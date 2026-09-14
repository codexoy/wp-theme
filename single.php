<?php
/**
 * 単一投稿（投稿・サービス・チーム・FAQ）。
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
			<nav class="crumbs" aria-label="<?php esc_attr_e( 'パンくず', 'minato' ); ?>">
				<?php
				$trail = minato_breadcrumbs();
				$last  = count( $trail );
				$i     = 0;
				foreach ( $trail as $crumb ) :
					$i++;
					if ( $i === $last ) {
						echo '<span>' . esc_html( $crumb['label'] ) . '</span>';
					} else {
						echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['label'] ) . '</a><span class="crumbs__sep">/</span>';
					}
				endforeach;
				?>
			</nav>
			<header class="page-head">
				<p class="eyebrow"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
				<h1><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="article__hero"><?php minato_thumbnail( 'minato-wide' ); ?></figure>
			<?php endif; ?>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
