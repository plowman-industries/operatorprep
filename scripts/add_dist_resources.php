<?php
/**
 * Finds and updates the D1-D5 study guide hub snippet to add an EPA Resources card.
 * Run via: wp eval-file scripts/add_dist_resources.php --allow-root
 */
global $wpdb;

// ── 1. Find the snippet(s) that generate the distribution study guide hub ──
$snippets = $wpdb->get_results(
    "SELECT id, title, code FROM ugk_snippets
     WHERE (code LIKE '%opp-sg-hub%' OR code LIKE '%sg-card%' OR code LIKE '%d1-study%' OR code LIKE '%d1_study%')
     AND active = 1
     LIMIT 20"
);

echo "=== Matching snippets ===" . PHP_EOL;
foreach ($snippets as $s) {
    echo "ID={$s->id} title='{$s->title}'" . PHP_EOL;
    echo substr($s->code, 0, 300) . PHP_EOL;
    echo "---" . PHP_EOL;
}

// ── 2. Also check page content for shortcodes ──
$pages = $wpdb->get_results(
    "SELECT ID, post_title, post_name, post_content FROM {$wpdb->posts}
     WHERE post_name LIKE '%study-guide%' AND post_status='publish'
     AND (post_content LIKE '%opp_%' OR post_content LIKE '%shortcode%')
     LIMIT 10"
);

echo PHP_EOL . "=== Study Guide Pages ===" . PHP_EOL;
foreach ($pages as $p) {
    echo "ID={$p->ID} slug='{$p->post_name}' title='{$p->post_title}'" . PHP_EOL;
    echo "Content: " . substr($p->post_content, 0, 200) . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo "DONE" . PHP_EOL;
