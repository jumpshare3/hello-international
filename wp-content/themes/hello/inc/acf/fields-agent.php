<?php
/**
 * ACF フィールドグループ：エージェント比較（hello_agent）
 *
 * 比較表の中核。タイプタグ／サポート分野（★合計20まで）／体制タグ／
 * 実績・スタンス・おすすめ家庭・費用・難題事例・基本情報・公式URL。
 * カリキュラム／エリア／価格帯は タクソノミー(hello_curriculum/region/price) で管理。
 * ※ 初期スキャフォルド。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_agent',
	'title'    => 'エージェント比較 詳細',
	'fields'   => array(

		// ■ 基本
		array( 'key' => 'field_hello_ag_tab_basic', 'label' => '基本', 'type' => 'tab' ),
		array( 'key' => 'field_hello_ag_desc', 'label' => '紹介文', 'name' => 'description', 'type' => 'textarea', 'rows' => 4 ),
		array( 'key' => 'field_hello_ag_official', 'label' => '公式サイトURL', 'name' => 'official_url', 'type' => 'url' ),
		array(
			'key'   => 'field_hello_ag_type_stance', 'label' => 'タイプ:サービススタンス', 'name' => 'type_stance', 'type' => 'text',
			'instructions' => '例: インター進学専門 / 教育移住サポート型 / フルサポート型',
		),
		array( 'key' => 'field_hello_ag_type_strength', 'label' => 'タイプ:強み', 'name' => 'type_strength', 'type' => 'text', 'instructions' => '例: 学校選定重視型 / ビザ・生活支援強化型' ),
		array( 'key' => 'field_hello_ag_type_position', 'label' => 'タイプ:ポジション（価格傾向）', 'name' => 'type_position', 'type' => 'text', 'instructions' => '例: 高価格帯インター中心 / 低〜中価格帯中心' ),
		array(
			'key'     => 'field_hello_ag_grades',
			'label'   => '対応学年区分',
			'name'    => 'grade_support',
			'type'    => 'checkbox',
			'choices' => array(
				'Early Years' => 'Early Years',
				'Primary'     => 'Primary',
				'Secondary'   => 'Secondary',
				'High School' => 'High School',
			),
		),
		array(
			'key'     => 'field_hello_ag_system_tags',
			'label'   => '体制タグ',
			'name'    => 'system_tags',
			'type'    => 'checkbox',
			'choices' => array(
				'日本語スタッフ在籍' => '日本語スタッフ在籍',
				'現地法人あり'       => '現地法人あり',
				'日本法人あり'       => '日本法人あり',
				'IB対応実績あり'     => 'IB対応実績あり',
				'幼児校対応実績あり' => '幼児校対応実績あり',
			),
		),

		// ■ 得意なサポート分野（★合計20まで）
		array( 'key' => 'field_hello_ag_tab_support', 'label' => 'サポート分野', 'type' => 'tab' ),
		array(
			'key'          => 'field_hello_ag_support',
			'label'        => '得意なサポート分野（★は合計20まで）',
			'name'         => 'support_fields',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => '項目を追加',
			'instructions' => '学校選定・見学アレンジ・出願手続き・ビザ・現地生活支援 等。★は各最大5、合計20まで（表示側で検証）。',
			'sub_fields'   => array(
				array( 'key' => 'field_hello_ag_sup_name', 'label' => '項目', 'name' => 'field_name', 'type' => 'text' ),
				array( 'key' => 'field_hello_ag_sup_stars', 'label' => '★（0〜5）', 'name' => 'stars', 'type' => 'number', 'min' => 0, 'max' => 5 ),
				array( 'key' => 'field_hello_ag_sup_note', 'label' => '補足', 'name' => 'note', 'type' => 'text' ),
			),
		),

		// ■ 詳細テキスト
		array( 'key' => 'field_hello_ag_tab_detail', 'label' => '詳細', 'type' => 'tab' ),
		array( 'key' => 'field_hello_ag_stance_text', 'label' => 'わたしたちのスタンス', 'name' => 'stance_text', 'type' => 'textarea', 'rows' => 4 ),
		array( 'key' => 'field_hello_ag_recommend', 'label' => 'こんなご家庭におすすめ', 'name' => 'recommended_families', 'type' => 'textarea', 'rows' => 4 ),
		array( 'key' => 'field_hello_ag_fees', 'label' => '費用について', 'name' => 'fees', 'type' => 'textarea', 'rows' => 4 ),
		array( 'key' => 'field_hello_ag_hardcase', 'label' => '過去に解決した難題事例', 'name' => 'hard_case', 'type' => 'textarea', 'rows' => 4 ),
		array( 'key' => 'field_hello_ag_achievements', 'label' => '主な対応実績（直近3年・自己申告）', 'name' => 'achievements', 'type' => 'textarea', 'rows' => 3 ),

		// ■ 会社基本情報
		array( 'key' => 'field_hello_ag_tab_company', 'label' => '会社情報', 'type' => 'tab' ),
		array( 'key' => 'field_hello_ag_company', 'label' => '会社名', 'name' => 'company_name', 'type' => 'text' ),
		array( 'key' => 'field_hello_ag_established', 'label' => '設立年', 'name' => 'established', 'type' => 'text' ),
		array( 'key' => 'field_hello_ag_languages', 'label' => '対応言語', 'name' => 'languages', 'type' => 'text' ),
		array( 'key' => 'field_hello_ag_location', 'label' => '所在地', 'name' => 'location', 'type' => 'text' ),
		array( 'key' => 'field_hello_ag_contact', 'label' => '主な連絡手段', 'name' => 'contact_method', 'type' => 'text', 'instructions' => '例: WhatsApp' ),
		array( 'key' => 'field_hello_ag_response', 'label' => 'レスポンス目安', 'name' => 'response_time', 'type' => 'text', 'instructions' => '例: 24時間以内' ),
		array( 'key' => 'field_hello_ag_japan_base', 'label' => '日本拠点の有無', 'name' => 'has_japan_base', 'type' => 'true_false', 'ui' => 1 ),
		array( 'key' => 'field_hello_ag_local_staff', 'label' => '現地常駐スタッフの有無', 'name' => 'has_local_staff', 'type' => 'true_false', 'ui' => 1 ),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_agent' ) ),
	),
) );
