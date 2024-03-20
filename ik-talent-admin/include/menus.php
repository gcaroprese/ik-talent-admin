<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/* 
IK Talent Admin Menus
Created: 03/07/2023
Last Update: 01/08/2023
Author: Gabriel Caroprese
*/

//Menu constants
define('IK_TALENTS_MENU_VAL_CONFIG', "ik_talent_config_page");
define('IK_TALENTS_MENU_VAL_REQ', "ik_talent_enquiries");
define('IK_TALENTS_MENU_VAL_ENTRIES', "ik_talent_main");
define('IK_TALENTS_MENU_VAL_TECHSTACKS', "ik_talent_tech_stacks");
define('IK_TALENTS_MENU_VAL_SKILLS', "ik_talent_skills");
define('IK_TALENTS_MENU_VAL_QUEST', "ik_talent_questions");

// I add menus on WP-admin
add_action('admin_menu', 'ik_talent_main_menu', 999);
function ik_talent_main_menu(){
    add_menu_page(__( 'Talents', 'ik_talent_admin' ), __( 'Talents', 'ik_talent_admin' ), 'manage_options', 'ik_talent_main', false, 'dashicons-id-alt' );
    add_submenu_page('ik_talent_main', __( 'Talents List', 'ik_talent_admin' ), __( 'Talents List', 'ik_talent_admin' ), 'manage_options', IK_TALENTS_MENU_VAL_ENTRIES, 'ik_talent_main', 1 );
    add_submenu_page('ik_talent_main', __( 'Enquiries', 'ik_talent_admin' ), __( 'Enquiries', 'ik_talent_admin' ), 'manage_options', IK_TALENTS_MENU_VAL_REQ, 'ik_talent_req_page', 3 );
    add_submenu_page('ik_talent_main', __( 'Skills', 'ik_talent_admin' ), __( 'Skills', 'ik_talent_admin' ), 'manage_options', IK_TALENTS_MENU_VAL_SKILLS, 'ik_talent_skills_page', 4 );
    add_submenu_page('ik_talent_main', __( 'Tech Stack', 'ik_talent_admin' ), __( 'Tech Stack', 'ik_talent_admin' ), 'manage_options', IK_TALENTS_MENU_VAL_TECHSTACKS, 'ik_talent_techstack_page', 5 );
    add_submenu_page('ik_talent_main', __( 'Questionnaire', 'ik_talent_admin' ), __( 'Questionnaire', 'ik_talent_admin' ), 'manage_options', IK_TALENTS_MENU_VAL_QUEST, 'ik_talent_quest_page', 6 );
}

//Function to add menu content
function ik_talent_main(){
    include (IK_TALENT_DIR.'/templates/talents.php');
}
function ik_talent_req_page(){
    include (IK_TALENT_DIR.'/templates/enquiries.php');
}
function ik_talent_skills_page(){
    include (IK_TALENT_DIR.'/templates/skills.php');
}
function ik_talent_techstack_page(){
    include (IK_TALENT_DIR.'/templates/techstack.php');
}
function ik_talent_quest_page(){
    include (IK_TALENT_DIR.'/templates/questions.php');
}

?>