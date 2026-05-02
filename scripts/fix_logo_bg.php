<?php
/**
 * EPA Resources page: restyle to light theme (match site design review)
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
echo "Updating EPA resources page to light theme..." . PHP_EOL;

$epa_page_id = 1341;

$epa_page_content = <<<'HTML'
<!-- wp:html -->
<style>.entry-header.ast-no-thumbnail { display: none !important; }</style>
<div id="opp-epa-resources">
<style>
#opp-epa-resources { font-family: var(--f-body, 'Segoe UI', system-ui, sans-serif); background: transparent; color: var(--c-ink, #0b1220); max-width: 860px; margin: 0 auto; padding: 40px 24px; box-sizing: border-box; }
#opp-epa-resources *, #opp-epa-resources *::before, #opp-epa-resources *::after { box-sizing: border-box; }
.epa-header { text-align: center; margin-bottom: 36px; }
.epa-header h1 { font-family: var(--f-display, Georgia, serif); font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; letter-spacing: -0.02em; color: var(--c-ink, #0b1220); margin: 0 0 12px; }
.epa-header h1 span { color: var(--c-signal, #0f5ea8); }
.epa-header p { color: var(--c-steel, #475569); font-size: 1.02em; line-height: 1.65; max-width: 580px; margin: 0 auto; }
.epa-intro { background: var(--c-mist, #f4f6f8); border: 1px solid var(--c-fog, #e2e8f0); border-left: 4px solid var(--c-signal, #0f5ea8); border-radius: 0 8px 8px 0; padding: 20px 24px; margin-bottom: 28px; color: var(--c-steel, #475569); font-size: 0.95em; line-height: 1.7; }
.epa-intro strong { color: var(--c-ink, #0b1220); }
.epa-cards { display: grid; gap: 16px; }
.epa-doc { background: #ffffff; border: 1px solid var(--c-fog, #e2e8f0); border-left: 4px solid var(--c-signal, #0f5ea8); border-radius: 0 12px 12px 0; padding: 24px 28px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.epa-doc h2 { font-size: 1.05em; font-weight: 700; color: var(--c-ink, #0b1220); margin: 0 0 10px; display: flex; align-items: center; gap: 8px; }
.epa-doc p { color: var(--c-steel, #475569); font-size: 0.93em; line-height: 1.65; margin: 0 0 16px; }
.epa-dl-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--c-signal, #0f5ea8); color: #fff !important; font-weight: 600; font-size: 0.875em; border-radius: 6px; text-decoration: none; transition: background 0.2s; }
.epa-dl-btn:hover { background: #0a4a88; color: #fff !important; }
.epa-eyebrow { font-family: var(--f-mono, monospace); font-size: 0.72rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--c-signal, #0f5ea8); display: block; margin-bottom: 10px; }
.epa-back { display: inline-flex; align-items: center; gap: 6px; margin-top: 32px; color: var(--c-signal, #0f5ea8); font-size: 0.9em; text-decoration: none; font-weight: 500; }
.epa-back:hover { text-decoration: underline; }
@media (max-width: 640px) { #opp-epa-resources { padding: 24px 16px; } .epa-header h1 { font-size: 1.6rem; } .epa-doc { padding: 20px 20px 16px; } }
</style>

<div class="epa-header">
  <span class="epa-eyebrow">Distribution Study Resources</span>
  <h1>EPA <span>Distribution</span> Resources</h1>
  <p>Official guidance documents from the U.S. Environmental Protection Agency for water distribution system operators</p>
</div>

<div class="epa-intro">
  The EPA's <strong>Drinking Water Capacity Development</strong> program provides these technical assistance documents for small system operators. They cover operational best practices, regulatory compliance, and protecting water quality throughout the distribution system -- topics tested directly on D1-D5 certification exams.
</div>

<div class="epa-cards">
  <div class="epa-doc">
    <h2>&#x1F4C4; Distribution Systems: A Best Practices Guide</h2>
    <p>Covers operational best practices for small drinking water distribution systems -- including pipe maintenance, flushing programs, water quality monitoring, system mapping, and emergency response procedures. An essential reference for understanding how to properly operate and maintain a distribution system in compliance with drinking water regulations.</p>
    <a href="https://www.epa.gov/sites/default/files/2015-09/documents/epa816f06038.pdf" target="_blank" rel="noopener" class="epa-dl-btn">&#x2B07; Download PDF</a>
  </div>
  <div class="epa-doc">
    <h2>&#x1F4C4; Cross-Connection Control: A Best Practices Guide</h2>
    <p>Explains how to establish and maintain a cross-connection control program for small systems -- including backflow prevention device requirements, inspection and testing procedures, and protecting distribution system water quality from contamination through improper connections.</p>
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
<!-- /wp:html -->
HTML;

$result = wp_update_post( array(
    'ID'           => $epa_page_id,
    'post_content' => $epa_page_content,
    'post_status'  => 'publish',
) );

if ( is_wp_error( $result ) ) {
    echo "ERROR: " . $result->get_error_message() . PHP_EOL;
} else {
    echo "Updated EPA page ID $result" . PHP_EOL;
}

// Purge caches
wp_cache_flush();
do_action( 'sg_cachepress_purge_cache' );
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE" . PHP_EOL;
