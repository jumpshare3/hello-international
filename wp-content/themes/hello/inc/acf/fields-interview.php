<?php
/**
 * ACF フィールドグループ：インタビュー（hello_interview）
 *
 * ※ 初期スキャフォルド。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_interview',
	'title'    => 'インタビュー 詳細',
	'fields'   => array(
		// ■ プロフィール
		array( 'key' => 'field_hello_int_tab_profile', 'label' => 'プロフィール', 'type' => 'tab' ),
		array( 'key' => 'field_hello_int_school', 'label' => '学校名', 'name' => 'school_name', 'type' => 'text' ),
		array( 'key' => 'field_hello_int_grade', 'label' => '学区（例: High School）', 'name' => 'grade_level', 'type' => 'text' ),
		array(
			'key'     => 'field_hello_int_position',
			'label'   => '立場',
			'name'    => 'position',
			'type'    => 'select',
			'choices' => array(
				'卒業生' => '卒業生',
				'在校生' => '在校生',
				'卒ママ' => '卒ママ',
				'現ママ' => '現ママ',
			),
			'allow_null' => 1,
		),
		array( 'key' => 'field_hello_int_year', 'label' => '在学／卒業年', 'name' => 'enroll_grad_year', 'type' => 'text' ),

		// ■ Q&A
		array( 'key' => 'field_hello_int_tab_qa', 'label' => 'Q&A', 'type' => 'tab' ),
		array(
			'key'          => 'field_hello_int_qa',
			'label'        => 'Q&A',
			'name'         => 'qa',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Q&Aを追加',
			'sub_fields'   => array(
				array(
					'key'     => 'field_hello_int_qa_cat',
					'label'   => 'カテゴリー',
					'name'    => 'category',
					'type'    => 'text',
					'instructions' => '例: 授業・先生について / クラス・友人関係 / 将来・進学',
				),
				array( 'key' => 'field_hello_int_qa_q', 'label' => '質問', 'name' => 'question', 'type' => 'text' ),
				array( 'key' => 'field_hello_int_qa_a', 'label' => '回答', 'name' => 'answer', 'type' => 'textarea', 'rows' => 3 ),
			),
		),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_interview' ) ),
	),
) );
