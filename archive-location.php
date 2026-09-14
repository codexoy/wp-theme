<?php
/**
 * 拠点一覧 + 全拠点マップ。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="wrap stack">
		<header class="page-head">
			<p class="eyebrow"><?php esc_html_e( 'Locations', 'minato' ); ?></p>
			<h1><?php esc_html_e( '拠点', 'minato' ); ?></h1>
		</header>
		<?php minato_map(); ?>
		<?php if ( have_posts() ) : ?>
			<div class="cards cards--locations">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'card' ); ?>>
						<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="card__meta">
							<span class="pin" aria-hidden="true"></span>
							<?php echo esc_html( (string) minato_field( 'minato_address' ) ); ?>
						</p>
					</article>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
