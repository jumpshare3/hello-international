<?php
/**
 * ショートコード [hello_faq] — よくある質問（Q&A プール）をまとめ表示する。
 *
 * まとめ記事は、固定ページ/投稿の本文に次のように置くだけで量産できる：
 *   [hello_faq target="parent-considering"]
 *
 * 属性:
 *   target   … 対象区分タームの slug（例: parent-considering）。空なら全件。
 *   section  … セクションタームの slug でさらに絞り込み（任意）。
 *   tag      … hello_tag の slug で絞り込み（任意）。
 *   group    … "section"（既定）でセクション見出しごとに分けて表示。"none" で一覧。
 *   limit    … 最大件数（既定 -1=全件）。
 *   heading  … まとめ上部に出す見出し（任意）。
 *   open     … "1" で最初から開いた状態。既定は閉じる。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'hello_faq', function ( $atts ) {
	$a = shortcode_atts( array(
		'target'  => '',
		'section' => '',
		'tag'     => '',
		'group'   => 'section',
		'limit'   => -1,
		'heading' => '',
		'open'    => '',
	), $atts, 'hello_faq' );

	$tax_query = array();
	if ( $a['target'] ) {
		$tax_query[] = array( 'taxonomy' => 'hello_faq_target', 'field' => 'slug', 'terms' => array_map( 'trim', explode( ',', $a['target'] ) ) );
	}
	if ( $a['section'] ) {
		$tax_query[] = array( 'taxonomy' => 'hello_faq_section', 'field' => 'slug', 'terms' => array_map( 'trim', explode( ',', $a['section'] ) ) );
	}
	if ( $a['tag'] ) {
		$tax_query[] = array( 'taxonomy' => 'hello_tag', 'field' => 'slug', 'terms' => array_map( 'trim', explode( ',', $a['tag'] ) ) );
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}

	$q = new WP_Query( array(
		'post_type'      => 'hello_faq',
		'post_status'    => 'publish',
		'posts_per_page' => (int) $a['limit'],
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'tax_query'      => $tax_query ?: array(),
		'no_found_rows'  => true,
	) );

	if ( ! $q->have_posts() ) {
		return '<p class="hello-faq__empty">該当する質問がまだありません。</p>';
	}

	$open_attr = $a['open'] ? ' open' : '';

	// 1問分のアコーディオンHTML
	$render_item = function ( $post_id ) use ( $open_attr ) {
		$answer = function_exists( 'get_field' ) ? get_field( 'faq_answer', $post_id ) : '';
		return sprintf(
			'<details%1$s><summary>%2$s</summary><div class="hello-faq__a">%3$s</div></details>',
			$open_attr,
			esc_html( get_the_title( $post_id ) ),
			wp_kses_post( $answer )
		);
	};

	ob_start();
	echo '<div class="hello-faq hello-faq--shortcode">';
	if ( $a['heading'] ) {
		echo '<h2 class="hello-sec__ttl">' . esc_html( $a['heading'] ) . '</h2>';
	}

	if ( 'section' === $a['group'] ) {
		// セクションごとにグルーピング（出現順を維持）
		$grouped = array();
		$order   = array();
		foreach ( $q->posts as $p ) {
			$terms = get_the_terms( $p->ID, 'hello_faq_section' );
			$label = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'その他';
			if ( ! isset( $grouped[ $label ] ) ) {
				$grouped[ $label ] = array();
				$order[]           = $label;
			}
			$grouped[ $label ][] = $p->ID;
		}
		foreach ( $order as $label ) {
			echo '<section class="hello-faq__sec">';
			echo '<h3 class="hello-faq__sec-ttl">' . esc_html( $label ) . '</h3>';
			foreach ( $grouped[ $label ] as $pid ) {
				echo $render_item( $pid ); // phpcs:ignore
			}
			echo '</section>';
		}
	} else {
		foreach ( $q->posts as $p ) {
			echo $render_item( $p->ID ); // phpcs:ignore
		}
	}
	echo '</div>';

	wp_reset_postdata();
	return ob_get_clean();
} );
