<?php
// Dump full content of ww1-study-guide (ID 1250)
$page = get_post(1250);
if (!$page) { echo "not found\n"; exit; }
echo $page->post_content;
