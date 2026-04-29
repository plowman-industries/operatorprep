<?php
/**
 * Audits flashcard categories for all levels, looking for math-related entries.
 * Uses wp_remote_get with a real HTTP request (bypasses rest_do_request auth issues).
 * Run via: wp eval-file scripts/audit_fc_categories.php --allow-root
 */

$site_url = get_site_url();
$levels = ['t1','t2','t3','t4','t5','d1','d2','d3','d4','d5','ww1','ww2','ww3','ww4','ww5'];

// Also list ALL registered REST routes under opp-study namespace so we know what's available
$server = rest_get_server();
$routes = array_keys($server->get_routes());
$opp_routes = array_filter($routes, function($r) { return strpos($r, 'opp-study') !== false || strpos($r, 'opp/v1') !== false; });
sort($opp_routes);
echo "=== Registered OPP REST routes ===" . PHP_EOL;
foreach ($opp_routes as $r) { echo "  " . $r . PHP_EOL; }
echo PHP_EOL;

foreach ($levels as $level) {
    if ($level === 't2') {
        $url = $site_url . '/wp-json/opp-study/v1/categories';
    } else {
        $url = $site_url . '/wp-json/opp-study/v1/' . $level . '/categories';
    }
    $resp = wp_remote_get($url, ['timeout' => 15, 'sslverify' => false]);
    if (is_wp_error($resp)) {
        echo strtoupper($level) . ": ERROR - " . $resp->get_error_message() . PHP_EOL;
        continue;
    }
    $code = wp_remote_retrieve_response_code($resp);
    $body = wp_remote_retrieve_body($resp);
    $data = json_decode($body, true);
    if (!is_array($data)) {
        echo strtoupper($level) . ": HTTP {$code} - non-array response: " . substr($body, 0, 120) . PHP_EOL;
        continue;
    }
    // Handle both array of cats and WP error object
    if (isset($data['code'])) {
        echo strtoupper($level) . ": API error [{$data['code']}] " . ($data['message'] ?? '') . PHP_EOL;
        continue;
    }
    $names = array_column($data, 'name');
    $slugs = array_column($data, 'slug');
    $math = array_filter($names, function($n) { return stripos($n, 'math') !== false; });
    $flag = count($math) ? '  *** MATH: ' . implode(', ', $math) . ' ***' : '';
    echo strtoupper($level) . ' (' . count($names) . ' cats): ' . implode(' | ', $names) . $flag . PHP_EOL;
}
echo "DONE" . PHP_EOL;
