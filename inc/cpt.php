<?php
/**
 * Custom post types and taxonomies.
 *
 * Content model (designed for ACF + WP All Import):
 *
 *   project     Case studies / selected work     taxonomy: project_type
 *   service     Offerings sold by the studio     taxonomy: service_group
 *   location    Offices (Google Maps)            meta: lat/lng/address
 *   team        People
 *   faq         Questions (FAQPage schema)
 *
 * REST-enabled so WP-CLI, headless consumers, and WP All Import can write them.
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post types.
 */
function minato_register_cpts() {
	$shared = array(
		'show_in_rest'    => true,
		'public'          => true,
		'has_archive'     => true,
		'show_in_nav_menus' => true,
		'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
	);

	register_post_type( 'project', array_merge( $shared, array(
		'label'             => __( 'Projects', 'minato' ),
		'menu_icon'         => 'dashicons-portfolio',
		'rewrite'           => array( 'slug' => 'work' ),
		'has_archive'       => 'work',
		'menu_position'     => 21,
		'labels'            => minato_cpt_labels( __( 'Project', 'minato' ), __( 'Projects', 'minato' ) ),
	) ) );

	register_post_type( 'service', array_merge( $shared, array(
		'label'         => __( 'Services', 'minato' ),
		'menu_icon'     => 'dashicons-hammer',
		'rewrite'       => array( 'slug' => 'services' ),
		'has_archive'   => 'services',
		'menu_position' => 22,
		'labels'        => minato_cpt_labels( __( 'Service', 'minato' ), __( 'Services', 'minato' ) ),
	) ) );

	register_post_type( 'location', array_merge( $shared, array(
		'label'         => __( 'Locations', 'minato' ),
		'menu_icon'     => 'dashicons-location',
		'rewrite'       => array( 'slug' => 'locations' ),
		'has_archive'   => 'locations',
		'menu_position' => 23,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'labels'        => minato_cpt_labels( __( 'Location', 'minato' ), __( 'Locations', 'minato' ) ),
	) ) );

	register_post_type( 'team', array_merge( $shared, array(
		'label'         => __( 'Team', 'minato' ),
		'menu_icon'     => 'dashicons-groups',
		'rewrite'       => array( 'slug' => 'team' ),
		'has_archive'   => 'team',
		'menu_position' => 24,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'labels'        => minato_cpt_labels( __( 'Person', 'minato' ), __( 'Team', 'minato' ) ),
	) ) );

	register_post_type( 'faq', array(
		'label'           => __( 'FAQs', 'minato' ),
		'menu_icon'       => 'dashicons-editor-help',
		'public'          => true,
		'has_archive'     => 'faq',
		'rewrite'         => array( 'slug' => 'faq' ),
		'show_in_rest'    => true,
		'menu_position'   => 25,
		'supports'        => array( 'title', 'editor', 'page-attributes', 'custom-fields' ),
		'labels'          => minato_cpt_labels( __( 'FAQ', 'minato' ), __( 'FAQs', 'minato' ) ),
	) );

	register_taxonomy( 'project_type', array( 'project' ), array(
		'label'        => __( 'Project types', 'minato' ),
		'public'       => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'work-type' ),
	) );

	register_taxonomy( 'service_group', array( 'service' ), array(
		'label'        => __( 'Service groups', 'minato' ),
		'public'       => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'service-group' ),
	) );
}
add_action( 'init', 'minato_register_cpts' );

/**
 * Build CPT labels from singular/plural.
 *
 * @param string $one Singular.
 * @param string $many Plural.
 * @return array
 */
function minato_cpt_labels( $one, $many ) {
	return array(
		'name'          => $many,
		'singular_name' => $one,
		'add_new_item'  => sprintf( __( 'Add %s', 'minato' ), $one ),
		'edit_item'     => sprintf( __( 'Edit %s', 'minato' ), $one ),
		'search_items'  => sprintf( __( 'Search %s', 'minato' ), $many ),
		'all_items'     => $many,
	);
}

/**
 * Flush rewrite rules once after theme activation.
 */
function minato_activate() {
	minato_register_cpts();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'minato_activate' );
