<?php
/**
 * Redesign Distribution Diagram — white background, fixed quiz + drag & drop
 * Page ID 1094 (/d1-d2-distribution-diagram/)
 */
echo "Updating distribution diagram page..." . PHP_EOL;

$pid = 1094;
$post = get_post($pid);
if (!$post) { echo "ERROR: page not found" . PHP_EOL; exit; }

$content = <<<'ENDHTML'
<!-- wp:html -->
<!-- D1 D2 Distribution System Diagram - Operator Prep -->
<style>.entry-header.ast-no-thumbnail { display: none !important; }</style>
<div id="opp-dist-diagram">
<style>
#opp-dist-diagram { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #ffffff; color: #1e293b; max-width: 1200px; margin: 0 auto; padding: 24px; box-sizing: border-box; }
#opp-dist-diagram *, #opp-dist-diagram *::before, #opp-dist-diagram *::after { box-sizing: border-box; }
.dd-header { text-align: center; margin-bottom: 20px; }
.dd-header h1 { font-size: 1.7em; color: #0f172a; margin: 0 0 4px; font-weight: 700; }
.dd-header p { color: #64748b; font-size: 0.95em; margin: 0; }
.dd-modes { display: flex; justify-content: center; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }
.dd-mode-btn { background: #f8fafc; color: #64748b; border: 1.5px solid #e2e8f0; padding: 8px 20px; border-radius: 8px; cursor: pointer; font-size: 0.9em; font-weight: 500; transition: all 0.18s; }
.dd-mode-btn:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
.dd-mode-btn.active { background: #1e40af; color: #fff; border-color: #1e40af; box-shadow: 0 2px 8px rgba(30,64,175,0.25); }
.dd-quiz-banner { display: none; background: linear-gradient(135deg, #1e40af, #2563eb); color: #fff; border-radius: 10px; padding: 14px 20px; margin-bottom: 12px; text-align: center; }
.dd-quiz-banner.visible { display: block; animation: ddFadeIn 0.25s ease; }
.dd-quiz-banner .dd-qb-q { font-size: 1.05em; font-weight: 600; margin-bottom: 6px; }
.dd-quiz-banner .dd-qb-row { display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 8px; flex-wrap: wrap; }
.dd-quiz-banner .dd-qb-feedback { font-size: 0.95em; font-weight: 600; min-height: 1.3em; }
.dd-quiz-banner .dd-qb-feedback.correct { color: #86efac; }
.dd-quiz-banner .dd-qb-feedback.wrong { color: #fca5a5; }
.dd-quiz-banner .dd-qb-score { font-size: 0.85em; color: rgba(255,255,255,0.8); }
.dd-quiz-banner .dd-quiz-start { background: rgba(255,255,255,0.2); color: #fff; border: 1.5px solid rgba(255,255,255,0.4); padding: 8px 22px; border-radius: 7px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.18s; margin-top: 8px; }
.dd-quiz-banner .dd-quiz-start:hover { background: rgba(255,255,255,0.3); }
.dd-drag-banner { display: none; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 20px; margin-bottom: 12px; text-align: center; color: #475569; font-size: 0.9em; }
.dd-drag-banner.visible { display: block; animation: ddFadeIn 0.25s ease; }
.dd-drag-banner .dd-db-selected { font-weight: 700; color: #1e40af; font-size: 1em; }
.dd-drag-banner .dd-db-score { color: #64748b; font-size: 0.85em; margin-top: 4px; }
.dd-drag-banner .dd-db-score span { color: #16a34a; font-weight: 600; }
.dd-svg-wrap { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
.dd-svg-wrap svg { display: block; width: 100%; height: auto; }
.dd-comp { cursor: pointer; }
.dd-comp .dd-comp-body { transition: filter 0.15s; }
.dd-comp:hover .dd-comp-body { filter: drop-shadow(0 0 5px rgba(37,99,235,0.35)); }
.dd-comp.selected .dd-comp-body { filter: drop-shadow(0 0 8px rgba(37,99,235,0.6)); }
.dd-comp.correct-flash .dd-comp-body { filter: drop-shadow(0 0 10px rgba(22,163,74,0.9)); }
.dd-comp.wrong-flash .dd-comp-body { filter: drop-shadow(0 0 10px rgba(220,38,38,0.85)); }
.dd-drop-zone { fill: transparent; stroke: #f59e0b; stroke-width: 2.5; stroke-dasharray: 6,3; opacity: 0; pointer-events: none; }
#opp-dist-diagram.mode-drag .dd-drop-zone { opacity: 1; animation: ddPulse 1.8s infinite; pointer-events: all; cursor: pointer; }
#opp-dist-diagram.mode-drag .dd-drop-zone.filled { stroke: #16a34a; opacity: 0.7; stroke-dasharray: none; animation: none; }
@keyframes ddPulse { 0%,100% { opacity: 0.4; } 50% { opacity: 1; } }
.dd-info { background: #fff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-top: 14px; display: none; box-shadow: 0 4px 16px rgba(0,0,0,0.07); position: relative; }
.dd-info.visible { display: block; animation: ddFadeIn 0.25s ease; }
@keyframes ddFadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.dd-info h2 { color: #1e40af; font-size: 1.2em; margin: 0 0 4px; }
.dd-info .dd-info-type { color: #d97706; font-size: 0.82em; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; }
.dd-info p { color: #334155; line-height: 1.65; margin: 0 0 10px; font-size: 0.95em; }
.dd-info .dd-exam-tip { background: #fffbeb; border-left: 3px solid #f59e0b; padding: 10px 14px; border-radius: 0 8px 8px 0; margin-top: 10px; font-size: 0.9em; color: #44403c; }
.dd-info .dd-exam-tip strong { color: #92400e; }
.dd-info .dd-close { position: absolute; top: 14px; right: 16px; background: #f1f5f9; border: none; color: #64748b; font-size: 1.1em; cursor: pointer; padding: 4px 8px; border-radius: 6px; line-height: 1; }
.dd-info .dd-close:hover { background: #e2e8f0; color: #1e293b; }
.dd-drag-panel { display: none; flex-wrap: wrap; gap: 8px; justify-content: center; padding: 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-top: 12px; }
.dd-drag-panel.visible { display: flex; }
.dd-drag-chip { background: #fff; color: #334155; padding: 7px 14px; border-radius: 8px; cursor: pointer; font-size: 0.85em; font-weight: 500; border: 1.5px solid #e2e8f0; user-select: none; transition: all 0.18s; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.dd-drag-chip:hover { border-color: #94a3b8; background: #f1f5f9; }
.dd-drag-chip.active-chip { background: #1e40af; color: #fff; border-color: #1e40af; box-shadow: 0 2px 8px rgba(30,64,175,0.3); }
.dd-drag-chip.placed { background: #f0fdf4; color: #16a34a; border-color: #86efac; cursor: default; opacity: 0.75; }
.dd-label { font-family: 'Segoe UI', system-ui, sans-serif; fill: #1e293b; font-size: 11px; font-weight: 600; pointer-events: none; }
.dd-label-bg { fill: #fff; stroke: #e2e8f0; stroke-width: 1; }
.dd-sublabel { font-family: 'Segoe UI', system-ui, sans-serif; fill: #64748b; font-size: 9px; pointer-events: none; }
.dd-flow-arrow { fill: none; stroke: #3b82f6; stroke-width: 2; stroke-dasharray: 8,4; opacity: 0.7; }
@media (max-width: 768px) { #opp-dist-diagram { padding: 12px; } .dd-header h1 { font-size: 1.3em; } .dd-mode-btn { padding: 7px 12px; font-size: 0.82em; } .dd-label { font-size: 8.5px; } }
</style>

<div class="dd-header">
  <h1>Water Distribution System &mdash; Interactive Diagram</h1>
  <p>D1 &amp; D2 &mdash; Key Distribution System Components</p>
</div>
<div class="dd-modes">
  <button class="dd-mode-btn active" onclick="ddSetMode('explore')">&#x1F50D; Explore</button>
  <button class="dd-mode-btn" onclick="ddSetMode('quiz')">&#x1F4DD; Quiz Mode</button>
  <button class="dd-mode-btn" onclick="ddSetMode('drag')">&#x1F3AF; Drag &amp; Drop</button>
</div>
<div class="dd-quiz-banner" id="ddQuizBanner">
  <div class="dd-qb-q" id="ddQBQuestion">Test your knowledge of distribution system components.</div>
  <div class="dd-qb-row">
    <div class="dd-qb-feedback" id="ddQBFeedback"></div>
    <div class="dd-qb-score" id="ddQBScore"></div>
  </div>
  <button class="dd-quiz-start" id="ddQuizBtn" onclick="ddStartQuiz()">Start Quiz</button>
</div>
<div class="dd-drag-banner" id="ddDragBanner">
  <div id="ddDragInstruct">Select a component name below, then click it in the diagram.</div>
  <div class="dd-db-selected" id="ddDragSelected"></div>
  <div class="dd-db-score">Placed: <span id="ddDragPlaced">0</span> / <span id="ddDragTotal">0</span></div>
</div>
<div class="dd-svg-wrap">
<svg viewBox="0 0 1200 520" xmlns="http://www.w3.org/2000/svg">
  <defs><marker id="ddArrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#3b82f6"/></marker></defs>
  <rect x="0" y="0" width="1200" height="520" fill="#f8fafc"/>
  <rect x="0" y="350" width="1200" height="170" fill="#e8edf4" opacity="0.55"/>
  <line x1="0" y1="350" x2="1200" y2="350" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="6,4"/>
  <text x="20" y="370" fill="#94a3b8" font-size="10" font-family="sans-serif" font-style="italic">Underground</text>
  <path d="M 135,260 L 220,260" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 320,260 L 400,260" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 480,260 L 540,200" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 480,260 L 540,380" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 640,200 L 720,260" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 640,380 L 720,260" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 820,260 L 900,260" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 1000,260 L 1060,180" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 1000,260 L 1060,340" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <path d="M 1000,260 L 1060,460" class="dd-flow-arrow" marker-end="url(#ddArrow)"/>
  <g class="dd-comp" data-id="treatment" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="30" y="210" width="100" height="100" rx="8" fill="#ffffff" stroke="#3b82f6" stroke-width="2"/><rect x="45" y="225" width="70" height="50" rx="4" fill="#dbeafe"/><rect x="55" y="232" width="12" height="36" rx="2" fill="#3b82f6" opacity="0.5"/><rect x="73" y="240" width="12" height="28" rx="2" fill="#3b82f6" opacity="0.35"/><rect x="91" y="236" width="12" height="32" rx="2" fill="#3b82f6" opacity="0.45"/><circle cx="80" cy="295" r="6" fill="#22c55e"/><text x="80" y="298" text-anchor="middle" fill="#fff" font-size="7" font-weight="bold">OK</text></g><rect class="dd-label-bg" x="32" y="195" width="96" height="18" rx="3"/><text class="dd-label" x="80" y="208" text-anchor="middle">Treatment Plant</text><rect class="dd-drop-zone" data-id="treatment" x="28" y="193" width="104" height="122" rx="8"/></g>
  <g class="dd-comp" data-id="transmission" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="220" y="245" width="100" height="30" rx="6" fill="#eff6ff" stroke="#3b82f6" stroke-width="1.5"/><line x1="235" y1="260" x2="305" y2="260" stroke="#3b82f6" stroke-width="3" stroke-dasharray="10,5"/><circle cx="250" cy="260" r="4" fill="#93c5fd"/><circle cx="280" cy="260" r="4" fill="#93c5fd"/></g><rect class="dd-label-bg" x="222" y="227" width="96" height="18" rx="3"/><text class="dd-label" x="270" y="240" text-anchor="middle">Transmission Main</text><text class="dd-sublabel" x="270" y="290" text-anchor="middle">16&#x22;-48&#x22; diameter</text><rect class="dd-drop-zone" data-id="transmission" x="218" y="225" width="104" height="58" rx="6"/></g>
  <g class="dd-comp" data-id="booster" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="400" y="220" width="80" height="80" rx="8" fill="#ffffff" stroke="#8b5cf6" stroke-width="2"/><circle cx="440" cy="255" r="20" fill="#ede9fe" stroke="#8b5cf6" stroke-width="1.5"/><path d="M 428,255 L 440,243 L 452,255 L 440,267 Z" fill="#8b5cf6" opacity="0.8"/><circle cx="440" cy="255" r="6" fill="#a78bfa"/></g><rect class="dd-label-bg" x="397" y="202" width="86" height="18" rx="3"/><text class="dd-label" x="440" y="215" text-anchor="middle">Booster Station</text><rect class="dd-drop-zone" data-id="booster" x="398" y="200" width="84" height="104" rx="8"/></g>
  <g class="dd-comp" data-id="elevated" onclick="ddClickComp(this)"><g class="dd-comp-body"><line x1="590" y1="200" x2="590" y2="130" stroke="#94a3b8" stroke-width="3"/><rect x="555" y="80" width="70" height="50" rx="6" fill="#ffffff" stroke="#38bdf8" stroke-width="2"/><path d="M 555,80 Q 590,65 625,80" fill="#ffffff" stroke="#38bdf8" stroke-width="2"/><rect x="558" y="95" width="64" height="32" rx="3" fill="#bae6fd" opacity="0.7"/><text class="dd-sublabel" x="590" y="115" text-anchor="middle" fill="#0284c7">75%</text></g><rect class="dd-label-bg" x="543" y="55" width="94" height="18" rx="3"/><text class="dd-label" x="590" y="68" text-anchor="middle">Elevated Tank</text><rect class="dd-drop-zone" data-id="elevated" x="541" y="53" width="98" height="160" rx="6"/></g>
  <g class="dd-comp" data-id="ground_storage" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="545" y="360" width="90" height="60" rx="8" fill="#ffffff" stroke="#38bdf8" stroke-width="2"/><rect x="550" y="380" width="80" height="35" rx="4" fill="#bae6fd" opacity="0.5"/><line x1="555" y1="390" x2="625" y2="390" stroke="#38bdf8" stroke-width="1" opacity="0.6"/><line x1="555" y1="400" x2="625" y2="400" stroke="#38bdf8" stroke-width="1" opacity="0.4"/></g><rect class="dd-label-bg" x="543" y="342" width="94" height="18" rx="3"/><text class="dd-label" x="590" y="355" text-anchor="middle">Ground Storage</text><rect class="dd-drop-zone" data-id="ground_storage" x="541" y="340" width="98" height="84" rx="8"/></g>
  <g class="dd-comp" data-id="dist_main" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="720" y="245" width="100" height="30" rx="6" fill="#eff6ff" stroke="#2563eb" stroke-width="1.5"/><line x1="735" y1="260" x2="805" y2="260" stroke="#2563eb" stroke-width="3"/><circle cx="755" cy="260" r="3" fill="#93c5fd"/><circle cx="780" cy="260" r="3" fill="#93c5fd"/></g><rect class="dd-label-bg" x="723" y="227" width="94" height="18" rx="3"/><text class="dd-label" x="770" y="240" text-anchor="middle">Distribution Main</text><text class="dd-sublabel" x="770" y="290" text-anchor="middle">6&#x22;-16&#x22; diameter</text><rect class="dd-drop-zone" data-id="dist_main" x="718" y="225" width="104" height="58" rx="6"/></g>
  <g class="dd-comp" data-id="gate_valve" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="860" y="240" width="40" height="40" rx="4" fill="#ffffff" stroke="#f59e0b" stroke-width="2"/><line x1="868" y1="260" x2="892" y2="260" stroke="#f59e0b" stroke-width="2"/><polygon points="880,248 886,260 874,260" fill="#f59e0b"/><polygon points="880,272 886,260 874,260" fill="#f59e0b"/></g><rect class="dd-label-bg" x="845" y="222" width="70" height="18" rx="3"/><text class="dd-label" x="880" y="235" text-anchor="middle">Gate Valve</text><rect class="dd-drop-zone" data-id="gate_valve" x="843" y="220" width="74" height="64" rx="4"/></g>
  <g class="dd-comp" data-id="prv" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="900" y="145" width="60" height="45" rx="6" fill="#ffffff" stroke="#f59e0b" stroke-width="2"/><circle cx="930" cy="165" r="12" fill="none" stroke="#f59e0b" stroke-width="1.5"/><path d="M 922,165 L 930,157 L 938,165" fill="none" stroke="#f59e0b" stroke-width="1.5"/><text class="dd-sublabel" x="930" y="182" text-anchor="middle" fill="#d97706">PSI</text></g><rect class="dd-label-bg" x="900" y="127" width="60" height="18" rx="3"/><text class="dd-label" x="930" y="140" text-anchor="middle">PRV</text><rect class="dd-drop-zone" data-id="prv" x="898" y="125" width="64" height="69" rx="6"/></g>
  <g class="dd-comp" data-id="hydrant" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="1050" y="150" width="60" height="70" rx="6" fill="#ffffff" stroke="#ef4444" stroke-width="2"/><rect x="1070" y="165" width="20" height="35" rx="3" fill="#ef4444" opacity="0.7"/><circle cx="1080" cy="175" r="5" fill="#fca5a5"/><rect x="1065" y="195" width="30" height="6" rx="2" fill="#ef4444" opacity="0.7"/><line x1="1080" y1="200" x2="1080" y2="215" stroke="#94a3b8" stroke-width="2"/></g><rect class="dd-label-bg" x="1047" y="132" width="66" height="18" rx="3"/><text class="dd-label" x="1080" y="145" text-anchor="middle">Fire Hydrant</text><rect class="dd-drop-zone" data-id="hydrant" x="1045" y="130" width="70" height="94" rx="6"/></g>
  <g class="dd-comp" data-id="service" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="1040" y="310" width="80" height="70" rx="6" fill="#ffffff" stroke="#22c55e" stroke-width="2"/><circle cx="1080" cy="335" r="12" fill="#f0fdf4" stroke="#22c55e" stroke-width="1.5"/><text x="1080" y="339" text-anchor="middle" fill="#16a34a" font-size="8" font-weight="bold">M</text><polygon points="1065,365 1080,355 1095,365" fill="#dcfce7" stroke="#22c55e" stroke-width="1"/><rect x="1070" y="365" width="20" height="12" fill="#ffffff" stroke="#e2e8f0" stroke-width="1"/></g><rect class="dd-label-bg" x="1037" y="292" width="86" height="18" rx="3"/><text class="dd-label" x="1080" y="305" text-anchor="middle">Service / Meter</text><rect class="dd-drop-zone" data-id="service" x="1035" y="290" width="90" height="94" rx="6"/></g>
  <g class="dd-comp" data-id="backflow" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="1040" y="430" width="80" height="55" rx="6" fill="#ffffff" stroke="#a78bfa" stroke-width="2"/><circle cx="1065" cy="455" r="10" fill="none" stroke="#a78bfa" stroke-width="1.5"/><path d="M 1060,455 L 1070,455" stroke="#a78bfa" stroke-width="2"/><circle cx="1095" cy="455" r="10" fill="none" stroke="#a78bfa" stroke-width="1.5"/><path d="M 1090,455 L 1100,455" stroke="#a78bfa" stroke-width="2"/><line x1="1075" y1="455" x2="1085" y2="455" stroke="#a78bfa" stroke-width="2"/></g><rect class="dd-label-bg" x="1033" y="412" width="94" height="18" rx="3"/><text class="dd-label" x="1080" y="425" text-anchor="middle">Backflow Device</text><rect class="dd-drop-zone" data-id="backflow" x="1031" y="410" width="98" height="79" rx="6"/></g>
  <g class="dd-comp" data-id="sampling" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="770" y="380" width="60" height="50" rx="6" fill="#ffffff" stroke="#06b6d4" stroke-width="2"/><rect x="785" y="392" width="8" height="25" rx="2" fill="#06b6d4" opacity="0.5"/><rect x="800" y="398" width="8" height="19" rx="2" fill="#06b6d4" opacity="0.35"/><circle cx="800" cy="390" r="3" fill="#06b6d4"/></g><rect class="dd-label-bg" x="757" y="362" width="86" height="18" rx="3"/><text class="dd-label" x="800" y="375" text-anchor="middle">Sampling Point</text><rect class="dd-drop-zone" data-id="sampling" x="755" y="360" width="90" height="74" rx="6"/></g>
  <g class="dd-comp" data-id="deadend" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="880" y="380" width="60" height="50" rx="6" fill="#ffffff" stroke="#fb923c" stroke-width="2"/><line x1="895" y1="405" x2="925" y2="405" stroke="#fb923c" stroke-width="2"/><circle cx="925" cy="405" r="5" fill="none" stroke="#fb923c" stroke-width="2"/><path d="M 910,395 L 910,415" stroke="#fb923c" stroke-width="1" stroke-dasharray="3,2"/></g><rect class="dd-label-bg" x="870" y="362" width="80" height="18" rx="3"/><text class="dd-label" x="910" y="375" text-anchor="middle">Dead End</text><rect class="dd-drop-zone" data-id="deadend" x="868" y="360" width="84" height="74" rx="6"/></g>
  <g class="dd-comp" data-id="air_valve" onclick="ddClickComp(this)"><g class="dd-comp-body"><rect x="770" y="120" width="60" height="45" rx="6" fill="#ffffff" stroke="#84cc16" stroke-width="2"/><polygon points="800,130 810,155 790,155" fill="none" stroke="#84cc16" stroke-width="1.5"/><line x1="800" y1="128" x2="800" y2="118" stroke="#84cc16" stroke-width="1.5"/><circle cx="800" cy="115" r="3" fill="#84cc16"/></g><rect class="dd-label-bg" x="753" y="102" width="94" height="18" rx="3"/><text class="dd-label" x="800" y="115" text-anchor="middle">Air Release Valve</text><rect class="dd-drop-zone" data-id="air_valve" x="751" y="100" width="98" height="69" rx="6"/></g>
  <path d="M 770,260 L 770,380" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 770,260 L 770,120" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 820,260 L 880,260 L 880,380" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 880,260 L 930,190" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <rect x="20" y="440" width="210" height="70" rx="8" fill="#fff" stroke="#e2e8f0" stroke-width="1"/>
  <text x="35" y="460" fill="#64748b" font-size="10" font-weight="700" font-family="sans-serif">LEGEND</text>
  <line x1="35" y1="475" x2="55" y2="475" stroke="#3b82f6" stroke-width="2" stroke-dasharray="8,4"/>
  <text x="62" y="479" fill="#64748b" font-size="9" font-family="sans-serif">Water Flow</text>
  <line x1="120" y1="475" x2="140" y2="475" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="147" y="479" fill="#64748b" font-size="9" font-family="sans-serif">Branch Line</text>
  <rect x="35" y="488" width="12" height="12" rx="2" fill="none" stroke="#f59e0b" stroke-width="1.5"/>
  <text x="52" y="498" fill="#64748b" font-size="9" font-family="sans-serif">Valve / Control</text>
  <circle cx="126" cy="494" r="5" fill="none" stroke="#22c55e" stroke-width="1.5"/>
  <text x="136" y="498" fill="#64748b" font-size="9" font-family="sans-serif">Monitoring</text>
</svg>
</div>
<div class="dd-info" id="ddInfo">
  <button class="dd-close" onclick="ddCloseInfo()">&times;</button>
  <h2 id="ddInfoTitle"></h2>
  <div class="dd-info-type" id="ddInfoType"></div>
  <p id="ddInfoDesc"></p>
  <div class="dd-exam-tip" id="ddInfoTip"></div>
</div>
<div class="dd-drag-panel" id="ddDragPanel"></div>
<script>
(function(){
var comps={treatment:{name:"Water Treatment Plant",type:"Source / Supply",desc:"The treatment plant is the origin of potable water entering the distribution system. Water has been treated through coagulation, flocculation, sedimentation, filtration, and disinfection. Operators must ensure the plant delivers water meeting all primary and secondary drinking water standards.",tip:"<strong>Exam Tip:</strong> Treated water must meet EPA/state standards for turbidity (&lt;0.3 NTU combined filter effluent), disinfectant residual (min 0.2 mg/L free chlorine entering distribution), and total coliform."},transmission:{name:"Transmission Main",type:"Conveyance",desc:"Transmission mains are large-diameter pipes (typically 16\"-48\") that carry treated water from the treatment plant to the distribution system. They operate at higher pressures and volumes than distribution mains and usually have few or no direct service connections.",tip:"<strong>Exam Tip:</strong> Transmission mains differ from distribution mains in size and purpose. They move large volumes over distance. Know the difference between a transmission main and a distribution main for the exam."},booster:{name:"Booster Pump Station",type:"Pressure Management",desc:"Booster stations increase water pressure in areas where gravity flow or system pressure is insufficient. They contain pumps, check valves, pressure gauges, and often SCADA monitoring. Critical for serving elevated areas or maintaining adequate pressure at system extremities.",tip:"<strong>Exam Tip:</strong> Normal distribution system pressure should be 35-85 psi (minimum 20 psi per most state codes). Minimum pressure during fire flow is typically 20 psi."},elevated:{name:"Elevated Storage Tank",type:"Storage / Pressure",desc:"Elevated tanks store finished water at height to maintain system pressure through gravity. The water surface elevation determines the hydraulic grade line and pressure. They provide equalizing storage for peak demand periods and emergency reserves.",tip:"<strong>Exam Tip:</strong> Every 2.31 feet of elevation = 1 psi of pressure. A tank 100 ft above a service point provides ~43.3 psi. Elevated tanks fill during low demand and drain during peak demand (equalizing storage)."},ground_storage:{name:"Ground-Level Storage",type:"Storage",desc:"Ground-level reservoirs and standpipes store large volumes of treated water at ground level. They require pumps to push water into the distribution system (unlike elevated tanks which use gravity). Used for fire reserves, emergency supply, and peak demand equalization.",tip:"<strong>Exam Tip:</strong> Ground storage requires booster pumps to deliver water at adequate pressure. Storage volume must account for fire flow reserves, peak demand equalization, and emergency supply. Typical fire storage: 2-4 hours of fire flow."},dist_main:{name:"Distribution Main",type:"Conveyance",desc:"Distribution mains are the network of pipes (typically 6\"-16\") that deliver water throughout the service area. They form loops (preferred) or branches and have service connections, hydrants, and valves. Minimum for fire protection: 6\" residential, 8\" commercial.",tip:"<strong>Exam Tip:</strong> Looped systems are preferred over dead-end: (1) more uniform pressure, (2) fewer water quality issues, (3) redundant flow paths. C-factor (Hazen-Williams) decreases as pipes age and tuberculate."},gate_valve:{name:"Gate Valve",type:"Flow Control",desc:"Gate valves are the most common valve in distribution systems used to isolate pipe sections for maintenance. They use a wedge-shaped gate that moves up/down to open/close. They should be operated fully open or fully closed — not for throttling.",tip:"<strong>Exam Tip:</strong> Gate valves should NOT be used for throttling. Valve spacing: no more than 500 feet of main shut down for a repair. Turns to close: ~3 turns per inch of valve diameter (8\" valve ≈ 24 turns)."},prv:{name:"Pressure Reducing Valve (PRV)",type:"Pressure Management",desc:"PRVs automatically reduce higher inlet pressure to a lower, steady outlet pressure. Installed where mains transition from high-pressure to lower-pressure zones. They protect piping, fixtures, and appliances from excessive pressure.",tip:"<strong>Exam Tip:</strong> PRVs are essential where pressure exceeds 80 psi. They only reduce pressure — they cannot increase it. A failed PRV causes high-pressure complaints downstream."},hydrant:{name:"Fire Hydrant",type:"Fire Protection / Flushing",desc:"Fire hydrants provide emergency water supply for firefighting and are also used for system flushing, testing, and sampling. Dry-barrel hydrants drain automatically (freezing climates). Wet-barrel hydrants have water up to the outlets (warm climates only).",tip:"<strong>Exam Tip:</strong> Know dry-barrel vs. wet-barrel hydrants. NFPA color coding: Blue >1500 GPM, Green 1000-1499, Orange 500-999, Red <500 GPM. Use a pitot gauge during hydrant flow testing."},service:{name:"Service Line & Water Meter",type:"Customer Connection",desc:"Service lines connect the distribution main to customer premises. A corporation stop connects at the main, a curb stop allows shutoff at the property line, and the meter measures consumption for billing. Most residential services are 3/4\" or 1\".",tip:"<strong>Exam Tip:</strong> Order: corporation stop → service line → curb stop → meter → customer plumbing. The utility owns everything up to and including the meter. Lead and Copper Rule action level: 15 ppb for lead."},backflow:{name:"Backflow Prevention Device",type:"Cross-Connection Control",desc:"Backflow preventers protect the potable water supply from contamination caused by reverse flow. Backflow occurs via backpressure or backsiphonage. Types include RPBA, DCVA, AVB, and PVB — each for different hazard levels.",tip:"<strong>Exam Tip:</strong> Air gap is the most reliable method. RPBA (Reduced Pressure) for high-hazard. DCVA (Double Check) for low-hazard. Devices must be tested annually by a certified tester. Know backpressure vs. backsiphonage!"},sampling:{name:"Sampling Point",type:"Water Quality Monitoring",desc:"Designated locations throughout the distribution system where water samples are collected for regulatory compliance. Samples are tested for disinfectant residual, coliform bacteria, DBPs, lead/copper, and other parameters per the monitoring schedule.",tip:"<strong>Exam Tip:</strong> Total Coliform Rule requires routine sampling based on population served. Minimum 0.2 mg/L chlorine residual must be detectable throughout the system. Dead ends and low-flow areas are common problem spots."},deadend:{name:"Dead End / Flushing Point",type:"System Maintenance",desc:"Dead ends occur where mains terminate without looping. Water can stagnate, leading to low disinfectant residuals, taste/odor complaints, and discolored water. Regular flushing programs (unidirectional flushing preferred) maintain water quality.",tip:"<strong>Exam Tip:</strong> Dead ends are the #1 cause of water quality complaints in distribution. Flushing velocity should be at least 2.5 ft/s to scour sediment. Always flush to a dechlorination point before discharging to storm drains."},air_valve:{name:"Air Release Valve",type:"System Protection",desc:"Air release valves automatically vent trapped air from pipelines at high points. Trapped air can reduce pipe capacity, cause pressure surges (water hammer), and lead to inaccurate meter readings.",tip:"<strong>Exam Tip:</strong> Install ARVs at high points in the system. Combination air valves release small pockets during operation AND large volumes during filling. Water hammer is caused by rapid changes in flow velocity."}};

var currentMode='explore',quizOrder=[],quizIdx=0,quizCorrect=0,quizActive=false,dragSelected=null,dragPlaced=0;
var wrap=document.getElementById('opp-dist-diagram');

window.ddClickComp=function(el){
  var id=el.getAttribute('data-id');
  if(currentMode==='explore'){ddShowInfo(id);document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected');});el.classList.add('selected');}
  else if(currentMode==='quiz'&&quizActive){ddCheckAnswer(id,el);}
  else if(currentMode==='drag'){ddHandleDrop(id,el);}
};
function ddShowInfo(id){var c=comps[id];if(!c)return;document.getElementById('ddInfoTitle').textContent=c.name;document.getElementById('ddInfoType').textContent=c.type;document.getElementById('ddInfoDesc').textContent=c.desc;document.getElementById('ddInfoTip').innerHTML=c.tip;document.getElementById('ddInfo').classList.add('visible');}
window.ddCloseInfo=function(){document.getElementById('ddInfo').classList.remove('visible');document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected');});};
window.ddSetMode=function(mode){
  currentMode=mode;
  wrap.className=wrap.className.replace(/\bmode-\S+/g,'').trim();wrap.classList.add('mode-'+mode);
  document.querySelectorAll('.dd-mode-btn').forEach(function(b,i){b.classList.toggle('active',['explore','quiz','drag'][i]===mode);});
  document.getElementById('ddInfo').classList.remove('visible');
  document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected','correct-flash','wrong-flash');});
  document.getElementById('ddQuizBanner').classList.toggle('visible',mode==='quiz');
  document.getElementById('ddDragBanner').classList.toggle('visible',mode==='drag');
  document.getElementById('ddDragPanel').classList.toggle('visible',mode==='drag');
  document.querySelectorAll('.dd-label,.dd-label-bg,.dd-sublabel').forEach(function(e){e.style.display=(mode==='drag')?'none':'';});
  if(mode==='quiz'){quizActive=false;document.getElementById('ddQBQuestion').textContent='Test your knowledge of distribution system components.';document.getElementById('ddQBFeedback').textContent='';document.getElementById('ddQBFeedback').className='dd-qb-feedback';document.getElementById('ddQBScore').textContent='';document.getElementById('ddQuizBtn').textContent='Start Quiz';document.getElementById('ddQuizBtn').style.display='';}
  if(mode==='drag')ddSetupDrag();
};
window.ddStartQuiz=function(){
  quizOrder=Object.keys(comps).sort(function(){return Math.random()-0.5;});
  quizIdx=0;quizCorrect=0;quizActive=true;
  document.getElementById('ddQuizBtn').style.display='none';
  document.getElementById('ddQBFeedback').textContent='';
  document.getElementById('ddQBScore').textContent='Score: 0 / '+quizOrder.length;
  ddNextQ();
};
function ddNextQ(){
  if(quizIdx>=quizOrder.length){quizActive=false;document.getElementById('ddQBQuestion').textContent='Quiz complete! Score: '+quizCorrect+' / '+quizOrder.length;document.getElementById('ddQBFeedback').textContent=quizCorrect===quizOrder.length?'Perfect!':quizCorrect>=Math.floor(quizOrder.length*0.7)?'Great job!':'Keep practicing!';document.getElementById('ddQBFeedback').className='dd-qb-feedback '+(quizCorrect>=Math.floor(quizOrder.length*0.7)?'correct':'wrong');document.getElementById('ddQuizBtn').textContent='Restart Quiz';document.getElementById('ddQuizBtn').style.display='';return;}
  document.getElementById('ddQBQuestion').textContent='Click on: '+comps[quizOrder[quizIdx]].name;
  document.getElementById('ddQBFeedback').textContent='';
}
function ddCheckAnswer(id,el){
  if(!quizActive||quizIdx>=quizOrder.length)return;
  var exp=quizOrder[quizIdx],fb=document.getElementById('ddQBFeedback');
  quizIdx++;
  if(id===exp){quizCorrect++;fb.textContent='Correct!';fb.className='dd-qb-feedback correct';el.classList.add('correct-flash');setTimeout(function(){el.classList.remove('correct-flash');},900);}
  else{fb.textContent='That was '+comps[id].name+' — answer: '+comps[exp].name;fb.className='dd-qb-feedback wrong';el.classList.add('wrong-flash');var ce=document.querySelector('.dd-comp[data-id="'+exp+'"]');if(ce){ce.classList.add('correct-flash');setTimeout(function(){ce.classList.remove('correct-flash');},1200);}setTimeout(function(){el.classList.remove('wrong-flash');},900);}
  document.getElementById('ddQBScore').textContent='Score: '+quizCorrect+' / '+quizOrder.length;
  setTimeout(ddNextQ,1600);
}
function ddSetupDrag(){
  dragSelected=null;dragPlaced=0;
  var ids=Object.keys(comps).sort(function(){return Math.random()-0.5;});
  var panel=document.getElementById('ddDragPanel');panel.innerHTML='';
  ids.forEach(function(id){var btn=document.createElement('button');btn.className='dd-drag-chip';btn.textContent=comps[id].name;btn.setAttribute('data-id',id);btn.addEventListener('click',function(e){e.stopPropagation();ddSelectChip(id,btn);});panel.appendChild(btn);});
  document.getElementById('ddDragPlaced').textContent='0';document.getElementById('ddDragTotal').textContent=ids.length;
  document.getElementById('ddDragInstruct').textContent='Select a component name below, then click it in the diagram.';document.getElementById('ddDragSelected').textContent='';
}
function ddSelectChip(id,btn){
  if(btn.classList.contains('placed'))return;
  document.querySelectorAll('.dd-drag-chip').forEach(function(b){b.classList.remove('active-chip');});
  btn.classList.add('active-chip');dragSelected=id;
  document.getElementById('ddDragInstruct').textContent='Now click in the diagram:';
  document.getElementById('ddDragSelected').textContent='➤ '+comps[id].name;
}
function ddHandleDrop(targetId,el){
  if(!dragSelected){ddShowInfo(targetId);return;}
  if(dragSelected===targetId){
    dragPlaced++;document.getElementById('ddDragPlaced').textContent=dragPlaced;
    var chip=document.querySelector('.dd-drag-chip[data-id="'+dragSelected+'"]');if(chip){chip.classList.remove('active-chip');chip.classList.add('placed');}
    var dz=document.querySelector('.dd-drop-zone[data-id="'+targetId+'"]');if(dz){dz.classList.add('filled');}
    var cg=document.querySelector('.dd-comp[data-id="'+targetId+'"]');if(cg){cg.querySelectorAll('.dd-label,.dd-label-bg').forEach(function(e){e.style.display='';});}
    el.classList.add('correct-flash');setTimeout(function(){el.classList.remove('correct-flash');},900);
    var tot=parseInt(document.getElementById('ddDragTotal').textContent,10);
    if(dragPlaced>=tot){document.getElementById('ddDragInstruct').textContent='All components placed!';document.getElementById('ddDragSelected').textContent='';}
    else{document.getElementById('ddDragInstruct').textContent='Select a component name below, then click it in the diagram.';document.getElementById('ddDragSelected').textContent='';}
    dragSelected=null;
  }else{el.classList.add('wrong-flash');setTimeout(function(){el.classList.remove('wrong-flash');},700);}
}
})();
<\/script>
</div>
<!-- /wp:html -->
ENDHTML;

wp_update_post(array('ID' => $pid, 'post_content' => $content));
echo "UPDATED: distribution diagram page (ID $pid)" . PHP_EOL;
wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
