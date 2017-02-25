<?php 
/*************** PUBLIC SECTION ***************/

require_once IHC_PATH . 'public/functions.php';

//SHORTCODES
require IHC_PATH . 'public/shortcodes.php';

//INIT ACTION (login, register, logout, reset_pass)
require IHC_PATH . 'public/init.php';
add_action('init', 'ihc_init', 50, 0);

//FILTERS
require IHC_PATH . 'public/filters.php';
add_filter('the_content', 'ihc_print_message', 99);

//STYLE AND SCRIPTS
add_action('wp_enqueue_scripts', 'ihc_public_head');
function ihc_public_head(){
	wp_enqueue_style( 'ihc_font_style', IHC_URL . 'assets/css/font-awesome.css' );
	wp_enqueue_style( 'ihc_front_end_style', IHC_URL . 'assets/css/style.css' );
	wp_enqueue_style( 'ihc_templates_style', IHC_URL . 'assets/css/templates.css' );
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script( 'ihc-jquery_upload_file', IHC_URL . 'assets/js/jquery.uploadfile.min.js', array(), null );
	wp_enqueue_script( 'ihc-front_end_js', IHC_URL . 'assets/js/functions.js', array(), null );
	wp_localize_script( 'ihc-front_end_js', 'ihc_site_url', get_site_url() );
}


