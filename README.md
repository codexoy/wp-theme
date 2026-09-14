# Minato

建築スタジオ向け WordPress オリジナルテーマ（親テーマなし・フルスクラッチ）。

プロジェクト・サービス・拠点・FAQ などをカスタム投稿で運用するコーポレートサイトとして設計・実装したテーマです。

| 項目 | 内容 |
| --- | --- |
| 担当 | 情報設計 / テーマ実装 / フロント（HTML・CSS・JS） / SEO 内部 |
| 使用技術 | PHP 8.1+ / WordPress 6.4+ / ACF Pro（未導入時は native meta） / Polylang・WPML / Google Maps JavaScript API / WP All Import / WP-CLI |
| 開発期間 | 約 1〜2 週間 |

トップの見た目は `screenshot.png` を参照。

## 主な機能

- **フルスクラッチ**: 親テーマなし。`style.css` のヘッダーと `functions.php` の分割読み込み
- **CPT + ACF**: `inc/cpt.php`（project / service / location / team / faq）と `inc/acf.php`（ローカルフィールドグループ。ACF が無い環境ではメタボックスへフォールバック）
- **モバイルファースト**: `assets/css/main.css`（880px 未満はハンバーガー）
- **Schema.org JSON-LD**: `inc/schema.php`（Organization / WebSite / Breadcrumb / CreativeWork / LocalBusiness / FAQPage / Article）
- **日・英・中**: `inc/i18n.php`（Polylang → WPML → `?lang=` の順）
- **Google Maps**: `inc/maps.php` + `assets/js/maps.js`（表示領域に入ってから API を読む。キーはカスタマイザーのみ）
- **CSV 一括**: `data/*.csv`。フィールド名を CSV ヘッダーと一致。`pmxi_saved_post` で型を正規化
- **速度**: `inc/performance.php` と defer / preconnect / 画像の fetchpriority
- **SEO**: `inc/seo.php`（canonical / OGP / Twitter / hreflang）。Yoast / Rank Math がある場合は出さない
- **サイトマップ**: WordPress 5.5 以降のコアサイトマップ（公開 CPT は自動掲載）

## ディレクトリ

```
inc/                 関心ごとの PHP
template-parts/      カード・FAQ・フォールバックナビ
page-templates/      お問い合わせ
assets/css|js        フロント資産
data/                WP All Import 用サンプル CSV
languages/           翻訳ファイル
```

テンプレート階層の要点:

- `front-page.php` — ヒーロー + 注目プロジェクト + FAQ
- `single-project.php` / `archive-project.php`
- `single-location.php` / `archive-location.php` — 地図
- `page-templates/template-contact.php` — 地図 + `admin-post.php` のフォーム

## 導入

1. `wp-content/themes/minato` に配置して有効化
2. パーマリンクを「投稿名」にして保存（CPT のリライト用）
3. （任意）ACF Pro、Polylang または WPML
4. カスタマイザー「Minato スタジオ情報」に Maps API キー
5. 初期コンテンツの投入:

```bash
wp minato seed
```

WP All Import の場合は `data/projects-sample.csv` などを使い、カスタムフィールドを `minato_client` など同名でマップする。

## 設計方針

- テンプレートにメタ取得を散らさず、`minato_field()` で ACF / post meta を吸収する
- 公開 API（REST の `show_in_rest`）と CLI から同じモデルを触れるようにする
- プラグインが強い領域（SEO・多言語）は検出して二重出力しない
- 地図スクリプトは拠点・問い合わせにだけ読む
