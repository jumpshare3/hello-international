<?php
/**
 * 各マガジンページのフッターに、もとになったワイヤーの「ファイル名＋該当ページへのリンク」を表示する。
 * 社内チェッカーがワイヤーと突き合わせやすくするためのQA補助（[[07-wireframe-checklist]] 連動）。
 *
 * 公開前に無効化したい場合は wp-config.php に次を定義：
 *   define('HELLO_SHOW_WIREFRAME_REF', false);
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ワイヤー（Googleスライド）ファイル名とベースURL */
define( 'HELLO_WIREFRAME_FILE', 'ワイヤーHello!_ver0.6FIX_エージェンツコンテンツ追加' );
define( 'HELLO_WIREFRAME_BASE', 'https://docs.google.com/presentation/d/1RzRoZPF7j8WN81oAVs9OvPqOaKXEKvTm00isQUKetmk/edit' );

/**
 * 現在のページに対応するワイヤー参照を返す。
 * anchor はスライドの slide id（分かっているものだけ設定。未設定はデッキ先頭リンク）。
 * ※ 各ページの正確なスライドURLが分かれば anchor を追記すると個別ページに直接飛べる。
 */
function hello_current_wireframe_ref() {
	$map_single = array(
		'hello_article'   => array( 'label' => 'マガジン記事', 'anchor' => '' ),
		'hello_live'      => array( 'label' => 'YouTube LIVE 記事', 'anchor' => '' ),
		'hello_interview' => array( 'label' => 'インタビュー記事', 'anchor' => '' ),
		'hello_faq'       => array( 'label' => 'よくある質問', 'anchor' => '' ),
		'hello_ranking'   => array( 'label' => 'ランキング 詳細', 'anchor' => '' ),
		'hello_agent'     => array( 'label' => 'エージェント比較 詳細', 'anchor' => '' ),
	);
	$map_archive = array(
		'hello_article'   => array( 'label' => 'マガジン記事 一覧', 'anchor' => '' ),
		'hello_live'      => array( 'label' => 'YouTube LIVE 一覧', 'anchor' => '' ),
		'hello_interview' => array( 'label' => 'インタビュー 一覧', 'anchor' => '' ),
		'hello_faq'       => array( 'label' => 'よくある質問 一覧', 'anchor' => '' ),
		'hello_ranking'   => array( 'label' => 'ランキング 一覧', 'anchor' => '' ),
		'hello_agent'     => array( 'label' => 'エージェント比較 一覧', 'anchor' => '' ),
	);

	if ( is_front_page() ) {
		return array( 'label' => 'ハローマガジンTOP（⑲）', 'anchor' => 'g36db62e5aa8_0_104' );
	}
	// FAQ まとめページ（[hello_faq] ショートコードを含む固定ページ）
	if ( is_page() ) {
		$p = get_post();
		if ( $p && has_shortcode( (string) $p->post_content, 'hello_faq' ) ) {
			return array( 'label' => 'よくある質問 まとめ', 'anchor' => '' );
		}
	}
	foreach ( $map_single as $pt => $ref ) {
		if ( is_singular( $pt ) ) {
			return $ref;
		}
	}
	foreach ( $map_archive as $pt => $ref ) {
		if ( is_post_type_archive( $pt ) ) {
			return $ref;
		}
	}
	return null; // マガジン外のページには出さない
}

/** フッターにワイヤー参照バッジを出力 */
add_action( 'wp_footer', function () {
	if ( defined( 'HELLO_SHOW_WIREFRAME_REF' ) && ! HELLO_SHOW_WIREFRAME_REF ) {
		return;
	}
	$ref = hello_current_wireframe_ref();
	if ( ! $ref ) {
		return;
	}
	$url = HELLO_WIREFRAME_BASE . ( $ref['anchor'] ? '#slide=id.' . $ref['anchor'] : '' );
	?>
	<div class="hello-wfref" role="note">
		<span class="hello-wfref__tag">📐 ワイヤー</span>
		<span class="hello-wfref__file"><?php echo esc_html( HELLO_WIREFRAME_FILE ); ?></span>
		<span class="hello-wfref__sep">／</span>
		<a class="hello-wfref__link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
			<?php echo esc_html( $ref['label'] ); ?> ↗
		</a>
	</div>
	<?php
} );
