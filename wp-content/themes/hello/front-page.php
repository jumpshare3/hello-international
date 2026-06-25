<?php
/**
 * front-page.php — サイトのトップ（/）をマガジンTOPとして表示する。
 * SWELL のデフォルトフロント（page.php の is_front_page 分岐）より優先される。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part( 'template-parts/magazine/top' );
get_footer();
