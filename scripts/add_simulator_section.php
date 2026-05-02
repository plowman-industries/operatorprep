<?php
/**
 * Inserts the "See How Your System Works" simulator cards section
 * into the homepage (page ID 35) between op-features and op-certs.
 * Safe to run multiple times — checks for existing section first.
 */
echo "Adding simulator section to homepage (ID 35)..." . PHP_EOL;

$pid = 35;
$page = get_post($pid);
if ( ! $page ) {
    echo "ERROR: Page 35 not found." . PHP_EOL;
    exit;
}

$current = $page->post_content;

// Guard: skip if already inserted
if ( strpos( $current, 'op-sims' ) !== false ) {
    echo "Simulator section already present — skipping." . PHP_EOL;
    exit;
}

$new_section = '
<section class="op-sims">
<div class="op-sims__inner">
<span class="op-eyebrow">// Free Visual Tools</span>
<h2 class="op-section-heading">See How Your System Works.</h2>
<p class="op-section-sub">Interactive plant diagrams for every cert track. Explore each component, quiz yourself by clicking the right part, then drag-and-drop your way to mastery. Free for everyone.</p>
<div class="op-sims__grid">

<div class="op-sim-card">
<div class="op-sim-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c4.97 0 9 3.358 9 7.5S16.97 18 12 18c-.99 0-1.944-.13-2.83-.37l-3.19 1.87.85-2.97C5.394 15.4 3 13.56 3 10.5 3 6.358 7.03 3 12 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10.5h8M10 13h4"/></svg></div>
<div class="op-sim-card__label">Water Treatment &middot; T1 / T2</div>
<div class="op-sim-card__title">T1/T2 Plant Diagram</div>
<div class="op-sim-card__desc">Walk the 9-step treatment train from raw water intake through coagulation, flocculation, sedimentation, filtration, and disinfection to the clearwell.</div>
<a href="/t1-t2-plant-diagram/" class="op-sim-card__link">Try the Simulator &rarr;</a>
</div>

<div class="op-sim-card">
<div class="op-sim-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M3 6h18M3 18h18"/><circle cx="7" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="12" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="17" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg></div>
<div class="op-sim-card__label">Water Distribution &middot; D1 / D2</div>
<div class="op-sim-card__title">D1/D2 Distribution Diagram</div>
<div class="op-sim-card__desc">Trace water from the source through pumping stations, storage tanks, transmission mains, and service connections to the customer meter.</div>
<a href="/d1-d2-distribution-diagram/" class="op-sim-card__link">Try the Simulator &rarr;</a>
</div>

<div class="op-sim-card">
<div class="op-sim-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg></div>
<div class="op-sim-card__label">Wastewater Treatment &middot; WW1 / WW2</div>
<div class="op-sim-card__title">WW1/WW2 Plant Diagram</div>
<div class="op-sim-card__desc">Follow influent through 12 treatment stages: screening, primary clarification, activated sludge, secondary clarification, disinfection, and biosolids handling.</div>
<a href="/ww1-ww2-wastewater-diagram/" class="op-sim-card__link">Try the Simulator &rarr;</a>
</div>

</div>
</div>
</section>
';

$marker = '<section class="op-certs">';
if ( strpos( $current, $marker ) === false ) {
    echo "ERROR: Could not find op-certs section marker." . PHP_EOL;
    echo "First 500 chars of content:" . PHP_EOL;
    echo substr( $current, 0, 500 ) . PHP_EOL;
    exit;
}

$updated = str_replace( $marker, $new_section . "\n" . $marker, $current );

$result = wp_update_post( array( 'ID' => $pid, 'post_content' => $updated ) );
if ( is_wp_error( $result ) ) {
    echo "ERROR updating post: " . $result->get_error_message() . PHP_EOL;
    exit;
}

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE — simulator section added to homepage." . PHP_EOL;
