<?php
/**
 * fix-footer.php
 * Run via: wp eval-file ... --allow-root
 * 1. Finds and removes the opp-footer-fix CSS that hides the site footer
 * 2. Adds meta description to homepage
 */

// ── Remove opp-footer-fix from WordPress custom CSS ──────────────────────
$custom_css = wp_get_custom_css();
if ( strpos( $custom_css, 'opp-footer-fix' ) !== false || strpos( $custom_css, 'site-footer' ) !== false ) {
    $cleaned = preg_replace( '/#opp-footer-fix[^}]*}|footer\.site-footer\s*\{[^}]*display\s*:\s*none[^}]*\}/i', '', $custom_css );
    wp_update_custom_css_post( $cleaned );
    echo "Removed opp-footer-fix from Customizer custom CSS.\n";
} else {
    echo "opp-footer-fix not found in Customizer custom CSS.\n";
}

// Also check astra custom CSS option
$astra_css = get_option( 'astra-settings', array() );
if ( isset( $astra_css['custom-css'] ) && strpos( $astra_css['custom-css'], 'opp-footer-fix' ) !== false ) {
    $astra_css['custom-css'] = preg_replace( '/#opp-footer-fix[^}]*}|footer\.site-footer\s*\{[^}]*\}/i', '', $astra_css['custom-css'] );
    update_option( 'astra-settings', $astra_css );
    echo "Removed opp-footer-fix from Astra settings.\n";
}

// Check jetpack/plugin custom css
$jetpack_css = get_option( 'safecss', '' );
if ( strpos( $jetpack_css, 'opp-footer-fix' ) !== false ) {
    update_option( 'safecss', preg_replace( '/#opp-footer-fix[^}]*}/i', '', $jetpack_css ) );
    echo "Removed from Jetpack safecss.\n";
}

// ── Check where opp-footer-fix actually comes from ────────────────────────
echo "\n--- SEARCHING for opp-footer-fix source ---\n";
global $wpdb;
$results = $wpdb->get_results( "SELECT option_name, LEFT(option_value, 200) as snippet FROM {$wpdb->options} WHERE option_value LIKE '%opp-footer-fix%' LIMIT 10" );
foreach ( $results as $r ) {
    echo "Found in option: {$r->option_name}\n  snippet: {$r->snippet}\n";
}
if ( empty( $results ) ) {
    echo "Not found in wp_options.\n";
}

// Check postmeta
$pmeta = $wpdb->get_results( "SELECT post_id, meta_key, LEFT(meta_value,200) as snippet FROM {$wpdb->postmeta} WHERE meta_value LIKE '%opp-footer-fix%' LIMIT 5" );
foreach ( $pmeta as $r ) {
    echo "Found in postmeta post_id={$r->post_id} key={$r->meta_key}\n  {$r->snippet}\n";
}

// ── Add meta description to homepage ─────────────────────────────────────
$home_id = (int) get_option( 'page_on_front' );
if ( $home_id ) {
    // Try Yoast
    if ( defined( 'WPSEO_VERSION' ) ) {
        update_post_meta( $home_id, '_yoast_wpseo_metadesc', 'Prepare for your water treatment or wastewater operator certification exam with practice tests, flashcards, math drills, and study guides for T1–T5, D1–D5, and WW1–WW5.' );
        echo "Set Yoast meta description on homepage.\n";
    }
    // Try RankMath
    if ( defined( 'RANK_MATH_VERSION' ) ) {
        update_post_meta( $home_id, 'rank_math_description', 'Prepare for your water treatment or wastewater operator certification exam with practice tests, flashcards, math drills, and study guides for T1–T5, D1–D5, and WW1–WW5.' );
        echo "Set RankMath meta description on homepage.\n";
    }
    // Generic fallback
    update_post_meta( $home_id, '_meta_description', 'Prepare for your water treatment or wastewater operator certification exam with practice tests, flashcards, math drills, and study guides for T1–T5, D1–D5, and WW1–WW5.' );
    echo "Set generic meta description on homepage (ID: {$home_id}).\n";
}

// Also check which SEO plugin is active
$active_plugins = get_option( 'active_plugins', array() );
$seo_plugins = array_filter( $active_plugins, fn($p) => stripos($p, 'yoast') !== false || stripos($p, 'rank-math') !== false || stripos($p, 'seo') !== false || stripos($p, 'all-in-one-seo') !== false );
echo "\nActive SEO plugins: " . ( $seo_plugins ? implode(', ', $seo_plugins) : 'none found' ) . "\n";

echo "\nDONE.\n";
