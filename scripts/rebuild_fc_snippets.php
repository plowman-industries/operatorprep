<?php
/**
 * Rebuilds snippets 25, 26, and 42 to use the REST API (Tutor LMS quiz data)
 * as the flashcard data source for all non-T2 levels, matching T2's layout exactly.
 *
 * Run via: wp eval-file scripts/rebuild_fc_snippets.php --allow-root
 */

// ── Shared flashcard function body (stored as a string, injected into each snippet) ─
// The function uses ob_start() / ob_get_clean() to return HTML.
// Element IDs and API URL are parameterised by $level.
$shared_fn = <<<'PHPFN'
if (!function_exists('opp_api_flashcard_shortcode')) {
function opp_api_flashcard_shortcode($level) {
    if (!function_exists('opp_has_access') || !function_exists('opp_get_product_id')) {
        return '<p style="color:red">Error: access-gating functions missing.</p>';
    }
    if (!opp_has_access(opp_get_product_id($level))) {
        return opp_render_access_denied(opp_get_product_id($level), strtoupper($level));
    }
    $nonce = wp_create_nonce('wp_rest');
    $ls = esc_attr($level);
    $lj = esc_js($level);
    $nj = esc_js($nonce);
    static $meta = array(
        't1'  => array('T1 Water Treatment Flashcards',       'Flip through flashcards covering every T1 exam topic. Click a card to flip it.'),
        't3'  => array('T3 Water Treatment Flashcards',       'Flip through flashcards covering every T3 exam topic. Click a card to flip it.'),
        't4'  => array('T4 Water Treatment Flashcards',       'Flip through flashcards covering every T4 exam topic. Click a card to flip it.'),
        't5'  => array('T5 Water Treatment Flashcards',       'Flip through flashcards covering every T5 exam topic. Click a card to flip it.'),
        'd1'  => array('D1 Water Distribution Flashcards',    'Flip through flashcards covering every D1 exam topic. Click a card to flip it.'),
        'd2'  => array('D2 Water Distribution Flashcards',    'Flip through flashcards covering every D2 exam topic. Click a card to flip it.'),
        'd3'  => array('D3 Water Distribution Flashcards',    'Flip through flashcards covering every D3 exam topic. Click a card to flip it.'),
        'd4'  => array('D4 Water Distribution Flashcards',    'Flip through flashcards covering every D4 exam topic. Click a card to flip it.'),
        'd5'  => array('D5 Water Distribution Flashcards',    'Flip through flashcards covering every D5 exam topic. Click a card to flip it.'),
        'ww1' => array('WW1 Wastewater Treatment Flashcards', 'Flip through flashcards covering every WW1 exam topic. Click a card to flip it.'),
        'ww2' => array('WW2 Wastewater Treatment Flashcards', 'Flip through flashcards covering every WW2 exam topic. Click a card to flip it.'),
        'ww3' => array('WW3 Wastewater Treatment Flashcards', 'Flip through flashcards covering every WW3 exam topic. Click a card to flip it.'),
        'ww4' => array('WW4 Wastewater Treatment Flashcards', 'Flip through flashcards covering every WW4 exam topic. Click a card to flip it.'),
        'ww5' => array('WW5 Wastewater Treatment Flashcards', 'Flip through flashcards covering every WW5 exam topic. Click a card to flip it.'),
    );
    $title    = isset($meta[$level]) ? $meta[$level][0] : strtoupper($level) . ' Flashcards';
    $subtitle = isset($meta[$level]) ? $meta[$level][1] : 'Flip through flashcards covering every exam topic.';
    ob_start(); ?>
<div id="opp-<?php echo $ls; ?>-flashcards" class="opp-fc-wrap">
<style>
.opp-fc-wrap{max-width:900px;margin:0 auto;padding:20px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
.entry-title,.page-title,.wp-block-post-title{display:none !important}
.opp-fc-h2{text-align:center;font-size:2em;margin-bottom:10px;color:#e2e8f0}
.opp-fc-sub{text-align:center;color:#94a3b8;margin-bottom:30px}
.opp-fc-loading{text-align:center;padding:40px;color:#94a3b8;grid-column:1/-1}
.opp-fc-category-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;margin-bottom:20px}
.opp-fc-cat-card{background:rgba(35,65,105,.9);border:1px solid rgba(96,165,250,.25);border-radius:12px;padding:20px;cursor:pointer;transition:transform .2s,box-shadow .2s;color:#e2e8f0;display:flex;flex-direction:column;gap:6px}
.opp-fc-cat-card:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,0,.5);border-color:#60a5fa}
.opp-fc-cat-name{font-size:1em;font-weight:600;color:#f1f5f9}
.opp-fc-cat-desc{font-size:.82em;color:#94a3b8;line-height:1.4}
.opp-fc-cat-count{font-size:.82em;color:#60a5fa;font-weight:600;margin-top:4px}
.opp-fc-actions{display:flex;gap:12px;justify-content:center;margin-bottom:20px;flex-wrap:wrap}
.opp-fc-action-btn{display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#2a5a8a,#1a3a5a);border:2px solid #3a7aba;border-radius:12px;padding:16px 28px;cursor:pointer;transition:all .2s;color:#fff;font-size:1.05em;font-weight:600}
.opp-fc-action-btn:hover{transform:translateY(-2px);box-shadow:0 4px 20px rgba(42,90,138,.5);border-color:#5a9ada}
.opp-fc-action-btn .icon{font-size:1.4em}
.opp-fc-action-btn.print-btn{background:linear-gradient(135deg,#2E7D32,#1a4a1e);border-color:#3a8a3e}
.opp-fc-action-btn.print-btn:hover{box-shadow:0 4px 20px rgba(46,125,50,.5);border-color:#5aaa5e}
.opp-fc-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;flex-wrap:wrap;gap:10px}
.opp-fc-back-btn,.opp-fc-shuffle{background:rgba(35,65,105,.9);color:#e2e8f0;border:1px solid rgba(96,165,250,.3);padding:8px 16px;border-radius:8px;cursor:pointer;font-size:.9em}
.opp-fc-back-btn:hover,.opp-fc-shuffle:hover{background:rgba(96,165,250,.3)}
.opp-fc-cat-title{font-size:1.1em;font-weight:600;color:#f1f5f9}
.opp-fc-progress{height:6px;background:rgba(30,41,59,.8);border-radius:3px;overflow:hidden;margin-bottom:8px}
.opp-fc-progress-bar{height:100%;background:linear-gradient(90deg,#3b82f6,#60a5fa);width:0;transition:width .3s}
.opp-fc-counter{text-align:center;color:#94a3b8;font-size:.9em;margin-bottom:20px}
.opp-fc-card-wrap{perspective:1200px;margin-bottom:25px;min-height:340px}
.opp-fc-card{position:relative;width:100%;min-height:340px;transform-style:preserve-3d;transition:transform .6s;cursor:pointer}
.opp-fc-card.opp-flipped{transform:rotateY(180deg)}
.opp-fc-face{position:absolute;width:100%;min-height:340px;backface-visibility:hidden;border-radius:16px;padding:40px 30px;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.4)}
.opp-fc-front{background:linear-gradient(135deg,#1e3a5f 0%,#234169 100%);border:1px solid rgba(96,165,250,.3)}
.opp-fc-back{background:linear-gradient(135deg,#14532d 0%,#166534 100%);border:1px solid rgba(74,222,128,.4);transform:rotateY(180deg)}
.opp-fc-label{font-size:.75em;text-transform:uppercase;letter-spacing:2px;color:#94a3b8;margin-bottom:15px;font-weight:600}
.opp-fc-q{font-size:1.2em;line-height:1.6;color:#f1f5f9;max-width:700px}
.opp-fc-a{font-size:1.35em;line-height:1.6;color:#bbf7d0;font-weight:600;max-width:700px;margin-bottom:15px}
.opp-fc-desc{font-size:.95em;line-height:1.5;color:#d1fae5;max-width:700px;font-style:italic}
.opp-fc-hint{position:absolute;bottom:15px;font-size:.75em;color:#64748b}
.opp-fc-nav{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.opp-fc-nav-btn,.opp-fc-known,.opp-fc-review{padding:12px 20px;border-radius:8px;border:none;cursor:pointer;font-size:.95em;font-weight:600;transition:all .2s}
.opp-fc-nav-btn{background:rgba(35,65,105,.9);color:#e2e8f0;border:1px solid rgba(96,165,250,.3)}
.opp-fc-nav-btn:hover:not(:disabled){background:rgba(96,165,250,.3)}
.opp-fc-nav-btn:disabled{opacity:.4;cursor:not-allowed}
.opp-fc-known{background:rgba(22,101,52,.8);color:#bbf7d0;border:1px solid rgba(74,222,128,.4)}
.opp-fc-known:hover{background:rgba(22,163,74,.9)}
.opp-fc-review{background:rgba(120,53,15,.8);color:#fde68a;border:1px solid rgba(251,191,36,.4)}
.opp-fc-review:hover{background:rgba(180,83,9,.9)}
.opp-fc-print-area{display:none}
@media print{body>*{display:none !important}.opp-fc-print-area{display:block !important;width:100%}@page{size:letter portrait;margin:0}.avery-page{width:8.5in;height:11in;padding:.5in 0 0 1.75in;box-sizing:border-box;page-break-after:always;display:flex;flex-direction:column;gap:0}.avery-page:last-child{page-break-after:auto}.avery-card{width:5in;height:3in;border:1px dashed #ccc;box-sizing:border-box;padding:.3in;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;margin-bottom:.167in;page-break-inside:avoid}.avery-front-term{font-family:Arial,sans-serif;font-size:16pt;font-weight:700;color:#000;text-align:center;line-height:1.3}.avery-back-term{font-family:Arial,sans-serif;font-size:11pt;font-weight:700;color:#000;text-align:center;margin-bottom:5pt;border-bottom:1pt solid #ccc;padding-bottom:4pt;width:100%}.avery-back-def{font-family:Arial,sans-serif;font-size:10pt;color:#333;line-height:1.4;text-align:center}.avery-back-cat{font-family:Arial,sans-serif;font-size:7pt;color:#999;margin-top:auto;text-align:center}}
@media(max-width:600px){.opp-fc-category-grid{grid-template-columns:1fr}.opp-fc-q,.opp-fc-a{font-size:1.05em}.opp-fc-face{padding:25px 20px}}
</style>
<div class="opp-fc-intro" id="opp-<?php echo $ls; ?>-fc-intro">
  <h2 class="opp-fc-h2"><?php echo esc_html($title); ?></h2>
  <p class="opp-fc-sub"><?php echo esc_html($subtitle); ?></p>
  <div class="opp-fc-actions">
    <div class="opp-fc-action-btn" id="opp-<?php echo $ls; ?>-btn-random"><span class="icon">&#127922;</span><span>Random Study Mode<br><small style="font-weight:400;font-size:.8em;opacity:.7">All cards shuffled</small></span></div>
    <div class="opp-fc-action-btn print-btn" id="opp-<?php echo $ls; ?>-btn-print"><span class="icon">&#128424;</span><span>Print Flash Cards<br><small style="font-weight:400;font-size:.8em;opacity:.7">Avery 5388 double-sided</small></span></div>
  </div>
  <p class="opp-fc-sub" style="margin-bottom:12px;font-size:.9em">Or study by category:</p>
  <div class="opp-fc-category-grid" id="opp-<?php echo $ls; ?>-fc-cat-grid"><div class="opp-fc-loading">Loading categories&#8230;</div></div>
</div>
<div class="opp-fc-deck" id="opp-<?php echo $ls; ?>-fc-deck" style="display:none;">
  <div class="opp-fc-toolbar">
    <button class="opp-fc-back-btn" id="opp-<?php echo $ls; ?>-fc-back">&larr; Categories</button>
    <span class="opp-fc-cat-title" id="opp-<?php echo $ls; ?>-fc-cat-title"></span>
    <button class="opp-fc-shuffle" id="opp-<?php echo $ls; ?>-fc-shuffle">&#128256; Shuffle</button>
  </div>
  <div class="opp-fc-progress"><div class="opp-fc-progress-bar" id="opp-<?php echo $ls; ?>-fc-progress-bar"></div></div>
  <div class="opp-fc-counter" id="opp-<?php echo $ls; ?>-fc-counter">Card 1 of 1</div>
  <div class="opp-fc-card-wrap">
    <div class="opp-fc-card" id="opp-<?php echo $ls; ?>-fc-card">
      <div class="opp-fc-face opp-fc-front">
        <div class="opp-fc-label">Question</div>
        <div class="opp-fc-q" id="opp-<?php echo $ls; ?>-fc-q"></div>
        <div class="opp-fc-hint">Click card to flip</div>
      </div>
      <div class="opp-fc-face opp-fc-back">
        <div class="opp-fc-label">Answer</div>
        <div class="opp-fc-a" id="opp-<?php echo $ls; ?>-fc-a"></div>
        <div class="opp-fc-desc" id="opp-<?php echo $ls; ?>-fc-desc"></div>
      </div>
    </div>
  </div>
  <div class="opp-fc-nav">
    <button class="opp-fc-nav-btn" id="opp-<?php echo $ls; ?>-fc-prev">&larr; Prev</button>
    <button class="opp-fc-known" id="opp-<?php echo $ls; ?>-fc-known">&#10003; I knew it</button>
    <button class="opp-fc-review" id="opp-<?php echo $ls; ?>-fc-review">&#8635; Review later</button>
    <button class="opp-fc-nav-btn" id="opp-<?php echo $ls; ?>-fc-next">Next &rarr;</button>
  </div>
</div>
<div class="opp-fc-print-area" id="opp-<?php echo $ls; ?>-fc-print-area"></div>
<script>
(function(){
var LVL='<?php echo $lj; ?>';
var API='/wp-json/opp-study/v1/<?php echo $lj; ?>';
var restNonce='<?php echo $nj; ?>';
var deck=[],idx=0,allCats=[];
function _(id){return document.getElementById('opp-'+LVL+'-'+id);}
function correctAnswer(q){return(q.answers||[]).filter(function(a){return a.correct;}).map(function(a){return a.text;}).join(' • ');}
function render(){
  if(!deck.length)return;
  var q=deck[idx];
  _('fc-card').classList.remove('opp-flipped');
  _('fc-q').textContent=q.question||'';
  _('fc-a').textContent=correctAnswer(q);
  _('fc-desc').textContent=(q.description||'').replace(/<[^>]*>/g,'');
  _('fc-counter').textContent='Card '+(idx+1)+' of '+deck.length;
  _('fc-progress-bar').style.width=(((idx+1)/deck.length)*100)+'%';
  _('fc-prev').disabled=idx===0;
  _('fc-next').disabled=idx===deck.length-1;
}
function shuffle(arr){for(var i=arr.length-1;i>0;i--){var j=Math.floor(Math.random()*(i+1));var t=arr[i];arr[i]=arr[j];arr[j]=t;}return arr;}
function openDeck(slug,name){
  _('fc-intro').style.display='none';_('fc-deck').style.display='block';
  _('fc-cat-title').textContent=name;_('fc-q').textContent='Loading…';
  fetch(API+'/quiz/'+slug,{headers:{'X-WP-Nonce':restNonce}})
    .then(function(r){return r.json();})
    .then(function(data){deck=data.questions||[];idx=0;render();})
    .catch(function(){_('fc-q').textContent='Error loading cards.';});
}
function buildPrint(){
  if(!allCats.length)return;
  var area=_('fc-print-area');
  area.innerHTML='<div style="text-align:center;padding:20px;color:#fff">Loading cards…</div>';
  document.body.appendChild(area);area.style.display='block';
  Promise.all(allCats.map(function(c){
    return fetch(API+'/quiz/'+c.slug+'?all=1',{headers:{'X-WP-Nonce':restNonce}}).then(function(r){return r.json();});
  })).then(function(results){
    var all=[];
    results.forEach(function(data){
      if(data&&data.questions){data.questions.forEach(function(q){all.push({q:q.question,a:correctAnswer(q),cat:data.category});});}
    });
    var h='';
    for(var p=0;p<all.length;p+=3){
      var batch=all.slice(p,Math.min(p+3,all.length));
      h+='<div class="avery-page">';
      for(var i=0;i<batch.length;i++)h+='<div class="avery-card"><div class="avery-front-term">'+batch[i].q+'</div></div>';
      for(var i=batch.length;i<3;i++)h+='<div class="avery-card"></div>';
      h+='</div><div class="avery-page">';
      for(var i=batch.length-1;i>=0;i--)h+='<div class="avery-card"><div class="avery-back-term">'+batch[i].cat+'</div><div class="avery-back-def">'+batch[i].a+'</div></div>';
      for(var i=batch.length;i<3;i++)h+='<div class="avery-card"></div>';
      h+='</div>';
    }
    area.innerHTML=h;
    var wrap=document.getElementById('opp-'+LVL+'-flashcards');
    setTimeout(function(){window.print();setTimeout(function(){area.style.display='none';wrap.appendChild(area);},500);},300);
  }).catch(function(){area.innerHTML='<div style="text-align:center;padding:20px;">Error loading cards for print.</div>';});
}
fetch(API+'/categories',{headers:{'X-WP-Nonce':restNonce}})
  .then(function(r){return r.json();})
  .then(function(cats){
    cats=cats.filter(function(c){return!/math/i.test(c.name)&&(c.total_questions||0)>0;});
    allCats=cats;
    var grid=_('fc-cat-grid');grid.innerHTML='';
    cats.forEach(function(c){
      var card=document.createElement('div');
      card.className='opp-fc-cat-card';
      card.innerHTML='<div class="opp-fc-cat-name">'+c.name+'</div>'
        +'<div class="opp-fc-cat-desc">'+(c.description||'')+'</div>'
        +'<div class="opp-fc-cat-count">'+(c.total_questions||0)+' cards</div>';
      card.addEventListener('click',function(){openDeck(c.slug,c.name);});
      grid.appendChild(card);
    });
  })
  .catch(function(){_('fc-cat-grid').innerHTML='<div class="opp-fc-loading">Error loading categories.</div>';});
_('fc-card').addEventListener('click',function(){_('fc-card').classList.toggle('opp-flipped');});
_('fc-back').addEventListener('click',function(){_('fc-deck').style.display='none';_('fc-intro').style.display='';});
_('fc-shuffle').addEventListener('click',function(){shuffle(deck);idx=0;render();});
_('fc-prev').addEventListener('click',function(e){e.stopPropagation();if(idx>0){idx--;render();}});
_('fc-next').addEventListener('click',function(e){e.stopPropagation();if(idx<deck.length-1){idx++;render();}});
_('btn-random').addEventListener('click',function(){openDeck('random-all','Random Study Mode');});
_('btn-print').addEventListener('click',buildPrint);
})();
</script>
</div>
<?php
    return ob_get_clean();
}
}
PHPFN;

// ── Snippet 42: T1 / T3 / T4 / T5 Flashcards ──────────────────────────────
$code42 = $shared_fn . "\n\n" . <<<'PHP42'
add_shortcode('opp_t1_flashcards', function() { return opp_api_flashcard_shortcode('t1'); });
add_shortcode('opp_t3_flashcards', function() { return opp_api_flashcard_shortcode('t3'); });
add_shortcode('opp_t4_flashcards', function() { return opp_api_flashcard_shortcode('t4'); });
add_shortcode('opp_t5_flashcards', function() { return opp_api_flashcard_shortcode('t5'); });
PHP42;

// ── Snippet 26: D1–D5 Flashcards ─────────────────────────────────────────
$code26 = $shared_fn . "\n\n" . <<<'PHP26'
add_shortcode('opp_d1_flashcards', function() { return opp_api_flashcard_shortcode('d1'); });
add_shortcode('opp_d2_flashcards', function() { return opp_api_flashcard_shortcode('d2'); });
add_shortcode('opp_d3_flashcards', function() { return opp_api_flashcard_shortcode('d3'); });
add_shortcode('opp_d4_flashcards', function() { return opp_api_flashcard_shortcode('d4'); });
add_shortcode('opp_d5_flashcards', function() { return opp_api_flashcard_shortcode('d5'); });
PHP26;

// ── Snippet 25: WW1–WW5 Flashcards ────────────────────────────────────────
$code25 = $shared_fn . "\n\n" . <<<'PHP25'
add_shortcode('opp_ww1_flashcards', function() { return opp_api_flashcard_shortcode('ww1'); });
add_shortcode('opp_ww2_flashcards', function() { return opp_api_flashcard_shortcode('ww2'); });
add_shortcode('opp_ww3_flashcards', function() { return opp_api_flashcard_shortcode('ww3'); });
add_shortcode('opp_ww4_flashcards', function() { return opp_api_flashcard_shortcode('ww4'); });
add_shortcode('opp_ww5_flashcards', function() { return opp_api_flashcard_shortcode('ww5'); });
PHP25;

// ── Write to DB ────────────────────────────────────────────────────────────
global $wpdb;
$updates = array(
    42 => $code42,
    26 => $code26,
    25 => $code25,
);

foreach ($updates as $id => $code) {
    $result = $wpdb->update('ugk_snippets', array('code' => $code), array('id' => $id));
    if ($result === false) {
        echo "SNIPPET {$id}: DB ERROR - " . $wpdb->last_error . PHP_EOL;
    } else {
        echo "SNIPPET {$id}: UPDATED (" . strlen($code) . " bytes)" . PHP_EOL;
    }
}

wp_cache_flush();
echo "DONE - cache flushed" . PHP_EOL;
