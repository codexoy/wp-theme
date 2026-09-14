<?php
/**
 * 仕事一覧。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="wrap stack">
		<header class="page-head">
			<p class="eyebrow"><?php esc_html_e( 'Work', 'minato' ); ?></p>
			<h1><?php esc_html_e( '選択した仕事', 'minato' ); ?></h1>
			<p class="lede"><?php esc_html_e( '住宅、仕事場、公共。場所の性格を先に読む。', 'minato' ); ?></p>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cards">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', 'project' );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'プロジェクトは wp minato seed で投入できます。', 'minato' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
