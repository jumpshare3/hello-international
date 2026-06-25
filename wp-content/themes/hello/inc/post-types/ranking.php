<?php
/**
 * カスタム投稿タイプ：ランキング（hello_ranking）
 *
 * スライド RANK-001 由来。「一覧ページ」＋「詳細ページ」を持つ。
 * 詳細では比較条件（エリア／費用基準／学年区分）＋ 学校エントリの繰り返し
 * （順位・学校名・評価・エリア・カリキュラム・費用・特徴・写真）を ACF で持つ。
 *
 * 検索結果の自動挿入（食べログ風の学校カード自動表示）は要検討事項。
 * 当面は ACF 繰り返しによる手動エントリで運用可能な設計とする（要件メモ参照）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_ranking', array(
		'labels'        => array(
			'name'          => 'ランキング',
			'singular_name' => 'ランキング',
			'menu_name'     => 'ランキング',
			'add_new_item'  => 'ランキングを追加',
			'edit_item'     => 'ランキングを編集',
			'all_items'     => 'ランキング一覧',
		),
		'public'        => true,
		'has_archive'   => true,   // 一覧ページ
		'show_in_menu'  => 'hello-magazine',
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-chart-bar',
		'rewrite'       => array( 'slug' => 'magazine/ranking', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	) );
} );
