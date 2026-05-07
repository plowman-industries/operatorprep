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

    // Admins see all certifications (for testing and support purposes)
    if ( current_user_can( 'manage_options' ) ) {
        wp_send_json_success( array_values( $cert_keys ) );
        return;
    }

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
 * Inject OP_AJAX config + full dashboard JS via wp_footer on My Account pages.
 * Doing this in wp_footer keeps the JS out of post_content so wpautop never touches it.
 */
add_action( 'wp_footer', 'op_inject_dash_config', 1 );
function op_inject_dash_config() {
    if ( ! is_account_page() ) {
        return;
    }
    $nonce     = wp_create_nonce( 'op_active_subs' );
    $ajax_url  = admin_url( 'admin-ajax.php' );
    $logged_in = is_user_logged_in() ? 'true' : 'false';
    echo '<script>window.OP_AJAX={url:"' . esc_js( $ajax_url ) . '",nonce:"' . esc_js( $nonce ) . '",logged_in:' . $logged_in . '};</script>' . "\n";
}

/**
 * Remove wpautop on the My Account page so the inline <script> in page content
 * is not mangled with <p> tags that break the JavaScript.
 */
add_action( 'wp', function() {
    if ( is_account_page() ) {
        remove_filter( 'the_content', 'wpautop' );
        remove_filter( 'the_content', 'wpautop_fix' );
    }
} );

// ── SEO Meta Descriptions ─────────────────────────────────────────────────
/**
 * Inject <meta name="description"> for key pages.
 * Fires at priority 1 (before any SEO plugin) so pages without a plugin-set
 * description still get a meaningful tag. If an SEO plugin later outputs its
 * own tag, search engines use the first one — which will be ours.
 */
add_action( 'wp_head', 'op_inject_meta_description', 1 );
function op_inject_meta_description() {
    $desc = '';

    // ── Static page descriptions ──────────────────────────────────────────
    $page_descs = array(
        // Core pages
        'pricing'        => 'Get unlimited access to all 15 water and wastewater operator certification prep courses for $19.99/month. Practice tests, study guides, flashcards, and math drills. Cancel anytime.',
        'certifications' => 'Browse all 15 water and wastewater operator certification prep courses. Treatment grades T1-T5, Distribution D1-D5, and Wastewater WW1-WW5. Start studying today.',
        'about'          => 'OperatorPrep is designed by water and wastewater professionals to help operators pass their state certification exams. Prep materials for all 15 certification levels.',
        'faq'            => 'Common questions about OperatorPrep exam prep -- what is included in each course, how the practice tests work, and how to prepare for your water operator certification.',
        'contact-us'     => 'Contact the OperatorPrep support team. We respond to all questions about certification exam prep within 24 hours.',
        // Treatment cert hubs
        't1'  => 'Grade 1 Water Treatment Operator exam prep. Practice tests, study guides, flashcards, and math drills for the T1 certification. Start your free trial today.',
        't2'  => 'Grade 2 Water Treatment Operator exam prep. In-depth practice tests, study guides, and flashcards aligned to the T2 certification exam.',
        't3'  => 'Grade 3 Water Treatment Operator exam prep. Comprehensive practice exams, process simulations, and study materials for the T3 certification.',
        't4'  => 'Grade 4 Water Treatment Operator exam prep. Advanced practice tests and study guides for the T4 water treatment certification.',
        't5'  => 'Grade 5 Water Treatment Operator exam prep. Expert-level practice tests and study materials for the highest-grade treatment certification.',
        // Distribution cert hubs
        'd1'  => 'Grade 1 Water Distribution Operator exam prep. Practice tests, study guides, and flashcards for the D1 distribution certification.',
        'd2'  => 'Grade 2 Water Distribution Operator exam prep. Targeted practice exams and study guides for the D2 distribution certification.',
        'd3'  => 'Grade 3 Water Distribution Operator exam prep. Comprehensive practice tests and study materials for the D3 certification.',
        'd4'  => 'Grade 4 Water Distribution Operator exam prep. Advanced practice exams for the D4 water distribution certification.',
        'd5'  => 'Grade 5 Water Distribution Operator exam prep. Expert practice tests and study guides for the highest-grade distribution certification.',
        // Wastewater cert hubs
        'ww1' => 'Grade 1 Wastewater Treatment Operator exam prep. Practice tests, study guides, and flashcards for the WW1 certification exam.',
        'ww2' => 'Grade 2 Wastewater Treatment Operator exam prep. Targeted practice exams and study materials for the WW2 certification.',
        'ww3' => 'Grade 3 Wastewater Treatment Operator exam prep. Comprehensive practice tests and study guides for the WW3 wastewater certification.',
        'ww4' => 'Grade 4 Wastewater Treatment Operator exam prep. Advanced practice exams for the WW4 wastewater treatment certification.',
        'ww5' => 'Grade 5 Wastewater Treatment Operator exam prep. Expert practice tests and study materials for the highest-grade wastewater certification.',
    );

    if ( is_front_page() ) {
        $desc = 'Pass your water or wastewater operator certification exam with OperatorPrep. Practice tests, study guides, flashcards, and math drills for T1-T5, D1-D5, and WW1-WW5 certifications.';
    } elseif ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        if ( isset( $page_descs[ $slug ] ) ) {
            $desc = $page_descs[ $slug ];
        }
    }

    if ( $desc ) {
        echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
    }
}

/**
 * Inject a site-wide footer before </body>.
 * The Astra footer template was removed; this replaces it with a minimal
 * branded footer that satisfies ad platform policy link requirements.
 */
add_action( 'wp_footer', 'op_render_site_footer', 5 );
function op_render_site_footer() {
    $year = date( 'Y' );
    ?>
    <footer class="op-site-footer" aria-label="Site footer">
        <div class="op-site-footer__inner">
            <div class="op-site-footer__brand">
                <span class="op-site-footer__logo">OperatorPrep</span>
                <span class="op-site-footer__tagline">Water &amp; Wastewater Operator Exam Prep</span>
            </div>
            <nav class="op-site-footer__links" aria-label="Footer navigation">
                <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>
                <a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>">Terms of Service</a>
                <a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>">Refund Policy</a>
                <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a>
                <a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a>
            </nav>
            <p class="op-site-footer__copy">&copy; <?php echo esc_html( $year ); ?> Plowman Industries LLC. All rights reserved.</p>
        </div>
    </footer>
    <?php
}
