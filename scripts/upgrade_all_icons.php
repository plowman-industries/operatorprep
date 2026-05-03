<?php
/**
 * Upgrade all simulator SVG icons to clearly recognizable symbols.
 * Updates:  WW1/WW2 (page 1093), T1/T2 (page 1004), D1/D2 (page 1094)
 *
 * Strategy: depth-aware group replacement — each component group found by
 * data-id, nested <g> counted, entire block swapped for new recognizable icon.
 */
echo "Upgrading simulator icons to recognizable symbols...\n";

// ─── Helper: replace one component group by data-id ──────────────────────────
function replace_comp($content, $data_id, $new_group) {
    $search = 'data-id="' . $data_id . '"';
    $pos    = strpos($content, $search);
    if ($pos === false) { echo "  WARN: data-id='$data_id' not found\n"; return $content; }
    $g_start = strrpos(substr($content, 0, $pos), '<g ');
    if ($g_start === false) return $content;
    $p = $g_start; $depth = 0; $len = strlen($content); $end = -1;
    while ($p < $len) {
        if ($content[$p] === '<') {
            $two  = substr($content, $p, 2);
            $four = substr($content, $p, 4);
            if ($two === '<g' && ($p+2 < $len) && ($content[$p+2] === ' ' || $content[$p+2] === '>')) {
                $depth++; $p += 2; continue;
            }
            if ($four === '</g>') {
                $depth--;
                if ($depth === 0) { $end = $p + 4; break; }
                $p += 4; continue;
            }
        }
        $p++;
    }
    if ($end === -1) { echo "  WARN: no close tag for '$data_id'\n"; return $content; }
    return substr($content, 0, $g_start) . $new_group . substr($content, $end);
}

function update_page($pid, $label, $replacements) {
    $page = get_post($pid);
    if (!$page) { echo "ERROR: page $pid not found\n"; return; }
    $c = $page->post_content;
    foreach ($replacements as $id => $html) {
        $c = replace_comp($c, $id, $html);
        echo "  [$label] $id → replaced\n";
    }
    $result = wp_update_post(['ID' => $pid, 'post_content' => $c]);
    if (is_wp_error($result)) { echo "ERROR $pid: " . $result->get_error_message() . "\n"; return; }
    echo "OK: $label (page $pid) icons updated\n";
}


// ═══════════════════════════════════════════════════════════════════════════════
//  WW1/WW2  (page 1093)
// ═══════════════════════════════════════════════════════════════════════════════
$ww = [];

// ── Collection System ─────────────────────────────────────────────────────────
$ww['collection'] = '<g class="ww-comp" data-id="collection" role="button" aria-label="Collection System">
  <!-- Manhole cover (circle with cross-hatch) + sewer pipe -->
  <circle cx="80" cy="190" r="26" fill="#374151" stroke="#9ca3af" stroke-width="2"/>
  <line x1="80" y1="164" x2="80" y2="216" stroke="#111827" stroke-width="3"/>
  <line x1="54" y1="190" x2="106" y2="190" stroke="#111827" stroke-width="3"/>
  <line x1="61" y1="171" x2="99" y2="209" stroke="#111827" stroke-width="1.5"/>
  <line x1="99" y1="171" x2="61" y2="209" stroke="#111827" stroke-width="1.5"/>
  <circle cx="80" cy="190" r="7" fill="#6b7280"/>
  <rect x="73" y="216" width="14" height="16" rx="2" fill="#374151" stroke="#6b7280" stroke-width="1"/>
  <rect x="67" y="229" width="26" height="4" rx="2" fill="#60a5fa" opacity=".6"/>
  <rect class="ww-label-bg" x="30" y="137" width="100" height="18"/>
  <text class="ww-label" x="80" y="150" text-anchor="middle">Collection System</text>
  <text class="ww-sublabel" x="80" y="246" text-anchor="middle">Influent</text>
  <rect class="ww-drop-zone" data-id="collection" x="28" y="135" width="104" height="114" rx="8"/>
</g>';

// ── Headworks ─────────────────────────────────────────────────────────────────
$ww['headworks'] = '<g class="ww-comp" data-id="headworks" role="button" aria-label="Headworks">
  <!-- Channel with bar screen (left) + grit chamber (right) -->
  <rect x="200" y="165" width="120" height="60" rx="3" fill="#e0f2fe" stroke="#78716c" stroke-width="1.5"/>
  <!-- Bar screen: heavy vertical bars across left section -->
  <rect x="200" y="165" width="2" height="60" fill="#374151"/>
  <line x1="216" y1="165" x2="214" y2="225" stroke="#374151" stroke-width="4"/>
  <line x1="224" y1="165" x2="222" y2="225" stroke="#374151" stroke-width="4"/>
  <line x1="232" y1="165" x2="230" y2="225" stroke="#374151" stroke-width="4"/>
  <line x1="240" y1="165" x2="238" y2="225" stroke="#374151" stroke-width="4"/>
  <line x1="248" y1="165" x2="246" y2="225" stroke="#374151" stroke-width="4"/>
  <!-- Dividing wall -->
  <line x1="260" y1="165" x2="260" y2="225" stroke="#64748b" stroke-width="2"/>
  <!-- Grit chamber right section with grit pile -->
  <rect x="262" y="165" width="58" height="60" rx="2" fill="#d4d4d4" opacity=".7"/>
  <ellipse cx="291" cy="218" rx="22" ry="5" fill="#92400e" opacity=".6"/>
  <polygon points="272,225 310,225 302,210 280,210" fill="#a8a29e"/>
  <!-- Labels -->
  <text class="ww-sublabel" x="230" y="244" text-anchor="middle">Screens</text>
  <text class="ww-sublabel" x="291" y="244" text-anchor="middle">Grit</text>
  <rect class="ww-label-bg" x="210" y="137" width="110" height="18"/>
  <text class="ww-label" x="265" y="150" text-anchor="middle">Headworks</text>
  <rect class="ww-drop-zone" data-id="headworks" x="193" y="135" width="144" height="114" rx="8"/>
</g>';

// ── Primary Clarifier ─────────────────────────────────────────────────────────
$ww['primary_clar'] = '<g class="ww-comp" data-id="primary_clar" role="button" aria-label="Primary Clarifier">
  <!-- Rectangular settling tank cross-section -->
  <rect x="406" y="162" width="128" height="100" rx="4" fill="#dbeafe" stroke="#92400e" stroke-width="2"/>
  <!-- Surface scum (top band) -->
  <rect x="406" y="162" width="128" height="11" rx="2" fill="#d6d3d1" opacity=".7"/>
  <!-- Sludge blanket (settling particles zone) -->
  <circle cx="430" cy="215" r="4" fill="#a8a29e" opacity=".7"/>
  <circle cx="445" cy="222" r="5" fill="#a8a29e" opacity=".7"/>
  <circle cx="462" cy="210" r="4" fill="#78716c" opacity=".8"/>
  <circle cx="478" cy="220" r="5" fill="#78716c" opacity=".8"/>
  <circle cx="493" cy="213" r="4" fill="#a8a29e" opacity=".7"/>
  <!-- Sludge blanket bottom -->
  <polygon points="406,262 534,262 524,240 416,240" fill="#92400e" opacity=".45"/>
  <!-- Primary sludge hopper -->
  <polygon points="450,262 490,262 482,280 458,280" fill="#78350f"/>
  <!-- Effluent weir notch (right wall) -->
  <rect x="529" y="164" width="5" height="25" fill="#f0f9ff"/>
  <!-- Skimmer arm -->
  <line x1="415" y1="177" x2="525" y2="177" stroke="#475569" stroke-width="2"/>
  <rect class="ww-label-bg" x="403" y="137" width="134" height="18"/>
  <text class="ww-label" x="470" y="150" text-anchor="middle">Primary Clarifier</text>
  <rect class="ww-drop-zone" data-id="primary_clar" x="398" y="135" width="144" height="144" rx="8"/>
</g>';

