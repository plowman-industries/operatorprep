<?php
/**
 * Redesign Distribution Diagram — white bg, fix quiz + drag & drop
 * Uses backtick JS strings to avoid WordPress backslash-stripping bug
 * Page ID 1094 (/d1-d2-distribution-diagram/)
 */
echo "Updating distribution diagram..." . PHP_EOL;

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
.dd-modes{display:flex;justify-content:center;gap:8px;margin-bottom:16px;flex-wrap:wrap}
.dd-mode-btn{background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:500;transition:all .18s}
.dd-mode-btn:hover{background:#f1f5f9;color:#1e293b;border-color:#cbd5e1}
.dd-mode-btn.active{background:#1e40af;color:#fff;border-color:#1e40af;box-shadow:0 2px 8px rgba(30,64,175,.25)}
.dd-quiz-banner{display:none;background:linear-gradient(135deg,#1e40af,#2563eb);color:#fff;border-radius:10px;padding:14px 20px;margin-bottom:12px;text-align:center}
.dd-quiz-banner.visible{display:block;animation:ddFadeIn .25s ease}
.dd-qb-q{font-size:1.05em;font-weight:600;margin-bottom:6px}
.dd-qb-row{display:flex;justify-content:center;align-items:center;gap:16px;margin-top:8px;flex-wrap:wrap}
.dd-qb-feedback{font-size:.95em;font-weight:600;min-height:1.3em}
.dd-qb-feedback.correct{color:#86efac}
.dd-qb-feedback.wrong{color:#fca5a5}
.dd-qb-score{font-size:.85em;color:rgba(255,255,255,.8)}
.dd-quiz-start{background:rgba(255,255,255,.2);color:#fff;border:1.5px solid rgba(255,255,255,.4);padding:8px 22px;border-radius:7px;font-size:.9em;font-weight:600;cursor:pointer;transition:all .18s;margin-top:8px}
.dd-quiz-start:hover{background:rgba(255,255,255,.3)}
.dd-drag-banner{display:none;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px 20px;margin-bottom:12px;text-align:center;color:#475569;font-size:.9em}
.dd-drag-banner.visible{display:block;animation:ddFadeIn .25s ease}
.dd-db-selected{font-weight:700;color:#1e40af;font-size:1em}
.dd-db-score{color:#64748b;font-size:.85em;margin-top:4px}
.dd-db-score span{color:#16a34a;font-weight:600}
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
.dd-drag-panel{display:none;flex-wrap:wrap;gap:8px;justify-content:center;padding:16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;margin-top:12px}
.dd-drag-panel.visible{display:flex}
.dd-chip{background:#fff;color:#334155;padding:7px 14px;border-radius:8px;cursor:grab;font-size:.85em;font-weight:500;border:1.5px solid #e2e8f0;user-select:none;transition:all .18s;box-shadow:0 1px 3px rgba(0,0,0,.05)}
.dd-chip:hover{border-color:#94a3b8;background:#f1f5f9}
.dd-chip.on{background:#1e40af;color:#fff;border-color:#1e40af;box-shadow:0 2px 8px rgba(30,64,175,.3);cursor:grabbing}
.dd-chip.dragging{opacity:.4;cursor:grabbing}
.dd-chip.done{background:#f0fdf4;color:#16a34a;border-color:#86efac;cursor:default;opacity:.75}
.dd-comp.drag-over .dd-cb{filter:drop-shadow(0 0 8px rgba(249,115,22,.7))}
.dd-lbl{font-family:'Segoe UI',system-ui,sans-serif;fill:#1e293b;font-size:11px;font-weight:600;pointer-events:none}
.dd-lbg{fill:#fff;stroke:#e2e8f0;stroke-width:1}
.dd-sub{font-family:'Segoe UI',system-ui,sans-serif;fill:#64748b;font-size:9px;pointer-events:none}
.dd-arrow{fill:none;stroke:#3b82f6;stroke-width:2;stroke-dasharray:8,4;opacity:.7}
@media(max-width:768px){#opp-dist-diagram{padding:12px}.dd-header h1{font-size:1.3em}.dd-mode-btn{padding:7px 12px;font-size:.82em}.dd-lbl{font-size:8.5px}}
</style>

<div class="dd-header">
  <h1>Water Distribution System &mdash; Interactive Diagram</h1>
  <p>D1 &amp; D2 &mdash; Key Distribution System Components</p>
</div>
<div class="dd-modes">
  <button class="dd-mode-btn active" id="ddBtn0">&#x1F50D; Explore</button>
  <button class="dd-mode-btn" id="ddBtn1">&#x1F4DD; Quiz Mode</button>
  <button class="dd-mode-btn" id="ddBtn2">&#x1F3AF; Drag &amp; Drop</button>
</div>
<div class="dd-quiz-banner" id="ddQuizBanner">
  <div class="dd-qb-q" id="ddQBQ">Test your knowledge of distribution system components.</div>
  <div class="dd-qb-row">
    <div class="dd-qb-feedback" id="ddQBFB"></div>
    <div class="dd-qb-score" id="ddQBSc"></div>
  </div>
  <button class="dd-quiz-start" id="ddStartBtn">Start Quiz</button>
</div>
<div class="dd-drag-banner" id="ddDragBanner">
  <div id="ddDragInst">Select a component name below, then click it in the diagram.</div>
  <div class="dd-db-selected" id="ddDragSel"></div>
  <div class="dd-db-score">Placed: <span id="ddDP">0</span> / <span id="ddDT">0</span></div>
</div>
<div class="dd-drag-panel" id="ddDragPanel"></div>
<div class="dd-svg-wrap">
<svg viewBox="0 0 1200 520" xmlns="http://www.w3.org/2000/svg">
  <defs><marker id="ddarr" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#3b82f6"/></marker></defs>
  <rect x="0" y="0" width="1200" height="520" fill="#f8fafc"/>
  <rect x="0" y="350" width="1200" height="170" fill="#e8edf4" opacity=".55"/>
  <line x1="0" y1="350" x2="1200" y2="350" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="6,4"/>
  <text x="20" y="370" fill="#94a3b8" font-size="10" font-family="sans-serif" font-style="italic">Underground</text>
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
  <g class="dd-comp" data-id="treatment"><g class="dd-cb"><rect x="30" y="210" width="100" height="100" rx="8" fill="#fff" stroke="#3b82f6" stroke-width="2"/><rect x="45" y="225" width="70" height="50" rx="4" fill="#dbeafe"/><rect x="55" y="232" width="12" height="36" rx="2" fill="#3b82f6" opacity=".5"/><rect x="73" y="240" width="12" height="28" rx="2" fill="#3b82f6" opacity=".35"/><rect x="91" y="236" width="12" height="32" rx="2" fill="#3b82f6" opacity=".45"/><circle cx="80" cy="295" r="6" fill="#22c55e"/><text x="80" y="298" text-anchor="middle" fill="#fff" font-size="7" font-weight="bold">OK</text></g><rect class="dd-lbg" x="32" y="195" width="96" height="18" rx="3"/><text class="dd-lbl" x="80" y="208" text-anchor="middle">Treatment Plant</text><rect class="dd-dz" data-id="treatment" x="28" y="193" width="104" height="122" rx="8"/></g>
  <g class="dd-comp" data-id="transmission"><g class="dd-cb"><rect x="220" y="245" width="100" height="30" rx="6" fill="#eff6ff" stroke="#3b82f6" stroke-width="1.5"/><line x1="235" y1="260" x2="305" y2="260" stroke="#3b82f6" stroke-width="3" stroke-dasharray="10,5"/><circle cx="250" cy="260" r="4" fill="#93c5fd"/><circle cx="280" cy="260" r="4" fill="#93c5fd"/></g><rect class="dd-lbg" x="222" y="227" width="96" height="18" rx="3"/><text class="dd-lbl" x="270" y="240" text-anchor="middle">Transmission Main</text><text class="dd-sub" x="270" y="290" text-anchor="middle">16-48 inch diameter</text><rect class="dd-dz" data-id="transmission" x="218" y="225" width="104" height="58" rx="6"/></g>
  <g class="dd-comp" data-id="booster"><g class="dd-cb"><rect x="400" y="220" width="80" height="80" rx="8" fill="#fff" stroke="#8b5cf6" stroke-width="2"/><circle cx="440" cy="255" r="20" fill="#ede9fe" stroke="#8b5cf6" stroke-width="1.5"/><path d="M 428,255 L 440,243 L 452,255 L 440,267 Z" fill="#8b5cf6" opacity=".8"/><circle cx="440" cy="255" r="6" fill="#a78bfa"/></g><rect class="dd-lbg" x="397" y="202" width="86" height="18" rx="3"/><text class="dd-lbl" x="440" y="215" text-anchor="middle">Booster Station</text><rect class="dd-dz" data-id="booster" x="398" y="200" width="84" height="104" rx="8"/></g>
  <g class="dd-comp" data-id="elevated"><g class="dd-cb"><line x1="590" y1="200" x2="590" y2="130" stroke="#94a3b8" stroke-width="3"/><rect x="555" y="80" width="70" height="50" rx="6" fill="#fff" stroke="#38bdf8" stroke-width="2"/><path d="M 555,80 Q 590,65 625,80" fill="#fff" stroke="#38bdf8" stroke-width="2"/><rect x="558" y="95" width="64" height="32" rx="3" fill="#bae6fd" opacity=".7"/><text class="dd-sub" x="590" y="115" text-anchor="middle" fill="#0284c7">75%</text></g><rect class="dd-lbg" x="543" y="55" width="94" height="18" rx="3"/><text class="dd-lbl" x="590" y="68" text-anchor="middle">Elevated Tank</text><rect class="dd-dz" data-id="elevated" x="541" y="53" width="98" height="160" rx="6"/></g>
  <g class="dd-comp" data-id="ground_storage"><g class="dd-cb"><rect x="545" y="360" width="90" height="60" rx="8" fill="#fff" stroke="#38bdf8" stroke-width="2"/><rect x="550" y="380" width="80" height="35" rx="4" fill="#bae6fd" opacity=".5"/><line x1="555" y1="390" x2="625" y2="390" stroke="#38bdf8" stroke-width="1" opacity=".6"/><line x1="555" y1="400" x2="625" y2="400" stroke="#38bdf8" stroke-width="1" opacity=".4"/></g><rect class="dd-lbg" x="543" y="342" width="94" height="18" rx="3"/><text class="dd-lbl" x="590" y="355" text-anchor="middle">Ground Storage</text><rect class="dd-dz" data-id="ground_storage" x="541" y="340" width="98" height="84" rx="8"/></g>
  <g class="dd-comp" data-id="dist_main"><g class="dd-cb"><rect x="720" y="245" width="100" height="30" rx="6" fill="#eff6ff" stroke="#2563eb" stroke-width="1.5"/><line x1="735" y1="260" x2="805" y2="260" stroke="#2563eb" stroke-width="3"/><circle cx="755" cy="260" r="3" fill="#93c5fd"/><circle cx="780" cy="260" r="3" fill="#93c5fd"/></g><rect class="dd-lbg" x="723" y="227" width="94" height="18" rx="3"/><text class="dd-lbl" x="770" y="240" text-anchor="middle">Distribution Main</text><text class="dd-sub" x="770" y="290" text-anchor="middle">6-16 inch diameter</text><rect class="dd-dz" data-id="dist_main" x="718" y="225" width="104" height="58" rx="6"/></g>
  <g class="dd-comp" data-id="gate_valve"><g class="dd-cb"><rect x="860" y="240" width="40" height="40" rx="4" fill="#fff" stroke="#f59e0b" stroke-width="2"/><line x1="868" y1="260" x2="892" y2="260" stroke="#f59e0b" stroke-width="2"/><polygon points="880,248 886,260 874,260" fill="#f59e0b"/><polygon points="880,272 886,260 874,260" fill="#f59e0b"/></g><rect class="dd-lbg" x="845" y="222" width="70" height="18" rx="3"/><text class="dd-lbl" x="880" y="235" text-anchor="middle">Gate Valve</text><rect class="dd-dz" data-id="gate_valve" x="843" y="220" width="74" height="64" rx="4"/></g>
  <g class="dd-comp" data-id="prv"><g class="dd-cb"><rect x="900" y="145" width="60" height="45" rx="6" fill="#fff" stroke="#f59e0b" stroke-width="2"/><circle cx="930" cy="165" r="12" fill="none" stroke="#f59e0b" stroke-width="1.5"/><path d="M 922,165 L 930,157 L 938,165" fill="none" stroke="#f59e0b" stroke-width="1.5"/><text class="dd-sub" x="930" y="182" text-anchor="middle" fill="#d97706">PSI</text></g><rect class="dd-lbg" x="900" y="127" width="60" height="18" rx="3"/><text class="dd-lbl" x="930" y="140" text-anchor="middle">PRV</text><rect class="dd-dz" data-id="prv" x="898" y="125" width="64" height="69" rx="6"/></g>
  <g class="dd-comp" data-id="hydrant"><g class="dd-cb"><rect x="1050" y="150" width="60" height="70" rx="6" fill="#fff" stroke="#ef4444" stroke-width="2"/><rect x="1070" y="165" width="20" height="35" rx="3" fill="#ef4444" opacity=".7"/><circle cx="1080" cy="175" r="5" fill="#fca5a5"/><rect x="1065" y="195" width="30" height="6" rx="2" fill="#ef4444" opacity=".7"/><line x1="1080" y1="200" x2="1080" y2="215" stroke="#94a3b8" stroke-width="2"/></g><rect class="dd-lbg" x="1047" y="132" width="66" height="18" rx="3"/><text class="dd-lbl" x="1080" y="145" text-anchor="middle">Fire Hydrant</text><rect class="dd-dz" data-id="hydrant" x="1045" y="130" width="70" height="94" rx="6"/></g>
  <g class="dd-comp" data-id="service"><g class="dd-cb"><rect x="1040" y="310" width="80" height="70" rx="6" fill="#fff" stroke="#22c55e" stroke-width="2"/><circle cx="1080" cy="335" r="12" fill="#f0fdf4" stroke="#22c55e" stroke-width="1.5"/><text x="1080" y="339" text-anchor="middle" fill="#16a34a" font-size="8" font-weight="bold">M</text><polygon points="1065,365 1080,355 1095,365" fill="#dcfce7" stroke="#22c55e" stroke-width="1"/><rect x="1070" y="365" width="20" height="12" fill="#fff" stroke="#e2e8f0" stroke-width="1"/></g><rect class="dd-lbg" x="1037" y="292" width="86" height="18" rx="3"/><text class="dd-lbl" x="1080" y="305" text-anchor="middle">Service / Meter</text><rect class="dd-dz" data-id="service" x="1035" y="290" width="90" height="94" rx="6"/></g>
  <g class="dd-comp" data-id="backflow"><g class="dd-cb"><rect x="1040" y="430" width="80" height="55" rx="6" fill="#fff" stroke="#a78bfa" stroke-width="2"/><circle cx="1065" cy="455" r="10" fill="none" stroke="#a78bfa" stroke-width="1.5"/><path d="M 1060,455 L 1070,455" stroke="#a78bfa" stroke-width="2"/><circle cx="1095" cy="455" r="10" fill="none" stroke="#a78bfa" stroke-width="1.5"/><path d="M 1090,455 L 1100,455" stroke="#a78bfa" stroke-width="2"/><line x1="1075" y1="455" x2="1085" y2="455" stroke="#a78bfa" stroke-width="2"/></g><rect class="dd-lbg" x="1033" y="412" width="94" height="18" rx="3"/><text class="dd-lbl" x="1080" y="425" text-anchor="middle">Backflow Device</text><rect class="dd-dz" data-id="backflow" x="1031" y="410" width="98" height="79" rx="6"/></g>
  <g class="dd-comp" data-id="sampling"><g class="dd-cb"><rect x="770" y="380" width="60" height="50" rx="6" fill="#fff" stroke="#06b6d4" stroke-width="2"/><rect x="785" y="392" width="8" height="25" rx="2" fill="#06b6d4" opacity=".5"/><rect x="800" y="398" width="8" height="19" rx="2" fill="#06b6d4" opacity=".35"/><circle cx="800" cy="390" r="3" fill="#06b6d4"/></g><rect class="dd-lbg" x="757" y="362" width="86" height="18" rx="3"/><text class="dd-lbl" x="800" y="375" text-anchor="middle">Sampling Point</text><rect class="dd-dz" data-id="sampling" x="755" y="360" width="90" height="74" rx="6"/></g>
  <g class="dd-comp" data-id="deadend"><g class="dd-cb"><rect x="880" y="380" width="60" height="50" rx="6" fill="#fff" stroke="#fb923c" stroke-width="2"/><line x1="895" y1="405" x2="925" y2="405" stroke="#fb923c" stroke-width="2"/><circle cx="925" cy="405" r="5" fill="none" stroke="#fb923c" stroke-width="2"/><path d="M 910,395 L 910,415" stroke="#fb923c" stroke-width="1" stroke-dasharray="3,2"/></g><rect class="dd-lbg" x="870" y="362" width="80" height="18" rx="3"/><text class="dd-lbl" x="910" y="375" text-anchor="middle">Dead End</text><rect class="dd-dz" data-id="deadend" x="868" y="360" width="84" height="74" rx="6"/></g>
  <g class="dd-comp" data-id="air_valve"><g class="dd-cb"><rect x="770" y="120" width="60" height="45" rx="6" fill="#fff" stroke="#84cc16" stroke-width="2"/><polygon points="800,130 810,155 790,155" fill="none" stroke="#84cc16" stroke-width="1.5"/><line x1="800" y1="128" x2="800" y2="118" stroke="#84cc16" stroke-width="1.5"/><circle cx="800" cy="115" r="3" fill="#84cc16"/></g><rect class="dd-lbg" x="753" y="102" width="94" height="18" rx="3"/><text class="dd-lbl" x="800" y="115" text-anchor="middle">Air Release Valve</text><rect class="dd-dz" data-id="air_valve" x="751" y="100" width="98" height="69" rx="6"/></g>
  <path d="M 770,260 L 770,380" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 770,260 L 770,120" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 820,260 L 880,260 L 880,380" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
  <path d="M 880,260 L 930,190" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,3"/>
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
  <button class="dd-close" id="ddCloseBtn">&times;</button>
  <h2 id="ddIT"></h2>
  <div class="dd-itype" id="ddITy"></div>
  <p id="ddID"></p>
  <div class="dd-tip" id="ddITip"></div>
</div>

<script>
(function(){
var C={
  treatment:{name:`Water Treatment Plant`,type:`Source / Supply`,desc:`The treatment plant is the origin of potable water in the distribution system. Water is treated through coagulation, flocculation, sedimentation, filtration, and disinfection before being pumped in. Operators ensure the plant delivers water meeting all primary and secondary standards.`,tip:`<strong>Exam Tip:</strong> Know the turbidity standard (less than 0.3 NTU combined filter effluent), minimum 0.2 mg/L free chlorine entering distribution, and total coliform limits.`},
  transmission:{name:`Transmission Main`,type:`Conveyance`,desc:`Transmission mains are large-diameter pipes (16 to 48 inch) that carry treated water from the plant to the distribution system. They operate at higher pressures and volumes than distribution mains and have few direct service connections.`,tip:`<strong>Exam Tip:</strong> Transmission mains move large volumes over long distances. Common materials: ductile iron, prestressed concrete, and steel. Know the difference between transmission mains and distribution mains for the exam.`},
  booster:{name:`Booster Pump Station`,type:`Pressure Management`,desc:`Booster stations increase water pressure where gravity or system pressure is insufficient. They contain pumps, check valves, pressure gauges, and often SCADA monitoring. Critical for serving elevated areas or maintaining pressure at system extremities.`,tip:`<strong>Exam Tip:</strong> Normal distribution pressure is 35-85 psi. Minimum 20 psi per most state codes. Minimum pressure during fire flow is typically 20 psi.`},
  elevated:{name:`Elevated Storage Tank`,type:`Storage / Pressure`,desc:`Elevated tanks store finished water at height to maintain system pressure through gravity. The water surface elevation determines the hydraulic grade line. They provide equalizing storage for peak demand and emergency reserves.`,tip:`<strong>Exam Tip:</strong> Every 2.31 feet of elevation = 1 psi. A tank 100 ft above a service point provides about 43 psi. Tanks fill during low demand and drain during peak demand (equalizing storage).`},
  ground_storage:{name:`Ground-Level Storage`,type:`Storage`,desc:`Ground-level reservoirs and standpipes store large volumes of treated water at grade. They require booster pumps to pressurize the system (unlike elevated tanks). Used for fire reserves, emergency supply, and peak demand equalization.`,tip:`<strong>Exam Tip:</strong> Ground storage requires booster pumps. Storage volume must cover fire flow reserves, peak demand, and emergency supply. Typical fire storage reserve: 2-4 hours of fire flow.`},
  dist_main:{name:`Distribution Main`,type:`Conveyance`,desc:`Distribution mains are the pipe network (6 to 16 inch) delivering water throughout the service area. They form loops (preferred) or branches with service connections, hydrants, and valves. Minimum size for fire protection is typically 6 inch residential or 8 inch commercial.`,tip:`<strong>Exam Tip:</strong> Looped systems are preferred: more uniform pressure, fewer water quality issues, redundant flow paths. C-factor (Hazen-Williams) decreases as pipes age and tuberculate.`},
  gate_valve:{name:`Gate Valve`,type:`Flow Control`,desc:`Gate valves are the most common valve in distribution systems, used to isolate pipe sections. A wedge-shaped gate moves up and down to open or close. They must be operated fully open or fully closed — never used for throttling.`,tip:`<strong>Exam Tip:</strong> Gate valves must NOT be used for throttling. Valve spacing: no more than 500 ft of main shut down for a repair. Turns to close: about 3 turns per inch of valve diameter (8-inch valve = 24 turns).`},
  prv:{name:`Pressure Reducing Valve (PRV)`,type:`Pressure Management`,desc:`PRVs automatically reduce higher inlet pressure to a lower, steady outlet pressure. Installed where mains transition from high-pressure to lower-pressure zones. They protect piping, fixtures, and appliances from excessive pressure.`,tip:`<strong>Exam Tip:</strong> PRVs are essential where pressure exceeds 80 psi. They only reduce pressure, never increase it. A failed PRV causes high-pressure complaints downstream.`},
  hydrant:{name:`Fire Hydrant`,type:`Fire Protection / Flushing`,desc:`Fire hydrants provide emergency water supply for firefighting and are used for system flushing, testing, and sampling. Dry-barrel hydrants drain automatically in freezing climates. Wet-barrel hydrants have water up to the outlets in warm climates.`,tip:`<strong>Exam Tip:</strong> Know dry-barrel vs. wet-barrel hydrants. NFPA color coding: Blue over 1500 GPM, Green 1000-1499, Orange 500-999, Red under 500 GPM. Use a pitot gauge during flow testing.`},
  service:{name:`Service Line and Water Meter`,type:`Customer Connection`,desc:`Service lines connect the distribution main to customer premises. A corporation stop connects at the main, a curb stop allows shutoff at the property line, and the meter measures consumption. Most residential services are 3/4 or 1 inch.`,tip:`<strong>Exam Tip:</strong> Order: corporation stop at main, then service line, then curb stop, then meter, then customer plumbing. The utility owns everything up to and including the meter. Lead and Copper Rule action level: 15 ppb.`},
  backflow:{name:`Backflow Prevention Device`,type:`Cross-Connection Control`,desc:`Backflow preventers protect the potable water supply from contamination caused by reverse flow. Backflow occurs via backpressure or backsiphonage. Types include RPBA, DCVA, AVB, and PVB for different hazard levels.`,tip:`<strong>Exam Tip:</strong> Air gap is the most reliable method. RPBA for high-hazard. DCVA (Double Check) for low-hazard. Devices must be tested annually by a certified tester. Know backpressure vs. backsiphonage!`},
  sampling:{name:`Sampling Point`,type:`Water Quality Monitoring`,desc:`Designated locations throughout the distribution system where water samples are collected for regulatory compliance. Samples are tested for disinfectant residual, coliform bacteria, DBPs, lead and copper, and other parameters.`,tip:`<strong>Exam Tip:</strong> Total Coliform Rule requires routine sampling based on population. Minimum 0.2 mg/L chlorine residual throughout the system. Dead ends and low-flow areas are common problem spots.`},
  deadend:{name:`Dead End and Flushing Point`,type:`System Maintenance`,desc:`Dead ends occur where mains terminate without looping. Water can stagnate, causing low disinfectant residuals, taste and odor complaints, and discolored water. Regular unidirectional flushing programs maintain water quality.`,tip:`<strong>Exam Tip:</strong> Dead ends are the number one cause of water quality complaints in distribution. Flushing velocity should be at least 2.5 ft/s to scour sediment. Always flush to a dechlorination point before discharging to storm drains.`},
  air_valve:{name:`Air Release Valve`,type:`System Protection`,desc:`Air release valves automatically vent trapped air from pipelines at high points. Trapped air reduces pipe capacity, causes pressure surges (water hammer), and leads to inaccurate meter readings. Combination valves handle large volumes during filling and small pockets during operation.`,tip:`<strong>Exam Tip:</strong> Install ARVs at high points. Combination air valves release small pockets during operation AND large volumes during filling. Water hammer is caused by rapid changes in flow velocity.`}
};

var mode='explore', qOrder=[], qIdx=0, qScore=0, qActive=false, dragSel=null, dragPlaced=0;
var wrap=document.getElementById('opp-dist-diagram');

function setMode(m){
  mode=m;
  var cls=wrap.className.split(' ').filter(function(c){return c.indexOf('mode-')!==0;});
  cls.push('mode-'+m); wrap.className=cls.join(' ');
  ['ddBtn0','ddBtn1','ddBtn2'].forEach(function(id,i){
    document.getElementById(id).classList.toggle('active',i===(['explore','quiz','drag'].indexOf(m)));
  });
  document.getElementById('ddInfo').classList.remove('visible');
  document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected','correct-flash','wrong-flash');});
  document.getElementById('ddQuizBanner').classList.toggle('visible',m==='quiz');
  document.getElementById('ddDragBanner').classList.toggle('visible',m==='drag');
  document.getElementById('ddDragPanel').classList.toggle('visible',m==='drag');
  document.querySelectorAll('.dd-lbl,.dd-lbg,.dd-sub').forEach(function(e){e.style.display=(m==='drag'||m==='quiz')?'none':'';});
  if(m==='quiz'){qActive=false;document.getElementById('ddQBQ').textContent='Test your knowledge of distribution system components.';document.getElementById('ddQBFB').textContent='';document.getElementById('ddQBFB').className='dd-qb-feedback';document.getElementById('ddQBSc').textContent='';document.getElementById('ddStartBtn').textContent='Start Quiz';document.getElementById('ddStartBtn').style.display='';}
  if(m==='drag')setupDrag();
}

function showInfo(id){
  var c=C[id]; if(!c)return;
  document.getElementById('ddIT').textContent=c.name;
  document.getElementById('ddITy').textContent=c.type;
  document.getElementById('ddID').textContent=c.desc;
  document.getElementById('ddITip').innerHTML=c.tip;
  document.getElementById('ddInfo').classList.add('visible');
}

function startQuiz(){
  qOrder=Object.keys(C).sort(function(){return Math.random()-0.5;});
  qIdx=0; qScore=0; qActive=true;
  document.getElementById('ddStartBtn').style.display='none';
  document.getElementById('ddQBFB').textContent='';
  document.getElementById('ddQBSc').textContent='Score: 0 / '+qOrder.length;
  nextQ();
}

function nextQ(){
  if(qIdx>=qOrder.length){
    qActive=false;
    document.getElementById('ddQBQ').textContent='Quiz complete! Score: '+qScore+' / '+qOrder.length;
    var ok=qScore>=Math.floor(qOrder.length*0.7);
    document.getElementById('ddQBFB').textContent=qScore===qOrder.length?'Perfect!':ok?'Great job!':'Keep practicing!';
    document.getElementById('ddQBFB').className='dd-qb-feedback '+(ok?'correct':'wrong');
    document.getElementById('ddStartBtn').textContent='Restart Quiz';
    document.getElementById('ddStartBtn').style.display='';
    return;
  }
  document.getElementById('ddQBQ').textContent='Click on: '+C[qOrder[qIdx]].name;
  document.getElementById('ddQBFB').textContent='';
}

function checkAnswer(id,el){
  if(!qActive||qIdx>=qOrder.length)return;
  var exp=qOrder[qIdx]; qIdx++;
  var fb=document.getElementById('ddQBFB');
  if(id===exp){
    qScore++;
    fb.textContent='Correct!'; fb.className='dd-qb-feedback correct';
    el.classList.add('correct-flash'); setTimeout(function(){el.classList.remove('correct-flash');},900);
  } else {
    fb.textContent='That was '+C[id].name+' - answer: '+C[exp].name;
    fb.className='dd-qb-feedback wrong';
    el.classList.add('wrong-flash');
    var ce=document.querySelector('.dd-comp[data-id="'+exp+'"]');
    if(ce){ce.classList.add('correct-flash');setTimeout(function(){ce.classList.remove('correct-flash');},1200);}
    setTimeout(function(){el.classList.remove('wrong-flash');},900);
  }
  document.getElementById('ddQBSc').textContent='Score: '+qScore+' / '+qOrder.length;
  setTimeout(nextQ,1600);
}

function setupDrag(){
  dragSel=null; dragPlaced=0;
  var ids=Object.keys(C).sort(function(){return Math.random()-0.5;});
  var panel=document.getElementById('ddDragPanel'); panel.innerHTML='';
  ids.forEach(function(id){
    var chip=document.createElement('div');
    chip.className='dd-chip'; chip.textContent=C[id].name;
    chip.setAttribute('data-id',id); chip.setAttribute('draggable','true');
    // HTML5 drag
    chip.addEventListener('dragstart',function(e){
      if(chip.classList.contains('done')){e.preventDefault();return;}
      e.dataTransfer.setData('text/plain',id);
      e.dataTransfer.effectAllowed='move';
      chip.classList.add('dragging');
      dragSel=id;
    });
    chip.addEventListener('dragend',function(){chip.classList.remove('dragging');});
    // Click fallback (touch / accessibility)
    chip.addEventListener('click',function(e){
      e.stopPropagation();
      if(chip.classList.contains('done'))return;
      document.querySelectorAll('.dd-chip').forEach(function(b){b.classList.remove('on');});
      chip.classList.add('on'); dragSel=id;
      document.getElementById('ddDragInst').textContent='Now click a component in the diagram:';
      document.getElementById('ddDragSel').textContent='> '+C[id].name;
    });
    panel.appendChild(chip);
  });
  document.getElementById('ddDP').textContent='0';
  document.getElementById('ddDT').textContent=ids.length;
  document.getElementById('ddDragInst').textContent='Drag a label onto its component in the diagram — or tap a label then tap the component.';
  document.getElementById('ddDragSel').textContent='';
}

function placeDrop(dragId,targetId,targetEl){
  if(dragId===targetId){
    dragPlaced++;
    document.getElementById('ddDP').textContent=dragPlaced;
    var chip=document.querySelector('.dd-chip[data-id="'+dragId+'"]');
    if(chip){chip.classList.remove('on','dragging');chip.classList.add('done');}
    var dz=document.querySelector('.dd-dz[data-id="'+targetId+'"]');
    if(dz)dz.classList.add('filled');
    var cg=document.querySelector('.dd-comp[data-id="'+targetId+'"]');
    if(cg)cg.querySelectorAll('.dd-lbl,.dd-lbg').forEach(function(e){e.style.display='';});
    targetEl.classList.add('correct-flash');setTimeout(function(){targetEl.classList.remove('correct-flash');},900);
    var tot=parseInt(document.getElementById('ddDT').textContent,10);
    if(dragPlaced>=tot){document.getElementById('ddDragInst').textContent='All components placed!';document.getElementById('ddDragSel').textContent='';}
    else{document.getElementById('ddDragInst').textContent='Drag a label onto its component — or tap a label then tap the component.';document.getElementById('ddDragSel').textContent='';}
    dragSel=null;
  } else {
    targetEl.classList.add('wrong-flash');setTimeout(function(){targetEl.classList.remove('wrong-flash');},700);
  }
}

// Wire up buttons via addEventListener (no onclick attributes)
document.getElementById('ddBtn0').addEventListener('click',function(){setMode('explore');});
document.getElementById('ddBtn1').addEventListener('click',function(){setMode('quiz');});
document.getElementById('ddBtn2').addEventListener('click',function(){setMode('drag');});
document.getElementById('ddStartBtn').addEventListener('click',function(){startQuiz();});
document.getElementById('ddCloseBtn').addEventListener('click',function(){
  document.getElementById('ddInfo').classList.remove('visible');
  document.querySelectorAll('.dd-comp').forEach(function(c){c.classList.remove('selected');});
});

// Wire up SVG components via event delegation
var svgWrap=document.querySelector('.dd-svg-wrap');

function findComp(target,root){
  var el=target;
  while(el && el!==root){
    if(el.classList && el.classList.contains('dd-comp'))return el;
    el=el.parentElement;
  }
  return null;
}

// Click (explore, quiz, drag fallback)
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

// Drag over — highlight target component
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
// Drop
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
echo "UPDATED: distribution diagram (ID $pid)" . PHP_EOL;
wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();
echo "DONE" . PHP_EOL;
