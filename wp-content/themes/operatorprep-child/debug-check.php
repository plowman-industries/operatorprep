<?php
// Read-only: check what's actually in post 35 post_content
global $wpdb;
$row = $wpdb->get_row("SELECT ID, post_title, post_status, LEFT(post_content,200) as snippet FROM {$wpdb->posts} WHERE ID=35");
echo 'DB-ID:'.$row->ID.' STATUS:'.$row->post_status."\n";
echo 'DB-SNIPPET:'.preg_replace('/\s+/',' ',$row->snippet)."\n";
$front = get_option('page_on_front');
echo 'PAGE_ON_FRONT:'.$front."\n";
