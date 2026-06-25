<?php
/**
 * カスタム投稿タイプ：学校マスタ（hello_school）
 *
 * ランキング等で参照する「学校」の軽量マスタ。表記揺れ防止のため、
 * ランキングの学校欄はこのマスタから選択する（公式URL・エリア・カリキュラムを自動補完）。
 * データ源は Drive「学校マスタFMT」シート（CSV取り込み想定）。
 *
 * フロント公開はしない（学校詳細ページは本体システム側の領域）。管理画面でのみ管理。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_school', array(
		'labels'             => array(
			'name'          => '学校マスタ',
			'singular_name' => '学校',
			'menu_name'     => '学校マスタ',
			'add_new_item'  => '学校を追加',
			'edit_item'     => '学校を編集',
			'all_items'     => '学校一覧',
			'search_items'  => '学校を検索',
		),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'publicly_queryable' => false,
		'exclude_from_search'=> true,
		'has_archive'        => false,
		'menu_icon'          => 'dashicons-bank',
		'menu_position'      => 32,
		'supports'           => array( 'title' ), // タイトル=学校名（日本語）
	) );
} );
