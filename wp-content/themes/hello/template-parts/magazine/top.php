<?php
/**
 * マガジンTOP 本体（共通パーツ）。
 * フロントページ(front-page.php)と固定ページテンプレート(template-magazine-top.php)の両方から使う。
 *   - 種別タブ（?mag_type）＋ タグ絞り込み（?mag_tag, hello_tag）
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$types    = hello_magazine_post_types();
$cur_type = isset( $_GET['mag_type'] ) ? sanitize_key( $_GET['mag_type'] ) : 'all';
$cur_tag  = isset( $_GET['mag_tag'] ) ? sanitize_title( wp_unslash( $_GET['mag_tag'] ) ) : '';
// フロントページなら home、固定ページなら自身のURL
$base_url = ( is_front_page() ) ? home_url( '/' ) : get_permalink();

if ( 'all' !== $cur_type && ! isset( $types[ $cur_type ] ) ) {
	$cur_type = 'all';
}
// 個別Q&A(hello_faq)は「まとめ」経由で見せるため、TOPの記事フィードからは除外。
$feed_types = array_values( array_diff( array_keys( $types ), array( 'hello_faq' ) ) );
$query_pt   = 'all' === $cur_type ? $feed_types : array( $cur_type );

$args = array(
	'post_type'      => $query_pt,
	'post_status'    => 'publish',
	'posts_per_page' => 10,
	'paged'          => max( 1, (int) get_query_var( 'paged' ) ),
);
if ( $cur_tag ) {
	$args['tax_query'] = array(
		array( 'taxonomy' => 'hello_tag', 'field' => 'slug', 'terms' => $cur_tag ),
	);
}
$q = new WP_Query( $args );

hello_main_open( 'hello-top' );
hello_lang_switcher();
?>
<header class="hello-hero">
	<h1 class="hello-hero__ttl">Hello! Magazine</h1>
	<p class="hello-hero__lead">マレーシア教育移住・インターナショナルスクール。<br>入学前から在学中まで役立つ、リアルな体験談・YouTube LIVE・Q&A・学校ランキング。</p>
</header>

<?php if ( 'all' === $cur_type && ! $cur_tag ) :
	$featured = new WP_Query( array(
		'post_type'      => $feed_types,
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	) );
	if ( $featured->have_posts() ) : ?>
		<section class="hello-sec">
			<h2 class="hello-sec__ttl">注目の記事</h2>
			<div class="hello-grid">
				<?php while ( $featured->have_posts() ) : $featured->the_post(); hello_magazine_card(); endwhile; ?>
			</div>
		</section>
		<?php wp_reset_postdata();
	endif;
endif; ?>

<h2 class="hello-sec__ttl">記事一覧</h2>
<nav class="hello-top__tabs" aria-label="記事種別">
	<?php
	$tab_url = $cur_tag ? add_query_arg( 'mag_tag', $cur_tag, $base_url ) : $base_url;
	printf( '<a class="hello-top__tab%s" href="%s">すべて</a>',
		'all' === $cur_type ? ' is-current' : '',
		esc_url( $tab_url ) );
	foreach ( $types as $pt => $meta ) {
		// よくある質問は「まとめ一覧（ハブ）」へ誘導する
		if ( 'hello_faq' === $pt ) {
			printf( '<a class="hello-top__tab" href="%s">%s %s</a>',
				esc_url( get_post_type_archive_link( 'hello_faq' ) ),
				esc_html( $meta['icon'] ), esc_html( $meta['label'] ) );
			continue;
		}
		printf( '<a class="hello-top__tab%s" href="%s">%s %s</a>',
			$cur_type === $pt ? ' is-current' : '',
			esc_url( add_query_arg( 'mag_type', $pt, $tab_url ) ),
			esc_html( $meta['icon'] ), esc_html( $meta['label'] ) );
	}
	?>
</nav>

<?php $tags = get_terms( array( 'taxonomy' => 'hello_tag', 'hide_empty' => false ) ); ?>
<?php if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
	<div class="hello-tags">
		<?php
		$type_keep = 'all' !== $cur_type ? add_query_arg( 'mag_type', $cur_type, $base_url ) : $base_url;
		if ( $cur_tag ) {
			printf( '<a class="hello-tags__item" href="%s">✕ タグ解除</a>', esc_url( $type_keep ) );
		}
		foreach ( $tags as $t ) {
			$active = $cur_tag === $t->slug ? ' style="background:var(--hello-accent);color:#fff;"' : '';
			printf( '<a class="hello-tags__item" href="%s"%s>#%s</a>',
				esc_url( add_query_arg( 'mag_tag', $t->slug, $type_keep ) ),
				$active,
				esc_html( $t->name ) );
		}
		?>
	</div>
<?php endif; ?>

<?php if ( $q->have_posts() ) : ?>
	<div class="hello-grid">
		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
			<?php hello_magazine_card(); ?>
		<?php endwhile; ?>
	</div>
<?php else : ?>
	<p>該当する記事がありません。</p>
<?php endif; ?>

<?php
wp_reset_postdata();
hello_main_close();
