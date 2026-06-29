<?php
/**
 * Front-end and editor asset registration.
 *
 * Stylesheets:  tokens.css -> theme.css -> child style.css
 * Scripts:      site-chrome.js (accent switcher, header, drawer, smooth scroll,
 *               current-nav marking, year), reveal.js (scroll reveal),
 *               contact-form.js (validation + REST submit), engage.js
 *               (engagement switcher). Each script is self-guarded, so it
 *               no-ops on pages that lack its markup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', static function () {
	$theme_uri = get_stylesheet_directory_uri();
	$theme_dir = get_stylesheet_directory();
	$version   = wp_get_theme()->get( 'Version' );

	$asset_version = static function ( $relative ) use ( $theme_dir ) {
		$path = $theme_dir . '/' . ltrim( $relative, '/' );
		return file_exists( $path ) ? (string) filemtime( $path ) : '0';
	};

	// Stylesheets in dependency order: tokens -> theme -> child overrides.
	wp_enqueue_style( 'sw-tokens', $theme_uri . '/assets/css/tokens.css', [], $asset_version( 'assets/css/tokens.css' ) );
	wp_enqueue_style( 'sw-theme',  $theme_uri . '/assets/css/theme.css',  [ 'sw-tokens' ], $asset_version( 'assets/css/theme.css' ) );
	wp_enqueue_style( 'serensweb-child', get_stylesheet_uri(), [ 'sw-theme' ], $version );

	// Behaviour modules, deferred in the footer.
	wp_enqueue_script( 'sw-site-chrome',  $theme_uri . '/assets/js/site-chrome.js',  [], $asset_version( 'assets/js/site-chrome.js' ), true );
	wp_enqueue_script( 'sw-reveal',       $theme_uri . '/assets/js/reveal.js',       [], $asset_version( 'assets/js/reveal.js' ), true );
	wp_enqueue_script( 'sw-contact-form', $theme_uri . '/assets/js/contact-form.js', [], $asset_version( 'assets/js/contact-form.js' ), true );
	wp_enqueue_script( 'sw-engage',       $theme_uri . '/assets/js/engage.js',       [], $asset_version( 'assets/js/engage.js' ), true );

	// Hand the contact form its REST endpoint and a nonce.
	wp_localize_script( 'sw-contact-form', 'swContact', [
		'url'   => esc_url_raw( rest_url( 'serensweb/v1/contact' ) ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	] );

	// Hand the engagement switcher the WhatsApp number (international format,
	// digits only, no plus or spaces). Empty string disables the deep link.
	wp_localize_script( 'sw-engage', 'swEngage', [
		'whatsapp' => '27769420144',
	] );
} );

add_action( 'after_setup_theme', static function () {
	add_editor_style( [
		'assets/css/tokens.css',
		'assets/css/theme.css',
	] );
} );
