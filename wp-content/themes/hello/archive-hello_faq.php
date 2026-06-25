<?php
/**
 * archive-hello_faq.php — よくある質問 ハブ（まとめ一覧）
 *
 * 個別Q&A（hello_faq）はプール。利用者向けの導線は「まとめ」。
 * ここでは [hello_faq] ショートコードを含むまとめ固定ページをカード一覧で見せ、
 * 各まとめページ（対象区分①〜④）への導線にする。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
hello_main_open( 'hello-faq-hub' );
hello_lang_switcher();
?>
<header>
	<h1 class="hello-mag__ttl">よくある質問</h1>
	<p>マレーシア教育移住・インター選びのよくある疑問を、対象別にまとめています。</p>
</header>
<?php hello_magazine_tabs( 'hello_faq' ); ?>

<?php
// まとめ固定ページ（[hello_faq] を含む page）を収集
$pages  = get_posts( array(
	'post_type'      => 'page',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
) );
$matome = array_filter( $pages, function ( $p ) {
	return has_shortcode( (string) $p->post_content, 'hello_faq' );
} );
?>

<?php if ( $matome ) : ?>
	<div class="hello-grid">
		<?php foreach ( $matome as $p ) : ?>
			<a class="hello-acard" href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
				<span class="hello-acard__thumb -ph" data-pt="hello_faq">❓</span>
				<span class="hello-acard__body">
					<span class="hello-acard__type -hello_faq">❓ よくある質問まとめ</span>
					<span class="hello-acard__ttl"><?php echo esc_html( get_the_title( $p->ID ) ); ?></span>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<p>まとめがまだありません。</p>
<?php endif; ?>

<?php
hello_main_close();
get_footer();
