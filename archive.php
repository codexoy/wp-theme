<?php
/**
 * 汎用アーカイブ。投稿タイプに応じたカードを出す。
 *
 * @package Minato
 */

get_header();

$title = is_post_type_archive() ? post_type_archive_title( '', false ) : get_the_archive_title();
?>
<main id="main" class="site-main">
	<div class="wrap stack">
		<header class="page-head">
			<h1><?php echo esc_html( wp_strip_all_tags( $title ) ); ?></h1>
			<?php the_archive_description( '<p class="lede">', '</p>' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cards">
				<?php
				while ( have_posts() ) :
					the_post();
					$part = locate_template( 'template-parts/card-' . get_post_type() . '.php' ) ? get_post_type() : 'post';
					get_template_part( 'template-parts/card', $part );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'まだ公開されている項目がありません。', 'minato' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
