<?php
/**
 * ヘッダー。
 *
 * @package Minato
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( '本文へスキップ', 'minato' ); ?></a>

<header class="site-header">
	<div class="wrap site-header__inner">
		<p class="logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</p>

		<button class="nav-toggle js-nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
			<span class="nav-toggle__bars" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'メニュー', 'minato' ); ?></span>
		</button>

		<nav id="primary-nav" class="primary-nav js-nav" aria-label="<?php esc_attr_e( 'メイン', 'minato' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-nav__list',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
			} else {
				get_template_part( 'template-parts/nav', 'fallback' );
			}
			?>
		</nav>

		<?php minato_language_switcher(); ?>
	</div>
</header>
