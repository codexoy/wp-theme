<?php
/**
 * フロントページ。スクリーンショットと同じ骨格。
 *
 * @package Minato
 */

get_header();

$featured = new WP_Query( array(
	'post_type'      => 'project',
	'posts_per_page' => 3,
	'meta_key'       => 'minato_featured',
	'meta_value'     => '1',
	'no_found_rows'  => true,
) );

if ( ! $featured->have_posts() ) {
	$featured = new WP_Query( array(
		'post_type'      => 'project',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	) );
}
?>
<main id="main" class="site-main">
	<section class="hero wrap">
		<div class="hero__copy">
			<h1 class="hero__title"><?php esc_html_e( 'Place, craft, and quiet precision.', 'minato' ); ?></h1>
			<span class="hero__rule" aria-hidden="true"></span>
			<p class="hero__lead">
				<?php esc_html_e( '場所、技、そして静かな精度。', 'minato' ); ?><br>
				<?php esc_html_e( '東京の水辺から、品格ある建築を。', 'minato' ); ?>
			</p>
		</div>
		<div class="hero__media">
			<?php
			if ( have_posts() && has_post_thumbnail() ) {
				the_post();
				minato_thumbnail( 'minato-hero' );
				rewind_posts();
			} else {
				echo '<div class="ph ph--minato-hero ph--hero-building" role="img" aria-label="' . esc_attr__( '水辺の建築', 'minato' ) . '"></div>';
			}
			?>
		</div>
	</section>

	<section class="section wrap">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Featured projects', 'minato' ); ?></p>
			<a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">
				<?php esc_html_e( 'View all work', 'minato' ); ?> →
			</a>
		</div>
		<div class="cards">
			<?php
			if ( $featured->have_posts() ) :
				while ( $featured->have_posts() ) :
					$featured->the_post();
					get_template_part( 'template-parts/card', 'project' );
				endwhile;
				wp_reset_postdata();
			else :
				get_template_part( 'template-parts/card', 'project-demo' );
			endif;
			?>
		</div>
	</section>

	<?php
	$faqs = new WP_Query( array(
		'post_type'      => 'faq',
		'posts_per_page' => 4,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
	if ( $faqs->have_posts() ) :
		?>
		<section class="section wrap">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( 'FAQ', 'minato' ); ?></p>
			</div>
			<div class="faq-list">
				<?php
				while ( $faqs->have_posts() ) :
					$faqs->the_post();
					get_template_part( 'template-parts/faq', 'item' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
