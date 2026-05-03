<?php
/**
 * Adds the EPA Resources card to WW1-WW5 study guide hub pages.
 * Injects a .sg-card.resources card into the .sg-cards grid.
 * Safe to re-run — skips pages that already have the card.
 *
 * Page IDs: WW1=1250, WW2=1251, WW3=1252, WW4=1253, WW5=1254
 */

$page_ids = [1250, 1251, 1252, 1253, 1254];

$epa_card = '  <a class="sg-card resources" href="/epa-wastewater-resources/">
    <div class="sg-icon">&#x1F4CB;</div>
    <h2>EPA Resources</h2>
    <p class="sg-desc">Official EPA compliance documents, process control guides, and training videos for wastewater operators.</p>
    <span class="sg-btn">View Resources &#x2192;</span>
  </a>';

// Marker: end of the sg-cards grid, right before the back link
$marker = '</div>
<a class="sg-back"';

$updated_count = 0;

foreach ( $page_ids as $id ) {
    $page = get_post( $id );
    if ( ! $page ) {
        echo "ERROR: Page ID {$id} not found." . PHP_EOL;
        continue;
    }

    $content = $page->post_content;

    // Guard: skip if EPA card already present
    if ( strpos( $content, 'epa-wastewater-resources' ) !== false ) {
        echo "Page {$id} ({$page->post_name}): EPA card already present — skipping." . PHP_EOL;
        continue;
    }

    // Verify marker exists
    if ( strpos( $content, $marker ) === false ) {
        echo "ERROR: Page {$id} ({$page->post_name}): injection marker not found." . PHP_EOL;
        echo "Last 300 chars: " . substr( $content, -300 ) . PHP_EOL;
        continue;
    }

    // Inject EPA card just before the closing </div> of .sg-cards
    $updated = str_replace(
        $marker,
        $epa_card . "\n" . $marker,
        $content
    );

    $result = wp_update_post( [ 'ID' => $id, 'post_content' => $updated ] );
    if ( is_wp_error( $result ) ) {
        echo "ERROR updating page {$id}: " . $result->get_error_message() . PHP_EOL;
        continue;
    }

    echo "Page {$id} ({$page->post_name}): EPA resources card added." . PHP_EOL;
    $updated_count++;
}

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE — {$updated_count} page(s) updated." . PHP_EOL;
