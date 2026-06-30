<?php
/**
 * マガジンのフィード（RSS）拡張。
 *
 * - メインフィード `/feed/` をマガジン記事種別（個別Q&Aを除く）の横断フィードにする
 * - 各アイテムに **アイキャッチ画像のフルパス** を付与
 *     ・<media:content url="フルパス"> / <media:thumbnail url="フルパス">（Media RSS）
 *     ・本文先頭にも画像を差し込み（一般的なリーダー表示用）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** メインフィードをマガジン横断にする（個別Q&A=hello_faq は除外） */
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() || ! $q->is_feed() || ! $q->is_main_query() ) {
		return;
	}
	if ( function_exists( 'hello_magazine_post_types' ) ) {
		$types = array_values( array_diff( array_keys( hello_magazine_post_types() ), array( 'hello_faq' ) ) );
		$q->set( 'post_type', $types );
	}
} );

/**
 * フィードアイテムのアイキャッチ画像を返す。
 * 実アイキャッチがあればそのフルURL、無ければ種別ごとの既定画像（テーマ同梱）。
 * 返り値: array( url, mime )
 */
function hello_feed_item_image( $id ) {
	if ( has_post_thumbnail( $id ) ) {
		$url = get_the_post_thumbnail_url( $id, 'full' );
		if ( $url ) {
			return array( 'url' => $url, 'mime' => get_post_mime_type( get_post_thumbnail_id( $id ) ) ?: 'image/jpeg' );
		}
	}
	$map  = array(
		'hello_article' => 'article', 'hello_live' => 'live', 'hello_interview' => 'interview',
		'hello_faq' => 'faq', 'hello_ranking' => 'ranking', 'hello_agent' => 'agent',
	);
	$slug = $map[ get_post_type( $id ) ] ?? 'default';
	$rel  = '/assets/img/eyecatch-' . $slug . '.png';
	if ( ! file_exists( HELLO_THEME_DIR . $rel ) ) {
		$rel = '/assets/img/eyecatch-default.png';
	}
	return array( 'url' => HELLO_THEME_URI . $rel, 'mime' => 'image/png' );
}

/** Media RSS 名前空間を宣言 */
add_action( 'rss2_ns', function () {
	echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n";
} );

/** 各アイテムにアイキャッチ画像のフルパスを付与（実画像 or 種別既定） */
add_action( 'rss2_item', function () {
	$img = hello_feed_item_image( get_the_ID() );
	echo "\t<media:content url=\"" . esc_url( $img['url'] ) . "\" medium=\"image\" type=\"" . esc_attr( $img['mime'] ) . "\" />\n";
	echo "\t<media:thumbnail url=\"" . esc_url( $img['url'] ) . "\" />\n";
} );

/** 本文フィードの先頭にアイキャッチを差し込む（リーダー表示用） */
add_filter( 'the_content_feed', function ( $content ) {
	$img = hello_feed_item_image( get_the_ID() );
	return '<p><img src="' . esc_url( $img['url'] ) . '" style="max-width:100%;height:auto;" /></p>' . $content;
} );
add_filter( 'the_excerpt_rss', function ( $content ) {
	$img = hello_feed_item_image( get_the_ID() );
	return '<p><img src="' . esc_url( $img['url'] ) . '" style="max-width:100%;height:auto;" /></p>' . $content;
} );
