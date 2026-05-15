<?php
// Free 3-day trial system. One trial per email, one cert chosen at signup.

define( 'OPP_TRIAL_DAYS', 3 );

// Product ID -> cert slug map (mirrors snippet 41).
function opp_trial_cert_map() {
    return [
        574 => 't1', 575 => 't2', 576 => 't3', 577 => 't4', 578 => 't5',
        579 => 'd1', 580 => 'd2', 581 => 'd3', 582 => 'd4', 583 => 'd5',
        584 => 'ww1', 585 => 'ww2', 586 => 'ww3', 587 => 'ww4', 588 => 'ww5',
    ];
}

// Returns true if the current user has an active trial for this product.
function opp_has_trial_access( $product_id ) {
    if ( ! is_user_logged_in() ) return false;
    $uid        = get_current_user_id();
    $started    = (int) get_user_meta( $uid, 'opp_trial_started', true );
    if ( ! $started ) return false;
    $trial_cert = (int) get_user_meta( $uid, 'opp_trial_cert', true );
    if ( $trial_cert !== (int) $product_id ) return false;
    return time() < $started + ( OPP_TRIAL_DAYS * DAY_IN_SECONDS );
}

// Returns true if this email has ever had a trial (active or expired).
function opp_email_has_used_trial( $email ) {
    $users = get_users( [
        'search'         => sanitize_email( $email ),
        'search_columns' => [ 'user_email' ],
        'fields'         => 'ids',
        'number'         => 1,
    ] );
    if ( empty( $users ) ) return false;
    return (bool) get_user_meta( $users[0], 'opp_trial_started', true );
}

// Starts the trial for a user. Returns false if email already used a trial.
function opp_start_trial( $user_id, $product_id ) {
    $email = get_userdata( $user_id )->user_email;
    if ( opp_email_has_used_trial( $email ) ) return false;
    update_user_meta( $user_id, 'opp_trial_started', time() );
    update_user_meta( $user_id, 'opp_trial_cert',    (int) $product_id );
    return true;
}

// AJAX: handle trial signup form (nopriv = logged-out visitors).
add_action( 'wp_ajax_nopriv_opp_start_trial', 'opp_ajax_start_trial' );
add_action( 'wp_ajax_opp_start_trial',        'opp_ajax_start_trial' );
function opp_ajax_start_trial() {
    check_ajax_referer( 'opp_trial_nonce', 'nonce' );

    $username   = sanitize_user( wp_unslash( $_POST['username'] ?? '' ) );
    $email      = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $password   = wp_unslash( $_POST['password'] ?? '' );
    $product_id = (int) ( $_POST['product_id'] ?? 0 );

    if ( ! $username || ! is_email( $email ) || ! $password || ! $product_id ) {
        wp_send_json_error( [ 'message' => 'All fields are required, including a certification selection.' ] );
    }

    $valid_ids = array_keys( opp_trial_cert_map() );
    if ( ! in_array( $product_id, $valid_ids, true ) ) {
        wp_send_json_error( [ 'message' => 'Invalid certification selected.' ] );
    }

    if ( opp_email_has_used_trial( $email ) ) {
        wp_send_json_error( [ 'message' => 'This email has already been used for a free trial.' ] );
    }

    if ( email_exists( $email ) ) {
        wp_send_json_error( [ 'message' => 'An account with this email already exists. Free trials are limited to new accounts.' ] );
    }

    if ( username_exists( $username ) ) {
        wp_send_json_error( [ 'message' => 'That username is already taken. Please choose another.' ] );
    }

    $user_id = wp_create_user( $username, $password, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( [ 'message' => $user_id->get_error_message() ] );
    }

    opp_start_trial( $user_id, $product_id );

    $user = get_user_by( 'id', $user_id );
    wp_set_current_user( $user_id, $user->user_login );
    wp_set_auth_cookie( $user_id );
    do_action( 'wp_login', $user->user_login, $user );

    $map      = opp_trial_cert_map();
    $slug     = $map[ $product_id ] ?? '';
    $redirect = $slug ? home_url( '/' . $slug . '/' ) : home_url( '/my-account/' );
    wp_send_json_success( [ 'redirect' => $redirect ] );
}

// SEO: inject a descriptive title tag on the /free-trial/ page.
add_filter( 'document_title_parts', function( $title ) {
    if ( is_page( 'free-trial' ) ) {
        $title['title'] = 'Free 3-Day Trial &mdash; Water &amp; Wastewater Operator Exam Prep';
        $title['site']  = 'OperatorPrep';
    }
    return $title;
} );

