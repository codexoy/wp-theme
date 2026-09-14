<?php
/**
 * 検索フォーム。
 *
 * @package Minato
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( '検索', 'minato' ); ?></span>
		<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'キーワード', 'minato' ); ?>">
	</label>
	<button type="submit"><?php esc_html_e( '探す', 'minato' ); ?></button>
</form>
