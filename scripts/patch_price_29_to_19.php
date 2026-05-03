<?php
/**
 * Patches all published pages: replaces every "$29.99" reference with "$19.99".
 * Covers: certifications page, pricing page, T5/D5/WW5 landing pages, and any
 * other page that references the old CA-grade price.
 *
 * Safe to re-run — skips pages that already contain $19.99 and no $29.99.
 */
echo "Patching \$29.99 → \$19.99 across all published pages...\n";

$pages = get_posts([
    'post_type'      => ['page', 'post'],
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'all',
]);

$updated = 0;
$skipped = 0;

foreach ( $pages as $page ) {
    $c = $page->post_content;
    if ( strpos( $c, '$29.99' ) === false ) {
        $skipped++;
        continue;
    }
    $new = str_replace( '$29.99', '$19.99', $c );
    $result = wp_update_post( [ 'ID' => $page->ID, 'post_content' => $new ] );
    if ( is_wp_error( $result ) ) {
        echo "  ERROR (ID {$page->ID} "{$page->post_title}"): " . $result->get_error_message() . "\n";
    } else {
        $count = substr_count( $c, '$29.99' );
        echo "  OK (ID {$page->ID}): "{$page->post_title}" — {$count} replacement(s)\n";
        $updated++;
    }
}

echo "\nDone — {$updated} page(s) updated, {$skipped} skipped (no match).\n";

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}
