<?php
/**
 * Deeper diagnosis for D1-D5 flashcard loading issues.
 * Run via: wp eval-file scripts/debug_dist_fc.php --allow-root
 */
global $wpdb;

// 1. Check snippet 26 code structure
$row = $wpdb->get_row("SELECT id, active, code FROM ugk_snippets WHERE id=26");
echo "=== Snippet 26 structure ===" . PHP_EOL;
echo "Active: " . $row->active . PHP_EOL;
echo "Total bytes: " . strlen($row->code) . PHP_EOL;
// Check for function_exists guard
echo "Has fn guard: " . (strpos($row->code, 'function_exists') !== false ? 'YES' : 'NO') . PHP_EOL;
// Check shortcode registrations
echo "Has opp_d1_flashcards: " . (strpos($row->code, 'opp_d1_flashcards') !== false ? 'YES' : 'NO') . PHP_EOL;
echo "Has opp_d5_flashcards: " . (strpos($row->code, 'opp_d5_flashcards') !== false ? 'YES' : 'NO') . PHP_EOL;
// Check for PHP errors via token check
$tokens = @token_get_all($row->code);
$errors = error_get_last();
echo "PHP parse errors: " . ($errors ? json_encode($errors) : 'none') . PHP_EOL;

// 2. Check if shortcodes are registered
echo PHP_EOL . "=== Shortcode registration ===" . PHP_EOL;
$sc_list = ['opp_d1_flashcards','opp_d2_flashcards','opp_d3_flashcards','opp_d4_flashcards','opp_d5_flashcards'];
foreach ($sc_list as $sc) {
    echo $sc . ': ' . (shortcode_exists($sc) ? 'REGISTERED' : 'NOT REGISTERED') . PHP_EOL;
}

// 3. Test actual shortcode output (as subscriber without product access)
echo PHP_EOL . "=== Shortcode output test ===" . PHP_EOL;
// Find a subscriber user
$subscribers = get_users(['role' => 'subscriber', 'number' => 3]);
echo "Found " . count($subscribers) . " subscriber(s)" . PHP_EOL;
if ($subscribers) {
    $sub = $subscribers[0];
    wp_set_current_user($sub->ID);
    echo "Testing as user: {$sub->user_login} (ID: {$sub->ID})" . PHP_EOL;
    // Check access
    $pid = function_exists('opp_get_product_id') ? opp_get_product_id('d1') : 'fn_missing';
    $has = function_exists('opp_has_access') ? (opp_has_access($pid) ? 'YES' : 'NO') : 'fn_missing';
    echo "D1 product_id={$pid} has_access={$has}" . PHP_EOL;
    // Check shortcode output snippet
    $output = do_shortcode('[opp_d1_flashcards]');
    $first200 = substr(strip_tags($output), 0, 200);
    echo "Output (first 200 stripped): " . trim($first200) . PHP_EOL;

    // Also check REST as this user
    $req = new WP_REST_Request('GET', '/opp-study/v1/d1/categories');
    $resp = rest_do_request($req);
    $data = rest_get_server()->response_to_data($resp, false);
    $status = $resp->get_status();
    echo "REST /d1/categories as subscriber: HTTP {$status} - " . (isset($data['code']) ? "[{$data['code']}]" : count($data)." cats") . PHP_EOL;
}

// 4. Check REST as admin but look at permission callback
echo PHP_EOL . "=== Permission callback check ===" . PHP_EOL;
$admin = get_users(['role' => 'administrator', 'number' => 1]);
wp_set_current_user($admin[0]->ID);
$server = rest_get_server();
$routes = $server->get_routes();
foreach (['/opp-study/v1/d1/categories', '/opp-study/v1/(?P<level>t1|t3|t4)/categories'] as $route) {
    if (isset($routes[$route])) {
        $cb = $routes[$route][0]['permission_callback'] ?? 'none';
        if (is_string($cb)) {
            echo $route . ': permission_callback=' . $cb . PHP_EOL;
        } elseif (is_array($cb)) {
            echo $route . ': permission_callback=[array]' . PHP_EOL;
        } else {
            echo $route . ': permission_callback=[closure/other]' . PHP_EOL;
        }
    } else {
        echo $route . ': NOT FOUND in routes' . PHP_EOL;
    }
}

echo PHP_EOL . "DONE" . PHP_EOL;
