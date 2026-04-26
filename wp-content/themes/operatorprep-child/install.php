<?php
/**
 * OperatorPrep Homepage Installer
 *
 * Reads homepage-v2.html and writes it to the WordPress front page post.
 * Run via: wp eval-file wp-content/themes/operatorprep-child/install.php --allow-root
 *
 * To update the homepage: edit homepage-v2.html, commit, deploy. No base64 needed.
 */

$html_file = dirname( __FILE__ ) . '/homepage-v2.html';

if ( ! file_exists( $html_file ) ) {
	echo 'ERR: homepage-v2.html not found at ' . $html_file;
	exit;
}

$content = file_get_contents( $html_file );

if ( empty( $content ) ) {
	echo 'ERR: homepage-v2.html is empty';
	exit;
}

// Allow saving raw HTML including <style> blocks without KSES stripping
wp_set_current_user( 1 );
kses_remove_filters();

// Find the front page
$id = (int) get_option( 'page_on_front' );
if ( ! $id ) {
	$home = get_page_by_path( 'home' );
	$id   = $home ? $home->ID : 0;
}

if ( $id ) {
	$result = wp_update_post(
		array(
			'ID'           => $id,
			'post_content' => $content,
			'post_status'  => 'publish',
		),
		true
	);
	echo is_wp_error( $result )
		? 'ERR:' . $result->get_error_message()
		: 'UPDATED:' . $id;
} else {
	$id = wp_insert_post(
		array(
			'post_title'   => 'Home',
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_name'    => 'home',
		)
	);
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $id );
	echo 'INSERTED:' . $id;
}

flush_rewrite_rules();
wp_cache_flush();
