<?php
/**
 * Asset loading. Fonts are preloaded, CSS is render-critical, JS is deferred.
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end assets.
 */
function minato_enqueue() {
	$ver = MINATO_VERSION;

	wp_enqueue_style(
		'minato-fonts',
		'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;1,6..72,400&family=Noto+Sans+JP:wght@400;500&family=Noto+Serif+JP:wght@400;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'minato', MINATO_URI . '/assets/css/main.css', array( 'minato-fonts' ), $ver );

	wp_enqueue_script( 'minato', MINATO_URI . '/assets/js/main.js', array(), $ver, array( 'strategy' => 'defer', 'in_footer' => true ) );

	wp_localize_script( 'minato', 'minatoData', array(
		'homeUrl'  => home_url( '/' ),
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'mapsKey'  => minato_maps_api_key(),
		'i18n'     => array(
			'openMenu'  => __( 'Open menu', 'minato' ),
			'closeMenu' => __( 'Close menu', 'minato' ),
		),
	) );

	if ( is_singular( 'location' ) || is_post_type_archive( 'location' ) || is_page_template( 'page-templates/template-contact.php' ) ) {
		wp_enqueue_script( 'minato-maps', MINATO_URI . '/assets/js/maps.js', array( 'minato' ), $ver, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'minato_enqueue' );

/**
 * Preconnect / preload hints for Core Web Vitals.
 */
function minato_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => false,
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => true,
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'minato_resource_hints', 10, 2 );

/**
 * Add fetchpriority=high to the first content image on singular views.
 */
function minato_featured_image_attr( $attr, $attachment, $size ) {
	if ( is_admin() ) {
		return $attr;
	}
	static $first = true;
	if ( $first && in_array( $size, array( 'minato-hero', 'minato-wide', 'full' ), true ) ) {
		$attr['fetchpriority'] = 'high';
		$attr['loading']       = 'eager';
		$first                 = false;
	} else {
		$attr['loading']  = $attr['loading'] ?? 'lazy';
		$attr['decoding'] = 'async';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'minato_featured_image_attr', 10, 3 );
