<?php
/**
 * よくある質問（hello_faq）用タクソノミー。
 *
 * Q&Aを「1問＝1投稿」のプールとして持ち、ショートコード [hello_faq] で
 * 対象区分・セクションごとに集約（アコーディオン表示）する設計。
 *
 * - hello_faq_target  … 対象区分（①保護者×検討中 / ②生徒×検討中 / ③保護者×在学中 / ④生徒×在学中）
 * - hello_faq_section … セクション（A.情報収集・学校選び 等。まとめ内の見出しグループ）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {

	register_taxonomy( 'hello_faq_target', array( 'hello_faq' ), array(
		'labels'            => array( 'name' => '対象区分', 'singular_name' => '対象区分', 'menu_name' => '対象区分' ),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'faq-target' ),
	) );

	register_taxonomy( 'hello_faq_section', array( 'hello_faq' ), array(
		'labels'            => array( 'name' => 'セクション', 'singular_name' => 'セクション', 'menu_name' => 'セクション' ),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'faq-section' ),
	) );
} );
