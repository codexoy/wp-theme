<?php
/**
 * プロジェクトカード。
 *
 * @package Minato
 */
?>
<article <?php post_class( 'card' ); ?>>
	<a class="card__media" href="<?php the_permalink(); ?>">
		<?php minato_thumbnail( 'minato-card' ); ?>
	</a>
	<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="card__sub">
		<?php echo esc_html( has_excerpt() ? get_the_excerpt() : minato_excerpt( 12 ) ); ?>
	</p>
	<p class="card__meta">
		<span class="pin" aria-hidden="true"></span>
		<?php echo esc_html( minato_field( 'minato_place' ) ?: 'Tokyo' ); ?>
	</p>
</article>
