<?php
/**
 * single-hello_article.php — マガジン記事（通常記事）
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	hello_main_open( 'hello-article' );
	hello_lang_switcher();
	?>
	<header>
		<span class="hello-badge">記事</span>
		<h1 class="hello-mag__ttl"><?php the_title(); ?></h1>
		<?php hello_the_tags(); ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="hello-article__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>

	<div class="post_content"><?php the_content(); ?></div>

	<?php
	hello_main_close();
endwhile;
get_footer();
