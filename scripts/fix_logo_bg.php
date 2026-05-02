<?php
/**
 * Fix T1/T2: insert EPA card into grid (CSS already injected)
 */
global $wpdb;
echo "Fixing T1/T2 EPA card insertion..." . PHP_EOL;

$epa_url = get_permalink(1348); // EPA treatment regulations page
echo "EPA URL: $epa_url" . PHP_EOL;

$new_epa_card  = '  <a class="sg-card resources" href="' . esc_url($epa_url) . '">' . "\n";
$new_epa_card .= '    <div class="sg-icon">&#x1F4CB;</div>' . "\n";
$new_epa_card .= '    <h2>EPA Regulations</h2>' . "\n";
$new_epa_card .= '    <p class="sg-desc">Official EPA drinking water regulations -- surface water treatment rules, disinfection byproducts, and microbial monitoring requirements.</p>' . "\n";
$new_epa_card .= '    <span class="sg-btn">View EPA Regulations &#x2192;</span>' . "\n";
$new_epa_card .= '  </a>';

foreach (array(1160 => 't1-study-guide', 941 => 't2-study-guide') as $pid => $slug) {
    $post = get_post($pid);
    $content = $post->post_content;

    // Already done?
    if (strpos($content, 'class="sg-card resources" href=') !== false) {
        echo "SKIP (already has card): $slug" . PHP_EOL;
        continue;
    }

    // Find the grid closing pattern -- try multiple variants
    $patterns = array(
        "</div>\n<a class=\"sg-back\"",
        "</div>\r\n<a class=\"sg-back\"",
        "</div>\n<a class='sg-back'",
    );

    $matched = false;
    foreach ($patterns as $pattern) {
        if (strpos($content, $pattern) !== false) {
            $content = str_replace($pattern, $new_epa_card . "\n</div>\n<a class=\"sg-back\"", $content);
            echo "INSERTED card using pattern variant into $slug" . PHP_EOL;
            $matched = true;
            break;
        }
    }

    if (!$matched) {
        // Fallback: find </div> immediately before <a class="sg-back" and insert before it
        $pos = strpos($content, '<a class="sg-back"');
        if ($pos === false) { $pos = strpos($content, "<a class='sg-back'"); }
        if ($pos !== false) {
            // Walk back past whitespace to find the </div>
            $before = substr($content, 0, $pos);
            $after  = substr($content, $pos);
            $before = rtrim($before);
            if (substr($before, -6) === '</div>') {
                $before = substr($before, 0, -6); // remove trailing </div>
                $content = $before . $new_epa_card . "\n</div>\n" . $after;
                echo "INSERTED card via fallback position into $slug" . PHP_EOL;
                $matched = true;
            }
        }
    }

    if (!$matched) {
        echo "ERROR: could not find insertion point in $slug" . PHP_EOL;
        // Debug: show hex of chars around sg-back
        $pos = strpos($content, 'sg-back');
        if ($pos !== false) {
            $chunk = substr($content, max(0, $pos - 20), 40);
            echo "  HEX around sg-back: " . bin2hex($chunk) . PHP_EOL;
        }
        continue;
    }

    wp_update_post(array('ID' => $pid, 'post_content' => $content));
    echo "SAVED: $slug" . PHP_EOL;
}

wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