// ── Aeration Basin ────────────────────────────────────────────────────────────
$ww['aeration'] = '<g class="ww-comp" data-id="aeration" role="button" aria-label="Aeration Basin">
  <!-- Tank outline -->
  <rect x="606" y="162" width="128" height="105" rx="4" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/>
  <!-- Diffuser header pipe at bottom -->
  <line x1="616" y1="255" x2="724" y2="255" stroke="#374151" stroke-width="4"/>
  <!-- Diffuser drops (short stems) -->
  <line x1="626" y1="250" x2="626" y2="255" stroke="#374151" stroke-width="2"/>
  <line x1="641" y1="250" x2="641" y2="255" stroke="#374151" stroke-width="2"/>
  <line x1="656" y1="250" x2="656" y2="255" stroke="#374151" stroke-width="2"/>
  <line x1="671" y1="250" x2="671" y2="255" stroke="#374151" stroke-width="2"/>
  <line x1="686" y1="250" x2="686" y2="255" stroke="#374151" stroke-width="2"/>
  <line x1="701" y1="250" x2="701" y2="255" stroke="#374151" stroke-width="2"/>
  <line x1="716" y1="250" x2="716" y2="255" stroke="#374151" stroke-width="2"/>
  <!-- Bubble columns rising from diffusers -->
  <circle cx="626" cy="240" r="3.5" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <circle cx="626" cy="227" r="3" fill="none" stroke="#93c5fd" stroke-width="1.5"/>
  <circle cx="641" cy="236" r="4" fill="none" stroke="#3b82f6" stroke-width="1.5"/>
  <circle cx="643" cy="220" r="2.5" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <circle cx="656" cy="242" r="3" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <circle cx="659" cy="225" r="4" fill="none" stroke="#3b82f6" stroke-width="1.5"/>
  <circle cx="671" cy="238" r="3.5" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <circle cx="673" cy="222" r="3" fill="none" stroke="#93c5fd" stroke-width="1.5"/>
  <circle cx="686" cy="243" r="3" fill="none" stroke="#3b82f6" stroke-width="1.5"/>
  <circle cx="689" cy="228" r="4" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <circle cx="701" cy="237" r="3.5" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <circle cx="703" cy="220" r="2.5" fill="none" stroke="#3b82f6" stroke-width="1.5"/>
  <circle cx="716" cy="240" r="3" fill="none" stroke="#60a5fa" stroke-width="1.5"/>
  <!-- O₂ label -->
  <text x="670" y="180" text-anchor="middle" fill="#1e40af" font-size="12" font-weight="700" font-family="sans-serif">O₂ ↑</text>
  <text class="ww-sublabel" x="670" y="277" text-anchor="middle">Activated Sludge</text>
  <rect class="ww-label-bg" x="607" y="137" width="126" height="18"/>
  <text class="ww-label" x="670" y="150" text-anchor="middle">Aeration Basin</text>
  <rect class="ww-drop-zone" data-id="aeration" x="598" y="135" width="144" height="144" rx="8"/>
</g>';

// ── Secondary Clarifier (TOP VIEW — circular with rake arms) ──────────────────
$ww['secondary_clar'] = '<g class="ww-comp" data-id="secondary_clar" role="button" aria-label="Secondary Clarifier">
  <!-- Top-view circular clarifier: THE standard clarifier symbol -->
  <!-- Outer tank wall -->
  <circle cx="870" cy="215" r="60" fill="#dcfce7" stroke="#065f46" stroke-width="2.5"/>
  <!-- Weir channel (inner ring) -->
  <circle cx="870" cy="215" r="52" fill="none" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="4,3"/>
  <!-- Settled sludge zone (center, darker) -->
  <circle cx="870" cy="215" r="25" fill="#dcfce7" opacity=".5"/>
  <!-- 4 Rake arms from center -->
  <line x1="870" y1="215" x2="922" y2="215" stroke="#374151" stroke-width="3"/>
  <line x1="870" y1="215" x2="818" y2="215" stroke="#374151" stroke-width="3"/>
  <line x1="870" y1="215" x2="870" y2="163" stroke="#374151" stroke-width="3"/>
  <line x1="870" y1="215" x2="870" y2="267" stroke="#374151" stroke-width="3"/>
  <!-- Diagonal cross-bracing (2 diagonal arms for realism) -->
  <line x1="870" y1="215" x2="907" y2="178" stroke="#374151" stroke-width="1.5" opacity=".5"/>
  <line x1="870" y1="215" x2="833" y2="252" stroke="#374151" stroke-width="1.5" opacity=".5"/>
  <!-- Center drive unit -->
  <circle cx="870" cy="215" r="12" fill="#374151" stroke="#6b7280" stroke-width="1.5"/>
  <circle cx="870" cy="215" r="5" fill="#94a3b8"/>
  <!-- Clear effluent label -->
  <text x="870" y="193" text-anchor="middle" fill="#065f46" font-size="8" font-weight="600" font-family="sans-serif">Clear Effluent</text>
  <rect class="ww-label-bg" x="798" y="137" width="144" height="18"/>
  <text class="ww-label" x="870" y="150" text-anchor="middle">Secondary Clarifier</text>
  <rect class="ww-drop-zone" data-id="secondary_clar" x="798" y="135" width="144" height="144" rx="8"/>
</g>';

// ── Disinfection ──────────────────────────────────────────────────────────────
$ww['disinfection'] = '<g class="ww-comp" data-id="disinfection" role="button" aria-label="Disinfection">
  <!-- Serpentine contact chamber with baffles -->
  <rect x="1005" y="162" width="90" height="75" rx="4" fill="#fefce8" stroke="#eab308" stroke-width="2"/>
  <!-- Baffles alternating from top and bottom -->
  <line x1="1022" y1="162" x2="1022" y2="220" stroke="#374151" stroke-width="4"/>
  <line x1="1039" y1="178" x2="1039" y2="237" stroke="#374151" stroke-width="4"/>
  <line x1="1056" y1="162" x2="1056" y2="220" stroke="#374151" stroke-width="4"/>
  <line x1="1073" y1="178" x2="1073" y2="237" stroke="#374151" stroke-width="4"/>
  <!-- Cl₂ label (prominent, bold) -->
  <text x="1050" y="157" text-anchor="middle" fill="#ca8a04" font-size="13" font-weight="800" font-family="sans-serif">Cl₂</text>
  <rect class="ww-label-bg" x="1003" y="137" width="94" height="18"/>
  <text class="ww-label" x="1050" y="150" text-anchor="middle">Disinfection</text>
  <rect class="ww-drop-zone" data-id="disinfection" x="998" y="135" width="104" height="114" rx="8"/>
</g>';

// ── Effluent Discharge ────────────────────────────────────────────────────────
$ww['effluent'] = '<g class="ww-comp" data-id="effluent" role="button" aria-label="Effluent Discharge">
  <!-- Discharge pipe → water waves (outfall) -->
  <rect x="1122" y="188" width="52" height="16" rx="3" fill="#dbeafe" stroke="#38bdf8" stroke-width="2"/>
  <!-- Pipe end cap with opening -->
  <rect x="1170" y="183" width="8" height="26" rx="2" fill="#374151"/>
  <!-- Water waves cascading down -->
  <path d="M1124,220 Q1132,214 1140,220 Q1148,226 1156,220 Q1164,214 1172,220" fill="none" stroke="#38bdf8" stroke-width="2.5"/>
  <path d="M1126,230 Q1134,224 1142,230 Q1150,236 1158,230 Q1166,224 1174,230" fill="none" stroke="#7dd3fc" stroke-width="2"/>
  <path d="M1128,240 Q1136,234 1144,240 Q1152,246 1160,240 Q1168,234 1176,240" fill="none" stroke="#bae6fd" stroke-width="1.5"/>
  <!-- Flow direction -->
  <text x="1148" y="198" text-anchor="middle" fill="#0369a1" font-size="11" font-weight="700" font-family="sans-serif">→</text>
  <rect class="ww-label-bg" x="1118" y="142" width="74" height="18"/>
  <text class="ww-label" x="1155" y="155" text-anchor="middle">Discharge</text>
  <rect class="ww-drop-zone" data-id="effluent" x="1116" y="140" width="78" height="104" rx="8"/>
</g>';

// ── Thickener ─────────────────────────────────────────────────────────────────
$ww['thickener'] = '<g class="ww-comp" data-id="thickener" role="button" aria-label="Sludge Thickener">
  <!-- Gravity thickener cross-section (trapezoid) -->
  <polygon points="286,378 384,378 372,442 298,442" fill="#dbeafe" stroke="#a16207" stroke-width="2"/>
  <!-- Clear water overflow zone (top) -->
  <rect x="286" y="378" width="98" height="12" fill="#bae6fd" opacity=".6" rx="2"/>
  <!-- Sludge blanket (bottom) -->
  <polygon points="311,432 359,432 351,442 319,442" fill="#92400e" opacity=".55"/>
  <!-- Center thickening zone -->
  <circle cx="335" cy="412" r="3.5" fill="#a8a29e" opacity=".7"/>
  <circle cx="322" cy="418" r="4" fill="#a8a29e" opacity=".7"/>
  <circle cx="348" cy="416" r="3" fill="#78716c" opacity=".8"/>
  <!-- Center drive shaft -->
  <line x1="335" y1="378" x2="335" y2="432" stroke="#475569" stroke-width="2"/>
  <!-- Overflow weirs (notches) on top -->
  <line x1="295" y1="375" x2="375" y2="375" stroke="#3b82f6" stroke-width="2"/>
  <!-- Thickened sludge outlet at bottom -->
  <rect x="328" y="442" width="14" height="8" rx="1" fill="#78350f" stroke="#a16207" stroke-width="1.5"/>
  <text class="ww-sublabel" x="335" y="396" text-anchor="middle">Gravity</text>
  <rect class="ww-label-bg" x="290" y="352" width="90" height="18"/>
  <text class="ww-label" x="335" y="365" text-anchor="middle">Thickener</text>
  <rect class="ww-drop-zone" data-id="thickener" x="278" y="350" width="114" height="104" rx="8"/>
