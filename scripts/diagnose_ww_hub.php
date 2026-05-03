<?php
/**
 * Diagnose WW study guide hub pages so we can inject the EPA resources card.
 * Shows page IDs, slugs, and a snapshot of the content/structure.
 */
global $wpdb;

echo "=== WW Study Guide Pages ===" . PHP_EOL;

// Find pages by slug pattern
$pages = $wpdb->get_results(
    "SELECT ID, post_title, post_name, LENGTH(post_content) AS content_len
     FROM {$wpdb->posts}
     WHERE post_name LIKE 'ww%-study-guide%' AND post_status = 'publish'
     ORDER BY post_name"
);

if ( empty($pages) ) {
    echo "No WW study guide pages found by slug." . PHP_EOL;
} else {
    foreach ( $pages as $p ) {
        echo "ID={$p->ID} slug='{$p->post_name}' title='{$p->post_title}' content_len={$p->content_len}" . PHP_EOL;
    }
}

// For each, show first 600 chars + check for EPA card + check for opp-sg-hub
if ( ! empty($pages) ) {
    echo PHP_EOL . "=== Content Snapshots ===" . PHP_EOL;
    foreach ( $pages as $p ) {
        $content = get_post( $p->ID )->post_content;
        $has_hub      = strpos( $content, 'opp-sg-hub' ) !== false;
        $has_epa      = strpos( $content, 'resources' ) !== false;
        $has_sim      = strpos( $content, 'sg-card' ) !== false;
        echo PHP_EOL . "--- {$p->post_name} (ID {$p->ID}) ---" . PHP_EOL;
        echo "has opp-sg-hub: " . ( $has_hub ? 'YES' : 'NO' ) . PHP_EOL;
        echo "has sg-card:    " . ( $has_sim ? 'YES' : 'NO' ) . PHP_EOL;
        echo "has 'resources': " . ( $has_epa ? 'YES' : 'NO' ) . PHP_EOL;
        echo "First 600 chars:" . PHP_EOL;
        echo substr( $content, 0, 600 ) . PHP_EOL;
        echo "---END---" . PHP_EOL;
    }
}

// Also check ugk_snippets for WW hub snippets
$snippets = $wpdb->get_results(
    "SELECT id, title, LEFT(code, 400) AS code_preview
     FROM ugk_snippets
     WHERE (code LIKE '%ww1-study%' OR code LIKE '%ww_study%' OR code LIKE '%opp-sg-hub%')
     AND active = 1
     LIMIT 10"
);

echo PHP_EOL . "=== ugk_snippets matches ===" . PHP_EOL;
if ( empty($snippets) ) {
    echo "None found." . PHP_EOL;
} else {
    foreach ( $snippets as $s ) {
        echo "id={$s->id} title='{$s->title}'" . PHP_EOL;
        echo $s->code_preview . PHP_EOL;
        echo "---" . PHP_EOL;
    }
}

echo PHP_EOL . "DONE" . PHP_EOL;
