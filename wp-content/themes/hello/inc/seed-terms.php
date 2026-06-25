<?php
/**
 * タクソノミーの初期ターム投入（CMS 表記統一・タグマスタに準拠）。
 *
 * 出典: CMSタグ管理スライド（1p-AO2dz7odihq-xuQY_pWq1bsV7NLdn2UjhtCuFyDys）
 *   - 立場 / カリキュラム / エリア / 学費帯 / 口コミカテゴリ の「表記統一(20260608)」。
 *
 * 冪等。テーマ有効化時に一度だけ実行（option フラグで多重実行を防止）。
 * 手動で再投入したい場合: wp eval 'hello_seed_default_terms();'
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** タクソノミー => 初期ターム（CMS表記統一準拠） */
function hello_default_terms() {
	return array(
		// 立場（表記統一 20260608）
		'hello_persona' => array(
			'在籍中の保護者',
			'卒業生の保護者',
			'在校生',
			'卒業生',
			'入学を検討している保護者',
		),
		// カリキュラム（タグマスタ）
		'hello_curriculum' => array(
			'イギリス式（British）',
			'アメリカ式（US）',
			'IB（国際バカロレア）',
			'オーストラリア式（Australian）',
			'カナダ式（Canadian）',
			'モンテッソーリ',
			'宗教系（カトリック・イスラムなど）',
		),
		// エリア（タグマスタ）
		'hello_region' => array(
			'KL中心部',
			'モントキアラ／スリハルタマス',
			'バンサー／KLセントラル',
			'PJ／TTDI／バンダーウタマ',
			'サイバージャヤ／プトラジャヤ',
			'シャーアラム／スバンジャヤ',
			'ペナン',
			'ジョホールバル',
			'その他エリア',
		),
		// 学費帯（タグマスタ）
		'hello_price' => array(
			'RM20,000以下',
			'RM20,001〜35,000',
			'RM35,001〜50,000',
			'RM50,001以上',
		),
		// 横断タグ（マガジン記事の編集用ハッシュタグ）。
		// ※ 旧版では「口コミカテゴリ」(=システム本体側のマスタ) を誤って投入していたため修正。
		//   マガジンのトピックに置き換え。確定リストは要確認（ワイヤーの #タグ 例ベース）。
		'hello_tag' => array(
			'費用学費',
			'学校生活',
			'生活の知恵',
			'入学前の準備',
			'進路・進学',
			'英語・学習',
			'学校選び',
		),
	);
}

/** 初期タームを冪等に投入する */
function hello_seed_default_terms() {
	foreach ( hello_default_terms() as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		foreach ( $terms as $term ) {
			if ( ! term_exists( $term, $taxonomy ) ) {
				wp_insert_term( $term, $taxonomy );
			}
		}
	}
}

// テーマ有効化時に一度だけ
add_action( 'after_switch_theme', 'hello_seed_default_terms' );

// フラグ未設定なら管理画面アクセス時に一度だけ実行（初回導入の取りこぼし防止）
add_action( 'admin_init', function () {
	if ( get_option( 'hello_terms_seeded_v1' ) ) {
		return;
	}
	hello_seed_default_terms();
	update_option( 'hello_terms_seeded_v1', 1 );
} );
