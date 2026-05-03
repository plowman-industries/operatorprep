<?php
/**
 * Updates WooCommerce T5/D5/WW5 subscription prices using the WooCommerce
 * product API (wc_get_product + set methods + save) to ensure all internal
 * caches, lookup tables, and transients are properly cleared.
 *
 * Product IDs: T5=578, D5=583, WW5=588
 */
echo "Patching WooCommerce product prices via WC API...\n";

$product_ids = [ 578, 583, 588 ];
$old_price   = '29.99';
$new_price   = '19.99';

foreach ( $product_ids as $pid ) {
    $product = wc_get_product( $pid );
    if ( ! $product ) {
        echo "  WARN: product ID {$pid} not found\n";
        continue;
    }

    $current_price = $product->get_price();
    $current_reg   = $product->get_regular_price();
    $title         = $product->get_name();

    echo "  [{$pid}] \"{$title}\" — current price={$current_price}, regular={$current_reg}\n";

    // Update via WC API
    $product->set_price( $new_price );
    $product->set_regular_price( $new_price );

    // For WooCommerce Subscriptions, also update subscription price meta directly
    update_post_meta( $pid, '_subscription_price', $new_price );
    update_post_meta( $pid, '_price', $new_price );
    update_post_meta( $pid, '_regular_price', $new_price );

    $product->save();

    // Force WC to rebuild its price lookup table
    if ( class_exists( 'Automattic\WooCommerce\Internal\ProductAttributesLookup\LookupDataStore' ) ) {
        wc_get_container()->get( Automattic\WooCommerce\Internal\ProductAttributesLookup\LookupDataStore::class )->on_product_changed( $product );
    }

    // Clear all caches for this product
    wc_delete_product_transients( $pid );
    clean_post_cache( $pid );

    // Verify
    $verify = wc_get_product( $pid );
    echo "  -> saved: price={$verify->get_price()}, regular={$verify->get_regular_price()}\n";
}

// Rebuild WooCommerce product price lookup table if available
if ( function_exists( 'wc_recount_all_terms' ) ) {
    wc_recount_all_terms();
}

// Nuke all WC transients
if ( function_exists( 'wc_get_container' ) ) {
    $wpdb_obj = $GLOBALS['wpdb'];
    $wpdb_obj->query( "DELETE FROM {$wpdb_obj->options} WHERE option_name LIKE '_transient_wc_product%' OR option_name LIKE '_transient_timeout_wc_product%'" );
    echo "\nWC product transients purged from DB.\n";
}

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
    echo "SiteGround cache purged.\n";
}

echo "DONE.\n";
