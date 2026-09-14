<?php
/**
 * WP-CLI: `wp minato seed`
 *
 * ポートフォリオ確認用の日本語デモ投稿を投入する。
 * 既存の同じスラッグは上書きしない。
 *
 * @package Minato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

/**
 * Minato 用コマンド。
 */
class Minato_CLI {

	/**
	 * デモコンテンツを投入する。
	 *
	 * ## EXAMPLES
	 *
	 *     wp minato seed
	 *
	 * @when after_wp_load
	 */
	public function seed() {
		minato_register_cpts();

		$projects = array(
			array(
				'title'   => 'Azabu Cove Residence',
				'slug'    => 'azabu-cove-residence',
				'excerpt' => '麻布十番の水景レジデンス',
				'content' => '水辺に面した集合住宅。視線の抜けと静かな精度を両立した外皮。',
				'meta'    => array(
					'minato_client'   => 'Private',
					'minato_year'     => 2024,
					'minato_place'    => 'Tokyo',
					'minato_area'     => '1,240㎡',
					'minato_status'   => 'completed',
					'minato_featured' => 1,
				),
				'term'    => 'Residence',
			),
			array(
				'title'   => 'Kagami Studio',
				'slug'    => 'kagami-studio',
				'excerpt' => '虎ノ門のクラフトスタジオ',
				'content' => '手仕事のための一室。机の高さまで設計した光。',
				'meta'    => array(
					'minato_client'   => 'Kagami',
					'minato_year'     => 2023,
					'minato_place'    => 'Tokyo',
					'minato_area'     => '86㎡',
					'minato_status'   => 'completed',
					'minato_featured' => 1,
				),
				'term'    => 'Workspace',
			),
			array(
				'title'   => 'Minami Aoyama House',
				'slug'    => 'minami-aoyama-house',
				'excerpt' => '南青山の邸宅',
				'content' => '街路から一段引いた玄関。樹と門型のプロポーション。',
				'meta'    => array(
					'minato_client'   => 'Private',
					'minato_year'     => 2025,
					'minato_place'    => 'Tokyo',
					'minato_area'     => '412㎡',
					'minato_status'   => 'ongoing',
					'minato_featured' => 1,
				),
				'term'    => 'Residence',
			),
		);

		foreach ( $projects as $item ) {
			$this->upsert( 'project', $item, 'project_type' );
		}

		$locations = array(
			array(
				'title'   => 'Minato Atelier',
				'slug'    => 'minato-atelier',
				'excerpt' => '港区のアトリエ',
				'content' => '模型と図面が並ぶ小さな事務所。',
				'meta'    => array(
					'minato_address' => '東京都港区海岸 1-1-1',
					'minato_tel'     => '03-0000-0000',
					'minato_hours'   => '月–金 10:00–18:00',
					'minato_lat'     => 35.655,
					'minato_lng'     => 139.76,
					'minato_zoom'    => 15,
				),
			),
		);
		foreach ( $locations as $item ) {
			$this->upsert( 'location', $item );
		}

		$faqs = array(
			array(
				'title'   => '設計の依頼はどの段階から可能ですか？',
				'slug'    => 'when-to-start',
				'content' => '敷地が決まっていれば、企画段階からお受けしています。土地探しからのご相談も可能です。',
			),
			array(
				'title'   => '多言語のサイトも同じテーマで運用できますか？',
				'slug'    => 'multilingual',
				'content' => 'はい。Polylang / WPML に加え、プラグイン無しの ?lang= フォールバックを用意しています。',
			),
		);
		foreach ( $faqs as $item ) {
			$this->upsert( 'faq', $item );
		}

		$this->ensure_pages();
		flush_rewrite_rules();
		WP_CLI::success( 'Minato のデモコンテンツを投入しました。' );
	}

	/**
	 * 固定ページを用意する。
	 */
	private function ensure_pages() {
		$pages = array(
			'contact' => array(
				'title'    => 'Contact',
				'template' => 'page-templates/template-contact.php',
			),
		);
		foreach ( $pages as $slug => $data ) {
			$existing = get_page_by_path( $slug );
			if ( $existing ) {
				continue;
			}
			$id = wp_insert_post( array(
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_wp_page_template', $data['template'] );
			}
		}
	}

	/**
	 * スラッグ単位で投稿を作る。
	 *
	 * @param string $type 投稿タイプ。
	 * @param array  $item データ。
	 * @param string $tax  タクソノミー。
	 */
	private function upsert( $type, $item, $tax = '' ) {
		$existing = get_page_by_path( $item['slug'], OBJECT, $type );
		if ( $existing ) {
			WP_CLI::log( 'skip: ' . $item['slug'] );
			return;
		}
		$id = wp_insert_post( array(
			'post_title'   => $item['title'],
			'post_name'    => $item['slug'],
			'post_excerpt' => isset( $item['excerpt'] ) ? $item['excerpt'] : '',
			'post_content' => $item['content'],
			'post_status'  => 'publish',
			'post_type'    => $type,
		) );
		if ( is_wp_error( $id ) ) {
			WP_CLI::warning( $id->get_error_message() );
			return;
		}
		if ( ! empty( $item['meta'] ) ) {
			foreach ( $item['meta'] as $k => $v ) {
				update_post_meta( $id, $k, $v );
			}
		}
		if ( $tax && ! empty( $item['term'] ) ) {
			wp_set_object_terms( $id, $item['term'], $tax );
		}
		WP_CLI::log( 'created: ' . $item['slug'] );
	}
}

WP_CLI::add_command( 'minato', 'Minato_CLI' );
