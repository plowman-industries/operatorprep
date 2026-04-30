<?php
/**
 * Dumps full CSS from sample pages to plan white background override.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;

// Show full first <style> block for one Quick Ref and one Study Guide
foreach ([1097, 1209] as $id) {
    $post = get_post($id);
    $content = $post->post_content;
    // Extract ALL style blocks
    preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $content, $m);
    echo PHP_EOL . "=== ID {$id}: {$post->post_title} ===" . PHP_EOL;
    foreach ($m[1] as $i => $block) {
        if (strlen($block) > 100) { // skip trivial ones
            echo "--- Style block " . ($i+1) . " ---" . PHP_EOL;
            echo $block . PHP_EOL;
        }
    }
}
echo PHP_EOL . "DONE" . PHP_EOL;
