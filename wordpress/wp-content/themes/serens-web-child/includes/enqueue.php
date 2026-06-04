<?php
/**
 * Front-end and editor asset registration.
 *
 * Stylesheets:  tokens.css -> theme.css -> child style.css
 * Scripts:      site-chrome.js (accent switcher, header, drawer, year),
 *               reveal.js (scroll reveal). Section-specific behaviours
 *               (work grid, lightbox, contact form) are enqueued by their
 *               page patterns as those sections are built.
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
	wp_enqueue_style( 'serens-web-child', get_stylesheet_uri(), [ 'sw-theme' ], $version );

	// Global chrome behaviours, deferred in the footer.
	wp_enqueue_script( 'sw-site-chrome', $theme_uri . '/assets/js/site-chrome.js', [], $asset_version( 'assets/js/site-chrome.js' ), true );
	wp_enqueue_script( 'sw-reveal',      $theme_uri . '/assets/js/reveal.js',      [], $asset_version( 'assets/js/reveal.js' ), true );
} );

add_action( 'after_setup_theme', static function () {
	add_editor_style( [
		'assets/css/tokens.css',
		'assets/css/theme.css',
	] );
} );
