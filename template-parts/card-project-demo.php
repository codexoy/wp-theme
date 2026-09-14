<?php
/**
 * 投稿が空のときの見た目用カード（スクリーンショット準拠）。
 * シード前でもレイアウトを確認できるようにする。
 *
 * @package Minato
 */

$demos = array(
	array(
		'title' => 'Azabu Cove Residence',
		'sub'   => '麻布十番の水景レジデンス',
		'place' => 'Tokyo',
		'mod'   => 'water',
	),
	array(
		'title' => 'Kagami Studio',
		'sub'   => '虎ノ門のクラフトスタジオ',
		'place' => 'Tokyo',
		'mod'   => 'studio',
	),
	array(
		'title' => 'Minami Aoyama House',
		'sub'   => '南青山の邸宅',
		'place' => 'Tokyo',
		'mod'   => 'house',
	),
);
foreach ( $demos as $demo ) :
	?>
	<article class="card">
		<div class="card__media">
			<div class="ph ph--minato-card ph--<?php echo esc_attr( $demo['mod'] ); ?>" role="img" aria-label="<?php echo esc_attr( $demo['title'] ); ?>"></div>
		</div>
		<h2 class="card__title"><?php echo esc_html( $demo['title'] ); ?></h2>
		<p class="card__sub"><?php echo esc_html( $demo['sub'] ); ?></p>
		<p class="card__meta"><span class="pin" aria-hidden="true"></span><?php echo esc_html( $demo['place'] ); ?></p>
	</article>
	<?php
endforeach;
