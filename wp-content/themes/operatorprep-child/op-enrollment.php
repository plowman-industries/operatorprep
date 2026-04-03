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
