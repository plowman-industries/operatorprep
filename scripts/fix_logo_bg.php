<?php
/**
 * Remove Practice Tests (sim), Flashcards (flash), and Math Drills (drill)
 * cards from all study guide hub pages — they are redundant with their own sections.
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
echo "Removing redundant cards from all study guide hubs..." . PHP_EOL;

$page_ids = array(
    1160, 941, 1243, 1244, 1245,   // T1-T5
    1246, 1247, 1209, 1248, 1249,  // D1-D5
    1250, 1251, 1252, 1253, 1254,  // WW1-WW5
);

$updated = 0;
$skipped = 0;

foreach ($page_ids as $pid) {
    $post = get_post($pid);
    if (!$post) { echo "NOT FOUND: $pid" . PHP_EOL; continue; }

    $content  = $post->post_content;
    $original = $content;
    $slug     = $post->post_name;

    // Remove each redundant card type: sim (Practice Tests), flash (Flashcards), drill (Math Drills)
    // Pattern: optional leading whitespace + <a class="sg-card TYPE"...> ... </a> + optional trailing newline
    foreach (array('sim', 'flash', 'drill') as $type) {
        $content = preg_replace(
            '/\s*<a class="sg-card ' . $type . '"[^>]*>.*?<\/a>/s',
            '',
            $content
        );
    }

    if ($content === $original) {
        echo "SKIP (no changes): $slug" . PHP_EOL;
        $skipped++;
        continue;
    }

    wp_update_post(array('ID' => $pid, 'post_content' => $content));
    echo "UPDATED: $slug" . PHP_EOL;
    $updated++;
}

wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();

echo "Done -- updated: $updated, skipped: $skipped" . PHP_EOL;
echo "DONE" . PHP_EOL;
