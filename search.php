<?php
/**
 * 検索結果。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="wrap stack">
		<header class="page-head">
			<h1>
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( '「%s」の検索結果', 'minato' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
			<?php get_search_form(); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cards cards--journal">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', 'post' );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( '一致する結果がありませんでした。', 'minato' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
