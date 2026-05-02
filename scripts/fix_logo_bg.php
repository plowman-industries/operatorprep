<?php
/**
 * Distribution Diagram — full UX overhaul (v3)
 * Implements all Claude QA feedback:
 * - Mode buttons with descriptions
 * - Quiz gates labels behind Start Quiz
 * - Quiz shows question prompt + counter + instant feedback
 * - Drag & Drop: wrong-drop hint, progress bar, reset, show-answers toggle
 * - Label naming consistency (short SVG labels = chip labels)
 * - Completion screen with summary
 * - Aria labels for accessibility
 * - Underground visual band improved
 * Page ID 1094 (/d1-d2-distribution-diagram/)
 */
echo "Updating distribution diagram (v3 UX overhaul)..." . PHP_EOL;

$pid = 1094;
if (!get_post($pid)) { echo "NOT FOUND" . PHP_EOL; exit; }

$content = <<<'ENDHTML'
<!-- wp:html -->
<style>.entry-header.ast-no-thumbnail{display:none!important}</style>
<div id="opp-dist-diagram">
<style>
#opp-dist-diagram{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#fff;color:#1e293b;max-width:1200px;margin:0 auto;padding:24px;box-sizing:border-box}
#opp-dist-diagram *,#opp-dist-diagram *::before,#opp-dist-diagram *::after{box-sizing:border-box}
.dd-header{text-align:center;margin-bottom:20px}
.dd-header h1{font-size:1.7em;color:#0f172a;margin:0 0 4px;font-weight:700}
.dd-header p{color:#64748b;font-size:.95em;margin:0}
/* How-to toggle */
.dd-howto-toggle{background:none;border:none;color:#2563eb;font-size:.85em;cursor:pointer;text-decoration:underline;margin-top:6px;padding:0}
.dd-howto-box{display:none;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 16px;margin-top:8px;font-size:.85em;color:#1e3a8a;text-align:left;max-width:600px;margin-left:auto;margin-right:auto}
.dd-howto-box.open{display:block}
.dd-howto-box ul{margin:4px 0 0;padding-left:18px}
.dd-howto-box li{margin-bottom:4px}
/* Mode buttons */
.dd-modes{display:flex;justify-content:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;align-items:flex-start}
.dd-mode-wrap{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:130px}
.dd-mode-btn{background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}
.dd-mode-btn:hover{background:#f1f5f9;color:#1e293b;border-color:#cbd5e1}
.dd-mode-btn.active{background:#1e40af;color:#fff;border-color:#1e40af;box-shadow:0 2px 8px rgba(30,64,175,.25)}
.dd-mode-desc{font-size:.75em;color:#94a3b8;text-align:center;line-height:1.3}
/* Quiz banner */
.dd-quiz-banner{display:none;background:linear-gradient(135deg,#1e40af,#2563eb);color:#fff;border-radius:10px;padding:16px 20px;margin-bottom:12px;text-align:center}
.dd-quiz-banner.visible{display:block;animation:ddFadeIn .25s ease}
.dd-qb-q{font-size:1.1em;font-weight:700;margin-bottom:6px;min-height:1.4em}
.dd-qb-counter{font-size:.8em;color:rgba(255,255,255,.75);margin-bottom:8px;min-height:1em}
.dd-progress-bar{background:rgba(255,255,255,.2);border-radius:4px;height:6px;margin:0 auto 10px;max-width:300px;overflow:hidden}
.dd-progress-fill{background:#86efac;height:6px;border-radius:4px;width:0;transition:width .4s ease}
.dd-qb-row{display:flex;justify-content:center;align-items:center;gap:16px;flex-wrap:wrap}
.dd-qb-feedback{font-size:.95em;font-weight:600;min-height:1.5em}
.dd-qb-feedback.correct{color:#86efac}
.dd-qb-feedback.wrong{color:#fca5a5}
.dd-qb-feedback.hint{color:#fde68a;font-size:.88em}
.dd-qb-score{font-size:.85em;color:rgba(255,255,255,.8)}
.dd-quiz-start{background:rgba(255,255,255,.2);color:#fff;border:1.5px solid rgba(255,255,255,.4);padding:8px 24px;border-radius:7px;font-size:.95em;font-weight:700;cursor:pointer;transition:all .18s;margin-top:8px}
.dd-quiz-start:hover{background:rgba(255,255,255,.3)}
/* Quiz intro (before Start is clicked) */
.dd-qb-intro{font-size:.9em;color:rgba(255,255,255,.85);margin-bottom:4px}
/* Completion screen */
.dd-complete{display:none;background:#f0fdf4;border:2px solid #86efac;border-radius:12px;padding:24px;text-align:center;margin-bottom:12px;animation:ddFadeIn .3s ease}
.dd-complete.visible{display:block}
.dd-complete h3{color:#15803d;font-size:1.4em;margin:0 0 8px}
.dd-complete .dd-final-score{font-size:2em;font-weight:800;color:#15803d;margin:8px 0}
.dd-complete p{color:#166534;font-size:.95em;margin:0 0 12px}
.dd-complete-btns{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.dd-complete-btns button{padding:8px 20px;border-radius:8px;font-size:.9em;font-weight:600;cursor:pointer;border:none;transition:all .18s}
.dd-btn-retry{background:#1e40af;color:#fff}
.dd-btn-retry:hover{background:#1d4ed8}
.dd-btn-explore{background:#f1f5f9;color:#1e293b;border:1.5px solid #e2e8f0!important;border:none}
.dd-btn-explore:hover{background:#e2e8f0}
/* Drag banner */
.dd-drag-banner{display:none;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px 20px;margin-bottom:8px;text-align:center;color:#475569;font-size:.9em}
.dd-drag-banner.visible{display:block;animation:ddFadeIn .25s ease}
.dd-db-selected{font-weight:700;color:#1e40af;font-size:.95em;margin:4px 0}
.dd-drag-score-row{display:flex;justify-content:center;align-items:center;gap:12px;flex-wrap:wrap;margin-top:6px;font-size:.85em;color:#64748b}
.dd-drag-score-row .dd-sc-correct{color:#16a34a;font-weight:700}
.dd-drag-score-row .dd-sc-wrong{color:#dc2626;font-weight:700}
.dd-drag-progress{background:#e2e8f0;border-radius:4px;height:6px;max-width:300px;width:100%;margin:6px auto 0;overflow:hidden}
.dd-drag-progress-fill{background:#16a34a;height:6px;border-radius:4px;width:0;transition:width .4s ease}
.dd-drag-feedback{font-size:.88em;color:#dc2626;font-weight:600;min-height:1.4em;margin-top:4px}
/* Drag controls row */
.dd-drag-controls{display:none;justify-content:center;gap:8px;margin-bottom:8px;flex-wrap:wrap}
.dd-drag-controls.visible{display:flex}
.dd-ctrl-btn{background:#f1f5f9;color:#475569;border:1.5px solid #e2e8f0;padding:5px 14px;border-radius:7px;font-size:.8em;font-weight:600;cursor:pointer;transition:all .18s}
.dd-ctrl-btn:hover{background:#e2e8f0;color:#1e293b}
.dd-ctrl-btn.active{background:#1e40af;color:#fff;border-color:#1e40af}
/* SVG wrap */
.dd-svg-wrap{background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden}
.dd-svg-wrap svg{display:block;width:100%;height:auto}
.dd-comp{cursor:pointer}
.dd-comp .dd-cb{transition:filter .15s}
.dd-comp:hover .dd-cb{filter:drop-shadow(0 0 5px rgba(37,99,235,.35))}
.dd-comp.selected .dd-cb{filter:drop-shadow(0 0 8px rgba(37,99,235,.6))}
.dd-comp.correct-flash .dd-cb{filter:drop-shadow(0 0 10px rgba(22,163,74,.9))}
.dd-comp.wrong-flash .dd-cb{filter:drop-shadow(0 0 10px rgba(220,38,38,.85))}
.dd-dz{fill:transparent;stroke:#f59e0b;stroke-width:2.5;stroke-dasharray:6,3;opacity:0;pointer-events:none}
#opp-dist-diagram.mode-drag .dd-dz{opacity:1;animation:ddPulse 1.8s infinite;pointer-events:all;cursor:pointer}
#opp-dist-diagram.mode-drag .dd-dz.filled{stroke:#16a34a;opacity:.7;stroke-dasharray:none;animation:none}
@keyframes ddPulse{0%,100%{opacity:.4}50%{opacity:1}}
/* Info panel */
.dd-info{background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:20px 24px;margin-top:14px;display:none;box-shadow:0 4px 16px rgba(0,0,0,.07);position:relative}
.dd-info.visible{display:block;animation:ddFadeIn .25s ease}
@keyframes ddFadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
.dd-info h2{color:#1e40af;font-size:1.2em;margin:0 0 4px}
.dd-info .dd-itype{color:#d97706;font-size:.82em;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px}
.dd-info p{color:#334155;line-height:1.65;margin:0 0 10px;font-size:.95em}
.dd-info .dd-tip{background:#fffbeb;border-left:3px solid #f59e0b;padding:10px 14px;border-radius:0 8px 8px 0;margin-top:10px;font-size:.9em;color:#44403c}
.dd-info .dd-tip strong{color:#92400e}
.dd-info .dd-close{position:absolute;top:14px;right:16px;background:#f1f5f9;border:none;color:#64748b;font-size:1.1em;cursor:pointer;padding:4px 8px;border-radius:6px;line-height:1}
.dd-info .dd-close:hover{background:#e2e8f0;color:#1e293b}
/* Drag chip panel */
.dd-drag-panel{display:none;flex-wrap:wrap;gap:8px;justify-content:center;padding:16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;margin-bottom:10px}
.dd-drag-panel.visible{display:flex}
.dd-chip{background:#fff;color:#334155;padding:7px 14px;border-radius:8px;cursor:grab;font-size:.85em;font-weight:500;border:1.5px solid #e2e8f0;user-select:none;transition:all .18s;box-shadow:0 1px 3px rgba(0,0,0,.05)}
.dd-chip:hover{border-color:#94a3b8;background:#f1f5f9}
.dd-chip.on{background:#1e40af;color:#fff;border-color:#1e40af;box-shadow:0 2px 8px rgba(30,64,175,.3);cursor:grabbing}
.dd-chip.dragging{opacity:.4;cursor:grabbing}
.dd-chip.done{background:#f0fdf4;color:#16a34a;border-color:#86efac;cursor:default;opacity:.7;text-decoration:line-through}
.dd-chip.wrong-chip{animation:ddShake .4s ease;border-color:#fca5a5;background:#fff1f2}
.dd-comp.drag-over .dd-cb{filter:drop-shadow(0 0 8px rgba(249,115,22,.7))}
/* SVG text */
.dd-lbl{font-family:'Segoe UI',system-ui,sans-serif;fill:#1e293b;font-size:11px;font-weight:600;pointer-events:none}
.dd-lbg{fill:#fff;stroke:#e2e8f0;stroke-width:1}
.dd-sub{font-family:'Segoe UI',system-ui,sans-serif;fill:#64748b;font-size:9px;pointer-events:none}
.dd-arrow{fill:none;stroke:#3b82f6;stroke-width:2;stroke-dasharray:8,4;opacity:.7}
@keyframes ddShake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
@media(max-width:768px){#opp-dist-diagram{padding:12px}.dd-header h1{font-size:1.3em}.dd-mode-btn{padding:7px 12px;font-size:.82em}.dd-lbl{font-size:8.5px}.dd-mode-wrap{min-width:100px}}
</style>

<div class="dd-header">
  <h1>Water Distribution System &mdash; Interactive Diagram</h1>
  <p>D1 &amp; D2 &mdash; Key Distribution System Components</p>
  <button class="dd-howto-toggle" id="ddHowtoBtn" aria-expanded="false" aria-controls="ddHowtoBox">&#x2139;&#xfe0f; How to use this tool</button>
  <div class="dd-howto-box" id="ddHowtoBox" role="region" aria-label="How to use">
    <ul>
      <li><strong>Explore:</strong> Click any component to learn its name, function, and exam tips.</li>
      <li><strong>Quiz Mode:</strong> Click &ldquo;Start Quiz&rdquo; &mdash; then click the component named in the prompt. Instant feedback after each answer.</li>
      <li><strong>Drag &amp; Drop:</strong> Drag label chips onto the matching component. Or tap a chip, then tap the component. Use &ldquo;Reset&rdquo; to start over.</li>
    </ul>
  </div>
</div>

<div class="dd-modes" role="tablist">
  <div class="dd-mode-wrap">
    <button class="dd-mode-btn active" id="ddBtn0" role="tab" aria-selected="true" aria-label="Explore mode: see all labels and click for details">&#x1F50D; Explore</button>
    <span class="dd-mode-desc">Click components for details</span>
  </div>
  <div class="dd-mode-wrap">
    <button class="dd-mode-btn" id="ddBtn1" role="tab" aria-selected="false" aria-label="Quiz mode: identify components from icons">&#x1F4DD; Quiz Mode</button>
    <span class="dd-mode-desc">Identify components by icon</span>
  </div>
  <div class="dd-mode-wrap">
    <button class="dd-mode-btn" id="ddBtn2" role="tab" aria-selected="false" aria-label="Drag and drop mode: match labels to components">&#x1F3AF; Drag &amp; Drop</button>
    <span class="dd-mode-desc">Match labels to components</span>
  </div>
</div>

<div class="dd-quiz-banner" id="ddQuizBanner" role="status" aria-live="polite">
  <div class="dd-qb-intro" id="ddQBIntro">Test your knowledge &mdash; click the component named below each time.</div>
  <div class="dd-qb-q" id="ddQBQ">Ready to test your knowledge?</div>
  <div class="dd-qb-counter" id="ddQBCounter"></div>
  <div class="dd-progress-bar" id="ddProgressBar" style="display:none"><div class="dd-progress-fill" id="ddProgressFill"></div></div>
  <div class="dd-qb-row">
    <div class="dd-qb-feedback" id="ddQBFB"></div>
    <div class="dd-qb-score" id="ddQBSc"></div>
  </div>
  <button class="dd-quiz-start" id="ddStartBtn" aria-label="Start the quiz">Start Quiz</button>
</div>

<div class="dd-complete" id="ddComplete" role="alert">
  <h3 id="ddCompTitle">Quiz Complete!</h3>
  <div class="dd-final-score" id="ddCompScore"></div>
  <p id="ddCompMsg"></p>
  <div class="dd-complete-btns">
    <button class="dd-btn-retry" id="ddRetryBtn">&#x1F501; Retry Quiz</button>
    <button class="dd-btn-explore" id="ddExploreBtn">&#x1F50D; Review in Explore Mode</button>
  </div>
</div>

<div class="dd-drag-banner" id="ddDragBanner" role="status" aria-live="polite">
  <div id="ddDragInst">Drag a label onto its matching component &mdash; or tap a label, then tap the component.</div>
  <div class="dd-db-selected" id="ddDragSel"></div>
  <div class="dd-drag-score-row">
    <span>Correct: <span class="dd-sc-correct" id="ddDCorr">0</span></span>
    <span>Wrong: <span class="dd-sc-wrong" id="ddDWrong">0</span></span>
    <span>Remaining: <span id="ddDRem">14</span></span>
  </div>
  <div class="dd-drag-progress"><div class="dd-drag-progress-fill" id="ddDragFill"></div></div>
  <div class="dd-drag-feedback" id="ddDragFB"></div>
</div>

<div class="dd-drag-controls" id="ddDragControls">
  <button class="dd-ctrl-btn" id="ddResetBtn" aria-label="Reset drag and drop">&#x21BA; Reset</button>
  <button class="dd-ctrl-btn" id="ddShowAnsBtn" aria-label="Toggle show answers">&#x1F441; Show Answers</button>
</div>

<div class="dd-drag-panel" id="ddDragPanel" aria-label="Label chips for drag and drop"></div>

<div class="dd-svg-wrap">
<svg viewBox="0 0 1200 520" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Water Distribution System diagram">
  <defs><marker id="ddarr" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#3b82f6"/></marker></defs>
  <!-- Background -->
  <rect x="0" y="0" width="1200" height="520" fill="#f8fafc"/>
  <!-- Underground zone -->
  <rect x="0" y="340" width="1200" height="180" fill="#dbeafe" opacity=".22"/>
  <line x1="0" y1="340" x2="1200" y2="340" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="8,5"/>
  <text x="18" y="360" fill="#64748b" font-size="11" font-family="sans-serif" font-style="italic" font-weight="600">&#x25BC; Underground</text>
  <text x="18" y="330" fill="#64748b" font-size="11" font-family="sans-serif" font-style="italic" font-weight="600">Above Ground &#x25B2;</text>
  <!-- Flow arrows -->
  <path d="M 135,260 L 220,260" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 320,260 L 400,260" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 480,260 L 540,200" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 480,260 L 540,380" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 640,200 L 720,260" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 640,380 L 720,260" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 820,260 L 900,260" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 1000,260 L 1060,180" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 1000,260 L 1060,340" class="dd-arrow" marker-end="url(#ddarr)"/>
  <path d="M 1000,260 L 1060,460" class="dd-arrow" marker-end="url(#ddarr)"/>
  <!-- Branch lines -->
  <path d="M 770,260 L 770,380" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 770,260 L 770,120" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 820,260 L 880,260 L 880,380" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 880,260 L 930,190" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <!-- Components -->
  <g class="dd-comp" data-id="treatment" role="button" aria-label="Treatment Plant"><g class="dd-cb"><rect x="30" y="210" width="100" height="100" rx="8" fill="#fff" stroke="#3b82f6" stroke-width="2"/><rect x="45" y="225" width="70" height="50" rx="4" fill="#dbeafe"/><rect x="55" y="232" width="12" height="36" rx="2" fill="#3b82f6" opacity=".5"/><rect x="73" y="240" width="12" height="28" rx="2" fill="#3b82f6" opacity=".35"/><rect x="91" y="236" width="12" height="32" rx="2" fill="#3b82f6" opacity=".45"/><circle cx="80" cy="295" r="6" fill="#22c55e"/><text x="80" y="298" text-anchor="middle" fill="#fff" font-size="7" font-weight="bold">OK</text></g><rect class="dd-lbg" x="32" y="195" width="96" height="18" rx="3"/><text class="dd-lbl" x="80" y="208" text-anchor="middle">Treatment Plant</text><rect class="dd-dz" data-id="treatment" x="28" y="193" width="104" height="122" rx="8"/></g>
  <g class="dd-comp" data-id="transmission" role="button" aria-label="Transmission Main"><g class="dd-cb"><rect x="220" y="245" width="100" height="30" rx="6" fill="#eff6ff" stroke="#3b82f6" stroke-width="1.5"/><line x1="235" y1="260" x2="305" y2="260" stroke="#3b82f6" stroke-width="3" stroke-dasharray="10,5"/><circle cx="250" cy="260" r="4" fill="#93c5fd"/><circle cx="280" cy="260" r="4" fill="#93c5fd"/></g><rect class="dd-lbg" x="222" y="227" width="96" height="18" rx="3"/><text class="dd-lbl" x="270" y="240" text-anchor="middle">Transmission Main</text><text class="dd-sub" x="270" y="290" text-anchor="middle">16-48 inch diameter</text><rect class="dd-dz" data-id="transmission" x="218" y="225" width="104" height="58" rx="6"/></g>
  <g class="dd-comp" data-id="booster" role="button" aria-label="Booster Pump Station"><g class="dd-cb"><rect x="400" y="220" width="80" height="80" rx="8" fill="#fff" stroke="#8b5cf6" stroke-width="2"/><circle cx="440" cy="255" r="20" fill="#ede9fe" stroke="#8b5cf6" stroke-width="1.5"/><path d="M 428,255 L 440,243 L 452,255 L 440,267 Z" fill="#8b5cf6" opacity=".8"/><circle cx="440" cy="255" r="6" fill="#a78bfa"/></g><rect class="dd-lbg" x="394" y="202" width="92" height="18" rx="3"/><text class="dd-lbl" x="440" y="215" text-anchor="middle">Booster Station</text><rect class="dd-dz" data-id="booster" x="398" y="200" width="84" height="104" rx="8"/></g>
  <g class="dd-comp" data-id="elevated" role="button" aria-label="Elevated Storage Tank"><g class="dd-cb"><line x1="590" y1="200" x2="590" y2="130" stroke="#94a3b8" stroke-width="3"/><rect x="555" y="80" width="70" height="50" rx="6" fill="#fff" stroke="#38bdf8" stroke-width="2"/><path d="M 555,80 Q 590,65 625,80" fill="#fff" stroke="#38bdf8" stroke-width="2"/><rect x="558" y="95" width="64" height="32" rx="3" fill="#bae6fd" opacity=".7"/><text class="dd-sub" x="590" y="115" text-anchor="middle" fill="#0284c7">75%</text></g><rect class="dd-lbg" x="537" y="55" width="106" height="18" rx="3"/><text class="dd-lbl" x="590" y="68" text-anchor="middle">Elevated Storage Tank</text><rect class="dd-dz" data-id="elevated" x="535" y="53" width="110" height="160" rx="6"/></g>
  <g class="dd-comp" data-id="ground_storage" role="button" aria-label="Ground-Level Storage"><g class="dd-cb"><rect x="545" y="360" width="90" height="60" rx="8" fill="#fff" stroke="#38bdf8" stroke-width="2"/><rect x="550" y="380" width="80" height="35" rx="4" fill="#bae6fd" opacity=".5"/><line x1="555" y1="390" x2="625" y2="390" stroke="#38bdf8" stroke-width="1" opacity=".6"/><line x1="555" y1="400" x2="625" y2="400" stroke="#38bdf8" stroke-width="1" opacity=".4"/></g><rect class="dd-lbg" x="537" y="342" width="106" height="18" rx="3"/><text class="dd-lbl" x="590" y="355" text-anchor="middle">Ground-Level Storage</text><rect class="dd-dz" data-id="ground_storage" x="535" y="340" width="110" height="84" rx="8"/></g>
  <g class="dd-comp" data-id="dist_main" role="button" aria-label="Distribution Main"><g class="dd-cb"><rect x="720" y="245" width="100" height="30" rx="6" fill="#eff6ff" stroke="#2563eb" stroke-width="1.5"/><line x1="735" y1="260" x2="805" y2="260" stroke="#2563eb" stroke-width="3"/><circle cx="755" cy="260" r="3" fill="#93c5fd"/><circle cx="780" cy="260" r="3" fill="#93c5fd"/></g><rect class="dd-lbg" x="718" y="227" width="104" height="18" rx="3"/><text class="dd-lbl" x="770" y="240" text-anchor="middle">Distribution Main</text><text class="dd-sub" x="770" y="290" text-anchor="middle">6-16 inch diameter</text><rect class="dd-dz" data-id="dist_main" x="718" y="225" width="104" height="58" rx="6"/></g>
  <g class="dd-comp" data-id="gate_valve" role="button" aria-label="Gate Valve"><g class="dd-cb"><rect x="860" y="240" width="40" height="40" rx="4" fill="#fff" stroke="#f59e0b" stroke-width="2"/><line x1="868" y1="260" x2="892" y2="260" stroke="#f59e0b" stroke-width="2"/><polygon points="880,248 886,260 874,260" fill="#f59e0b"/><polygon points="880,272 886,260 874,260" fill="#f59e0b"/></g><rect class="dd-lbg" x="845" y="222" width="70" height="18" rx="3"/><text class="dd-lbl" x="880" y="235" text-anchor="middle">Gate Valve</text><rect class="dd-dz" data-id="gate_valve" x="843" y="220" width="74" height="64" rx="4"/></g>
  <g class="dd-comp" data-id="prv" role="button" aria-label="Pressure Reducing Valve PRV"><g class="dd-cb"><rect x="900" y="145" width="60" height="45" rx="6" fill="#fff" stroke="#f59e0b" stroke-width="2"/><circle cx="930" cy="165" r="12" fill="none" stroke="#f59e0b" stroke-width="1.5"/><path d="M 922,165 L 930,157 L 938,165" fill="none" stroke="#f59e0b" stroke-width="1.5"/><text class="dd-sub" x="930" y="182" text-anchor="middle" fill="#d97706">PSI</text></g><rect class="dd-lbg" x="898" y="127" width="64" height="18" rx="3"/><text class="dd-lbl" x="930" y="140" text-anchor="middle">PRV</text><rect class="dd-dz" data-id="prv" x="896" y="125" width="68" height="69" rx="6"/></g>
  <g class="dd-comp" data-id="hydrant" role="button" aria-label="Fire Hydrant"><g class="dd-cb"><rect x="1050" y="150" width="60" height="70" rx="6" fill="#fff" stroke="#ef4444" stroke-width="2"/><rect x="1070" y="165" width="20" height="35" rx="3" fill="#ef4444" opacity=".7"/><circle cx="1080" cy="175" r="5" fill="#fca5a5"/><rect x="1065" y="195" width="30" height="6" rx="2" fill="#ef4444" opacity=".7"/><line x1="1080" y1="200" x2="1080" y2="215" stroke="#94a3b8" stroke-width="2"/></g><rect class="dd-lbg" x="1047" y="132" width="66" height="18" rx="3"/><text class="dd-lbl" x="1080" y="145" text-anchor="middle">Fire Hydrant</text><rect class="dd-dz" data-id="hydrant" x="1045" y="130" width="70" height="94" rx="6"/></g>
  <g class="dd-comp" data-id="service" role="button" aria-label="Service Line and Meter"><g class="dd-cb"><rect x="1040" y="310" width="80" height="70" rx="6" fill="#fff" stroke="#22c55e" stroke-width="2"/><circle cx="1080" cy="335" r="12" fill="#f0fdf4" stroke="#22c55e" stroke-width="1.5"/><text x="1080" y="339" text-anchor="middle" fill="#16a34a" font-size="8" font-weight="bold">M</text><polygon points="1065,365 1080,355 1095,365" fill="#dcfce7" stroke="#22c55e" stroke-width="1"/><rect x="1070" y="365" width="20" height="12" fill="#fff" stroke="#e2e8f0" stroke-width="1"/></g><rect class="dd-lbg" x="1033" y="292" width="94" height="18" rx="3"/><text class="dd-lbl" x="1080" y="305" text-anchor="middle">Service Line &amp; Meter</text><rect class="dd-dz" data-id="service" x="1031" y="290" width="98" height="94" rx="6"/></g>
  <g class="dd-comp" data-id="backflow" role="button" aria-label="Backflow Prevention Device"><g class="dd-cb"><rect x="1040" y="430" width="80" height="55" rx="6" fill="#fff" stroke="#a78bfa" stroke-width="2"/><circle cx="1065" cy="455" r="10" fill="none" stroke="#a78bfa" stroke-width="1.5"/><path d="M 1060,455 L 1070,455" stroke="#a78bfa" stroke-width="2"/><circle cx="1095" cy="455" r="10" fill="none" stroke="#a78bfa" stroke-width="1.5"/><path d="M 1090,455 L 1100,455" stroke="#a78bfa" stroke-width="2"/><line x1="1075" y1="455" x2="1085" y2="455" stroke="#a78bfa" stroke-width="2"/></g><rect class="dd-lbg" x="1027" y="412" width="106" height="18" rx="3"/><text class="dd-lbl" x="1080" y="425" text-anchor="middle">Backflow Preventer</text><rect class="dd-dz" data-id="backflow" x="1025" y="410" width="110" height="79" rx="6"/></g>
  <g class="dd-comp" data-id="sampling" role="button" aria-label="Sampling Point"><g class="dd-cb"><rect x="770" y="380" width="60" height="50" rx="6" fill="#fff" stroke="#06b6d4" stroke-width="2"/><rect x="785" y="392" width="8" height="25" rx="2" fill="#06b6d4" opacity=".5"/><rect x="800" y="398" width="8" height="19" rx="2" fill="#06b6d4" opacity=".35"/><circle cx="800" cy="390" r="3" fill="#06b6d4"/></g><rect class="dd-lbg" x="757" y="362" width="86" height="18" rx="3"/><text class="dd-lbl" x="800" y="375" text-anchor="middle">Sampling Point</text><rect class="dd-dz" data-id="sampling" x="755" y="360" width="90" height="74" rx="6"/></g>
  <g class="dd-comp" data-id="deadend" role="button" aria-label="Dead End and Flushing Point"><g class="dd-cb"><rect x="880" y="380" width="60" height="50" rx="6" fill="#fff" stroke="#fb923c" stroke-width="2"/><line x1="895" y1="405" x2="925" y2="405" stroke="#fb923c" stroke-width="2"/><circle cx="925" cy="405" r="5" fill="none" stroke="#fb923c" stroke-width="2"/><path d="M 910,395 L 910,415" stroke="#fb923c" stroke-width="1" stroke-dasharray="3,2"/></g><rect class="dd-lbg" x="864" y="362" width="92" height="18" rx="3"/><text class="dd-lbl" x="910" y="375" text-anchor="middle">Dead End / Flushing</text><rect class="dd-dz" data-id="deadend" x="862" y="360" width="96" height="74" rx="6"/></g>
  <g class="dd-comp" data-id="air_valve" role="button" aria-label="Air Release Valve"><g class="dd-cb"><rect x="770" y="120" width="60" height="45" rx="6" fill="#fff" stroke="#84cc16" stroke-width="2"/><polygon points="800,130 810,155 790,155" fill="none" stroke="#84cc16" stroke-width="1.5"/><line x1="800" y1="128" x2="800" y2="118" stroke="#84cc16" stroke-width="1.5"/><circle cx="800" cy="115" r="3" fill="#84cc16"/></g><rect class="dd-lbg" x="753" y="102" width="94" height="18" rx="3"/><text class="dd-lbl" x="800" y="115" text-anchor="middle">Air Release Valve</text><rect class="dd-dz" data-id="air_valve" x="751" y="100" width="98" height="69" rx="6"/></g>
  <!-- Legend -->
  <rect x="20" y="442" width="240" height="70" rx="8" fill="#fff" stroke="#e2e8f0" stroke-width="1"/>
  <text x="35" y="461" fill="#64748b" font-size="10" font-weight="700" font-family="sans-serif">LEGEND</text>
  <line x1="35" y1="476" x2="55" y2="476" stroke="#3b82f6" stroke-width="2" stroke-dasharray="8,4"/>
  <text x="62" y="480" fill="#64748b" font-size="9" font-family="sans-serif">Water Flow</text>
  <line x1="130" y1="476" x2="150" y2="476" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="157" y="480" fill="#64748b" font-size="9" font-family="sans-serif">Branch Line</text>
  <rect x="35" y="489" width="12" height="12" rx="2" fill="none" stroke="#f59e0b" stroke-width="1.5"/>
  <text x="52" y="499" fill="#64748b" font-size="9" font-family="sans-serif">Valve / Control</text>
  <circle cx="136" cy="495" r="5" fill="none" stroke="#22c55e" stroke-width="1.5"/>
  <text x="146" y="499" fill="#64748b" font-size="9" font-family="sans-serif">Monitoring / Safety</text>
</svg>
</div>
<div class="dd-info" id="ddInfo" role="region" aria-label="Component information">
  <button class="dd-close" id="ddCloseBtn" aria-label="Close information panel">&times;</button>
  <h2 id="ddIT"></h2>
  <div class="dd-itype" id="ddITy"></div>
  <p id="ddID"></p>
  <div class="dd-tip" id="ddITip"></div>
</div>

<script>
(function(){
var C={
  treatment:{name:`Treatment Plant`,type:`Source / Supply`,desc:`The treatment plant is the origin of potable water in the distribution system. Water is treated through coagulation, flocculation, sedimentation, filtration, and disinfection before being pumped in. Operators ensure the plant delivers water meeting all primary and secondary standards.`,tip:`<strong>Exam Tip:</strong> Know the turbidity standard (less than 0.3 NTU combined filter effluent), minimum 0.2 mg/L free chlorine entering distribution, and total coliform limits.`},
  transmission:{name:`Transmission Main`,type:`Conveyance`,desc:`Transmission mains are large-diameter pipes (16 to 48 inch) that carry treated water from the plant to the distribution system. They operate at higher pressures and volumes than distribution mains and have few direct service connections.`,tip:`<strong>Exam Tip:</strong> Transmission mains move large volumes over long distances. Common materials: ductile iron, prestressed concrete, and steel. Know the difference between transmission mains and distribution mains for the exam.`},
  booster:{name:`Booster Station`,type:`Pressure Management`,desc:`Booster stations increase water pressure where gravity or system pressure is insufficient. They contain pumps, check valves, pressure gauges, and often SCADA monitoring. Critical for serving elevated areas or maintaining pressure at system extremities.`,tip:`<strong>Exam Tip:</strong> Normal distribution pressure is 35-85 psi. Minimum 20 psi per most state codes. Minimum pressure during fire flow is typically 20 psi.`},
  elevated:{name:`Elevated Storage Tank`,type:`Storage / Pressure`,desc:`Elevated tanks store finished water at height to maintain system pressure through gravity. The water surface elevation determines the hydraulic grade line. They provide equalizing storage for peak demand and emergency reserves.`,tip:`<strong>Exam Tip:</strong> Every 2.31 feet of elevation = 1 psi. A tank 100 ft above a service point provides about 43 psi. Tanks fill during low demand and drain during peak demand (equalizing storage).`},
  ground_storage:{name:`Ground-Level Storage`,type:`Storage`,desc:`Ground-level reservoirs and standpipes store large volumes of treated water at grade. They require booster pumps to pressurize the system (unlike elevated tanks). Used for fire reserves, emergency supply, and peak demand equalization.`,tip:`<strong>Exam Tip:</strong> Ground storage requires booster pumps. Storage volume must cover fire flow reserves, peak demand, and emergency supply. Typical fire storage reserve: 2-4 hours of fire flow.`},
  dist_main:{name:`Distribution Main`,type:`Conveyance`,desc:`Distribution mains are the pipe network (6 to 16 inch) delivering water throughout the service area. They form loops (preferred) or branches with service connections, hydrants, and valves. Minimum size for fire protection is typically 6 inch residential or 8 inch commercial.`,tip:`<strong>Exam Tip:</strong> Looped systems are preferred: more uniform pressure, fewer water quality issues, redundant flow paths. C-factor (Hazen-Williams) decreases as pipes age and tuberculate.`},
  gate_valve:{name:`Gate Valve`,type:`Flow Control`,desc:`Gate valves are the most common valve in distribution systems, used to isolate pipe sections. A wedge-shaped gate moves up and down to open or close. They must be operated fully open or fully closed — never used for throttling.`,tip:`<strong>Exam Tip:</strong> Gate valves must NOT be used for throttling. Valve spacing: no more than 500 ft of main shut down for a repair. Turns to close: about 3 turns per inch of valve diameter.`},
  prv:{name:`PRV`,type:`Pressure Management`,desc:`Pressure Reducing Valves (PRVs) automatically reduce higher inlet pressure to a lower, steady outlet pressure. Installed where mains transition from high-pressure to lower-pressure zones. They protect piping, fixtures, and appliances from excessive pressure.`,tip:`<strong>Exam Tip:</strong> PRVs are essential where pressure exceeds 80 psi. They only reduce pressure, never increase it. A failed PRV causes high-pressure complaints downstream.`},
  hydrant:{name:`Fire Hydrant`,type:`Fire Protection / Flushing`,desc:`Fire hydrants provide emergency water supply for firefighting and are used for system flushing, testing, and sampling. Dry-barrel hydrants drain automatically in freezing climates. Wet-barrel hydrants have water up to the outlets in warm climates.`,tip:`<strong>Exam Tip:</strong> Know dry-barrel vs. wet-barrel hydrants. NFPA color coding: Blue over 1500 GPM, Green 1000-1499, Orange 500-999, Red under 500 GPM. Use a pitot gauge during flow testing.`},
  service:{name:`Service Line & Meter`,type:`Customer Connection`,desc:`Service lines connect the distribution main to customer premises. A corporation stop connects at the main, a curb stop allows shutoff at the property line, and the meter measures consumption. Most residential services are 3/4 or 1 inch.`,tip:`<strong>Exam Tip:</strong> Order: corporation stop at main → service line → curb stop → meter → customer plumbing. The utility owns everything up to and including the meter. Lead and Copper Rule action level: 15 ppb.`},
  backflow:{name:`Backflow Preventer`,type:`Cross-Connection Control`,desc:`Backflow prevention devices protect the potable water supply from contamination caused by reverse flow. Backflow occurs via backpressure or backsiphonage. Types include RPBA, DCVA, AVB, and PVB for different hazard levels.`,tip:`<strong>Exam Tip:</strong> Air gap is the most reliable method. RPBA for high-hazard. DCVA (Double Check) for low-hazard. Devices must be tested annually by a certified tester. Know backpressure vs. backsiphonage!`},
  sampling:{name:`Sampling Point`,type:`Water Quality Monitoring`,desc:`Designated locations throughout the distribution system where water samples are collected for regulatory compliance. Samples are tested for disinfectant residual, coliform bacteria, DBPs, lead and copper, and other parameters.`,tip:`<strong>Exam Tip:</strong> Total Coliform Rule requires routine sampling based on population. Minimum 0.2 mg/L chlorine residual throughout the system. Dead ends and low-flow areas are common problem spots.`},
  deadend:{name:`Dead End / Flushing`,type:`System Maintenance`,desc:`Dead ends occur where mains terminate without looping. Water can stagnate at dead ends, causing low disinfectant residuals, taste and odor complaints, and discolored water. Flushing points are installed at dead ends to enable regular unidirectional flushing that maintains water quality.`,tip:`<strong>Exam Tip:</strong> Dead ends are the #1 cause of water quality complaints in distribution. Flushing velocity should be at least 2.5 ft/s to scour sediment. Always discharge to a dechlorination point — never directly to storm drains.`},
  air_valve:{name:`Air Release Valve`,type:`System Protection`,desc:`Air release valves automatically vent trapped air from pipelines at high points. Trapped air reduces pipe capacity, causes pressure surges (water hammer), and leads to inaccurate meter readings. Combination valves handle large volumes during filling and small pockets during operation.`,tip:`<strong>Exam Tip:</strong> Install ARVs at high points. Combination air valves release small pockets during operation AND large volumes during filling. Water hammer is caused by rapid changes in flow velocity.`}
};

var mode='explore', qOrder=[], qIdx=0, qCorrect=0, qWrong=0, qActive=false;
var dragSel=null, dragPlaced=0, dragWrong=0, showingAnswers=false;
var wrap=document.getElementById('opp-dist-diagram');
var totalComps=Object.keys(C).length;

/* ---- MODE SWITCHER ---- */
function setMode(m){
  mode=m;
  var cls=wrap.className.split(' ').filter(function(c){return c.indexOf('mode-')!==0;});
  cls.push('mode-'+m); wrap.className=cls.join(' ');
  ['ddBtn0','ddBtn1','ddBtn2'].forEach(function(id,i){
    var btn=document.getElementById(id);
    var active=(i===(['explore','quiz','drag'].indexOf(m)));
    btn.classList.toggle('active',active);
    btn.setAttribute('aria-selected',active?'true':'false');
  });
  document.getElementById('ddInfo').classList.remove('visible');
  document.getElementById('ddComplete').classList.remove('visible');
  document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected','correct-flash','wrong-flash');});

  var isQuiz=(m==='quiz'), isDrag=(m==='drag');
  document.getElementById('ddQuizBanner').classList.toggle('visible',isQuiz);
  document.getElementById('ddDragBanner').classList.toggle('visible',isDrag);
  document.getElementById('ddDragPanel').classList.toggle('visible',isDrag);
  document.getElementById('ddDragControls').classList.toggle('visible',isDrag);

  /* Labels: show in explore, hide in drag always, hide in quiz only after started */
  var hideLbls=(isDrag||(isQuiz&&qActive));
  document.querySelectorAll('.dd-lbl,.dd-lbg,.dd-sub').forEach(function(e){e.style.display=hideLbls?'none':'';});

  if(isQuiz && !qActive){
    /* Reset quiz UI to pre-start state */
    document.getElementById('ddQBQ').textContent='Ready to test your knowledge?';
    document.getElementById('ddQBCounter').textContent='';
    document.getElementById('ddProgressBar').style.display='none';
    document.getElementById('ddQBFB').textContent=''; document.getElementById('ddQBFB').className='dd-qb-feedback';
    document.getElementById('ddQBSc').textContent='';
    document.getElementById('ddStartBtn').textContent='Start Quiz';
    document.getElementById('ddStartBtn').style.display='';
    document.getElementById('ddQBIntro').style.display='';
  }
  if(isDrag) setupDrag();
}

/* ---- EXPLORE INFO ---- */
function showInfo(id){
  var c=C[id]; if(!c)return;
  document.getElementById('ddIT').textContent=c.name;
  document.getElementById('ddITy').textContent=c.type;
  document.getElementById('ddID').textContent=c.desc;
  document.getElementById('ddITip').innerHTML=c.tip;
  document.getElementById('ddInfo').classList.add('visible');
}

/* ---- QUIZ ---- */
function startQuiz(){
  qOrder=Object.keys(C).sort(function(){return Math.random()-0.5;});
  qIdx=0; qCorrect=0; qWrong=0; qActive=true;
  /* Hide labels now that quiz has started */
  document.querySelectorAll('.dd-lbl,.dd-lbg,.dd-sub').forEach(function(e){e.style.display='none';});
  document.getElementById('ddStartBtn').style.display='none';
  document.getElementById('ddQBIntro').style.display='none';
  document.getElementById('ddProgressBar').style.display='block';
  document.getElementById('ddComplete').classList.remove('visible');
  nextQ();
}

function nextQ(){
  if(qIdx>=qOrder.length){ endQuiz(); return; }
  var name=C[qOrder[qIdx]].name;
  document.getElementById('ddQBQ').textContent='Click on: '+name;
  document.getElementById('ddQBCounter').textContent='Question '+(qIdx+1)+' of '+qOrder.length;
  var pct=Math.round((qIdx/qOrder.length)*100);
  document.getElementById('ddProgressFill').style.width=pct+'%';
  document.getElementById('ddQBFB').textContent='';
  document.getElementById('ddQBSc').textContent='Correct: '+qCorrect+' · Wrong: '+qWrong;
}

function checkAnswer(id,el){
  if(!qActive||qIdx>=qOrder.length)return;
  var exp=qOrder[qIdx]; qIdx++;
  var fb=document.getElementById('ddQBFB');
  if(id===exp){
    qCorrect++;
    fb.textContent='✓ Correct!'; fb.className='dd-qb-feedback correct';
    el.classList.add('correct-flash'); setTimeout(function(){el.classList.remove('correct-flash');},900);
  } else {
    qWrong++;
    fb.textContent='✗ That’s '+C[id].name+'. The answer was: '+C[exp].name;
    fb.className='dd-qb-feedback wrong';
    el.classList.add('wrong-flash');
    var ce=document.querySelector('.dd-comp[data-id="'+exp+'"]');
    if(ce){ce.classList.add('correct-flash');setTimeout(function(){ce.classList.remove('correct-flash');},1400);}
    setTimeout(function(){el.classList.remove('wrong-flash');},900);
  }
  document.getElementById('ddQBSc').textContent='Correct: '+qCorrect+' · Wrong: '+qWrong;
  setTimeout(nextQ,1800);
}

function endQuiz(){
  qActive=false;
  document.getElementById('ddProgressFill').style.width='100%';
  document.getElementById('ddQuizBanner').classList.remove('visible');
  /* Show completion screen */
  var pct=Math.round((qCorrect/qOrder.length)*100);
  document.getElementById('ddCompScore').textContent=qCorrect+' / '+qOrder.length+' ('+pct+'%)';
  var title, msg;
  if(pct===100){title='Perfect Score! 🎉';msg='Excellent! You identified every component correctly. You’re ready for the exam!';}
  else if(pct>=70){title='Great Job! 💪';msg='You got '+qCorrect+' out of '+qOrder.length+' correct. Review the ones you missed in Explore mode.';}
  else{title='Keep Practicing 📚';msg='You got '+qCorrect+' out of '+qOrder.length+' correct. Use Explore mode to study the components, then try again.';}
  document.getElementById('ddCompTitle').textContent=title;
  document.getElementById('ddCompMsg').textContent=msg;
  document.getElementById('ddComplete').classList.add('visible');
  /* Restore labels */
  document.querySelectorAll('.dd-lbl,.dd-lbg,.dd-sub').forEach(function(e){e.style.display='';});
}

/* ---- DRAG & DROP ---- */
function setupDrag(){
  dragSel=null; dragPlaced=0; dragWrong=0; showingAnswers=false;
  document.getElementById('ddShowAnsBtn').textContent='👁 Show Answers';
  document.getElementById('ddShowAnsBtn').classList.remove('active');
  /* Reset drop zones */
  document.querySelectorAll('.dd-dz').forEach(function(dz){dz.classList.remove('filled');});
  /* Reset labels that may have been revealed */
  document.querySelectorAll('.dd-lbl,.dd-lbg').forEach(function(e){e.style.display='none';});
  /* Sub labels (diameter) stay hidden in drag mode */
  document.querySelectorAll('.dd-sub').forEach(function(e){e.style.display='none';});
  var ids=Object.keys(C).sort(function(){return Math.random()-0.5;});
  var panel=document.getElementById('ddDragPanel'); panel.innerHTML='';
  ids.forEach(function(id){
    var chip=document.createElement('div');
    chip.className='dd-chip'; chip.textContent=C[id].name;
    chip.setAttribute('data-id',id); chip.setAttribute('draggable','true');
    chip.setAttribute('tabindex','0'); chip.setAttribute('role','option');
    chip.setAttribute('aria-label','Label: '+C[id].name);
    chip.addEventListener('dragstart',function(e){
      if(chip.classList.contains('done')){e.preventDefault();return;}
      e.dataTransfer.setData('text/plain',id);
      e.dataTransfer.effectAllowed='move';
      chip.classList.add('dragging'); dragSel=id;
    });
    chip.addEventListener('dragend',function(){chip.classList.remove('dragging');});
    chip.addEventListener('click',function(e){
      e.stopPropagation();
      if(chip.classList.contains('done'))return;
      document.querySelectorAll('.dd-chip').forEach(function(b){b.classList.remove('on');});
      chip.classList.add('on'); dragSel=id;
      document.getElementById('ddDragInst').textContent='Now click the matching component in the diagram:';
      document.getElementById('ddDragSel').textContent='▶ '+C[id].name;
    });
    chip.addEventListener('keydown',function(e){
      if(e.key==='Enter'||e.key===' '){e.preventDefault();chip.click();}
    });
    panel.appendChild(chip);
  });
  updateDragScore();
  document.getElementById('ddDragInst').textContent='Drag a label onto its matching component — or tap a label, then tap the component.';
  document.getElementById('ddDragSel').textContent='';
  document.getElementById('ddDragFB').textContent='';
}

function updateDragScore(){
  var total=Object.keys(C).length;
  var rem=total-dragPlaced;
  document.getElementById('ddDCorr').textContent=dragPlaced;
  document.getElementById('ddDWrong').textContent=dragWrong;
  document.getElementById('ddDRem').textContent=rem;
  var pct=Math.round((dragPlaced/total)*100);
  document.getElementById('ddDragFill').style.width=pct+'%';
}

function placeDrop(dragId,targetId,targetEl){
  var chip=document.querySelector('.dd-chip[data-id="'+dragId+'"]');
  if(dragId===targetId){
    dragPlaced++;
    if(chip){chip.classList.remove('on','dragging','wrong-chip');chip.classList.add('done');}
    var dz=document.querySelector('.dd-dz[data-id="'+targetId+'"]');
    if(dz)dz.classList.add('filled');
    /* Reveal label on placed component */
    var cg=document.querySelector('.dd-comp[data-id="'+targetId+'"]');
    if(cg)cg.querySelectorAll('.dd-lbl,.dd-lbg').forEach(function(e){e.style.display='';});
    targetEl.classList.add('correct-flash');setTimeout(function(){targetEl.classList.remove('correct-flash');},900);
    document.getElementById('ddDragFB').textContent='';
    updateDragScore();
    if(dragPlaced>=Object.keys(C).length){
      document.getElementById('ddDragInst').textContent='🎉 All '+Object.keys(C).length+' components placed correctly!';
      document.getElementById('ddDragSel').textContent='';
    } else {
      document.getElementById('ddDragInst').textContent='Drag a label onto its matching component — or tap a label, then tap the component.';
      document.getElementById('ddDragSel').textContent='';
    }
    dragSel=null;
  } else {
    /* Wrong placement */
    dragWrong++;
    targetEl.classList.add('wrong-flash');setTimeout(function(){targetEl.classList.remove('wrong-flash');},700);
    if(chip){
      chip.classList.remove('on');
      chip.classList.add('wrong-chip');
      setTimeout(function(){chip.classList.remove('wrong-chip');},500);
    }
    var hint='That’s the '+C[targetId].name+'. Hint: '+C[dragId].name+' is categorized as “'+C[dragId].type+'”.';
    document.getElementById('ddDragFB').textContent=hint;
    updateDragScore();
    dragSel=null;
    document.querySelectorAll('.dd-chip').forEach(function(b){b.classList.remove('on');});
    document.getElementById('ddDragSel').textContent='';
    document.getElementById('ddDragInst').textContent='Drag a label onto its matching component — or tap a label, then tap the component.';
  }
}

function toggleShowAnswers(){
  showingAnswers=!showingAnswers;
  var btn=document.getElementById('ddShowAnsBtn');
  btn.textContent=showingAnswers?'👁 Hide Answers':'👁 Show Answers';
  btn.classList.toggle('active',showingAnswers);
  document.querySelectorAll('.dd-comp').forEach(function(comp){
    var id=comp.getAttribute('data-id');
    if(!document.querySelector('.dd-dz[data-id="'+id+'"].filled')){
      comp.querySelectorAll('.dd-lbl,.dd-lbg').forEach(function(e){
        e.style.display=showingAnswers?'':'none';
      });
    }
  });
}

/* ---- BUTTON WIRING ---- */
document.getElementById('ddBtn0').addEventListener('click',function(){setMode('explore');});
document.getElementById('ddBtn1').addEventListener('click',function(){setMode('quiz');});
document.getElementById('ddBtn2').addEventListener('click',function(){setMode('drag');});
document.getElementById('ddStartBtn').addEventListener('click',function(){startQuiz();});
document.getElementById('ddRetryBtn').addEventListener('click',function(){
  document.getElementById('ddComplete').classList.remove('visible');
  qActive=false;
  setMode('quiz');
  setTimeout(function(){startQuiz();},100);
});
document.getElementById('ddExploreBtn').addEventListener('click',function(){setMode('explore');});
document.getElementById('ddCloseBtn').addEventListener('click',function(){
  document.getElementById('ddInfo').classList.remove('visible');
  document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected');});
});
document.getElementById('ddResetBtn').addEventListener('click',function(){setupDrag();});
document.getElementById('ddShowAnsBtn').addEventListener('click',function(){toggleShowAnswers();});
document.getElementById('ddHowtoBtn').addEventListener('click',function(){
  var box=document.getElementById('ddHowtoBox');
  var open=box.classList.toggle('open');
  document.getElementById('ddHowtoBtn').setAttribute('aria-expanded',open?'true':'false');
});

/* ---- SVG EVENT DELEGATION ---- */
var svgWrap=document.querySelector('.dd-svg-wrap');

function findComp(target,root){
  var el=target;
  while(el && el!==root){
    if(el.classList && el.classList.contains('dd-comp'))return el;
    el=el.parentElement;
  }
  return null;
}

svgWrap.addEventListener('click',function(e){
  var comp=findComp(e.target,this); if(!comp)return;
  var id=comp.getAttribute('data-id');
  if(mode==='explore'){
    showInfo(id);
    document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected');});
    comp.classList.add('selected');
  } else if(mode==='quiz' && qActive){
    checkAnswer(id,comp);
  } else if(mode==='drag' && dragSel){
    placeDrop(dragSel,id,comp);
  }
});

svgWrap.addEventListener('dragover',function(e){
  var comp=findComp(e.target,this);
  if(comp && !comp.querySelector('.dd-dz.filled')){
    e.preventDefault();
    e.dataTransfer.dropEffect='move';
    document.querySelectorAll('.dd-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
    comp.classList.add('drag-over');
  }
});
svgWrap.addEventListener('dragleave',function(e){
  if(!svgWrap.contains(e.relatedTarget)){
    document.querySelectorAll('.dd-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
  }
});
svgWrap.addEventListener('drop',function(e){
  e.preventDefault();
  document.querySelectorAll('.dd-comp.drag-over').forEach(function(c){c.classList.remove('drag-over');});
  var comp=findComp(e.target,this); if(!comp)return;
  var dragId=e.dataTransfer.getData('text/plain');
  if(dragId) placeDrop(dragId,comp.getAttribute('data-id'),comp);
});

})();
</script>
</div>
<!-- /wp:html -->
ENDHTML;

wp_update_post(array('ID' => $pid, 'post_content' => $content));
echo "UPDATED: distribution diagram v3 (ID $pid)" . PHP_EOL;
wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
