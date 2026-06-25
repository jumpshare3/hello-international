<?php
/**
 * カスタム投稿タイプ：エージェント比較（hello_agent）
 *
 * スライド「エージェント比較」由来。「一覧ページ」＋「詳細ページ」を持つ。
 * 教育移住エージェントの比較情報：
 *   - タイプタグ（サービススタンス／強み／ポジション）
 *   - 得意なサポート分野（項目ごとに★1〜5、合計20まで）
 *   - 体制タグ（日本語スタッフ在籍 等）
 *   - 価格帯／カリキュラム／エリア（タクソノミー hello_price/curriculum/region）
 *   - 実績・スタンス・おすすめ家庭・費用・難題事例・基本情報・公式URL
 * これらは ACF（繰り返し／チェックボックス等）で構造化する。
 *
 * 将来：学校ページの口コミと紐づけ、Google口コミ風の「口コミ＋返信」を実装予定
 * （初期は手動登録で可）→ 要件メモ参照。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_agent', array(
		'labels'        => array(
			'name'          => 'エージェント比較',
			'singular_name' => 'エージェント',
			'menu_name'     => 'エージェント比較',
			'add_new_item'  => 'エージェントを追加',
			'edit_item'     => 'エージェントを編集',
			'all_items'     => 'エージェント一覧',
		),
		'public'        => true,
		'has_archive'   => true,   // 一覧ページ
		'show_in_menu'  => 'hello-magazine',
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-groups',
		'rewrite'       => array( 'slug' => 'magazine/agent', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	) );
} );
