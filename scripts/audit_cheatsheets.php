<?php
/**
 * Finds cheat sheet content across snippets and pages.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;

// Search snippets for cheat-related terms
$terms = ['cheat', 'quick.ref', 'reference.card', 'study.guide', 'opp-cs-', 'opp_cs_', 'cheatsheet'];
echo "=== Snippet search ===" . PHP_EOL;
foreach ($terms as $t) {
    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT id, name, active FROM ugk_snippets WHERE name LIKE %s OR code LIKE %s",
        "%{$t}%", "%{$t}%"
    ));
    if ($rows) {
        foreach ($rows as $r) {
            echo "  [{$t}] ID {$r->id} | active:{$r->active} | {$r->name}" . PHP_EOL;
        }
    }
}

// Search WordPress pages/posts
echo PHP_EOL . "=== Pages/posts with 'cheat' ===" . PHP_EOL;
$pages = $wpdb->get_results(
    "SELECT ID, post_title, post_status, post_type FROM {$wpdb->posts}
     WHERE (post_title LIKE '%cheat%' OR post_content LIKE '%cheat%' OR post_name LIKE '%cheat%')
     AND post_status != 'trash'
     ORDER BY post_type, ID LIMIT 30"
);
foreach ($pages as $p) {
    echo "  {$p->post_type} ID {$p->ID} [{$p->post_status}]: {$p->post_title}" . PHP_EOL;
    // Show shortcodes used in this page
    preg_match_all('/\[opp_\w+\]/', get_post_field('post_content', $p->ID), $sc);
    if ($sc[0]) echo "    Shortcodes: " . implode(', ', array_unique($sc[0])) . PHP_EOL;
}

// List ALL opp_ shortcodes registered that contain cs/cheat/ref
echo PHP_EOL . "=== All opp shortcodes containing cs/ref/sheet ===" . PHP_EOL;
global $shortcode_tags;
foreach (array_keys($shortcode_tags) as $sc) {
    if (preg_match('/cheat|_cs_|_ref|sheet/i', $sc)) {
        echo "  [{$sc}]" . PHP_EOL;
    }
}

// Dump ALL snippet names so we can spot cheat sheets by name
echo PHP_EOL . "=== All active snippets ===" . PHP_EOL;
$all = $wpdb->get_results("SELECT id, name FROM ugk_snippets WHERE active=1 ORDER BY id");
foreach ($all as $r) {
    echo "  ID {$r->id}: {$r->name}" . PHP_EOL;
}

echo PHP_EOL . "DONE" . PHP_EOL;
