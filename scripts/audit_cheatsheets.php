<?php
/**
 * Checks Quick Reference / Study Guide page structure for CSS targeting.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;

$page_ids = [777,778,941,1097,1098,1101,1102,1103,1104,1105,1106,1160,1209,1230,1231,1232,1243,1244,1245,1246,1247,1248,1249,1250,1251,1252,1253,1254];

echo "=== Page templates & parents ===" . PHP_EOL;
foreach ($page_ids as $id) {
    $post = get_post($id);
    if (!$post) continue;
    $template = get_page_template_slug($id) ?: 'default';
    $parent   = $post->post_parent ? $post->post_parent : 'none';
    echo "ID {$id} | parent:{$parent} | template:{$template} | {$post->post_title}" . PHP_EOL;
}

// Show a sample page's raw content (first 600 chars)
echo PHP_EOL . "=== Sample content: ID 1097 (T4 Quick Reference) ===" . PHP_EOL;
$content = get_post_field('post_content', 1097);
echo substr($content, 0, 600) . PHP_EOL;

// Check for any custom CSS class on these pages (post meta)
echo PHP_EOL . "=== Custom CSS classes on ID 1097 ===" . PHP_EOL;
$css_class = get_post_meta(1097, '_elementor_css', true) ?: '(none)';
echo "elementor_css: " . (is_array($css_class) ? 'array' : substr($css_class, 0, 100)) . PHP_EOL;

// Check what body classes WordPress would assign
echo PHP_EOL . "=== Body class hints ===" . PHP_EOL;
// page-id-{id}, page-template-{template}, parent-pageid-{parent}
$sample = get_post(1097);
echo "Body classes would include: page-id-1097";
if ($sample->post_parent) echo ", page-parent, parent-pageid-{$sample->post_parent}";
echo PHP_EOL;

echo PHP_EOL . "DONE" . PHP_EOL;
