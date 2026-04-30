<?php
/**
 * Extracts root container IDs and backgrounds from all cheat sheet pages.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;

$page_ids = [777,778,941,1097,1098,1101,1102,1103,1104,1106,1160,1209,1230,1231,1232,1243,1244,1245,1246,1247,1248,1249,1250,1251,1252,1253,1254];

echo "=== Root container IDs and backgrounds ===" . PHP_EOL;
foreach ($page_ids as $id) {
    $post = get_post($id);
    if (!$post) continue;
    $content = $post->post_content;
    // Find the first div with an id attribute
    if (preg_match('/<div\s+id=["\']([^"\']+)["\']/', $content, $m)) {
        $div_id = $m[1];
    } else {
        $div_id = '(none found)';
    }
    // Find background color declarations in the first style block
    if (preg_match('/<style>(.*?)<\/style>/s', $content, $sm)) {
        preg_match_all('/background[^;:]*:\s*([^;]+);/', $sm[1], $bm);
        $bg = isset($bm[1][0]) ? trim($bm[1][0]) : '(none)';
    } else {
        $bg = '(no style block)';
    }
    echo "ID {$id} | div_id=#{$div_id} | bg={$bg} | {$post->post_title}" . PHP_EOL;
}
echo PHP_EOL . "DONE" . PHP_EOL;
