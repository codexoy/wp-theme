<?php
/**
 * サービスカード。
 *
 * @package Minato
 */
?>
<article <?php post_class( 'card' ); ?>>
	<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="card__sub"><?php echo esc_html( minato_field( 'minato_lead' ) ?: minato_excerpt( 18 ) ); ?></p>
	<?php if ( minato_field( 'minato_duration' ) ) : ?>
		<p class="card__meta"><?php echo esc_html( (string) minato_field( 'minato_duration' ) ); ?></p>
	<?php endif; ?>
</article>
