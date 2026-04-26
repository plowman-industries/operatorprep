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
