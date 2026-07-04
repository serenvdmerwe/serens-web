<?php
/**
 * Live aircraft proxy for the Planes Overhead playground map (theme-only, zero plugins).
 *
 * The upstream ADS-B aggregators do not send CORS headers, so the static
 * playground page cannot call them from the browser. This read-only GET
 * endpoint fetches server-side and returns a trimmed list, which also lets
 * the playground demo honestly say "WordPress is the map's backend".
 *
 * No nonce: the playground pages are static files with no WP page context to
 * mint one, and this route reads public flight data with no side effects.
 * Abuse is bounded instead: coordinates are validated and bucketed, the
 * upstream radius is fixed server-side, and a short transient means any burst
 * of visitors shares one upstream call per area.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'rest_api_init', static function () {
	register_rest_route( 'serensweb/v1', '/planes', [
		'methods'             => 'GET',
		'callback'            => 'serensweb_handle_planes',
		'permission_callback' => '__return_true',
		'args'                => [
			'lat' => [ 'required' => true ],
			'lon' => [ 'required' => true ],
		],
	] );
} );

/**
 * Fetch aircraft near a point via adsb.lol, with a shared 15 second cache.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function serensweb_handle_planes( WP_REST_Request $request ) {
	$lat = (float) $request->get_param( 'lat' );
	$lon = (float) $request->get_param( 'lon' );
	if ( ! is_finite( $lat ) || ! is_finite( $lon ) || abs( $lat ) > 85 || abs( $lon ) > 180 ) {
		return new WP_Error( 'sw_bad_coords', 'Coordinates out of range.', [ 'status' => 400 ] );
	}

	// Quarter-degree buckets so nearby visitors share one cache entry.
	$blat = round( $lat * 4 ) / 4;
	$blon = round( $lon * 4 ) / 4;
	$key  = 'sw_planes_' . str_replace( [ '.', '-' ], [ 'p', 'm' ], $blat . '_' . $blon );

	$cached = get_transient( $key );
	if ( false !== $cached ) {
		$cached['cached'] = true;
		return rest_ensure_response( $cached );
	}

	$url = sprintf( 'https://api.adsb.lol/v2/point/%F/%F/150', $blat, $blon );
	$res = wp_remote_get( $url, [ 'timeout' => 8, 'user-agent' => 'serensweb.com playground (planes overhead demo)' ] );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
		return new WP_Error( 'sw_upstream', 'The flight data service did not answer.', [ 'status' => 502 ] );
	}

	$data = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( ! is_array( $data ) || ! isset( $data['ac'] ) || ! is_array( $data['ac'] ) ) {
		return new WP_Error( 'sw_upstream', 'The flight data service sent something unexpected.', [ 'status' => 502 ] );
	}

	$planes = [];
	foreach ( $data['ac'] as $ac ) {
		if ( ! isset( $ac['lat'], $ac['lon'] ) ) {
			continue;
		}
		$planes[] = [
			'hex'    => isset( $ac['hex'] ) ? sanitize_text_field( (string) $ac['hex'] ) : '',
			'flight' => isset( $ac['flight'] ) ? trim( sanitize_text_field( (string) $ac['flight'] ) ) : '',
			'reg'    => isset( $ac['r'] ) ? sanitize_text_field( (string) $ac['r'] ) : '',
			'type'   => isset( $ac['t'] ) ? sanitize_text_field( (string) $ac['t'] ) : '',
			'alt'    => isset( $ac['alt_baro'] ) ? ( 'ground' === $ac['alt_baro'] ? 'ground' : (int) $ac['alt_baro'] ) : null,
			'gs'     => isset( $ac['gs'] ) ? round( (float) $ac['gs'] ) : null,
			'track'  => isset( $ac['track'] ) ? round( (float) $ac['track'] ) : null,
			'lat'    => round( (float) $ac['lat'], 4 ),
			'lon'    => round( (float) $ac['lon'], 4 ),
		];
		if ( count( $planes ) >= 80 ) {
			break;
		}
	}

	$payload = [
		'ok'      => true,
		'count'   => count( $planes ),
		'planes'  => $planes,
		'fetched' => gmdate( 'c' ),
		'cached'  => false,
	];
	set_transient( $key, $payload, 15 );

	return rest_ensure_response( $payload );
}
