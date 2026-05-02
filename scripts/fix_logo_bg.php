<?php
/**
 * EPA Resources v2:
 * 1. Create shared EPA resources page (write-up + PDF links)
 * 2. Move EPA card into sg-cards grid on D1-D5 hub pages
 * 3. Remove old full-width EPA block and stale pdf-link CSS
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
global $wpdb;

// ═══════════════════════════════════════════════════
// STEP 1 — Create / update dedicated EPA resources page
// ═══════════════════════════════════════════════════
$epa_slug  = 'epa-distribution-resources';
$epa_title = 'EPA Distribution Resources';

$epa_page_content = '<!-- wp:html -->
<style>.entry-header.ast-no-thumbnail { display: none !important; }</style>
<div id="opp-epa-resources">
<style>
#opp-epa-resources { font-family: \'Segoe UI\', system-ui, -apple-system, sans-serif; background: #0f172a; color: #e2e8f0; max-width: 900px; margin: 0 auto; padding: 40px 24px; box-sizing: border-box; }
#opp-epa-resources *, #opp-epa-resources *::before, #opp-epa-resources *::after { box-sizing: border-box; }
.epa-header { text-align: center; margin-bottom: 40px; }
.epa-header h1 { font-size: 2.1em; color: #22c55e; margin: 0 0 12px; }
.epa-header p { color: #94a3b8; font-size: 1.02em; line-height: 1.65; max-width: 620px; margin: 0 auto; }
.epa-intro { background: #1e293b; border: 1px solid #334155; border-left: 4px solid #22c55e; border-radius: 0 12px 12px 0; padding: 22px 26px; margin-bottom: 32px; color: #94a3b8; font-size: 0.95em; line-height: 1.7; }
.epa-intro strong { color: #e2e8f0; }
.epa-cards { display: grid; gap: 20px; }
.epa-doc { background: #1e293b; border: 1px solid #334155; border-left: 4px solid #22c55e; border-radius: 0 12px 12px 0; padding: 26px 28px 22px; }
.epa-doc h2 { font-size: 1.1em; color: #e2e8f0; margin: 0 0 10px; }
.epa-doc p { color: #94a3b8; font-size: 0.93em; line-height: 1.65; margin: 0 0 16px; }
.epa-dl-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: #16a34a; color: #fff !important; font-weight: 600; font-size: 0.88em; border-radius: 8px; text-decoration: none; transition: background 0.2s; }
.epa-dl-btn:hover { background: #15803d; color: #fff !important; }
.epa-back { display: block; text-align: center; margin-top: 36px; color: #64748b; font-size: 0.9em; text-decoration: none; }
.epa-back:hover { color: #94a3b8; }
@media (max-width: 640px) { #opp-epa-resources { padding: 24px 16px; } .epa-header h1 { font-size: 1.6em; } }
</style>
<div class="epa-header">
  <h1>EPA Distribution Resources</h1>
  <p>Official guidance documents from the U.S. Environmental Protection Agency for water distribution system operators</p>
</div>
<div class="epa-intro">
  The EPA\'s <strong>Drinking Water Capacity Development</strong> program provides these technical assistance documents for small drinking water system operators. They cover day-to-day operational best practices, regulatory compliance, and protecting water quality throughout the distribution system — topics covered directly on D1–D5 certification exams.
</div>
<div class="epa-cards">
  <div class="epa-doc">
    <h2>&#x1F4C4; Distribution Systems: A Best Practices Guide</h2>
    <p>Covers operational best practices for small drinking water distribution systems — including pipe maintenance, flushing programs, water quality monitoring, system mapping, and emergency response procedures. An essential reference for understanding how to properly operate and maintain a distribution system in compliance with drinking water regulations.</p>
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816f06038.pdf" target="_blank" rel="noopener" class="epa-dl-btn">&#x2B07; Download PDF</a>
  </div>
  <div class="epa-doc">
    <h2>&#x1F4C4; Cross-Connection Control: A Best Practices Guide</h2>
    <p>Explains how to establish and maintain a cross-connection control program for small systems — including backflow prevention device requirements, inspection and testing procedures, and protecting distribution system water quality from contamination through improper connections.</p>
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816f06035.pdf" target="_blank" rel="noopener" class="epa-dl-btn">&#x2B07; Download PDF</a>
  </div>
  <div class="epa-doc">
    <h2>&#x1F4C4; Cross-Connection Control Manual</h2>
    <p>A comprehensive technical reference for administering a full cross-connection control program. Covers regulatory framework, device types (RPZ, double-check valves, AVBs), testing standards, record-keeping, and enforcement procedures. The definitive EPA resource for understanding backflow and back-siphonage prevention in distribution systems.</p>
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816r03002_0.pdf" target="_blank" rel="noopener" class="epa-dl-btn">&#x2B07; Download PDF</a>
  </div>
</div>
<a class="epa-back" href="javascript:history.back()">&#x2190; Back to Study Guide</a>
</div>
<!-- /wp:html -->';

$existing_page = get_page_by_path( $epa_slug );
if ( $existing_page ) {
    $epa_page_id = $existing_page->ID;
    wp_update_post( [ 'ID' => $epa_page_id, 'post_content' => $epa_page_content, 'post_status' => 'publish' ] );
    echo "Updated EPA page: ID $epa_page_id" . PHP_EOL;
} else {
    $epa_page_id = wp_insert_post( [
        'post_title'   => $epa_title,
        'post_name'    => $epa_slug,
        'post_content' => $epa_page_content,
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ] );
    echo "Created EPA page: ID $epa_page_id" . PHP_EOL;
}
$epa_url = get_permalink( $epa_page_id );
echo "EPA URL: $epa_url" . PHP_EOL;

// ═══════════════════════════════════════════════════
// STEP 2 — Build the new EPA grid card HTML
// ═══════════════════════════════════════════════════
$new_epa_card = '  <a class="sg-card resources" href="' . esc_url( $epa_url ) . '">
    <div class="sg-icon">&#x1F4CB;</div>
    <h2>EPA Resources</h2>
    <p class="sg-desc">Official EPA guides for distribution operators — best practices, cross-connection control, and system management.</p>
    <span class="sg-btn">View EPA Resources &#x2192;</span>
  </a>';

// CSS to append to inner <style> block (before its closing </style>)
$resources_css = '
.sg-card.resources::before { background: linear-gradient(90deg, #22c55e, #16a34a); }
.sg-card.resources:hover { border-color: #22c55e; }
.sg-card.resources .sg-icon { background: rgba(34,197,94,0.12); color: #22c55e; }
.sg-card.resources .sg-btn { background: #16a34a; color: #fff; }';

// ═══════════════════════════════════════════════════
// STEP 3 — Update D1-D5 hub pages
// ═══════════════════════════════════════════════════
$page_ids = [ 1246, 1247, 1209, 1248, 1249 ]; // D1-D5
$updated = 0; $skipped = 0;

foreach ( $page_ids as $pid ) {
    $post = get_post( $pid );
    if ( ! $post ) { echo "NOT FOUND: $pid" . PHP_EOL; continue; }
    $content = $post->post_content;
    $slug    = $post->post_name;

    // Already done if EPA card is already a link
    if ( strpos( $content, 'class="sg-card resources" href=' ) !== false ) {
        echo "SKIP: post $pid ($slug) already updated" . PHP_EOL;
        $skipped++;
        continue;
    }

    // ── 3a. Strip stale outer <style> block (pdf-link CSS), keep only entry-header rule ──
    $content = preg_replace(
        '/<style>\.entry-header\.ast-no-thumbnail \{ display: none !important; \}.*?<\/style>/s',
        '<style>.entry-header.ast-no-thumbnail { display: none !important; }</style>',
        $content, 1
    );

    // ── 3b. Inject resources card CSS into inner <style> block ──
    // Inner style block closes before <div class="sg-header">
    if ( strpos( $content, 'sg-card.resources::before' ) === false ) {
        $content = str_replace(
            "</style>\n<div class=\"sg-header\">",
            $resources_css . "\n</style>\n<div class=\"sg-header\">",
            $content
        );
    }

    // ── 3c. Insert EPA grid card into .sg-cards (before its closing </div>) ──
    // The grid closes with </div> immediately before the sg-back <a> tag
    $grid_close = "</div>\n<a class=\"sg-back\"";
    if ( strpos( $content, $grid_close ) !== false ) {
        $content = str_replace(
            $grid_close,
            $new_epa_card . "\n</div>\n<a class=\"sg-back\"",
            $content
        );
    }

    // ── 3d. Remove old standalone EPA card block (everything from its div to end of content) ──
    $epa_block_start = "\n<div class=\"sg-card resources\">";
    $epa_pos = strpos( $content, $epa_block_start );
    if ( $epa_pos !== false ) {
        // Truncate at EPA card start, append proper hub close + block delimiter
        $content = substr( $content, 0, $epa_pos ) . "\n</div>\n<!-- /wp:html -->";
    }

    wp_update_post( [ 'ID' => $pid, 'post_content' => $content ] );
    echo "UPDATED: post $pid ($slug)" . PHP_EOL;
    $updated++;
}

// ── Purge caches ──
wp_cache_flush();
do_action( 'sg_cachepress_purge_cache' );
if ( function_exists( 'sg_cachepress_purge_cache' ) ) sg_cachepress_purge_cache();

echo "Done — updated: $updated, skipped: $skipped" . PHP_EOL;
echo "DONE" . PHP_EOL;
