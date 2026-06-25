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

add_filter( 'swell_is_show_sidebar', function ( $is_show ) {
	$cpts = array( 'hello_live', 'hello_interview', 'hello_faq', 'hello_ranking', 'hello_agent' );

	if ( is_singular( $cpts ) || is_post_type_archive( $cpts ) ) {
		return false;
	}
	if ( is_page_template( 'template-magazine-top.php' ) ) {
		return false;
	}
	return $is_show;
} );
