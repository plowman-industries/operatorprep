<?php
/**
 * Audits flashcard categories for all levels, looking for math-related entries.
 * Run via: wp eval-file scripts/audit_fc_categories.php --allow-root
 */

// Set current user to admin so REST auth passes
$admin = get_users(['role' => 'administrator', 'number' => 1]);
if ($admin) { wp_set_current_user($admin[0]->ID); }

$levels = ['t1','t2','t3','t4','t5','d1','d2','d3','d4','d5','ww1','ww2','ww3','ww4','ww5'];

// Show registered opp-study routes
$server = rest_get_server();
$routes = array_keys($server->get_routes());
$opp = array_filter($routes, function($r) { return strpos($r, 'opp') !== false; });
sort($opp);
echo "=== OPP routes ===" . PHP_EOL;
foreach ($opp as $r) { echo "  $r" . PHP_EOL; }
echo PHP_EOL;

foreach ($levels as $level) {
    if ($level === 't2') {
        $path = '/opp-study/v1/categories';
    } else {
        $path = '/opp-study/v1/' . $level . '/categories';
    }
    $req  = new WP_REST_Request('GET', $path);
    $resp = rest_do_request($req);
    $data = rest_get_server()->response_to_data($resp, false);

    if (!is_array($data)) {
        echo strtoupper($level) . ": non-array - " . json_encode($data) . PHP_EOL;
        continue;
    }
    if (isset($data['code'])) {
        echo strtoupper($level) . ": error [{$data['code']}] " . ($data['message'] ?? '') . PHP_EOL;
        continue;
    }

    $names = array_column($data, 'name');
    $math  = array_filter($names, function($n) { return stripos($n, 'math') !== false; });
    $flag  = count($math) ? '  *** MATH: ' . implode(', ', $math) . ' ***' : '';
    echo strtoupper($level) . ' (' . count($names) . ' cats): ' . implode(' | ', $names) . $flag . PHP_EOL;
}
echo "DONE" . PHP_EOL;
