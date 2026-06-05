<?php
/**
 * Block pattern registration.
 *
 * Registers the SerensWeb pattern category and auto-registers every
 * patterns/*.php file whose header declares a Title and Slug. The patterns/
 * directory is populated one section at a time by wp-page-from-handoff.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', static function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'serensweb-child', [
			'label'       => __( 'SerensWeb', 'serensweb-child' ),
			'description' => __( 'Sections from the SerensWeb design handoff.', 'serensweb-child' ),
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
