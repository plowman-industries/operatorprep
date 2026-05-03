<?php
/**
 * Fixes the pricing page: replaces hardcoded "$29" (before <span class="opp-cents">)
 * with "$19" for the T5/D5/WW5 Chief-grade price cards.
 *
 * The price is rendered as: $29<span class="opp-cents">.99</span>
 * so a search for "$29.99" never matched. This patches the split HTML directly.
 */
echo "Patching pricing page split-HTML prices...\n";

$page = get_page_by_path('pricing');
if (!$page) { echo "ERROR: pricing page not found\n"; exit; }

$c       = $page->post_content;
$original = $c;

// Count occurrences before
$before = substr_count($c, '>$29<span');
echo "Occurrences of '>$29<span' before: {$before}\n";

// Replace $29 (before .99 span) with $19 in all opp-price-amount divs
$c = str_replace(
    '>$29<span class="opp-cents">.99</span>',
    '>$19<span class="opp-cents">.99</span>',
    $c
);

$after = substr_count($c, '>$29<span');
$fixed = $before - $after;
echo "Replaced {$fixed} occurrence(s). Remaining: {$after}\n";

if ($c === $original) {
    echo "No changes made.\n";
    exit;
}

$result = wp_update_post(['ID' => $page->ID, 'post_content' => $c]);
if (is_wp_error($result)) {
    echo "ERROR: " . $result->get_error_message() . "\n";
    exit;
}

wp_cache_flush();
if (function_exists('sg_cachepress_purge_cache')) {
    sg_cachepress_purge_cache();
}

echo "DONE — pricing page updated.\n";
