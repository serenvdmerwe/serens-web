<?php
/**
 * Document head: title separator, per-page descriptions, Open Graph, icons.
 *
 * Open Graph moved here from schema.php so each file keeps one concern:
 * schema.php is JSON-LD, meta.php is everything else in the head. The
 * descriptions speak as Seren and only make provable claims.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// WordPress defaults to an en dash between title parts; the writing rules ban it.
add_filter( 'document_title_separator', static function () {
	return '|';
} );

// Blind /favicon.ico requests (crawlers, legacy browsers) get the real file.
add_action( 'do_faviconico', static function () {
	wp_redirect( get_stylesheet_directory_uri() . '/assets/images/favicon.ico', 301 );
	exit;
} );

add_action( 'wp_head', static function () {
	$assets   = get_stylesheet_directory_uri() . '/assets';
	$img_base = $assets . '/images';

	// Google Search Console site ownership (URL-prefix property). Bing imports
	// this verified property, so one tag covers both engines. DNS TXT is the
	// alternative; the meta tag keeps ownership reproducible in the theme.
	echo '<meta name="google-site-verification" content="7iBbMdsOS0Kno1VsKJTJ4DrMkqBmmt57TBqeAFjLYKs" />' . "\n";

	// Start the primary font download with the page instead of after the CSS.
	// crossorigin is required on font preloads even for same-origin files.
	printf( '<link rel="preload" href="%s/fonts/Geist-Variable.woff2" as="font" type="font/woff2" crossorigin />' . "\n", esc_url( $assets ) );

	// Icons: SVG for modern browsers, ICO fallback, touch icon for home screens.
	printf( '<link rel="icon" href="%s/favicon.svg" type="image/svg+xml" />' . "\n", esc_url( $img_base ) );
	printf( '<link rel="icon" href="%s/favicon.ico" sizes="48x48 32x32 16x16" />' . "\n", esc_url( $img_base ) );
	printf( '<link rel="apple-touch-icon" href="%s/apple-touch-icon.png" />' . "\n", esc_url( $img_base ) );

	// Description: per page where it matters, sitewide default elsewhere.
	$desc = 'AI-augmented web developer building fast, modern, conversion-focused websites and web apps.';
	if ( is_front_page() ) {
		$desc = 'I am Seren van der Merwe, an AI-augmented web developer. I build custom WordPress themes, progressive web apps, and AI-assisted workflows for founders, marketers, and product teams.';
	} elseif ( is_page( 'about' ) ) {
		$desc = 'How I work: AI tooling for speed, human judgment for taste and correctness, and builds that ship through reviewed pull requests.';
	} elseif ( is_page( 'lab' ) ) {
		$desc = 'A shelf of interactive experiments: live maps, browser games, honest calculators, and installable apps, running on open data and browser APIs.';
	} elseif ( is_page( 'contact' ) ) {
		$desc = 'Tell me what you are building. Email, WhatsApp, or the form; engagement can be full-time, part-time, or per project.';
	} elseif ( is_post_type_archive( 'project' ) ) {
		$desc = 'Selected builds and what each one proves: custom WordPress themes, progressive web apps, and AI-assisted workflows.';
	} elseif ( is_singular( 'project' ) && has_excerpt() ) {
		$desc = wp_strip_all_tags( get_the_excerpt() );
	}
	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $desc ) );

	// Canonical-ish URL for sharing: the current view, not always the homepage.
	if ( is_singular() ) {
		$url = get_permalink();
	} elseif ( is_post_type_archive( 'project' ) ) {
		$url = get_post_type_archive_link( 'project' );
	} else {
		$url = home_url( '/' );
	}

	$og_image = get_stylesheet_directory_uri() . '/assets/images/og-card.jpg';
	$og_tags  = [
		'og:type'         => is_singular( 'project' ) ? 'article' : 'website',
		'og:site_name'    => get_bloginfo( 'name' ),
		'og:title'        => wp_get_document_title(),
		'og:description'  => $desc,
		'og:url'          => $url,
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
		'twitter:title'       => wp_get_document_title(),
		'twitter:description' => $desc,
		'twitter:image'       => $og_image,
	];
	foreach ( $tw_tags as $name => $content ) {
		printf( '<meta name="%s" content="%s" />' . "\n", esc_attr( $name ), esc_attr( $content ) );
	}
}, 5 );
