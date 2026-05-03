<?php
/**
 * Diagnoses where $29.99 is stored on the pricing page.
 * Checks post_content AND all postmeta for the /pricing/ page.
 */
echo "Diagnosing pricing page for 29.99...\n\n";

$page = get_page_by_path('pricing');
if (!$page) {
    // Try other common slugs
    $page = get_page_by_path('pricing-page');
}
if (!$page) {
    $pages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1]);
    foreach ($pages as $p) {
        if (strpos(strtolower($p->post_title), 'pric') !== false) {
            $page = $p;
            break;
        }
    }
}

if (!$page) {
    echo "ERROR: pricing page not found\n";
    exit;
}

echo "Found page: ID={$page->ID}, slug={$page->post_name}, title={$page->post_title}\n\n";

// Check post_content
$in_content = strpos($page->post_content, '29.99') !== false;
echo "post_content contains 29.99: " . ($in_content ? "YES" : "no") . "\n";
echo "post_content length: " . strlen($page->post_content) . " chars\n\n";

// Check ALL postmeta
$meta = get_post_meta($page->ID);
echo "Checking " . count($meta) . " postmeta entries for 29.99...\n";
foreach ($meta as $key => $values) {
    foreach ($values as $val) {
        if (strpos((string)$val, '29.99') !== false) {
            $snippet = substr((string)$val, max(0, strpos((string)$val, '29.99') - 50), 120);
            echo "  FOUND in meta key [{$key}]: ...{$snippet}...\n";
        }
    }
}

// Also show what page builder is active
$elementor = get_post_meta($page->ID, '_elementor_edit_mode', true);
$divi = get_post_meta($page->ID, '_et_pb_use_builder', true);
$beaver = get_post_meta($page->ID, '_fl_builder_enabled', true);
echo "\nPage builder detection:\n";
echo "  Elementor: " . ($elementor ? $elementor : 'not active') . "\n";
echo "  Divi: " . ($divi ? $divi : 'not active') . "\n";
echo "  Beaver: " . ($beaver ? $beaver : 'not active') . "\n";

// Show first 500 chars of post_content
echo "\npost_content preview:\n" . substr($page->post_content, 0, 500) . "\n";
