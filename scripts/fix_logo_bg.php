<?php
/**
 * Discovery: dump D1 study guide page content so we can see the grid HTML.
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
$post = get_post(1246); // D1
if (!$post) { echo "NOT FOUND\n"; exit(1); }
echo "=== POST CONTENT START ===\n";
echo $post->post_content;
echo "\n=== POST CONTENT END ===\n";
