<?php
/**
 * 管理画面に「Hello! Magazine」親メニューを用意し、
 * 各カスタム投稿タイプ（LIVE / インタビュー / FAQ / ランキング / エージェント比較）を
 * その配下にまとめる。編集者(猪口さん等)が記事種別を一望できるようにするため。
 *
 * 各 post-type 側は 'show_in_menu' => 'hello-magazine' でこの親に紐づく。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HELLO_MAGAZINE_MENU_SLUG', 'hello-magazine' );

add_action( 'admin_menu', function () {
	add_menu_page(
		'Hello! Magazine',
		'Hello! Magazine',
		'edit_posts',
		HELLO_MAGAZINE_MENU_SLUG,
		'__return_null',     // 実体ページは持たず、配下のCPTへの入り口とする
		'dashicons-welcome-learn-more',
		25
	);
}, 8 ); // CPT が show_in_menu でぶら下がる前に親を用意
