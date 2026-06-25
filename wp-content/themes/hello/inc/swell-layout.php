<?php
/**
 * SWELL レイアウト調整。
 *
 * マガジン関連ページはワイヤー通り「サイドバーなし（全幅・1カラム）」にする。
 * 対象: マガジンの各CPT（single / archive）と、マガジンTOP固定ページテンプレート。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * サイトのトップ（マガジンTOP）では、SWELL のフロント用パーツ
 * （メインビジュアル / 記事スライダー / ピックアップバナー）を出さない。
 * SWELL は 'wp'(優先度2) で mv/post_slider を有効化するため、その後で無効化する。
 */
add_action( 'wp', function () {
	if ( is_front_page() && class_exists( 'SWELL_Theme' ) ) {
		SWELL_Theme::set_use( 'mv', false );
		SWELL_Theme::set_use( 'post_slider', false );
	}
}, 5 );
add_filter( 'swell_is_show_pickup_banner', function ( $is_show ) {
	return is_front_page() ? false : $is_show;
} );
add_filter( 'swell_is_show_ttltop', function ( $is_show ) {
	return is_front_page() ? false : $is_show;
} );

add_filter( 'swell_is_show_sidebar', function ( $is_show ) {
	$cpts = function_exists( 'hello_magazine_post_types' ) ? array_keys( hello_magazine_post_types() ) : array();

	// サイトのトップ（マガジンTOP）
	if ( is_front_page() ) {
		return false;
	}
	// マガジンの各CPT（single / archive）
	if ( is_singular( $cpts ) || is_post_type_archive( $cpts ) ) {
		return false;
	}
	// FAQ まとめページ（[hello_faq] ショートコードを含む固定ページ）も1カラム
	if ( is_page() ) {
		$p = get_post();
		if ( $p && has_shortcode( (string) $p->post_content, 'hello_faq' ) ) {
			return false;
		}
	}
	return $is_show;
} );
