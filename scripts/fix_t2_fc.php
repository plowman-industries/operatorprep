<?php
/**
 * Patches snippet 11 (T2 Flashcards) to add Random Study Mode + Print Flash Cards
 * buttons at the top of the flashcard page, matching T1/T3-T5/D1-D5/WW1-WW5.
 *
 * Run via: wp eval-file scripts/fix_t2_fc.php --allow-root
 */
global $wpdb;
$row = $wpdb->get_row("SELECT id, code FROM ugk_snippets WHERE id=11");
if (!$row) {
    echo 'ERROR: snippet 11 not found';
    exit(1);
}
$code = $row->code;

// Idempotency check
if (strpos($code, 'opp-t2-btn-random') !== false) {
    echo 'ALREADY PATCHED - no changes made';
    exit(0);
}

// ── PATCH 1: Insert CSS + action buttons before the category grid ───────────
$search1 = '<div class="opp-fc-category-grid" id="opp-fc-cat-grid">';
if (strpos($code, $search1) === false) {
    echo 'ERROR: patch 1 search string not found';
    exit(1);
}
$insert1 =
    '<style>' . "\n" .
    '    .opp-fc-actions{display:flex;gap:12px;justify-content:center;margin-bottom:25px;flex-wrap:wrap}' . "\n" .
    '    .opp-fc-action-btn{display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#2a5a8a,#1a3a5a);border:2px solid #3a7aba;border-radius:12px;padding:16px 28px;cursor:pointer;transition:all .2s;color:#fff;font-size:1.05em;font-weight:600}' . "\n" .
    '    .opp-fc-action-btn:hover{transform:translateY(-2px);box-shadow:0 4px 20px rgba(42,90,138,.5);border-color:#5a9ada}' . "\n" .
    '    .opp-fc-action-btn .icon{font-size:1.4em}' . "\n" .
    '    .opp-fc-action-btn.print-btn{background:linear-gradient(135deg,#2E7D32,#1a4a1e);border-color:#3a8a3e}' . "\n" .
    '    .opp-fc-action-btn.print-btn:hover{box-shadow:0 4px 20px rgba(46,125,50,.5);border-color:#5aaa5e}' . "\n" .
    '    @media print{body>*{display:none !important}.opp-t2-print-area{display:block !important;width:100%}' .
    '@page{size:letter portrait;margin:0}' .
    '.avery-page{width:8.5in;height:11in;padding:0.5in 0 0 1.75in;box-sizing:border-box;page-break-after:always;display:flex;flex-direction:column;gap:0}' .
    '.avery-page:last-child{page-break-after:auto}' .
    '.avery-card{width:5in;height:3in;border:1px dashed #ccc;box-sizing:border-box;padding:0.3in;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;margin-bottom:0.167in;page-break-inside:avoid}' .
    '.avery-front-term{font-family:Arial,sans-serif;font-size:18pt;font-weight:700;color:#000;text-align:center;line-height:1.3}' .
    '.avery-back-term{font-family:Arial,sans-serif;font-size:12pt;font-weight:700;color:#000;text-align:center;margin-bottom:6pt;border-bottom:1pt solid #ccc;padding-bottom:4pt;width:100%}' .
    '.avery-back-def{font-family:Arial,sans-serif;font-size:11pt;color:#333;line-height:1.4;text-align:center}' .
    '.avery-back-cat{font-family:Arial,sans-serif;font-size:8pt;color:#999;margin-top:auto;text-align:center}}' . "\n" .
    '    .opp-t2-print-area{display:none}' . "\n" .
    '    </style>' . "\n" .
    '    <div class="opp-fc-actions" id="opp-t2-fc-actions">' . "\n" .
    '        <div class="opp-fc-action-btn" id="opp-t2-btn-random"><span class="icon">&#127922;</span><span>Random Study Mode<br><small style="font-weight:400;font-size:.8em;opacity:.7">All cards shuffled</small></span></div>' . "\n" .
    '        <div class="opp-fc-action-btn print-btn" id="opp-t2-btn-print"><span class="icon">&#128424;</span><span>Print Flash Cards<br><small style="font-weight:400;font-size:.8em;opacity:.7">Avery 5388 double-sided</small></span></div>' . "\n" .
    '    </div>' . "\n" .
    '    <p class="opp-fc-sub" style="margin-bottom:12px;font-size:.9em">Or study by category:</p>' . "\n" .
    '    ';
$code = str_replace($search1, $insert1 . $search1, $code, $c1);
echo "Patch 1 (HTML+CSS): replaced {$c1} time(s)" . PHP_EOL;

