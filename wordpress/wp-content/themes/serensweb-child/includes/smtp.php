<?php
/**
 * Route wp_mail() through Gmail SMTP (plugin-free delivery).
 *
 * The contact endpoint (ajax-contact.php) sends with wp_mail(). WordPress ships
 * PHPMailer but no transport, so on a host without a local MTA nothing leaves
 * the box. This hooks phpmailer_init and points PHPMailer at Gmail SMTP when
 * credentials are present.
 *
 * Credentials are never committed (so they stay out of the theme zip and the
 * repo). Each value is read from a wp-config.php constant first, then a matching
 * environment variable, so the same theme works in both places:
 *   - Shared hosting (Hostinger): define( 'SW_SMTP_PASS', '...' ) in
 *     wp-config.php, which lives above the theme, is not in the zip, and is not
 *     in git. Set once on the server; theme re-uploads never touch it.
 *   - Docker local stack: SW_SMTP_* env vars from .env via compose.yml.
 *
 * Keys:
 *   SW_SMTP_USER  Gmail address that authenticates and sends (required)
 *   SW_SMTP_PASS  Google App Password (16 chars, not the account password) (required)
 *   SW_SMTP_HOST  optional, default smtp.gmail.com
 *   SW_SMTP_PORT  optional, default 587 (TLS); use 465 for SSL
 *   SW_SMTP_FROM  optional From address, defaults to SW_SMTP_USER
 *
 * If SW_SMTP_USER or SW_SMTP_PASS is missing (local dev, or a deploy that has
 * not set the secret yet), this returns early and wp_mail() behaves exactly as
 * before: it reports success to the visitor and logs the transport failure.
 * Setup steps: docs/contact-email-setup.md.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve an SMTP setting from a wp-config constant, falling back to an env var.
 *
 * @param string $key Setting name, e.g. SW_SMTP_PASS.
 * @return string The value, or '' when neither source defines it.
 */
function serensweb_smtp_config( $key ) {
	if ( defined( $key ) ) {
		return (string) constant( $key );
	}
	$env = getenv( $key );
	return false === $env ? '' : (string) $env;
}

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) {
		$user = serensweb_smtp_config( 'SW_SMTP_USER' );
		$pass = serensweb_smtp_config( 'SW_SMTP_PASS' );

		// No credentials: leave wp_mail() on its default (non-)transport.
		if ( ! $user || ! $pass ) {
			return;
		}

		$host = serensweb_smtp_config( 'SW_SMTP_HOST' ) ?: 'smtp.gmail.com';
		$port = (int) ( serensweb_smtp_config( 'SW_SMTP_PORT' ) ?: 587 );
		$from = serensweb_smtp_config( 'SW_SMTP_FROM' ) ?: $user;

		$phpmailer->isSMTP();
		$phpmailer->Host       = $host;
		$phpmailer->Port       = $port;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Username   = $user;
		$phpmailer->Password   = $pass;
		$phpmailer->SMTPSecure = ( 465 === $port ) ? 'ssl' : 'tls';

		// Gmail rejects a From that is not the authenticated account, so force
		// it here (this overrides WordPress's wordpress@domain default). The
		// visitor's address stays reachable via the Reply-To that
		// ajax-contact.php sets.
		$phpmailer->setFrom( $from, wp_specialchars_decode( get_bloginfo( 'name' ) ) );
	}
);
