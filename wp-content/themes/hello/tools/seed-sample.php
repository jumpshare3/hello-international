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
wp_set_object_terms( $id, array( '学費・コスト満足度' ), 'hello_tag' );
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
wp_set_object_terms( $id, array( '学校生活のリアル' ), 'hello_tag' );
wp_set_object_terms( $id, array( '卒業生' ), 'hello_persona' );

// ---- よくある質問 ----
$id = hello_seed_post( 'hello_faq', '入学検討中の保護者向け Q&Aまとめ', 'マレーシア教育移住に関するよくある疑問を整理しています。' );
update_field( 'faq_audience', 'parent_considering', $id );
update_field( 'sections', array(
	array(
		'section_label' => 'A. 情報収集・学校選び',
		'items' => array(
			array( 'question' => 'どのように学校を探しましたか？', 'answer' => '日系企業や駐在員コミュニティから情報を集め、検索サイトやSNSの口コミも確認しました。' ),
			array( 'question' => '学校を選ぶときに重視したポイントは？', 'answer' => '学費・通学時間・安全性・カリキュラムを重点的に比較しました。' ),
		),
	),
	array(
		'section_label' => 'B. 出願・入学プロセス',
		'items' => array(
			array( 'question' => '出願から入学までどれくらいかかりましたか？', 'answer' => '書類審査や面談を含めて2〜3か月程度を想定して動きました。' ),
		),
	),
), $id );
wp_set_object_terms( $id, array( 'その他・自由記述' ), 'hello_tag' );
wp_set_object_terms( $id, array( '入学を検討している保護者' ), 'hello_persona' );

// ---- ランキング ----
$id = hello_seed_post( 'hello_ranking', '【2026年最新版】初年度費用が安い学校まとめ', '学年別の初年度目安費用をもとに、比較しやすいよう整理しました。' );
update_field( 'cond_area', 'マレーシア', $id );
update_field( 'cond_fee_basis', '初年度目安（入学金含む）', $id );
update_field( 'cond_grade', 'Nursery', $id );
update_field( 'intro', '学年別の初年度目安費用をもとに整理しました。学年や為替により変動する場合があります。', $id );
update_field( 'entries', array(
	array( 'rank' => 1, 'school_name' => 'オーストラリア・インターナショナルスクール', 'area' => 'KL中心部', 'curriculum' => 'イギリス式', 'price' => 'RM20,000以下',
		'rating_learning' => 4.4, 'rating_school_life' => 4.3, 'rating_parent_support' => 4.6, 'rating_fee_satisfaction' => 3.9, 'rating_overall' => 3.26, 'features' => 'STEM教育・IB対応' ),
	array( 'rank' => 2, 'school_name' => 'ABCインターナショナルスクール', 'area' => 'KL中心部', 'curriculum' => 'イギリス式', 'price' => 'RM35,000〜',
		'rating_learning' => 4.2, 'rating_school_life' => 4.1, 'rating_parent_support' => 4.5, 'rating_fee_satisfaction' => 3.8, 'rating_overall' => 3.18, 'features' => 'IB・STEM教育' ),
), $id );
update_field( 'footer_note', '本ランキングは各校の公開情報をもとに、初年度目安費用で整理しています。', $id );
wp_set_object_terms( $id, array( '学費・コスト満足度' ), 'hello_tag' );

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
	array( 'field_name' => '見学アレンジ', 'stars' => 4, 'note' => '日本語通訳が同行' ),
	array( 'field_name' => '出願手続き', 'stars' => 5, 'note' => '' ),
	array( 'field_name' => '学生ビザ', 'stars' => 4, 'note' => '' ),
	array( 'field_name' => '現地生活支援', 'stars' => 2, 'note' => '提携先を紹介' ),
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
update_field( 'official_url', 'https://example.com/', $id );
wp_set_object_terms( $id, array( 'イギリス式（British）' ), 'hello_curriculum' );
wp_set_object_terms( $id, array( 'KL中心部' ), 'hello_region' );
wp_set_object_terms( $id, array( 'RM20,001〜35,000' ), 'hello_price' );
wp_set_object_terms( $id, array( 'その他・自由記述' ), 'hello_tag' );

// マガジンTOPはサイトのトップ（front-page.php）が担うため、固定ページは作らない。
echo "seed done.\n";
