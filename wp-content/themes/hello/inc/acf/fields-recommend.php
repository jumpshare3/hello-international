<?php
/**
 * ACF：CTA「知って得する Helo! マガジン」に表示するマガジン記事を投稿ごとに指定する。
 * 未指定なら最新のマガジン記事を表示（hello_get_recommend_cta 側でフォールバック）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_recommend',
	'title'    => 'おすすめ記事（CTA）',
	'fields'   => array(
		array(
			'key'           => 'field_hello_recommend_articles',
			'label'         => 'CTAに表示するマガジン記事',
			'name'          => 'recommend_articles',
			'type'          => 'relationship',
			'post_type'     => array( 'hello_article' ),
			'filters'       => array( 'search' ),
			'max'           => 2,
			'return_format' => 'id',
			'instructions'  => '「知って得する Helo! マガジン」CTAに表示する記事を最大2つ選択。未指定なら最新記事を表示。',
		),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_live' ) ),
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_interview' ) ),
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_faq' ) ),
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_ranking' ) ),
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_agent' ) ),
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_article' ) ),
	),
	'menu_order' => 20,
) );
