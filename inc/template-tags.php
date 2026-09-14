<?php
/**
 * エスケープ済みの出力ヘルパー。
 *
 * テンプレートにはロジックを置かず、ここ経由でメタを読む。
 * ACF が入っていれば get_field()、無ければ post meta にフォールバックする。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF / native meta の値を取得する。
 *
 * @param string   $key     フィールド名。
 * @param int|null $post_id 投稿 ID。
 * @return mixed
 */
function minato_field( $key, $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, $post_id );
		if ( null !== $value && false !== $value && '' !== $value ) {
			return $value;
		}
	}

	$value = get_post_meta( $post_id, $key, true );
	if ( '' === $value ) {
		$value = get_post_meta( $post_id, '_' . $key, true );
	}

	return $value;
}

/**
 * アイキャッチ。未設定時は投稿ごとに色の違うプレースホルダー。
 *
 * @param string $size 画像サイズ。
 */
function minato_thumbnail( $size = 'minato-card' ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size, array(
			'alt' => the_title_attribute( array( 'echo' => false ) ),
		) );
		return;
	}

	$hue = ( (int) get_the_ID() * 47 ) % 40;
	printf(
		'<div class="ph ph--%1$s" role="img" aria-label="%2$s" style="--ph-hue:%3$d"></div>',
		esc_attr( $size ),
		esc_attr( get_the_title() ),
		(int) $hue
	);
}

/**
 * 抜粋を整形して返す。
 *
 * @param int $words 語数。
 * @return string
 */
function minato_excerpt( $words = 24 ) {
	$raw = get_the_excerpt();
	if ( ! $raw ) {
		$raw = wp_strip_all_tags( get_the_content( null, false ) );
	}
	return wp_trim_words( $raw, $words, '…' );
}

/**
 * パンくず（JSON-LD と見た目で共有する配列）。
 *
 * @return array<int, array{label:string,url:string}>
 */
function minato_breadcrumbs() {
	$items   = array();
	$items[] = array(
		'label' => __( 'トップ', 'minato' ),
		'url'   => home_url( '/' ),
	);

	if ( is_singular( 'project' ) || is_post_type_archive( 'project' ) ) {
		$items[] = array(
			'label' => __( '仕事', 'minato' ),
			'url'   => get_post_type_archive_link( 'project' ),
		);
	} elseif ( is_singular( 'service' ) || is_post_type_archive( 'service' ) ) {
		$items[] = array(
			'label' => __( 'サービス', 'minato' ),
			'url'   => get_post_type_archive_link( 'service' ),
		);
	} elseif ( is_singular( 'location' ) || is_post_type_archive( 'location' ) ) {
		$items[] = array(
			'label' => __( '拠点', 'minato' ),
			'url'   => get_post_type_archive_link( 'location' ),
		);
	} elseif ( is_home() || is_singular( 'post' ) ) {
		$items[] = array(
			'label' => __( 'ジャーナル', 'minato' ),
			'url'   => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/journal/' ),
		);
	}

	if ( is_singular() ) {
		$items[] = array(
			'label' => get_the_title(),
			'url'   => get_permalink(),
		);
	}

	return $items;
}

/**
 * お問い合わせフォーム送信。
 */
function minato_handle_contact() {
	if ( ! isset( $_POST['minato_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['minato_contact_nonce'] ) ), 'minato_contact' ) ) {
		wp_die( esc_html__( '不正な送信です。', 'minato' ) );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'invalid', wp_get_referer() ?: home_url( '/' ) ) );
		exit;
	}

	$to      = get_theme_mod( 'minato_email', get_option( 'admin_email' ) );
	$subject = sprintf( '[Minato] %s', $name );
	$body    = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );
	$headers = array( 'Reply-To: ' . $email );

	$sent = wp_mail( $to, $subject, $body, $headers );
	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'sent' : 'error', wp_get_referer() ?: home_url( '/' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_minato_contact', 'minato_handle_contact' );
add_action( 'admin_post_minato_contact', 'minato_handle_contact' );
