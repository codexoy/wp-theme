<?php
/**
 * 固定ページ。
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
			<header class="page-head">
				<h1><?php the_title(); ?></h1>
			</header>
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
