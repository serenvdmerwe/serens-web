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

	// Playground page: ItemList of the experiments so crawlers see the catalog.
	if ( is_page( 'playground' ) ) {
		$base        = get_stylesheet_directory_uri() . '/assets/playground/';
		$experiments = [
			'Risk Explorer: Florida and Cape Town'   => 'florida-risk-explorer.html',
			'Hurricane Tracks Time Machine'          => 'hurricane-tracks.html',
			'How South Africa Reaches the Internet'  => 'submarine-cables.html',
			'Live Earthquake Map'                    => 'earthquakes-live.html',
			'Planes Overhead'                        => 'planes-overhead.html',
			'ISS Live Tracker'                       => 'iss-tracker.html',
			'Golden Hour, Everywhere'                => 'golden-hour.html',
			'The Wind, as Particles'                 => 'wind-particles.html',
			'Contrast: the Game'                     => 'contrast-game.html',
			'Dev Typing Test'                        => 'dev-typing-test.html',
			'AI Feature Cost Estimator'              => 'ai-cost-estimator.html',
			'Design Token Re-themer'                 => 'token-rethemer.html',
			'Aptitude Trainer'                       => 'aptitude-trainer/index.html',
			'How a WordPress Page Loads'             => 'wp-page-load.html',
			'The Agentic Build, Replayed'            => 'build-replay.html',
		];
		$items       = [];
		$position    = 1;
		foreach ( $experiments as $label => $file ) {
			$items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $label,
				'url'      => $base . $file,
			];
		}
		$list = [
			'@context'        => 'https://schema.org',
			'@type'           => 'ItemList',
			'name'            => 'SerensWeb Playground experiments',
			'description'     => 'Interactive maps, browser games, developer tools, installable web apps, and explainers built by Seren van der Merwe.',
			'numberOfItems'   => count( $items ),
			'itemListElement' => $items,
		];
		echo '<script type="application/ld+json">' . wp_json_encode( $list ) . "</script>\n";
	}
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
