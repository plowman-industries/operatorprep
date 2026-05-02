<?php
/**
 * Restore simulator cards to D1, D2, WW1, WW2 study guide hubs.
 * Pages exist — cards were accidentally removed with the sim class purge.
 */
echo "Restoring simulator cards to D1/D2 and WW1/WW2..." . PHP_EOL;

$grid_open = '<div class="sg-cards">';

$targets = array(
    // D1 and D2 -> Distribution System Diagram
    1246 => array(
        'slug' => 'd1-study-guide',
        'href' => '/d1-d2-distribution-diagram/',
        'icon' => '&#x1F3ED;',
        'title' => 'Distribution System Diagram',
        'desc'  => 'Explore an interactive distribution system diagram. Click on each component to learn how it works and understand the full distribution process.',
        'btn'   => 'Launch Simulation &#x2192;',
    ),
    1247 => array(
        'slug' => 'd2-study-guide',
        'href' => '/d1-d2-distribution-diagram/',
        'icon' => '&#x1F3ED;',
        'title' => 'Distribution System Diagram',
        'desc'  => 'Explore an interactive distribution system diagram. Click on each component to learn how it works and understand the full distribution process.',
        'btn'   => 'Launch Simulation &#x2192;',
    ),
    // WW1 and WW2 -> Wastewater Plant Diagram
    1250 => array(
        'slug' => 'ww1-study-guide',
        'href' => '/ww1-ww2-wastewater-diagram/',
        'icon' => '&#x1F3ED;',
        'title' => 'Wastewater Plant Diagram',
        'desc'  => 'Explore an interactive wastewater treatment plant diagram. Click on each component to learn how it works and understand the full treatment process.',
        'btn'   => 'Launch Simulation &#x2192;',
    ),
    1251 => array(
        'slug' => 'ww2-study-guide',
        'href' => '/ww1-ww2-wastewater-diagram/',
        'icon' => '&#x1F3ED;',
        'title' => 'Wastewater Plant Diagram',
        'desc'  => 'Explore an interactive wastewater treatment plant diagram. Click on each component to learn how it works and understand the full treatment process.',
        'btn'   => 'Launch Simulation &#x2192;',
    ),
);

foreach ($targets as $pid => $info) {
    $post = get_post($pid);
    if (!$post) { echo "NOT FOUND: $pid" . PHP_EOL; continue; }
    $content = $post->post_content;

    // Already restored?
    if (strpos($content, $info['href']) !== false) {
        echo "SKIP (already present): {$info['slug']}" . PHP_EOL;
        continue;
    }

    $card  = '  <a class="sg-card sim" href="' . $info['href'] . '">' . "\n";
    $card .= '    <div class="sg-icon">' . $info['icon'] . '</div>' . "\n";
    $card .= '    <h2>' . $info['title'] . '</h2>' . "\n";
    $card .= '    <p class="sg-desc">' . $info['desc'] . '</p>' . "\n";
    $card .= '    <span class="sg-btn">' . $info['btn'] . '</span>' . "\n";
    $card .= '  </a>';

    // Insert at start of grid
    if (strpos($content, $grid_open . "\n") !== false) {
        $content = str_replace($grid_open . "\n", $grid_open . "\n" . $card . "\n", $content);
    } else {
        // fallback: insert after grid_open regardless of trailing char
        $pos = strpos($content, $grid_open);
        if ($pos === false) { echo "ERROR: grid not found in {$info['slug']}" . PHP_EOL; continue; }
        $content = substr($content, 0, $pos + strlen($grid_open)) . "\n" . $card . "\n" . substr($content, $pos + strlen($grid_open));
    }

    wp_update_post(array('ID' => $pid, 'post_content' => $content));
    echo "RESTORED: {$info['slug']}" . PHP_EOL;
}

wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
