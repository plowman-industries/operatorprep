<?php
/**
 * fix-smtp.php
 * Run via: wp eval-file wp-content/themes/operatorprep-child/fix-smtp.php --allow-root
 *
 * Installs + activates WP Mail SMTP and configures it to use SiteGround's
 * localhost SMTP (port 25, no auth) — the fastest/most reliable option on SG hosting.
 */

// ── 1. Install WP Mail SMTP if not present ────────────────────────────────
$plugin_slug = 'wp-mail-smtp/wp_mail_smtp.php';
$active = get_option( 'active_plugins', array() );

if ( ! in_array( $plugin_slug, $active, true ) ) {
    echo "WP Mail SMTP not active — installing...\n";

    // Use WP-CLI internally to install
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/misc.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    $api = plugins_api( 'plugin_information', array(
        'slug'   => 'wp-mail-smtp',
        'fields' => array( 'sections' => false ),
    ) );

    if ( is_wp_error( $api ) ) {
        echo "ERR fetching plugin info: " . $api->get_error_message() . "\n";
        exit(1);
    }

    $upgrader = new Plugin_Upgrader( new WP_Upgrader_Skin() );
    $result   = $upgrader->install( $api->download_link );

    if ( is_wp_error( $result ) || ! $result ) {
        echo "ERR installing plugin.\n";
        exit(1);
    }
    echo "Installed WP Mail SMTP.\n";
} else {
    echo "WP Mail SMTP already installed.\n";
}

// Activate
if ( ! in_array( $plugin_slug, $active, true ) ) {
    $activated = activate_plugin( $plugin_slug );
    if ( is_wp_error( $activated ) ) {
        echo "ERR activating: " . $activated->get_error_message() . "\n";
        exit(1);
    }
    echo "Activated WP Mail SMTP.\n";
} else {
    echo "WP Mail SMTP already active.\n";
}

// ── 2. Configure to use SiteGround localhost SMTP ─────────────────────────
// SiteGround allows unauthenticated SMTP via localhost:25 — fast, no TLS needed.
$options = get_option( 'wp_mail_smtp', array() );

$options['mail'] = array(
    'from_email'       => 'noreply@operatorprep.com',
    'from_name'        => 'OperatorPrep',
    'mailer'           => 'smtp',
    'return_path'      => false,
    'from_email_force' => true,
    'from_name_force'  => true,
);

$options['smtp'] = array(
    'host'           => 'localhost',
    'port'           => 25,
    'encryption'     => 'none',
    'auth'           => false,
    'user'           => '',
    'pass'           => '',
    'autotls'        => false,
);

update_option( 'wp_mail_smtp', $options );
echo "Configured WP Mail SMTP: localhost:25, no-auth (SiteGround native SMTP).\n";

// ── 3. Send a test email to confirm delivery ──────────────────────────────
$sent = wp_mail(
    'support@operatorprep.com',
    '[OperatorPrep] SMTP Test — ' . date('Y-m-d H:i:s'),
    "This is an automated test from fix-smtp.php.\n\nIf you receive this, email delivery is working correctly.\n\nSent: " . date('r'),
    array( 'From: OperatorPrep <noreply@operatorprep.com>' )
);

echo $sent ? "Test email sent to support@operatorprep.com.\n" : "ERR: wp_mail() returned false — delivery may have failed.\n";
echo "\nDONE.\n";
