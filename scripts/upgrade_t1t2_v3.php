<?php
/**
 * T1/T2 Water Treatment Plant Diagram — v3 UX upgrade (standalone)
 * Page ID 1004 (/t1-t2-plant-diagram/)
 * Run: wp eval-file scripts/upgrade_t1t2_v3.php --allow-root
 */
echo "Upgrading T1/T2 water treatment plant diagram to v3..." . PHP_EOL;

$pid = 1004;
if (!get_post($pid)) { echo "T1/T2 NOT FOUND (ID $pid)" . PHP_EOL; exit; }

$content = <<<'ENDT1'
<!-- wp:html -->
<style>.entry-header.ast-no-thumbnail{display:none!important}</style>
<div id="opp-tp-diagram">
<style>
#opp-tp-diagram{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#0b1320;color:#e2e8f0;max-width:1200px;margin:0 auto;padding:20px;box-sizing:border-box}
#opp-tp-diagram *,#opp-tp-diagram *::before,#opp-tp-diagram *::after{box-sizing:border-box}
.tp-header{text-align:center;margin-bottom:16px}
.tp-header h1{font-size:1.6em;color:#f1f5f9;margin:0 0 4px;font-weight:700}
.tp-header p{color:#94a3b8;font-size:.9em;margin:0}
.tp-howto-btn{background:none;border:none;color:#38bdf8;font-size:.82em;cursor:pointer;text-decoration:underline;margin-top:6px;padding:0}
.tp-howto-box{display:none;background:#132035;border:1px solid #1e3a5f;border-radius:8px;padding:12px 16px;margin:8px auto;font-size:.82em;color:#94a3b8;text-align:left;max-width:640px}
.tp-howto-box.open{display:block}
.tp-howto-box ul{margin:4px 0 0;padding-left:18px}
.tp-howto-box li{margin-bottom:4px}
.tp-modes{display:flex;justify-content:center;gap:10px;margin-bottom:14px;flex-wrap:wrap;align-items:flex-start}
.tp-mode-wrap{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:130px}
.tp-mode-btn{background:#132035;color:#94a3b8;border:1.5px solid #1e3a5f;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}
.tp-mode-btn:hover{background:#1a2e47;color:#e2e8f0;border-color:#2563eb}
.tp-mode-btn.active{background:#0369a1;color:#fff;border-color:#0369a1;box-shadow:0 2px 8px rgba(3,105,161,.4)}
.tp-mode-desc{font-size:.73em;color:#64748b;text-align:center;line-height:1.3}
.tp-quiz-banner{display:none;background:linear-gradient(135deg,#0c4a6e,#0369a1);color:#fff;border-radius:10px;padding:16px 20px;margin-bottom:10px;text-align:center}
.tp-quiz-banner.visible{display:block;animation:tpFade .25s ease}
.tp-qb-intro{font-size:.87em;color:rgba(255,255,255,.8);margin-bottom:4px}
.tp-qb-q{font-size:1.05em;font-weight:700;margin-bottom:4px;min-height:1.4em}
.tp-qb-counter{font-size:.78em;color:rgba(255,255,255,.7);margin-bottom:8px;min-height:1em}
.tp-progress-bar{background:rgba(255,255,255,.2);border-radius:4px;height:6px;margin:0 auto 10px;max-width:300px;overflow:hidden}
.tp-progress-fill{background:#7dd3fc;height:6px;border-radius:4px;width:0;transition:width .4s ease}
.tp-qb-row{display:flex;justify-content:center;align-items:center;gap:16px;flex-wrap:wrap}
.tp-qb-fb{font-size:.92em;font-weight:600;min-height:1.5em}
.tp-qb-fb.correct{color:#7dd3fc}
.tp-qb-fb.wrong{color:#fca5a5}
.tp-qb-score{font-size:.82em;color:rgba(255,255,255,.75)}
.tp-quiz-start{background:rgba(255,255,255,.18);color:#fff;border:1.5px solid rgba(255,255,255,.35);padding:8px 24px;border-radius:7px;font-size:.92em;font-weight:700;cursor:pointer;transition:all .18s;margin-top:8px}
.tp-quiz-start:hover{background:rgba(255,255,255,.28)}
.tp-complete{display:none;background:#0c2340;border:2px solid #38bdf8;border-radius:12px;padding:22px;text-align:center;margin-bottom:10px}
.tp-complete.visible{display:block;animation:tpFade .3s ease}
.tp-complete h3{color:#38bdf8;font-size:1.35em;margin:0 0 8px}
.tp-final-score{font-size:2em;font-weight:800;color:#38bdf8;margin:6px 0}
.tp-complete p{color:#7dd3fc;font-size:.9em;margin:0 0 12px}
.tp-complete-btns{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.tp-complete-btns button{padding:8px 18px;border-radius:8px;font-size:.88em;font-weight:600;cursor:pointer;border:none;transition:all .18s}
.tp-btn-retry{background:#0369a1;color:#fff}
.tp-btn-retry:hover{background:#0284c7}
.tp-btn-explore{background:#132035;color:#e2e8f0;border:1.5px solid #1e3a5f!important}
.tp-btn-explore:hover{background:#1a2e47}
.tp-drag-banner{display:none;background:#132035;border:1.5px solid #1e3a5f;border-radius:10px;padding:12px 18px;margin-bottom:8px;text-align:center;color:#94a3b8;font-size:.87em}
.tp-drag-banner.visible{display:block;animation:tpFade .25s ease}
.tp-db-sel{font-weight:700;color:#38bdf8;font-size:.92em;margin:4px 0}
.tp-drag-score-row{display:flex;justify-content:center;align-items:center;gap:12px;flex-wrap:wrap;margin-top:6px;font-size:.83em;color:#64748b}
.tp-sc-correct{color:#7dd3fc;font-weight:700}
.tp-sc-wrong{color:#f87171;font-weight:700}
.tp-drag-progress{background:#1e3a5f;border-radius:4px;height:6px;max-width:300px;width:100%;margin:6px auto 0;overflow:hidden}
.tp-drag-pfill{background:#38bdf8;height:6px;border-radius:4px;width:0;transition:width .4s ease}
.tp-drag-fb{font-size:.84em;color:#fca5a5;font-weight:600;min-height:1.4em;margin-top:4px}
.tp-drag-controls{display:none;justify-content:center;gap:8px;margin-bottom:8px;flex-wrap:wrap}
.tp-drag-controls.visible{display:flex}
.tp-ctrl-btn{background:#132035;color:#64748b;border:1.5px solid #1e3a5f;padding:5px 14px;border-radius:7px;font-size:.78em;font-weight:600;cursor:pointer;transition:all .18s}
.tp-ctrl-btn:hover{background:#1a2e47;color:#e2e8f0}
.tp-ctrl-btn.active{background:#0369a1;color:#fff;border-color:#0369a1}
.tp-drag-panel{display:none;flex-wrap:wrap;gap:7px;justify-content:center;padding:14px;background:#132035;border:1.5px solid #1e3a5f;border-radius:10px;margin-bottom:10px}
.tp-drag-panel.visible{display:flex}
.tp-chip{background:#0b1320;color:#cbd5e1;padding:6px 13px;border-radius:7px;cursor:grab;font-size:.82em;font-weight:500;border:1.5px solid #1e3a5f;user-select:none;transition:all .18s}
.tp-chip:hover{border-color:#2563eb;background:#132035}
.tp-chip.on{background:#0369a1;color:#fff;border-color:#0369a1;cursor:grabbing}
.tp-chip.dragging{opacity:.4;cursor:grabbing}
.tp-chip.done{background:#0c2340;color:#38bdf8;border-color:#38bdf8;opacity:.7;text-decoration:line-through;cursor:default}
.tp-chip.wrong-chip{animation:tpShake .4s ease;border-color:#f87171}
.tp-svg-wrap{background:#132035;border:1.5px solid #1e3a5f;border-radius:12px;overflow:hidden;margin-bottom:10px}
.tp-svg-wrap svg{display:block;width:100%;height:auto}
.tp-comp{cursor:pointer}
.tp-comp:hover rect:first-child{filter:brightness(1.3)}
.tp-comp.selected rect:first-child{stroke-width:3}
.tp-comp.correct-flash rect:first-child{stroke:#38bdf8!important;stroke-width:3}
.tp-comp.wrong-flash rect:first-child{stroke:#f87171!important;stroke-width:3}
.tp-comp.drag-over rect:first-child{stroke:#f97316!important;stroke-width:3}
.tp-flow-arrow{fill:none;stroke:#1e40af;stroke-width:2;stroke-dasharray:8,4;opacity:.9}
.tp-label{font-family:'Segoe UI',system-ui,sans-serif;fill:#e2e8f0;font-size:10px;font-weight:600;pointer-events:none}
.tp-label-bg{fill:#132035;stroke:#1e3a5f;stroke-width:1;pointer-events:none}
.tp-sublabel{font-family:'Segoe UI',system-ui,sans-serif;fill:#64748b;font-size:8px;pointer-events:none}
.tp-chem-label{font-family:'Segoe UI',system-ui,sans-serif;fill:#fbbf24;font-size:8.5px;font-style:italic;pointer-events:none}
.tp-drop-zone{fill:transparent;stroke:#f59e0b;stroke-width:2;stroke-dasharray:5,3;opacity:0;pointer-events:none}
#opp-tp-diagram.mode-drag .tp-drop-zone{opacity:1;animation:tpPulse 1.8s infinite;pointer-events:all;cursor:pointer}
#opp-tp-diagram.mode-drag .tp-drop-zone.filled{stroke:#38bdf8;opacity:.6;stroke-dasharray:none;animation:none}
@keyframes tpPulse{0%,100%{opacity:.3}50%{opacity:.9}}
@keyframes tpFade{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}
@keyframes tpShake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
.tp-info{background:#132035;border:1.5px solid #1e3a5f;border-radius:12px;padding:18px 22px;margin-top:10px;display:none;position:relative}
.tp-info.visible{display:block;animation:tpFade .25s ease}
.tp-info h2{color:#38bdf8;font-size:1.15em;margin:0 0 3px}
.tp-info-type{color:#d97706;font-size:.78em;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px}
.tp-info p{color:#cbd5e1;line-height:1.65;margin:0 0 10px;font-size:.92em}
.tp-info .tp-tip{background:#0c1a00;border-left:3px solid #f59e0b;padding:9px 14px;border-radius:0 8px 8px 0;margin-top:8px;font-size:.88em;color:#d6d3d1}
.tp-info .tp-tip strong{color:#fbbf24}
.tp-close{position:absolute;top:12px;right:14px;background:#0b1320;border:none;color:#64748b;font-size:1.1em;cursor:pointer;padding:4px 8px;border-radius:6px}
.tp-close:hover{background:#132035;color:#e2e8f0}
@media(max-width:768px){#opp-tp-diagram{padding:10px}.tp-header h1{font-size:1.25em}.tp-mode-btn{padding:6px 10px;font-size:.8em}.tp-mode-wrap{min-width:90px}.tp-label{font-size:7px}}
</style>

<div class="tp-header">
  <h1>Water Treatment Plant &mdash; Interactive Diagram</h1>
  <p>T1 &amp; T2 &mdash; Conventional Surface Water Treatment</p>
  <button class="tp-howto-btn" id="tpHowtoBtn" aria-expanded="false">&#x2139;&#xfe0f; How to use</button>
  <div class="tp-howto-box" id="tpHowtoBox">
    <ul>
      <li><strong>Explore:</strong> Click any component to learn its function and exam tips.</li>
      <li><strong>Quiz Mode:</strong> Click Start Quiz, then click the component named in the prompt.</li>
      <li><strong>Drag &amp; Drop:</strong> Drag label chips onto the matching component. Tap a chip then tap the component on mobile.</li>
    </ul>
  </div>
</div>

<div class="tp-modes" role="tablist">
  <div class="tp-mode-wrap">
    <button class="tp-mode-btn active" id="tpBtn0" role="tab" aria-selected="true" aria-label="Explore mode">&#x1F50D; Explore</button>
    <span class="tp-mode-desc">Click components for details</span>
  </div>
  <div class="tp-mode-wrap">
    <button class="tp-mode-btn" id="tpBtn1" role="tab" aria-selected="false" aria-label="Quiz mode">&#x1F4DD; Quiz Mode</button>
    <span class="tp-mode-desc">Identify components by icon</span>
  </div>
  <div class="tp-mode-wrap">
    <button class="tp-mode-btn" id="tpBtn2" role="tab" aria-selected="false" aria-label="Drag and drop mode">&#x1F3AF; Drag &amp; Drop</button>
    <span class="tp-mode-desc">Match labels to components</span>
  </div>
</div>

<div class="tp-quiz-banner" id="tpQuizBanner" role="status" aria-live="polite">
  <div class="tp-qb-intro" id="tpQBIntro">Click each component when prompted &mdash; trace the full treatment process.</div>
  <div class="tp-qb-q" id="tpQBQ">Ready to test your knowledge?</div>
  <div class="tp-qb-counter" id="tpQBCounter"></div>
  <div class="tp-progress-bar" id="tpProgressBar" style="display:none"><div class="tp-progress-fill" id="tpProgressFill"></div></div>
  <div class="tp-qb-row">
    <div class="tp-qb-fb" id="tpQBFB"></div>
    <div class="tp-qb-score" id="tpQBSc"></div>
  </div>
  <button class="tp-quiz-start" id="tpStartBtn">Start Quiz</button>
</div>

<div class="tp-complete" id="tpComplete" role="alert">
  <h3 id="tpCompTitle">Quiz Complete!</h3>
  <div class="tp-final-score" id="tpCompScore"></div>
  <p id="tpCompMsg"></p>
  <div class="tp-complete-btns">
    <button class="tp-btn-retry" id="tpRetryBtn">&#x1F501; Retry Quiz</button>
    <button class="tp-btn-explore" id="tpExploreBtn">&#x1F50D; Review in Explore Mode</button>
  </div>
</div>

<div class="tp-drag-banner" id="tpDragBanner" role="status" aria-live="polite">
  <div id="tpDragInst">Drag a label onto its matching component &mdash; or tap a label, then tap the component.</div>
  <div class="tp-db-sel" id="tpDragSel"></div>
  <div class="tp-drag-score-row">
    <span>Correct: <span class="tp-sc-correct" id="tpDCorr">0</span></span>
    <span>Wrong: <span class="tp-sc-wrong" id="tpDWrong">0</span></span>
    <span>Remaining: <span id="tpDRem">9</span></span>
  </div>
  <div class="tp-drag-progress"><div class="tp-drag-pfill" id="tpDragFill"></div></div>
  <div class="tp-drag-fb" id="tpDragFB"></div>
</div>

<div class="tp-drag-controls" id="tpDragControls">
  <button class="tp-ctrl-btn" id="tpResetBtn">&#x21BA; Reset</button>
  <button class="tp-ctrl-btn" id="tpShowAnsBtn">&#x1F441; Show Answers</button>
</div>

<div class="tp-drag-panel" id="tpDragPanel" aria-label="Label chips"></div>

<div class="tp-svg-wrap">
<svg viewBox="0 0 1160 320" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Water Treatment Plant diagram">
  <defs>
    <marker id="tpArrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#1e40af"/></marker>
  </defs>
  <path d="M 95,160 L 125,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 225,160 L 255,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 355,160 L 385,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 485,160 L 515,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 615,160 L 645,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 745,160 L 775,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 875,160 L 905,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <path d="M 1005,160 L 1035,160" class="tp-flow-arrow" marker-end="url(#tpArrow)"/>
  <g class="tp-comp" data-id="source" role="button" aria-label="Raw Water Source"><rect x="10" y="120" width="85" height="80" rx="8" fill="#0f2d4a" stroke="#1e40af" stroke-width="2"/><ellipse cx="52" cy="155" rx="28" ry="12" fill="#1e3a5f" stroke="#38bdf8" stroke-width="1.5"/><path d="M 30,162 Q 52,172 74,162" fill="#1e3a5f" stroke="#38bdf8" stroke-width="1.5"/><path d="M 30,169 Q 52,179 74,169" fill="#1e3a5f" stroke="#38bdf8" stroke-width="1" opacity="0.5"/><text class="tp-sublabel" x="52" y="190" text-anchor="middle">River/Lake</text><rect class="tp-label-bg" x="10" y="102" width="85" height="16"/><text class="tp-label" x="52" y="114" text-anchor="middle">Raw Water</text><rect class="tp-drop-zone" data-id="source" x="8" y="100" width="89" height="104" rx="8"/></g>
  <g class="tp-comp" data-id="intake" role="button" aria-label="Intake Structure"><rect x="125" y="120" width="100" height="80" rx="8" fill="#0f2d4a" stroke="#475569" stroke-width="2"/><rect x="135" y="133" width="30" height="55" rx="3" fill="#1e293b"/><line x1="143" y1="138" x2="143" y2="183" stroke="#475569" stroke-width="1.5"/><line x1="151" y1="138" x2="151" y2="183" stroke="#475569" stroke-width="1.5"/><line x1="159" y1="138" x2="159" y2="183" stroke="#475569" stroke-width="1.5"/><ellipse cx="195" cy="165" rx="16" ry="22" fill="#1e293b" stroke="#475569" stroke-width="1.5"/><path d="M 188,158 L 195,150 L 202,158" fill="#38bdf8" opacity="0.6"/><text class="tp-sublabel" x="175" y="214" text-anchor="middle">Screen+Pump</text><rect class="tp-label-bg" x="125" y="102" width="100" height="16"/><text class="tp-label" x="175" y="114" text-anchor="middle">Intake Structure</text><rect class="tp-drop-zone" data-id="intake" x="123" y="100" width="104" height="104" rx="8"/></g>
  <g class="tp-comp" data-id="coag" role="button" aria-label="Coagulation"><rect x="255" y="120" width="100" height="80" rx="8" fill="#0f2d4a" stroke="#7c3aed" stroke-width="2"/><rect x="265" y="133" width="80" height="55" rx="4" fill="#1e1b4b"/><line x1="295" y1="145" x2="295" y2="178" stroke="#7c3aed" stroke-width="2.5"/><line x1="302" y1="140" x2="302" y2="183" stroke="#7c3aed" stroke-width="2.5"/><circle cx="280" cy="162" r="4" fill="#a78bfa" opacity="0.5"/><circle cx="320" cy="155" r="3" fill="#a78bfa" opacity="0.4"/><circle cx="330" cy="168" r="5" fill="#a78bfa" opacity="0.4"/><text class="tp-chem-label" x="305" y="145" text-anchor="middle">Al&#x2083;</text><text class="tp-sublabel" x="305" y="214" text-anchor="middle">Rapid Mix</text><rect class="tp-label-bg" x="255" y="102" width="100" height="16"/><text class="tp-label" x="305" y="114" text-anchor="middle">Coagulation</text><rect class="tp-drop-zone" data-id="coag" x="253" y="100" width="104" height="104" rx="8"/></g>
  <g class="tp-comp" data-id="floc" role="button" aria-label="Flocculation"><rect x="385" y="120" width="100" height="80" rx="8" fill="#0f2d4a" stroke="#0891b2" stroke-width="2"/><rect x="395" y="133" width="80" height="55" rx="4" fill="#082f49"/><ellipse cx="415" cy="162" rx="8" ry="11" fill="none" stroke="#0891b2" stroke-width="1.5" opacity="0.7"/><ellipse cx="435" cy="158" rx="10" ry="13" fill="none" stroke="#0891b2" stroke-width="1.5" opacity="0.7"/><ellipse cx="455" cy="162" rx="9" ry="12" fill="none" stroke="#0891b2" stroke-width="1.5" opacity="0.7"/><circle cx="435" cy="162" r="4" fill="#0891b2" opacity="0.4"/><text class="tp-sublabel" x="435" y="214" text-anchor="middle">Gentle Mix</text><rect class="tp-label-bg" x="385" y="102" width="100" height="16"/><text class="tp-label" x="435" y="114" text-anchor="middle">Flocculation</text><rect class="tp-drop-zone" data-id="floc" x="383" y="100" width="104" height="104" rx="8"/></g>
  <g class="tp-comp" data-id="sed" role="button" aria-label="Sedimentation"><rect x="515" y="115" width="100" height="90" rx="8" fill="#0f2d4a" stroke="#92400e" stroke-width="2"/><path d="M 525,135 L 605,135 L 595,190 L 535,190 Z" fill="#1c1917" stroke="#78350f" stroke-width="1"/><circle cx="545" cy="162" r="4" fill="#a16207" opacity="0.5"/><circle cx="565" cy="175" r="5" fill="#a16207" opacity="0.4"/><circle cx="560" cy="182" r="4" fill="#a16207" opacity="0.6"/><path d="M 535,184 Q 565,190 595,184" fill="#78350f" opacity="0.5" stroke="none"/><line x1="565" y1="135" x2="565" y2="186" stroke="#475569" stroke-width="1"/><text class="tp-sublabel" x="565" y="218" text-anchor="middle">Settling Tank</text><rect class="tp-label-bg" x="515" y="97" width="100" height="16"/><text class="tp-label" x="565" y="109" text-anchor="middle">Sedimentation</text><rect class="tp-drop-zone" data-id="sed" x="513" y="95" width="104" height="114" rx="8"/></g>
  <g class="tp-comp" data-id="filt" role="button" aria-label="Filtration"><rect x="645" y="120" width="100" height="80" rx="8" fill="#0f2d4a" stroke="#065f46" stroke-width="2"/><rect x="655" y="133" width="80" height="20" rx="2" fill="#1e293b"/><rect x="655" y="153" width="80" height="14" rx="2" fill="#292524"/><rect x="655" y="167" width="80" height="10" rx="2" fill="#374151"/><rect x="655" y="177" width="80" height="8" rx="1" fill="#1e293b"/><line x1="670" y1="133" x2="670" y2="185" stroke="#065f46" stroke-width="1" opacity="0.5"/><line x1="690" y1="133" x2="690" y2="185" stroke="#065f46" stroke-width="1" opacity="0.5"/><line x1="710" y1="133" x2="710" y2="185" stroke="#065f46" stroke-width="1" opacity="0.5"/><text class="tp-sublabel" x="695" y="214" text-anchor="middle">Sand/Anthracite</text><rect class="tp-label-bg" x="645" y="102" width="100" height="16"/><text class="tp-label" x="695" y="114" text-anchor="middle">Filtration</text><rect class="tp-drop-zone" data-id="filt" x="643" y="100" width="104" height="104" rx="8"/></g>
  <g class="tp-comp" data-id="disinf" role="button" aria-label="Disinfection"><rect x="775" y="120" width="100" height="80" rx="8" fill="#0f2d4a" stroke="#eab308" stroke-width="2"/><path d="M 788,138 L 865,138 L 865,158 L 788,158 L 788,178 L 865,178 L 865,195" fill="none" stroke="#eab308" stroke-width="2" opacity="0.7"/><text x="825" y="152" text-anchor="middle" fill="#fbbf24" font-size="11" font-weight="bold" font-family="sans-serif">Cl&#x2082;</text><text class="tp-sublabel" x="825" y="214" text-anchor="middle">Contact Basin</text><rect class="tp-label-bg" x="775" y="102" width="100" height="16"/><text class="tp-label" x="825" y="114" text-anchor="middle">Disinfection</text><rect class="tp-drop-zone" data-id="disinf" x="773" y="100" width="104" height="104" rx="8"/></g>
  <g class="tp-comp" data-id="clearwell" role="button" aria-label="Clearwell Storage"><rect x="905" y="115" width="100" height="90" rx="8" fill="#0f2d4a" stroke="#0369a1" stroke-width="2"/><rect x="915" y="130" width="80" height="65" rx="4" fill="#082f49"/><path d="M 917,175 Q 955,182 993,175" fill="#1e3a5f" stroke="#38bdf8" stroke-width="1"/><path d="M 917,183 Q 955,190 993,183" fill="#1e3a5f" stroke="#38bdf8" stroke-width="1" opacity="0.6"/><text class="tp-sublabel" x="955" y="150" text-anchor="middle" fill="#38bdf8">CT</text><text class="tp-sublabel" x="955" y="218" text-anchor="middle">Finished Water</text><rect class="tp-label-bg" x="905" y="97" width="100" height="16"/><text class="tp-label" x="955" y="109" text-anchor="middle">Clearwell</text><rect class="tp-drop-zone" data-id="clearwell" x="903" y="95" width="104" height="114" rx="8"/></g>
  <g class="tp-comp" data-id="dist" role="button" aria-label="Distribution System"><rect x="1035" y="120" width="110" height="80" rx="8" fill="#0f2d4a" stroke="#0369a1" stroke-width="2"/><line x1="1050" y1="160" x2="1135" y2="160" stroke="#38bdf8" stroke-width="3"/><circle cx="1070" cy="160" r="6" fill="none" stroke="#38bdf8" stroke-width="2"/><circle cx="1095" cy="160" r="6" fill="none" stroke="#38bdf8" stroke-width="2"/><line x1="1070" y1="154" x2="1070" y2="140" stroke="#38bdf8" stroke-width="2"/><line x1="1095" y1="154" x2="1095" y2="140" stroke="#38bdf8" stroke-width="2"/><rect x="1063" y="130" width="14" height="10" rx="2" fill="#132035" stroke="#38bdf8" stroke-width="1"/><rect x="1088" y="130" width="14" height="10" rx="2" fill="#132035" stroke="#38bdf8" stroke-width="1"/><text class="tp-sublabel" x="1090" y="215" text-anchor="middle">Mains+Service</text><rect class="tp-label-bg" x="1035" y="102" width="110" height="16"/><text class="tp-label" x="1090" y="114" text-anchor="middle">Distribution</text><rect class="tp-drop-zone" data-id="dist" x="1033" y="100" width="114" height="104" rx="8"/></g>
  <rect x="10" y="255" width="200" height="55" rx="6" fill="#132035" stroke="#1e3a5f" opacity="0.9"/>
  <text x="22" y="272" fill="#94a3b8" font-size="9" font-weight="600" font-family="sans-serif">LEGEND</text>
  <line x1="22" y1="283" x2="55" y2="283" stroke="#1e40af" stroke-width="2" stroke-dasharray="8,4"/>
  <text x="60" y="287" fill="#94a3b8" font-size="8" font-family="sans-serif">Treatment Flow</text>
  <text x="22" y="302" fill="#fbbf24" font-size="8" font-style="italic" font-family="sans-serif">Al&#x2083; = coagulant added</text>
</svg>
</div>

<div class="tp-info" id="tpInfo" role="region" aria-label="Component information">
  <button class="tp-close" id="tpCloseBtn" aria-label="Close">&times;</button>
  <h2 id="tpIT"></h2>
  <div class="tp-info-type" id="tpITy"></div>
  <p id="tpID"></p>
  <div class="tp-tip" id="tpITip"></div>
</div>

<script>
(function(){
var C={
  source:{name:`Raw Water Source`,type:`Source Water`,desc:`The raw water source is the origin of all drinking water — rivers, lakes, reservoirs, or groundwater. Surface water requires more treatment due to turbidity, organic matter, and potential contamination. Watershed protection is the first barrier in the multiple-barrier approach to safe drinking water.`,tip:`<strong>Exam Tip:</strong> Surface water must be filtered and disinfected under the Surface Water Treatment Rule (SWTR). Turbidity of raw surface water: 1 to 1000+ NTU. Spring runoff increases turbidity and organics. Know surface water vs. groundwater treatment requirements.`},
  intake:{name:`Intake Structure`,type:`Source Water Collection`,desc:`The intake structure withdraws raw water from the source and includes bar screens, traveling screens, and low-lift pumps. Bar screens remove large debris. Traveling screens capture smaller particles. Low-lift pumps convey raw water to the plant.`,tip:`<strong>Exam Tip:</strong> Coarse bar screens: 1 to 3 inch openings. Fine screens: 3/8 to 3/4 inch. Difference between low-lift pumps (source to plant) and high-service pumps (clearwell to distribution). Screen velocity ~2 ft/s to avoid fish impingement.`},
  coag:{name:`Coagulation`,type:`Primary Treatment — Chemical`,desc:`Coagulation destabilizes suspended particles by neutralizing their negative charge. Common coagulants: alum (aluminum sulfate), ferric sulfate, and cationic polymers. Rapid mixing (30-60 seconds) disperses the coagulant. Poor coagulation compromises all downstream processes.`,tip:`<strong>Exam Tip:</strong> Alum dose typically 5-50 mg/L. Alum lowers pH — monitor closely. Optimal pH for alum: 6.5-7.5. Use the jar test to determine optimal dose and pH. Zeta potential measures particle charge. Turbidity and TOC are primary targets.`},
  floc:{name:`Flocculation`,type:`Primary Treatment — Physical`,desc:`Flocculation gently agitates coagulated water to promote particle collision and growth of settleable floc. Slow paddle mixers or baffled channels provide gentle mixing. Floc grows from microfloc to large visible floc. Over-mixing breaks fragile floc apart.`,tip:`<strong>Exam Tip:</strong> Detention time: 20-40 minutes. Velocity gradient (G): 10-75 per second. Gt value: 10,000-100,000. Too much mixing breaks floc. Tapered flocculation decreases mixing intensity as floc grows.`},
  sed:{name:`Sedimentation`,type:`Primary Treatment — Physical`,desc:`Sedimentation removes floc by gravity settling in large tanks. Clarified water overflows the top while sludge collects at the bottom. Tube settlers and lamella plates improve performance by providing more settling surface.`,tip:`<strong>Exam Tip:</strong> SOR: 500-1000 GPD/ft2. Detention time: 2-4 hours. Removes 80-90% TSS when preceded by good coagulation/flocculation. Alum sludge collects at the bottom. Weir loading rate less than 20,000 GPD/ft.`},
  filt:{name:`Filtration`,type:`Secondary Treatment — Physical`,desc:`Rapid sand filters remove remaining turbidity, floc, Giardia, and Cryptosporidium. Most plants use dual-media filters (anthracite over sand). Backwashing reverses flow to clean the media when headloss becomes excessive or effluent turbidity rises.`,tip:`<strong>Exam Tip:</strong> Post-filter turbidity must be 0.3 NTU or less in 95% of measurements, never exceed 1 NTU (SWTR). Filtration rate: 2-5 GPM/ft2. Backwash rate: 12-20 GPM/ft2. Cryptosporidium removal is primarily through filtration, not disinfection.`},
  disinf:{name:`Disinfection`,type:`Final Treatment — Chemical`,desc:`Disinfection inactivates pathogens before distribution. Chlorine gas or sodium hypochlorite is most common, using a serpentine contact basin for CT. Chloramines, ozone, and UV are alternatives. Residual chlorine must be maintained throughout the distribution system.`,tip:`<strong>Exam Tip:</strong> CT = concentration (mg/L) times time (min). Maximum residual disinfectant level for free chlorine: 4 mg/L. Minimum residual in distribution: 0.2 mg/L recommended. DBPs (TTHMs, HAA5) form when chlorine reacts with organics. TTHM limit: 80 ug/L. HAA5 limit: 60 ug/L.`},
  clearwell:{name:`Clearwell`,type:`Finished Water Storage`,desc:`The clearwell provides disinfection contact time (CT) and buffers between steady plant production and variable customer demand. Baffling efficiency (T10/T) affects CT credit. Well-baffled clearwells provide more CT credit for the same chlorine dose.`,tip:`<strong>Exam Tip:</strong> Baffling factor = T10 divided by theoretical detention time. Poorly baffled: 0.1. Highly baffled: 1.0. High-service pumps draw from the clearwell. Capacity: 2-4 hours of average daily demand. Maintain at least 0.2 mg/L chlorine residual.`},
  dist:{name:`Distribution System`,type:`Water Delivery`,desc:`The distribution system delivers finished water through transmission mains, distribution mains, service lines, storage tanks, and pressure zones. Pumping stations maintain pressure. Dead ends and low-flow areas are contamination risks. Cross-connections must be prevented.`,tip:`<strong>Exam Tip:</strong> Minimum pressure: 20 psi (35 psi recommended). Flushing maintains quality in dead ends. Cross-connection control is critical. Disinfectant residual must remain detectable throughout. Know the difference between transmission mains and distribution mains.`}
};

var mode='explore',qOrder=[],qIdx=0,qCorrect=0,qWrong=0,qActive=false;
var dragSel=null,dragPlaced=0,dragWrong=0,showingAnswers=false;
var wrap=document.getElementById('opp-tp-diagram');
var total=Object.keys(C).length;

function setMode(m){
  mode=m;
  var cls=wrap.className.split(' ').filter(function(c){return c.indexOf('mode-')!==0;});
  cls.push('mode-'+m); wrap.className=cls.join(' ');
  ['tpBtn0','tpBtn1','tpBtn2'].forEach(function(id,i){
    var btn=document.getElementById(id);
    var active=(i===(['explore','quiz','drag'].indexOf(m)));
    btn.classList.toggle('active',active);
    btn.setAttribute('aria-selected',active?'true':'false');
  });
  document.getElementById('tpInfo').classList.remove('visible');
  document.getElementById('tpComplete').classList.remove('visible');
  document.querySelectorAll('.tp-comp').forEach(function(c){c.classList.remove('selected','correct-flash','wrong-flash');});
  var isQ=(m==='quiz'),isD=(m==='drag');
  document.getElementById('tpQuizBanner').classList.toggle('visible',isQ);
  document.getElementById('tpDragBanner').classList.toggle('visible',isD);
  document.getElementById('tpDragPanel').classList.toggle('visible',isD);
  document.getElementById('tpDragControls').classList.toggle('visible',isD);
  var hideLbls=(isD||(isQ&&qActive));
  document.querySelectorAll('.tp-label,.tp-label-bg,.tp-sublabel').forEach(function(e){e.style.display=hideLbls?'none':'';});
  if(isQ&&!qActive){
    document.getElementById('tpQBQ').textContent='Ready to test your knowledge?';
    document.getElementById('tpQBCounter').textContent='';
    document.getElementById('tpProgressBar').style.display='none';
    document.getElementById('tpQBFB').textContent='';
    document.getElementById('tpQBFB').className='tp-qb-fb';
    document.getElementById('tpQBSc').textContent='';
    document.getElementById('tpStartBtn').style.display='';
    document.getElementById('tpQBIntro').style.display='';
  }
  if(isD) setupDrag();
}

function showInfo(id){
  var c=C[id]; if(!c)return;
  document.getElementById('tpIT').textContent=c.name;
  document.getElementById('tpITy').textContent=c.type;
  document.getElementById('tpID').textContent=c.desc;
  document.getElementById('tpITip').innerHTML=c.tip;
  document.getElementById('tpInfo').classList.add('visible');
}

function startQuiz(){
  qOrder=Object.keys(C).sort(function(){return Math.random()-.5;});
  qIdx=0; qCorrect=0; qWrong=0; qActive=true;
  document.querySelectorAll('.tp-label,.tp-label-bg,.tp-sublabel').forEach(function(e){e.style.display='none';});
  document.getElementById('tpStartBtn').style.display='none';
  document.getElementById('tpQBIntro').style.display='none';
  document.getElementById('tpProgressBar').style.display='block';
  document.getElementById('tpComplete').classList.remove('visible');
  nextQ();
}

function nextQ(){
  if(qIdx>=qOrder.length){endQuiz();return;}
  document.getElementById('tpQBQ').textContent='Click on: '+C[qOrder[qIdx]].name;
  document.getElementById('tpQBCounter').textContent='Question '+(qIdx+1)+' of '+qOrder.length;
  document.getElementById('tpProgressFill').style.width=Math.round((qIdx/qOrder.length)*100)+'%';
  document.getElementById('tpQBFB').textContent='';
  document.getElementById('tpQBSc').textContent='Correct: '+qCorrect+' \xb7 Wrong: '+qWrong;
}

function checkAnswer(id,el){
  if(!qActive||qIdx>=qOrder.length)return;
  var exp=qOrder[qIdx]; qIdx++;
  var fb=document.getElementById('tpQBFB');
  if(id===exp){
    qCorrect++;
    fb.textContent='Correct!'; fb.className='tp-qb-fb correct';
    el.classList.add('correct-flash'); setTimeout(function(){el.classList.remove('correct-flash');},900);
  } else {
    qWrong++;
    fb.textContent='That is '+C[id].name+'. Answer: '+C[exp].name;
    fb.className='tp-qb-fb wrong';
    el.classList.add('wrong-flash');
    var ce=document.querySelector('.tp-comp[data-id="'+exp+'"]');
    if(ce){ce.classList.add('correct-flash');setTimeout(function(){ce.classList.remove('correct-flash');},1400);}
    setTimeout(function(){el.classList.remove('wrong-flash');},900);
  }
  document.getElementById('tpQBSc').textContent='Correct: '+qCorrect+' \xb7 Wrong: '+qWrong;
  setTimeout(nextQ,1800);
}

function endQuiz(){
  qActive=false;
  document.getElementById('tpProgressFill').style.width='100%';
  document.getElementById('tpQuizBanner').classList.remove('visible');
  var pct=Math.round((qCorrect/qOrder.length)*100);
  document.getElementById('tpCompScore').textContent=qCorrect+' / '+qOrder.length+' ('+pct+'%)';
  var title,msg;
  if(pct===100){title='Perfect Score!';msg='Outstanding! You identified every treatment step correctly. You are ready for the T1/T2 exam!';}
  else if(pct>=70){title='Great Job!';msg='You got '+qCorrect+' of '+qOrder.length+' correct. Review the ones you missed in Explore mode.';}
  else{title='Keep Practicing';msg='You got '+qCorrect+' of '+qOrder.length+' correct. Use Explore mode to study each step, then try again.';}
  document.getElementById('tpCompTitle').textContent=title;
  document.getElementById('tpCompMsg').textContent=msg;
  document.getElementById('tpComplete').classList.add('visible');
  document.querySelectorAll('.tp-label,.tp-label-bg,.tp-sublabel').forEach(function(e){e.style.display='';});
}

function setupDrag(){
  dragSel=null; dragPlaced=0; dragWrong=0; showingAnswers=false;
  document.getElementById('tpShowAnsBtn').textContent='Show Answers';
  document.getElementById('tpShowAnsBtn').classList.remove('active');
  document.querySelectorAll('.tp-drop-zone').forEach(function(dz){dz.classList.remove('filled');});
  document.querySelectorAll('.tp-label,.tp-label-bg,.tp-sublabel').forEach(function(e){e.style.display='none';});
  var ids=Object.keys(C).sort(function(){return Math.random()-.5;});
  var panel=document.getElementById('tpDragPanel'); panel.innerHTML='';
  ids.forEach(function(id){
    var chip=document.createElement('div');
    chip.className='tp-chip'; chip.textContent=C[id].name;
    chip.setAttribute('data-id',id); chip.setAttribute('draggable','true');
    chip.setAttribute('tabindex','0'); chip.setAttribute('role','option');
    chip.addEventListener('dragstart',function(e){
      if(chip.classList.contains('done')){e.preventDefault();return;}
      e.dataTransfer.setData('text/plain',id); e.dataTransfer.effectAllowed='move';
      chip.classList.add('dragging'); dragSel=id;
    });
    chip.addEventListener('dragend',function(){chip.classList.remove('dragging');});
    chip.addEventListener('click',function(e){
      e.stopPropagation();
      if(chip.classList.contains('done'))return;
      document.querySelectorAll('.tp-chip').forEach(function(b){b.classList.remove('on');});
      chip.classList.add('on'); dragSel=id;
      document.getElementById('tpDragInst').textContent='Now click the matching component in the diagram:';
      document.getElementById('tpDragSel').textContent='> '+C[id].name;
    });
    chip.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();chip.click();}});
    panel.appendChild(chip);
  });
  document.getElementById('tpDCorr').textContent='0';
  document.getElementById('tpDWrong').textContent='0';
  document.getElementById('tpDRem').textContent=String(total);
  document.getElementById('tpDragFill').style.width='0%';
  document.getElementById('tpDragInst').textContent='Drag a label onto its matching component — or tap a label, then tap the component.';
  document.getElementById('tpDragSel').textContent='';
  document.getElementById('tpDragFB').textContent='';
}

function placeDrop(dragId,targetId,targetEl){
  var chip=document.querySelector('.tp-chip[data-id="'+dragId+'"]');
  if(dragId===targetId){
    dragPlaced++;
    if(chip){chip.classList.remove('on','dragging','wrong-chip');chip.classList.add('done');}
    var dz=document.querySelector('.tp-drop-zone[data-id="'+targetId+'"]');
    if(dz)dz.classList.add('filled');
    var cg=document.querySelector('.tp-comp[data-id="'+targetId+'"]');
    if(cg)cg.querySelectorAll('.tp-label,.tp-label-bg').forEach(function(e){e.style.display='';});
    targetEl.classList.add('correct-flash');setTimeout(function(){targetEl.classList.remove('correct-flash');},900);
    document.getElementById('tpDragFB').textContent='';
    document.getElementById('tpDCorr').textContent=String(dragPlaced);
    document.getElementById('tpDRem').textContent=String(total-dragPlaced);
    document.getElementById('tpDragFill').style.width=Math.round((dragPlaced/total)*100)+'%';
    if(dragPlaced>=total){document.getElementById('tpDragInst').textContent='All '+total+' components placed correctly!';}
    document.getElementById('tpDragSel').textContent='';
    dragSel=null;
  } else {
    dragWrong++;
    targetEl.classList.add('wrong-flash');setTimeout(function(){targetEl.classList.remove('wrong-flash');},700);
    if(chip){chip.classList.remove('on');chip.classList.add('wrong-chip');setTimeout(function(){chip.classList.remove('wrong-chip');},500);}
    document.getElementById('tpDragFB').textContent='That is the '+C[targetId].name+'. Hint: '+C[dragId].name+' is "'+C[dragId].type+'".';
    document.getElementById('tpDWrong').textContent=String(dragWrong);
    dragSel=null;
    document.querySelectorAll('.tp-chip').forEach(function(b){b.classList.remove('on');});
    document.getElementById('tpDragSel').textContent='';
  }
}

function toggleShowAnswers(){
  showingAnswers=!showingAnswers;
  var btn=document.getElementById('tpShowAnsBtn');
  btn.textContent=showingAnswers?'Hide Answers':'Show Answers';
  btn.classList.toggle('active',showingAnswers);
  document.querySelectorAll('.tp-comp').forEach(function(comp){
    var id=comp.getAttribute('data-id');
    if(!document.querySelector('.tp-drop-zone[data-id="'+id+'"].filled')){
      comp.querySelectorAll('.tp-label,.tp-label-bg').forEach(function(e){e.style.display=showingAnswers?'':'none';});
    }
  });
}

document.getElementById('tpBtn0').addEventListener('click',function(){setMode('explore');});
document.getElementById('tpBtn1').addEventListener('click',function(){setMode('quiz');});
document.getElementById('tpBtn2').addEventListener('click',function(){setMode('drag');});
document.getElementById('tpStartBtn').addEventListener('click',function(){startQuiz();});
document.getElementById('tpRetryBtn').addEventListener('click',function(){
  document.getElementById('tpComplete').classList.remove('visible');
  qActive=false; setMode('quiz'); setTimeout(function(){startQuiz();},100);
});
document.getElementById('tpExploreBtn').addEventListener('click',function(){setMode('explore');});
document.getElementById('tpCloseBtn').addEventListener('click',function(){
  document.getElementById('tpInfo').classList.remove('visible');
  document.querySelectorAll('.tp-comp').forEach(function(c){c.classList.remove('selected');});
});
document.getElementById('tpResetBtn').addEventListener('click',function(){setupDrag();});
document.getElementById('tpShowAnsBtn').addEventListener('click',function(){toggleShowAnswers();});
document.getElementById('tpHowtoBtn').addEventListener('click',function(){
  var box=document.getElementById('tpHowtoBox');
  var open=box.classList.toggle('open');
  document.getElementById('tpHowtoBtn').setAttribute('aria-expanded',open?'true':'false');
});

var svgWrap=document.querySelector('.tp-svg-wrap');
function findComp(target,root){
  var el=target;
  while(el&&el!==root){if(el.classList&&el.classList.contains('tp-comp'))return el;el=el.parentElement;}
  return null;
}
svgWrap.addEventListener('click',function(e){
  var comp=findComp(e.target,this); if(!comp)return;
  var id=comp.getAttribute('data-id');
  if(mode==='explore'){
    showInfo(id);
    document.querySelectorAll('.tp-comp').forEach(function(c){c.classList.remove('selected');});
    comp.classList.add('selected');
  } else if(mode==='quiz'&&qActive){checkAnswer(id,comp);}
  else if(mode==='drag'&&dragSel){placeDrop(dragSel,id,comp);}
});
svgWrap.addEventListener('dragover',function(e){
  var comp=findComp(e.target,this);
  if(comp&&!comp.querySelector('.tp-drop-zone.filled')){
    e.preventDefault(); e.dataTransfer.dropEffect='move';
    document.querySelectorAll('.tp-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
    comp.classList.add('drag-over');
  }
});
svgWrap.addEventListener('dragleave',function(e){
  if(!svgWrap.contains(e.relatedTarget))
    document.querySelectorAll('.tp-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
});
svgWrap.addEventListener('drop',function(e){
  e.preventDefault();
  document.querySelectorAll('.tp-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
  var comp=findComp(e.target,this); if(!comp)return;
  var dragId=e.dataTransfer.getData('text/plain');
  if(dragId)placeDrop(dragId,comp.getAttribute('data-id'),comp);
});
})();
</script>
</div>
<!-- /wp:html -->
ENDT1;

wp_update_post(array('ID' => $pid, 'post_content' => $content));
echo "UPDATED: T1/T2 water treatment plant diagram v3 (ID $pid)" . PHP_EOL;
wp_cache_flush();
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
