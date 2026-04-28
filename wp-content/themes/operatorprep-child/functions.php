<?php
/**
 * OperatorPrep Child Theme — functions.php
 * Plowman Industries LLC
 */

// WooCommerce Subscriptions → Tutor LMS auto-enrollment
require_once get_stylesheet_directory() . '/op-enrollment.php';

// Content protection — disable copy/right-click on course pages
require_once get_stylesheet_directory() . '/op-content-protection.php';

// Enqueue parent theme, Google Fonts, and child theme styles
add_action( 'wp_enqueue_scripts', 'operatorprep_child_enqueue_styles' );
function operatorprep_child_enqueue_styles() {
    // Parent theme
    wp_enqueue_style(
        'astra-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    // Google Fonts — Fraunces + Inter Tight + JetBrains Mono (design system v2.1)
    wp_enqueue_style(
        'operatorprep-google-fonts',
        'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,800&family=Inter+Tight:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap',
        array(),
        null
    );
    // Child theme — loads after parent and fonts
    wp_enqueue_style(
        'operatorprep-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'astra-parent-style', 'operatorprep-google-fonts' ),
        wp_get_theme()->get( 'Version' )
    );
}

// Preconnect to Google Fonts for faster loading
add_action( 'wp_head', 'operatorprep_preconnect_fonts', 1 );
function operatorprep_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

// Disable Astra banner/page-title area on the homepage (page ID 35)
// This prevents "Home" H1 from appearing above the hero section
add_filter( 'astra_banner_area', function( $show ) {
    if ( is_front_page() ) return false;
    return $show;
} );

// Also remove the page title via Astra meta on front page
add_filter( 'astra_page_title_enabled', function( $enabled ) {
    if ( is_front_page() ) return false;
    return $enabled;
} );

// Disable entry header on homepage so Astra doesn't inject the H1
add_filter( 'astra_render_header_section', function( $show, $section ) {
    if ( is_front_page() && $section === 'ast-hfb-header' ) return false;
    return $show;
}, 10, 2 );

// ── My Account dashboard: server-side active subscriptions ────────────────

/**
 * AJAX endpoint: returns the list of cert keys the current user has an active
 * WooCommerce subscription for. Used by the My Account dashboard JS.
 */
add_action( 'wp_ajax_op_active_subs', 'op_ajax_get_active_subs' );
function op_ajax_get_active_subs() {
    check_ajax_referer( 'op_active_subs', 'nonce' );

    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        wp_send_json_success( array() );
        return;
    }

    // Product ID → cert key (matches op_get_product_course_map() order)
    $cert_keys = array(
        574 => 't1',  575 => 't2',  576 => 't3',  577 => 't4',  578 => 't5',
        579 => 'd1',  580 => 'd2',  581 => 'd3',  582 => 'd4',  583 => 'd5',
        584 => 'ww1', 585 => 'ww2', 586 => 'ww3', 587 => 'ww4', 588 => 'ww5',
    );

    $active_keys = array();

    if ( function_exists( 'wcs_get_users_subscriptions' ) ) {
        foreach ( wcs_get_users_subscriptions( $user_id ) as $sub ) {
            if ( $sub->get_status() !== 'active' ) {
                continue;
            }
            foreach ( $sub->get_items() as $item ) {
                $pid = $item->get_product_id();
                if ( isset( $cert_keys[ $pid ] ) && ! in_array( $cert_keys[ $pid ], $active_keys, true ) ) {
                    $active_keys[] = $cert_keys[ $pid ];
                }
            }
        }
    }

    wp_send_json_success( $active_keys );
}

/**
 * Inject OP_AJAX config for the My Account dashboard before footer scripts load.
 * Provides the AJAX URL + a fresh nonce so the dashboard JS can call op_active_subs.
 */
add_action( 'wp_footer', 'op_inject_dash_config', 1 );
function op_inject_dash_config() {
    if ( ! is_account_page() ) {
        return;
    }
    $nonce    = wp_create_nonce( 'op_active_subs' );
    $ajax_url = admin_url( 'admin-ajax.php' );
    $logged_in = is_user_logged_in() ? 'true' : 'false';
    echo '<script>window.OP_AJAX={url:"' . esc_js( $ajax_url ) . '",nonce:"' . esc_js( $nonce ) . '",logged_in:' . $logged_in . '};</script>' . "\n";
}
