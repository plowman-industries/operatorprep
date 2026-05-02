<?php
/**
 * Find T1-T2 plant simulator page and search for D1/D2/WW1/WW2 equivalents
 */
echo "=== T1-T2 Plant Simulator ===" . PHP_EOL;
$t12 = get_page_by_path('t1-t2-plant-diagram');
if ($t12) {
    echo "FOUND: ID={$t12->ID} slug={$t12->post_name} title={$t12->post_title} status={$t12->post_status}" . PHP_EOL;
} else {
    echo "NOT FOUND by slug t1-t2-plant-diagram" . PHP_EOL;
}

echo PHP_EOL . "=== Searching all pages for plant/simulator/diagram ===" . PHP_EOL;
$query = new WP_Query(array(
    'post_type'      => array('page', 'post'),
    'post_status'    => array('publish', 'draft', 'private'),
    'posts_per_page' => 50,
    's'              => 'plant',
));
foreach ($query->posts as $p) {
    echo "  ID={$p->ID} slug={$p->post_name} title={$p->post_title} status={$p->post_status}" . PHP_EOL;
}

echo PHP_EOL . "=== Searching for diagram/simulator ===" . PHP_EOL;
$q2 = new WP_Query(array(
    'post_type'      => array('page', 'post'),
    'post_status'    => array('publish', 'draft', 'private'),
    'posts_per_page' => 50,
    's'              => 'diagram',
));
foreach ($q2->posts as $p) {
    echo "  ID={$p->ID} slug={$p->post_name} title={$p->post_title} status={$p->post_status}" . PHP_EOL;
}

echo PHP_EOL . "=== Searching by slug patterns ===" . PHP_EOL;
$slugs = array('d1-plant-diagram','d2-plant-diagram','d1-d2-plant-diagram','ww1-plant-diagram','ww2-plant-diagram','ww1-ww2-plant-diagram','plant-simulator','distribution-plant-diagram','wastewater-plant-diagram');
foreach ($slugs as $slug) {
    $p = get_page_by_path($slug);
    if ($p) echo "  FOUND: $slug => ID={$p->ID} title={$p->post_title} status={$p->post_status}" . PHP_EOL;
    else echo "  NOT FOUND: $slug" . PHP_EOL;
}

echo "DONE" . PHP_EOL;