// Shortcode: [opp_free_trial_form]
add_shortcode( 'opp_free_trial_form', 'opp_render_trial_form' );
function opp_render_trial_form() {
    // If user already has/had a trial, show a continuation link.
    if ( is_user_logged_in() ) {
        $uid         = get_current_user_id();
        $trial_cert  = (int) get_user_meta( $uid, 'opp_trial_cert', true );
        $trial_start = get_user_meta( $uid, 'opp_trial_started', true );
        if ( $trial_cert && $trial_start ) {
            $map  = opp_trial_cert_map();
            $slug = $map[ $trial_cert ] ?? '';
            $link = $slug ? home_url( '/' . $slug . '/' ) : home_url( '/my-account/' );
            if ( opp_has_trial_access( $trial_cert ) ) {
                return '<div class="opp-trial-notice">You have an active trial. <a href="' . esc_url( $link ) . '">Continue studying &rarr;</a></div>';
            } else {
                $checkout = esc_url( add_query_arg( 'add-to-cart', $trial_cert, wc_get_checkout_url() ) );
                return '<div class="opp-trial-notice">Your free trial has ended. <a href="' . $checkout . '">Subscribe to keep studying &rarr;</a></div>';
            }
        }
    }

    $certs = [
        'Water Treatment'    => [ 'T1' => 574, 'T2' => 575, 'T3' => 576, 'T4' => 577, 'T5 (CA)' => 578 ],
        'Water Distribution' => [ 'D1' => 579, 'D2' => 580, 'D3' => 581, 'D4' => 582, 'D5 (CA)' => 583 ],
        'Wastewater'         => [ 'WW1' => 584, 'WW2' => 585, 'WW3' => 586, 'WW4' => 587, 'WW5 (CA)' => 588 ],
    ];

    ob_start();
    ?>
    <div class="opp-trial-wrap">

      <!-- Header -->
      <div class="opp-trial-header">
        <h1 class="opp-trial-title">Try OperatorPrep Free &#x2014; 3 Days, No Card</h1>
        <p class="opp-trial-sub">Pick one certification. Create an account. Start studying immediately.</p>
        <p class="opp-trial-cred">Built by an active CA Grade 5 operator. Not a test-prep company.</p>
      </div>

      <!-- What's included -->
      <div class="opp-trial-included">
        <p class="opp-trial-included__heading">What you get during your trial</p>
        <ul class="opp-trial-included__list">
          <li>&#x2713;&ensp;Full practice test bank for your chosen certification</li>
          <li>&#x2713;&ensp;Flashcards covering key concepts and definitions</li>
          <li>&#x2713;&ensp;Math drills with step-by-step operator solutions</li>
        </ul>
        <p class="opp-trial-included__sub">All content for one cert, for 3 days. After your trial, nothing auto-charges &#x2014; subscribe for $19.99/mo only if you want to continue.</p>
      </div>

      <form id="opp-trial-form" class="opp-trial-form" novalidate>
        <?php wp_nonce_field( 'opp_trial_nonce', 'nonce' ); ?>

        <!-- Cert picker -->
        <div class="opp-trial-section">
          <p class="opp-trial-label">Choose Your Certification <span class="opp-trial-label__hint">(select one to continue)</span></p>
          <?php foreach ( $certs as $track => $items ) : ?>
          <div class="opp-trial-track">
            <div class="opp-trial-track-name"><?php echo esc_html( $track ); ?></div>
            <div class="opp-trial-certs">
              <?php foreach ( $items as $label => $pid ) : ?>
              <label class="opp-cert-radio">
                <input type="radio" name="product_id" value="<?php echo (int) $pid; ?>" required>
                <span class="opp-cert-radio__label"><?php echo esc_html( $label ); ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Account fields -->
        <div class="opp-trial-section opp-trial-fields">
          <label class="opp-trial-field-label" for="trial-username">Username</label>
          <input class="opp-trial-input" type="text" id="trial-username" name="username" required autocomplete="username" placeholder="yourhandle">

          <label class="opp-trial-field-label" for="trial-email">Email Address</label>
          <input class="opp-trial-input" type="email" id="trial-email" name="email" required autocomplete="email" placeholder="you@example.com">

          <label class="opp-trial-field-label" for="trial-password">Password</label>
          <input class="opp-trial-input" type="password" id="trial-password" name="password" required autocomplete="new-password" placeholder="min 8 characters">
        </div>

        <!-- Error message -->
        <div id="opp-trial-msg" class="opp-trial-msg" style="display:none;" role="alert"></div>

        <!-- Fine print ABOVE submit — reassurance, not legal disclosure -->
        <p class="opp-trial-fine">3 days &middot; one cert &middot; no card required &middot; one trial per email &middot; nothing auto-charges</p>

        <button type="submit" class="opp-gate-btn opp-gate-btn--primary opp-trial-submit">
          Start Free Trial &#x2192;
        </button>

        <!-- Post-submit expectation -->
        <p class="opp-trial-expect">After signup you'll be taken directly to your study content. Check your spam folder for the confirmation email if it doesn't arrive within a few minutes.</p>

      </form>
    </div>

    <style>
    .opp-trial-wrap{max-width:680px;margin:0 auto;padding:40px 24px;}
    .opp-trial-header{text-align:center;margin-bottom:32px;}
    .opp-trial-title{font-size:clamp(22px,4vw,34px);font-weight:700;margin:0 0 10px;}
    .opp-trial-sub{color:#64748b;line-height:1.6;margin:0 0 6px;}
    .opp-trial-cred{font-size:13px;color:#94a3b8;margin:0;font-style:italic;}

    /* What's included box */
    .opp-trial-included{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:20px 24px;margin-bottom:32px;}
    .opp-trial-included__heading{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;margin:0 0 12px;}
    .opp-trial-included__list{list-style:none;padding:0;margin:0 0 12px;display:flex;flex-direction:column;gap:8px;font-size:15px;font-weight:500;}
    .opp-trial-included__sub{font-size:13px;color:#64748b;margin:0;line-height:1.5;}

    .opp-trial-section{margin-bottom:32px;}
    .opp-trial-label{font-weight:600;margin:0 0 16px;font-size:15px;}
    .opp-trial-label__hint{font-weight:400;color:#94a3b8;font-size:13px;}
    .opp-trial-track{margin-bottom:16px;}
    .opp-trial-track-name{font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#64748b;margin-bottom:8px;}
    .opp-trial-certs{display:flex;flex-wrap:wrap;gap:8px;}
    .opp-cert-radio{cursor:pointer;}
    .opp-cert-radio input{position:absolute;opacity:0;width:0;height:0;}
    .opp-cert-radio__label{display:inline-block;padding:6px 14px;border:2px solid #e2e8f0;border-radius:6px;font-size:14px;font-weight:600;transition:border-color .15s,background .15s;}
    .opp-cert-radio input:checked + .opp-cert-radio__label{border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8;}
    .opp-cert-radio__label:hover{border-color:#93c5fd;}
    .opp-trial-fields{display:flex;flex-direction:column;gap:12px;}
    .opp-trial-field-label{font-size:14px;font-weight:600;margin-bottom:4px;}
    .opp-trial-input{width:100%;padding:10px 14px;border:2px solid #e2e8f0;border-radius:6px;font-size:15px;transition:border-color .15s;box-sizing:border-box;}
    .opp-trial-input:focus{outline:none;border-color:#1d4ed8;}
    .opp-trial-msg{padding:12px 16px;background:#fef2f2;border:1px solid #fca5a5;border-radius:6px;color:#dc2626;font-size:14px;margin-bottom:16px;}
    .opp-trial-fine{text-align:center;font-size:13px;color:#64748b;margin:0 0 14px;font-weight:500;}
    .opp-trial-submit{width:100%;font-size:17px;padding:14px 24px;margin-bottom:10px;}
    .opp-trial-expect{text-align:center;font-size:13px;color:#94a3b8;margin:0;line-height:1.5;}
    .opp-trial-notice{padding:16px 20px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;font-size:15px;}
    </style>

    <script>
    (function() {
      var form = document.getElementById('opp-trial-form');
      if (!form) return;
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var btn = form.querySelector('.opp-trial-submit');
        var msg = document.getElementById('opp-trial-msg');

        // Validate cert selection explicitly with a friendly error.
        if (!form.querySelector('input[name="product_id"]:checked')) {
          msg.textContent = 'Please select a certification before continuing.';
          msg.style.display = 'block';
          form.querySelector('.opp-trial-section').scrollIntoView({behavior:'smooth',block:'start'});
          return;
        }

        msg.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'Setting up your trial…';

        var data = new FormData(form);
        data.append('action', 'opp_start_trial');
        fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
          method: 'POST',
          body: data,
          credentials: 'same-origin'
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
          if (res.success) {
            btn.textContent = 'You’re in — taking you to your content…';
            window.location.href = res.data.redirect;
          } else {
            msg.textContent = res.data.message;
            msg.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = 'Start Free Trial &#x2192;';
          }
        })
        .catch(function() {
          msg.textContent = 'Something went wrong. Please try again.';
          msg.style.display = 'block';
          btn.disabled = false;
          btn.innerHTML = 'Start Free Trial &#x2192;';
        });
      });
    })();
    </script>
    <?php
    return ob_get_clean();
}
