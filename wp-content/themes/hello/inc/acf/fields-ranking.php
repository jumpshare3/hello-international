<?php
/**
 * ACF フィールドグループ：ランキング（hello_ranking）
 *
 * 比較条件 ＋ 学校エントリの繰り返し（順位・評価・費用 等）。
 * ※ 学校カードの自動挿入は未確定のため、当面は手動エントリ前提（要件メモ参照）。
 * ※ 初期スキャフォルド。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_ranking',
	'title'    => 'ランキング 詳細',
	'fields'   => array(
		// 比較条件
		array( 'key' => 'field_hello_rank_cond_area', 'label' => '対象エリア', 'name' => 'cond_area', 'type' => 'text', 'instructions' => '例: マレーシア' ),
		array( 'key' => 'field_hello_rank_cond_fee', 'label' => '費用基準', 'name' => 'cond_fee_basis', 'type' => 'text', 'instructions' => '例: 初年度目安（入学金含む）' ),
		array( 'key' => 'field_hello_rank_cond_grade', 'label' => '学年区分', 'name' => 'cond_grade', 'type' => 'text', 'instructions' => '例: Nursery / Primary 等' ),
		array( 'key' => 'field_hello_rank_intro', 'label' => 'リード文', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),

		// エントリ（学校）
		array(
			'key'          => 'field_hello_rank_entries',
			'label'        => 'ランキングエントリ',
			'name'         => 'entries',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => '学校を追加',
			'sub_fields'   => array(
				array( 'key' => 'field_hello_rank_e_rank', 'label' => '順位', 'name' => 'rank', 'type' => 'number' ),
				// ★学校マスタから選択（表記揺れ防止）。選択すると 学校名/URL/エリア/カリキュラムは自動補完。
				array( 'key' => 'field_hello_rank_e_school_ref', 'label' => '学校（マスタから選択）', 'name' => 'school', 'type' => 'post_object', 'post_type' => array( 'hello_school' ), 'return_format' => 'id', 'allow_null' => 1, 'ui' => 1, 'instructions' => 'まず学校マスタから選択。下の手入力欄はマスタ未登録校のときだけ使う（上書き）。' ),
				array( 'key' => 'field_hello_rank_e_school', 'label' => '学校名（手入力・マスタ未選択時）', 'name' => 'school_name', 'type' => 'text' ),
				array( 'key' => 'field_hello_rank_e_url', 'label' => '公式サイトURL（手入力・マスタ未選択時）', 'name' => 'school_url', 'type' => 'url' ),
				array( 'key' => 'field_hello_rank_e_photo', 'label' => '写真', 'name' => 'photo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
				array( 'key' => 'field_hello_rank_e_area', 'label' => 'エリア（手入力・マスタ未選択時）', 'name' => 'area', 'type' => 'text' ),
				array( 'key' => 'field_hello_rank_e_curr', 'label' => 'カリキュラム（手入力・マスタ未選択時）', 'name' => 'curriculum', 'type' => 'text' ),
				array( 'key' => 'field_hello_rank_e_price', 'label' => '初年度目安費用', 'name' => 'price', 'type' => 'text', 'instructions' => '例: RM35,000〜' ),
				// 評価（★）
				array( 'key' => 'field_hello_rank_e_r_learn', 'label' => '評価:学習環境', 'name' => 'rating_learning', 'type' => 'number', 'min' => 0, 'max' => 5, 'step' => 0.1 ),
				array( 'key' => 'field_hello_rank_e_r_life', 'label' => '評価:学校生活', 'name' => 'rating_school_life', 'type' => 'number', 'min' => 0, 'max' => 5, 'step' => 0.1 ),
				array( 'key' => 'field_hello_rank_e_r_parent', 'label' => '評価:保護者対応', 'name' => 'rating_parent_support', 'type' => 'number', 'min' => 0, 'max' => 5, 'step' => 0.1 ),
				array( 'key' => 'field_hello_rank_e_r_fee', 'label' => '評価:費用満足度', 'name' => 'rating_fee_satisfaction', 'type' => 'number', 'min' => 0, 'max' => 5, 'step' => 0.1 ),
				array( 'key' => 'field_hello_rank_e_r_total', 'label' => '総合評価', 'name' => 'rating_overall', 'type' => 'number', 'min' => 0, 'max' => 5, 'step' => 0.01 ),
				array( 'key' => 'field_hello_rank_e_feat', 'label' => '特徴', 'name' => 'features', 'type' => 'textarea', 'rows' => 2 ),
			),
		),
		array( 'key' => 'field_hello_rank_footer', 'label' => 'フッター注記', 'name' => 'footer_note', 'type' => 'textarea', 'rows' => 2 ),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_ranking' ) ),
	),
) );
