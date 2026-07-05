<?php
/**
 * IndexNow: instant URL submission on publish, update, and unpublish.
 *
 * IndexNow (indexnow.org) lets the site tell Bing, Yandex, Seznam, and Naver
 * about changed URLs the moment they change instead of waiting for a crawl.
 * Bing also feeds Copilot and DuckDuckGo, so this is the AEO half of the
 * submission story alongside the sitemap.
 *
 * The protocol needs a key served at /{key}.txt to prove ownership. The key
 * is generated once into an option and served on parse_request (the same
 * mechanism seo.php uses for llms.txt), so nothing is written to the web
 * root and no rewrite flush is needed on deploy.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The site's IndexNow key. Generated on first use, then stable for life so
 * search engines can keep verifying the same key file.
 */
function serensweb_indexnow_key(): string {
	$key = get_option( 'serensweb_indexnow_key' );
	if ( is_string( $key ) && '' !== $key ) {
		return $key;
	}

	$key = bin2hex( random_bytes( 16 ) );

	// add_option returns false when a concurrent request already inserted the
	// row (the wp_options name column is unique). That request's key is the
	// one now served at /{key}.txt, so adopt it rather than returning a key
	// that was never stored and would fail verification.
	if ( ! add_option( 'serensweb_indexnow_key', $key, '', false ) ) {
		$key = (string) get_option( 'serensweb_indexnow_key' );
	}

	return $key;
}

// Serve /{key}.txt. The regex gate runs before the option is read, so normal
// requests never pay the lookup (which is also why the option can skip
// autoload).
add_action( 'parse_request', static function ( $wp ) {
	if ( ! preg_match( '/^[a-f0-9]{32}\.txt$/', $wp->request ) ) {
		return;
	}
	$key = serensweb_indexnow_key();
	if ( $wp->request !== $key . '.txt' ) {
		return;
	}

	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo $key;
	exit;
} );

/**
 * Submit one URL to the IndexNow endpoint, non-blocking.
 *
 * Pings are suppressed on local hosts so dev saves cannot spam the API with
 * URLs it can never fetch. The filter is the dev override and kill switch.
 */
function serensweb_indexnow_ping( string $url ): void {
	$host       = wp_parse_url( home_url(), PHP_URL_HOST );
	$is_public  = $host
		&& 'localhost' !== $host
		&& ! str_ends_with( $host, '.test' )
		&& ! str_ends_with( $host, '.local' );

	if ( ! apply_filters( 'serensweb_indexnow_enabled', $is_public ) ) {
		return;
	}

	// One ping per URL per request, in case several hooks fire on one save.
	static $pinged = [];
	if ( isset( $pinged[ $url ] ) ) {
		return;
	}
	$pinged[ $url ] = true;

	$key  = serensweb_indexnow_key();
	$body = [
		'host'        => $host,
		'key'         => $key,
		'keyLocation' => home_url( '/' . $key . '.txt' ),
		'urlList'     => [ $url ],
	];

	wp_remote_post( 'https://api.indexnow.org/indexnow', [
		'blocking' => false,
		'timeout'  => 3,
		'headers'  => [ 'Content-Type' => 'application/json; charset=utf-8' ],
		'body'     => wp_json_encode( $body ),
	] );

	// Observability hook for logging or tests; the site itself ignores it.
	do_action( 'serensweb_indexnow_pinged', $url, $body );
}

// Publish and update pings. In editor flows this fires inside
// wp_insert_post after the slug is final, so get_permalink() is the public
// URL. Revisions and autosaves never reach publish status, and the public
// post type check drops nav menu items and similar internals.
add_action( 'transition_post_status', static function ( $new_status, $old_status, $post ) {
	if ( 'publish' !== $new_status ) {
		return;
	}

	// wp_publish_post() on a bare draft transitions before a slug exists,
	// and get_permalink() would degrade to the home URL. The next save of
	// the post pings with the real permalink.
	if ( '' === $post->post_name ) {
		return;
	}

	$type = get_post_type_object( $post->post_type );
	if ( ! $type || ! $type->public ) {
		return;
	}

	serensweb_indexnow_ping( get_permalink( $post ) );
}, 10, 3 );

// Unpublish ping. wp_trash_post fires before the status flips, so the
// permalink is still the public URL engines should re-check. (In
// transition_post_status it is already ?p=N because the post is no longer
// public.)
add_action( 'wp_trash_post', static function ( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'publish' !== $post->post_status ) {
		return;
	}

	$type = get_post_type_object( $post->post_type );
	if ( ! $type || ! $type->public ) {
		return;
	}

	serensweb_indexnow_ping( get_permalink( $post ) );
} );
