<?php
/**
 * Add EPA Distribution Resources card to D1-D5 study guide hub pages.
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
global $wpdb;

$page_ids = [1246, 1247, 1209, 1248, 1249]; // D1, D2, D3, D4, D5

// ── New EPA Resources card HTML ──
$epa_card = '
<div class="sg-card resources">
  <div class="sg-icon">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
  </div>
  <h2>EPA Distribution Resources</h2>
  <p class="sg-desc">Official EPA guides for distribution system operators — best practices, cross-connection control, and system management.</p>
  <div class="sg-pdf-links">
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816f06038.pdf" target="_blank" rel="noopener" class="sg-pdf-link">
      📄 Distribution Systems: A Best Practices Guide
    </a>
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816f06035.pdf" target="_blank" rel="noopener" class="sg-pdf-link">
      📄 Cross-Connection Control: A Best Practices Guide
    </a>
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816r03002_0.pdf" target="_blank" rel="noopener" class="sg-pdf-link">
      📄 Cross-Connection Control Manual
    </a>
  </div>
</div>';

// ── CSS to add for the resources card and pdf links ──
$epa_css = '
.sg-card.resources { cursor: default; }
.sg-pdf-links { display: flex; flex-direction: column; gap: 10px; margin-top: 12px; }
.sg-pdf-link {
  display: block;
  padding: 10px 14px;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(100,160,255,0.2);
  border-radius: 8px;
  color: #93c5fd;
  font-size: 0.88em;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.2s, border-color 0.2s;
  line-height: 1.4;
}
.sg-pdf-link:hover {
  background: rgba(255,255,255,0.13);
  border-color: rgba(100,160,255,0.5);
  color: #bfdbfe;
}';

$updated = 0;
$skipped = 0;

foreach ($page_ids as $pid) {
    $post = get_post($pid);
    if (!$post) {
        echo "SKIP: post $pid not found" . PHP_EOL;
        $skipped++;
        continue;
    }

    $content = $post->post_content;

    // Skip if already has EPA card
    if (strpos($content, 'EPA Distribution Resources') !== false) {
        echo "SKIP: post $pid ({$post->post_name}) already has EPA card" . PHP_EOL;
        $skipped++;
        continue;
    }

    // Insert CSS into the existing <style> block inside #opp-sg-hub
    // Find the closing </style> tag after the sg-hub style block
    $style_close = strpos($content, '</style>');
    if ($style_close !== false) {
        $content = substr_replace($content, $epa_css . "\n</style>", $style_close, strlen('</style>'));
    }

    // Insert card: find the closing </div> of sg-cards
    // The pattern is: the last </div> before the final </div>\n</div> block
    // Strategy: insert $epa_card right before the </div> that closes sg-cards
    // Look for </div>\n</div> at the end (sg-cards close then sg-hub close)
    $insert_before = '</div>' . "\n" . '</div>';
    $last_pos = strrpos($content, $insert_before);
    if ($last_pos !== false) {
        $content = substr($content, 0, $last_pos) . $epa_card . "\n" . $insert_before . substr($content, $last_pos + strlen($insert_before));
    } else {
        // Fallback: insert before last </div>
        $last_div = strrpos($content, '</div>');
        $content = substr($content, 0, $last_div) . $epa_card . "\n</div>" . substr($content, $last_div + strlen('</div>'));
    }

    // Update post
    $result = wp_update_post([
        'ID'           => $pid,
        'post_content' => $content,
    ], true);

    if (is_wp_error($result)) {
        echo "ERROR: post $pid ({$post->post_name}): " . $result->get_error_message() . PHP_EOL;
    } else {
        echo "UPDATED: post $pid ({$post->post_name})" . PHP_EOL;
        $updated++;
    }
}

// Flush caches
wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();

echo PHP_EOL . "Done — updated: $updated, skipped: $skipped" . PHP_EOL;
