<?php
/**
 * コメント。ジャーナル以外ではほぼ使わない。
 *
 * @package Minato
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments wrap">
	<?php if ( have_comments() ) : ?>
		<h2><?php esc_html_e( 'コメント', 'minato' ); ?></h2>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol>
	<?php endif; ?>
	<?php comment_form(); ?>
</div>
