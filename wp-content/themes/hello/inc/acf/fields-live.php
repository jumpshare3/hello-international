<?php
/**
 * ACF フィールドグループ：YouTube LIVE（hello_live）
 *
 * ※ 初期スキャフォルド。実フィールドはチームで精査して調整する前提（要件メモ参照）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_live',
	'title'    => 'YouTube LIVE 詳細',
	'fields'   => array(
		array(
			'key'   => 'field_hello_live_youtube_url',
			'label' => 'YouTube 動画URL',
			'name'  => 'youtube_url',
			'type'  => 'url',
		),
		array(
			'key'   => 'field_hello_live_archive_url',
			'label' => 'アーカイブURL（任意）',
			'name'  => 'archive_url',
			'type'  => 'url',
		),
		array(
			'key'           => 'field_hello_live_date',
			'label'         => '開催日',
			'name'          => 'live_date',
			'type'          => 'date_picker',
			'display_format' => 'Y-m-d',
			'return_format'  => 'Y-m-d',
		),
		array(
			'key'     => 'field_hello_live_members_only',
			'label'   => '会員限定',
			'name'    => 'members_only',
			'type'    => 'true_false',
			'ui'      => 1,
			'default_value' => 1,
		),
		// 目次（チャプター）
		array(
			'key'          => 'field_hello_live_chapters',
			'label'        => '目次（チャプター）',
			'name'         => 'chapters',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => 'チャプターを追加',
			'sub_fields'   => array(
				array( 'key' => 'field_hello_live_ch_title', 'label' => '見出し', 'name' => 'title', 'type' => 'text' ),
				array( 'key' => 'field_hello_live_ch_time', 'label' => '開始位置（秒 または mm:ss）', 'name' => 'timestamp', 'type' => 'text', 'instructions' => '例: 125 または 2:05。再生位置・サムネ生成に利用予定（要件メモ参照）' ),
			),
		),
		// 要約Q&A（フィールド名はCPT間の衝突を避けるため live_qa）
		array(
			'key'          => 'field_hello_live_qa',
			'label'        => '要約Q&A',
			'name'         => 'live_qa',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Q&Aを追加',
			'sub_fields'   => array(
				array( 'key' => 'field_hello_live_qa_q', 'label' => '質問', 'name' => 'question', 'type' => 'text' ),
				array( 'key' => 'field_hello_live_qa_a', 'label' => '回答（要約）', 'name' => 'answer_summary', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_hello_live_qa_t', 'label' => '動画タイムスタンプ（秒/mm:ss）', 'name' => 'video_timestamp', 'type' => 'text' ),
			),
		),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_live' ) ),
	),
) );
