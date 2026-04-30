<?php
/**
 * Shows the block structure of D1 D2 page to find why some sections are dark.
 */
global $wpdb;
$content = get_post_field('post_content', 1101);

// Split by wp:html blocks
$blocks = parse_blocks($content);
echo "=== Block count: " . count($blocks) . " ===" . PHP_EOL;
foreach ($blocks as $i => $block) {
    $inner = substr($block['innerHTML'] ?? '', 0, 200);
    echo PHP_EOL . "Block {$i}: type={$block['blockName']}" . PHP_EOL;
    // Find first div ID in this block
    if (preg_match('/<div\s[^>]*id=["\']([^"\']+)["\']/', $inner, $m)) {
        echo "  First div id: #{$m[1]}" . PHP_EOL;
    }
    // Find all container div IDs
    if (preg_match_all('/<div\s[^>]*id=["\']([^"\']+)["\']/', $block['innerHTML'] ?? '', $ids)) {
        echo "  All div IDs: " . implode(', ', array_slice($ids[1], 0, 6)) . PHP_EOL;
    }
    echo "  innerHTML preview: " . htmlspecialchars(substr(preg_replace('/\s+/', ' ', $inner), 0, 150)) . PHP_EOL;
}
echo PHP_EOL . "DONE" . PHP_EOL;
