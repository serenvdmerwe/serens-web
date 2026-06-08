<?php
/**
 * JSON-LD structured data: Organization + WebSite.
 *
 * sameAs lists the confirmed GitHub and LinkedIn profiles (see the footer
 * social links). No X profile is used.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', static function () {
	$logo = get_stylesheet_directory_uri() . '/assets/images/logo.svg';

	$org = [
		'@context'    => 'https://schema.org',
		'@type'       => 'Organization',
		'name'        => 'SerensWeb',
		'description' => 'Freelance web developer building fast, modern, conversion focused sites and web apps.',
		'url'         => home_url( '/' ),
		'logo'        => esc_url( $logo ),
		'email'       => 'hello@serensweb.dev',
		'areaServed'  => 'Remote, worldwide',
		'sameAs'      => [ 'https://github.com/serenvdmerwe', 'https://www.linkedin.com/in/serenvdmerwe' ],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $org ) . "</script>\n";

	$site = [
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $site ) . "</script>\n";
}, 5 );
