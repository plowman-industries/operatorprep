<?php
/**
 * Diagnoses why D1-D5 flashcards aren't loading.
 * Run via: wp eval-file scripts/debug_dist_fc.php --allow-root
 */

// Set admin context
$admin = get_users(['role' => 'administrator', 'number' => 1]);
if ($admin) wp_set_current_user($admin[0]->ID);

$levels = ['d1','d2','d3','d4','d5'];

// 1. Check opp_get_product_id + opp_has_access for each level
echo "=== Access check ===" . PHP_EOL;
foreach ($levels as $lvl) {
    if (!function_exists('opp_get_product_id')) {
        echo "ERROR: opp_get_product_id() not found" . PHP_EOL;
        break;
    }
    $pid = opp_get_product_id($lvl);
    $has = function_exists('opp_has_access') ? (opp_has_access($pid) ? 'YES' : 'NO') : 'fn missing';
    echo strtoupper($lvl) . ": product_id={$pid}  has_access={$has}" . PHP_EOL;
}

// 2. Check categories REST endpoint for each
echo PHP_EOL . "=== REST /categories ===" . PHP_EOL;
foreach ($levels as $lvl) {
    $req  = new WP_REST_Request('GET', "/opp-study/v1/{$lvl}/categories");
    $resp = rest_do_request($req);
    $data = rest_get_server()->response_to_data($resp, false);
    $status = $resp->get_status();
    if (isset($data['code'])) {
        echo strtoupper($lvl) . " HTTP {$status}: [{$data['code']}] " . ($data['message'] ?? '') . PHP_EOL;
    } elseif (is_array($data)) {
        $names = array_column($data, 'name');
        echo strtoupper($lvl) . " HTTP {$status}: " . count($names) . " cats - " . implode(' | ', $names) . PHP_EOL;
    } else {
        echo strtoupper($lvl) . " HTTP {$status}: " . json_encode($data) . PHP_EOL;
    }
}

// 3. Check one quiz endpoint (first category slug from D1)
echo PHP_EOL . "=== REST /quiz/{slug} sample (D1 first cat) ===" . PHP_EOL;
$req  = new WP_REST_Request('GET', '/opp-study/v1/d1/categories');
$resp = rest_do_request($req);
$cats = rest_get_server()->response_to_data($resp, false);
if (is_array($cats) && !isset($cats['code']) && count($cats)) {
    $first = $cats[0];
    echo "Trying slug: " . $first['slug'] . PHP_EOL;
    $req2  = new WP_REST_Request('GET', '/opp-study/v1/d1/quiz/' . $first['slug']);
    $resp2 = rest_do_request($req2);
    $data2 = rest_get_server()->response_to_data($resp2, false);
    $status2 = $resp2->get_status();
    if (isset($data2['code'])) {
        echo "D1 quiz HTTP {$status2}: [{$data2['code']}] " . ($data2['message'] ?? '') . PHP_EOL;
    } else {
        $qcount = isset($data2['questions']) ? count($data2['questions']) : 'N/A';
        echo "D1 quiz HTTP {$status2}: {$qcount} questions" . PHP_EOL;
    }
} else {
    echo "Skipped (no cats from D1)" . PHP_EOL;
}

// 4. Check snippet 26 is active
global $wpdb;
$row = $wpdb->get_row("SELECT id, active, name FROM ugk_snippets WHERE id=26");
echo PHP_EOL . "=== Snippet 26 ===" . PHP_EOL;
if ($row) {
    echo "ID: {$row->id} | active: {$row->active} | name: {$row->name}" . PHP_EOL;
} else {
    echo "NOT FOUND" . PHP_EOL;
}

echo PHP_EOL . "DONE" . PHP_EOL;
