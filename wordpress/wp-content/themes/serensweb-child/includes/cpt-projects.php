<?php
/**
 * Projects: custom post type + project_type taxonomy. Zero plugins.
 *
 * Fields all come from the block editor and taxonomies, no custom meta:
 *   title -> post title, story -> post content, card blurb -> excerpt,
 *   card visual -> featured image, category label -> project_type term,
 *   tech chips -> post_tag terms.
 *
 * URLs: /projects (archive), /projects/<slug> (single). Rewrite rules are
 * flushed on theme switch; re-saving Permalinks in wp-admin is the fallback.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', static function () {
	register_post_type( 'project', [
		'labels' => [
			'name'               => __( 'Projects', 'serensweb-child' ),
			'singular_name'      => __( 'Project', 'serensweb-child' ),
			'add_new_item'       => __( 'Add New Project', 'serensweb-child' ),
			'edit_item'          => __( 'Edit Project', 'serensweb-child' ),
			'new_item'           => __( 'New Project', 'serensweb-child' ),
			'view_item'          => __( 'View Project', 'serensweb-child' ),
			'search_items'       => __( 'Search Projects', 'serensweb-child' ),
			'menu_name'          => __( 'Projects', 'serensweb-child' ),
		],
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-portfolio',
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
		'taxonomies'   => [ 'post_tag', 'project_type' ],
		'rewrite'      => [ 'slug' => 'projects', 'with_front' => false ],
	] );

	register_taxonomy( 'project_type', 'project', [
		'labels' => [
			'name'          => __( 'Project Types', 'serensweb-child' ),
			'singular_name' => __( 'Project Type', 'serensweb-child' ),
		],
		'public'            => true,
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'project-type', 'with_front' => false ],
	] );
} );

// Register first (on init above), then flush once when the theme is switched on.
add_action( 'after_switch_theme', static function () {
	flush_rewrite_rules();
} );
