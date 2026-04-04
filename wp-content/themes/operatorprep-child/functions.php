<?php
/**
 * OperatorPrep Child Theme — functions.php
 * Plowman Industries LLC
 */

// Enqueue parent theme, Google Fonts, and child theme styles
add_action( 'wp_enqueue_scripts', 'operatorprep_child_enqueue_styles' );
function operatorprep_child_enqueue_styles() {
    // Parent theme
    wp_enqueue_style(
        'astra-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    // Google Fonts — Barlow Condensed + Barlow + DM Mono
    wp_enqueue_style(
        'operatorprep-google-fonts',
        'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=Barlow:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    // Child theme (depends on parent + fonts)
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

// Replace button text and force white color via output buffer
add_action( 'template_redirect', 'operatorprep_start_ob' );
function operatorprep_start_ob() {
    if ( is_front_page() ) {
        ob_start( 'operatorprep_replace_output' );
    }
}

function operatorprep_replace_output( $html ) {
    // Replace button text (handles whitespace around arrow)
    $html = preg_replace(
        '/(\\x{2192}\s*)Start Studying Free/u',
        '${1}Start Studying - \$19.99',
        $html
    );
    return $html;
}

// Force white text on primary CTA buttons
add_action( 'wp_head', 'operatorprep_button_white_text', 99 );
function operatorprep_button_white_text() {
    echo '<style>
        .op-btn-primary,
        .op-btn-primary:hover,
        .op-btn-primary:visited {
            color: #ffffff !important;
        }
    </style>' . "\n";
}
