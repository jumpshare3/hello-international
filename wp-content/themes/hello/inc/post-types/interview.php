<?php
/**
 * カスタム投稿タイプ：インタビュー（hello_interview）
 *
 * スライド INT-001 由来。卒業生・在校生・保護者へのインタビュー記事。
 * プロフィール（学校名・学区・立場・在学/卒業年）＋ Q&A を ACF で持つ。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_interview', array(
		'labels'        => array(
			'name'          => 'インタビュー',
			'singular_name' => 'インタビュー',
			'menu_name'     => 'インタビュー',
			'add_new_item'  => 'インタビューを追加',
			'edit_item'     => 'インタビューを編集',
			'all_items'     => 'インタビュー一覧',
		),
		'public'        => true,
		'has_archive'   => true,
		'show_in_menu'  => 'hello-magazine',
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-microphone',
		'rewrite'       => array( 'slug' => 'magazine/interview', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	) );
} );
