<?php
/**
 * Google Maps。拠点 CPT の lat/lng をフロントへ渡す。
 *
 * API キーはカスタマイザーのみ。ソースに埋め込まない。
 * JS は拠点・お問い合わせページだけで読み込む（enqueue.php）。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Maps API キー。
 *
 * @return string
 */
function minato_maps_api_key() {
	$key = get_theme_mod( 'minato_maps_key', '' );
	if ( ! $key && defined( 'MINATO_MAPS_KEY' ) ) {
		$key = MINATO_MAPS_KEY;
	}
	return (string) $key;
}

/**
 * 公開中の拠点を地図用配列にする。
 *
 * @return array<int, array<string, mixed>>
 */
function minato_map_markers() {
	$query = new WP_Query( array(
		'post_type'      => 'location',
		'posts_per_page' => 50,
		'post_status'    => 'publish',
		'no_found_rows'  => true,
	) );

	$markers = array();
	foreach ( $query->posts as $post ) {
		$lat = minato_field( 'minato_lat', $post->ID );
		$lng = minato_field( 'minato_lng', $post->ID );
		if ( '' === (string) $lat || '' === (string) $lng ) {
			continue;
		}
		$markers[] = array(
			'id'      => $post->ID,
			'title'   => get_the_title( $post ),
			'url'     => get_permalink( $post ),
			'address' => (string) minato_field( 'minato_address', $post->ID ),
			'lat'     => (float) $lat,
			'lng'     => (float) $lng,
			'zoom'    => (int) ( minato_field( 'minato_zoom', $post->ID ) ?: 15 ),
		);
	}
	wp_reset_postdata();
	return $markers;
}

/**
 * 地図コンテナの data 属性を出力する。
 *
 * @param int|null $post_id 単一拠点。null なら全拠点。
 */
function minato_map( $post_id = null ) {
	$markers = array();
	if ( $post_id ) {
		$lat = minato_field( 'minato_lat', $post_id );
		$lng = minato_field( 'minato_lng', $post_id );
		if ( '' !== (string) $lat && '' !== (string) $lng ) {
			$markers[] = array(
				'id'      => $post_id,
				'title'   => get_the_title( $post_id ),
				'url'     => get_permalink( $post_id ),
				'address' => (string) minato_field( 'minato_address', $post_id ),
				'lat'     => (float) $lat,
				'lng'     => (float) $lng,
				'zoom'    => (int) ( minato_field( 'minato_zoom', $post_id ) ?: 16 ),
			);
		}
	} else {
		$markers = minato_map_markers();
	}

	if ( ! $markers ) {
		echo '<p class="map-empty">' . esc_html__( '緯度経度が未設定の拠点です。', 'minato' ) . '</p>';
		return;
	}

	printf(
		'<div class="map js-map" data-markers="%s" data-key="%s" role="region" aria-label="%s"></div>',
		esc_attr( wp_json_encode( $markers ) ),
		esc_attr( minato_maps_api_key() ),
		esc_attr__( '拠点地図', 'minato' )
	);
}
