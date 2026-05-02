<?php
/**
 * Discovery: find T1-T5 study guide hub pages
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
echo "Searching for T1-T5 study guide hub pages..." . PHP_EOL;

$slugs = array('t1-study-guide','t2-study-guide','t3-study-guide','t4-study-guide','t5-study-guide');

foreach ($slugs as $slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        echo "FOUND: ID={$page->ID} slug={$page->post_name} title={$page->post_title}" . PHP_EOL;
        // Show first 300 chars of content to check structure
        echo "  CONTENT_PEEK: " . substr(str_replace("\n",' ',$page->post_content), 0, 300) . PHP_EOL;
    } else {
        echo "NOT FOUND: $slug" . PHP_EOL;
        // Try alternate slugs
        $alt = get_page_by_path(str_replace('-study-guide','',$slug).'-study-guide');
        if ($alt) echo "  ALT FOUND: ID={$alt->ID} slug={$alt->post_name}" . PHP_EOL;
    }
}

// Also search by title
$query = new WP_Query(array(
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    's'              => 'Study Guide',
));
echo PHP_EOL . "All 'Study Guide' pages:" . PHP_EOL;
foreach ($query->posts as $p) {
    echo "  ID={$p->ID} slug={$p->post_name} title={$p->post_title}" . PHP_EOL;
}

echo "DONE" . PHP_EOL;
