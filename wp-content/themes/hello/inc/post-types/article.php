<?php
/**
 * カスタム投稿タイプ：マガジン記事（hello_article）＝通常記事
 *
 * コラム/解説などの通常記事。共通フッターの「知って得する Helo! マガジン」CTA に
 * 表示する記事の供給元になる（各投稿の ACF で表示記事を指定／未指定なら最新を表示）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'hello_article', array(
		'labels'        => array(
			'name'          => 'マガジン記事',
			'singular_name' => 'マガジン記事',
			'menu_name'     => 'マガジン記事',
			'add_new_item'  => 'マガジン記事を追加',
			'edit_item'     => 'マガジン記事を編集',
			'all_items'     => 'マガジン記事一覧',
		),
		'public'        => true,
		'has_archive'   => true,
		'show_in_menu'  => true,   // 大メニュー（トップレベル）
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-edit',
		'menu_position' => 26,
		'rewrite'       => array( 'slug' => 'article', 'with_front' => false ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
	) );
} );
