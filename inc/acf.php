<?php
/**
 * ACF Pro ローカルフィールドグループ + ACF 未導入時のネイティブフォールバック。
 *
 * 設計方針:
 * - フィールド定義をテーマに同梱し、管理画面の「フィールドグループ」依存をなくす。
 * - 名前は WP All Import の CSV ヘッダーと一致させる（data/*.csv を参照）。
 * - ACF が無くても register_post_meta() と簡易メタボックスで同じキーを保存できる。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * コンテンツモデルのフィールド定義（単一ソース）。
 *
 * @return array
 */
function minato_field_map() {
	return array(
		'project'  => array(
			'minato_client'   => array( 'label' => __( 'クライアント', 'minato' ), 'type' => 'text' ),
			'minato_year'     => array( 'label' => __( '竣工年', 'minato' ), 'type' => 'number' ),
			'minato_place'    => array( 'label' => __( '所在地', 'minato' ), 'type' => 'text' ),
			'minato_area'     => array( 'label' => __( '延床面積', 'minato' ), 'type' => 'text' ),
			'minato_status'   => array( 'label' => __( 'ステータス', 'minato' ), 'type' => 'select', 'choices' => array( 'completed' => __( '竣工', 'minato' ), 'ongoing' => __( '進行中', 'minato' ) ) ),
			'minato_featured' => array( 'label' => __( 'トップに掲載', 'minato' ), 'type' => 'true_false' ),
		),
		'service'  => array(
			'minato_lead'     => array( 'label' => __( 'リード文', 'minato' ), 'type' => 'textarea' ),
			'minato_duration' => array( 'label' => __( '標準期間', 'minato' ), 'type' => 'text' ),
		),
		'location' => array(
			'minato_address' => array( 'label' => __( '住所', 'minato' ), 'type' => 'text' ),
			'minato_tel'     => array( 'label' => __( '電話', 'minato' ), 'type' => 'text' ),
			'minato_hours'   => array( 'label' => __( '営業時間', 'minato' ), 'type' => 'text' ),
			'minato_lat'     => array( 'label' => __( '緯度', 'minato' ), 'type' => 'number' ),
			'minato_lng'     => array( 'label' => __( '経度', 'minato' ), 'type' => 'number' ),
			'minato_zoom'    => array( 'label' => __( '地図ズーム', 'minato' ), 'type' => 'number' ),
		),
		'team'     => array(
			'minato_role'  => array( 'label' => __( '役職', 'minato' ), 'type' => 'text' ),
			'minato_email' => array( 'label' => __( 'メール', 'minato' ), 'type' => 'email' ),
		),
	);
}

/**
 * ACF ローカルフィールドグループを登録する。
 */
function minato_register_acf_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	foreach ( minato_field_map() as $post_type => $fields ) {
		$acf_fields = array();
		$i          = 0;
		foreach ( $fields as $name => $def ) {
			$i++;
			$field = array(
				'key'   => 'field_' . $name,
				'label' => $def['label'],
				'name'  => $name,
				'type'  => $def['type'],
			);
			if ( 'true_false' === $def['type'] ) {
				$field['ui']      = 1;
				$field['message'] = $def['label'];
			}
			if ( ! empty( $def['choices'] ) ) {
				$field['choices'] = $def['choices'];
			}
			if ( 'textarea' === $def['type'] ) {
				$field['rows'] = 3;
			}
			if ( 'number' === $def['type'] ) {
				$field['step'] = ( false !== strpos( $name, 'lat' ) || false !== strpos( $name, 'lng' ) ) ? '0.000001' : '1';
			}
			$acf_fields[] = $field;
		}

		acf_add_local_field_group( array(
			'key'      => 'group_minato_' . $post_type,
			'title'    => sprintf( __( '%s の詳細', 'minato' ), $post_type ),
			'fields'   => $acf_fields,
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => $post_type,
					),
				),
			),
			'position' => 'normal',
			'style'    => 'default',
		) );
	}
}
add_action( 'acf/init', 'minato_register_acf_groups' );

