<?php
/**
 * Check T1 and T2 page content around the grid close area
 */
foreach (array(1160, 941) as $pid) {
    $post = get_post($pid);
    $content = $post->post_content;
    // Find sg-cards area - show last 800 chars before sg-back or end of sg-cards
    $pos = strpos($content, 'sg-back');
    if ($pos !== false) {
        echo "=== {$post->post_name} - around sg-back ===" . PHP_EOL;
        echo substr($content, max(0, $pos - 300), 500) . PHP_EOL;
    } else {
        echo "=== {$post->post_name} - NO sg-back found, last 600 chars ===" . PHP_EOL;
        echo substr($content, -600) . PHP_EOL;
    }
    echo PHP_EOL;
}
echo "DONE" . PHP_EOL;
