<?php
/**
 * op-enrollment.php
 * WooCommerce Subscriptions → Tutor LMS Auto-Enrollment
 * Plowman Industries LLC
 *
 * Hooks into WooCommerce Subscription status changes to automatically
 * enroll or unenroll users in the corresponding Tutor LMS course.
 *
 * Product → Course mapping (15 certifications):
 *   T1–T5, D1–D5, WW1–WW5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Returns the product ID → course ID mapping for all 15 certifications.
 *
 * @return array<int,int> Keyed by WooCommerce product ID, value is Tutor LMS course ID.
 */
function op_get_product_course_map() {
    return array(
        574 => 156,  // T1
        575 => 22,   // T2
        576 => 153,  // T3
        577 => 281,  // T4
        578 => 943,  // T5
        579 => 589,  // D1
        580 => 326,  // D2
        581 => 592,  // D3
        582 => 597,  // D4
        583 => 600,  // D5
        584 => 375,  // WW1
        585 => 418,  // WW2
        586 => 461,  // WW3
        587 => 504,  // WW4
        588 => 944,  // WW5
    );
}

/**
 * Enroll a user in all courses linked to the subscription's products.
 *
 * @param WC_Subscription $subscription
 */
function op_enroll_user_from_subscription( $subscription ) {
    if ( ! function_exists( 'tutor_utils' ) ) {
        return;
    }

    $user_id = $subscription->get_user_id();
    if ( ! $user_id ) {
        return;
    }

    $map = op_get_product_course_map();

    foreach ( $subscription->get_items() as $item ) {
        $product_id = $item->get_product_id();
        if ( isset( $map[ $product_id ] ) ) {
            $course_id = $map[ $product_id ];
            // do_enroll( $course_id, $user_id ) — returns enrollment ID or false
            tutor_utils()->do_enroll( $course_id, 0, $user_id );
        }
    }
}

/**
 * Unenroll a user from all courses linked to the subscription's products.
 *
 * @param WC_Subscription $subscription
 */
function op_unenroll_user_from_subscription( $subscription ) {
    if ( ! function_exists( 'tutor_utils' ) ) {
        return;
    }
    $user_id = $subscription->get_user_id();
    if ( ! $user_id ) { return; }
    $map = op_get_product_course_map();
    foreach ( $subscription->get_items() as $item ) {
        $product_id = $item->get_product_id();
        if ( isset( $map[ $product_id ] ) ) {
            tutor_utils()->cancel_course_enrol( $map[ $product_id ], $user_id );
        }
    }
}

// ── Enrollment triggers ────────────────────────────────────────────────────

/**
 * Enroll when a subscription becomes active (new purchase or reactivation).
 */
add_action( 'woocommerce_subscription_status_active', 'op_enroll_user_from_subscription' );

// ── Unenrollment triggers ──────────────────────────────────────────────────

/**
 * Unenroll when a subscription is cancelled.
 */
add_action( 'woocommerce_subscription_status_cancelled', 'op_unenroll_user_from_subscription' );

/**
 * Unenroll when a subscription expires.
 */
add_action( 'woocommerce_subscription_status_expired', 'op_unenroll_user_from_subscription' );

/**
 * Unenroll when a subscription is put on-hold (payment failed / manual hold).
 */
add_action( 'woocommerce_subscription_status_on-hold', 'op_unenroll_user_from_subscription' );

// ── Enrollment gate — block free self-enrollment ───────────────────────────

/**
 * Block free Tutor LMS enrollment on protected courses.
 *
 * Fires at priority 1 on the Tutor LMS "Enroll Now" AJAX action — before
 * Tutor processes the request. If the user doesn't have an active
 * WooCommerce subscription for this course's product, we block it and
 * return a JSON error with a link to purchase.
 *
 * Admins bypass this check so manual enrollment from wp-admin still works.
 */
add_action( 'wp_ajax_tutor_enroll_now', 'op_gate_enrollment_by_subscription', 1 );
function op_gate_enrollment_by_subscription() {
    // Admins can always enroll manually
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }

    $course_id = isset( $_POST['course_id'] ) ? absint( $_POST['course_id'] ) : 0;
    if ( ! $course_id ) {
        return;
    }

    // Build reverse map: course_id => product_id
    $map               = op_get_product_course_map();
    $course_to_product = array_flip( $map );

    // If this course isn't in our map, let Tutor handle it normally
    if ( ! isset( $course_to_product[ $course_id ] ) ) {
        return;
    }

    $product_id = $course_to_product[ $course_id ];
    $user_id    = get_current_user_id();

    // Check for an active WooCommerce subscription covering this product
    if ( function_exists( 'wcs_user_has_subscription' )
        && wcs_user_has_subscription( $user_id, $product_id, 'active' ) ) {
        return; // Subscription confirmed — let Tutor proceed
    }

    // No active subscription — block and point them to the product page
    $product_url = get_permalink( $product_id );
    if ( ! $product_url ) {
        $product_url = wc_get_page_permalink( 'shop' );
    }

    wp_send_json_error( array(
        'message' => 'An active subscription is required to access this course. '
            . '<a href="' . esc_url( $product_url ) . '">Subscribe to unlock →</a>',
    ) );
    exit;
}
