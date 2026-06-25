<?php
/**
 * Polylang 連携。
 *
 * マガジンのカスタム投稿・タクソノミーを「翻訳対象」にする設定をコード化する
 * （管理画面の手動設定に依存せず、環境間で再現できるようにするため）。
 *
 * 多言語方針: ベース＝日本語。対応＝日本語/英語/中文/한국어/Bahasa Melayu の手動翻訳。
 * 言語そのものの追加は DB 設定（Polylang > 言語）で行う。導入手順は docs/05-translation.md 参照。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 翻訳対象にするカスタム投稿タイプ */
add_filter( 'pll_get_post_types', function ( $post_types, $is_settings ) {
	foreach ( array( 'hello_live', 'hello_interview', 'hello_faq', 'hello_ranking', 'hello_agent' ) as $pt ) {
		$post_types[ $pt ] = $pt;
	}
	return $post_types;
}, 10, 2 );

/** 翻訳対象にするタクソノミー */
add_filter( 'pll_get_taxonomies', function ( $taxonomies, $is_settings ) {
	foreach ( array(
		'hello_tag', 'hello_persona', 'hello_ranking_type',
		'hello_curriculum', 'hello_region', 'hello_price',
	) as $tx ) {
		$taxonomies[ $tx ] = $tx;
	}
	return $taxonomies;
}, 10, 2 );
