<?php
/**
 * single-hello_ranking.php — ランキング詳細
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$area    = get_field( 'cond_area' );
	$fee     = get_field( 'cond_fee_basis' );
	$grade   = get_field( 'cond_grade' );
	$intro   = get_field( 'intro' );
	$entries = get_field( 'entries' );
	$footer  = get_field( 'footer_note' );
	hello_main_open( 'hello-ranking' );
	hello_lang_switcher();
	?>
	<header>
		<span class="hello-badge">ランキング</span>
		<h1 class="hello-mag__ttl"><?php the_title(); ?></h1>
		<?php hello_the_tags(); ?>
	</header>

	<?php if ( $area || $fee || $grade ) : ?>
		<div class="hello-rank__cond">
			<?php if ( $area ) : ?><span>📍 対象エリア: <?php echo esc_html( $area ); ?></span><?php endif; ?>
			<?php if ( $fee ) : ?><span>💰 費用基準: <?php echo esc_html( $fee ); ?></span><?php endif; ?>
			<?php if ( $grade ) : ?><span>🎓 学年: <?php echo esc_html( $grade ); ?></span><?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $intro ) : ?><p><?php echo nl2br( esc_html( $intro ) ); ?></p><?php endif; ?>

	<?php if ( $entries ) : ?>
		<div class="hello-rank__list">
			<?php foreach ( $entries as $e ) :
				$photo = $e['photo'] ?? null; ?>
				<div class="hello-card">
					<div class="hello-card__rank"><?php echo esc_html( $e['rank'] ?? '' ); ?>位</div>
					<div>
						<?php if ( $photo && ! empty( $photo['sizes']['medium'] ) ) : ?>
							<div class="hello-card__photo"><img src="<?php echo esc_url( $photo['sizes']['medium'] ); ?>" alt="<?php echo esc_attr( $e['school_name'] ?? '' ); ?>" width="240"></div>
						<?php endif; ?>
						<p class="hello-card__name">
							<?php echo esc_html( $e['school_name'] ?? '' ); ?>
							<?php if ( ! empty( $e['school_url'] ) ) : ?>
								<a class="hello-extlink" href="<?php echo esc_url( $e['school_url'] ); ?>" target="_blank" rel="noopener nofollow"
									title="公式サイトを開く" aria-label="<?php echo esc_attr( ( $e['school_name'] ?? '' ) . ' の公式サイトを開く（外部リンク）' ); ?>">
									<span aria-hidden="true">↗</span>
								</a>
							<?php endif; ?>
						</p>
						<div class="hello-card__attr">
							<?php if ( ! empty( $e['area'] ) ) : ?><span>🏙️ <?php echo esc_html( $e['area'] ); ?></span><?php endif; ?>
							<?php if ( ! empty( $e['curriculum'] ) ) : ?><span>📘 <?php echo esc_html( $e['curriculum'] ); ?></span><?php endif; ?>
							<?php if ( ! empty( $e['price'] ) ) : ?><span>💰 <?php echo esc_html( $e['price'] ); ?></span><?php endif; ?>
						</div>
						<div class="hello-card__ratings">
							<span>学習環境 <?php echo hello_stars( $e['rating_learning'] ?? 0 ); ?></span>
							<span>学校生活 <?php echo hello_stars( $e['rating_school_life'] ?? 0 ); ?></span>
							<span>保護者対応 <?php echo hello_stars( $e['rating_parent_support'] ?? 0 ); ?></span>
							<span>費用満足度 <?php echo hello_stars( $e['rating_fee_satisfaction'] ?? 0 ); ?></span>
						</div>
						<?php if ( ! empty( $e['rating_overall'] ) ) : ?>
							<p>総合評価 <span class="hello-card__total"><?php echo esc_html( number_format( (float) $e['rating_overall'], 2 ) ); ?></span></p>
						<?php endif; ?>
						<?php if ( ! empty( $e['features'] ) ) : ?><p><?php echo nl2br( esc_html( $e['features'] ) ); ?></p><?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $footer ) : ?><p class="hello-meta"><?php echo nl2br( esc_html( $footer ) ); ?></p><?php endif; ?>

	<?php
	hello_main_close();
endwhile;
get_footer();
