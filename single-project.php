<?php
/**
 * プロジェクト詳細。メタは ACF / ネイティブ共通キー。
 *
 * @package Minato
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$specs = array(
			__( 'クライアント', 'minato' ) => minato_field( 'minato_client' ),
			__( '年', 'minato' )           => minato_field( 'minato_year' ),
			__( '所在地', 'minato' )       => minato_field( 'minato_place' ),
			__( '面積', 'minato' )         => minato_field( 'minato_area' ),
			__( '状態', 'minato' )         => minato_field( 'minato_status' ),
		);
		?>
		<article <?php post_class( 'wrap article' ); ?>>
			<p class="eyebrow"><?php esc_html_e( 'Project', 'minato' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<figure class="article__hero"><?php minato_thumbnail( 'minato-wide' ); ?></figure>
			<dl class="spec-grid">
				<?php foreach ( $specs as $label => $value ) : ?>
					<?php if ( $value ) : ?>
						<div>
							<dt><?php echo esc_html( $label ); ?></dt>
							<dd><?php echo esc_html( (string) $value ); ?></dd>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</dl>
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
