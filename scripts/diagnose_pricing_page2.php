<?php
/**
 * Deep-dive: how does the pricing page render $29.99?
 * Check the full post_content around price mentions, and render a snippet
 * to see what the shortcode actually outputs.
 */
$page = get_page_by_path('pricing');
if (!$page) { echo "not found\n"; exit; }

echo "Page ID: {$page->ID}\n";

// Search for any price pattern in post_content
preg_match_all('/[2-3][0-9]\.[0-9]{2}/', $page->post_content, $matches, PREG_OFFSET_CAPTURE);
echo "Price patterns found in post_content: " . count($matches[0]) . "\n";
foreach ($matches[0] as $m) {
    $pos = $m[1];
    echo "  Found '{$m[0]}' at pos {$pos}: ..." . substr($page->post_content, max(0, $pos-60), 140) . "...\n";
}

// Check for WooCommerce shortcodes
preg_match_all('/\[woo[^\]]*\]|\[product[^\]]*\]|\[add_to_cart[^\]]*\]/i', $page->post_content, $sc);
echo "\nWooCommerce shortcodes found: " . count($sc[0]) . "\n";
foreach ($sc[0] as $s) { echo "  $s\n"; }

// Search for any [product or [wc_ shortcodes
preg_match_all('/\[[\w_-]+[^\]]*\]/', $page->post_content, $all_sc);
echo "\nAll shortcodes: " . implode(', ', array_unique($all_sc[0])) . "\n";

// Search for product IDs 578, 583, 588 anywhere in content
foreach ([578, 583, 588] as $pid) {
    $pos = strpos($page->post_content, (string)$pid);
    if ($pos !== false) {
        echo "\nProduct ID $pid found at pos $pos: ..." . substr($page->post_content, max(0,$pos-80), 200) . "...\n";
    }
}

// Check if there's any object cache still holding old price
if (function_exists('wp_cache_get')) {
    $cached = wp_cache_get('woocommerce_get_price_578', 'product');
    echo "\nObject cache 'woocommerce_get_price_578': " . var_export($cached, true) . "\n";
}

// Actually render a snippet of the pricing page content
echo "\n--- Searching post_content for 'Chief' context ---\n";
$positions = [];
$offset = 0;
while (($pos = strpos($page->post_content, 'Chief', $offset)) !== false) {
    $positions[] = $pos;
    $offset = $pos + 1;
}
foreach (array_slice($positions, 0, 3) as $pos) {
    echo substr($page->post_content, max(0, $pos-20), 200) . "\n---\n";
}
