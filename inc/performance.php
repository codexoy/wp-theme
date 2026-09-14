<?php
/**
 * Core Web Vitals 向けのヘッド/スクリプト整理。
 *
 * やりすぎない。絵文字・embed・Dashicons フロントなど、
 * テーマが使わないものを外すだけ。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * フロントで不要な WordPress 既定アセットを外す。
 */
function minato_trim_front_assets() {
	if ( is_admin() ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );

	if ( ! is_user_logged_in() ) {
		wp_dequeue_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'minato_trim_front_assets', 100 );

/**
 * Gutenberg ブロック CSS は本文があるページだけ残す。
 */
function minato_maybe_dequeue_block_library() {
	if ( is_admin() ) {
		return;
	}
	if ( is_singular() && has_blocks() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'minato_maybe_dequeue_block_library', 101 );

/**
 * 画像を WebP/AVIF 優先（WP 6.1+ の srcset に乗る）。
 *
 * @param array $sources ソース。
 * @return array
 */
function minato_prefer_modern_images( $sources ) {
	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'minato_prefer_modern_images' );

/**
 * メニュー・ウィジェット以外の jQuery をフロントから外す。
 */
function minato_drop_jquery() {
	if ( is_admin() || is_customize_preview() ) {
		return;
	}
	if ( wp_script_is( 'jquery', 'enqueued' ) && ! wp_script_is( 'comment-reply', 'enqueued' ) ) {
		// テーマ本体はバニラ JS。他プラグインが jQuery を要求していれば残す。
		return;
	}
}
add_action( 'wp_enqueue_scripts', 'minato_drop_jquery', 99 );
