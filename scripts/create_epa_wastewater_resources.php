<?php
/**
 * Creates the EPA Wastewater Resources page (slug: epa-wastewater-resources).
 * Matches the structure of epa-distribution-resources and epa-treatment-regulations.
 * Resources sourced from: https://www.epa.gov/compliance/resources-wastewater-operators
 *
 * Safe to re-run — guarded by slug check.
 */
echo "Creating EPA Wastewater Resources page...\n";

// Guard: already exists?
$existing = get_page_by_path( 'epa-wastewater-resources' );
if ( $existing ) {
    echo "Page already exists (ID {$existing->ID}) — skipping.\n";
    exit;
}

$content = <<<'HTML'
<style>
.entry-header.ast-no-thumbnail { display: none !important; }

#opp-epa-ww {
  font-family: var(--f-body, 'Segoe UI', system-ui, sans-serif);
  background: transparent;
  color: var(--c-ink, #0b1220);
  max-width: 860px;
  margin: 0 auto;
  padding: 40px 24px;
  box-sizing: border-box;
}
#opp-epa-ww *, #opp-epa-ww *::before, #opp-epa-ww *::after { box-sizing: border-box; }

.epaww-header { text-align: center; margin-bottom: 36px; }
.epaww-eyebrow {
  font-family: var(--f-mono, monospace);
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--c-signal, #0f5ea8);
  display: block;
  margin-bottom: 10px;
}
.epaww-header h1 {
  font-family: var(--f-display, Georgia, serif);
  font-size: clamp(1.8rem, 4vw, 2.6rem);
  font-weight: 800;
  letter-spacing: -0.02em;
  color: var(--c-ink, #0b1220);
  margin: 0 0 12px;
}
.epaww-header h1 span { color: var(--c-signal, #0f5ea8); }
.epaww-header p {
  color: var(--c-steel, #475569);
  font-size: 1.02em;
  line-height: 1.65;
  max-width: 580px;
  margin: 0 auto;
}
.epaww-intro {
  background: var(--c-mist, #f4f6f8);
  border: 1px solid var(--c-fog, #e2e8f0);
  border-left: 4px solid var(--c-signal, #0f5ea8);
  border-radius: 0 8px 8px 0;
  padding: 20px 24px;
  margin-bottom: 28px;
  color: var(--c-steel, #475569);
  font-size: 0.95em;
  line-height: 1.7;
}
.epaww-intro strong { color: var(--c-ink, #0b1220); }

.epaww-section-title {
  font-family: var(--f-mono, monospace);
  font-size: 0.72rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--c-signal, #0f5ea8);
  margin: 28px 0 12px;
  display: block;
}
.epaww-cards { display: grid; gap: 14px; }
.epaww-doc {
  background: #ffffff;
  border: 1px solid var(--c-fog, #e2e8f0);
  border-left: 4px solid var(--c-signal, #0f5ea8);
  border-radius: 0 12px 12px 0;
  padding: 22px 26px 18px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.epaww-doc h2 {
  font-size: 1.0em;
  font-weight: 700;
  color: var(--c-ink, #0b1220);
  margin: 0 0 8px;
}
.epaww-doc p {
  color: var(--c-steel, #475569);
  font-size: 0.9em;
  line-height: 1.6;
  margin: 0 0 14px;
}
.epaww-dl-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 18px;
  background: var(--c-signal, #0f5ea8);
  color: #fff !important;
  font-weight: 600;
  font-size: 0.85em;
  border-radius: 6px;
  text-decoration: none;
  transition: background 0.2s;
}
.epaww-dl-btn:hover { background: #0a4a88; color: #fff !important; }
.epaww-source {
  font-size: 0.78em;
  color: var(--c-steel, #475569);
  margin-top: 20px;
  text-align: center;
}
.epaww-source a { color: var(--c-signal, #0f5ea8); }
.epaww-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 32px;
  color: var(--c-signal, #0f5ea8);
  font-size: 0.9em;
  text-decoration: none;
  font-weight: 500;
}
.epaww-back:hover { text-decoration: underline; }

@media (max-width: 640px) {
  #opp-epa-ww { padding: 24px 16px; }
  .epaww-header h1 { font-size: 1.6rem; }
  .epaww-doc { padding: 18px 18px 14px; }
}
</style>

<div id="opp-epa-ww">

  <div class="epaww-header">
    <span class="epaww-eyebrow">// Wastewater Operator Study Resources</span>
    <h1>EPA <span>Wastewater Resources</span></h1>
    <p>Official guidance documents, tip sheets, and training materials from the U.S. Environmental Protection Agency for wastewater treatment operators</p>
  </div>

  <div class="epaww-intro">
    The EPA's compliance assistance program provides these technical resources specifically for wastewater operators. They cover operational best practices, NPDES permit compliance, process control, and troubleshooting — topics tested directly on <strong>WW1–WW5 certification exams</strong>. All documents are free and publicly available from the EPA.
  </div>

  <!-- ── Compliance & Permitting ───────────────────────────────────────── -->
  <span class="epaww-section-title">// Compliance &amp; Permitting</span>
  <div class="epaww-cards">

    <div class="epaww-doc">
      <h2>Compliance Tips for Small, Mechanical Wastewater Treatment Plants</h2>
      <p>A 5-page EPA Compliance Advisory covering the most common NPDES permit violations at small mechanical WWTPs — including monitoring frequency, effluent limit exceedances, record-keeping requirements, and reporting obligations. Essential reading for any operator responsible for permit compliance.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/sites/default/files/2021-04/documents/compliancetips-smallmechanicalwwtps.pdf" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

    <div class="epaww-doc">
      <h2>Discharge Monitoring Reports — Avoiding Common Mistakes</h2>
      <p>A 90-minute recorded EPA webinar on how to accurately complete Discharge Monitoring Reports (DMRs) and avoid the most common reporting errors that result in permit violations. Covers data entry, calculation methods, and submission requirements under NPDES permits.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/compliance/discharge-monitoring-reports-avoiding-common-mistakes" target="_blank" rel="noopener">&#x25B6; Watch Webinar</a>
    </div>

    <div class="epaww-doc">
      <h2>Technical Assistance Webinars: Improving CWA-NPDES Permit Compliance</h2>
      <p>EPA's ongoing webinar series covering a wide variety of technical topics for NPDES permittees — including effluent limits, monitoring requirements, stormwater management, and compliance strategies. Each session is recorded and freely available for operators seeking continuing education.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/compliance/technical-assistance-webinar-series-strengthening-cwa-npdes-permit-compliance-protect" target="_blank" rel="noopener">&#x25B6; View Webinar Series</a>
    </div>

  </div>

  <!-- ── Process Control & Technical References ────────────────────────── -->
  <span class="epaww-section-title">// Process Control &amp; Technical References</span>
  <div class="epaww-cards">

    <div class="epaww-doc">
      <h2>Small Mechanical Plants — Wastewater Tip Sheet</h2>
      <p>A concise EPA tip sheet designed to help operators of small mechanical wastewater treatment plants avoid the most common operational mistakes that trigger NPDES permit non-compliance. Covers routine monitoring, process adjustments, and record-keeping best practices.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/system/files/documents/2024-11/ww-smlmech-tipsheet.pdf" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

    <div class="epaww-doc">
      <h2>Treatment Lagoons — Wastewater Tip Sheet</h2>
      <p>An EPA tip sheet for operators managing wastewater treatment lagoon systems. Identifies the most frequent compliance pitfalls — including algae interference, seasonal temperature effects, hydraulic overloading, and effluent quality monitoring — and provides practical corrective guidance.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/system/files/documents/2024-11/ww-lagoon-tipsheet.pdf" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

    <div class="epaww-doc">
      <h2>Troubleshooting Manual for Small Wastewater Lagoons</h2>
      <p>Designed to help lagoon operators systematically identify the causes of upset conditions — including odor problems, poor effluent quality, scum accumulation, and algae blooms. Provides a structured diagnostic approach with corrective action recommendations for each category of lagoon problem.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/system/files/documents/2024-02/lagoon-troubleshooting-manual.pdf" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

    <div class="epaww-doc">
      <h2>Principles of Design and Operations of Wastewater Treatment Pond Systems</h2>
      <p>A comprehensive EPA technical manual (EPA 600-R-11-088) covering the full lifecycle of wastewater treatment pond systems — design criteria, nutrient removal mechanisms, algae control strategies, effluent quality optimization, and systematic troubleshooting procedures. An authoritative reference for lagoon operators at all certification levels.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/sites/default/files/2014-09/documents/lagoon-pond-treatment-2011.pdf" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

    <div class="epaww-doc">
      <h2>Algae's Influence on the BOD<sub>5</sub> Test</h2>
      <p>Explains how algae presence in effluent samples affects dissolved oxygen measurements and the 5-day BOD test — a critical concept for lagoon and pond operators interpreting effluent quality results. Understanding this interference is essential for accurate compliance monitoring and correct data reporting on DMRs.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/sites/default/files/2020-09/documents/algaebodinfluence.pdf" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

    <div class="epaww-doc">
      <h2>The Microbiology of Wastewater Treatment</h2>
      <p>A 90-minute recorded EPA webinar covering the microorganisms found in activated sludge systems — bacteria, protozoa, fungi, and indicator organisms — and how biological activity drives treatment performance. Covers the relationship between microbial populations, process control parameters, and effluent quality.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/compliance/microbiology-wastewater-treatment" target="_blank" rel="noopener">&#x25B6; Watch Webinar</a>
    </div>

  </div>

  <!-- ── Tools & Formulas ──────────────────────────────────────────────── -->
  <span class="epaww-section-title">// Tools &amp; Formulas</span>
  <div class="epaww-cards">

    <div class="epaww-doc">
      <h2>Ammonia Removal Best Practices Tool</h2>
      <p>An Excel-based EPA decision-support tool for evaluating elevated ammonia levels in wastewater treatment plant effluent. Walks operators through a structured best management practice assessment — identifying likely causes, process adjustments, and corrective actions for nitrification system problems.</p>
      <a class="epaww-dl-btn" href="https://www.epa.gov/system/files/documents/2022-09/Ammonia%20Removal%20Best%20Practices.xltm" target="_blank" rel="noopener">&#x2B07; Download Excel Tool</a>
    </div>

    <div class="epaww-doc">
      <h2>ABC Formula Table for Wastewater Treatment</h2>
      <p>A comprehensive reference table of commonly used formulas, abbreviations, and conversion factors for wastewater treatment — compiled by the Association of Boards of Certification (ABC). Covers flow calculations, loading rates, detention times, sludge volumes, and chemical dosing. An essential study aid for all WW exam levels.</p>
      <a class="epaww-dl-btn" href="https://www.gowpi.org/download/6392/?tmstv=1711655312" target="_blank" rel="noopener">&#x2B07; Download PDF</a>
    </div>

  </div>

  <!-- ── Video Training ────────────────────────────────────────────────── -->
  <span class="epaww-section-title">// Video Training</span>
  <div class="epaww-cards">

    <div class="epaww-doc">
      <h2>Process Control Technical Assistance Video Series — Part 1: Introduction</h2>
      <p>The first in EPA's 4-part process control video series for wastewater operators. Introduces the fundamentals of process control monitoring — what to measure, how to interpret results, and how to use data to make operational decisions that keep treatment performance within permit limits.</p>
      <a class="epaww-dl-btn" href="https://youtu.be/g2dA4RHyNWc" target="_blank" rel="noopener">&#x25B6; Watch on YouTube</a>
    </div>

    <div class="epaww-doc">
      <h2>Process Control Technical Assistance Video Series — Part 2: WWTP Walkthrough</h2>
      <p>Walks through a complete wastewater treatment plant, identifying key process control monitoring points at each treatment stage — from headworks through secondary treatment and disinfection. Demonstrates how to collect samples and take process measurements correctly in the field.</p>
      <a class="epaww-dl-btn" href="https://youtu.be/1QSSyDsw2WA" target="_blank" rel="noopener">&#x25B6; Watch on YouTube</a>
    </div>

    <div class="epaww-doc">
      <h2>Process Control Technical Assistance Video Series — Part 3: Tools and Equipment</h2>
      <p>Covers the instruments and equipment wastewater operators use for process monitoring — including dissolved oxygen meters, pH meters, settleability tests, and turbidimeters. Explains proper calibration, use, and maintenance of field testing equipment essential for daily operations.</p>
      <a class="epaww-dl-btn" href="https://youtu.be/XveEG4Pn8kg" target="_blank" rel="noopener">&#x25B6; Watch on YouTube</a>
    </div>

    <div class="epaww-doc">
      <h2>Process Control Technical Assistance Video Series — Part 4: Troubleshooting</h2>
      <p>The final video in the series focuses on troubleshooting activated sludge and lagoon systems when effluent quality declines. Demonstrates a systematic diagnostic approach — reading process data, identifying root causes, and making process adjustments to restore compliance.</p>
      <a class="epaww-dl-btn" href="https://youtu.be/Gu0NsfthhdQ" target="_blank" rel="noopener">&#x25B6; Watch on YouTube</a>
    </div>

  </div>

  <p class="epaww-source">Source: <a href="https://www.epa.gov/compliance/resources-wastewater-operators" target="_blank" rel="noopener">EPA Compliance — Resources for Wastewater Operators</a></p>

  <a class="epaww-back" href="/wastewater-study-guides/">&#x2190; Back to Study Guide</a>

</div>
HTML;

$page_id = wp_insert_post( [
    'post_title'   => 'EPA Wastewater Resources',
    'post_name'    => 'epa-wastewater-resources',
    'post_content' => $content,
    'post_status'  => 'publish',
    'post_type'    => 'page',
    'post_author'  => 1,
], true );

if ( is_wp_error( $page_id ) ) {
    echo "ERROR creating page: " . $page_id->get_error_message() . "\n";
    exit;
}

echo "OK: page created (ID $page_id) at /epa-wastewater-resources/\n";

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE.\n";
