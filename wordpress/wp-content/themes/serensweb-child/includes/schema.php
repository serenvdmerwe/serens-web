<?php
/**
 * JSON-LD structured data: Person + WebSite.
 *
 * sameAs lists the confirmed GitHub and LinkedIn profiles (see the footer
 * social links). No X profile is used.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', static function () {
	$person = [
		'@context'    => 'https://schema.org',
		'@type'       => 'Person',
		'name'        => 'Seren van der Merwe',
		'jobTitle'    => 'AI-Augmented Web Developer',
		'description' => 'AI-augmented web developer building fast, modern, conversion-focused websites and web apps.',
		'url'         => home_url( '/' ),
		'email'       => 'vandermerweseren@gmail.com',
		'knowsAbout'  => [ 'WordPress', 'PHP', 'React', 'Next.js', 'TypeScript', 'AI workflows' ],
		'sameAs'      => [ 'https://github.com/serenvdmerwe', 'https://www.linkedin.com/in/serenvdmerwe' ],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $person ) . "</script>\n";

	$site = [
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $site ) . "</script>\n";
}, 5 );
