<?php
/**
 * 学校・エージェント属性タクソノミー（比較軸）。
 *
 * - hello_curriculum … カリキュラム（IB / British / American / Australian / Canadian / モンテッソーリ / 宗教系）
 * - hello_region     … エリア（KL中心部 / モントキアラ / バンサー / PJ / ペナン / ジョホール 等）
 * - hello_price      … 価格帯（RM20,000以下 / 〜35,000 / 〜50,000 / 50,001以上）
 *
 * 当面は「エージェント比較(hello_agent)」に付与。
 * 将来、学校(school)カスタム投稿を作る場合は同タクソノミーを共有して
 * 検索ページ／ランキングの自動連携に使えるよう設計している（要件メモ参照）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {

	$shared = array( 'hello_agent', 'hello_school' ); // エージェント＋学校マスタで共有

	register_taxonomy( 'hello_curriculum', $shared, array(
		'labels'            => array( 'name' => 'カリキュラム', 'singular_name' => 'カリキュラム' ),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'curriculum' ),
	) );

	register_taxonomy( 'hello_region', $shared, array(
		'labels'            => array( 'name' => 'エリア', 'singular_name' => 'エリア' ),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'region' ),
	) );

	register_taxonomy( 'hello_price', $shared, array(
		'labels'            => array( 'name' => '価格帯', 'singular_name' => '価格帯' ),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'price-range' ),
	) );
} );
