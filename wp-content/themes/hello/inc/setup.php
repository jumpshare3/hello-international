<?php
/**
 * テーマ共通セットアップ ＆ ACF 連携設定。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF のフィールドグループを「acf-json」でバージョン管理する。
 *
 * - 管理画面(GUI)で作成・編集したフィールドグループは acf-json/ に JSON 自動保存される
 *   → リポジトリにコミットすれば他環境へ同期できる（PHPでの手書き登録と併用可）。
 * - inc/acf/*.php の acf_add_local_field_group() による初期スキャフォルドは
 *   コードで明示登録したもの。GUIで作り直す場合は acf-json 運用に寄せていく。
 */
add_filter( 'acf/settings/save_json', function () {
	return HELLO_THEME_DIR . '/acf-json';
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = HELLO_THEME_DIR . '/acf-json';
	return $paths;
} );

/**
 * ACF Pro ライセンスの自動アクティベート。
 *
 * 実運用では wp-config.php / 環境変数に定義した定数 ACF_PRO_LICENSE を使う想定。
 * ローカル開発では docker-compose の WORDPRESS_CONFIG_EXTRA で定義している。
 * （ここではコードにライセンスキーを直書きしない。）
 */
