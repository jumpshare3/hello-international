<?php
/**
 * ACF フィールドグループ：よくある質問（hello_faq）
 *
 * 1問＝1投稿のプール。タイトル＝質問、本文として回答を持つ。
 * 対象区分・セクションは タクソノミー（hello_faq_target / hello_faq_section）で分類し、
 * ショートコード [hello_faq] で集約（アコーディオン）表示する。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_faq',
	'title'    => 'よくある質問（Q&A）',
	'fields'   => array(
		array(
			'key'          => 'field_hello_faq_answer',
			'label'        => '回答',
			'name'         => 'faq_answer',
			'type'         => 'wysiwyg',
			'media_upload' => 0,
			'toolbar'      => 'basic',
			'instructions' => '質問はタイトルに入力。対象区分・セクションは右の分類で設定。',
		),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_faq' ) ),
	),
) );
