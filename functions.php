<?php
/**
 * Minato theme bootstrap.
 *
 * Architecture (deliberately split by concern, not a 2,000-line functions.php):
 *
 *   inc/setup.php          Theme supports, menus, image sizes
 *   inc/enqueue.php        CSS/JS, preloads, font loading
 *   inc/cpt.php            Custom post types + taxonomies
 *   inc/acf.php            ACF Pro local field groups + native fallbacks
 *   inc/seo.php            Canonical, OGP, Twitter cards
 *   inc/schema.php         Schema.org JSON-LD
 *   inc/i18n.php           ja / en / zh switcher (Polylang, WPML, fallback)
 *   inc/maps.php           Google Maps for location CPT
 *   inc/performance.php    Core Web Vitals hygiene
 *   inc/import.php         WP All Import field map + sample CSV helpers
 *   inc/customizer.php     Site identity, API keys, contact
 *   inc/cli.php            WP-CLI `wp minato seed`
 *   inc/template-tags.php  Escaped output helpers
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MINATO_VERSION', '1.0.0' );
define( 'MINATO_DIR', get_template_directory() );
define( 'MINATO_URI', get_template_directory_uri() );

$minato_includes = array(
	'setup',
	'enqueue',
	'cpt',
	'acf',
	'seo',
	'schema',
	'i18n',
	'maps',
	'performance',
	'import',
	'customizer',
	'template-tags',
	'cli',
);

foreach ( $minato_includes as $minato_file ) {
	$path = MINATO_DIR . '/inc/' . $minato_file . '.php';
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
