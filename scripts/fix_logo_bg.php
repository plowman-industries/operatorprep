<?php
/**
 * Dump the distribution diagram page content
 */
$post = get_post(1094); // /d1-d2-distribution-diagram/
if (!$post) {
    // try by slug
    $posts = get_posts(['name' => 'd1-d2-distribution-diagram', 'post_type' => 'page', 'numberposts' => 1]);
    if ($posts) $post = $posts[0];
}
if (!$post) { echo "NOT FOUND" . PHP_EOL; exit; }
echo "ID: " . $post->ID . PHP_EOL;
echo "Title: " . $post->post_title . PHP_EOL;
echo "=== CONTENT ===" . PHP_EOL;
echo $post->post_content . PHP_EOL;
echo "=== END ===" . PHP_EOL;
