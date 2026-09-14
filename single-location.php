<?php
/**
 * 拠点詳細 + Google Maps。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'wrap article' ); ?>>
			<p class="eyebrow"><?php esc_html_e( 'Location', 'minato' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="lede"><?php echo esc_html( (string) minato_field( 'minato_address' ) ); ?></p>
			<?php minato_map( get_the_ID() ); ?>
			<dl class="spec-grid">
				<?php if ( minato_field( 'minato_tel' ) ) : ?>
					<div>
						<dt><?php esc_html_e( '電話', 'minato' ); ?></dt>
						<dd><?php echo esc_html( (string) minato_field( 'minato_tel' ) ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( minato_field( 'minato_hours' ) ) : ?>
					<div>
						<dt><?php esc_html_e( '時間', 'minato' ); ?></dt>
						<dd><?php echo esc_html( (string) minato_field( 'minato_hours' ) ); ?></dd>
					</div>
				<?php endif; ?>
			</dl>
			<div class="prose"><?php the_content(); ?></div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
