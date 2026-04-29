<?php
/**
 * Audits flashcard categories for all levels, looking for math-related entries.
 * Run via: wp eval-file scripts/audit_fc_categories.php --allow-root
 */

$levels = ['t1','t2','t3','t4','t5','d1','d2','d3','d4','d5','ww1','ww2','ww3','ww4','ww5'];

foreach ($levels as $level) {
    if ($level === 't2') {
        // T2 uses base endpoint (no level prefix)
        $request = new WP_REST_Request('GET', '/opp-study/v1/categories');
    } else {
        $request = new WP_REST_Request('GET', "/opp-study/v1/{$level}/categories");
    }
    $response = rest_do_request($request);
    if (is_wp_error($response)) {
        echo strtoupper($level) . ": WP_Error - " . $response->get_error_message() . PHP_EOL;
        continue;
    }
    $data = rest_get_server()->response_to_data($response, false);
    if (!is_array($data)) {
        echo strtoupper($level) . ": unexpected response" . PHP_EOL;
        continue;
    }
    $names = array_column($data, 'name');
    $math = array_filter($names, function($n) { return stripos($n, 'math') !== false; });
    $flag = count($math) ? '  *** MATH FOUND: ' . implode(', ', $math) . ' ***' : '';
    echo strtoupper($level) . ' (' . count($names) . ' cats): ' . implode(' | ', $names) . $flag . PHP_EOL;
}
echo "DONE" . PHP_EOL;
