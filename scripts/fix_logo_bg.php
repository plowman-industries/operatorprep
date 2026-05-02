<?php
/**
 * Show the last 800 chars of T1 and T2 post content
 */
foreach (array(1160, 941) as $pid) {
    $post = get_post($pid);
    $content = $post->post_content;
    echo "=== {$post->post_name} (last 800 chars) ===" . PHP_EOL;
    echo substr($content, -800) . PHP_EOL . PHP_EOL;
}
echo "DONE" . PHP_EOL;
