<?php
/**
 * プレビュー（公開前）モードの制御。
 *
 * WordPress の「検索エンジンがインデックスしないようにする」(blog_public=0) を
 * プレビューの合図とし、
 *   - X-Robots-Tag: noindex を送出（WP標準の noindex meta に加えて二重化）
 *   - 誘導的に誤判定されやすいダミーCTA（口コミ投稿/無料トライアル）を控えめ表示
 * を行う。公開時に「設定 > 表示設定」で検索エンジンの表示を許可（blog_public=1）すれば
 * 本番モード（noindex解除＋本CTA表示）に切り替わる。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 公開前（プレビュー）かどうか。blog_public!=1 を合図にする。 */
function hello_is_preview() {
	return get_option( 'blog_public' ) !== '1';
}

/** プレビュー中は noindex ヘッダを送る（クローラに拾われても確実に除外） */
add_action( 'send_headers', function () {
	if ( hello_is_preview() && ! is_admin() ) {
		header( 'X-Robots-Tag: noindex, nofollow', true );
	}
} );
