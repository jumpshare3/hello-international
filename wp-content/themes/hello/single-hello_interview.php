<?php
/**
 * single-hello_interview.php — インタビュー記事
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$school   = get_field( 'school_name' );
	$grade    = get_field( 'grade_level' );
	$position = get_field( 'position' );
	$year     = get_field( 'enroll_grad_year' );
	$qa       = get_field( 'qa' );
	hello_main_open( 'hello-interview' );
	hello_lang_switcher();
	?>
	<header>
		<span class="hello-badge">インタビュー</span>
		<h1 class="hello-mag__ttl"><?php the_title(); ?></h1>
		<?php hello_the_tags(); ?>
	</header>

	<section class="hello-profile">
		<dl>
			<?php if ( $school ) : ?><dt>学校名</dt><dd><?php echo esc_html( $school ); ?></dd><?php endif; ?>
			<?php if ( $grade ) : ?><dt>学区</dt><dd><?php echo esc_html( $grade ); ?></dd><?php endif; ?>
			<?php if ( $position ) : ?><dt>立場</dt><dd><?php echo esc_html( $position ); ?></dd><?php endif; ?>
			<?php if ( $year ) : ?><dt>在学／卒業年</dt><dd><?php echo esc_html( $year ); ?></dd><?php endif; ?>
		</dl>
	</section>

	<?php if ( get_the_content() ) : ?>
		<div class="post_content"><?php the_content(); ?></div>
	<?php endif; ?>

	<?php if ( $qa ) :
		// カテゴリーごとにグルーピング表示
		$grouped = array();
		foreach ( $qa as $row ) {
			$cat = $row['category'] ?: 'Q&A';
			$grouped[ $cat ][] = $row;
		}
		foreach ( $grouped as $cat => $rows ) : ?>
			<section class="hello-sec">
				<h2 class="hello-sec__ttl"><?php echo esc_html( $cat ); ?></h2>
				<div class="hello-qa">
					<?php foreach ( $rows as $row ) : ?>
						<div class="hello-qa__item">
							<p class="hello-qa__q">Q. <?php echo esc_html( $row['question'] ?? '' ); ?></p>
							<p class="hello-qa__a"><?php echo nl2br( esc_html( $row['answer'] ?? '' ) ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach;
	endif;

	hello_main_close();
endwhile;
get_footer();
