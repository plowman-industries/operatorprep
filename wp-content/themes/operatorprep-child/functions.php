<?php
/**
 * OperatorPrep Child Theme — functions.php
 * Plowman Industries LLC
 * v4 — JS button text via wp_head
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

// Front page customizations: white CTA text + button text replacement
add_action( 'wp_head', 'operatorprep_front_page_customizations', 99 );
function operatorprep_front_page_customizations() {
    if ( ! is_front_page() ) return;
    echo '<!-- OP child theme v4 -->' . "\n";
    echo '<style>
        .op-btn-primary,
        .op-btn-primary:hover,
        .op-btn-primary:visited {
            color: #ffffff !important;
        }
    </style>' . "\n";
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".op-btn-primary").forEach(function(btn) {
            var t = btn.textContent;
            if (t.indexOf("Start Studying Free") !== -1) {
                btn.textContent = "\u2192 Start Studying - \u002419.99";
            }
        });
    });
    </script>' . "\n";
}
