<?php
/**
 * Dumps the full CSS and structure of D1 D2 Quick Reference to find mismatch.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;
$content = get_post_field('post_content', 1101);

// Show ALL style blocks
preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $content, $m);
echo "=== All style blocks in ID 1101 ===" . PHP_EOL;
foreach ($m[1] as $i => $block) {
    if (strlen(trim($block)) > 50) {
        echo PHP_EOL . "--- Block " . ($i+1) . " (" . strlen($block) . " chars) ---" . PHP_EOL;
        echo $block . PHP_EOL;
    }
}

// Show all container div IDs
preg_match_all('/<div\s[^>]*id=["\']([^"\']+)["\'][^>]*>/i', $content, $ids);
echo PHP_EOL . "=== All div IDs ===" . PHP_EOL;
foreach ($ids[1] as $id) { echo "  #" . $id . PHP_EOL; }

echo PHP_EOL . "DONE" . PHP_EOL;
