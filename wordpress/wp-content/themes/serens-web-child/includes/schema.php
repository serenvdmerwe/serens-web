<?php
/**
 * JSON-LD structured data for the site.
 *
 * Baseline: Organization + WebSite. Per-page WebPage / BreadcrumbList can be
 * added later through the issue workflow.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', static function () {
	$org = [
		'@context'    => 'https://schema.org',
		'@type'       => 'Organization',
		'name'        => 'SerensWeb',
		'url'         => home_url( '/' ),
		'logo'        => esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.svg' ),
		'description' => 'Freelance web developer building fast, modern, conversion focused sites and web apps, from headless commerce to AI powered tools.',
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
