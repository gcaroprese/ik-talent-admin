<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/* 
IK Talent Admin Data Talent Shortcode
Created: 06/08/2023
Last Update: 27/10/2023
Author: Gabriel Caroprese
*/

//Shortcode to get data from current post ID

function ik_talent_get_talent_data_shortcode($atts = [], $content = null, $tag = ''){
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $data_location = shortcode_atts(['type' => 'skills',], $atts, $tag);

    global $post;

    if(isset($post->ID)){
        $talent_data = new Ik_Talent_Admin();
        $location_id = absint($data_location['type']);
        switch ($data_location['type']) {
            case 'languages':
                $output = $talent_data->get_talent_data_list($post->ID, 'languages');
                break;
            case 'techstack':
                $output = $talent_data->get_talent_data_list($post->ID, 'techstack');
                break;
            case 'experience':
                $output = $talent_data->get_talent_data_list($post->ID, 'experience');
                break;    
            case 'courses':
                $output = $talent_data->get_talent_data_list($post->ID, 'courses');
                break;                       
            default:
                $output = $talent_data->get_talent_data_list($post->ID, 'skills'); //skills
                break;
        }
        
        return $output;
        
    } else {
        $return = '';
    }
}
add_shortcode('IK_TALENT_DATA', 'ik_talent_get_talent_data_shortcode');
