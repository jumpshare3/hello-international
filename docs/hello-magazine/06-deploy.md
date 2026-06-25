# 06. 本番デプロイ記録

対象: **magazine.hello-internationalschool.com**（ConoHa WING / www1188 / PHP 8.3 / Apache）
実施日: 2026-06-25 ／ デプロイ元: `main`

## 本番に実施したセットアップ（コード外の手作業）

リポジトリにはテーマコードのみが入るため、以下は本番側で別途実施した（記録）。

1. **プラグイン設置・有効化**（本番 `wp-content/plugins/`、いずれも .gitignore 対象）
   - Advanced Custom Fields PRO 6.8.4（ライセンスでDL）
   - Polylang 3.8.5（wordpress.org）
   - ※ wp-cli が本番に無いため、`php + wp-load` の `activate_plugins()` で有効化。
2. **ACF Pro ライセンス**: `wp-config.php` に `define('ACF_PRO_LICENSE', ...)` を追加。
3. **テーマ配備**: `rsync -avz --delete`（`--dry-run`で差分確認後に本実行）
   `./wp-content/themes/hello/` → 本番 `.../themes/hello/`。
4. **パーマリンク**: `/%postname%/` に設定。
5. **`.htaccess` 作成**: 本番に存在しなかったため、同サーバーの稼働WPと同形式の
   WordPress 標準リライトブロックを設置（これが無いと `/magazine/...` が 404）。
6. **Polylang 言語**: ja(既定)/en/zh/ko/ms を追加。
   ※ 本番の plain CLI では `PLL()` 未初期化のため、`new WP_Syntex\Polylang\Options\Options()`
   ＋ `PLL_Admin_Model` で追加（言語が1つも無い初期状態の注意点）。
7. **初期ターム＋サンプル投入**: `inc/seed-terms.php` / `tools/seed-sample.php` を実行。

## 検証
- `/magazine/`（TOP）, 各CPTの single / archive いずれも **HTTP 200**・致命的エラーなし。
- TOP は ヒーロー＋注目の記事＋種別タブ＋タグ絞り込み＋記事一覧 が表示。

## プレビュー（公開前）モード
- WordPress「設定 > 表示設定 > 検索エンジンでの表示」を**インデックスしない**（`blog_public=0`）に
  しておくと、テーマが **noindex（meta＋X-Robots-Tag）** を出し、誘導的に誤判定されやすい
  **ダミーCTA（口コミ投稿/無料トライアル）を「準備中」表示に抑制**する（`inc/noindex.php`）。
- Google「危険なサイト」警告（Safe Browsing）対策：本番はBasic認証＋noindexでクロール対象外。
  既存フラグの解除は **Google Search Console で審査リクエスト**が必要（所有者対応）。
- **公開時**：Basic認証を外し、`blog_public=1`（検索エンジンに表示）に戻すと本CTA表示＋indexable。

## 申し送り（公開前に対応）
- 投入済みは**サンプル（ダミー）コンテンツ**。LIVE のYouTube URLもダミー。
  公開前に実コンテンツへ差し替え／削除すること。
- サイト**フロントページ**は既定のまま（マガジンは `/magazine/`）。導線は別途設計。
- Polylang のタームへの言語割当は管理画面（言語＞翻訳）で必要に応じて整える。
- 次回以降のテーマ更新は `main` を `rsync`（`--dry-run`→承認→本実行）で反映。
