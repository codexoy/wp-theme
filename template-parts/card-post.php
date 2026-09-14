<?php
/**
 * ジャーナルカード。
 *
 * @package Minato
 */
?>
<article <?php post_class( 'card card--row' ); ?>>
	<a class="card__media" href="<?php the_permalink(); ?>"><?php minato_thumbnail( 'minato-card' ); ?></a>
	<div>
		<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
		<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="card__sub"><?php echo esc_html( minato_excerpt( 22 ) ); ?></p>
	</div>
</article>
