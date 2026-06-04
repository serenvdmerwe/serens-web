<?php
/**
 * Block pattern registration.
 *
 * Auto-registers every /patterns/*.php whose header declares Title and Slug.
 * Sections from the handoff are delivered as patterns (one section per file)
 * by the wp-page-from-handoff workflow.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', static function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'serens-web-child', [
			'label'       => __( 'SerensWeb', 'serens-web-child' ),
			'description' => __( 'Sections from the SerensWeb design handoff.', 'serens-web-child' ),
		] );
	}

	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	$patterns_dir = get_stylesheet_directory() . '/patterns';
	foreach ( glob( $patterns_dir . '/*.php' ) ?: [] as $file ) {
		$headers = get_file_data( $file, [
			'title'       => 'Title',
			'slug'        => 'Slug',
			'description' => 'Description',
			'categories'  => 'Categories',
		] );

		if ( empty( $headers['title'] ) || empty( $headers['slug'] ) ) {
			continue;
		}

		ob_start();
		include $file;
		$content = ob_get_clean();

		register_block_pattern( $headers['slug'], [
			'title'       => $headers['title'],
			'description' => $headers['description'] ?: '',
			'categories'  => array_filter( array_map( 'trim', explode( ',', $headers['categories'] ) ) ),
			'content'     => $content,
		] );
	}
} );