// ── PATCH 2: Wire up buttons inside the IIFE (before loadCategories()) ──────
$search2 = 'loadCategories();';
if (strpos($code, $search2) === false) {
    echo 'ERROR: patch 2 search string not found';
    exit(1);
}
$occ2 = substr_count($code, $search2);
echo "Occurrences of 'loadCategories();': {$occ2}" . PHP_EOL;

$js_addition =
    '// T2 action buttons - Random Study Mode + Print Flash Cards' . "\n" .
    "        var _t2PrintArea = document.createElement('div');\n" .
    "        _t2PrintArea.id = 'opp-t2-print-area';\n" .
    "        _t2PrintArea.className = 'opp-t2-print-area';\n" .
    "        document.getElementById('opp-t2-flashcards').appendChild(_t2PrintArea);\n" .
    "\n" .
    "        function buildT2PrintCards() {\n" .
    "            var slugs = ['source-water','coagulation','sedimentation','filtration','disinfection','corrosion-water-quality','regulations','special-treatment'];\n" .
    "            var area = _t2PrintArea;\n" .
    "            area.innerHTML = '<div style=\"text-align:center;padding:20px;color:#fff\">Loading cards for printing…</div>';\n" .
    "            document.body.appendChild(area);\n" .
    "            area.style.display = 'block';\n" .
    "            Promise.all(slugs.map(function(s) {\n" .
    "                return fetch(API + '/quiz/' + s + '?all=1', { headers: { 'X-WP-Nonce': restNonce } }).then(function(r) { return r.json(); });\n" .
    "            })).then(function(results) {\n" .
    "                var all = [];\n" .
    "                results.forEach(function(data) {\n" .
    "                    if (data && data.questions) {\n" .
    "                        data.questions.forEach(function(q) {\n" .
    "                            var correct = (q.answers || []).filter(function(a) { return a.correct; }).map(function(a) { return a.text; }).join(' • ');\n" .
    "                            all.push({ q: q.question, a: correct, cat: data.category });\n" .
    "                        });\n" .
    "                    }\n" .
    "                });\n" .
    "                var h = '';\n" .
    "                for (var p = 0; p < all.length; p += 3) {\n" .
    "                    var batch = all.slice(p, Math.min(p + 3, all.length));\n" .
    "                    h += '<div class=\"avery-page\">';\n" .
    "                    for (var i = 0; i < batch.length; i++) h += '<div class=\"avery-card\"><div class=\"avery-front-term\">' + batch[i].q + '</div></div>';\n" .
    "                    for (var i = batch.length; i < 3; i++) h += '<div class=\"avery-card\"></div>';\n" .
    "                    h += '</div><div class=\"avery-page\">';\n" .
    "                    for (var i = batch.length - 1; i >= 0; i--) h += '<div class=\"avery-card\"><div class=\"avery-back-term\">' + batch[i].cat + '</div><div class=\"avery-back-def\">' + batch[i].a + '</div></div>';\n" .
    "                    for (var i = batch.length; i < 3; i++) h += '<div class=\"avery-card\"></div>';\n" .
    "                    h += '</div>';\n" .
    "                }\n" .
    "                area.innerHTML = h;\n" .
    "                setTimeout(function() {\n" .
    "                    window.print();\n" .
    "                    setTimeout(function() { area.style.display = 'none'; document.getElementById('opp-t2-flashcards').appendChild(area); }, 500);\n" .
    "                }, 300);\n" .
    "            }).catch(function() {\n" .
    "                area.innerHTML = '<div style=\"text-align:center;padding:20px;color:#fff\">Error loading cards.</div>';\n" .
    "            });\n" .
    "        }\n" .
    "\n" .
    "        document.getElementById('opp-t2-btn-random').addEventListener('click', function() { openDeck('random-all', 'Random Study Mode'); });\n" .
    "        document.getElementById('opp-t2-btn-print').addEventListener('click', buildT2PrintCards);\n" .
    "\n        ";
$code = str_replace($search2, $js_addition . $search2, $code, $c2);
echo "Patch 2 (JS wiring): replaced {$c2} time(s)" . PHP_EOL;

if ($c1 === 0 || $c2 === 0) {
    echo 'ERROR: one or more patches failed - aborting without DB update';
    exit(1);
}

$result = $wpdb->update('ugk_snippets', array('code' => $code), array('id' => 11));
if ($result === false) {
    echo 'DB ERROR: ' . $wpdb->last_error;
    exit(1);
}
echo 'SUCCESS: snippet 11 updated' . PHP_EOL;
wp_cache_flush();
