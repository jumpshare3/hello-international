<?php
/**
 * archive-hello_live.php — マガジン一覧（共通インナーを使用）
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part( 'template-parts/magazine/archive' );
get_footer();
