<?php
/**
 * ACF フィールドグループ：学校マスタ（hello_school）
 * タイトル＝学校名（日本語）。英語名・slug・公式サイトURL を保持。
 * エリア／カリキュラムは タクソノミー（hello_region / hello_curriculum）で管理。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_hello_school',
	'title'    => '学校情報',
	'fields'   => array(
		array( 'key' => 'field_hello_school_name_en', 'label' => '学校名（英語）', 'name' => 'school_name_en', 'type' => 'text', 'instructions' => '例: Garden International School' ),
		array( 'key' => 'field_hello_school_slug', 'label' => 'スラッグ', 'name' => 'school_slug', 'type' => 'text', 'instructions' => 'マスタFMTの slug（例: garden-intl）。本体システムと一致させる' ),
		array( 'key' => 'field_hello_school_url', 'label' => '公式サイトURL', 'name' => 'website_url', 'type' => 'url', 'instructions' => 'ランキングの外部リンク↗に使用' ),
	),
	'location' => array(
		array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'hello_school' ) ),
	),
) );
