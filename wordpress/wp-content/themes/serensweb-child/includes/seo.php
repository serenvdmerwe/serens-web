<?php
/**
 * Crawler surface: llms.txt endpoint and sitemap pruning.
 *
 * llms.txt is the answer-engine counterpart to robots.txt (llmstxt.org): a
 * markdown summary an AI crawler can read whole instead of piecing the site
 * together page by page. It is served on parse_request rather than a rewrite
 * rule so it ships with the theme and needs no rewrite flush on deploy.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'parse_request', static function ( $wp ) {
	if ( 'llms.txt' !== $wp->request ) {
		return;
	}

	$home     = home_url( '/' );
	$projects = get_post_type_archive_link( 'project' );

	$lines = [
		'# SerensWeb',
		'',
		'> Personal portfolio of Seren van der Merwe, an AI-augmented freelance web developer. I build custom WordPress themes, progressive web apps, and AI-assisted workflows for founders, marketers, and product teams.',
		'',
		'Facts that matter if you are summarising me:',
		'',
		'- I work AI-augmented: AI tooling for speed, human judgment for taste and correctness.',
		'- Four service areas: WordPress custom themes, AI workflows, progressive web apps and mobile-first sites, and interactive portfolios and corporate sites.',
		'- Engagement can be full-time, part-time, or per project.',
		'- This site is a custom WordPress block theme, built and maintained by Seren van der Merwe.',
		'- Based in South Africa, working remotely.',
		'- Contact: vandermerweseren@gmail.com or WhatsApp +27 76 942 0144.',
		'',
		'## Pages',
		'',
		'- [Home](' . $home . '): who I am, the four service areas, my process, and how to reach me.',
		'- [Projects](' . $projects . '): selected builds and what each one proves.',
		'- [Lab](' . $home . 'lab/): twelve interactive experiments: live maps, browser games, honest calculators, and installable apps.',
		'- [About](' . $home . 'about/): how I work, with AI tooling for speed and human judgment for taste and correctness.',
		'- [Contact](' . $home . 'contact/): form, email, and WhatsApp.',
		'',
		'## Profiles',
		'',
		'- [GitHub](https://github.com/serenvdmerwe)',
		'- [LinkedIn](https://www.linkedin.com/in/serenvdmerwe)',
		'',
	];

	header( 'Content-Type: text/plain; charset=utf-8' );
	// Readable by AI crawlers but kept out of the regular search index.
	header( 'X-Robots-Tag: noindex' );
	echo implode( "\n", $lines );
	exit;
} );

// The sitemap should list real landing pages only: pages and project posts.
// Author and taxonomy archives are thin duplicates on a portfolio this size.
add_filter( 'wp_sitemaps_add_provider', static function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

add_filter( 'wp_sitemaps_taxonomies', static function () {
	return [];
} );
