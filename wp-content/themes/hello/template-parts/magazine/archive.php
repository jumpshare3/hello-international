<?php
/**
 * マガジン アーカイブ共通インナー（各 archive-hello_*.php から呼ぶ）。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$pt = get_query_var( 'post_type' );
$pt = is_array( $pt ) ? reset( $pt ) : $pt;
hello_main_open( 'hello-archive' );
hello_lang_switcher();
echo '<h1 class="hello-mag__ttl">' . esc_html( post_type_archive_title( '', false ) ) . '</h1>';
hello_magazine_tabs( $pt );

if ( have_posts() ) :
	echo '<div class="hello-grid">';
	while ( have_posts() ) :
		the_post();
		hello_magazine_card();
	endwhile;
	echo '</div>';
	the_posts_pagination( array( 'mid_size' => 2 ) );
else :
	echo '<p>記事がまだありません。</p>';
endif;

hello_main_close();
