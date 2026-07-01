<?php
/**
 * Route wp_mail() through Gmail SMTP (plugin-free delivery).
 *
 * The contact endpoint (ajax-contact.php) sends with wp_mail(). WordPress ships
 * PHPMailer but no transport, so on a host without a local MTA nothing leaves
 * the box. This hooks phpmailer_init and points PHPMailer at Gmail SMTP when
 * credentials are present in the environment.
 *
 * Credentials are read from the environment, never committed (so they stay out
 * of the theme zip and the repo):
 *   SW_SMTP_USER  Gmail address that authenticates and sends
 *   SW_SMTP_PASS  Google App Password (16 chars, not the account password)
 * Optional overrides (Gmail defaults apply when unset):
 *   SW_SMTP_HOST  default smtp.gmail.com
 *   SW_SMTP_PORT  default 587 (TLS); use 465 for SSL
 *   SW_SMTP_FROM  From address; defaults to SW_SMTP_USER
 *
 * If SW_SMTP_USER or SW_SMTP_PASS is missing (local dev, or a deploy that has
 * not set the secret yet), this returns early and wp_mail() behaves exactly as
 * before: it reports success to the visitor and logs the transport failure.
 * Setup steps: docs/contact-email-setup.md.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) {
		$user = getenv( 'SW_SMTP_USER' );
		$pass = getenv( 'SW_SMTP_PASS' );

		// No credentials: leave wp_mail() on its default (non-)transport.
		if ( ! $user || ! $pass ) {
			return;
		}

		$host = getenv( 'SW_SMTP_HOST' ) ?: 'smtp.gmail.com';
		$port = (int) ( getenv( 'SW_SMTP_PORT' ) ?: 587 );
		$from = getenv( 'SW_SMTP_FROM' ) ?: $user;

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
