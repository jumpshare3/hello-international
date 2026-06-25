<?php
/**
 * single-hello_faq.php — よくある質問（1問＝1投稿）
 * まとめ表示はショートコード [hello_faq] を使う（固定ページ等に設置）。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$answer  = get_field( 'faq_answer' );
	$targets = get_the_terms( get_the_ID(), 'hello_faq_target' );
	$secs    = get_the_terms( get_the_ID(), 'hello_faq_section' );
	hello_main_open( 'hello-faq' );
	hello_lang_switcher();
	?>
	<header>
		<span class="hello-badge">よくある質問</span>
		<div class="hello-meta">
			<?php if ( $targets && ! is_wp_error( $targets ) ) : ?><span><?php echo esc_html( $targets[0]->name ); ?></span><?php endif; ?>
			<?php if ( $secs && ! is_wp_error( $secs ) ) : ?><span><?php echo esc_html( $secs[0]->name ); ?></span><?php endif; ?>
		</div>
		<h1 class="hello-mag__ttl">Q. <?php the_title(); ?></h1>
		<?php hello_the_tags(); ?>
	</header>

	<div class="hello-faq__a hello-faq__single-a"><?php echo wp_kses_post( $answer ); ?></div>

	<?php if ( get_the_content() ) : ?>
		<div class="post_content"><?php the_content(); ?></div>
	<?php endif; ?>

	<?php
	hello_main_close();
endwhile;
get_footer();