</g>';

// ── Anaerobic Digester ────────────────────────────────────────────────────────
$ww['digester'] = '<g class="ww-comp" data-id="digester" role="button" aria-label="Anaerobic Digester">
  <!-- Egg-shaped/dome tank: dome top + cylindrical body -->
  <rect x="96" y="406" width="118" height="56" rx="3" fill="#fef3c7" stroke="#dc2626" stroke-width="2"/>
  <ellipse cx="155" cy="406" rx="59" ry="26" fill="#fef3c7" stroke="#dc2626" stroke-width="2"/>
  <!-- Gas flame icon at top (methane CH₄) -->
  <path d="M151,383 C151,376 145,371 148,365 C150,361 154,364 155,368 C157,362 162,358 163,365 C165,361 168,368 166,374 C168,370 172,374 170,379 C168,382 164,380 163,383 C162,386 158,382 155,386 C152,382 148,386 147,383 C145,380 150,376 151,383Z" fill="#f97316"/>
  <ellipse cx="155" cy="384" rx="5" ry="3" fill="#fbbf24"/>
  <!-- Gas bubble indicators -->
  <circle cx="130" cy="435" r="4.5" fill="none" stroke="#fbbf24" stroke-width="1.5"/>
  <circle cx="145" cy="426" r="3.5" fill="none" stroke="#f97316" stroke-width="1.5"/>
  <circle cx="170" cy="437" r="5" fill="none" stroke="#fbbf24" stroke-width="1.5"/>
  <circle cx="185" cy="428" r="3" fill="none" stroke="#f97316" stroke-width="1.5"/>
  <!-- CH₄ label -->
  <text x="155" y="449" text-anchor="middle" fill="#92400e" font-size="9" font-weight="600" font-family="sans-serif">CH₄</text>
  <text class="ww-sublabel" x="155" y="465" text-anchor="middle">95°F / 35°C</text>
  <rect class="ww-label-bg" x="90" y="352" width="130" height="18"/>
  <text class="ww-label" x="155" y="365" text-anchor="middle">Anaerobic Digester</text>
  <rect class="ww-drop-zone" data-id="digester" x="88" y="350" width="134" height="124" rx="8"/>
</g>';

// ── Dewatering / Biosolids ────────────────────────────────────────────────────
$ww['biosolids'] = '<g class="ww-comp" data-id="biosolids" role="button" aria-label="Dewatering and Biosolids">
  <!-- Belt filter press: two rollers + belt -->
  <rect x="94" y="496" width="122" height="40" rx="3" fill="#f1f5f9" stroke="#854d0e" stroke-width="1.5"/>
  <!-- Left roller -->
  <circle cx="116" cy="516" r="15" fill="#e2e8f0" stroke="#374151" stroke-width="2"/>
  <circle cx="116" cy="516" r="5" fill="#6b7280"/>
  <!-- Right roller -->
  <circle cx="194" cy="516" r="15" fill="#e2e8f0" stroke="#374151" stroke-width="2"/>
  <circle cx="194" cy="516" r="5" fill="#6b7280"/>
  <!-- Belt (top line) -->
  <line x1="116" y1="501" x2="194" y2="501" stroke="#374151" stroke-width="3"/>
  <!-- Belt (bottom line) -->
  <line x1="116" y1="531" x2="194" y2="531" stroke="#374151" stroke-width="3"/>
  <!-- Pressed cake output (right side) -->
  <rect x="205" y="508" width="10" height="16" rx="2" fill="#a8a29e" stroke="#78716c" stroke-width="1"/>
  <!-- Filtrate drips (below belt) -->
  <line x1="140" y1="531" x2="138" y2="540" stroke="#60a5fa" stroke-width="1.5"/>
  <line x1="155" y1="531" x2="153" y2="541" stroke="#60a5fa" stroke-width="1.5"/>
  <line x1="170" y1="531" x2="168" y2="540" stroke="#60a5fa" stroke-width="1.5"/>
  <rect class="ww-label-bg" x="87" y="472" width="136" height="18"/>
  <text class="ww-label" x="155" y="485" text-anchor="middle">Dewatering / Biosolids</text>
  <rect class="ww-drop-zone" data-id="biosolids" x="88" y="470" width="134" height="84" rx="8"/>
</g>';

// ── Sludge Pumps (RAS/WAS) — P&ID centrifugal pump symbol ────────────────────
$ww['ras_was'] = '<g class="ww-comp" data-id="ras_was" role="button" aria-label="RAS WAS Sludge Pumps">
  <!-- Standard P&ID centrifugal pump: circle + triangle impeller -->
  <!-- Pump casing circle -->
  <circle cx="870" cy="412" r="24" fill="#eff6ff" stroke="#7c3aed" stroke-width="2.5"/>
  <!-- Impeller triangle (pointing from inlet toward outlet) -->
  <polygon points="850,412 876,399 876,425" fill="#7c3aed" opacity=".7"/>
  <!-- Suction inlet (left horizontal pipe) -->
  <line x1="820" y1="412" x2="846" y2="412" stroke="#374151" stroke-width="4"/>
  <rect x="820" y="409" width="26" height="6" fill="#374151" rx="1"/>
  <!-- Discharge outlet (top vertical pipe) -->
  <line x1="870" y1="388" x2="870" y2="368" stroke="#374151" stroke-width="4"/>
  <rect x="867" y="368" width="6" height="20" fill="#374151" rx="1"/>
  <!-- Center shaft dot -->
  <circle cx="870" cy="412" r="4.5" fill="#7c3aed"/>
  <text class="ww-sublabel" x="870" y="440" text-anchor="middle">RAS / WAS</text>
  <rect class="ww-label-bg" x="818" y="362" width="104" height="18"/>
  <text class="ww-label" x="870" y="375" text-anchor="middle">Sludge Pumps</text>
  <rect class="ww-drop-zone" data-id="ras_was" x="818" y="360" width="104" height="94" rx="8"/>
</g>';

// ── Lab / Monitoring ──────────────────────────────────────────────────────────
$ww['lab'] = '<g class="ww-comp" data-id="lab" role="button" aria-label="Laboratory and Process Monitoring">
  <!-- Erlenmeyer flask (universal lab symbol) -->
  <path d="M1042,380 L1042,400 L1022,435 L1078,435 L1058,400 L1058,380 Z" fill="#d1fae5" stroke="#22d3ee" stroke-width="2"/>
  <!-- Flask neck -->
  <rect x="1040" y="374" width="20" height="8" rx="3" fill="#374151" stroke="#6b7280" stroke-width="1.5"/>
  <!-- Liquid level inside flask -->
  <path d="M1028,431 Q1050,423 1072,431 L1078,435 L1022,435 Z" fill="#34d399" opacity=".55"/>
  <!-- Measurement markings on flask -->
  <line x1="1046" y1="415" x2="1040" y2="415" stroke="#059669" stroke-width="1.5"/>
  <line x1="1046" y1="422" x2="1040" y2="422" stroke="#059669" stroke-width="1.5"/>
  <!-- Test tube (right) -->
  <rect x="1065" y="378" width="9" height="28" rx="4" fill="#a7f3d0" stroke="#22d3ee" stroke-width="1.5"/>
  <rect x="1066" y="395" width="7" height="9" rx="2" fill="#34d399" opacity=".7"/>
  <rect class="ww-label-bg" x="1000" y="352" width="100" height="18"/>
  <text class="ww-label" x="1050" y="365" text-anchor="middle">Lab / Monitoring</text>
  <rect class="ww-drop-zone" data-id="lab" x="998" y="350" width="104" height="94" rx="8"/>
</g>';

update_page(1093, 'WW1/WW2', $ww);


// ═══════════════════════════════════════════════════════════════════════════════
//  T1/T2  (page 1004)
// ═══════════════════════════════════════════════════════════════════════════════
$tp = [];

