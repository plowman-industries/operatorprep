<?php
/**
 * Diagnostic: dump the script portion of the distribution diagram page
 */
$post = get_post(1094);
if (!$post) { echo "NOT FOUND" . PHP_EOL; exit; }
$c = $post->post_content;
// Find the <script> tag
$start = strpos($c, '<script>');
$end   = strpos($c, '</script>', $start);
if ($start === false) { echo "NO SCRIPT TAG FOUND" . PHP_EOL; }
else {
    echo "Script tag found at offset $start" . PHP_EOL;
    if ($end === false) {
        echo "NO CLOSING </script> TAG FOUND - this is the bug!" . PHP_EOL;
        // Show last 200 chars
        echo "Last 300 chars of content:" . PHP_EOL;
        echo substr($c, -300) . PHP_EOL;
    } else {
        echo "Closing </script> found at offset $end" . PHP_EOL;
        echo "Script length: " . ($end - $start) . " chars" . PHP_EOL;
        // Show last 100 chars of the script
        echo "Last 100 chars of script:" . PHP_EOL;
        echo substr($c, $end - 100, 100) . PHP_EOL;
        echo "...CLOSING TAG..." . PHP_EOL;
        echo substr($c, $end, 20) . PHP_EOL;
    }
}
// Also check for <\/script>
$backslash_close = strpos($c, '<\/script>');
echo PHP_EOL;
echo "Contains '<\\/script>': " . ($backslash_close !== false ? "YES at offset $backslash_close" : "NO") . PHP_EOL;
echo "DONE" . PHP_EOL;
