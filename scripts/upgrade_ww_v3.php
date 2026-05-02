<?php
/**
 * WW1/WW2 Wastewater Diagram — v3 UX upgrade (standalone)
 * Page ID 1093 (/ww1-ww2-wastewater-diagram/)
 * Run: wp eval-file scripts/upgrade_ww_v3.php --allow-root
 */
echo "Upgrading WW1/WW2 wastewater diagram to v3..." . PHP_EOL;

$pid = 1093;
if (!get_post($pid)) { echo "WW NOT FOUND (ID $pid)" . PHP_EOL; exit; }

$content = <<<'ENDWW'
<!-- wp:html -->
<style>.entry-header.ast-no-thumbnail{display:none!important}</style>
<div id="opp-ww-diagram">
<style>
#opp-ww-diagram{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#0f172a;color:#e2e8f0;max-width:1200px;margin:0 auto;padding:20px;box-sizing:border-box}
#opp-ww-diagram *,#opp-ww-diagram *::before,#opp-ww-diagram *::after{box-sizing:border-box}
.ww-header{text-align:center;margin-bottom:16px}
.ww-header h1{font-size:1.6em;color:#f1f5f9;margin:0 0 4px;font-weight:700}
.ww-header p{color:#94a3b8;font-size:.9em;margin:0}
.ww-howto-btn{background:none;border:none;color:#60a5fa;font-size:.82em;cursor:pointer;text-decoration:underline;margin-top:6px;padding:0}
.ww-howto-box{display:none;background:#1e293b;border:1px solid #334155;border-radius:8px;padding:12px 16px;margin:8px auto;font-size:.82em;color:#94a3b8;text-align:left;max-width:640px}
.ww-howto-box.open{display:block}
.ww-howto-box ul{margin:4px 0 0;padding-left:18px}
.ww-howto-box li{margin-bottom:4px}
.ww-modes{display:flex;justify-content:center;gap:10px;margin-bottom:14px;flex-wrap:wrap;align-items:flex-start}
.ww-mode-wrap{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:130px}
.ww-mode-btn{background:#1e293b;color:#94a3b8;border:1.5px solid #334155;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}
.ww-mode-btn:hover{background:#293548;color:#e2e8f0;border-color:#475569}
.ww-mode-btn.active{background:#2563eb;color:#fff;border-color:#2563eb;box-shadow:0 2px 8px rgba(37,99,235,.35)}
.ww-mode-desc{font-size:.73em;color:#64748b;text-align:center;line-height:1.3}
.ww-quiz-banner{display:none;background:linear-gradient(135deg,#1e3a8a,#1d4ed8);color:#fff;border-radius:10px;padding:16px 20px;margin-bottom:10px;text-align:center}
.ww-quiz-banner.visible{display:block;animation:wwFade .25s ease}
.ww-qb-intro{font-size:.87em;color:rgba(255,255,255,.8);margin-bottom:4px}
.ww-qb-q{font-size:1.05em;font-weight:700;margin-bottom:4px;min-height:1.4em}
.ww-qb-counter{font-size:.78em;color:rgba(255,255,255,.7);margin-bottom:8px;min-height:1em}
.ww-progress-bar{background:rgba(255,255,255,.2);border-radius:4px;height:6px;margin:0 auto 10px;max-width:300px;overflow:hidden}
.ww-progress-fill{background:#86efac;height:6px;border-radius:4px;width:0;transition:width .4s ease}
.ww-qb-row{display:flex;justify-content:center;align-items:center;gap:16px;flex-wrap:wrap}
.ww-qb-fb{font-size:.92em;font-weight:600;min-height:1.5em}
.ww-qb-fb.correct{color:#86efac}
.ww-qb-fb.wrong{color:#fca5a5}
.ww-qb-score{font-size:.82em;color:rgba(255,255,255,.75)}
.ww-quiz-start{background:rgba(255,255,255,.18);color:#fff;border:1.5px solid rgba(255,255,255,.35);padding:8px 24px;border-radius:7px;font-size:.92em;font-weight:700;cursor:pointer;transition:all .18s;margin-top:8px}
.ww-quiz-start:hover{background:rgba(255,255,255,.28)}
.ww-complete{display:none;background:#1a2e1a;border:2px solid #4ade80;border-radius:12px;padding:22px;text-align:center;margin-bottom:10px}
.ww-complete.visible{display:block;animation:wwFade .3s ease}
.ww-complete h3{color:#4ade80;font-size:1.35em;margin:0 0 8px}
.ww-final-score{font-size:2em;font-weight:800;color:#4ade80;margin:6px 0}
.ww-complete p{color:#86efac;font-size:.9em;margin:0 0 12px}
.ww-complete-btns{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.ww-complete-btns button{padding:8px 18px;border-radius:8px;font-size:.88em;font-weight:600;cursor:pointer;border:none;transition:all .18s}
.ww-btn-retry{background:#2563eb;color:#fff}
.ww-btn-retry:hover{background:#1d4ed8}
.ww-btn-explore{background:#1e293b;color:#e2e8f0;border:1.5px solid #334155!important}
.ww-btn-explore:hover{background:#293548}
.ww-drag-banner{display:none;background:#1e293b;border:1.5px solid #334155;border-radius:10px;padding:12px 18px;margin-bottom:8px;text-align:center;color:#94a3b8;font-size:.87em}
.ww-drag-banner.visible{display:block;animation:wwFade .25s ease}
.ww-db-sel{font-weight:700;color:#60a5fa;font-size:.92em;margin:4px 0}
.ww-drag-score-row{display:flex;justify-content:center;align-items:center;gap:12px;flex-wrap:wrap;margin-top:6px;font-size:.83em;color:#64748b}
.ww-sc-correct{color:#4ade80;font-weight:700}
.ww-sc-wrong{color:#f87171;font-weight:700}
.ww-drag-progress{background:#334155;border-radius:4px;height:6px;max-width:300px;width:100%;margin:6px auto 0;overflow:hidden}
.ww-drag-pfill{background:#4ade80;height:6px;border-radius:4px;width:0;transition:width .4s ease}
.ww-drag-fb{font-size:.84em;color:#fca5a5;font-weight:600;min-height:1.4em;margin-top:4px}
.ww-drag-controls{display:none;justify-content:center;gap:8px;margin-bottom:8px;flex-wrap:wrap}
.ww-drag-controls.visible{display:flex}
.ww-ctrl-btn{background:#1e293b;color:#64748b;border:1.5px solid #334155;padding:5px 14px;border-radius:7px;font-size:.78em;font-weight:600;cursor:pointer;transition:all .18s}
.ww-ctrl-btn:hover{background:#293548;color:#e2e8f0}
.ww-ctrl-btn.active{background:#2563eb;color:#fff;border-color:#2563eb}
.ww-drag-panel{display:none;flex-wrap:wrap;gap:7px;justify-content:center;padding:14px;background:#1e293b;border:1.5px solid #334155;border-radius:10px;margin-bottom:10px}
.ww-drag-panel.visible{display:flex}
.ww-chip{background:#0f172a;color:#cbd5e1;padding:6px 13px;border-radius:7px;cursor:grab;font-size:.82em;font-weight:500;border:1.5px solid #334155;user-select:none;transition:all .18s}
.ww-chip:hover{border-color:#475569;background:#1a2535}
.ww-chip.on{background:#2563eb;color:#fff;border-color:#2563eb;cursor:grabbing}
.ww-chip.dragging{opacity:.4;cursor:grabbing}
.ww-chip.done{background:#1a2e1a;color:#4ade80;border-color:#4ade80;opacity:.7;text-decoration:line-through;cursor:default}
.ww-chip.wrong-chip{animation:wwShake .4s ease;border-color:#f87171}
.ww-svg-wrap{background:#1e293b;border:1.5px solid #334155;border-radius:12px;overflow:hidden;margin-bottom:10px}
.ww-svg-wrap svg{display:block;width:100%;height:auto}
.ww-comp{cursor:pointer}
.ww-comp:hover rect:first-child,.ww-comp:hover ellipse:first-child{filter:brightness(1.3)}
.ww-comp.selected rect:first-child{stroke-width:3}
.ww-comp.correct-flash rect:first-child{stroke:#4ade80!important;stroke-width:3}
.ww-comp.wrong-flash rect:first-child{stroke:#f87171!important;stroke-width:3}
.ww-comp.drag-over rect:first-child{stroke:#f97316!important;stroke-width:3}
.ww-flow-arrow{fill:none;stroke:#6b7280;stroke-width:2;stroke-dasharray:8,4;opacity:.8}
.ww-sludge-arrow{fill:none;stroke:#a16207;stroke-width:2;stroke-dasharray:6,3;opacity:.8}
.ww-label{font-family:'Segoe UI',system-ui,sans-serif;fill:#e2e8f0;font-size:11px;font-weight:600;pointer-events:none}
.ww-label-bg{fill:#1e293b;stroke:#334155;stroke-width:1;pointer-events:none}
.ww-sublabel{font-family:'Segoe UI',system-ui,sans-serif;fill:#64748b;font-size:8.5px;pointer-events:none}
.ww-chem-label{font-family:'Segoe UI',system-ui,sans-serif;fill:#fbbf24;font-size:9px;font-style:italic;pointer-events:none}
.ww-drop-zone{fill:transparent;stroke:#f59e0b;stroke-width:2;stroke-dasharray:5,3;opacity:0;pointer-events:none}
#opp-ww-diagram.mode-drag .ww-drop-zone{opacity:1;animation:wwPulse 1.8s infinite;pointer-events:all;cursor:pointer}
#opp-ww-diagram.mode-drag .ww-drop-zone.filled{stroke:#4ade80;opacity:.6;stroke-dasharray:none;animation:none}
@keyframes wwPulse{0%,100%{opacity:.35}50%{opacity:.9}}
.ww-info{background:#1e293b;border:1.5px solid #334155;border-radius:12px;padding:18px 22px;margin-top:10px;display:none;position:relative}
.ww-info.visible{display:block;animation:wwFade .25s ease}
.ww-info h2{color:#60a5fa;font-size:1.15em;margin:0 0 3px}
.ww-info-type{color:#d97706;font-size:.78em;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px}
.ww-info p{color:#cbd5e1;line-height:1.65;margin:0 0 10px;font-size:.92em}
.ww-info .ww-tip{background:#1a1400;border-left:3px solid #f59e0b;padding:9px 14px;border-radius:0 8px 8px 0;margin-top:8px;font-size:.88em;color:#d6d3d1}
.ww-info .ww-tip strong{color:#fbbf24}
.ww-close{position:absolute;top:12px;right:14px;background:#0f172a;border:none;color:#64748b;font-size:1.1em;cursor:pointer;padding:4px 8px;border-radius:6px}
.ww-close:hover{background:#1e293b;color:#e2e8f0}
@keyframes wwFade{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}
@keyframes wwShake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
@media(max-width:768px){#opp-ww-diagram{padding:10px}.ww-header h1{font-size:1.25em}.ww-mode-btn{padding:6px 10px;font-size:.8em}.ww-mode-wrap{min-width:90px}.ww-label{font-size:8px}}
</style>

<div class="ww-header">
  <h1>Wastewater Treatment Plant &mdash; Interactive Diagram</h1>
  <p>WW1 &amp; WW2 &mdash; Conventional Activated Sludge Process</p>
  <button class="ww-howto-btn" id="wwHowtoBtn" aria-expanded="false">&#x2139;&#xfe0f; How to use</button>
  <div class="ww-howto-box" id="wwHowtoBox">
    <ul>
      <li><strong>Explore:</strong> Click any component to see its function and exam tips.</li>
      <li><strong>Quiz Mode:</strong> Click Start Quiz then click the component named in the prompt.</li>
      <li><strong>Drag &amp; Drop:</strong> Drag label chips onto the matching component, or tap a chip then tap the component.</li>
    </ul>
  </div>
</div>

<div class="ww-modes" role="tablist">
  <div class="ww-mode-wrap">
    <button class="ww-mode-btn active" id="wwBtn0" role="tab" aria-selected="true" aria-label="Explore mode">&#x1F50D; Explore</button>
    <span class="ww-mode-desc">Click components for details</span>
  </div>
  <div class="ww-mode-wrap">
    <button class="ww-mode-btn" id="wwBtn1" role="tab" aria-selected="false" aria-label="Quiz mode">&#x1F4DD; Quiz Mode</button>
    <span class="ww-mode-desc">Identify components by icon</span>
  </div>
  <div class="ww-mode-wrap">
    <button class="ww-mode-btn" id="wwBtn2" role="tab" aria-selected="false" aria-label="Drag and drop mode">&#x1F3AF; Drag &amp; Drop</button>
    <span class="ww-mode-desc">Match labels to components</span>
  </div>
</div>

<div class="ww-quiz-banner" id="wwQuizBanner" role="status" aria-live="polite">
  <div class="ww-qb-intro" id="wwQBIntro">Click each component when prompted &mdash; test your knowledge of the treatment train.</div>
  <div class="ww-qb-q" id="wwQBQ">Ready to test your knowledge?</div>
  <div class="ww-qb-counter" id="wwQBCounter"></div>
  <div class="ww-progress-bar" id="wwProgressBar" style="display:none"><div class="ww-progress-fill" id="wwProgressFill"></div></div>
  <div class="ww-qb-row">
    <div class="ww-qb-fb" id="wwQBFB"></div>
    <div class="ww-qb-score" id="wwQBSc"></div>
  </div>
  <button class="ww-quiz-start" id="wwStartBtn">Start Quiz</button>
</div>

<div class="ww-complete" id="wwComplete" role="alert">
  <h3 id="wwCompTitle">Quiz Complete!</h3>
  <div class="ww-final-score" id="wwCompScore"></div>
  <p id="wwCompMsg"></p>
  <div class="ww-complete-btns">
    <button class="ww-btn-retry" id="wwRetryBtn">&#x1F501; Retry Quiz</button>
    <button class="ww-btn-explore" id="wwExploreBtn">&#x1F50D; Review in Explore Mode</button>
  </div>
</div>

<div class="ww-drag-banner" id="wwDragBanner" role="status" aria-live="polite">
  <div id="wwDragInst">Drag a label onto its matching component &mdash; or tap a label, then tap the component.</div>
  <div class="ww-db-sel" id="wwDragSel"></div>
  <div class="ww-drag-score-row">
    <span>Correct: <span class="ww-sc-correct" id="wwDCorr">0</span></span>
    <span>Wrong: <span class="ww-sc-wrong" id="wwDWrong">0</span></span>
    <span>Remaining: <span id="wwDRem">12</span></span>
  </div>
  <div class="ww-drag-progress"><div class="ww-drag-pfill" id="wwDragFill"></div></div>
  <div class="ww-drag-fb" id="wwDragFB"></div>
</div>

<div class="ww-drag-controls" id="wwDragControls">
  <button class="ww-ctrl-btn" id="wwResetBtn">&#x21BA; Reset</button>
  <button class="ww-ctrl-btn" id="wwShowAnsBtn">&#x1F441; Show Answers</button>
</div>

<div class="ww-drag-panel" id="wwDragPanel" aria-label="Label chips"></div>

<div class="ww-svg-wrap">
<svg viewBox="0 0 1200 580" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Wastewater Treatment Plant diagram">
  <defs>
    <marker id="wwArrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#6b7280"/></marker>
    <marker id="wwSludgeArrowM" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#a16207"/></marker>
  </defs>
  <path d="M 130,200 L 195,200" class="ww-flow-arrow" marker-end="url(#wwArrow)"/>
  <path d="M 335,200 L 400,200" class="ww-flow-arrow" marker-end="url(#wwArrow)"/>
  <path d="M 540,200 L 600,200" class="ww-flow-arrow" marker-end="url(#wwArrow)"/>
  <path d="M 740,200 L 800,200" class="ww-flow-arrow" marker-end="url(#wwArrow)"/>
  <path d="M 940,200 L 1000,200" class="ww-flow-arrow" marker-end="url(#wwArrow)"/>
  <path d="M 1100,200 L 1150,200" class="ww-flow-arrow" marker-end="url(#wwArrow)"/>
  <path d="M 470,280 L 470,380 L 400,380" class="ww-sludge-arrow" marker-end="url(#wwSludgeArrowM)"/>
  <text class="ww-chem-label" x="478" y="335">Primary Sludge</text>
  <path d="M 870,280 L 870,380" class="ww-sludge-arrow" marker-end="url(#wwSludgeArrowM)"/>
  <path d="M 870,420 L 870,460 L 670,460 L 670,280" class="ww-sludge-arrow" marker-end="url(#wwSludgeArrowM)"/>
  <text class="ww-chem-label" x="750" y="455">RAS (Return)</text>
  <path d="M 870,460 L 540,460 L 540,500 L 400,500" class="ww-sludge-arrow" marker-end="url(#wwSludgeArrowM)"/>
  <text class="ww-chem-label" x="570" y="495">WAS (Waste)</text>
  <g class="ww-comp" data-id="collection" role="button" aria-label="Collection System"><rect x="30" y="155" width="100" height="90" rx="8" fill="#1e293b" stroke="#6b7280" stroke-width="2"/><rect x="35" y="180" width="90" height="20" rx="4" fill="#374151"/><line x1="50" y1="190" x2="115" y2="190" stroke="#9ca3af" stroke-width="2" stroke-dasharray="6,3"/><circle cx="60" cy="190" r="3" fill="#a16207" opacity="0.7"/><circle cx="80" cy="188" r="2" fill="#a16207" opacity="0.5"/><circle cx="100" cy="191" r="2.5" fill="#a16207" opacity="0.6"/><text class="ww-sublabel" x="80" y="220" text-anchor="middle">Influent</text><rect class="ww-label-bg" x="30" y="137" width="100" height="18"/><text class="ww-label" x="80" y="150" text-anchor="middle">Collection System</text><rect class="ww-drop-zone" data-id="collection" x="28" y="135" width="104" height="114" rx="8"/></g>
  <g class="ww-comp" data-id="headworks" role="button" aria-label="Headworks"><rect x="195" y="155" width="140" height="90" rx="8" fill="#1e293b" stroke="#78716c" stroke-width="2"/><rect x="205" y="170" width="45" height="60" rx="4" fill="#292524"/><line x1="215" y1="175" x2="215" y2="225" stroke="#78716c" stroke-width="2"/><line x1="225" y1="175" x2="225" y2="225" stroke="#78716c" stroke-width="2"/><line x1="235" y1="175" x2="235" y2="225" stroke="#78716c" stroke-width="2"/><rect x="260" y="170" width="65" height="60" rx="4" fill="#292524"/><ellipse cx="292" cy="220" rx="25" ry="6" fill="#78350f" opacity="0.5"/><text class="ww-sublabel" x="220" y="244" text-anchor="middle">Screens</text><text class="ww-sublabel" x="292" y="244" text-anchor="middle">Grit</text><rect class="ww-label-bg" x="210" y="137" width="110" height="18"/><text class="ww-label" x="265" y="150" text-anchor="middle">Headworks</text><rect class="ww-drop-zone" data-id="headworks" x="193" y="135" width="144" height="114" rx="8"/></g>
  <g class="ww-comp" data-id="primary_clar" role="button" aria-label="Primary Clarifier"><rect x="400" y="155" width="140" height="120" rx="8" fill="#1e293b" stroke="#92400e" stroke-width="2"/><path d="M 415,175 L 525,175 L 515,260 L 425,260 Z" fill="#1c1917" stroke="#78350f" stroke-width="1"/><circle cx="440" cy="210" r="3" fill="#a16207" opacity="0.4"/><circle cx="470" cy="225" r="4" fill="#a16207" opacity="0.5"/><circle cx="500" cy="215" r="2.5" fill="#a16207" opacity="0.3"/><circle cx="455" cy="240" r="3.5" fill="#a16207" opacity="0.6"/><circle cx="490" cy="245" r="3" fill="#a16207" opacity="0.5"/><path d="M 425,250 Q 470,258 515,250" fill="#78350f" opacity="0.6" stroke="none"/><line x1="470" y1="175" x2="470" y2="255" stroke="#64748b" stroke-width="1.5"/><line x1="450" y1="180" x2="490" y2="180" stroke="#64748b" stroke-width="1.5"/><rect class="ww-label-bg" x="403" y="137" width="134" height="18"/><text class="ww-label" x="470" y="150" text-anchor="middle">Primary Clarifier</text><rect class="ww-drop-zone" data-id="primary_clar" x="398" y="135" width="144" height="144" rx="8"/></g>
  <g class="ww-comp" data-id="aeration" role="button" aria-label="Aeration Basin"><rect x="600" y="155" width="140" height="120" rx="8" fill="#1e293b" stroke="#2563eb" stroke-width="2"/><rect x="615" y="175" width="110" height="85" rx="4" fill="#172554"/><circle cx="635" cy="230" r="3" fill="#60a5fa" opacity="0.4"/><circle cx="645" cy="220" r="2" fill="#60a5fa" opacity="0.3"/><circle cx="665" cy="235" r="3.5" fill="#60a5fa" opacity="0.4"/><circle cx="670" cy="220" r="2" fill="#60a5fa" opacity="0.3"/><circle cx="695" cy="228" r="3" fill="#60a5fa" opacity="0.4"/><circle cx="700" cy="215" r="2.5" fill="#60a5fa" opacity="0.3"/><rect x="625" y="248" width="15" height="5" rx="2" fill="#3b82f6" opacity="0.6"/><rect x="655" y="248" width="15" height="5" rx="2" fill="#3b82f6" opacity="0.6"/><rect x="685" y="248" width="15" height="5" rx="2" fill="#3b82f6" opacity="0.6"/><text class="ww-chem-label" x="670" y="190" text-anchor="middle">O&#x2082; &#x2191;</text><text class="ww-sublabel" x="670" y="277" text-anchor="middle">Activated Sludge</text><rect class="ww-label-bg" x="607" y="137" width="126" height="18"/><text class="ww-label" x="670" y="150" text-anchor="middle">Aeration Basin</text><rect class="ww-drop-zone" data-id="aeration" x="598" y="135" width="144" height="144" rx="8"/></g>
  <g class="ww-comp" data-id="secondary_clar" role="button" aria-label="Secondary Clarifier"><rect x="800" y="155" width="140" height="120" rx="8" fill="#1e293b" stroke="#065f46" stroke-width="2"/><ellipse cx="870" cy="215" rx="55" ry="45" fill="#1c1917" stroke="#064e3b" stroke-width="1"/><circle cx="870" cy="215" r="12" fill="#1e293b" stroke="#065f46" stroke-width="1"/><ellipse cx="870" cy="245" rx="45" ry="8" fill="#78350f" opacity="0.4"/><line x1="870" y1="215" x2="915" y2="240" stroke="#64748b" stroke-width="1.5"/><text class="ww-sublabel" x="870" y="195" text-anchor="middle" fill="#34d399">Clear</text><rect class="ww-label-bg" x="798" y="137" width="144" height="18"/><text class="ww-label" x="870" y="150" text-anchor="middle">Secondary Clarifier</text><rect class="ww-drop-zone" data-id="secondary_clar" x="798" y="135" width="144" height="144" rx="8"/></g>
  <g class="ww-comp" data-id="disinfection" role="button" aria-label="Disinfection"><rect x="1000" y="155" width="100" height="90" rx="8" fill="#1e293b" stroke="#eab308" stroke-width="2"/><path d="M 1015,175 L 1080,175 L 1080,195 L 1015,195 L 1015,215 L 1080,215 L 1080,235" fill="none" stroke="#eab308" stroke-width="2" opacity="0.6"/><text x="1050" y="188" text-anchor="middle" fill="#fbbf24" font-size="11" font-weight="bold" font-family="sans-serif">Cl&#x2082;</text><rect class="ww-label-bg" x="1003" y="137" width="94" height="18"/><text class="ww-label" x="1050" y="150" text-anchor="middle">Disinfection</text><rect class="ww-drop-zone" data-id="disinfection" x="998" y="135" width="104" height="114" rx="8"/></g>
  <g class="ww-comp" data-id="effluent" role="button" aria-label="Effluent Discharge"><rect x="1120" y="160" width="70" height="80" rx="8" fill="#1e293b" stroke="#38bdf8" stroke-width="2"/><path d="M 1130,200 Q 1145,190 1160,200 Q 1175,210 1180,200" fill="none" stroke="#38bdf8" stroke-width="2"/><path d="M 1130,215 Q 1145,205 1160,215 Q 1175,225 1180,215" fill="none" stroke="#38bdf8" stroke-width="1.5" opacity="0.6"/><text class="ww-sublabel" x="1155" y="178" text-anchor="middle">Effluent</text><rect class="ww-label-bg" x="1118" y="142" width="74" height="18"/><text class="ww-label" x="1155" y="155" text-anchor="middle">Discharge</text><rect class="ww-drop-zone" data-id="effluent" x="1116" y="140" width="78" height="104" rx="8"/></g>
  <rect x="20" y="340" width="500" height="220" rx="10" fill="#0a0f1a" stroke="#334155" stroke-width="1" opacity="0.5"/>
  <text x="40" y="365" fill="#475569" font-size="11" font-weight="600" font-family="sans-serif">SOLIDS HANDLING</text>
  <g class="ww-comp" data-id="thickener" role="button" aria-label="Sludge Thickener"><rect x="280" y="370" width="110" height="80" rx="8" fill="#1e293b" stroke="#a16207" stroke-width="2"/><ellipse cx="335" cy="405" rx="40" ry="25" fill="#1c1917" stroke="#78350f" stroke-width="1"/><ellipse cx="335" cy="420" rx="30" ry="8" fill="#78350f" opacity="0.5"/><text class="ww-sublabel" x="335" y="395" text-anchor="middle">Gravity</text><rect class="ww-label-bg" x="290" y="352" width="90" height="18"/><text class="ww-label" x="335" y="365" text-anchor="middle">Thickener</text><rect class="ww-drop-zone" data-id="thickener" x="278" y="350" width="114" height="104" rx="8"/></g>
  <g class="ww-comp" data-id="digester" role="button" aria-label="Anaerobic Digester"><rect x="90" y="370" width="130" height="100" rx="8" fill="#1e293b" stroke="#dc2626" stroke-width="2"/><ellipse cx="155" cy="420" rx="45" ry="35" fill="#1c1917" stroke="#7f1d1d" stroke-width="1.5"/><path d="M 140,405 Q 145,395 150,405 Q 155,415 160,405" stroke="#ef4444" stroke-width="1.5" fill="none"/><circle cx="165" cy="395" r="4" fill="none" stroke="#fbbf24" stroke-width="1"/><text class="ww-sublabel" x="175" y="398" fill="#fbbf24">CH&#x2084;</text><text class="ww-sublabel" x="155" y="465" text-anchor="middle">95&#xB0;F / 35&#xB0;C</text><rect class="ww-label-bg" x="90" y="352" width="130" height="18"/><text class="ww-label" x="155" y="365" text-anchor="middle">Anaerobic Digester</text><rect class="ww-drop-zone" data-id="digester" x="88" y="350" width="134" height="124" rx="8"/></g>
  <path d="M 280,410 L 225,410" class="ww-sludge-arrow" marker-end="url(#wwSludgeArrowM)"/>
  <g class="ww-comp" data-id="biosolids" role="button" aria-label="Dewatering and Biosolids"><rect x="90" y="490" width="130" height="60" rx="8" fill="#1e293b" stroke="#854d0e" stroke-width="2"/><rect x="105" y="505" width="100" height="30" rx="4" fill="#292524"/><line x1="115" y1="520" x2="195" y2="520" stroke="#a16207" stroke-width="3"/><circle cx="125" cy="520" r="8" fill="none" stroke="#78716c" stroke-width="1.5"/><circle cx="185" cy="520" r="8" fill="none" stroke="#78716c" stroke-width="1.5"/><rect class="ww-label-bg" x="87" y="472" width="136" height="18"/><text class="ww-label" x="155" y="485" text-anchor="middle">Dewatering / Biosolids</text><rect class="ww-drop-zone" data-id="biosolids" x="88" y="470" width="134" height="84" rx="8"/></g>
  <path d="M 155,470 L 155,490" class="ww-sludge-arrow" marker-end="url(#wwSludgeArrowM)"/>
  <g class="ww-comp" data-id="ras_was" role="button" aria-label="RAS WAS Sludge Pumps"><rect x="820" y="380" width="100" height="70" rx="8" fill="#1e293b" stroke="#7c3aed" stroke-width="2"/><circle cx="870" cy="410" r="18" fill="#172554" stroke="#7c3aed" stroke-width="1.5"/><path d="M 860,410 L 870,400 L 880,410" fill="none" stroke="#a78bfa" stroke-width="2"/><text class="ww-sublabel" x="870" y="440" text-anchor="middle">RAS / WAS</text><rect class="ww-label-bg" x="818" y="362" width="104" height="18"/><text class="ww-label" x="870" y="375" text-anchor="middle">Sludge Pumps</text><rect class="ww-drop-zone" data-id="ras_was" x="818" y="360" width="104" height="94" rx="8"/></g>
  <g class="ww-comp" data-id="lab" role="button" aria-label="Laboratory and Process Monitoring"><rect x="1000" y="370" width="100" height="70" rx="8" fill="#1e293b" stroke="#22d3ee" stroke-width="2"/><rect x="1030" y="388" width="20" height="30" rx="2" fill="none" stroke="#22d3ee" stroke-width="1.5"/><rect x="1032" y="405" width="16" height="10" rx="1" fill="#22d3ee" opacity="0.3"/><rect x="1060" y="385" width="10" height="35" rx="5" fill="none" stroke="#22d3ee" stroke-width="1.5"/><rect x="1062" y="405" width="6" height="12" rx="3" fill="#22d3ee" opacity="0.3"/><rect class="ww-label-bg" x="1000" y="352" width="100" height="18"/><text class="ww-label" x="1050" y="365" text-anchor="middle">Lab / Monitoring</text><rect class="ww-drop-zone" data-id="lab" x="998" y="350" width="104" height="94" rx="8"/></g>
  <rect x="560" y="460" width="240" height="70" rx="8" fill="#1e293b" stroke="#334155" opacity="0.8"/>
  <text x="575" y="480" fill="#94a3b8" font-size="10" font-weight="600" font-family="sans-serif">LEGEND</text>
  <line x1="575" y1="495" x2="600" y2="495" stroke="#6b7280" stroke-width="2" stroke-dasharray="8,4"/>
  <text x="607" y="499" fill="#94a3b8" font-size="9" font-family="sans-serif">Liquid Flow</text>
  <line x1="690" y1="495" x2="715" y2="495" stroke="#a16207" stroke-width="2" stroke-dasharray="6,3"/>
  <text x="722" y="499" fill="#94a3b8" font-size="9" font-family="sans-serif">Sludge Flow</text>
</svg>
</div>

<div class="ww-info" id="wwInfo" role="region" aria-label="Component information">
  <button class="ww-close" id="wwCloseBtn" aria-label="Close">&times;</button>
  <h2 id="wwIT"></h2>
  <div class="ww-info-type" id="wwITy"></div>
  <p id="wwID"></p>
  <div class="ww-tip" id="wwITip"></div>
</div>

<script>
(function(){
var C={
  collection:{name:`Collection System`,type:`Influent / Conveyance`,desc:`The collection system is the network of sewer pipes, manholes, and lift stations that conveys wastewater from homes and businesses to the treatment plant. Gravity sewers are most common, with lift (pump) stations used where gravity flow is not possible. Infiltration and inflow are major operational concerns.`,tip:`<strong>Exam Tip:</strong> Know the difference between infiltration (groundwater seeping through pipe defects) and inflow (surface water entering through manholes). CSOs occur when combined sewers exceed capacity during storms. Typical residential wastewater flow: 100 gallons per person per day.`},
  headworks:{name:`Headworks`,type:`Preliminary Treatment`,desc:`Headworks is the first treatment step, removing large debris and heavy inorganic solids. Bar screens catch rags, sticks, plastics, and other large objects. Grit chambers remove sand, gravel, and other heavy particles that could damage downstream equipment.`,tip:`<strong>Exam Tip:</strong> Bar screen openings: coarse screens 1-3 inches, fine screens 0.25-0.75 inches. Grit chambers settle particles with specific gravity greater than 2.65. Velocity in grit channels: approximately 1 ft/s.`},
  primary_clar:{name:`Primary Clarifier`,type:`Primary Treatment`,desc:`Primary clarifiers remove 50-65% of suspended solids and 25-40% of BOD by gravity settling. Wastewater flows slowly through the tank, allowing heavy particles to settle to the bottom as primary sludge while grease and scum float to the surface and are skimmed off.`,tip:`<strong>Exam Tip:</strong> Detention time 1.5-2.5 hours, surface overflow rate 600-1200 GPD/ft2. Primary sludge is 3-6% solids.`},
  aeration:{name:`Aeration Basin`,type:`Secondary (Biological) Treatment`,desc:`The aeration basin is the heart of the activated sludge process. Microorganisms consume organic matter (BOD) in the presence of dissolved oxygen supplied through diffusers or mechanical aerators. Mixed liquor then flows to the secondary clarifier for separation.`,tip:`<strong>Exam Tip:</strong> DO maintained at 1-3 mg/L, F/M ratio 0.2-0.5, MLSS 1500-3000 mg/L, SRT 5-15 days, detention time 4-8 hours. SVI less than 150 indicates good settling.`},
  secondary_clar:{name:`Secondary Clarifier`,type:`Secondary Treatment`,desc:`Secondary clarifiers separate the biological floc from treated water. Clear effluent overflows the weirs while settled sludge is collected at the bottom. Most settled sludge returns to the aeration basin (RAS) while excess is wasted (WAS).`,tip:`<strong>Exam Tip:</strong> SOR typically 400-800 GPD/ft2. Rising sludge indicates denitrification in the clarifier. Bulking sludge has SVI greater than 150.`},
  disinfection:{name:`Disinfection`,type:`Tertiary Treatment`,desc:`Disinfection kills pathogenic organisms before discharge. Chlorination is most common using a serpentine contact basin. UV disinfection is increasingly used. Dechlorination using sodium bisulfite is often required before discharge.`,tip:`<strong>Exam Tip:</strong> CT = concentration times time. Contact time 15-30 minutes at peak flow. Chlorine residual must meet permit limits. UV dose measured in mJ/cm2.`},
  effluent:{name:`Effluent Discharge`,type:`Final Discharge`,desc:`Treated effluent is discharged to a receiving water body under an NPDES permit. The permit specifies limits for BOD, TSS, pH, ammonia, and other parameters. Effluent quality must be monitored and reported regularly.`,tip:`<strong>Exam Tip:</strong> Secondary treatment standard: BOD and TSS both less than or equal to 30 mg/L. pH 6.0-9.0. Facilities submit Discharge Monitoring Reports (DMRs).`},
  thickener:{name:`Thickener`,type:`Solids Handling`,desc:`Thickeners concentrate sludge by removing water, increasing solids content from 1-3% to 4-8%. Gravity thickeners work best for primary sludge. DAF thickeners are preferred for waste activated sludge.`,tip:`<strong>Exam Tip:</strong> Thickening reduces sludge volume, saving digester capacity. Gravity thickener loading rates: 6-10 lb/day/ft2. Thickener overflow returns to the plant headworks.`},
  digester:{name:`Anaerobic Digester`,type:`Solids Stabilization`,desc:`Anaerobic digesters stabilize sludge in the absence of oxygen at 95 degrees F (35 degrees C) with 15-20 day detention time. Methane gas is produced and can be used for heating or power generation. Digestion reduces volatile solids by 40-60%.`,tip:`<strong>Exam Tip:</strong> Digester gas: 60-65% methane, 30-35% CO2. Optimum pH 6.8-7.2. If pH drops below 6.2 the digester is going sour. Volatile acids/alkalinity ratio should be less than 0.1.`},
  biosolids:{name:`Dewatering / Biosolids`,type:`Final Solids Processing`,desc:`Dewatering removes water from digested sludge to create a cake of 15-30% solids for disposal or beneficial reuse. Belt filter presses, centrifuges, and plate-and-frame presses are common. Polymer is added as a conditioning agent.`,tip:`<strong>Exam Tip:</strong> Class A biosolids: less than 1000 MPN fecal coliform/g - can be used on lawns. Class B: less than 2 million MPN - restricted land application. The 503 Rule governs biosolids.`},
  ras_was:{name:`Sludge Pumps`,type:`Sludge Return and Wasting`,desc:`RAS pumps return settled sludge from the secondary clarifier to the aeration basin. WAS pumps remove excess biomass. Proper RAS/WAS control is critical for maintaining the right F/M ratio and sludge age.`,tip:`<strong>Exam Tip:</strong> RAS rate typically 25-75% of influent flow. Wasting controls sludge age (SRT/MCRT). MCRT = lb MLSS in system divided by lb WAS per day.`},
  lab:{name:`Lab / Monitoring`,type:`Quality Control`,desc:`The lab monitors treatment performance through regular testing of influent, effluent, and process streams. Key tests include BOD, TSS, pH, DO, ammonia, chlorine residual, fecal coliform, MLSS/MLVSS, and settleability.`,tip:`<strong>Exam Tip:</strong> SVI = (settled sludge volume in mL/L times 1000) divided by MLSS in mg/L. Good SVI: 80-150. BOD test: 5-day incubation at 20 degrees C.`}
};

var mode='explore',qOrder=[],qIdx=0,qCorrect=0,qWrong=0,qActive=false;
var dragSel=null,dragPlaced=0,dragWrong=0,showingAnswers=false;
var wrap=document.getElementById('opp-ww-diagram');
var total=Object.keys(C).length;

function setMode(m){
  mode=m;
  var cls=wrap.className.split(' ').filter(function(c){return c.indexOf('mode-')!==0;});
  cls.push('mode-'+m); wrap.className=cls.join(' ');
  ['wwBtn0','wwBtn1','wwBtn2'].forEach(function(id,i){
    var btn=document.getElementById(id);
    var active=(i===(['explore','quiz','drag'].indexOf(m)));
    btn.classList.toggle('active',active);
    btn.setAttribute('aria-selected',active?'true':'false');
  });
  document.getElementById('wwInfo').classList.remove('visible');
  document.getElementById('wwComplete').classList.remove('visible');
  document.querySelectorAll('.ww-comp').forEach(function(c){c.classList.remove('selected','correct-flash','wrong-flash');});
  var isQ=(m==='quiz'),isD=(m==='drag');
  document.getElementById('wwQuizBanner').classList.toggle('visible',isQ);
  document.getElementById('wwDragBanner').classList.toggle('visible',isD);
  document.getElementById('wwDragPanel').classList.toggle('visible',isD);
  document.getElementById('wwDragControls').classList.toggle('visible',isD);
  var hideLbls=(isD||(isQ&&qActive));
  document.querySelectorAll('.ww-label,.ww-label-bg,.ww-sublabel').forEach(function(e){e.style.display=hideLbls?'none':'';});
  if(isQ&&!qActive){
    document.getElementById('wwQBQ').textContent='Ready to test your knowledge?';
    document.getElementById('wwQBCounter').textContent='';
    document.getElementById('wwProgressBar').style.display='none';
    document.getElementById('wwQBFB').textContent='';
    document.getElementById('wwQBFB').className='ww-qb-fb';
    document.getElementById('wwQBSc').textContent='';
    document.getElementById('wwStartBtn').style.display='';
    document.getElementById('wwQBIntro').style.display='';
  }
  if(isD) setupDrag();
}

function showInfo(id){
  var c=C[id]; if(!c)return;
  document.getElementById('wwIT').textContent=c.name;
  document.getElementById('wwITy').textContent=c.type;
  document.getElementById('wwID').textContent=c.desc;
  document.getElementById('wwITip').innerHTML=c.tip;
  document.getElementById('wwInfo').classList.add('visible');
}

function startQuiz(){
  qOrder=Object.keys(C).sort(function(){return Math.random()-.5;});
  qIdx=0; qCorrect=0; qWrong=0; qActive=true;
  document.querySelectorAll('.ww-label,.ww-label-bg,.ww-sublabel').forEach(function(e){e.style.display='none';});
  document.getElementById('wwStartBtn').style.display='none';
  document.getElementById('wwQBIntro').style.display='none';
  document.getElementById('wwProgressBar').style.display='block';
  document.getElementById('wwComplete').classList.remove('visible');
  nextQ();
}

function nextQ(){
  if(qIdx>=qOrder.length){endQuiz();return;}
  document.getElementById('wwQBQ').textContent='Click on: '+C[qOrder[qIdx]].name;
  document.getElementById('wwQBCounter').textContent='Question '+(qIdx+1)+' of '+qOrder.length;
  document.getElementById('wwProgressFill').style.width=Math.round((qIdx/qOrder.length)*100)+'%';
  document.getElementById('wwQBFB').textContent='';
  document.getElementById('wwQBSc').textContent='Correct: '+qCorrect+' \xb7 Wrong: '+qWrong;
}

function checkAnswer(id,el){
  if(!qActive||qIdx>=qOrder.length)return;
  var exp=qOrder[qIdx]; qIdx++;
  var fb=document.getElementById('wwQBFB');
  if(id===exp){
    qCorrect++;
    fb.textContent='Correct!'; fb.className='ww-qb-fb correct';
    el.classList.add('correct-flash'); setTimeout(function(){el.classList.remove('correct-flash');},900);
  } else {
    qWrong++;
    fb.textContent='That is '+C[id].name+'. Answer: '+C[exp].name;
    fb.className='ww-qb-fb wrong';
    el.classList.add('wrong-flash');
    var ce=document.querySelector('.ww-comp[data-id="'+exp+'"]');
    if(ce){ce.classList.add('correct-flash');setTimeout(function(){ce.classList.remove('correct-flash');},1400);}
    setTimeout(function(){el.classList.remove('wrong-flash');},900);
  }
  document.getElementById('wwQBSc').textContent='Correct: '+qCorrect+' \xb7 Wrong: '+qWrong;
  setTimeout(nextQ,1800);
}

function endQuiz(){
  qActive=false;
  document.getElementById('wwProgressFill').style.width='100%';
  document.getElementById('wwQuizBanner').classList.remove('visible');
  var pct=Math.round((qCorrect/qOrder.length)*100);
  document.getElementById('wwCompScore').textContent=qCorrect+' / '+qOrder.length+' ('+pct+'%)';
  var title,msg;
  if(pct===100){title='Perfect Score!';msg='Outstanding! You identified every component correctly. You are ready for the WW exam!';}
  else if(pct>=70){title='Great Job!';msg='You got '+qCorrect+' of '+qOrder.length+' correct. Review the ones you missed in Explore mode.';}
  else{title='Keep Practicing';msg='You got '+qCorrect+' of '+qOrder.length+' correct. Use Explore mode to study the components, then try again.';}
  document.getElementById('wwCompTitle').textContent=title;
  document.getElementById('wwCompMsg').textContent=msg;
  document.getElementById('wwComplete').classList.add('visible');
  document.querySelectorAll('.ww-label,.ww-label-bg,.ww-sublabel').forEach(function(e){e.style.display='';});
}

function setupDrag(){
  dragSel=null; dragPlaced=0; dragWrong=0; showingAnswers=false;
  document.getElementById('wwShowAnsBtn').textContent='Show Answers';
  document.getElementById('wwShowAnsBtn').classList.remove('active');
  document.querySelectorAll('.ww-drop-zone').forEach(function(dz){dz.classList.remove('filled');});
  document.querySelectorAll('.ww-label,.ww-label-bg,.ww-sublabel').forEach(function(e){e.style.display='none';});
  var ids=Object.keys(C).sort(function(){return Math.random()-.5;});
  var panel=document.getElementById('wwDragPanel'); panel.innerHTML='';
  ids.forEach(function(id){
    var chip=document.createElement('div');
    chip.className='ww-chip'; chip.textContent=C[id].name;
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
      document.querySelectorAll('.ww-chip').forEach(function(b){b.classList.remove('on');});
      chip.classList.add('on'); dragSel=id;
      document.getElementById('wwDragInst').textContent='Now click the matching component in the diagram:';
      document.getElementById('wwDragSel').textContent='> '+C[id].name;
    });
    chip.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();chip.click();}});
    panel.appendChild(chip);
  });
  document.getElementById('wwDCorr').textContent='0';
  document.getElementById('wwDWrong').textContent='0';
  document.getElementById('wwDRem').textContent=String(total);
  document.getElementById('wwDragFill').style.width='0%';
  document.getElementById('wwDragInst').textContent='Drag a label onto its matching component — or tap a label, then tap the component.';
  document.getElementById('wwDragSel').textContent='';
  document.getElementById('wwDragFB').textContent='';
}

function placeDrop(dragId,targetId,targetEl){
  var chip=document.querySelector('.ww-chip[data-id="'+dragId+'"]');
  if(dragId===targetId){
    dragPlaced++;
    if(chip){chip.classList.remove('on','dragging','wrong-chip');chip.classList.add('done');}
    var dz=document.querySelector('.ww-drop-zone[data-id="'+targetId+'"]');
    if(dz)dz.classList.add('filled');
    var cg=document.querySelector('.ww-comp[data-id="'+targetId+'"]');
    if(cg)cg.querySelectorAll('.ww-label,.ww-label-bg').forEach(function(e){e.style.display='';});
    targetEl.classList.add('correct-flash');setTimeout(function(){targetEl.classList.remove('correct-flash');},900);
    document.getElementById('wwDragFB').textContent='';
    document.getElementById('wwDCorr').textContent=String(dragPlaced);
    document.getElementById('wwDRem').textContent=String(total-dragPlaced);
    document.getElementById('wwDragFill').style.width=Math.round((dragPlaced/total)*100)+'%';
    if(dragPlaced>=total){document.getElementById('wwDragInst').textContent='All '+total+' components placed correctly!';}
    document.getElementById('wwDragSel').textContent='';
    dragSel=null;
  } else {
    dragWrong++;
    targetEl.classList.add('wrong-flash');setTimeout(function(){targetEl.classList.remove('wrong-flash');},700);
    if(chip){chip.classList.remove('on');chip.classList.add('wrong-chip');setTimeout(function(){chip.classList.remove('wrong-chip');},500);}
    document.getElementById('wwDragFB').textContent='That is the '+C[targetId].name+'. Hint: '+C[dragId].name+' is "'+C[dragId].type+'".';
    document.getElementById('wwDWrong').textContent=String(dragWrong);
    dragSel=null;
    document.querySelectorAll('.ww-chip').forEach(function(b){b.classList.remove('on');});
    document.getElementById('wwDragSel').textContent='';
  }
}

function toggleShowAnswers(){
  showingAnswers=!showingAnswers;
  var btn=document.getElementById('wwShowAnsBtn');
  btn.textContent=showingAnswers?'Hide Answers':'Show Answers';
  btn.classList.toggle('active',showingAnswers);
  document.querySelectorAll('.ww-comp').forEach(function(comp){
    var id=comp.getAttribute('data-id');
    if(!document.querySelector('.ww-drop-zone[data-id="'+id+'"].filled')){
      comp.querySelectorAll('.ww-label,.ww-label-bg').forEach(function(e){e.style.display=showingAnswers?'':'none';});
    }
  });
}

document.getElementById('wwBtn0').addEventListener('click',function(){setMode('explore');});
document.getElementById('wwBtn1').addEventListener('click',function(){setMode('quiz');});
document.getElementById('wwBtn2').addEventListener('click',function(){setMode('drag');});
document.getElementById('wwStartBtn').addEventListener('click',function(){startQuiz();});
document.getElementById('wwRetryBtn').addEventListener('click',function(){
  document.getElementById('wwComplete').classList.remove('visible');
  qActive=false; setMode('quiz'); setTimeout(function(){startQuiz();},100);
});
document.getElementById('wwExploreBtn').addEventListener('click',function(){setMode('explore');});
document.getElementById('wwCloseBtn').addEventListener('click',function(){
  document.getElementById('wwInfo').classList.remove('visible');
  document.querySelectorAll('.ww-comp').forEach(function(c){c.classList.remove('selected');});
});
document.getElementById('wwResetBtn').addEventListener('click',function(){setupDrag();});
document.getElementById('wwShowAnsBtn').addEventListener('click',function(){toggleShowAnswers();});
document.getElementById('wwHowtoBtn').addEventListener('click',function(){
  var box=document.getElementById('wwHowtoBox');
  var open=box.classList.toggle('open');
  document.getElementById('wwHowtoBtn').setAttribute('aria-expanded',open?'true':'false');
});

var svgWrap=document.querySelector('.ww-svg-wrap');
function findComp(target,root){
  var el=target;
  while(el&&el!==root){if(el.classList&&el.classList.contains('ww-comp'))return el;el=el.parentElement;}
  return null;
}
svgWrap.addEventListener('click',function(e){
  var comp=findComp(e.target,this); if(!comp)return;
  var id=comp.getAttribute('data-id');
  if(mode==='explore'){
    showInfo(id);
    document.querySelectorAll('.ww-comp').forEach(function(c){c.classList.remove('selected');});
    comp.classList.add('selected');
  } else if(mode==='quiz'&&qActive){checkAnswer(id,comp);}
  else if(mode==='drag'&&dragSel){placeDrop(dragSel,id,comp);}
});
svgWrap.addEventListener('dragover',function(e){
  var comp=findComp(e.target,this);
  if(comp&&!comp.querySelector('.ww-drop-zone.filled')){
    e.preventDefault(); e.dataTransfer.dropEffect='move';
    document.querySelectorAll('.ww-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
    comp.classList.add('drag-over');
  }
});
svgWrap.addEventListener('dragleave',function(e){
  if(!svgWrap.contains(e.relatedTarget))
    document.querySelectorAll('.ww-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
});
svgWrap.addEventListener('drop',function(e){
  e.preventDefault();
  document.querySelectorAll('.ww-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
  var comp=findComp(e.target,this); if(!comp)return;
  var dragId=e.dataTransfer.getData('text/plain');
  if(dragId)placeDrop(dragId,comp.getAttribute('data-id'),comp);
});
})();
</script>
</div>
<!-- /wp:html -->
ENDWW;

wp_update_post(array('ID' => $pid, 'post_content' => $content));
echo "UPDATED: WW1/WW2 wastewater diagram v3 (ID $pid)" . PHP_EOL;
wp_cache_flush();
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
