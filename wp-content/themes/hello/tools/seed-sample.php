<?php
/**
 * 表示確認用サンプル記事の投入（開発用・任意実行）。
 *
 * 実行例（ローカル）:
 *   docker compose run --rm wpcli wp eval-file \
 *     /var/www/html/wp-content/themes/hello/tools/seed-sample.php
 *
 * 冪等：同名記事が既にあれば再利用してフィールドを上書きする。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hello_seed_post( $type, $title, $content = '' ) {
	$found = get_posts( array(
		'post_type'      => $type,
		'title'          => $title,
		'posts_per_page' => 1,
		'post_status'    => 'any',
		'fields'         => 'ids',
	) );
	if ( $found ) {
		return $found[0];
	}
	$id = wp_insert_post( array(
		'post_type'    => $type,
		'post_title'   => $title,
		'post_content' => $content,
		'post_status'  => 'publish',
	) );
	if ( $id && function_exists( 'pll_set_post_language' ) ) {
		pll_set_post_language( $id, 'ja' );
	}
	return $id;
}

// ============ 学校マスタ（FMTシートの5校）============
$school_data = array(
	// slug, name_ja, name_en, website_url, region, curriculum
	array( 'garden-intl', 'ガーデン・インターナショナルスクール', 'Garden International School', 'https://www.gardenschool.edu.my', 'モントキアラ／スリハルタマス', 'イギリス式（British）' ),
	array( 'iskl', 'インターナショナル・スクール・オブ・クアラルンプール', 'International School of Kuala Lumpur (ISKL)', 'https://www.iskl.edu.my', 'KL中心部', 'アメリカ式（US）' ),
	array( 'mkis', 'モンキアラ・インターナショナルスクール', "Mont'Kiara International School", 'https://www.mkis.edu.my', 'モントキアラ／スリハルタマス', 'アメリカ式（US）' ),
	array( 'alice-smith', 'アリス・スミス・スクール', 'Alice Smith School', 'https://www.alice-smith.edu.my', 'KL中心部', 'イギリス式（British）' ),
	array( 'igbis', 'IGB インターナショナルスクール', 'IGB International School (IGBIS)', 'https://www.igbis.edu.my', 'モントキアラ／スリハルタマス', 'IB（国際バカロレア）' ),
);
$school_map = array();
foreach ( $school_data as $sd ) {
	list( $slug, $ja, $en, $url, $region, $curr ) = $sd;
	$sid = hello_seed_post( 'hello_school', $ja );
	update_field( 'school_name_en', $en, $sid );
	update_field( 'school_slug', $slug, $sid );
	update_field( 'website_url', $url, $sid );
	wp_set_object_terms( $sid, array( $region ), 'hello_region' );
	wp_set_object_terms( $sid, array( $curr ), 'hello_curriculum' );
	$school_map[ $slug ] = $sid;
}

// ---- YouTube LIVE ----
$id = hello_seed_post( 'hello_live', '卒ママが本音で回答｜CDEインターナショナルスクール', '実際に通ったからこそ分かるリアルな話を伺いました。' );
update_field( 'youtube_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', $id );
update_field( 'live_date', '2026-05-20', $id );
update_field( 'members_only', 1, $id );
update_field( 'chapters', array(
	array( 'title' => '学費って正直どれくらい？', 'timestamp' => '0:30' ),
	array( 'title' => '英語力はどのくらい伸びた？', 'timestamp' => '5:10' ),
	array( 'title' => '日本の学校との違いは？', 'timestamp' => '12:00' ),
), $id );
update_field( 'live_qa', array(
	array( 'question' => '学費って正直どれくらい？', 'answer_summary' => "日本の私立と比べると高いですが、\n学費以外にかかる費用は想像よりシンプルでした。", 'video_timestamp' => '0:30' ),
	array( 'question' => '英語力はどのくらい伸びた？', 'answer_summary' => "最初は苦労しましたが、半年〜1年で\n日常会話は問題なくなりました。", 'video_timestamp' => '5:10' ),
), $id );
wp_set_object_terms( $id, array( '費用学費' ), 'hello_tag' );
wp_set_object_terms( $id, array( '卒業生の保護者' ), 'hello_persona' );

// ---- インタビュー ----
$id = hello_seed_post( 'hello_interview', 'ABCインターナショナルスクール 卒業生インタビュー', '卒業生の生の声を聞きました。' );
update_field( 'school_name', 'ABCインターナショナルスクール', $id );
update_field( 'grade_level', 'High School', $id );
update_field( 'position', '卒業生', $id );
update_field( 'enroll_grad_year', '2025', $id );
update_field( 'qa', array(
	array( 'category' => '授業・先生について', 'question' => '授業はどんなスタイル？', 'answer' => 'ディスカッションやプレゼンが中心で、自分の考えを英語で発表する機会が多かったです。' ),
	array( 'category' => 'クラス・友人関係', 'question' => '友達づくりはしやすかった？', 'answer' => 'プロジェクトやスポーツで自然に仲良くなれました。多国籍で刺激的でした。' ),
	array( 'category' => '将来・進学', 'question' => '進路についてのサポートは？', 'answer' => 'カウンセラーが個別に相談に乗ってくれ、出願準備も手厚かったです。' ),
), $id );
wp_set_object_terms( $id, array( '学校生活' ), 'hello_tag' );
wp_set_object_terms( $id, array( '卒業生' ), 'hello_persona' );

// ---- よくある質問（Q&Aプール：1問=1投稿）20件 ----
// まとめ記事はショートコード [hello_faq target="..."] で量産する。
$faq_qa = array(
	// [質問, 回答, 対象区分slug, セクション]
	array( 'どのように学校を探しましたか？', 'まず日系企業や駐在員コミュニティから情報を集め、並行してインター校検索サイトやSNSの口コミを確認しました。', 'parent-considering', '情報収集・学校選び' ),
	array( '学校を選ぶときに重視したポイントは？', '駐在家庭では学費・通学時間・安全性・カリキュラムが特に重視され、同じ点を重点的に比較しました。', 'parent-considering', '情報収集・学校選び' ),
	array( 'カリキュラム（IB／British／American）はどう選びましたか？', '帰国後の進路を想定し、IBやBritishは進学先の選択肢が広い点を評価して選びました。', 'parent-considering', '情報収集・学校選び' ),
	array( '日本人割合はどれくらいが良いと思いましたか？', '多国籍環境が推奨される一方、日本人が一定数いる安心感も好まれ、全体の2〜3割程度を目安に考えました。', 'parent-considering', '情報収集・学校選び' ),
	array( '何校くらい見学しましたか？', '主要都市には複数のインター校があるため、平均して3〜5校を見学しました。', 'parent-considering', '情報収集・学校選び' ),
	array( '出願から入学までどれくらいかかりましたか？', '多くはローリング入学制ですが、書類審査や面談を含めて2〜3か月程度を想定して動きました。', 'parent-considering', '出願・入学プロセス' ),
	array( '面接や入学試験ではどんなことを聞かれましたか？', '学習状況や家庭の教育方針、英語力の確認が中心で、適応力を重視する学校が多い印象でした。', 'parent-considering', '出願・入学プロセス' ),
	array( '受け入れ枠が少ない学年はありましたか？', '低学年や受験に直結する学年は満席になりやすく、人気校ではウェイティングが発生することもありました。', 'parent-considering', '出願・入学プロセス' ),
	array( 'どの学年での入学・編入がスムーズでしたか？', 'Year1やYear7など区切りの学年は受け入れが比較的スムーズで、新入生向けのサポート体制も整っています。', 'parent-considering', '出願・入学プロセス' ),
	array( '入学前に準備しておいてよかったことは？', '英語での簡単な自己紹介や教室で使うフレーズに慣れておくと、初期の不安がやわらぎスムーズに適応できました。', 'parent-considering', '出願・入学プロセス' ),
	array( '英語ができなくても入学できますか？', '多くの校がノンネイティブ受け入れを前提とし、一定の学力があれば英語初級でも入学は可能です。', 'parent-considering', '英語力・学習準備' ),
	array( '英語が不安な子向けのサポート（EAL）は？', 'EAL担当教員による少人数クラスやプルアウト型が一般的で、通常授業と組み合わせ段階的に英語力を伸ばします。', 'parent-considering', '英語力・学習準備' ),
	array( '入学前に家庭でしておくとよかった学習準備は？', '英語の基礎語彙に加え、日本語での算数や読解力を固めておくと海外カリキュラムにも適応しやすいです。', 'parent-considering', '英語力・学習準備' ),
	array( '学校見学やオープンデーでチェックしてよかった点は？', '授業の様子や生徒の表情など「教室の空気感」を確認するようにしました。', 'parent-considering', '学校見学・雰囲気' ),
	array( '見学時に感じた保護者コミュニティの雰囲気は？', '駐在が多いエリアでは日本人を含む外国人保護者コミュニティが活発で、新規家庭にも情報共有が行われていました。', 'parent-considering', '学校見学・雰囲気' ),
	array( '初日はどんな気持ちでしたか？', 'ドキドキしましたが先生が笑顔で迎えてくれ、すぐに安心しました。初日からいろんな国の子と話せて新鮮でした。', 'student-considering', '入学前後の気持ち・環境の変化' ),
	array( '日本の学校との違いで驚いたことは？', 'グループディスカッションが授業の中心で、自分の考えを英語で発表する機会が多かったです。', 'student-considering', '入学前後の気持ち・環境の変化' ),
	array( '英語に慣れるまでどれくらいかかりましたか？', '授業の指示が分かるまで約3か月、友達との会話がスムーズになるまで半年ほどでした。', 'student-considering', '入学前後の気持ち・環境の変化' ),
	array( '宿題の量や難しさはどう感じましたか？', '日本の2倍近く感じましたが、クリエイティブな課題が多く考え方が変わりました。', 'student-considering', '学習・英語・先生' ),
	array( '英語が苦手な子へのサポートはありましたか？', 'EALクラスが週数回あり、少人数で基礎から丁寧に教えてくれました。', 'student-considering', '学習・英語・先生' ),
);
foreach ( $faq_qa as $i => $row ) {
	list( $q, $a, $tgt, $sec ) = $row;
	$qid = hello_seed_post( 'hello_faq', $q );
	wp_update_post( array( 'ID' => $qid, 'menu_order' => $i + 1 ) );
	update_field( 'faq_answer', '<p>' . $a . '</p>', $qid );
	$tterm = get_term_by( 'slug', $tgt, 'hello_faq_target' );
	if ( $tterm ) {
		wp_set_object_terms( $qid, array( (int) $tterm->term_id ), 'hello_faq_target' );
	}
	wp_set_object_terms( $qid, array( $sec ), 'hello_faq_section' );
}

// ---- まとめ記事（固定ページ＋ショートコードで組み立て）----
$faq_matome = array(
	'parent-considering'  => array( 'faq-parent-considering', '入学検討中の保護者向け よくある質問まとめ' ),
	'student-considering' => array( 'faq-student-considering', '入学検討中の生徒向け よくある質問まとめ' ),
);
foreach ( $faq_matome as $slug => $info ) {
	list( $name, $title ) = $info;
	$found = get_posts( array( 'post_type' => 'page', 'name' => $name, 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids' ) );
	$pid   = $found ? $found[0] : wp_insert_post( array( 'post_type' => 'page', 'post_title' => $title, 'post_name' => $name, 'post_status' => 'publish' ) );
	wp_update_post( array( 'ID' => $pid, 'post_content' => '[hello_faq target="' . $slug . '" heading="' . $title . '"]' ) );
	if ( function_exists( 'pll_set_post_language' ) && ! pll_get_post_language( $pid ) ) {
		pll_set_post_language( $pid, 'ja' );
	}
}
$id = null;

// ---- ランキング ----
$id = hello_seed_post( 'hello_ranking', '【2026年最新版】初年度費用が安い学校まとめ', '学年別の初年度目安費用をもとに、比較しやすいよう整理しました。' );
update_field( 'cond_area', 'マレーシア', $id );
update_field( 'cond_fee_basis', '初年度目安（入学金含む）', $id );
update_field( 'cond_grade', 'Nursery', $id );
update_field( 'intro', '学年別の初年度目安費用をもとに整理しました。学年や為替により変動する場合があります。', $id );
// ★学校マスタから選択（学校名/URL/エリア/カリキュラムは自動補完）
update_field( 'entries', array(
	array( 'rank' => 1, 'school' => $school_map['garden-intl'], 'price' => 'RM50,001以上',
		'rating_learning' => 4.4, 'rating_school_life' => 4.3, 'rating_parent_support' => 4.6, 'rating_fee_satisfaction' => 3.9, 'rating_overall' => 3.26, 'features' => 'STEM教育・IB対応' ),
	array( 'rank' => 2, 'school' => $school_map['mkis'], 'price' => 'RM35,001〜50,000',
		'rating_learning' => 4.2, 'rating_school_life' => 4.1, 'rating_parent_support' => 4.5, 'rating_fee_satisfaction' => 3.8, 'rating_overall' => 3.18, 'features' => 'IB・STEM教育' ),
	array( 'rank' => 3, 'school' => $school_map['alice-smith'], 'price' => 'RM35,001〜50,000',
		'rating_learning' => 4.3, 'rating_school_life' => 4.2, 'rating_parent_support' => 4.4, 'rating_fee_satisfaction' => 3.7, 'rating_overall' => 3.15, 'features' => '英国式・進学実績' ),
), $id );
update_field( 'footer_note', '本ランキングは各校の公開情報をもとに、初年度目安費用で整理しています。', $id );
wp_set_object_terms( $id, array( '費用学費' ), 'hello_tag' );

// ---- エージェント比較 ----
$id = hello_seed_post( 'hello_agent', 'マレーシア教育移住エージェント A社', '教育移住・インター進学に強い現地日系エージェントです。' );
update_field( 'description', '学校選定から出願手続き、ビザ取得、現地生活の立ち上げまでを一貫してサポートします。', $id );
update_field( 'type_stance', '教育移住サポート型', $id );
update_field( 'type_strength', '学校選定重視型', $id );
update_field( 'type_position', '低〜中価格帯中心', $id );
update_field( 'grade_support', array( 'Early Years', 'Primary', 'Secondary' ), $id );
update_field( 'system_tags', array( '日本語スタッフ在籍', '現地法人あり', '日本法人あり', 'IB対応実績あり' ), $id );
update_field( 'support_fields', array(
	array( 'field_name' => '学校選定', 'stars' => 5, 'note' => '複数校の比較表を作成' ),
	array( 'field_name' => '見学アレンジ', 'stars' => 2, 'note' => '日本語通訳が同行' ),
	array( 'field_name' => '出願手続き', 'stars' => 4, 'note' => '' ),
	array( 'field_name' => '英文書類支援', 'stars' => 1, 'note' => '成績書の英訳に対応' ),
	array( 'field_name' => '学生ビザ', 'stars' => 2, 'note' => '' ),
	array( 'field_name' => '家族ビザ', 'stars' => 1, 'note' => '' ),
	array( 'field_name' => '不動産紹介', 'stars' => 1, 'note' => '提携不動産会社を紹介' ),
	array( 'field_name' => '現地生活支援', 'stars' => 1, 'note' => '' ),
	array( 'field_name' => '入学後フォロー', 'stars' => 0, 'note' => '' ),
	array( 'field_name' => '法人設立のフォロー', 'stars' => 1, 'note' => '' ),
	array( 'field_name' => '車購入サポート', 'stars' => 0, 'note' => '' ),
	array( 'field_name' => '緊急時の対応', 'stars' => 1, 'note' => '事故・病院等に対応' ),
	array( 'field_name' => '入国後の問題解決応力', 'stars' => 1, 'note' => '' ),
), $id );
update_field( 'stance_text', '特定の学校を一方的に推薦せず、ご家庭の方針に寄り添った中立的な立場でサポートします。', $id );
update_field( 'recommended_families', "・英語がまだ話せない/不安なお子さま\n・初めての海外教育移住で何から始めればよいか分からない方", $id );
update_field( 'fees', "・出願手続きサポート：RM1,000〜\n・学生ビザ申請サポート：RM2,000〜", $id );
update_field( 'hard_case', '入学直前のビザ難航時に、学校・関係機関と連携し大きな遅延なく入学を実現しました。', $id );
update_field( 'achievements', '直近3年で約120件の入学サポート実績。', $id );
update_field( 'company_name', 'マレーシア教育移住 A社 LLC', $id );
update_field( 'established', '2020年7月', $id );
update_field( 'languages', '日本語 / 英語', $id );
update_field( 'location', 'クアラルンプール', $id );
update_field( 'contact_method', 'WhatsApp', $id );
update_field( 'response_time', '24時間以内', $id );
update_field( 'has_japan_base', 1, $id );
update_field( 'has_local_staff', 1, $id );
update_field( 'official_url', 'https://example.com/', $id );
wp_set_object_terms( $id, array( 'イギリス式（British）', 'アメリカ式（US）', 'IB（国際バカロレア）', 'オーストラリア式（Australian）' ), 'hello_curriculum' );
wp_set_object_terms( $id, array( 'KL中心部', 'モントキアラ／スリハルタマス', 'バンサー／KLセントラル' ), 'hello_region' );
wp_set_object_terms( $id, array( 'RM20,000以下', 'RM20,001〜35,000' ), 'hello_price' );
wp_set_object_terms( $id, array( '学校選び' ), 'hello_tag' );

// ============================================================
// 各種別 サンプル量産（各20件程度）
// ============================================================
$schools   = array( 'ABC', 'CDE', 'DEF', 'GHI', 'JKL', 'MNO', 'PQR', 'STU', 'VWX', 'YZ', 'Garden', 'Alice Smith', "Mont'Kiara", 'IGB', 'Sri KDU', 'Nexus', 'ISKL', 'Fairview', 'HELP', 'Cempaka' );
$personas  = array( '在籍中の保護者', '卒業生の保護者', '在校生', '卒業生' );
$curricula = array( 'イギリス式（British）', 'アメリカ式（US）', 'IB（国際バカロレア）', 'オーストラリア式（Australian）' );
$regions   = array( 'KL中心部', 'モントキアラ／スリハルタマス', 'バンサー／KLセントラル', 'ペナン', 'ジョホールバル' );
$prices    = array( 'RM20,000以下', 'RM20,001〜35,000', 'RM35,001〜50,000', 'RM50,001以上' );
$mtags     = array( '費用学費', '学校生活', '生活の知恵', '入学前の準備', '進路・進学', '英語・学習', '学校選び' );
$grades    = array( 'Primary', 'Secondary', 'High School', 'Kindergarten' );

// ---- YouTube LIVE 20件 ----
for ( $i = 1; $i <= 20; $i++ ) {
	$sc = $schools[ ( $i - 1 ) % 20 ]; $pe = $personas[ ( $i - 1 ) % 4 ];
	$id = hello_seed_post( 'hello_live', "{$pe}に聞く｜{$sc}スクールのリアル LIVE#{$i}", '実際に通ったからこそ分かるリアルな話を伺いました。' );
	update_field( 'youtube_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', $id );
	update_field( 'live_date', sprintf( '2026-%02d-%02d', ( ( $i - 1 ) % 12 ) + 1, ( ( $i * 2 ) % 27 ) + 1 ), $id );
	update_field( 'members_only', $i % 2, $id );
	update_field( 'chapters', array(
		array( 'title' => '学費って正直どれくらい？', 'timestamp' => '0:30' ),
		array( 'title' => '英語力はどのくらい伸びた？', 'timestamp' => '5:10' ),
	), $id );
	update_field( 'live_qa', array(
		array( 'question' => '学費って正直どれくらい？', 'answer_summary' => '日本の私立と比べると高いですが、学費以外はシンプルでした。', 'video_timestamp' => '0:30' ),
		array( 'question' => '英語力はどのくらい伸びた？', 'answer_summary' => '半年〜1年で日常会話は問題なくなりました。', 'video_timestamp' => '5:10' ),
	), $id );
	wp_set_object_terms( $id, array( $pe ), 'hello_persona' );
	wp_set_object_terms( $id, array( $mtags[ ( $i - 1 ) % count( $mtags ) ] ), 'hello_tag' );
}

// ---- インタビュー 20件 ----
for ( $i = 1; $i <= 20; $i++ ) {
	$sc = $schools[ ( $i - 1 ) % 20 ]; $pe = $personas[ ( $i - 1 ) % 4 ]; $gr = $grades[ ( $i - 1 ) % 4 ];
	$id = hello_seed_post( 'hello_interview', "{$sc}インターナショナルスクール {$pe}インタビュー #{$i}", '在校生・卒業生・保護者のリアルな声を聞きました。' );
	update_field( 'school_name', "{$sc}インターナショナルスクール", $id );
	update_field( 'grade_level', $gr, $id );
	update_field( 'position', in_array( $pe, array( '在校生', '卒業生' ), true ) ? $pe : ( $pe === '在籍中の保護者' ? '在籍中の保護者' : '卒業生の保護者' ), $id );
	update_field( 'enroll_grad_year', (string) ( 2020 + ( $i % 6 ) ), $id );
	update_field( 'qa', array(
		array( 'category' => '授業・先生について', 'question' => '授業はどんなスタイル？', 'answer' => 'ディスカッションやプレゼンが中心で、英語で発表する機会が多かったです。' ),
		array( 'category' => 'クラス・友人関係', 'question' => '友達づくりはしやすかった？', 'answer' => 'プロジェクトやスポーツで自然に仲良くなれました。' ),
		array( 'category' => '将来・進学', 'question' => '進路サポートは？', 'answer' => 'カウンセラーが個別に相談に乗ってくれました。' ),
	), $id );
	wp_set_object_terms( $id, array( $pe ), 'hello_persona' );
	wp_set_object_terms( $id, array( $mtags[ ( $i - 1 ) % count( $mtags ) ] ), 'hello_tag' );
}

// ---- ランキング 20件 ----
$rank_topics = array( '初年度費用が安い順', 'IB認定校まとめ', 'STEM強化校', '日本語サポートあり校', '英語イマージョン校', '少人数制の学校', '新設校まとめ', '寮あり校', 'スクールバス対応校', '課外活動が充実した学校' );
for ( $i = 1; $i <= 20; $i++ ) {
	$tp = $rank_topics[ ( $i - 1 ) % count( $rank_topics ) ];
	$id = hello_seed_post( 'hello_ranking', "【2026年版】{$tp} #{$i}", '比較しやすいよう各校の公開情報をもとに整理しました。' );
	update_field( 'cond_area', 'マレーシア', $id );
	update_field( 'cond_fee_basis', '初年度目安（入学金含む）', $id );
	update_field( 'cond_grade', $grades[ ( $i - 1 ) % 4 ], $id );
	update_field( 'intro', "{$tp}の観点で整理しました。学年や為替により変動する場合があります。", $id );
	$entries = array();
	for ( $r = 1; $r <= 3; $r++ ) {
		$sc = $schools[ ( $i + $r ) % 20 ];
		$entries[] = array(
			'rank' => $r, 'school_name' => "{$sc}インターナショナルスクール", 'school_url' => 'https://example.com/',
			'area' => $regions[ ( $i + $r ) % count( $regions ) ], 'curriculum' => $curricula[ ( $i + $r ) % count( $curricula ) ],
			'price' => $prices[ ( $i + $r ) % count( $prices ) ],
			'rating_learning' => 4.2, 'rating_school_life' => 4.1, 'rating_parent_support' => 4.4, 'rating_fee_satisfaction' => 3.8,
			'rating_overall' => 3.2, 'features' => 'STEM・IB対応など特徴あり',
		);
	}
	update_field( 'entries', $entries, $id );
	update_field( 'footer_note', '本ランキングは各校の公開情報をもとに整理しています。', $id );
	wp_set_object_terms( $id, array( $tp ), 'hello_ranking_type' );
	wp_set_object_terms( $id, array( $mtags[ ( $i - 1 ) % count( $mtags ) ] ), 'hello_tag' );
}

// ---- エージェント比較 20件 ----
$ag_support = array( '学校選定', '見学アレンジ', '出願手続き', '英文書類支援', '学生ビザ', '家族ビザ', '不動産紹介', '現地生活支援', '入学後フォロー', '法人設立のフォロー', '車購入サポート', '緊急時の対応', '入国後の問題解決応力' );
for ( $i = 1; $i <= 20; $i++ ) {
	$id = hello_seed_post( 'hello_agent', "マレーシア教育移住エージェント 第{$i}社", '教育移住・インター進学をサポートする現地日系エージェントです。' );
	update_field( 'description', '学校選定から出願手続き、ビザ取得、現地生活の立ち上げまでを一貫してサポートします。', $id );
	update_field( 'type_stance', '教育移住サポート型', $id );
	update_field( 'type_strength', ( $i % 2 ) ? '学校選定重視型' : 'ビザ・生活支援強化型', $id );
	update_field( 'type_position', ( $i % 2 ) ? '低〜中価格帯中心' : '高価格帯インター中心', $id );
	update_field( 'grade_support', array( 'Early Years', 'Primary', 'Secondary' ), $id );
	update_field( 'system_tags', array( '日本語スタッフ在籍', '現地法人あり' ), $id );
	$sf = array();
	foreach ( array_slice( $ag_support, 0, 8 ) as $k => $name ) {
		$sf[] = array( 'field_name' => $name, 'stars' => max( 0, 4 - ( ( $k + $i ) % 5 ) ), 'note' => '' );
	}
	update_field( 'support_fields', $sf, $id );
	update_field( 'company_name', "マレーシア教育移住 第{$i}社 LLC", $id );
	update_field( 'established', (string) ( 2015 + ( $i % 10 ) ) . '年', $id );
	update_field( 'languages', '日本語 / 英語', $id );
	update_field( 'location', 'クアラルンプール', $id );
	update_field( 'contact_method', 'WhatsApp', $id );
	update_field( 'response_time', '24時間以内', $id );
	update_field( 'has_japan_base', $i % 2, $id );
	update_field( 'has_local_staff', 1, $id );
	update_field( 'official_url', 'https://example.com/', $id );
	wp_set_object_terms( $id, array( $curricula[ $i % count( $curricula ) ], $curricula[ ( $i + 1 ) % count( $curricula ) ] ), 'hello_curriculum' );
	wp_set_object_terms( $id, array( $regions[ $i % count( $regions ) ], $regions[ ( $i + 1 ) % count( $regions ) ] ), 'hello_region' );
	wp_set_object_terms( $id, array( $regions[ $i % count( $regions ) ], $regions[ ( $i + 1 ) % count( $regions ) ] ), 'hello_region' );
	wp_set_object_terms( $id, array( $prices[ $i % count( $prices ) ] ), 'hello_price' );
	wp_set_object_terms( $id, array( $mtags[ ( $i - 1 ) % count( $mtags ) ] ), 'hello_tag' );
}

// ---- マガジン記事（通常記事）20件 ----
$articles = array(
	array( 'マレーシアで人気のIB校とは？', 'IBってなに？学費や進路への影響をやさしく解説します。', '進路・進学' ),
	array( '学費だけじゃない！保護者が見てるポイント5選', '安心感・言語環境など、現地ママのリアル口コミから分析しました。', '学校選び' ),
	array( '英国式・米国式・IBの違いを徹底比較', 'カリキュラムの特徴と、帰国後の進路への影響を整理します。', '進路・進学' ),
	array( 'EAL（英語補習）ってどんな仕組み？', '英語が不安でも大丈夫。サポート体制の実際を紹介します。', '英語・学習' ),
	array( '入学までの流れと必要書類まとめ', '出願から入学までのスケジュールと準備物を解説します。', '入学前の準備' ),
	array( '学費の目安と「学費以外」にかかる費用', '初年度費用の内訳と、見落としがちな追加費用を整理。', '費用学費' ),
	array( 'KL中心部 vs モントキアラ、住むならどっち？', 'エリア別の通学・生活環境を比較します。', '学校選び' ),
	array( 'スクールバスは使うべき？通学事情のリアル', '通学手段の選び方と費用・安全性のポイント。', '学校生活' ),
	array( '入学前にやっておきたい英語準備3つ', '渡航前にできる、無理のない英語準備を紹介します。', '英語・学習' ),
	array( '現地ママに聞いた「学校選びの後悔」', '実際の声から学ぶ、学校選びの注意点。', '学校選び' ),
	array( 'インター校の一日のスケジュール例', '登校から放課後まで、典型的な一日を紹介します。', '学校生活' ),
	array( '課外活動（ECA）の選び方', '子どもに合った放課後アクティビティの見つけ方。', '学校生活' ),
	array( '日本語の維持はどうする？', '海外でも日本語力を保つための家庭の工夫。', '生活の知恵' ),
	array( '学年区分（Year）の対応早見表', '日本の学年とインター校のYearの対応をまとめました。', '入学前の準備' ),
	array( '見学・オープンデーでチェックすべき点', '学校見学で見るべきポイントをチェックリスト化。', '学校選び' ),
	array( '進学実績の見方と注意点', '進学実績データの読み解き方を解説します。', '進路・進学' ),
	array( '寮（ボーディング）という選択肢', '寮生活のメリット・デメリットと費用感。', '学校生活' ),
	array( '入学後によくあるつまずきと対処法', '最初の数か月に起きやすい悩みと乗り越え方。', '生活の知恵' ),
	array( '兄弟同時入学のコツと割引制度', '複数人入学時の手続きと費用面のポイント。', '費用学費' ),
	array( '帰国を見据えた学校選びの考え方', '将来の帰国・進学を見据えた選択のヒント。', '進路・進学' ),
);
$article_ids = array();
foreach ( $articles as $row ) {
	list( $t, $ex, $tag ) = $row;
	$aid = hello_seed_post( 'hello_article', $t, "<p>{$ex}</p><p>本記事では、{$t}について現地のリアルな情報をもとに分かりやすく解説します。（サンプル本文）</p>" );
	wp_update_post( array( 'ID' => $aid, 'post_excerpt' => $ex ) );
	wp_set_object_terms( $aid, array( $tag ), 'hello_tag' );
	$article_ids[] = $aid;
}

// CTAデモ：インタビュー showcase に「おすすめ記事」を明示指定（残りは最新記事を自動表示）
$demo = array_slice( $article_ids, 0, 2 );
$int  = get_posts( array( 'post_type' => 'hello_interview', 'title' => 'ABCインターナショナルスクール 卒業生インタビュー', 'posts_per_page' => 1, 'fields' => 'ids', 'post_status' => 'any' ) );
if ( $int && function_exists( 'update_field' ) ) {
	update_field( 'recommend_articles', $demo, $int[0] );
}

// ---- 投稿日をランダム化（TOPが特定種別に偏らないよう、種別を日付でシャッフル）----
$all = get_posts( array(
	'post_type'      => array( 'hello_article', 'hello_live', 'hello_interview', 'hello_faq', 'hello_ranking', 'hello_agent' ),
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'fields'         => 'ids',
) );
foreach ( $all as $pid ) {
	$ts   = time() - rand( 0, 180 * 24 * 3600 ) - rand( 0, 86399 ); // 直近180日内のランダム日時
	$date = date( 'Y-m-d H:i:s', $ts );
	wp_update_post( array(
		'ID'            => $pid,
		'post_date'     => $date,
		'post_date_gmt' => get_gmt_from_date( $date ),
	) );
}

echo "seed done.\n";
