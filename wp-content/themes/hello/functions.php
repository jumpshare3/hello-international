<?php

/* ※ 子テーマのfunctions.phpは、親テーマのfunctions.phpより先に読み込まれることに注意してください。 */


/**
 * 親テーマのfunctions.phpのあとで読み込みたいコードはこの中に。
 */
add_filter('after_setup_theme', function(){
    
}, 11);


/**
 * 子テーマでのファイルの読み込み関数
 *   $query : 各ファイルに付与するクエリ文字列。
 */
add_action('wp_enqueue_scripts', function() {
    
    //$query = '1.0';
    $query = date('Ymdgis');  /*子テーマのstyle.cssのブラウザキャッシュを無効にする。 */

    /* 子テーマのstyle.css読み込み */
    wp_enqueue_style( 'child_style', get_stylesheet_directory_uri() .'/style.css', [], $query );

    /* その他の読み込みファイルはこの下に記述 */

}, 11);