// ── Raw Water Source ──────────────────────────────────────────────────────────
$tp['source'] = '<g class="tp-comp" data-id="source" role="button" aria-label="Raw Water Source">
  <!-- Sun above water surface -->
  <circle cx="52" cy="133" r="10" fill="#fbbf24"/>
  <line x1="52" y1="118" x2="52" y2="114" stroke="#fbbf24" stroke-width="2"/>
  <line x1="40" y1="123" x2="37" y2="120" stroke="#fbbf24" stroke-width="2"/>
  <line x1="64" y1="123" x2="67" y2="120" stroke="#fbbf24" stroke-width="2"/>
  <line x1="35" y1="133" x2="31" y2="133" stroke="#fbbf24" stroke-width="2"/>
  <line x1="69" y1="133" x2="73" y2="133" stroke="#fbbf24" stroke-width="2"/>
  <!-- Water surface line -->
  <line x1="14" y1="152" x2="90" y2="152" stroke="#7dd3fc" stroke-width="1.5" stroke-dasharray="5,3"/>
  <!-- Water body (waves = lake/river) -->
  <path d="M14,162 Q27,155 40,162 Q53,169 66,162 Q79,155 90,162" fill="none" stroke="#3b82f6" stroke-width="2.5"/>
  <path d="M14,172 Q27,165 40,172 Q53,179 66,172 Q79,165 90,172" fill="none" stroke="#60a5fa" stroke-width="2"/>
  <path d="M14,182 Q27,175 40,182 Q53,189 66,182 Q79,175 90,182" fill="none" stroke="#93c5fd" stroke-width="1.5"/>
  <rect class="tp-label-bg" x="10" y="102" width="85" height="16"/>
  <text class="tp-label" x="52" y="114" text-anchor="middle">Raw Water</text>
  <text class="tp-sublabel" x="52" y="200" text-anchor="middle">River/Lake</text>
  <rect class="tp-drop-zone" data-id="source" x="8" y="100" width="89" height="104" rx="8"/>
</g>';

// ── Intake Structure ──────────────────────────────────────────────────────────
$tp['intake'] = '<g class="tp-comp" data-id="intake" role="button" aria-label="Intake Structure">
  <!-- Water level line -->
  <line x1="128" y1="152" x2="222" y2="152" stroke="#7dd3fc" stroke-width="1.5" stroke-dasharray="4,2"/>
  <!-- Submerged bar screen -->
  <rect x="134" y="152" width="54" height="42" rx="3" fill="#e0f2fe" stroke="#475569" stroke-width="1.5"/>
  <line x1="144" y1="152" x2="144" y2="194" stroke="#374151" stroke-width="3"/>
  <line x1="153" y1="152" x2="153" y2="194" stroke="#374151" stroke-width="3"/>
  <line x1="162" y1="152" x2="162" y2="194" stroke="#374151" stroke-width="3"/>
  <line x1="171" y1="152" x2="171" y2="194" stroke="#374151" stroke-width="3"/>
  <line x1="180" y1="152" x2="180" y2="194" stroke="#374151" stroke-width="3"/>
  <!-- Low-lift pump (P&ID symbol above water) -->
  <circle cx="200" cy="139" r="13" fill="#eff6ff" stroke="#1e40af" stroke-width="2"/>
  <polygon points="189,139 208,131 208,147" fill="#1e40af" opacity=".7"/>
  <circle cx="200" cy="139" r="4" fill="#1e40af"/>
  <!-- Pipe connecting screen to pump -->
  <line x1="188" y1="152" x2="188" y2="139" stroke="#374151" stroke-width="3"/>
  <rect class="tp-label-bg" x="125" y="102" width="100" height="16"/>
  <text class="tp-label" x="175" y="114" text-anchor="middle">Intake Structure</text>
  <text class="tp-sublabel" x="175" y="214" text-anchor="middle">Screen+Pump</text>
  <rect class="tp-drop-zone" data-id="intake" x="123" y="100" width="104" height="104" rx="8"/>
</g>';

// ── Coagulation (Rapid Mix) ───────────────────────────────────────────────────
$tp['coag'] = '<g class="tp-comp" data-id="coag" role="button" aria-label="Coagulation">
  <!-- Tank -->
  <rect x="261" y="127" width="88" height="66" rx="4" fill="#ede9fe" stroke="#7c3aed" stroke-width="2"/>
  <!-- Rapid mixer shaft (top to turbine) -->
  <line x1="305" y1="127" x2="305" y2="160" stroke="#374151" stroke-width="3"/>
  <!-- 6-blade turbine impeller (rapid mix) -->
  <line x1="305" y1="160" x2="288" y2="148" stroke="#7c3aed" stroke-width="3.5"/>
  <line x1="305" y1="160" x2="322" y2="148" stroke="#7c3aed" stroke-width="3.5"/>
  <line x1="305" y1="160" x2="284" y2="162" stroke="#7c3aed" stroke-width="3.5"/>
  <line x1="305" y1="160" x2="326" y2="162" stroke="#7c3aed" stroke-width="3.5"/>
  <line x1="305" y1="160" x2="290" y2="174" stroke="#7c3aed" stroke-width="3.5"/>
  <line x1="305" y1="160" x2="320" y2="174" stroke="#7c3aed" stroke-width="3.5"/>
  <circle cx="305" cy="160" r="6" fill="#7c3aed"/>
  <!-- Coagulant dosing port (Al₃) -->
  <circle cx="272" cy="132" r="6" fill="#e879f9" stroke="#a21caf" stroke-width="1.5"/>
  <line x1="272" y1="138" x2="272" y2="148" stroke="#a21caf" stroke-width="2"/>
  <line x1="272" y1="148" x2="282" y2="148" stroke="#a21caf" stroke-width="2"/>
  <!-- Rapid mix particles (small dots) -->
  <circle cx="285" cy="178" r="2" fill="#a78bfa" opacity=".7"/>
  <circle cx="298" cy="182" r="2.5" fill="#a78bfa" opacity=".7"/>
  <circle cx="312" cy="177" r="2" fill="#c4b5fd" opacity=".7"/>
  <circle cx="325" cy="183" r="2.5" fill="#a78bfa" opacity=".7"/>
  <text class="tp-chem-label" x="305" y="143" text-anchor="middle">Al₃</text>
  <text class="tp-sublabel" x="305" y="214" text-anchor="middle">Rapid Mix</text>
  <rect class="tp-label-bg" x="255" y="102" width="100" height="16"/>
  <text class="tp-label" x="305" y="114" text-anchor="middle">Coagulation</text>
  <rect class="tp-drop-zone" data-id="coag" x="253" y="100" width="104" height="104" rx="8"/>
</g>';

// ── Flocculation (Slow Paddle Mix) ───────────────────────────────────────────
$tp['floc'] = '<g class="tp-comp" data-id="floc" role="button" aria-label="Flocculation">
  <!-- Tank -->
  <rect x="391" y="127" width="88" height="66" rx="4" fill="#e0f2fe" stroke="#0891b2" stroke-width="2"/>
  <!-- Slow paddle mixer shaft -->
  <line x1="435" y1="127" x2="435" y2="193" stroke="#374151" stroke-width="3"/>
  <!-- Wide horizontal paddles (gentle mixing) -->
  <rect x="408" y="150" width="54" height="8" rx="3" fill="#0891b2" opacity=".75"/>
  <rect x="413" y="166" width="44" height="6" rx="3" fill="#0891b2" opacity=".55"/>
  <!-- Floc particles (larger = good flocculation) -->
  <circle cx="405" cy="182" r="6" fill="#bae6fd" stroke="#0891b2" stroke-width="1.5"/>
  <circle cx="420" cy="177" r="7" fill="#bae6fd" stroke="#0891b2" stroke-width="1.5"/>
  <circle cx="435" cy="183" r="8" fill="#bae6fd" stroke="#0891b2" stroke-width="1.5"/>
  <circle cx="452" cy="177" r="6" fill="#bae6fd" stroke="#0891b2" stroke-width="1.5"/>
  <circle cx="466" cy="182" r="7" fill="#bae6fd" stroke="#0891b2" stroke-width="1.5"/>
  <text class="tp-sublabel" x="435" y="214" text-anchor="middle">Gentle Mix</text>
  <rect class="tp-label-bg" x="385" y="102" width="100" height="16"/>
  <text class="tp-label" x="435" y="114" text-anchor="middle">Flocculation</text>
  <rect class="tp-drop-zone" data-id="floc" x="383" y="100" width="104" height="104" rx="8"/>
</g>';

