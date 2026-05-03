<?php
/**
 * Updates WooCommerce product/subscription prices from $29.99 → $19.99.
 * Targets T5, D5, and WW5 products by finding any product whose price
 * is currently 29.99 in _price / _regular_price / _sale_price postmeta.
 *
 * Also catches any WooCommerce subscription variation with that price.
 * Safe to re-run.
 */
echo "Patching WooCommerce product prices: 29.99 -> 19.99...\n";

global $wpdb;

// Find all post IDs that have a price of 29.99 in any price-related meta key.
// _subscription_price is used by WooCommerce Subscriptions plugin for the
// displayed billing amount — must be updated alongside _price/_regular_price.
$meta_keys = [ '_price', '_regular_price', '_sale_price', '_subscription_price' ];
$updated_ids = [];

foreach ( $meta_keys as $key ) {
    $rows = $wpdb->get_results( $wpdb->prepare(
        "SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta}
         WHERE meta_key = %s AND meta_value = '29.99'",
        $key
    ) );

    foreach ( $rows as $row ) {
        $post = get_post( $row->post_id );
        if ( ! $post ) continue;

        $title = $post->post_title;
        $type  = $post->post_type;

        $result = update_post_meta( $row->post_id, $key, '19.99' );
        echo "  OK: [{$type}] ID {$row->post_id} \"{$title}\" — {$key}: 29.99 -> 19.99\n";
        $updated_ids[] = $row->post_id;
    }
}

// Also check WooCommerce subscription price in order_items (display only, not stored)
// and verify by fetching the product titles we changed
if ( empty( $updated_ids ) ) {
    echo "  No products found with price 29.99 — already patched or not stored in postmeta.\n";
} else {
    echo "\nVerifying updated products:\n";
    foreach ( array_unique( $updated_ids ) as $pid ) {
        $price = get_post_meta( $pid, '_price', true );
        $reg   = get_post_meta( $pid, '_regular_price', true );
        $sub   = get_post_meta( $pid, '_subscription_price', true );
        $title = get_the_title( $pid );
        echo "  [{$pid}] \"{$title}\" — _price={$price}, _regular_price={$reg}, _subscription_price={$sub}\n";
    }
}

// Flush WooCommerce product caches
if ( function_exists( 'wc_delete_product_transients' ) ) {
    foreach ( array_unique( $updated_ids ) as $pid ) {
        wc_delete_product_transients( $pid );
    }
    echo "\nWooCommerce product transients cleared.\n";
}

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE.\n";
