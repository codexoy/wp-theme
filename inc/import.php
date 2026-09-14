<?php
/**
 * WP All Import 向けのフィールド対応表。
 *
 * CSV は data/ 配下。ユニークキーはタイトルではなく slug を推奨。
 * インポート後に `wp rewrite flush` を走らせること。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP All Import のカスタムフィールド候補（管理画面の説明用）。
 *
 * @return array<string, string>
 */
function minato_import_field_help() {
	return array(
		'minato_client'   => 'project.client',
		'minato_year'     => 'project.year',
		'minato_place'    => 'project.place',
		'minato_area'     => 'project.area',
		'minato_status'   => 'project.status (completed|ongoing)',
		'minato_featured' => 'project.featured (0|1)',
		'minato_address'  => 'location.address',
		'minato_tel'      => 'location.tel',
		'minato_hours'    => 'location.hours',
		'minato_lat'      => 'location.lat',
		'minato_lng'      => 'location.lng',
		'minato_zoom'     => 'location.zoom',
		'minato_lead'     => 'service.lead',
		'minato_duration' => 'service.duration',
		'minato_role'     => 'team.role',
		'minato_email'    => 'team.email',
	);
}

/**
 * インポート直後に真偽値・数値が文字列のまま残らないよう正規化する。
 *
 * @param int $post_id 投稿 ID。
 */
function minato_normalize_imported_meta( $post_id ) {
	$type = get_post_type( $post_id );
	$map  = minato_field_map();
	if ( empty( $map[ $type ] ) ) {
		return;
	}
	foreach ( $map[ $type ] as $key => $def ) {
		$value = get_post_meta( $post_id, $key, true );
		if ( '' === $value ) {
			continue;
		}
		if ( 'true_false' === $def['type'] ) {
			update_post_meta( $post_id, $key, in_array( (string) $value, array( '1', 'true', 'yes' ), true ) ? 1 : 0 );
		}
		if ( 'number' === $def['type'] && is_numeric( $value ) ) {
			update_post_meta( $post_id, $key, 0 + $value );
		}
	}
}
add_action( 'pmxi_saved_post', 'minato_normalize_imported_meta', 10, 1 );

/**
 * サンプル CSV の URL（管理画面用）。
 *
 * @return array<string, string>
 */
function minato_sample_csv_urls() {
	$base = MINATO_URI . '/data/';
	return array(
		'project'  => $base . 'projects-sample.csv',
		'location' => $base . 'locations-sample.csv',
		'faq'      => $base . 'faqs-sample.csv',
	);
}

/**
 * テーマ画面に CSV の置き場を表示する。
 */
function minato_import_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'dashboard' !== $screen->id ) {
		return;
	}
	if ( ! isset( $_GET['minato_help'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	echo '<div class="notice notice-info"><p>' . esc_html__( 'Minato のサンプル CSV はテーマの data/ ディレクトリにあります。WP All Import で投稿タイプを合わせ、カスタムフィールド名を minato_* にマップしてください。', 'minato' ) . '</p></div>';
}
add_action( 'admin_notices', 'minato_import_admin_notice' );