// ── Sedimentation ─────────────────────────────────────────────────────────────
$tp['sed'] = '<g class="tp-comp" data-id="sed" role="button" aria-label="Sedimentation">
  <!-- Rectangular settling tank cross-section -->
  <rect x="521" y="122" width="88" height="75" rx="4" fill="#e0f2fe" stroke="#92400e" stroke-width="2"/>
  <!-- Clear supernatant zone (top) -->
  <rect x="521" y="122" width="88" height="22" rx="2" fill="#dbeafe"/>
  <!-- Settling floc particles -->
  <circle cx="540" cy="160" r="4" fill="#a8a29e" opacity=".7"/>
  <circle cx="555" cy="155" r="5" fill="#a8a29e" opacity=".7"/>
  <circle cx="570" cy="162" r="4" fill="#78716c" opacity=".8"/>
  <circle cx="543" cy="168" r="3.5" fill="#78716c" opacity=".8"/>
  <circle cx="560" cy="167" r="4.5" fill="#a8a29e" opacity=".7"/>
  <circle cx="578" cy="160" r="3.5" fill="#78716c" opacity=".8"/>
  <!-- Sludge blanket at bottom -->
  <rect x="521" y="180" width="88" height="17" rx="2" fill="#78350f" opacity=".55"/>
  <!-- Sludge hopper -->
  <polygon points="547,197 573,197 565,209 555,209" fill="#7c2d12"/>
  <!-- Effluent weir -->
  <line x1="605" y1="122" x2="605" y2="148" stroke="#374151" stroke-width="2.5"/>
  <text class="tp-sublabel" x="565" y="218" text-anchor="middle">Settling Tank</text>
  <rect class="tp-label-bg" x="515" y="97" width="100" height="16"/>
  <text class="tp-label" x="565" y="109" text-anchor="middle">Sedimentation</text>
  <rect class="tp-drop-zone" data-id="sed" x="513" y="95" width="104" height="114" rx="8"/>
</g>';

// ── Filtration ────────────────────────────────────────────────────────────────
$tp['filt'] = '<g class="tp-comp" data-id="filt" role="button" aria-label="Filtration">
  <!-- Filter box -->
  <rect x="651" y="127" width="88" height="66" rx="4" fill="#f0fdf4" stroke="#065f46" stroke-width="2"/>
  <!-- Multi-media filter layers (clear and recognizable) -->
  <!-- Anthracite (top, dark gray) -->
  <rect x="651" y="133" width="88" height="13" fill="#9ca3af" rx="2"/>
  <text x="695" y="143" text-anchor="middle" fill="#f8fafc" font-size="7.5" font-weight="700" font-family="sans-serif">Anthracite</text>
  <!-- Sand (middle, yellow) -->
  <rect x="651" y="146" width="88" height="14" fill="#fde68a"/>
  <text x="695" y="156" text-anchor="middle" fill="#374151" font-size="7.5" font-weight="700" font-family="sans-serif">Sand</text>
  <!-- Gravel (bottom support, orange-brown) -->
  <rect x="651" y="160" width="88" height="12" fill="#d97706" opacity=".6"/>
  <text x="695" y="169" text-anchor="middle" fill="#f8fafc" font-size="7.5" font-weight="700" font-family="sans-serif">Gravel</text>
  <!-- Underdrain (dark gray bar) -->
  <rect x="651" y="172" width="88" height="8" rx="2" fill="#6b7280"/>
  <!-- Downflow arrows (into filter) -->
  <text x="666" y="130" fill="#059669" font-size="10" font-weight="700" font-family="sans-serif">↓</text>
  <text x="720" y="130" fill="#059669" font-size="10" font-weight="700" font-family="sans-serif">↓</text>
  <text class="tp-sublabel" x="695" y="214" text-anchor="middle">Sand/Anthracite</text>
  <rect class="tp-label-bg" x="645" y="102" width="100" height="16"/>
  <text class="tp-label" x="695" y="114" text-anchor="middle">Filtration</text>
  <rect class="tp-drop-zone" data-id="filt" x="643" y="100" width="104" height="104" rx="8"/>
</g>';

// ── Disinfection (T1/T2) ──────────────────────────────────────────────────────
$tp['disinf'] = '<g class="tp-comp" data-id="disinf" role="button" aria-label="Disinfection">
  <!-- Serpentine contact chamber -->
  <rect x="781" y="127" width="88" height="66" rx="4" fill="#fefce8" stroke="#eab308" stroke-width="2"/>
  <!-- Serpentine baffles -->
  <line x1="798" y1="127" x2="798" y2="177" stroke="#374151" stroke-width="4"/>
  <line x1="815" y1="142" x2="815" y2="193" stroke="#374151" stroke-width="4"/>
  <line x1="832" y1="127" x2="832" y2="177" stroke="#374151" stroke-width="4"/>
  <line x1="849" y1="142" x2="849" y2="193" stroke="#374151" stroke-width="4"/>
  <!-- Cl₂ label (bold, prominent) -->
  <text x="825" y="122" text-anchor="middle" fill="#ca8a04" font-size="13" font-weight="800" font-family="sans-serif">Cl₂</text>
  <text class="tp-sublabel" x="825" y="214" text-anchor="middle">Contact Basin</text>
  <rect class="tp-label-bg" x="775" y="102" width="100" height="16"/>
  <text class="tp-label" x="825" y="114" text-anchor="middle">Disinfection</text>
  <rect class="tp-drop-zone" data-id="disinf" x="773" y="100" width="104" height="104" rx="8"/>
</g>';

// ── Clearwell ─────────────────────────────────────────────────────────────────
$tp['clearwell'] = '<g class="tp-comp" data-id="clearwell" role="button" aria-label="Clearwell Storage">
  <!-- Underground covered storage tank -->
  <!-- Ground line -->
  <line x1="908" y1="152" x2="1002" y2="152" stroke="#78716c" stroke-width="2" stroke-dasharray="5,3"/>
  <!-- Concrete roof slab -->
  <rect x="908" y="145" width="94" height="9" rx="2" fill="#94a3b8" stroke="#475569" stroke-width="1.5"/>
  <!-- Tank body (underground) -->
  <rect x="915" y="154" width="80" height="45" rx="3" fill="#dbeafe" stroke="#0369a1" stroke-width="2"/>
  <!-- Water level inside (3/4 full) -->
  <rect x="916" y="165" width="78" height="33" rx="2" fill="#93c5fd" opacity=".55"/>
  <!-- Vent pipe -->
  <line x1="955" y1="145" x2="955" y2="127" stroke="#374151" stroke-width="2.5"/>
  <line x1="946" y1="127" x2="964" y2="127" stroke="#374151" stroke-width="2.5"/>
  <!-- CT label (contact time = critical exam topic) -->
  <text x="955" y="160" text-anchor="middle" fill="#0369a1" font-size="10" font-weight="700" font-family="sans-serif">CT</text>
  <text class="tp-sublabel" x="955" y="218" text-anchor="middle">Finished Water</text>
  <rect class="tp-label-bg" x="905" y="97" width="100" height="16"/>
  <text class="tp-label" x="955" y="109" text-anchor="middle">Clearwell</text>
  <rect class="tp-drop-zone" data-id="clearwell" x="903" y="95" width="104" height="114" rx="8"/>
</g>';

// ── Distribution System ───────────────────────────────────────────────────────
$tp['dist'] = '<g class="tp-comp" data-id="dist" role="button" aria-label="Distribution System">
  <!-- Main horizontal transmission pipe -->
  <rect x="1041" y="154" width="98" height="13" rx="5" fill="#dbeafe" stroke="#1e40af" stroke-width="2"/>
  <!-- Branch pipes (up and down) -->
  <line x1="1065" y1="154" x2="1065" y2="132" stroke="#1e40af" stroke-width="3.5"/>
  <line x1="1065" y1="167" x2="1065" y2="192" stroke="#1e40af" stroke-width="3.5"/>
  <line x1="1103" y1="154" x2="1103" y2="132" stroke="#1e40af" stroke-width="3.5"/>
  <line x1="1103" y1="167" x2="1103" y2="192" stroke="#1e40af" stroke-width="3.5"/>
  <!-- Gate valve symbols (diamond bowtie on branches) -->
  <polygon points="1062,140 1065,134 1068,140 1065,146" fill="#374151"/>
  <polygon points="1062,184 1065,178 1068,184 1065,190" fill="#374151"/>
  <polygon points="1100,140 1103,134 1106,140 1103,146" fill="#374151"/>
  <polygon points="1100,184 1103,178 1106,184 1103,190" fill="#374151"/>
  <!-- Flow arrow on main -->
  <text x="1085" y="164" text-anchor="middle" fill="#1e40af" font-size="11" font-weight="700" font-family="sans-serif">→</text>
  <text class="tp-sublabel" x="1090" y="215" text-anchor="middle">Mains+Service</text>
  <rect class="tp-label-bg" x="1035" y="102" width="110" height="16"/>
  <text class="tp-label" x="1090" y="114" text-anchor="middle">Distribution</text>
  <rect class="tp-drop-zone" data-id="dist" x="1033" y="100" width="114" height="104" rx="8"/>
