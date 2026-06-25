<?php
/**
 * マガジン共通フッター（ワイヤー準拠の仮デザイン）。
 *
 * CTA（口コミ投稿／無料トライアル）＋ナビ＋法務リンク＋言語＋コピーライト。
 * ナビ/CTAの遷移先は**プラットフォーム本体（別システム）**のため、現状は仮リンク(#)。
 * 実URLが決まれば hello_magazine_footer_links フィルタ等で差し替える。
 *
 * 表示は全マガジンページ（hello_main_close 経由）＋FAQまとめ固定ページ（the_content）に出す。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ナビリンク（仮）。実URL確定後に差し替え。 */
function hello_magazine_footer_links() {
	$links = array(
		'トップページ'                   => home_url( '/' ),
		'学校一覧（または検索）'         => '#',
		'口コミを見る'                   => '#',
		'質問する'                       => '#',
		'写真ギャラリー'                 => '#',
		'有料会員について（機能比較ページ）' => '#',
	);
	return apply_filters( 'hello_magazine_footer_links', $links );
}

/** 共通フッターのHTMLを返す */
function hello_get_magazine_footer() {
	$nav   = hello_magazine_footer_links();
	$legal = array(
		'利用規約'                 => '#',
		'プライバシーポリシー'     => '#',
		'特定商取引法に基づく表記' => '#',
		'投稿ガイドライン'         => '#',
	);
	$langs = array( '🇯🇵 日本語', '🇬🇧 English', '🇨🇳 中文（简体）', '🇰🇷 한국어', '🇲🇾 Bahasa Melayu' );

	ob_start();
	?>
	<footer class="hello-mfooter" role="contentinfo">
		<!-- CTA: 口コミ投稿 -->
		<div class="hello-mfooter__cta">
			<p class="hello-mfooter__cta-lead">「あなたの経験が、これからの誰かを助ける」<br>
				インター選びで悩むママたちに、リアルな声を届けませんか？</p>
			<a class="hello-mfooter__btn" href="#">口コミ投稿はコチラ &gt;&gt;</a>
		</div>

		<!-- CTA: 無料トライアル -->
		<div class="hello-mfooter__cta -trial">
			<p>💳 0円で、口コミも質問もぜんぶ見れる！<br>
				👉 まずは <a href="#">無料トライアル</a> から</p>
		</div>

		<p class="hello-mfooter__ig"><a href="#">📷 Instagramで最新情報チェック &gt;&gt;</a></p>

		<nav class="hello-mfooter__nav" aria-label="フッターナビ">
			<?php foreach ( $nav as $label => $url ) : ?>
				<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>

		<p class="hello-mfooter__legal">
			<?php
			$parts = array();
			foreach ( $legal as $label => $url ) {
				$parts[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
			}
			echo '｜ ' . implode( ' ｜ ', $parts ) . ' ｜'; // phpcs:ignore
			?>
		</p>
		<p class="hello-mfooter__contact"><a href="#">お問い合わせ</a></p>

		<p class="hello-mfooter__lang">🌐 言語：
			<?php
			if ( function_exists( 'pll_the_languages' ) ) {
				$pll = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
				if ( ! empty( $pll ) ) {
					foreach ( $pll as $l ) {
						printf( '[ <a href="%s">%s</a> ] ', esc_url( $l['url'] ), esc_html( $l['name'] ) );
					}
				}
			}
			if ( empty( $pll ) ) {
				foreach ( $langs as $l ) {
					echo '[ ' . esc_html( $l ) . ' ] ';
				}
			}
			?>
		</p>

		<p class="hello-mfooter__copy">© 2025 Helo!｜Malaysia International School Reviews &amp; Q&amp;A Platform. All rights reserved.</p>
	</footer>
	<?php
	return ob_get_clean();
}

/**
 * 別CTAセット「知って得する Helo! マガジン」（おすすめ記事ブロック）。
 * ワイヤーでは インタビュー詳細 と よくある質問まとめ のコンテンツ下に出る。
 * $faq=true で「みんなが疑問に思う インターFAQまとめました」のリードを追加。
 */
function hello_get_recommend_cta( $faq = false ) {
	$picks = array(
		array( '「マレーシアで人気のIB校とは？」', 'IBってなに？学費や進路への影響を解説！' ),
		array( '「学費だけじゃない！保護者が見てるポイント5選」', '安心感・言語環境など、現地ママのリアル口コミを分析' ),
	);
	ob_start();
	?>
	<aside class="hello-reco" aria-label="おすすめ記事">
		<?php if ( $faq ) : ?>
			<p class="hello-reco__lead">みんなが疑問に思う<br>インターFAQまとめました</p>
		<?php endif; ?>
		<p class="hello-reco__ttl">📖 知って得する Helo! マガジン</p>
		<div class="hello-reco__cards">
			<?php foreach ( $picks as $p ) : ?>
				<a class="hello-reco__card" href="#">
					<span class="hello-reco__card-ttl"><?php echo esc_html( $p[0] ); ?></span>
					<span class="hello-reco__card-desc"><?php echo esc_html( $p[1] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</aside>
	<?php
	return ob_get_clean();
}

/** FAQまとめ固定ページ（[hello_faq] を含む page）の本文末尾に 別CTA＋共通フッター を付ける */
add_filter( 'the_content', function ( $content ) {
	if ( is_page() && in_the_loop() && is_main_query() ) {
		$p = get_post();
		if ( $p && has_shortcode( (string) $p->post_content, 'hello_faq' ) ) {
			$content .= hello_get_recommend_cta( true ) . hello_get_magazine_footer();
		}
	}
	return $content;
} );
