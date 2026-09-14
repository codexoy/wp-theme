<?php
/**
 * 内部 SEO: canonical / OGP / Twitter Card / hreflang。
 *
 * Yoast または Rank Math が入っている場合は二重出力しない。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SEO プラグインが既にヘッドを握っているか。
 *
 * @return bool
 */
function minato_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/**
 * 現在ページの正規 URL。
 *
 * @return string
 */
function minato_canonical_url() {
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_post_type_archive() ) {
		return get_post_type_archive_link( get_query_var( 'post_type' ) );
	}
	if ( is_home() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		return $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
	}
	return home_url( add_query_arg( array() ) );
}

/**
 * OGP 用の説明文。
 *
 * @return string
 */
function minato_meta_description() {
	if ( is_singular() && has_excerpt() ) {
		return wp_strip_all_tags( get_the_excerpt() );
	}
	if ( is_singular() ) {
		return wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', get_queried_object_id() ) ), 40, '…' );
	}
	$tagline = get_bloginfo( 'description', 'display' );
	return $tagline ? $tagline : __( '場所、技、そして静かな精度。東京の水辺から品格ある建築を。', 'minato' );
}

/**
 * OGP 画像 URL。
 *
 * @return string
 */
function minato_og_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_url( get_post_thumbnail_id(), 'minato-wide' );
		if ( $src ) {
			return $src;
		}
	}
	$custom = get_theme_mod( 'minato_og_image' );
	if ( $custom ) {
		return $custom;
	}
	return MINATO_URI . '/screenshot.png';
}

/**
 * head に SEO タグを出す。
 */
function minato_seo_head() {
	if ( minato_has_seo_plugin() ) {
		return;
	}

	$canonical = minato_canonical_url();
	$title     = wp_get_document_title();
	$desc      = minato_meta_description();
	$image     = minato_og_image();
	$locale    = get_locale();
	$type      = is_singular( 'project' ) ? 'article' : 'website';

	echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
	echo '<meta name="description" content="' . esc_attr( $desc ) . '" />' . "\n";

	$og = array(
		'og:locale'      => $locale,
		'og:type'        => $type,
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $canonical,
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:image'       => $image,
	);
	foreach ( $og as $prop => $content ) {
		printf( '<meta property="%s" content="%s" />' . "\n", esc_attr( $prop ), esc_attr( $content ) );
	}

	printf( '<meta name="twitter:card" content="summary_large_image" />' . "\n" );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $desc ) );
	printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $image ) );

	if ( function_exists( 'minato_hreflang_tags' ) ) {
		minato_hreflang_tags();
	}
}
add_action( 'wp_head', 'minato_seo_head', 1 );
