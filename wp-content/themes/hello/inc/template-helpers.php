<?php
/**
 * テンプレート用の補助関数群。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * マガジンの記事種別（CPT）定義。TOPのタブ・フィルタで使う。
 */
function hello_magazine_post_types() {
	return array(
		'hello_live'      => array( 'label' => 'YouTube LIVE', 'icon' => '🔴' ),
		'hello_interview' => array( 'label' => '体験談・インタビュー', 'icon' => '🎤' ),
		'hello_faq'       => array( 'label' => 'よくある質問', 'icon' => '❓' ),
		'hello_ranking'   => array( 'label' => '学校ランキング', 'icon' => '🏆' ),
		'hello_agent'     => array( 'label' => 'エージェント比較', 'icon' => '🤝' ),
	);
}

/**
 * 星評価のHTML（0〜5、0.5刻み対応）。
 */
function hello_stars( $value, $max = 5 ) {
	$value = (float) $value;
	$full  = (int) floor( $value );
	$half  = ( $value - $full ) >= 0.5;
	$out   = '<span class="hello-stars" aria-label="' . esc_attr( $value . '/' . $max ) . '">';
	for ( $i = 1; $i <= $max; $i++ ) {
		if ( $i <= $full ) {
			$out .= '★';
		} elseif ( $half && $i === $full + 1 ) {
			$out .= '⯨';
		} else {
			$out .= '☆';
		}
	}
	$out .= '</span>';
	return $out;
}

/**
 * "2:05" や "125" を秒数に変換（YouTube start パラメータ用）。
 */
function hello_timestamp_to_seconds( $str ) {
	$str = trim( (string) $str );
	if ( $str === '' ) {
		return 0;
	}
	if ( strpos( $str, ':' ) !== false ) {
		$parts = array_reverse( array_map( 'intval', explode( ':', $str ) ) );
		$sec   = 0;
		foreach ( $parts as $i => $p ) {
			$sec += $p * pow( 60, $i );
		}
		return $sec;
	}
	return (int) $str;
}

/**
 * YouTube URL / ID から動画IDを取り出す。
 */
function hello_youtube_id( $url ) {
	if ( preg_match( '%(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{11})%', (string) $url, $m ) ) {
		return $m[1];
	}
	if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', (string) $url ) ) {
		return $url;
	}
	return '';
}

/**
 * 言語スイッチャ（Polylang）。未導入時は何も出さない。
 */
function hello_lang_switcher() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}
	$langs = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
	if ( empty( $langs ) ) {
		return;
	}
	echo '<nav class="hello-langs" aria-label="言語切替">';
	foreach ( $langs as $l ) {
		$cls = ! empty( $l['current_lang'] ) ? ' is-current' : '';
		printf(
			'<a class="hello-langs__item%1$s" href="%2$s" lang="%3$s">%4$s</a>',
			esc_attr( $cls ),
			esc_url( $l['url'] ),
			esc_attr( $l['slug'] ),
			esc_html( $l['name'] )
		);
	}
	echo '</nav>';
}

/**
 * 記事のタグ（hello_tag）をピル表示。
 */
function hello_the_tags( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$terms   = get_the_terms( $post_id, 'hello_tag' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}
	echo '<div class="hello-tags">';
	foreach ( $terms as $t ) {
		printf( '<span class="hello-tags__item">#%s</span>', esc_html( $t->name ) );
	}
	echo '</div>';
}

/**
 * マガジンTOP（タグ→種別フィルタ）の固定ページURL。slug 'magazine' を既定とする。
 */
function hello_magazine_top_url() {
	$page = get_page_by_path( 'magazine' );
	return $page ? get_permalink( $page ) : home_url( '/magazine/' );
}

/**
 * 種別タブ（すべて＋各CPT）。$current は 'all' か CPT slug。
 */
function hello_magazine_tabs( $current = 'all' ) {
	echo '<nav class="hello-top__tabs" aria-label="記事種別">';
	$all_cls = $current === 'all' ? ' is-current' : '';
	printf( '<a class="hello-top__tab%s" href="%s">すべて</a>', esc_attr( $all_cls ), esc_url( hello_magazine_top_url() ) );
	foreach ( hello_magazine_post_types() as $pt => $meta ) {
		$cls  = $current === $pt ? ' is-current' : '';
		$link = get_post_type_archive_link( $pt );
		if ( ! $link ) {
			continue;
		}
		printf( '<a class="hello-top__tab%s" href="%s">%s %s</a>', esc_attr( $cls ), esc_url( $link ), esc_html( $meta['icon'] ), esc_html( $meta['label'] ) );
	}
	echo '</nav>';
}

/**
 * 一覧用カード（ループ内で呼ぶ）。
 */
function hello_magazine_card( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$types   = hello_magazine_post_types();
	$pt      = get_post_type( $post_id );
	$label   = isset( $types[ $pt ] ) ? $types[ $pt ]['icon'] . ' ' . $types[ $pt ]['label'] : get_post_type( $post_id );
	$thumb   = get_the_post_thumbnail_url( $post_id, 'medium' );
	?>
	<a class="hello-acard" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
		<?php if ( $thumb ) : ?>
			<img class="hello-acard__thumb" src="<?php echo esc_url( $thumb ); ?>" alt="" loading="lazy">
		<?php else : ?>
			<span class="hello-acard__thumb"></span>
		<?php endif; ?>
		<span class="hello-acard__body">
			<span class="hello-acard__type"><?php echo esc_html( $label ); ?></span>
			<span class="hello-acard__ttl"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
		</span>
	</a>
	<?php
}

/**
 * SWELL のメインラッパー開始/終了（テンプレート共通）。
 */
function hello_main_open( $extra_class = '' ) {
	echo '<main id="main_content" class="l-mainContent l-article">';
	echo '<article class="l-mainContent__inner hello-mag ' . esc_attr( $extra_class ) . '" data-clarity-region="article">';
}
function hello_main_close() {
	echo '</article></main>';
}
