// OPP - Access Gating
// Central access control for all study content shortcodes.
// Maps cert level slugs to WooCommerce product IDs and provides
// opp_has_access() and opp_render_access_denied() for all render snippets.

function opp_get_product_id( $level ) {
    $map = [
        't1'=>574, 't2'=>575, 't3'=>576, 't4'=>577, 't5'=>578,
        'd1'=>579, 'd2'=>580, 'd3'=>581, 'd4'=>582, 'd5'=>583,
        'ww1'=>584, 'ww2'=>585, 'ww3'=>586, 'ww4'=>587, 'ww5'=>588,
    ];
    return isset($map[strtolower($level)]) ? $map[strtolower($level)] : null;
}

function opp_has_access( $product_id ) {
    if ( current_user_can('manage_options') ) return true;
    if ( ! is_user_logged_in() ) return false;
    $uid = get_current_user_id();
    if ( function_exists('wcs_user_has_subscription') )
        return wcs_user_has_subscription( $uid, $product_id, 'active' );
    $user = get_userdata( $uid );
    return function_exists('wc_customer_bought_product')
        ? wc_customer_bought_product( $user ? $user->user_email : '', $uid, $product_id )
        : false;
}

function opp_render_access_denied( $product_id, $label ) {
    $checkout_url = esc_url( add_query_arg( 'add-to-cart', $product_id, wc_get_checkout_url() ) );
    $login_url    = esc_url( wp_login_url( get_permalink() ) );
    ob_start();
    ?>
    <div class="opp-gate-card">
        <div class="opp-gate-icon">&#128274;</div>
        <h2 class="opp-gate-title"><?php echo esc_html( $label ); ?> Study Content</h2>
        <p class="opp-gate-message">An active <strong><?php echo esc_html( $label ); ?></strong> subscription is required to access this material.</p>
        <div class="opp-gate-actions">
            <?php if ( ! is_user_logged_in() ) : ?>
            <a href="<?php echo $login_url; ?>" class="opp-gate-btn opp-gate-btn--secondary">Log In</a>
            <?php endif; ?>
            <a href="<?php echo $checkout_url; ?>" class="opp-gate-btn opp-gate-btn--primary">Get Access</a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}