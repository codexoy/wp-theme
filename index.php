<?php
/**
 * フォールバックテンプレート。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="wrap stack">
		<header class="page-head">
			<h1><?php echo esc_html( is_home() ? __( 'ジャーナル', 'minato' ) : wp_get_document_title() ); ?></h1>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cards cards--journal">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', get_post_type() );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( '該当する記事がありません。', 'minato' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