</g>';

update_page(1004, 'T1/T2', $tp);


// ═══════════════════════════════════════════════════════════════════════════════
//  D1/D2  (page 1094)
// ═══════════════════════════════════════════════════════════════════════════════
$dd = [];

// ── Treatment Plant ───────────────────────────────────────────────────────────
// outer rect(30,210,100,100) lbl(80,208) lbg(32,195,96,18) dz(28,193,104,122)
$dd['treatment'] = '<g class="dd-comp" data-id="treatment" role="button" aria-label="Treatment Plant">
  <g class="dd-cb">
    <!-- Plant: multi-step process schematic in a building outline -->
    <rect x="30" y="210" width="100" height="100" rx="8" fill="#fff" stroke="#3b82f6" stroke-width="2"/>
    <!-- Building roofline -->
    <polygon points="30,228 80,215 130,228" fill="#dbeafe" stroke="#3b82f6" stroke-width="1.5"/>
    <!-- 3 process tanks inside (coag, filter, clearwell as simple rectangles) -->
    <rect x="38" y="232" width="20" height="28" rx="2" fill="#bae6fd" stroke="#3b82f6" stroke-width="1.5"/>
    <rect x="64" y="232" width="20" height="28" rx="2" fill="#a7f3d0" stroke="#059669" stroke-width="1.5"/>
    <rect x="90" y="232" width="20" height="28" rx="2" fill="#dbeafe" stroke="#0369a1" stroke-width="1.5"/>
    <!-- Connecting pipes between tanks -->
    <line x1="58" y1="246" x2="64" y2="246" stroke="#374151" stroke-width="2.5"/>
    <line x1="84" y1="246" x2="90" y2="246" stroke="#374151" stroke-width="2.5"/>
    <!-- Outgoing finished water pipe -->
    <line x1="110" y1="246" x2="130" y2="246" stroke="#3b82f6" stroke-width="3"/>
    <!-- Chlorine Cl₂ dosing -->
    <text x="80" y="277" text-anchor="middle" fill="#ca8a04" font-size="9" font-weight="700" font-family="sans-serif">Cl₂</text>
    <!-- NPDES/OK badge -->
    <circle cx="80" cy="293" r="8" fill="#22c55e"/>
    <text x="80" y="296" text-anchor="middle" fill="#fff" font-size="7" font-weight="700" font-family="sans-serif">OK</text>
  </g>
  <rect class="dd-lbg" x="32" y="195" width="96" height="18"/>
  <text class="dd-lbl" x="80" y="208" text-anchor="middle">Treatment Plant</text>
  <rect class="dd-dz" data-id="treatment" x="28" y="193" width="104" height="122" rx="8"/>
</g>';

// ── Transmission Main ─────────────────────────────────────────────────────────
// outer rect(220,245,100,30) lbl(270,240) lbg(222,227,96,18) dz(218,225,104,58) sub "16-48 inch"
$dd['transmission'] = '<g class="dd-comp" data-id="transmission" role="button" aria-label="Transmission Main">
  <g class="dd-cb">
    <!-- Large-diameter pipe with double-wall (thick pipe = transmission main) -->
    <rect x="220" y="245" width="100" height="30" rx="8" fill="#eff6ff" stroke="#3b82f6" stroke-width="2.5"/>
    <rect x="224" y="249" width="92" height="22" rx="6" fill="#dbeafe"/>
    <!-- Flow arrows inside pipe -->
    <text x="248" y="264" fill="#1e40af" font-size="12" font-weight="700" font-family="sans-serif">→ →</text>
    <!-- Diameter label inside -->
    <text x="270" y="258" text-anchor="middle" fill="#1e40af" font-size="8" font-weight="600" font-family="sans-serif">16–48"</text>
  </g>
  <rect class="dd-lbg" x="222" y="227" width="96" height="18"/>
  <text class="dd-lbl" x="270" y="240" text-anchor="middle">Transmission Main</text>
  <text class="dd-sub" x="270" y="290" text-anchor="middle">16-48 inch diameter</text>
  <rect class="dd-dz" data-id="transmission" x="218" y="225" width="104" height="58" rx="8"/>
</g>';

// ── Booster Station ───────────────────────────────────────────────────────────
// outer rect(400,220,80,80) lbl(440,215) lbg(394,202,92,18) dz(398,200,84,104)
$dd['booster'] = '<g class="dd-comp" data-id="booster" role="button" aria-label="Booster Station">
  <g class="dd-cb">
    <!-- P&ID centrifugal pump + pressure gauge -->
    <rect x="400" y="220" width="80" height="80" rx="8" fill="#fff" stroke="#3b82f6" stroke-width="2"/>
    <!-- Pump casing (large circle) -->
    <circle cx="440" cy="260" r="24" fill="#eff6ff" stroke="#1e40af" stroke-width="2.5"/>
    <!-- Impeller triangle -->
    <polygon points="421,260 445,249 445,271" fill="#1e40af" opacity=".75"/>
    <!-- Suction pipe (left) -->
    <line x1="400" y1="260" x2="416" y2="260" stroke="#374151" stroke-width="4"/>
    <!-- Discharge pipe (top) -->
    <line x1="440" y1="236" x2="440" y2="220" stroke="#374151" stroke-width="4"/>
    <!-- Center dot -->
    <circle cx="440" cy="260" r="5" fill="#1e40af"/>
    <!-- Pressure gauge (small circle with needle) -->
    <circle cx="466" cy="232" r="9" fill="#fff" stroke="#475569" stroke-width="1.5"/>
    <line x1="466" y1="232" x2="470" y2="226" stroke="#dc2626" stroke-width="1.5"/>
    <text x="466" y="248" text-anchor="middle" fill="#475569" font-size="7" font-family="sans-serif">PSI</text>
  </g>
  <rect class="dd-lbg" x="394" y="202" width="92" height="18"/>
  <text class="dd-lbl" x="440" y="215" text-anchor="middle">Booster Station</text>
  <rect class="dd-dz" data-id="booster" x="398" y="200" width="84" height="104" rx="8"/>
</g>';

// ── Elevated Storage Tank (Water Tower) ───────────────────────────────────────
// outer rect(555,80,70,50) lbl(590,68) lbg(537,55,106,18) dz(535,53,110,160) sub "75%"
$dd['elevated'] = '<g class="dd-comp" data-id="elevated" role="button" aria-label="Elevated Storage Tank">
  <g class="dd-cb">
    <!-- Water tower: tank on legs (most recognizable symbol!) -->
    <!-- Tower legs (2 V-shaped supports) -->
    <line x1="555" y1="200" x2="573" y2="130" stroke="#475569" stroke-width="3"/>
    <line x1="625" y1="200" x2="607" y2="130" stroke="#475569" stroke-width="3"/>
    <line x1="555" y1="200" x2="590" y2="175" stroke="#475569" stroke-width="2"/>
    <line x1="625" y1="200" x2="590" y2="175" stroke="#475569" stroke-width="2"/>
    <!-- Cross-brace -->
    <line x1="566" y1="170" x2="614" y2="170" stroke="#475569" stroke-width="2"/>
    <!-- Tank body (spherical-ish: rounded rect) -->
    <rect x="558" y="80" width="64" height="48" rx="12" fill="#bae6fd" stroke="#0369a1" stroke-width="2.5"/>
    <!-- Dome cap -->
    <ellipse cx="590" cy="80" rx="32" ry="10" fill="#93c5fd" stroke="#0369a1" stroke-width="2"/>
    <!-- Water level inside (75%) -->
    <rect x="561" y="100" width="58" height="26" rx="6" fill="#38bdf8" opacity=".45"/>
    <!-- 75% level text -->
    <text x="590" y="115" text-anchor="middle" fill="#0284c7" font-size="9" font-weight="700" font-family="sans-serif">75%</text>
  </g>
  <rect class="dd-lbg" x="537" y="55" width="106" height="18"/>
  <text class="dd-lbl" x="590" y="68" text-anchor="middle">Elevated Storage Tank</text>
  <rect class="dd-dz" data-id="elevated" x="535" y="53" width="110" height="160" rx="8"/>
