<?php
/**
 * カスタム投稿タイプ：よくある質問（hello_faq）
 *
 * スライド FAQ-001 / よくある質問のまとめ 由来。
 * 「＋」で開閉するアコーディオン型のQ&Aまとめ記事。
 * セクション（A.情報収集 / B.出願 …）＋ Q&A を ACF の繰り返しフィールドで持つ。
 *
 * 回答者×対象（保護者向け/生徒向け × 入学検討中/在学中）の区分は
 * hello_persona タクソノミー＋ACFの区分フィールドで表現する想定（要件メモに整理）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_faq', array(
		'labels'        => array(
			'name'          => 'よくある質問',
			'singular_name' => 'よくある質問',
			'menu_name'     => 'よくある質問',
			'add_new_item'  => 'よくある質問を追加',
			'edit_item'     => 'よくある質問を編集',
			'all_items'     => 'よくある質問一覧',
		),
		'public'        => true,
		'has_archive'   => true,
		'show_in_menu'  => 'hello-magazine',
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-editor-help',
		'rewrite'       => array( 'slug' => 'magazine/faq', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	) );
} );
