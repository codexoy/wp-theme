<?php
/**
 * FAQ アコーディオン 1 件。
 *
 * @package Minato
 */
$id = 'faq-' . get_the_ID();
?>
<details class="faq js-faq">
	<summary id="<?php echo esc_attr( $id ); ?>"><?php the_title(); ?></summary>
	<div class="faq__body">
		<?php the_content(); ?>
	</div>
</details>