</g>';

// ── Ground-Level Storage ──────────────────────────────────────────────────────
// outer rect(545,360,90,60) lbl(590,355) lbg(537,342,106,18) dz(535,340,110,84)
$dd['ground_storage'] = '<g class="dd-comp" data-id="ground_storage" role="button" aria-label="Ground-Level Storage">
  <g class="dd-cb">
    <!-- Cylindrical tank at grade (clear standpipe silhouette) -->
    <rect x="549" y="370" width="82" height="46" rx="4" fill="#dbeafe" stroke="#0369a1" stroke-width="2"/>
    <!-- Dome top -->
    <ellipse cx="590" cy="370" rx="41" ry="10" fill="#93c5fd" stroke="#0369a1" stroke-width="2"/>
    <!-- Water level fill -->
    <rect x="551" y="390" width="78" height="26" rx="3" fill="#38bdf8" opacity=".4"/>
    <!-- Ground line under tank -->
    <line x1="540" y1="416" x2="640" y2="416" stroke="#78716c" stroke-width="2.5"/>
    <!-- Hatch marks below ground line -->
    <line x1="545" y1="416" x2="541" y2="422" stroke="#78716c" stroke-width="1.5"/>
    <line x1="556" y1="416" x2="552" y2="422" stroke="#78716c" stroke-width="1.5"/>
    <line x1="567" y1="416" x2="563" y2="422" stroke="#78716c" stroke-width="1.5"/>
  </g>
  <rect class="dd-lbg" x="537" y="342" width="106" height="18"/>
  <text class="dd-lbl" x="590" y="355" text-anchor="middle">Ground-Level Storage</text>
  <rect class="dd-dz" data-id="ground_storage" x="535" y="340" width="110" height="84" rx="8"/>
</g>';

