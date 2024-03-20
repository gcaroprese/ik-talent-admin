<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/* 
Talents Admin | Ajax Functions
Created: 03/07/2023
Last Update: 01/08/2023
Author: Gabriel Caroprese
*/


//ajax to delete skill 
add_action('wp_ajax_ik_talent_ajax_delete_skill', 'ik_talent_ajax_delete_skill');
function ik_talent_ajax_delete_skill(){
    if (isset($_POST['iddata'])){
        $skills = new Ik_Talent_Skills();
        $skill_id = intval($_POST['iddata']);
        $skills->delete($skill_id);
    }
    wp_send_json(true);
    wp_die();         
}

//ajax to delete skill role
add_action('wp_ajax_ik_talent_ajax_delete_skill_role', 'ik_talent_ajax_delete_skill_role');
function ik_talent_ajax_delete_skill_role(){
    if (isset($_POST['iddata'])){
        $skills = new Ik_Talent_Skills();
        $role_id = intval($_POST['iddata']);
        $skills->delete_role($role_id);
    }
    wp_send_json(true);
    wp_die();         
}

//ajax to delete TechStack
add_action('wp_ajax_ik_talent_ajax_delete_techstack', 'ik_talent_ajax_delete_techstack');
function ik_talent_ajax_delete_techstack(){
    if (isset($_POST['iddata'])){
        $TechStack = new Ik_Talent_TechStack();
        $techstack_id = intval($_POST['iddata']);
        $TechStack->delete($techstack_id);
    }
    wp_send_json(true);
    wp_die();         
}


//ajax to delete inquiry
add_action('wp_ajax_ik_talent_ajax_delete_enquiry', 'ik_talent_ajax_delete_enquiry');
function ik_talent_ajax_delete_enquiry(){
    if (isset($_POST['iddata'])){
        $inquiry = new Ik_Talent_Requests();
        $inquiry_id = intval($_POST['iddata']);
        $inquiry->delete($inquiry_id);
    }
    wp_send_json(true);
    wp_die();         
}

//ajax to set skill as popular or not popular
add_action('wp_ajax_ik_talent_ajax_popular_skill', 'ik_talent_ajax_popular_skill');
function ik_talent_ajax_popular_skill(){
    if (isset($_POST['iddata'])){
        $skills = new Ik_Talent_Skills();
        $skill_id = intval($_POST['iddata']);

        //changes to popular or not popular and returns the dashicon star icon
        $popular_icon = $skills->change_popular($skill_id);
        wp_send_json($popular_icon);
    }
    wp_die();         
}

//ajax to set skill as popular or not popular
add_action('wp_ajax_ik_talent_ajax_popular_techstack', 'ik_talent_ajax_popular_techstack');
function ik_talent_ajax_popular_techstack(){
    if (isset($_POST['iddata'])){
        $TechStack = new Ik_Talent_TechStack();
        $techstack_id = intval($_POST['iddata']);

        //changes to popular or not popular and returns the dashicon star icon
        $popular_icon = $TechStack->change_popular($techstack_id);
        wp_send_json($popular_icon);
    }
    wp_die();         
}

//ajax to update skills selector
add_action('wp_ajax_ik_talent_ajax_update_skills_selector', 'ik_talent_ajax_update_skills_selector');
function ik_talent_ajax_update_skills_selector(){
    if (isset($_POST['iddata'])){
        $skills = new Ik_Talent_Skills();
        $role_id = intval($_POST['iddata']);
        $skills_selector = $skills->get_selector($role_id);
        wp_send_json($skills_selector);
    }
    wp_die();         
}

//ajax to delete talent
add_action('wp_ajax_ik_talent_ajax_delete_talent', 'ik_talent_ajax_delete_talent');
function ik_talent_ajax_delete_talent(){
    if (isset($_POST['iddata'])){
        $talents = new Ik_Talents();
        $talent_id = intval($_POST['iddata']);
        $talents->delete($talent_id);
    }
    wp_send_json(true);
    wp_die();         
}

// Ajax to upload talent's profile picture and retrieve new image uploader view
add_action( 'wp_ajax_ik_talent_ajax_upload_profile_img', 'ik_talent_ajax_upload_profile_img');
function ik_talent_ajax_upload_profile_img() {
    if(isset($_GET['image_id'])){
        $id_file = intval($_GET['image_id']);
        $talent_admin = new Ik_Talent_Admin();
        if ($id_file > 0){

            //returns a new view of the btn uploader
            $btn_uploader_html = $talent_admin->profile_image_uploader($id_file);
            wp_send_json( $btn_uploader_html );
        } else {
            //return an empty uploader
            $btn_uploader_html = $talent_admin->profile_image_uploader();
            wp_send_json( $btn_uploader_html );
        }
    } else {
        wp_send_json_success( false );    
    }
    wp_die();     
}

//ajax to get talent data for specific id
add_action('wp_ajax_ik_talent_ajax_show_backend_talent_data', 'ik_talent_ajax_show_backend_talent_data');
function ik_talent_ajax_show_backend_talent_data(){
    if (isset($_POST['iddata'])){
        $talent_id = intval($_POST['iddata']);
        $talent_main = new Ik_Talent_Admin();
        $talent_modal = $talent_main->show_modal_by_talent_id($talent_id);
        wp_send_json($talent_modal);
    } else {
        wp_send_json(false);
    }
    wp_die();
}