/**
 * REST / WP-CLI から触れるよう post meta を公開する。
 */
function minato_register_meta() {
	foreach ( minato_field_map() as $post_type => $fields ) {
		foreach ( $fields as $name => $def ) {
			$type = 'string';
			if ( 'number' === $def['type'] ) {
				$type = 'number';
			} elseif ( 'true_false' === $def['type'] ) {
				$type = 'boolean';
			}
			register_post_meta( $post_type, $name, array(
				'type'          => $type,
				'single'        => true,
				'show_in_rest'  => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );
		}
	}
}
add_action( 'init', 'minato_register_meta' );

/**
 * ACF が無い環境向けの簡易メタボックス。
 */
function minato_add_fallback_metaboxes() {
	if ( function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	foreach ( array_keys( minato_field_map() ) as $post_type ) {
		add_meta_box(
			'minato_fields_' . $post_type,
			__( 'Minato フィールド', 'minato' ),
			'minato_render_fallback_metabox',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'minato_add_fallback_metaboxes' );

/**
 * フォールバックメタボックスの HTML。
 *
 * @param WP_Post $post 投稿。
 */
function minato_render_fallback_metabox( $post ) {
	wp_nonce_field( 'minato_save_meta', 'minato_meta_nonce' );
	$fields = minato_field_map();
	if ( empty( $fields[ $post->post_type ] ) ) {
		return;
	}
	echo '<div class="minato-metabox">';
	foreach ( $fields[ $post->post_type ] as $name => $def ) {
		$value = get_post_meta( $post->ID, $name, true );
		echo '<p><label for="' . esc_attr( $name ) . '"><strong>' . esc_html( $def['label'] ) . '</strong></label><br />';
		if ( 'textarea' === $def['type'] ) {
			printf( '<textarea class="widefat" rows="3" id="%1$s" name="%1$s">%2$s</textarea>', esc_attr( $name ), esc_textarea( (string) $value ) );
		} elseif ( 'true_false' === $def['type'] ) {
			printf( '<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s />', esc_attr( $name ), checked( (bool) $value, true, false ) );
		} elseif ( 'select' === $def['type'] ) {
			echo '<select id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $def['choices'] as $k => $label ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $k ), selected( (string) $value, (string) $k, false ), esc_html( $label ) );
			}
			echo '</select>';
		} else {
			$input_type = ( 'email' === $def['type'] ) ? 'email' : ( ( 'number' === $def['type'] ) ? 'text' : 'text' );
			printf( '<input class="widefat" type="%1$s" id="%2$s" name="%2$s" value="%3$s" />', esc_attr( $input_type ), esc_attr( $name ), esc_attr( (string) $value ) );
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * フォールバックメタの保存。
 *
 * @param int $post_id 投稿 ID。
 */
function minato_save_fallback_meta( $post_id ) {
	if ( function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	if ( ! isset( $_POST['minato_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['minato_meta_nonce'] ) ), 'minato_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$type   = get_post_type( $post_id );
	$fields = minato_field_map();
	if ( empty( $fields[ $type ] ) ) {
		return;
	}

	foreach ( $fields[ $type ] as $name => $def ) {
		if ( 'true_false' === $def['type'] ) {
			update_post_meta( $post_id, $name, empty( $_POST[ $name ] ) ? 0 : 1 );
			continue;
		}
		if ( ! isset( $_POST[ $name ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( 'textarea' === $def['type'] ) {
			update_post_meta( $post_id, $name, sanitize_textarea_field( $raw ) );
		} elseif ( 'email' === $def['type'] ) {
			update_post_meta( $post_id, $name, sanitize_email( $raw ) );
		} else {
			update_post_meta( $post_id, $name, sanitize_text_field( $raw ) );
		}
	}
}
add_action( 'save_post', 'minato_save_fallback_meta' );
