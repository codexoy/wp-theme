<?php
/**
 * Schema.org JSON-LD。
 *
 * 出力するもの:
 * - Organization + WebSite（全ページ）
 * - BreadcrumbList
 * - CreativeWork（project）
 * - LocalBusiness（location）
 * - FAQPage（faq アーカイブ / フロントの FAQ ブロック）
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JSON-LD をまとめて出力する。
 */
function minato_schema_head() {
	$graph = array_filter( array(
		minato_schema_organization(),
		minato_schema_website(),
		minato_schema_breadcrumb(),
		minato_schema_project(),
		minato_schema_location(),
		minato_schema_faq(),
		minato_schema_article(),
	) );

	if ( ! $graph ) {
		return;
	}

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( $graph ),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'minato_schema_head', 5 );

/**
 * 組織。
 *
 * @return array
 */
function minato_schema_organization() {
	return array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#organization' ),
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
		'logo'  => MINATO_URI . '/screenshot.png',
		'email' => get_theme_mod( 'minato_email', get_option( 'admin_email' ) ),
		'telephone' => get_theme_mod( 'minato_tel', '' ),
		'address'   => array(
			'@type'         => 'PostalAddress',
			'streetAddress' => get_theme_mod( 'minato_address', '' ),
			'addressLocality' => 'Tokyo',
			'addressCountry'  => 'JP',
		),
	);
}

/**
 * WebSite + SearchAction。
 *
 * @return array
 */
function minato_schema_website() {
	return array(
		'@type' => 'WebSite',
		'@id'   => home_url( '/#website' ),
		'url'   => home_url( '/' ),
		'name'  => get_bloginfo( 'name' ),
		'publisher' => array( '@id' => home_url( '/#organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * パンくず。
 *
 * @return array|null
 */
function minato_schema_breadcrumb() {
	if ( is_front_page() ) {
		return null;
	}
	$list = array();
	$i    = 1;
	foreach ( minato_breadcrumbs() as $crumb ) {
		$list[] = array(
			'@type'    => 'ListItem',
			'position' => $i,
			'name'     => $crumb['label'],
			'item'     => $crumb['url'],
		);
		$i++;
	}
	return array(
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $list,
	);
}

/**
 * プロジェクト詳細。
 *
 * @return array|null
 */
function minato_schema_project() {
	if ( ! is_singular( 'project' ) ) {
		return null;
	}
	return array(
		'@type'       => 'CreativeWork',
		'name'        => get_the_title(),
		'description' => minato_meta_description(),
		'url'         => get_permalink(),
		'dateCreated' => (string) minato_field( 'minato_year' ),
		'locationCreated' => array(
			'@type' => 'Place',
			'name'  => (string) minato_field( 'minato_place' ),
		),
		'creator' => array( '@id' => home_url( '/#organization' ) ),
	);
}

/**
 * 拠点。
 *
 * @return array|null
 */
function minato_schema_location() {
	if ( ! is_singular( 'location' ) ) {
		return null;
	}
	$lat = minato_field( 'minato_lat' );
	$lng = minato_field( 'minato_lng' );
	$geo = null;
	if ( $lat && $lng ) {
		$geo = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $lat,
			'longitude' => (float) $lng,
		);
	}
	return array(
		'@type'     => 'LocalBusiness',
		'name'      => get_the_title(),
		'url'       => get_permalink(),
		'address'   => (string) minato_field( 'minato_address' ),
		'telephone' => (string) minato_field( 'minato_tel' ),
		'openingHours' => (string) minato_field( 'minato_hours' ),
		'geo'       => $geo,
		'parentOrganization' => array( '@id' => home_url( '/#organization' ) ),
	);
}

/**
 * FAQ。フロントまたは faq アーカイブ。
 *
 * @return array|null
 */
function minato_schema_faq() {
	$ids = array();
	if ( is_singular( 'faq' ) ) {
		$ids[] = get_the_ID();
	} elseif ( is_post_type_archive( 'faq' ) || is_front_page() ) {
		$q = new WP_Query( array(
			'post_type'      => 'faq',
			'posts_per_page' => 12,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		) );
		$ids = wp_list_pluck( $q->posts, 'ID' );
		wp_reset_postdata();
	}

	if ( ! $ids ) {
		return null;
	}

	$entities = array();
	foreach ( $ids as $id ) {
		$entities[] = array(
			'@type' => 'Question',
			'name'  => get_the_title( $id ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( get_post_field( 'post_content', $id ) ),
			),
		);
	}

	return array(
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}

/**
 * ジャーナル記事。
 *
 * @return array|null
 */
function minato_schema_article() {
	if ( ! is_singular( 'post' ) ) {
		return null;
	}
	return array(
		'@type'         => 'Article',
		'headline'      => get_the_title(),
		'datePublished' => get_the_date( DATE_W3C ),
		'dateModified'  => get_the_modified_date( DATE_W3C ),
		'author'        => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'publisher' => array( '@id' => home_url( '/#organization' ) ),
	);
}
