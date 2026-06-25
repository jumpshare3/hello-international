<?php
/**
 * single-hello_faq.php — よくある質問（アコーディオン）
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$audience_labels = array(
	'parent_considering'  => '保護者が答える：入学検討中の保護者向け',
	'student_considering' => '卒業生が答える：入学検討中の生徒向け',
	'parent_enrolled'     => '保護者が答える：在学中の保護者向け',
	'student_enrolled'    => '卒業生が答える：在学中の生徒向け',
);
get_header();
while ( have_posts() ) :
	the_post();
	$audience = get_field( 'faq_audience' );
	$sections = get_field( 'sections' );
	hello_main_open( 'hello-faq' );
	hello_lang_switcher();
	?>
	<header>
		<span class="hello-badge">よくある質問</span>
		<h1 class="hello-mag__ttl"><?php the_title(); ?></h1>
		<div class="hello-meta">
			<?php if ( $audience && isset( $audience_labels[ $audience ] ) ) : ?>
				<span><?php echo esc_html( $audience_labels[ $audience ] ); ?></span>
			<?php endif; ?>
		</div>
		<?php hello_the_tags(); ?>
	</header>

	<?php if ( get_the_content() ) : ?>
		<div class="post_content"><?php the_content(); ?></div>
	<?php endif; ?>

	<?php if ( $sections ) : ?>
		<?php foreach ( $sections as $sec ) : ?>
			<section class="hello-faq__sec">
				<?php if ( ! empty( $sec['section_label'] ) ) : ?>
					<h2 class="hello-faq__sec-ttl"><?php echo esc_html( $sec['section_label'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $sec['items'] ) ) : ?>
					<?php foreach ( $sec['items'] as $item ) : ?>
						<details>
							<summary><?php echo esc_html( $item['question'] ?? '' ); ?></summary>
							<div class="hello-faq__a"><?php echo wp_kses_post( $item['answer'] ?? '' ); ?></div>
						</details>
					<?php endforeach; ?>
				<?php endif; ?>
			</section>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php
	hello_main_close();
endwhile;
get_footer();
