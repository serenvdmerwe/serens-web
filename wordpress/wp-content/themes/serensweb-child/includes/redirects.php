<?php
/**
 * Legacy URL redirects.
 *
 * The Lab section was renamed from its old slug (/playground/). Search engines
 * and any inbound links still point at the old path, so it is 301'd to the new
 * one. Core's _wp_old_slug redirect only covers posts, not pages (it keys off
 * the `name` query var, while a page request populates `pagename`), so the
 * redirect is made explicit here. Zero plugins.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', static function () {
	if ( ! is_404() ) {
		return;
	}

	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );

	// Old slug => new path. Trailing and leading slashes are already trimmed,
	// so both /playground and /playground/ resolve to the same key.
	$map = [
		'playground' => '/lab/',
	];

	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
} );
