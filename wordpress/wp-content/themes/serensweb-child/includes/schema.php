<?php
/**
 * JSON-LD structured data: one linked @graph per page.
 *
 * Person and WebSite carry stable @id fragments and reference each other, so
 * crawlers see one connected entity instead of disjoint blocks. Page-specific
 * nodes (ProfilePage on about, BreadcrumbList on project singles, the
 * Playground ItemList) join the same graph. Service copy mirrors the
 * strengths cards on the home page. sameAs lists the confirmed GitHub and
 * LinkedIn profiles (see the footer social links); no X profile is used.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', static function () {
	$home       = home_url( '/' );
	$person_id  = $home . '#person';
	$website_id = $home . '#website';

	$services = [
		'WordPress Custom Themes'                     => 'Bespoke block themes built by hand, not assembled from a page builder. Fast, accessible, and easy to maintain.',
		'AI Workflows'                                => 'LLM features, assistants, and automation wired into real products and internal tools. Useful, not gimmicky.',
		'Progressive Web Apps and Mobile-First Sites' => 'Installable, offline-ready PWAs and responsive sites that feel native on every device.',
		'Interactive Portfolios and Corporate Sites'  => 'Brand-defining marketing sites with motion and craft that signal quality at first glance.',
	];
	$offers   = [];
	foreach ( $services as $name => $description ) {
		$offers[] = [
			'@type'       => 'Offer',
			'itemOffered' => [
				'@type'       => 'Service',
				'name'        => $name,
				'description' => $description,
				'provider'    => [ '@id' => $person_id ],
			],
		];
	}

	$graph = [
		[
			'@type'       => 'Person',
			'@id'         => $person_id,
			'name'        => 'Seren van der Merwe',
			'jobTitle'    => 'AI-Augmented Web Developer',
			'description' => 'AI-augmented web developer building fast, modern, conversion-focused websites and web apps.',
			'url'         => $home,
			'email'       => 'vandermerweseren@gmail.com',
			'address'     => [
				'@type'          => 'PostalAddress',
				'addressCountry' => 'ZA',
			],
			'knowsAbout'  => [ 'WordPress', 'PHP', 'React', 'Next.js', 'TypeScript', 'AI workflows' ],
			'sameAs'      => [ 'https://github.com/serenvdmerwe', 'https://www.linkedin.com/in/serenvdmerwe' ],
			'makesOffer'  => $offers,
		],
		[
			'@type'     => 'WebSite',
			'@id'       => $website_id,
			'name'      => get_bloginfo( 'name' ),
			'url'       => $home,
			'publisher' => [ '@id' => $person_id ],
		],
	];

	if ( is_page( 'about' ) ) {
		$graph[] = [
			'@type'      => 'ProfilePage',
			'@id'        => get_permalink() . '#profilepage',
			'url'        => get_permalink(),
			'name'       => wp_get_document_title(),
			'mainEntity' => [ '@id' => $person_id ],
			'isPartOf'   => [ '@id' => $website_id ],
		];
	}

	if ( is_singular( 'project' ) ) {
		$graph[] = [
			'@type'           => 'BreadcrumbList',
			'itemListElement' => [
				[
					'@type'    => 'ListItem',
					'position' => 1,
					'name'     => 'Home',
					'item'     => $home,
				],
				[
					'@type'    => 'ListItem',
					'position' => 2,
					'name'     => 'Projects',
					'item'     => get_post_type_archive_link( 'project' ),
				],
				[
					'@type'    => 'ListItem',
					'position' => 3,
					'name'     => get_the_title(),
				],
			],
		];
	}

	// Contact page: FAQPage mirroring the visible FAQ section, both fed by
	// serensweb_faq_items(). Google reserves FAQ rich results for authority
	// sites these days; the point here is answer engines quoting exact
	// first person answers.
	if ( is_page( 'contact' ) && function_exists( 'serensweb_faq_items' ) ) {
		$questions = [];
		foreach ( serensweb_faq_items() as $question => $answer ) {
			$questions[] = [
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text'  => $answer,
				],
			];
		}
		$graph[] = [
			'@type'      => 'FAQPage',
			'@id'        => get_permalink() . '#faq',
			'mainEntity' => $questions,
			'isPartOf'   => [ '@id' => $website_id ],
		];
	}

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
		$graph[] = [
			'@type'           => 'ItemList',
			'name'            => 'SerensWeb Playground experiments',
			'description'     => 'Interactive maps, browser games, developer tools, installable web apps, and explainers built by Seren van der Merwe.',
			'numberOfItems'   => count( $items ),
			'itemListElement' => $items,
		];
	}

	$data = [
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . "</script>\n";
	// Open Graph and Twitter cards live in meta.php (one concern per file).
}, 5 );