//ajax to get table with full list of talents
add_action('wp_ajax_ik_talent_ajax_get_table_all_talents', 'ik_talent_ajax_get_table_all_talents');
function ik_talent_ajax_get_table_all_talents(){
    if (current_user_can( 'manage_options' )){
        $talent_main = new Ik_Talent_Admin();
        $talents_table = $talent_main->get_table_all_talents();
        wp_send_json($talents_table);
    } else {
        wp_send_json(false);
    }
    wp_die();
}

//ajax to get questions for quiz
add_action('wp_ajax_nopriv_ik_talent_ajax_update_questions', 'ik_talent_ajax_update_questions');
add_action('wp_ajax_ik_talent_ajax_update_questions', 'ik_talent_ajax_update_questions');
function ik_talent_ajax_update_questions(){

    $Talents_admin = new Ik_Talent_Admin();

    //if already completed a step
    if(isset($_SESSION['ik_talent_role_selected']) && isset($_POST['go_back'])){
        $role_id = intval($_SESSION['ik_talent_role_selected']);

        if($role_id > 0){

            $stage_id_now = (isset($_SESSION['ik_talent_question_stage'])) ? absint($_SESSION['ik_talent_question_stage']) : 0;

            //if role was anwered and I'm not going back to an old answer
            if(isset($_SESSION['ik_talent_role_answered']) && !rest_sanitize_boolean($_POST['go_back'])){
                $answer = array(
                    'answerInputvalue' => (isset($_POST['answerInputvalue'])) ? sanitize_text_field($_POST['answerInputvalue']) : '', // textarea
                    'radio_value' => (isset($_POST['radio_value'])) ? sanitize_text_field($_POST['radio_value']) : '', // multiple choice
                    'selected_values' => (isset($_POST['selected_values'])) ? $_POST['selected_values'] : '', //skills or language
                    'comments' => (isset($_POST['comments'])) ? sanitize_text_field($_POST['comments']) : '' //additional text area
                );
                    
                    sanitize_text_field($_POST['answerInputvalue']);
                //if a question was answered / if its wrong the stage id will keep being the same
                $stage_id_now = $Talents_admin->validate_answers($role_id, $stage_id_now, $answer); //if valid $stage_id_now = $stage_id_now + 1
            } else {
                //role just selected
                $_SESSION['ik_talent_role_answered'] = true;
            }
            //update session data
            $_SESSION['ik_talent_question_stage'] = $stage_id_now; 

            //send question
            $question = $Talents_admin->send_questions($role_id, $stage_id_now);
            if($question){
                $output = $question;     
            } else {
                $output['html'] = $Talents_admin->get_contact_form().$Talents_admin->navbar_questions(false);
            }
        } else {
            //check if role was selected properly
            if(isset($_POST['radio_value'])){
               //process answer to send questions based on role selected
                $role_id = intval($_POST['radio_value']);

                //if role exists send the questions for that role
                if($Talents_admin->skills->get_role_by_id($role_id)){
                    //set session data for role
                    $_SESSION['ik_talent_role_selected'] = $role_id;

                    $question = $Talents_admin->send_questions($role_id);
                    if($question){
                        $output = $question;
                    } else {
                        $output['html'] = $Talents_admin->get_contact_form().$Talents_admin->navbar_questions(false);
                    }
                } else {
                    //wrong role id - complete question again
                    $output = $Talents_admin->get_roles_selection();   
                }
            } else {
                //show roles to select again
                $output = $Talents_admin->get_roles_selection();
            }
        }
    } else {
        //iniate session data for role selected as 0
        $_SESSION['ik_talent_role_selected'] = 0;
        
        //from beginning showing list to select different roles
        $output = $Talents_admin->get_roles_selection();
    }
    wp_send_json($output);
    wp_die();
}

//ajax to go back on questions for quiz
add_action('wp_ajax_nopriv_ik_talent_ajax_back_questions', 'ik_talent_ajax_back_questions');
add_action('wp_ajax_ik_talent_ajax_back_questions', 'ik_talent_ajax_back_questions');
function ik_talent_ajax_back_questions(){
    if(isset($_POST['go_back'])){
        $stage_id_now = (isset($_SESSION['ik_talent_question_stage'])) ? absint($_SESSION['ik_talent_question_stage']) -1 : NULL;
    
        if($stage_id_now >= 0){
            $_SESSION['ik_talent_question_stage'] = $stage_id_now;
        } else {
            unset($_SESSION['ik_talent_role_selected']);
        }
    
        wp_send_json(true);
    }
    wp_die();
}

//ajax to go submit form after completing quiz
add_action('wp_ajax_nopriv_ik_talent_ajax_submit_form', 'ik_talent_ajax_submit_form');
add_action('wp_ajax_ik_talent_ajax_submit_form', 'ik_talent_ajax_submit_form');
function ik_talent_ajax_submit_form(){
    if(isset($_POST['email']) && isset($_POST['company']) && isset($_POST['name']) && isset($_POST['tel'])){
        $Talents_admin = new Ik_Talent_Admin();


        if(isset($_SESSION['ik_talent_qest_question_answer'])){
            $quest_answers = $_SESSION['ik_talent_qest_question_answer'];
        } else {
            $quest_answers = '';
        }

        $args = array(
            'role_id' => intval($_SESSION['ik_talent_role_selected']),
            'answers' => $quest_answers,
            'email' => sanitize_email($_POST['email']),
            'company' => sanitize_text_field($_POST['company']),
            'name' => sanitize_text_field($_POST['name']),
            'phone' => sanitize_text_field($_POST['tel']),
        );

        $output = $Talents_admin->requests->create($args); // returns a messge
    
    } else {
        $output['html'] = $Talents_admin->get_contact_form().$Talents_admin->navbar_questions(false);
    }
    wp_send_json($output);
    wp_die();
}
?>