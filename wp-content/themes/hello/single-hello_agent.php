<?php
/**
 * single-hello_agent.php — エージェント比較 詳細
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$desc     = get_field( 'description' );
	$types    = array_filter( array( get_field( 'type_stance' ), get_field( 'type_strength' ), get_field( 'type_position' ) ) );
	$grades   = get_field( 'grade_support' );
	$sys_tags = get_field( 'system_tags' );
	$support  = get_field( 'support_fields' );
	$stance   = get_field( 'stance_text' );
	$recommend = get_field( 'recommended_families' );
	$fees     = get_field( 'fees' );
	$hardcase = get_field( 'hard_case' );
	$achieve  = get_field( 'achievements' );
	$official = get_field( 'official_url' );
	hello_main_open( 'hello-agent' );
	hello_lang_switcher();
	?>
	<header>
		<span class="hello-badge">エージェント比較</span>
		<h1 class="hello-mag__ttl"><?php the_title(); ?></h1>
		<?php if ( $types ) : ?>
			<div class="hello-agent__types">
				<?php foreach ( $types as $t ) : ?><span class="hello-agent__type"><?php echo esc_html( $t ); ?></span><?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php hello_the_tags(); ?>
	</header>

	<?php if ( $desc ) : ?><p><?php echo nl2br( esc_html( $desc ) ); ?></p><?php endif; ?>

	<?php if ( $grades ) : ?>
		<p class="hello-meta">対応学年区分：<?php echo esc_html( implode( ' / ', (array) $grades ) ); ?></p>
	<?php endif; ?>

	<?php if ( $sys_tags ) : ?>
		<div class="hello-sys-tags">
			<?php foreach ( (array) $sys_tags as $tag ) : ?><span>✅ <?php echo esc_html( $tag ); ?></span><?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $support ) :
		$total = 0;
		foreach ( $support as $s ) { $total += (int) ( $s['stars'] ?? 0 ); } ?>
		<section class="hello-sec">
			<h2 class="hello-sec__ttl">得意なサポート分野 <small>（★合計 <?php echo esc_html( $total ); ?>/20）</small></h2>
			<table class="hello-support">
				<tbody>
				<?php foreach ( $support as $s ) : ?>
					<tr>
						<th><?php echo esc_html( $s['field_name'] ?? '' ); ?></th>
						<td><?php echo hello_stars( $s['stars'] ?? 0 ); ?></td>
						<td><?php echo esc_html( $s['note'] ?? '' ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php endif; ?>

	<?php
	$blocks = array(
		'わたしたちのスタンス'       => $stance,
		'こんなご家庭におすすめ'     => $recommend,
		'費用について'               => $fees,
		'過去に解決した難題事例'     => $hardcase,
		'主な対応実績（直近3年）'    => $achieve,
	);
	foreach ( $blocks as $ttl => $body ) :
		if ( ! $body ) { continue; } ?>
		<section class="hello-sec">
			<h2 class="hello-sec__ttl"><?php echo esc_html( $ttl ); ?></h2>
			<p><?php echo nl2br( esc_html( $body ) ); ?></p>
		</section>
	<?php endforeach; ?>

	<section class="hello-sec hello-info">
		<h2 class="hello-sec__ttl">会社情報</h2>
		<dl>
			<?php
			$info = array(
				'会社名'           => get_field( 'company_name' ),
				'設立年'           => get_field( 'established' ),
				'対応言語'         => get_field( 'languages' ),
				'所在地'           => get_field( 'location' ),
				'主な連絡手段'     => get_field( 'contact_method' ),
				'レスポンス目安'   => get_field( 'response_time' ),
			);
			foreach ( $info as $k => $v ) {
				if ( $v ) {
					echo '<dt>' . esc_html( $k ) . '</dt><dd>' . esc_html( $v ) . '</dd>';
				}
			}
			?>
		</dl>
	</section>

	<?php if ( $official ) : ?>
		<?php // 計測のため中間ページを挟む想定（docs 01-requirements）。当面は直リンク。 ?>
		<a class="hello-cta" href="<?php echo esc_url( $official ); ?>" target="_blank" rel="noopener nofollow">公式サイトで相談する ↗</a>
	<?php endif; ?>

	<?php
	hello_main_close();
endwhile;
get_footer();
