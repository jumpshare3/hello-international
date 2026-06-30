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

/** Media RSS 名前空間を宣言 */
add_action( 'rss2_ns', function () {
	echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n";
} );

/** 各アイテムにアイキャッチ画像のフルパスを付与 */
add_action( 'rss2_item', function () {
	$id = get_the_ID();
	if ( ! has_post_thumbnail( $id ) ) {
		return;
	}
	$url = get_the_post_thumbnail_url( $id, 'full' );
	if ( ! $url ) {
		return;
	}
	$tid  = get_post_thumbnail_id( $id );
	$mime = get_post_mime_type( $tid ) ?: 'image/jpeg';
	echo "\t<media:content url=\"" . esc_url( $url ) . "\" medium=\"image\" type=\"" . esc_attr( $mime ) . "\" />\n";
	echo "\t<media:thumbnail url=\"" . esc_url( $url ) . "\" />\n";
} );

/** 本文フィードの先頭にアイキャッチを差し込む（リーダー表示用） */
add_filter( 'the_content_feed', function ( $content ) {
	$id = get_the_ID();
	if ( has_post_thumbnail( $id ) ) {
		$img     = get_the_post_thumbnail( $id, 'large', array( 'style' => 'max-width:100%;height:auto;' ) );
		$content = $img . $content;
	}
	return $content;
} );
add_filter( 'the_excerpt_rss', function ( $content ) {
	$id = get_the_ID();
	if ( has_post_thumbnail( $id ) ) {
		$url     = get_the_post_thumbnail_url( $id, 'large' );
		$content = '<p><img src="' . esc_url( $url ) . '" style="max-width:100%;height:auto;" /></p>' . $content;
	}
	return $content;
} );
