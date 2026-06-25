<?php
/**
 * 各カスタム投稿タイプの「設定」サブメニュー（ACFオプションページ）。
 *
 * 各CPTメニュー配下に「設定」を追加する。今後ここに各種ACF設定を載せる。
 * （ACF Pro の acf_add_options_sub_page を使用）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}
	$cpts = array(
		'hello_article'   => 'マガジン記事',
		'hello_live'      => 'YouTube LIVE',
		'hello_interview' => 'インタビュー',
		'hello_faq'       => 'よくある質問',
		'hello_ranking'   => 'ランキング',
		'hello_agent'     => 'エージェント比較',
	);
	foreach ( $cpts as $pt => $label ) {
		acf_add_options_sub_page( array(
			'page_title'  => $label . ' 設定',
			'menu_title'  => '設定',
			'parent_slug' => 'edit.php?post_type=' . $pt,
			'menu_slug'   => $pt . '-settings',
			'capability'  => 'manage_options',
			'update_button' => '保存',
		) );
	}
} );
