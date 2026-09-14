<?php
/**
 * メニュー未設定時のフォールバック。
 *
 * @package Minato
 */

$items = array(
	array( 'url' => get_post_type_archive_link( 'project' ), 'label' => __( 'Work', 'minato' ) ),
	array( 'url' => get_post_type_archive_link( 'service' ), 'label' => __( 'Services', 'minato' ) ),
	array( 'url' => get_post_type_archive_link( 'location' ), 'label' => __( 'Locations', 'minato' ) ),
	array( 'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/journal/' ), 'label' => __( 'Journal', 'minato' ) ),
	array( 'url' => home_url( '/contact/' ), 'label' => __( 'Contact', 'minato' ) ),
);
?>
<ul class="primary-nav__list">
	<?php foreach ( $items as $item ) : ?>
		<?php if ( $item['url'] ) : ?>
			<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
