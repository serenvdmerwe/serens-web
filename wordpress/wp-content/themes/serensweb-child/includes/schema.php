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
	// Open Graph + Twitter card defaults (sitewide). Self-hosted card image, no plugin.
	$og_image = get_stylesheet_directory_uri() . '/assets/images/og-card.jpg';
	$og_desc  = 'AI-augmented web developer building fast, modern, conversion-focused websites and web apps.';
	$og_tags  = [
		'og:type'         => 'website',
		'og:site_name'    => get_bloginfo( 'name' ),
		'og:title'        => get_bloginfo( 'name' ),
		'og:description'  => $og_desc,
		'og:url'          => home_url( '/' ),
		'og:image'        => $og_image,
		'og:image:width'  => '1200',
		'og:image:height' => '630',
		'og:image:alt'    => 'SerensWeb: websites that perform as well as they look',
	];
	foreach ( $og_tags as $property => $content ) {
		printf( '<meta property="%s" content="%s" />' . "\n", esc_attr( $property ), esc_attr( $content ) );
	}

	$tw_tags = [
		'twitter:card'        => 'summary_large_image',
		'twitter:title'       => get_bloginfo( 'name' ),
		'twitter:description' => $og_desc,
		'twitter:image'       => $og_image,
	];
	foreach ( $tw_tags as $name => $content ) {
		printf( '<meta name="%s" content="%s" />' . "\n", esc_attr( $name ), esc_attr( $content ) );
	}
}, 5 );
