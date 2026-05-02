<?php
/**
 * Find distribution study guide hub snippets and add EPA Resources card.
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
global $wpdb;

// ── 1. Find all snippets containing opp-sg-hub or sg-card ──
$snippets = $wpdb->get_results(
    "SELECT id, title, code FROM ugk_snippets
     WHERE code LIKE '%opp-sg-hub%' OR code LIKE '%sg-card%'
     ORDER BY id ASC LIMIT 30"
);

echo "=== Snippets with sg-hub/sg-card ===" . PHP_EOL;
foreach ($snippets as $s) {
    echo "ID={$s->id} title='{$s->title}'" . PHP_EOL;
    // Check if it mentions distribution levels
    $is_dist = preg_match('/\b[dD][1-5]\b|distribution|opp_d[1-5]/i', $s->code);
    echo "  Is distribution: " . ($is_dist ? 'YES' : 'no') . PHP_EOL;
    echo "  Code snippet: " . substr($s->code, 0, 200) . PHP_EOL;
    echo "---" . PHP_EOL;
}

// ── 2. Find study guide pages to see shortcodes used ──
$pages = $wpdb->get_results(
    "SELECT ID, post_title, post_name, post_content FROM {$wpdb->posts}
     WHERE post_name REGEXP 'd[1-5]-study-guide' AND post_status='publish'
     LIMIT 10"
);

echo PHP_EOL . "=== D1-D5 Study Guide Pages ===" . PHP_EOL;
foreach ($pages as $p) {
    echo "ID={$p->ID} slug='{$p->post_name}'" . PHP_EOL;
    echo "  Content: " . substr($p->post_content, 0, 300) . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo "DONE" . PHP_EOL;
