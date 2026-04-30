<?php
/**
 * Dumps all unique div class names from D1 D2 Quick Reference page.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;

// D1 D2 Quick Reference = ID 1101
$content = get_post_field('post_content', 1101);

// Find all class attributes on divs
preg_match_all('/<(?:div|section|details|summary)[^>]+class=["\']([^"\']+)["\'][^>]*>/i', $content, $m);
$classes = [];
foreach ($m[1] as $cls) {
    foreach (explode(' ', $cls) as $c) {
        $c = trim($c);
        if ($c) $classes[$c] = ($classes[$c] ?? 0) + 1;
    }
}
arsort($classes);

echo "=== All element classes in D1D2 Quick Reference (ID 1101) ===" . PHP_EOL;
foreach ($classes as $c => $count) {
    echo "  [{$count}x] {$c}" . PHP_EOL;
}

// Also show first 1200 chars of the content to see structure
echo PHP_EOL . "=== Content snippet ===" . PHP_EOL;
// Find where the dark sections start - look for the second style block or second section type
preg_match_all('/<div\s+class=["\']([^"\']*section[^"\']*)["\'][^>]*>/i', $content, $sm);
echo "Section div classes found:" . PHP_EOL;
foreach (array_unique($sm[1]) as $sc) {
    echo "  " . $sc . PHP_EOL;
}

echo PHP_EOL . "DONE" . PHP_EOL;
