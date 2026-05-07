<?php
/**
 * fix-contact.php
 * Run via: wp eval-file wp-content/themes/operatorprep-child/fix-contact.php --allow-root
 *
 * 1. Diagnoses what is redirecting /contact-us/ for logged-out users
 * 2. Removes the restriction
 * 3. Creates/repairs the CF7 contact form and wires it to the page
 */

wp_set_current_user( 1 );

// ── 1. Find the contact page ──────────────────────────────────────────────
$page = get_page_by_path( 'contact-us' );
if ( ! $page ) {
    echo "ERR: contact-us page not found\n";
    exit(1);
}
$page_id = $page->ID;
echo "Contact page ID: {$page_id}\n";
echo "Status: {$page->post_status}\n";

// ── 2. Dump all meta for inspection ───────────────────────────────────────
$all_meta = get_post_meta( $page_id );
echo "\n--- ALL META ---\n";
foreach ( $all_meta as $key => $val ) {
    echo "  {$key} => " . json_encode( $val[0] ) . "\n";
}

// ── 3. Check TutorLMS global login-required option ────────────────────────
echo "\n--- TUTOR OPTIONS (login/redirect related) ---\n";
$tutor_opts = get_option( 'tutor_option', array() );
$relevant   = array( 'login_required', 'tutor_login_page', 'redirect_to_login', 'enable_guest_mode' );
foreach ( $relevant as $k ) {
    if ( isset( $tutor_opts[ $k ] ) ) {
        echo "  {$k}: " . json_encode( $tutor_opts[ $k ] ) . "\n";
    }
}

// ── 4. Remove known restriction meta ─────────────────────────────────────
$restriction_keys = array(
    '_tutor_course_id', 'tutor_course_id', '_tutor_prerequisites',
    '_wc_memberships_force_public', '_membership_required',
    '_restrict_content', 'restrict_page_to',
    '_members_access_rules', '_pods_meta_restrict',
    'page_restricted', '_page_restricted', 'login_required',
);
foreach ( $restriction_keys as $k ) {
    if ( metadata_exists( 'post', $page_id, $k ) ) {
        delete_post_meta( $page_id, $k );
        echo "Removed meta: {$k}\n";
    }
}

// Make sure page is published and public
wp_update_post( array( 'ID' => $page_id, 'post_status' => 'publish' ) );
echo "Page status set to publish.\n";

// ── 5. Check if CF7 is active ─────────────────────────────────────────────
if ( ! post_type_exists( 'wpcf7_contact_form' ) ) {
    echo "ERR: CF7 not active\n";
    exit(1);
}

// ── 6. Find or create the contact form ───────────────────────────────────
$existing = get_posts( array(
    'post_type'      => 'wpcf7_contact_form',
    'numberposts'    => -1,
    'post_status'    => 'publish',
) );

echo "\n--- EXISTING CF7 FORMS ---\n";
foreach ( $existing as $f ) {
    echo "  ID: {$f->ID} — {$f->post_title}\n";
}

// Use existing "Contact form 1" or create a new one
$form_id = 0;
foreach ( $existing as $f ) {
    if ( stripos( $f->post_title, 'contact' ) !== false ) {
        $form_id = $f->ID;
        echo "Using existing form ID: {$form_id}\n";
        break;
    }
}

if ( ! $form_id ) {
    // Create a new CF7 form
    $form_id = wp_insert_post( array(
        'post_type'   => 'wpcf7_contact_form',
        'post_title'  => 'Contact Us',
        'post_status' => 'publish',
    ) );
    echo "Created new CF7 form ID: {$form_id}\n";
}

// ── 7. Set form body and mail config ─────────────────────────────────────
$form_body = '<label>Your Name<br>[text* your-name placeholder "Full Name"]</label>
<label>Your Email<br>[email* your-email placeholder "your@email.com"]</label>
<label>Subject<br>[text your-subject placeholder "How can we help?"]</label>
<label>Message<br>[textarea* your-message rows:6 placeholder "Your message..."]</label>
[submit "Send Message"]';

update_post_meta( $form_id, '_form', $form_body );

// Mail settings — delivers to support@operatorprep.com
$mail = array(
    'subject'    => '[OperatorPrep] [your-subject]',
    'sender'     => 'OperatorPrep <noreply@operatorprep.com>',
    'body'       => "From: [your-name] <[your-email]>\n\nSubject: [your-subject]\n\nMessage:\n[your-message]",
    'recipient'  => 'support@operatorprep.com',
    'additional_headers' => 'Reply-To: [your-name] <[your-email]>',
    'attachments' => '',
    'use_html'   => 0,
    'exclude_blank' => 0,
);
update_post_meta( $form_id, '_mail', $mail );

// Confirmation message
update_post_meta( $form_id, '_messages', array(
    'mail_sent_ok'      => 'Thank you — your message has been sent. We\'ll respond within 24 hours.',
    'mail_sent_ng'      => 'There was an error sending your message. Please email support@operatorprep.com directly.',
    'validation_error'  => 'Please fill in all required fields.',
    'spam'              => 'Your message was flagged as spam.',
    'accept_terms'      => '',
    'invalid_required'  => 'This field is required.',
    'invalid_too_long'  => 'This field is too long.',
    'invalid_too_short' => 'This field is too short.',
    'upload_failed'     => '',
    'upload_file_type_invalid' => '',
    'upload_file_too_large'    => '',
    'upload_failed_php_error'  => '',
    'fill_all_fields'          => '',
    'date_too_early'           => '',
    'date_too_late'            => '',
    'captcha_not_match'        => '',
    'number_too_small'         => '',
    'number_too_large'         => '',
    'quiz_answer_not_correct'  => '',
    'invalid_date'             => '',
    'invalid_url'              => '',
    'invalid_tel'              => '',
) );

// Additional settings
update_post_meta( $form_id, '_additional_settings', '' );

echo "Form configured: ID {$form_id}, delivers to support@operatorprep.com\n";

// ── 8. Update the contact page to use the new form shortcode ──────────────
$current_content = $page->post_content;
$shortcode       = "[contact-form-7 id=\"{$form_id}\" title=\"Contact Us\"]";

// Replace any broken shortcode or append if none present
if ( preg_match( '/\[contact-form-7[^\]]*\]/', $current_content ) ) {
    $new_content = preg_replace( '/\[contact-form-7[^\]]*\]/', $shortcode, $current_content );
    echo "Replaced existing CF7 shortcode in page content.\n";
} else {
    // Find "Send a Message" heading and insert after it, or just append
    $new_content = $current_content . "\n\n" . $shortcode;
    echo "Appended CF7 shortcode to page content.\n";
}

kses_remove_filters();
$result = wp_update_post( array(
    'ID'           => $page_id,
    'post_content' => $new_content,
    'post_status'  => 'publish',
), true );

if ( is_wp_error( $result ) ) {
    echo "ERR updating page: " . $result->get_error_message() . "\n";
} else {
    echo "Page updated: ID {$result}\n";
}

wp_cache_flush();
echo "\nDONE. Contact form ID {$form_id} wired to page ID {$page_id}.\n";
echo "Messages will be delivered to support@operatorprep.com\n";
