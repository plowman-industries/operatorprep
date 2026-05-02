<?php
/**
 * T1-T5 EPA Drinking Water Regulations:
 * 1. Create dedicated EPA treatment regulations page (light theme)
 * 2. Add EPA card into sg-cards grid on T1-T5 hub pages
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
echo "Script starting..." . PHP_EOL;
global $wpdb;

// ======================================================
// STEP 1 -- Create EPA treatment regulations page
// ======================================================
$epa_slug  = 'epa-treatment-regulations';
$epa_title = 'EPA Drinking Water Regulations';

$epa_page_content = <<<'HTML'
<!-- wp:html -->
<style>.entry-header.ast-no-thumbnail { display: none !important; }</style>
<div id="opp-epa-treatment">
<style>
#opp-epa-treatment { font-family: var(--f-body, 'Segoe UI', system-ui, sans-serif); background: transparent; color: var(--c-ink, #0b1220); max-width: 860px; margin: 0 auto; padding: 40px 24px; box-sizing: border-box; }
#opp-epa-treatment *, #opp-epa-treatment *::before, #opp-epa-treatment *::after { box-sizing: border-box; }
.epat-header { text-align: center; margin-bottom: 36px; }
.epat-eyebrow { font-family: var(--f-mono, monospace); font-size: 0.72rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--c-signal, #0f5ea8); display: block; margin-bottom: 10px; }
.epat-header h1 { font-family: var(--f-display, Georgia, serif); font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; letter-spacing: -0.02em; color: var(--c-ink, #0b1220); margin: 0 0 12px; }
.epat-header h1 span { color: var(--c-signal, #0f5ea8); }
.epat-header p { color: var(--c-steel, #475569); font-size: 1.02em; line-height: 1.65; max-width: 580px; margin: 0 auto; }
.epat-intro { background: var(--c-mist, #f4f6f8); border: 1px solid var(--c-fog, #e2e8f0); border-left: 4px solid var(--c-signal, #0f5ea8); border-radius: 0 8px 8px 0; padding: 20px 24px; margin-bottom: 28px; color: var(--c-steel, #475569); font-size: 0.95em; line-height: 1.7; }
.epat-intro strong { color: var(--c-ink, #0b1220); }
.epat-section-title { font-family: var(--f-mono, monospace); font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-signal, #0f5ea8); margin: 28px 0 12px; display: block; }
.epat-cards { display: grid; gap: 14px; }
.epat-doc { background: #ffffff; border: 1px solid var(--c-fog, #e2e8f0); border-left: 4px solid var(--c-signal, #0f5ea8); border-radius: 0 12px 12px 0; padding: 22px 26px 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.epat-doc h2 { font-size: 1.0em; font-weight: 700; color: var(--c-ink, #0b1220); margin: 0 0 8px; }
.epat-doc p { color: var(--c-steel, #475569); font-size: 0.9em; line-height: 1.6; margin: 0 0 14px; }
.epat-dl-btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; background: var(--c-signal, #0f5ea8); color: #fff !important; font-weight: 600; font-size: 0.85em; border-radius: 6px; text-decoration: none; transition: background 0.2s; }
.epat-dl-btn:hover { background: #0a4a88; color: #fff !important; }
.epat-source { font-size: 0.78em; color: var(--c-steel, #475569); margin-top: 20px; text-align: center; }
.epat-source a { color: var(--c-signal, #0f5ea8); }
.epat-back { display: inline-flex; align-items: center; gap: 6px; margin-top: 32px; color: var(--c-signal, #0f5ea8); font-size: 0.9em; text-decoration: none; font-weight: 500; }
.epat-back:hover { text-decoration: underline; }
@media (max-width: 640px) { #opp-epa-treatment { padding: 24px 16px; } .epat-header h1 { font-size: 1.6rem; } .epat-doc { padding: 18px 18px 14px; } }
</style>

<div class="epat-header">
  <span class="epat-eyebrow">Treatment Operator Study Resources</span>
  <h1>EPA <span>Drinking Water</span> Regulations</h1>
  <p>Official EPA regulatory guidance for water treatment system operators -- the rules that govern how treatment plants must operate</p>
</div>

<div class="epat-intro">
  The EPA sets enforceable drinking water standards for over 90 contaminants under the <strong>Safe Drinking Water Act (SDWA)</strong>. These quick reference guides and technical documents cover the core regulations that T1-T5 treatment operators are tested on -- including surface water treatment, disinfection byproducts, and microbial monitoring requirements.
</div>

<span class="epat-section-title">Surface Water Treatment</span>
<div class="epat-cards">
  <div class="epat-doc">
    <h2>&#x1F4C4; Comprehensive Surface Water Treatment Rules Quick Reference Guide</h2>
    <p>Covers all Surface Water Treatment Rules (SWTR, IESWTR, LT1, LT2) for systems using conventional or direct filtration -- turbidity limits, CT calculations, disinfection requirements, and filter performance standards.</p>
    <a href="https://nepis.epa.gov/Exe/ZyPDF.cgi?Dockey=P100N2UO.txt" target="_blank" rel="noopener" class="epat-dl-btn">&#x2B07; View / Download</a>
  </div>
  <div class="epat-doc">
    <h2>&#x1F4C4; Disinfection Profiling and Benchmarking Technical Guidance Manual</h2>
    <p>Technical guide for calculating CT values, developing disinfection profiles, and establishing benchmarks under the Surface Water Treatment Rules. Essential reference for understanding disinfection compliance requirements.</p>
    <a href="https://www.epa.gov/system/files/documents/2022-02/disprof_bench_3rules_final_508.pdf" target="_blank" rel="noopener" class="epat-dl-btn">&#x2B07; Download PDF</a>
  </div>
</div>

<span class="epat-section-title">Disinfection Byproducts</span>
<div class="epat-cards">
  <div class="epat-doc">
    <h2>&#x1F4C4; Comprehensive Disinfectants and Disinfection Byproducts Rules Quick Reference Guide</h2>
    <p>Covers Stage 1 and Stage 2 DBP rules -- maximum residual disinfectant levels (MRDLs), DBP maximum contaminant levels (MCLs), monitoring schedules, and compliance requirements for treatment plants using chlorine, chloramines, or ozone.</p>
    <a href="https://nepis.epa.gov/Exe/ZyPDF.cgi?Dockey=P100C8XW.txt" target="_blank" rel="noopener" class="epat-dl-btn">&#x2B07; View / Download</a>
  </div>
</div>

<span class="epat-section-title">Microbial Monitoring</span>
<div class="epat-cards">
  <div class="epat-doc">
    <h2>&#x1F4C4; Revised Total Coliform Rule: A Quick Reference Guide</h2>
    <p>Covers RTCR monitoring requirements, action levels, assessment triggers, and corrective action procedures. Treatment operators must understand the coliform monitoring framework and when Level 1 and Level 2 assessments are required.</p>
    <a href="https://nepis.epa.gov/Exe/ZyPDF.cgi?Dockey=P100K9MP.txt" target="_blank" rel="noopener" class="epat-dl-btn">&#x2B07; View / Download</a>
  </div>
  <div class="epat-doc">
    <h2>&#x1F4C4; Ground Water Rule: A Quick Reference Guide</h2>
    <p>Covers the Ground Water Rule requirements for systems using groundwater sources -- triggered source water monitoring, corrective action requirements, and compliance with microbial contamination prevention standards.</p>
    <a href="https://nepis.epa.gov/Exe/ZyPDF.cgi?Dockey=P100156H.txt" target="_blank" rel="noopener" class="epat-dl-btn">&#x2B07; View / Download</a>
  </div>
</div>

<p class="epat-source">Source: <a href="https://www.epa.gov/dwreginfo/drinking-water-regulations" target="_blank" rel="noopener">EPA Drinking Water Regulations</a></p>
<a class="epat-back" href="javascript:history.back()">&#x2190; Back to Study Guide</a>
</div>
<!-- /wp:html -->
HTML;

$existing_page = get_page_by_path($epa_slug);
if ($existing_page) {
    $epa_page_id = $existing_page->ID;
    wp_update_post(array('ID' => $epa_page_id, 'post_content' => $epa_page_content, 'post_status' => 'publish'));
    echo "Updated EPA treatment page: ID $epa_page_id" . PHP_EOL;
} else {
    $epa_page_id = wp_insert_post(array(
        'post_title'   => $epa_title,
        'post_name'    => $epa_slug,
        'post_content' => $epa_page_content,
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ));
    echo "Created EPA treatment page: ID $epa_page_id" . PHP_EOL;
}
$epa_url = get_permalink($epa_page_id);
echo "EPA URL: $epa_url" . PHP_EOL;

// ======================================================
// STEP 2 -- Build the EPA grid card
// ======================================================
$new_epa_card  = '  <a class="sg-card resources" href="' . esc_url($epa_url) . '">' . "\n";
$new_epa_card .= '    <div class="sg-icon">&#x1F4CB;</div>' . "\n";
$new_epa_card .= '    <h2>EPA Regulations</h2>' . "\n";
$new_epa_card .= '    <p class="sg-desc">Official EPA drinking water regulations -- surface water treatment rules, disinfection byproducts, and microbial monitoring requirements.</p>' . "\n";
$new_epa_card .= '    <span class="sg-btn">View EPA Regulations &#x2192;</span>' . "\n";
$new_epa_card .= '  </a>';

$resources_css  = "\n" . '.sg-card.resources::before { background: linear-gradient(90deg, #22c55e, #16a34a); }' . "\n";
$resources_css .= '.sg-card.resources:hover { border-color: #22c55e; }' . "\n";
$resources_css .= '.sg-card.resources .sg-icon { background: rgba(34,197,94,0.12); color: #22c55e; }' . "\n";
$resources_css .= '.sg-card.resources .sg-btn { background: #16a34a; color: #fff; }';

// ======================================================
// STEP 3 -- Update T1-T5 hub pages
// ======================================================
$page_ids = array(1160, 941, 1243, 1244, 1245); // T1-T5
$updated = 0;
$skipped = 0;

foreach ($page_ids as $pid) {
    $post = get_post($pid);
    if (!$post) { echo "NOT FOUND: $pid" . PHP_EOL; continue; }
    $content = $post->post_content;
    $slug    = $post->post_name;

    // Skip if EPA card already added
    if (strpos($content, 'class="sg-card resources" href=') !== false) {
        echo "SKIP (already done): post $pid ($slug)" . PHP_EOL;
        $skipped++;
        continue;
    }

    // 3a. Add resources CSS into inner style block (before </style> that precedes sg-header)
    if (strpos($content, 'sg-card.resources::before') === false) {
        $content = str_replace(
            "</style>\n<div class=\"sg-header\">",
            $resources_css . "\n</style>\n<div class=\"sg-header\">",
            $content
        );
    }

    // 3b. Insert EPA card into sg-cards grid (before grid closing </div>)
    $grid_close = "</div>\n<a class=\"sg-back\"";
    if (strpos($content, $grid_close) !== false) {
        $content = str_replace(
            $grid_close,
            $new_epa_card . "\n</div>\n<a class=\"sg-back\"",
            $content
        );
    } else {
        echo "WARNING: grid close not found in post $pid ($slug)" . PHP_EOL;
    }

    wp_update_post(array('ID' => $pid, 'post_content' => $content));
    echo "UPDATED: post $pid ($slug)" . PHP_EOL;
    $updated++;
}

// Purge caches
wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();

echo "Done -- updated: $updated, skipped: $skipped" . PHP_EOL;
echo "DONE" . PHP_EOL;