// ── Distribution Main ─────────────────────────────────────────────────────────
// outer rect(720,245,100,30) lbl(770,240) lbg(718,227,104,18) dz(718,225,104,58) sub "6-16 inch"
$dd['dist_main'] = '<g class="dd-comp" data-id="dist_main" role="button" aria-label="Distribution Main">
  <g class="dd-cb">
    <!-- Smaller pipe with service branch (6-16" = distribution main) -->
    <rect x="720" y="248" width="100" height="24" rx="6" fill="#eff6ff" stroke="#3b82f6" stroke-width="1.5"/>
    <!-- Service branch up (smaller diameter) -->
    <line x1="770" y1="248" x2="770" y2="232" stroke="#60a5fa" stroke-width="2.5"/>
    <rect x="764" y="228" width="12" height="6" rx="3" fill="#60a5fa"/>
    <!-- Flow arrow -->
    <text x="744" y="264" fill="#3b82f6" font-size="11" font-weight="700" font-family="sans-serif">→</text>
    <text x="780" y="264" fill="#3b82f6" font-size="11" font-weight="700" font-family="sans-serif">→</text>
  </g>
  <rect class="dd-lbg" x="718" y="227" width="104" height="18"/>
  <text class="dd-lbl" x="770" y="240" text-anchor="middle">Distribution Main</text>
  <text class="dd-sub" x="770" y="290" text-anchor="middle">6-16 inch diameter</text>
  <rect class="dd-dz" data-id="dist_main" x="718" y="225" width="104" height="58" rx="8"/>
</g>';

// ── Gate Valve ────────────────────────────────────────────────────────────────
// outer rect(860,240,40,40) lbl(880,235) lbg(845,222,70,18) dz(843,220,74,64)
$dd['gate_valve'] = '<g class="dd-comp" data-id="gate_valve" role="button" aria-label="Gate Valve">
  <g class="dd-cb">
    <!-- Standard gate valve P&ID symbol: bowtie/double-triangle on pipe -->
    <line x1="860" y1="260" x2="900" y2="260" stroke="#374151" stroke-width="4"/>
    <!-- Bowtie valve symbol -->
    <polygon points="867,250 880,260 867,270" fill="#374151"/>
    <polygon points="893,250 880,260 893,270" fill="#374151"/>
    <!-- Hand wheel (operator) -->
    <circle cx="880" cy="244" r="7" fill="none" stroke="#374151" stroke-width="2"/>
    <line x1="880" y1="237" x2="880" y2="250" stroke="#374151" stroke-width="2"/>
  </g>
  <rect class="dd-lbg" x="845" y="222" width="70" height="18"/>
  <text class="dd-lbl" x="880" y="235" text-anchor="middle">Gate Valve</text>
  <rect class="dd-dz" data-id="gate_valve" x="843" y="220" width="74" height="64" rx="8"/>
</g>';

// ── PRV (Pressure Reducing Valve) ─────────────────────────────────────────────
// outer rect(900,145,60,45) lbl(930,140) lbg(898,127,64,18) dz(896,125,68,69) sub "PSI"
$dd['prv'] = '<g class="dd-comp" data-id="prv" role="button" aria-label="PRV">
  <g class="dd-cb">
    <!-- PRV symbol: pipe + regulator body + spring indicator -->
    <line x1="900" y1="168" x2="960" y2="168" stroke="#374151" stroke-width="4"/>
    <!-- Regulator body (circle with spring) -->
    <circle cx="930" cy="168" r="12" fill="#fef3c7" stroke="#d97706" stroke-width="2"/>
    <!-- Spring lines inside circle -->
    <path d="M924,163 Q927,160 930,163 Q933,166 936,163" fill="none" stroke="#d97706" stroke-width="1.5"/>
    <path d="M924,168 Q927,165 930,168 Q933,171 936,168" fill="none" stroke="#d97706" stroke-width="1.5"/>
    <!-- Arrow showing pressure reduction -->
    <text x="930" y="158" text-anchor="middle" fill="#d97706" font-size="8" font-weight="700" font-family="sans-serif">↓PSI</text>
  </g>
  <rect class="dd-lbg" x="898" y="127" width="64" height="18"/>
  <text class="dd-lbl" x="930" y="140" text-anchor="middle">PRV</text>
  <text class="dd-sub" x="930" y="182" text-anchor="middle">PSI</text>
  <rect class="dd-dz" data-id="prv" x="896" y="125" width="68" height="69" rx="8"/>
</g>';

// ── Fire Hydrant ──────────────────────────────────────────────────────────────
// outer rect(1050,150,60,70) lbl(1080,145) lbg(1047,132,66,18) dz(1045,130,70,94)
$dd['hydrant'] = '<g class="dd-comp" data-id="hydrant" role="button" aria-label="Fire Hydrant">
  <g class="dd-cb">
    <!-- Classic fire hydrant silhouette -->
    <!-- Base flange -->
    <rect x="1063" y="212" width="34" height="6" rx="2" fill="#dc2626" stroke="#991b1b" stroke-width="1.5"/>
    <!-- Barrel body (main cylinder) -->
    <rect x="1068" y="180" width="24" height="32" rx="3" fill="#dc2626" stroke="#991b1b" stroke-width="2"/>
    <!-- Bonnet (dome top) -->
    <ellipse cx="1080" cy="180" rx="16" ry="8" fill="#dc2626" stroke="#991b1b" stroke-width="2"/>
    <!-- Nozzle caps (left and right of barrel) -->
    <rect x="1055" y="192" width="13" height="10" rx="3" fill="#b91c1c" stroke="#991b1b" stroke-width="1.5"/>
    <rect x="1092" y="192" width="13" height="10" rx="3" fill="#b91c1c" stroke="#991b1b" stroke-width="1.5"/>
    <!-- Nut on top bonnet -->
    <polygon points="1074,174 1080,168 1086,174" fill="#991b1b"/>
    <!-- Water spray indicator -->
    <text x="1080" y="162" text-anchor="middle" fill="#3b82f6" font-size="12" font-weight="700" font-family="sans-serif">💧</text>
  </g>
  <rect class="dd-lbg" x="1047" y="132" width="66" height="18"/>
  <text class="dd-lbl" x="1080" y="145" text-anchor="middle">Fire Hydrant</text>
  <rect class="dd-dz" data-id="hydrant" x="1045" y="130" width="70" height="94" rx="8"/>
</g>';

// ── Service Line & Meter ──────────────────────────────────────────────────────
// outer rect(1040,310,80,70) lbl(1080,305) lbg(1033,292,94,18) dz(1031,290,98,94)
$dd['service'] = '<g class="dd-comp" data-id="service" role="button" aria-label="Service Line and Meter">
  <g class="dd-cb">
    <!-- Service line: pipe from main + meter box + house connection -->
    <rect x="1040" y="310" width="80" height="70" rx="6" fill="#fff" stroke="#475569" stroke-width="1.5"/>
    <!-- Water meter (box with dial) -->
    <rect x="1055" y="326" width="50" height="28" rx="4" fill="#f8fafc" stroke="#374151" stroke-width="2"/>
    <!-- Meter dial face -->
    <circle cx="1080" cy="340" r="10" fill="#fff" stroke="#374151" stroke-width="1.5"/>
    <!-- Meter needle -->
    <line x1="1080" y1="340" x2="1086" y2="334" stroke="#dc2626" stroke-width="2"/>
    <!-- M label -->
    <text x="1080" y="344" text-anchor="middle" fill="#374151" font-size="8" font-weight="700" font-family="sans-serif">M</text>
    <!-- Inlet pipe (left of meter) -->
    <line x1="1040" y1="340" x2="1055" y2="340" stroke="#374151" stroke-width="3"/>
    <!-- Outlet pipe (right of meter → house) -->
    <line x1="1105" y1="340" x2="1120" y2="340" stroke="#374151" stroke-width="3"/>
    <!-- Curb stop symbol (small square on inlet) -->
    <rect x="1045" y="337" width="7" height="7" rx="1" fill="#374151"/>
  </g>
  <rect class="dd-lbg" x="1033" y="292" width="94" height="18"/>
  <text class="dd-lbl" x="1080" y="305" text-anchor="middle">Service Line &amp; Meter</text>
  <rect class="dd-dz" data-id="service" x="1031" y="290" width="98" height="94" rx="8"/>
</g>';

// ── Backflow Preventer ────────────────────────────────────────────────────────
// outer rect(1040,430,80,55) lbl(1080,425) lbg(1027,412,106,18) dz(1025,410,110,79)
$dd['backflow'] = '<g class="dd-comp" data-id="backflow" role="button" aria-label="Backflow Preventer">
  <g class="dd-cb">
    <!-- RPBA/Double-check backflow preventer symbol -->
    <line x1="1040" y1="457" x2="1120" y2="457" stroke="#374151" stroke-width="4"/>
    <!-- Check valve 1 (left) -->
    <polygon points="1054,447 1066,457 1054,467" fill="#374151"/>
    <line x1="1066" y1="447" x2="1066" y2="467" stroke="#374151" stroke-width="2.5"/>
    <!-- Check valve 2 (right) -->
    <polygon points="1080,447 1092,457 1080,467" fill="#374151"/>
    <line x1="1092" y1="447" x2="1092" y2="467" stroke="#374151" stroke-width="2.5"/>
    <!-- Relief valve ports (top of pipe) -->
    <line x1="1073" y1="457" x2="1073" y2="444" stroke="#475569" stroke-width="2"/>
    <line x1="1070" y1="444" x2="1076" y2="444" stroke="#475569" stroke-width="2"/>
    <!-- "No Backflow" arrow indication -->
    <text x="1080" y="440" text-anchor="middle" fill="#dc2626" font-size="9" font-weight="700" font-family="sans-serif">✖ Back</text>
  </g>
  <rect class="dd-lbg" x="1027" y="412" width="106" height="18"/>
  <text class="dd-lbl" x="1080" y="425" text-anchor="middle">Backflow Preventer</text>
  <rect class="dd-dz" data-id="backflow" x="1025" y="410" width="110" height="79" rx="8"/>
</g>';

// ── Sampling Point ────────────────────────────────────────────────────────────
// outer rect(770,380,60,50) lbl(800,375) lbg(757,362,86,18) dz(755,360,90,74)
$dd['sampling'] = '<g class="dd-comp" data-id="sampling" role="button" aria-label="Sampling Point">
  <g class="dd-cb">
    <!-- Sample tap + bottle -->
    <!-- Pipe with tap -->
    <line x1="770" y1="405" x2="830" y2="405" stroke="#374151" stroke-width="3"/>
    <!-- Sample tap (small T-valve) -->
    <line x1="800" y1="405" x2="800" y2="415" stroke="#374151" stroke-width="3"/>
    <!-- Sample bottle -->
    <rect x="792" y="415" width="16" height="12" rx="3" fill="#dbeafe" stroke="#3b82f6" stroke-width="1.5"/>
    <rect x="795" y="412" width="10" height="4" rx="1" fill="#374151"/>
    <!-- Water sample droplet in bottle -->
    <ellipse cx="800" cy="422" rx="5" ry="4" fill="#60a5fa" opacity=".6"/>
    <!-- Monitoring circle indicator -->
    <circle cx="800" cy="393" r="7" fill="#fef3c7" stroke="#d97706" stroke-width="1.5"/>
    <text x="800" y="397" text-anchor="middle" fill="#d97706" font-size="8" font-weight="700" font-family="sans-serif">S</text>
  </g>
  <rect class="dd-lbg" x="757" y="362" width="86" height="18"/>
  <text class="dd-lbl" x="800" y="375" text-anchor="middle">Sampling Point</text>
  <rect class="dd-dz" data-id="sampling" x="755" y="360" width="90" height="74" rx="8"/>
</g>';

// ── Dead End / Flushing ───────────────────────────────────────────────────────
// outer rect(880,380,60,50) lbl(910,375) lbg(864,362,92,18) dz(862,360,96,74)
$dd['deadend'] = '<g class="dd-comp" data-id="deadend" role="button" aria-label="Dead End Flushing">
  <g class="dd-cb">
    <!-- Dead end: pipe with end cap + flush arrow + warning -->
    <line x1="880" y1="405" x2="920" y2="405" stroke="#374151" stroke-width="4"/>
    <!-- End cap (solid block) -->
    <rect x="918" y="398" width="9" height="14" rx="2" fill="#374151" stroke="#475569" stroke-width="1.5"/>
    <!-- Dead-end warning symbol (!) -->
    <circle cx="900" cy="393" r="8" fill="#fef9c3" stroke="#ca8a04" stroke-width="1.5"/>
    <text x="900" y="397" text-anchor="middle" fill="#ca8a04" font-size="10" font-weight="800" font-family="sans-serif">!</text>
    <!-- Flush hydrant below pipe -->
    <line x1="895" y1="405" x2="895" y2="418" stroke="#374151" stroke-width="3"/>
    <rect x="888" y="418" width="14" height="8" rx="2" fill="#3b82f6" stroke="#1e40af" stroke-width="1.5"/>
    <!-- Flush flow arrows -->
    <text x="908" y="425" fill="#3b82f6" font-size="9" font-weight="700" font-family="sans-serif">↓flush</text>
  </g>
  <rect class="dd-lbg" x="864" y="362" width="92" height="18"/>
  <text class="dd-lbl" x="910" y="375" text-anchor="middle">Dead End / Flushing</text>
  <rect class="dd-dz" data-id="deadend" x="862" y="360" width="96" height="74" rx="8"/>
</g>';

// ── Air Release Valve ─────────────────────────────────────────────────────────
// outer rect(770,120,60,45) lbl(800,115) lbg(753,102,94,18) dz(751,100,98,69)
$dd['air_valve'] = '<g class="dd-comp" data-id="air_valve" role="button" aria-label="Air Release Valve">
  <g class="dd-cb">
    <!-- High-point pipe with air release valve (ARV) -->
    <!-- Pipe at high point (inverted V shape) -->
    <polyline points="770,155 800,135 830,155" fill="none" stroke="#374151" stroke-width="4"/>
    <!-- ARV valve body at apex -->
    <circle cx="800" cy="132" r="9" fill="#fef3c7" stroke="#d97706" stroke-width="2"/>
    <!-- Air rising arrow -->
    <text x="800" y="128" text-anchor="middle" fill="#374151" font-size="10" font-weight="700" font-family="sans-serif">↑</text>
    <!-- "AIR" label inside valve body -->
    <text x="800" y="136" text-anchor="middle" fill="#d97706" font-size="7" font-weight="700" font-family="sans-serif">AIR</text>
    <!-- Small vent tube on top -->
    <line x1="800" y1="123" x2="800" y2="115" stroke="#374151" stroke-width="2"/>
    <line x1="796" y1="115" x2="804" y2="115" stroke="#374151" stroke-width="2"/>
  </g>
  <rect class="dd-lbg" x="753" y="102" width="94" height="18"/>
  <text class="dd-lbl" x="800" y="115" text-anchor="middle">Air Release Valve</text>
  <rect class="dd-dz" data-id="air_valve" x="751" y="100" width="98" height="69" rx="8"/>
</g>';

update_page(1094, 'D1/D2', $dd);

// Flush caches
wp_cache_flush();
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE — all simulator icons upgraded to recognizable symbols.\n";
