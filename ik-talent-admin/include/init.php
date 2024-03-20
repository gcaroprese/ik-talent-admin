<?php
/* 
Talents Admin | Init Functions
Created: 10/08/2023
Last Update: 18/08/2023
Author: Gabriel Caroprese
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define('IK_TALENT_CVS_FOLDER_DIR', 'cvs');
define('IK_TALENTS_POST_TYPE_ID', "ik_talent");

require_once(IK_TALENT_DIR . '/include/menus.php');
require_once(IK_TALENT_DIR . '/include/class/class.skills.php');
require_once(IK_TALENT_DIR . '/include/class/class.talents.php');
require_once(IK_TALENT_DIR . '/include/class/class.talent_requests.php');
require_once(IK_TALENT_DIR . '/include/class/class.techstack.php');
require_once(IK_TALENT_DIR . '/include/class/class.talent_main.php');
require_once(IK_TALENT_DIR . '/include/talent_post_type.php');
require_once(IK_TALENT_DIR . '/include/ajax_functions.php');
require_once(IK_TALENT_DIR . '/include/shortcodes/questions_shortcode.php');
require_once(IK_TALENT_DIR . '/include/shortcodes/data_talent_shortcode.php');

//function to create tables in DB, creates folder and refreshes permalinks for ik_talent post type
function ik_talent_basic_config() {

	//Refresh permalinks
	flush_rewrite_rules();

	$talent_admin = new Ik_Talent_Admin();
	$talent_admin->create_db_tables();

	//Create folder for CVs
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_dir = $upload_dir . '/'.IK_TALENT_CVS_FOLDER_DIR;
	if (! is_dir($upload_dir)) {
		mkdir( $upload_dir, 0755 );
	}

	//Create index file to avoid navigation
	$index_file_path_temp = $upload_dir .'/index.php';

	// Verify if exists file to avoid navigation no matter what
	if ( ! file_exists( $index_file_path_temp ) ) {
		$content_index_temp = '<?php 
		//silence is golden 
		?>';
		file_put_contents( $index_file_path_temp, $content_index_temp );
	}
}

// function to start session to save data about booking data
add_action('init', 'ik_talent_questions_session');
function ik_talent_questions_session() {
    if (!session_id()) {
        session_start();
    }
}

//I add style and scripts from plugin to dashboard
function ik_talent_add_css_js(){
	wp_register_style( 'ik_talent_css', IK_TALENT_PUBLIC . 'css/stylesheet-backend.css', false, '1.1.2', 'all' );
	wp_enqueue_style('ik_talent_css');

	if ( ! wp_script_is( 'bootstrap', 'enqueued' )) {
		wp_enqueue_script('bootstrap', IK_TALENT_PUBLIC . 'js/bootstrap.min.js', array(), '5.1.3', true );
	}

    if ( ! wp_script_is( 'jquery', 'enqueued' )) {
		wp_enqueue_script('jquery', IK_TALENT_PUBLIC . 'js/jquery.min.1-11-2.js', array(), '1.11.2', true );
    }
	if ( ! wp_script_is( 'select2', 'enqueued' )) {
		wp_enqueue_script('select', IK_TALENT_PUBLIC . 'js/select2.js', array(), '3.4.8', true );
	}
	wp_enqueue_style( 'jquery-ui-css', IK_TALENT_PUBLIC . 'css/jquery-ui.css' );
	wp_enqueue_script('jquery-ui-ik-sch-book', IK_TALENT_PUBLIC . '/js/jquery-ui.min.1-11-4.js', array(), '1.11.4', true );
	wp_enqueue_script('convert-js-talents', IK_TALENT_PUBLIC . 'js/convert-to-csv.js', array(), '1.1.4', true );
}
add_action( 'admin_enqueue_scripts', 'ik_talent_add_css_js' );

//Alow pdf and doc files for CV upload
function ik_talent_myme_types($mime_types){
    $mime_types['pdf'] = 'application/pdf';
    $mime_types['doc'] = 'application/msword';
    $mime_types['docx'] = 'application/msword';
return $mime_types;
}
add_filter('upload_mimes', 'ik_talent_myme_types', 1, 1);

?>