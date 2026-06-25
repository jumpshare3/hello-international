<?php
/**
 * ACF フィールドグループ：よくある質問（hello_faq）
 *
 * 「＋」で開閉するアコーディオン型。セクション > Q&A の入れ子（繰り返しの繰り返し）。
 * ※ 初期スキャフォルド。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_faq',
	'title'    => 'よくある質問 詳細',
	'fields'   => array(
		array(
			'key'     => 'field_hello_faq_audience',
			'label'   => '対象区分',
			'name'    => 'faq_audience',
			'type'    => 'select',
			'instructions' => '回答者×対象の区分（スライドの ②③④ に対応）',
			'choices' => array(
				'parent_considering' => '保護者が答える：入学検討中の保護者向け',
				'student_considering' => '卒業生が答える：入学検討中の生徒向け',
				'parent_enrolled'    => '保護者が答える：在学中の保護者向け',
				'student_enrolled'   => '卒業生が答える：在学中の生徒向け',
			),
			'allow_null' => 1,
		),
		array(
			'key'          => 'field_hello_faq_sections',
			'label'        => 'セクション',
			'name'         => 'sections',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'セクションを追加',
			'sub_fields'   => array(
				array(
					'key'   => 'field_hello_faq_sec_label',
					'label' => 'セクション見出し',
					'name'  => 'section_label',
					'type'  => 'text',
					'instructions' => '例: A. 情報収集・学校選び',
				),
				array(
					'key'          => 'field_hello_faq_items',
					'label'        => 'Q&A',
					'name'         => 'items',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Q&Aを追加',
					'sub_fields'   => array(
						array( 'key' => 'field_hello_faq_q', 'label' => '質問', 'name' => 'question', 'type' => 'text' ),
						array( 'key' => 'field_hello_faq_a', 'label' => '回答', 'name' => 'answer', 'type' => 'wysiwyg', 'media_upload' => 0, 'toolbar' => 'basic' ),
					),
				),
			),
		),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_faq' ) ),
	),
) );
