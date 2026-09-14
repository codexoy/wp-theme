<?php
/**
 * Theme supports, menus, image sizes.
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and navigation menus.
 */
function minato_setup() {
	load_theme_textdomain( 'minato', MINATO_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 280,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'custom-background', array( 'default-color' => 'f3eee6' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'minato' ),
		'footer'  => __( 'Footer Menu', 'minato' ),
		'social'  => __( 'Social Menu', 'minato' ),
	) );

	add_image_size( 'minato-hero', 1600, 1000, true );
	add_image_size( 'minato-card', 800, 1000, true );
	add_image_size( 'minato-wide', 1400, 700, true );
}
add_action( 'after_setup_theme', 'minato_setup' );

/**
 * Content width used by embeds and Gutenberg.
 */
function minato_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'minato_content_width', 0 );

/**
 * Widget areas (kept minimal; most layouts are template-driven).
 */
function minato_widgets() {
	register_sidebar( array(
		'name'          => __( 'Footer notes', 'minato' ),
		'id'            => 'footer-notes',
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="footer-widget__title">',
		'after_title'   => '</p>',
	) );
}
add_action( 'widgets_init', 'minato_widgets' );
