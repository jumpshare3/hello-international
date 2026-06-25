<?php
/**
 * カスタム投稿タイプ：YouTube LIVE（hello_live）
 *
 * スライド ⑲/LIVE-001 由来。会員限定の YouTube LIVE アーカイブ記事。
 * 目次（チャプター）／要約Q&A／動画タイムスタンプ等を ACF で持つ。
 * （該当秒数のサムネ表示・その秒数から再生 は要検討事項 → 要件メモ参照）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_live', array(
		'labels'        => array(
			'name'               => 'YouTube LIVE',
			'singular_name'      => 'YouTube LIVE',
			'menu_name'          => 'YouTube LIVE',
			'add_new_item'       => 'YouTube LIVE を追加',
			'edit_item'          => 'YouTube LIVE を編集',
			'all_items'          => 'YouTube LIVE 一覧',
		),
		'public'        => true,
		'has_archive'   => true,
		'show_in_menu'  => 'hello-magazine',
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-video-alt3',
		'rewrite'       => array( 'slug' => 'magazine/live', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	) );
} );
