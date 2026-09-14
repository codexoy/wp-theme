<?php
/**
 * 404。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="wrap article">
		<p class="eyebrow">404</p>
		<h1><?php esc_html_e( 'ページが見つかりません。', 'minato' ); ?></h1>
		<p class="lede"><?php esc_html_e( 'アドレスを確認するか、トップから探してください。', 'minato' ); ?></p>
		<p><a class="text-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'トップへ戻る', 'minato' ); ?> →</a></p>
	</div>
</main>
<?php
get_footer();
