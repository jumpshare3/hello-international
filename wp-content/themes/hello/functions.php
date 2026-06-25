<?php
/**
 * Hello（SWELL 子テーマ） functions.php
 *
 * ※ 子テーマの functions.php は、親テーマ(SWELL)の functions.php より「先」に読み込まれる。
 *
 * 機能の本体は inc/ 配下にモジュール分割している（カスタム投稿・タクソノミー・ACF 等）。
 * このファイルは「読み込み」と「子テーマCSSの enqueue」だけに責務を限定する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 子テーマのルートパス／URI */
define( 'HELLO_THEME_DIR', get_stylesheet_directory() );
define( 'HELLO_THEME_URI', get_stylesheet_directory_uri() );

/**
 * inc/ 配下の PHP を読み込む。
 * 読み込み順に依存があるため、グループ順を固定する：
 *   1. taxonomies/  … タグ／話者／カリキュラム等（投稿タイプより先に定義）
 *   2. post-types/  … カスタム投稿タイプ（1ファイル = 1投稿タイプ）
 *   3. acf/         … ACF フィールドグループ定義
 *   4. inc/直下      … その他の単発設定
 */
$hello_inc = HELLO_THEME_DIR . '/inc';
foreach ( array( 'taxonomies', 'post-types', 'acf' ) as $hello_group ) {
	$dir = "{$hello_inc}/{$hello_group}";
	if ( ! is_dir( $dir ) ) {
		continue;
	}
	foreach ( glob( "{$dir}/*.php" ) ?: array() as $file ) {
		require_once $file;
	}
}
foreach ( glob( "{$hello_inc}/*.php" ) ?: array() as $file ) {
	require_once $file;
}

/**
 * 子テーマ style.css の読み込み（親テーマCSSの後ろ）。
 */
add_action( 'wp_enqueue_scripts', function () {
	$ver = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? (string) time() : wp_get_theme()->get( 'Version' );
	wp_enqueue_style(
		'hello-child',
		HELLO_THEME_URI . '/style.css',
		array(),
		$ver
	);
}, 11 );
