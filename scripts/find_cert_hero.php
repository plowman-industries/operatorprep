<?php
/**
 * Find the certifications page hero content
 * Run via: wp eval-file scripts/find_cert_hero.php --allow-root
 */
global $wpdb;

// Check for a page with 'certifications' in slug
$pages = $wpdb->get_results("SELECT ID, post_title, post_name, post_content FROM {$wpdb->posts} WHERE post_name LIKE '%certif%' AND post_status = 'publish' LIMIT 10");
echo "=== Pages ===" . PHP_EOL;
foreach ($pages as $p) {
    echo "ID={$p->ID} title='{$p->post_title}' slug='{$p->post_name}'" . PHP_EOL;
    echo "Content snippet: " . substr($p->post_content, 0, 500) . PHP_EOL;
    echo "---" . PHP_EOL;
}

// Check snippets for certifications content
$snippets = $wpdb->get_results("SELECT id, title, code FROM ugk_snippets WHERE code LIKE '%CERTIFICATION%' OR code LIKE '%3 TRACKS%' OR title LIKE '%certif%' LIMIT 10");
echo PHP_EOL . "=== Snippets ===" . PHP_EOL;
foreach ($snippets as $s) {
    echo "ID={$s->id} title='{$s->title}'" . PHP_EOL;
    echo "Code snippet: " . substr($s->code, 0, 500) . PHP_EOL;
    echo "---" . PHP_EOL;
}
echo "DONE" . PHP_EOL;
