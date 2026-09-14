<?php
/**
 * フッター。
 *
 * @package Minato
 */
?>
<footer class="site-footer">
	<div class="wrap site-footer__grid">
		<div>
			<p class="logo logo--footer"><?php bloginfo( 'name' ); ?></p>
			<p class="site-footer__lead">
				<?php esc_html_e( '場所、技、そして静かな精度。東京の水辺から品格ある建築を。', 'minato' ); ?>
			</p>
		</div>
		<div>
			<p class="footer-widget__title"><?php esc_html_e( '案内', 'minato' ); ?></p>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-nav',
					'depth'          => 1,
				) );
			} else {
				echo '<ul class="footer-nav">';
				echo '<li><a href="' . esc_url( get_post_type_archive_link( 'project' ) ) . '">' . esc_html__( '仕事', 'minato' ) . '</a></li>';
				echo '<li><a href="' . esc_url( get_post_type_archive_link( 'service' ) ) . '">' . esc_html__( 'サービス', 'minato' ) . '</a></li>';
				echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( '問い合わせ', 'minato' ) . '</a></li>';
				echo '</ul>';
			}
			?>
		</div>
		<div>
			<p class="footer-widget__title"><?php esc_html_e( '連絡', 'minato' ); ?></p>
			<p><?php echo esc_html( get_theme_mod( 'minato_address', __( '東京都港区', 'minato' ) ) ); ?></p>
			<p><?php echo esc_html( get_theme_mod( 'minato_email', get_option( 'admin_email' ) ) ); ?></p>
			<?php if ( is_active_sidebar( 'footer-notes' ) ) : ?>
				<?php dynamic_sidebar( 'footer-notes' ); ?>
			<?php endif; ?>
		</div>
	</div>
	<p class="wrap site-footer__legal">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
	</p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
