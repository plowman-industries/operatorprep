<?php
/**
 * Restore Plant Simulation card to T1 and T2 study guide hubs.
 * It was accidentally removed because it shared the 'sim' class with Practice Tests.
 */
echo "Restoring Plant Simulation card to T1 and T2..." . PHP_EOL;

$plant_card  = '  <a class="sg-card sim" href="/t1-t2-plant-diagram/">' . "\n";
$plant_card .= '    <div class="sg-icon">&#x1F3ED;</div>' . "\n";
$plant_card .= '    <h2>Plant Simulation</h2>' . "\n";
$plant_card .= '    <p class="sg-desc">Explore an interactive water treatment plant diagram. Click on each component to learn how it works and understand the full treatment process.</p>' . "\n";
$plant_card .= '    <span class="sg-btn">Launch Simulation &#x2192;</span>' . "\n";
$plant_card .= '  </a>';

// Insert at the start of the sg-cards grid
$grid_open = '<div class="sg-cards">';

foreach (array(1160 => 't1-study-guide', 941 => 't2-study-guide') as $pid => $slug) {
    $post = get_post($pid);
    if (!$post) { echo "NOT FOUND: $pid" . PHP_EOL; continue; }
    $content = $post->post_content;

    // Already restored?
    if (strpos($content, 't1-t2-plant-diagram') !== false) {
        echo "SKIP (already present): $slug" . PHP_EOL;
        continue;
    }

    if (strpos($content, $grid_open) === false) {
        echo "ERROR: grid open not found in $slug" . PHP_EOL;
        continue;
    }

    $content = str_replace(
        $grid_open . "\n",
        $grid_open . "\n" . $plant_card . "\n",
        $content
    );

    wp_update_post(array('ID' => $pid, 'post_content' => $content));
    echo "RESTORED: $slug" . PHP_EOL;
}

wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
