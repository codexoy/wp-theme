<?php
/**
 * 日 / 英 / 中 の言語切替。
 *
 * 優先順:
 * 1. Polylang (pll_*)
 * 2. WPML (ICL_LANGUAGE_CODE)
 * 3. クエリ ?lang=ja|en|zh （プラグイン無しのデモ用）
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 対応言語。
 *
 * @return array<string, string>
 */
function minato_languages() {
	return array(
		'ja' => 'JA',
		'en' => 'EN',
		'zh' => 'ZH',
	);
}

/**
 * 現在の言語コード。
 *
 * @return string
 */
function minato_current_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$code = pll_current_language();
		if ( $code ) {
			return minato_normalize_lang( $code );
		}
	}
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return minato_normalize_lang( ICL_LANGUAGE_CODE );
	}
	if ( isset( $_GET['lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return minato_normalize_lang( sanitize_key( wp_unslash( $_GET['lang'] ) ) );
	}
	return minato_normalize_lang( get_locale() );
}

/**
 * ja / en / zh に正規化する。
 *
 * @param string $code ロケールまたは短いコード。
 * @return string
 */
function minato_normalize_lang( $code ) {
	$code = strtolower( (string) $code );
	if ( 0 === strpos( $code, 'ja' ) ) {
		return 'ja';
	}
	if ( 0 === strpos( $code, 'zh' ) ) {
		return 'zh';
	}
	if ( 0 === strpos( $code, 'en' ) ) {
		return 'en';
	}
	return 'ja';
}

/**
 * 指定言語の URL。
 *
 * @param string $lang 言語コード。
 * @return string
 */
function minato_language_url( $lang ) {
	$lang = minato_normalize_lang( $lang );

	if ( function_exists( 'pll_the_languages' ) ) {
		$langs = pll_the_languages( array( 'raw' => 1 ) );
		foreach ( (array) $langs as $item ) {
			if ( minato_normalize_lang( $item['slug'] ) === $lang ) {
				return $item['url'];
			}
		}
	}

	if ( function_exists( 'icl_get_languages' ) ) {
		$langs = icl_get_languages( 'skip_missing=0' );
		foreach ( (array) $langs as $item ) {
			if ( minato_normalize_lang( $item['language_code'] ) === $lang ) {
				return $item['url'];
			}
		}
	}

	return add_query_arg( 'lang', $lang, minato_canonical_url() );
}

/**
 * hreflang リンク。
 */
function minato_hreflang_tags() {
	foreach ( array_keys( minato_languages() ) as $lang ) {
		$hreflang = ( 'zh' === $lang ) ? 'zh-Hans' : $lang;
		printf(
			'<link rel="alternate" hreflang="%s" href="%s" />' . "\n",
			esc_attr( $hreflang ),
			esc_url( minato_language_url( $lang ) )
		);
	}
	printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( minato_language_url( 'ja' ) ) );
}

/**
 * 言語スイッチ（テンプレートから呼ぶ）。
 */
function minato_language_switcher() {
	$current = minato_current_lang();
	echo '<nav class="lang" aria-label="' . esc_attr__( '言語切替', 'minato' ) . '">';
	$i = 0;
	foreach ( minato_languages() as $code => $label ) {
		if ( $i > 0 ) {
			echo '<span class="lang__dot" aria-hidden="true">·</span>';
		}
		printf(
			'<a class="lang__link%1$s" href="%2$s" hreflang="%3$s" lang="%3$s">%4$s</a>',
			$current === $code ? ' is-active' : '',
			esc_url( minato_language_url( $code ) ),
			esc_attr( $code ),
			esc_html( $label )
		);
		$i++;
	}
	echo '</nav>';
}

/**
 * html の lang 属性を現在言語に合わせる。
 *
 * @param string $output 既存属性。
 * @return string
 */
function minato_language_attributes( $output ) {
	$map  = array(
		'ja' => 'ja',
		'en' => 'en',
		'zh' => 'zh-Hans',
	);
	$lang = $map[ minato_current_lang() ];
	if ( preg_match( '/lang="[^"]+"/', $output ) ) {
		return preg_replace( '/lang="[^"]+"/', 'lang="' . esc_attr( $lang ) . '"', $output, 1 );
	}
	return $output . ' lang="' . esc_attr( $lang ) . '"';
}
add_filter( 'language_attributes', 'minato_language_attributes' );
