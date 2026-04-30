<?php
/**
 * Checks nesting depth of d12r-section divs to find if any fall outside #opp-d12-ref.
 * Also dumps ALL unique class prefixes across all cheat sheet pages.
 */
global $wpdb;

// Check D1D2 nesting
$content = get_post_field('post_content', 1101);
// Strip tags except div, track depth
$stripped = preg_replace('/<(?!\/?(div)[\s>])[^>]+>/i', '', $content);
$depth = 0;
$in_container = false;
$sections_inside = 0;
$sections_outside = 0;
$pos = 0;

echo "=== Checking nesting of #opp-d12-ref ===" . PHP_EOL;
while (preg_match('/<(\/?)div([^>]*)>/i', $content, $m, PREG_OFFSET_CAPTURE, $pos)) {
    $close = $m[1][0] === '/';
    $attrs = $m[2][0];
    $pos = $m[0][1] + strlen($m[0][0]);

    if (!$close) {
        $depth++;
        if (preg_match('/id=["\']opp-d12-ref["\']/', $attrs)) {
            $in_container = true;
            $container_depth = $depth;
            echo "Container opened at depth $depth" . PHP_EOL;
        }
        if (preg_match('/class=["\'][^"\']*d12r-section[^"\']*["\']/', $attrs)) {
            if ($in_container) {
                $sections_inside++;
            } else {
                $sections_outside++;
                echo "  SECTION OUTSIDE CONTAINER at depth $depth" . PHP_EOL;
            }
        }
    } else {
        if ($in_container && $depth === $container_depth) {
            $in_container = false;
            echo "Container closed at depth $depth" . PHP_EOL;
        }
        $depth--;
    }
}
echo "Sections inside container: $sections_inside" . PHP_EOL;
echo "Sections outside container: $sections_outside" . PHP_EOL;

// Collect all class prefixes across all cheat sheet pages
$page_ids = [1097,1098,1101,1102,1103,1104,1106,1160,1209,1230,1231,1232,1243,1244,1245,1246,1247,1248,1249,1250,1251,1252,1253,1254];
$all_prefixes = [];
echo PHP_EOL . "=== Section class prefixes across all pages ===" . PHP_EOL;
foreach ($page_ids as $id) {
    $c = get_post_field('post_content', $id);
    if (preg_match_all('/<div\s+class=["\']([^"\']*section[^"\']*)["\']/', $c, $m)) {
        foreach ($m[1] as $cls) {
            // Extract the prefix (e.g., "d12r" from "d12r-section")
            if (preg_match('/^([a-z0-9]+)-section/', $cls, $pm)) {
                $all_prefixes[$pm[1]] = $id;
            }
        }
    }
}
foreach ($all_prefixes as $prefix => $from_id) {
    echo "  .{$prefix}-section (from page {$from_id})" . PHP_EOL;
}

echo PHP_EOL . "DONE" . PHP_EOL;
