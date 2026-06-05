<?php
/**
 * Contact form handler (theme-only, zero plugins).
 *
 * Registers a REST route that the contact-form.js front-end posts to. Validates
 * server-side, then delivers with wp_mail(). Keeping this in the theme avoids a
 * form plugin and keeps the active-plugin count at zero.
 *
 * Note: local dev has no SMTP, so wp_mail() will not actually deliver here; the
 * endpoint still validates and responds. Configure SMTP (or a transactional
 * provider) in production for real delivery.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'rest_api_init', static function () {
	register_rest_route( 'serensweb/v1', '/contact', [
		'methods'             => 'POST',
		'callback'            => 'serensweb_handle_contact',
		'permission_callback' => '__return_true',
	] );
} );

/**
 * Validate and send a contact submission.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function serensweb_handle_contact( WP_REST_Request $request ) {
	// CSRF guard: the front end sends the wp_rest nonce in the X-WP-Nonce header.
	$nonce = $request->get_header( 'X-WP-Nonce' );
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return new WP_Error( 'sw_bad_nonce', 'Your session expired. Please reload and try again.', [ 'status' => 403 ] );
	}

	$name    = sanitize_text_field( (string) $request->get_param( 'name' ) );
	$email   = sanitize_email( (string) $request->get_param( 'email' ) );
	$message = sanitize_textarea_field( (string) $request->get_param( 'message' ) );

	$errors = [];
	if ( mb_strlen( trim( $name ) ) < 2 ) {
		$errors['name'] = 'Please enter your name.';
	}
	if ( ! is_email( $email ) ) {
		$errors['email'] = 'Please enter a valid email address.';
	}
	if ( mb_strlen( trim( $message ) ) < 10 ) {
		$errors['message'] = 'Please add a few details (10+ characters).';
	}
	if ( $errors ) {
		return new WP_Error( 'sw_invalid', 'Some fields need attention.', [ 'status' => 422, 'fields' => $errors ] );
	}

	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[%s] New enquiry from %s', wp_specialchars_decode( get_bloginfo( 'name' ) ), $name );
	$body    = "Name: {$name}\nEmail: {$email}\n\n{$message}\n";
	$headers = [ 'Reply-To: ' . $name . ' <' . $email . '>' ];

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		// Do not leak local mail-transport failures to the visitor; log instead.
		error_log( 'SerensWeb contact: wp_mail returned false (no SMTP configured?).' );
	}

	return rest_ensure_response( [
		'ok'      => true,
		'message' => 'Message sent. Thank you.',
	] );
}
