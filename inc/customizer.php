<?php
/**
 * カスタマイザー。サイト情報と外部 API キーだけを持つ。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * セクションとコントロールを登録する。
 *
 * @param WP_Customize_Manager $wp_customize カスタマイザー。
 */
function minato_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'minato_studio', array(
		'title'    => __( 'Minato スタジオ情報', 'minato' ),
		'priority' => 30,
	) );

	$fields = array(
		'minato_email'   => array( 'label' => __( 'メール', 'minato' ), 'default' => get_option( 'admin_email' ) ),
		'minato_tel'     => array( 'label' => __( '電話', 'minato' ), 'default' => '' ),
		'minato_address' => array( 'label' => __( '住所', 'minato' ), 'default' => '' ),
		'minato_maps_key'=> array( 'label' => __( 'Google Maps API キー', 'minato' ), 'default' => '' ),
		'minato_og_image'=> array( 'label' => __( '既定 OGP 画像 URL', 'minato' ), 'default' => '' ),
	);

	foreach ( $fields as $id => $args ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'minato_email' === $id ? 'sanitize_email' : ( 'minato_og_image' === $id ? 'esc_url_raw' : 'sanitize_text_field' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $args['label'],
			'section' => 'minato_studio',
			'type'    => 'text',
		) );
	}
}
add_action( 'customize_register', 'minato_customize_register' );
