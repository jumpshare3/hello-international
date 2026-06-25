<?php
/**
 * コンテンツ系タクソノミー（記事の分類軸）。
 *
 * - hello_tag        … 横断タグ。CMSのタグ管理と同一の体系にそろえる（#費用学費 等）。
 * - hello_persona    … 話者/立場（卒ママ・現ママ・卒生徒・在校生）。
 * - hello_ranking_type … ランキングの切り口（費用・エリア・口コミ評価・カリキュラム別 等）。
 *
 * いずれも複数のカスタム投稿タイプから共有する。対象の投稿タイプは
 * 各 post-type 側の register_taxonomy_for_object_type ではなく、
 * ここで object_type 配列に列挙して一元管理する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {

	// 横断タグ（非階層）。Hello! Magazine の全コンテンツで共有。
	register_taxonomy( 'hello_tag', array(
		'hello_live', 'hello_interview', 'hello_faq', 'hello_ranking', 'hello_agent',
	), array(
		'labels'            => array(
			'name'          => 'タグ',
			'singular_name' => 'タグ',
			'menu_name'     => 'タグ（共通）',
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'mag-tag' ),
	) );

	// 話者・立場（階層あり：卒業生/在校生・保護者/生徒 などを整理しやすく）
	// ※ FAQ は対象区分(hello_faq_target)を使うため persona の対象外
	register_taxonomy( 'hello_persona', array(
		'hello_live', 'hello_interview',
	), array(
		'labels'            => array(
			'name'          => '話者・立場',
			'singular_name' => '話者・立場',
			'menu_name'     => '話者・立場',
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'persona' ),
	) );

	// ランキングの切り口（一覧の絞り込み用）
	register_taxonomy( 'hello_ranking_type', array( 'hello_ranking' ), array(
		'labels'            => array(
			'name'          => 'ランキング種別',
			'singular_name' => 'ランキング種別',
			'menu_name'     => 'ランキング種別',
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'ranking-type' ),
	) );
} );
