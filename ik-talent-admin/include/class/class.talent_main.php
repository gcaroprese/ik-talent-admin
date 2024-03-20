<?php
/*

Talents Admin | Class Ik_Talent_Admin
Created: 15/07/2023
Update: 27/10/2023
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}
 
class Ik_Talent_Admin{
    public $config;
    public $qtyListing;
    public $form_data;
    public $talents;
    public $skills;
    public $techstack;
    public $requests;
    public $languages;

    public function __construct() {
        $this->talents = new Ik_Talents();
        $this->skills = new Ik_Talent_Skills();
        $this->requests = new Ik_Talent_Requests();
        $this->techstack = new Ik_Talent_TechStack();
        $this->form_data = 'ik_talent_form_data';
        $this->config = 'ik_talent_config';
        $this->qtyListing = 30;
        $this->languages = array(
            1 => array('id' => '1' , 'name' => __('Spanish', 'ik_talent_admin'), 'popular' => true),
            2 => array('id' => '2' , 'name' => __('English', 'ik_talent_admin'), 'popular' => true),
            3 => array('id' => '3' , 'name' => __('French', 'ik_talent_admin'), 'popular' => true),
            4 => array('id' => '4' , 'name' => __('German', 'ik_talent_admin'), 'popular' => true),
            5 => array('id' => '5' , 'name' => __('Italian', 'ik_talent_admin'), 'popular' => true),
            6 => array('id' => '6' , 'name' => __('Portuguese', 'ik_talent_admin'), 'popular' => true),
            7 => array('id' => '7' , 'name' => __('Dutch', 'ik_talent_admin'), 'popular' => false),
            8 => array('id' => '8' , 'name' => __('Russian', 'ik_talent_admin'), 'popular' => false),
            9 => array('id' => '9' , 'name' => __('Chinese', 'ik_talent_admin'), 'popular' => true),
            10 => array('id' => '10' , 'name' => __('Japanese', 'ik_talent_admin'), 'popular' => false),
            11 => array('id' => '11' , 'name' => __('Arabic', 'ik_talent_admin'), 'popular' => false),
            12 => array('id' => '12' , 'name' => __('Hindi', 'ik_talent_admin'), 'popular' => false),
            13 => array('id' => '13' , 'name' => __('Korean', 'ik_talent_admin'), 'popular' => false),
            14 => array('id' => '14' , 'name' => __('Turkish', 'ik_talent_admin'), 'popular' => false),
            15 => array('id' => '15' , 'name' => __('Swedish', 'ik_talent_admin'), 'popular' => false),
            16 => array('id' => '16' , 'name' => __('Norwegian', 'ik_talent_admin'), 'popular' => false),
            17 => array('id' => '17' , 'name' => __('Danish', 'ik_talent_admin'), 'popular' => false),
            18 => array('id' => '18' , 'name' => __('Finnish', 'ik_talent_admin'), 'popular' => false),
            19 => array('id' => '19' , 'name' => __('Greek', 'ik_talent_admin'), 'popular' => false),
            20 => array('id' => '20' , 'name' => __('Polish', 'ik_talent_admin'), 'popular' => false),
            21 => array('id' => '21' , 'name' => __('Czech', 'ik_talent_admin'), 'popular' => false),
            22 => array('id' => '22' , 'name' => __('Hungarian', 'ik_talent_admin'), 'popular' => false),
            23 => array('id' => '23' , 'name' => __('Thai', 'ik_talent_admin'), 'popular' => false),
            24 => array('id' => '24' , 'name' => __('Hebrew', 'ik_talent_admin'), 'popular' => false),
            25 => array('id' => '25' , 'name' => __('Indonesian', 'ik_talent_admin'), 'popular' => false),
            26 => array('id' => '26' , 'name' => __('Malay', 'ik_talent_admin'), 'popular' => false),
            27 => array('id' => '27' , 'name' => __('Romanian', 'ik_talent_admin'), 'popular' => false),
            28 => array('id' => '28' , 'name' => __('Bulgarian', 'ik_talent_admin'), 'popular' => false),
            29 => array('id' => '29' , 'name' => __('Croatian', 'ik_talent_admin'), 'popular' => false),
            30 => array('id' => '30' , 'name' => __('Serbian', 'ik_talent_admin'), 'popular' => false),
            31 => array('id' => '31' , 'name' => __('Slovak', 'ik_talent_admin'), 'popular' => false),
            32 => array('id' => '32' , 'name' => __('Slovenian', 'ik_talent_admin'), 'popular' => false),
            33 => array('id' => '33' , 'name' => __('Estonian', 'ik_talent_admin'), 'popular' => false),
            34 => array('id' => '34' , 'name' => __('Latvian', 'ik_talent_admin'), 'popular' => false),
            35 => array('id' => '35' , 'name' => __('Lithuanian', 'ik_talent_admin'), 'popular' => false),
            36 => array('id' => '36' , 'name' => __('Georgian', 'ik_talent_admin'), 'popular' => false),
            37 => array('id' => '37' , 'name' => __('Armenian', 'ik_talent_admin'), 'popular' => false),
            38 => array('id' => '38' , 'name' => __('Bengali', 'ik_talent_admin'), 'popular' => false),
            39 => array('id' => '39' , 'name' => __('Tamil', 'ik_talent_admin'), 'popular' => false),
            40 => array('id' => '40' , 'name' => __('Telugu', 'ik_talent_admin'), 'popular' => false),
            41 => array('id' => '41' , 'name' => __('Urdu', 'ik_talent_admin'), 'popular' => false),
            42 => array('id' => '42' , 'name' => __('Punjabi', 'ik_talent_admin'), 'popular' => false),
            43 => array('id' => '43' , 'name' => __('Gujarati', 'ik_talent_admin'), 'popular' => false),
            44 => array('id' => '44' , 'name' => __('Marathi', 'ik_talent_admin'), 'popular' => false),
            45 => array('id' => '45' , 'name' => __('Kannada', 'ik_talent_admin'), 'popular' => false),
            46 => array('id' => '46' , 'name' => __('Malayalam', 'ik_talent_admin'), 'popular' => false),
            47 => array('id' => '47' , 'name' => __('Sinhala', 'ik_talent_admin'), 'popular' => false),
            48 => array('id' => '48' , 'name' => __('Nepali', 'ik_talent_admin'), 'popular' => false),
            49 => array('id' => '49' , 'name' => __('Burmese', 'ik_talent_admin'), 'popular' => false),
            50 => array('id' => '50' , 'name' => __('Khmer', 'ik_talent_admin'), 'popular' => false)
        );
    }

    //If not exist create the tables
    public function create_db_tables(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
        CREATE TABLE IF NOT EXISTS ".$this->talents->get_table_name()." (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            role_id bigint(20) NOT NULL,
            skill_ids longtext NOT NULL,
            date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            date_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name varchar(255) NOT NULL,
            lastname varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(22) DEFAULT '-' NOT NULL,
            cv_file longtext NOT NULL,
            details longtext NOT NULL,
            languages longtext NOT NULL,
            techstack_ids longtext NOT NULL,
            image_id bigint(20) NOT NULL,
            profile_data longtext NOT NULL,
            active int(2)  DEFAULT '0' NOT NULL,
            UNIQUE KEY id (id)
        ) ".$charset_collate.";
        CREATE TABLE ".$this->skills->get_table_name()." (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            role_id bigint(20) NOT NULL,
            name varchar(100) NOT NULL,
            date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            date_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            popular int(1) DEFAULT '0' NOT NULL,
            UNIQUE KEY id (id)
        ) ".$charset_collate.";
        CREATE TABLE ".$this->skills->get_table_name('role')." (
            id_role bigint(20) NOT NULL AUTO_INCREMENT,
            role_name varchar(100) NOT NULL,
            description longtext NOT NULL,
            order_n int(3)  DEFAULT '0' NOT NULL,
            date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            date_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            UNIQUE KEY id (id_role)
        ) ".$charset_collate.";
        CREATE TABLE ".$this->requests->get_table_name()." (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            role_id bigint(20) NOT NULL,
            request_data longtext NOT NULL,
            company varchar(255) NOT NULL,
            contact_name varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(22) DEFAULT '-' NOT NULL,
            submited_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            UNIQUE KEY id (id)
        ) ".$charset_collate.";
        CREATE TABLE ".$this->techstack->get_table_name()." (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            date_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            popular int(1) DEFAULT '0' NOT NULL,
            UNIQUE KEY id (id)
        ) ".$charset_collate.";";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    // method to add pagination to lists
    public function paginator($qty, $qtyToList, $page_url){
        $qty = intval($qty);
        $qtyToList = $qtyToList;
        $page_url = sanitize_url($page_url);
        $page = (isset($_GET['paged'])) ? intval($_GET['paged']) : 1;
        $page = ($page == 0) ? 1 : $page;
        $output = '';

        if ($qty > 0){
            $data_dataSubstr = $qty / $qtyToList;
            $total_pages = intval($data_dataSubstr);
                
                if (is_float($data_dataSubstr)){
                    $total_pages = $total_pages + 1;
                }
            
            if ($qty > $qtyToList){
                
                if ($total_pages > 1){
                    $output .= '<div class="ik_talent_pages">';
                    
                    //Enable certain page ids to show
                    $mitadlist = intval($total_pages/2);
                    
                    $pagesToShow[] = 1;
                    $pagesToShow[] = 2;
                    $pagesToShow[] = 3;
                    $pagesToShow[] = $total_pages;
                    $pagesToShow[] = $total_pages - 1;
                    $pagesToShow[] = $total_pages - 2;
                    $pagesToShow[] = $mitadlist - 2;
                    $pagesToShow[] = $mitadlist - 1;
                    $pagesToShow[] = $mitadlist;
                    $pagesToShow[] = $mitadlist + 1;
                    $pagesToShow[] = $mitadlist + 2;
                    $pagesToShow[] = $page+3;
                    $pagesToShow[] = $page+2;
                    $pagesToShow[] = $page+1;
                    $pagesToShow[] = $page;
                    $pagesToShow[] = $page-1;
                    $pagesToShow[] = $page-2;
                    
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $show_page = false;
                        
                        //Showing enabled pages
                        if (in_array($i, $pagesToShow)) {
                            $show_page = true;
                        }
                        
                        if ($show_page == true){
                            if ($page == $i){
                                $PageNActual = 'actual_page';
                            } else {
                                $PageNActual = "";
                            }
                            $output .= '<a class="ik_listar_page_data '.$PageNActual.'" href="'.$page_url.'&list='.$i.'">'.$i.'</a>';
                        }
                    }
                    $output .= '</div>';
                }
            } 	            
        }
        return $output;
    }

    //method to show talent's profile image uploader
    public function profile_image_uploader($image_id = 0){
        $image_id = intval($image_id);
        $uploader_output = '<div id="ik_talent_edit_profile_image">
        <input type="hidden" id="ik_talent_upload_profile_image_id" name="image_id" value="'.$image_id.'">';

        //I check if file exists
        $file_name = get_post_meta( $image_id, '_wp_attached_file', true);

        if($file_name){
            $url = wp_upload_dir()['baseurl'].'/'.$file_name;
        }
        
        //if image to be shown showns image with delete button
        if(isset($url)){
            $uploader_output .= '<img id="ik_talent_edit_preview_image" src="'.$url.'" />
            <input type="button" class="button-primary" value="'.__( 'Change Image ', 'ik_talent_admin' ).'" id="ik_talent_upload_profile_img"/>
            <input type="button" class="button-primary btn_cancel" value="'.__( 'Delete', 'ik_talent_admin' ).'" id="ik_talent_delete_profile_img">';
        } else {
            $uploader_output .= '<input type="button" class="button-primary" value="'.__( 'Upload Image ', 'ik_talent_admin' ).'" id="ik_talent_upload_profile_img"/>';
        }
        $uploader_output .= '</div>';

        return $uploader_output;
    }

    //method to return language selector
    public function get_language_selector($ids_selected = array()){
        $ids_selected = (is_serialized($ids_selected)) ? maybe_unserialize($ids_selected) : $ids_selected;
        $selected_data = (is_array($ids_selected)) ? count($ids_selected) : 0;
        $existing_languages = $this->languages;
        //order by name
        usort($existing_languages, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });     
        
        $select_options = '<div id="language_selectors"><div class="language_selectors_content">';

        if($selected_data > 0 && is_array($ids_selected)){
            $first_option = true;
            foreach($ids_selected as $id_selected){
                $select_options .= '<div class="language_selectors_wrapper"><select required name="languages[]" class="ik_talent_language_select"><option value="0">'. __( 'Select Language', 'ik_talent_admin' ).'</option>';
                if ($existing_languages){
                    $id_selected = intval($id_selected);
                    foreach($existing_languages as $existing_language){
                        $selected = ($id_selected == $existing_language['id']) ? 'selected' : '';
                        $select_options .= '<option '.$selected.' value="'.$existing_language['id'].'">'.$existing_language['name'].'</option>';
                    }
                }
                $select_options .= '</select>';                
                if($first_option){
                    $first_option = false;
                } else {
                    $select_options .= '<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>';
                }
                $select_options .= '</div>';
            }


        } else {
            $select_options .= '<div class="language_selectors_wrapper"><select required name="languages[]" class="ik_talent_language_select select-w-delete"><option value="0">'. __( 'Select language', 'ik_talent_admin' ).' *</option>';
            if ($existing_languages){
                foreach($existing_languages as $existing_language){
                    $select_options .= '<option value="'.$existing_language['id'].'">'.$existing_language['name'].'</option>';
                }
            }
            $select_options .= '</select></div>';
        }

        $select_options .= '</div>';

        if ($existing_languages){
            $select_options .= '<a href="#" id="ik_talent_add_language_field" class="button">'. __( 'Add Language', 'ik_talent_admin' ).'</a>';

        }
        $select_options .= '</div>';

        return $select_options;    
    }

    //method to show talent's experience fields
    public function get_profile_fields($data_type = 'experience', $data_values = NULL){
        $output_fields = '';
        $counterExperience = 0;
        $counterCourses = 0;

        $data_type = ($data_type == 'experience') ? 'experience' : 'courses';

        if($data_type == 'experience'){

            if(is_serialized($data_values)){
                $profile_data = maybe_unserialize($data_values);
                if(isset($profile_data['experience'])){
                    $experiences = $profile_data['experience'];
                    $output_fields = '';
                    foreach($experiences as $experience){
                        $month_checked = ($experience['company_time_type'] == 'month') ? 'checked' : '';
                        $year_checked = ($experience['company_time_type'] == 'year') ? 'checked' : '';
                        $output_fields .= '
                        <div class="ik_talent_data_profile_container">
                            <label for="company_text">'.__( 'Company / organization Name', 'ik_talent_admin' ).'</label>
                            <input type="text" required="" name="company_name['.$counterExperience.']" value="'.$experience['company_name'].'">
                            <a href="#" class="ik_talent_delete_field button">'.__( 'Delete', 'ik_talent_admin' ).'</a><a href="#" class="ik_talent_open_field button"><span class="dashicons dashicons-arrow-up"></span></a>
                            <div class="ik_talent_data_profile_container_inner">
                                <label for="job_title_text">'.__( 'Job Title', 'ik_talent_admin' ).'</label>
                                <input type="text" required="" name="company_job['.$counterExperience.']" value="'.$experience['company_job'].'">
                                <label for="description_text">'.__( 'Description', 'ik_talent_admin' ).'</label>
                                <textarea name="company_description['.$counterExperience.']">'.$experience['company_description'].'</textarea>
                                <label for="time_experience_text">'.__( 'Time of Experience', 'ik_talent_admin' ).'</label>
                                <input type="number" pattern="^(?:[1-9][0-9]{0,2}|300)$" required="" name="company_time['.$counterExperience.']" value="'.$experience['company_time'].'">
                                <input type="radio" required="" name="company_time_type['.$counterExperience.']" value="month" '.$month_checked.'>'.__( 'Month/s', 'ik_talent_admin' ).'<input type="radio" required="" name="company_time_type['.$counterExperience.']" '.$year_checked.' value="year">'.__( 'Year/s', 'ik_talent_admin' ).'
                            </div>
                        </div>';
                        $counterExperience = $counterExperience + 1;
                    }
                }
            }
            if($counterExperience == 0){
                $output_fields = '
                    <div class="ik_talent_data_profile_container">
                        <label for="company_text">'.__( 'Company / organization Name', 'ik_talent_admin' ).'</label>
                        <input type="text" required="" name="company_name[0]">
                        <a href="#" class="ik_talent_delete_field button">'.__( 'Delete', 'ik_talent_admin' ).'</a><a href="#" class="ik_talent_open_field button"><span class="dashicons dashicons-arrow-up"></span></a>
                        <div class="ik_talent_data_profile_container_inner">
                            <label for="job_title_text">'.__( 'Job Title', 'ik_talent_admin' ).'</label>
                            <input type="text" required="" name="company_job[0]">
                            <label for="description_text">'.__( 'Description', 'ik_talent_admin' ).'</label>
                            <textarea name="company_description[0]"></textarea>
                            <label for="time_experience_text">'.__( 'Time of Experience', 'ik_talent_admin' ).'</label>
                            <input type="number" pattern="^(?:[1-9][0-9]{0,2}|300)$" required="" name="company_time[0]">
                            <input type="radio" required="" name="company_time_type[0]" value="month">'.__( 'Month/s', 'ik_talent_admin' ).'<input type="radio" required="" name="company_time_type[0]" value="year">'.__( 'Year/s', 'ik_talent_admin' ).'
                        </div>
                    </div>';
            }
        } else {
            if(is_serialized($data_values)){
                $profile_data = maybe_unserialize($data_values);
                if(isset($profile_data['courses'])){
                    $courses = $profile_data['courses'];
                    $output_fields = '';
                    foreach($courses as $course){
                        $output_fields .= '
                        <div class="ik_talent_data_profile_container">            
                            <label for="course_text">'.__( 'Course Title / Certification', 'ik_talent_admin' ).'</label>
                            <input type="text" required="" name="course_name['.$counterCourses.']" value="'.$course['course_name'].'">
                            <a href="#" class="ik_talent_delete_field button">'.__( 'Delete', 'ik_talent_admin' ).'</a><a href="#" class="ik_talent_open_field button"><span class="dashicons dashicons-arrow-up"></span></a>
                            <div class="ik_talent_data_profile_container_inner">
                                <label for="institution_text">'.__( 'Institution', 'ik_talent_admin' ).'</label>
                                <input type="text" name="course_place['.$counterCourses.']" value="'.$course['course_place'].'">
                                <label for="description_text">'.__( 'Description', 'ik_talent_admin' ).'</label>
                                <textarea name="course_description['.$counterCourses.']">'.$course['course_description'].'</textarea>
                                <label for="graduation_text">'.__( 'Graduation Year', 'ik_talent_admin' ).'</label>
                                <input type="number" pattern="^(?:[1-9][0-9]{0,2}|9999)$" name="course_graduation['.$counterCourses.']" value="'.$course['course_graduation'].'">
                            </div>
                        </div>';
                        $counterCourses = $counterCourses + 1;
                    }
                }          
            }
            if($counterCourses == 0){
                $output_fields = '
                        <div class="ik_talent_data_profile_container">            
                        <label for="course_text">'.__( 'Course Title / Certification', 'ik_talent_admin' ).'</label>
                        <input type="text" required="" name="course_name[0]">
                        <a href="#" class="ik_talent_delete_field button">'.__( 'Delete', 'ik_talent_admin' ).'</a><a href="#" class="ik_talent_open_field button"><span class="dashicons dashicons-arrow-up"></span></a>
                        <div class="ik_talent_data_profile_container_inner">
                            <label for="institution_text">'.__( 'Institution', 'ik_talent_admin' ).'</label>
                            <input type="text" name="course_place[0]">
                            <label for="description_text">'.__( 'Description', 'ik_talent_admin' ).'</label>
                            <textarea name="course_description[0]"></textarea>
                            <label for="graduation_text">'.__( 'Graduation Year', 'ik_talent_admin' ).'</label>
                            <input type="number" pattern="^(?:[1-9][0-9]{0,2}|9999)$" name="course_graduation[0]">
                        </div>
                    </div>';
            }
        }

        return $output_fields;
    }

    //method to get experience or courses list for frontend by post id
    public function get_profile_data_list($post_id, $data_type = 'experience'){
        $post_id = intval($post_id);
        $output = '-';
        $talent = $this->talents->get_by_post_id($post_id);

        if($talent){
            $data_values = $talent->profile_data;
            if($data_type == 'experience'){

                if(is_serialized($data_values)){
                    $profile_data = maybe_unserialize($data_values);
                    if(isset($profile_data['experience'])){
                        $experiences = $profile_data['experience'];
                        $output = '';
                        foreach($experiences as $experience){
                            $time_type = ($experience['company_time_type'] == 'month') ? 'month' : 'year';
                            $output .= $experience['company_name'] .' - '.$experience['company_job'].' - '.$experience['company_time'].' '.$time_type.'<br />';
                        }
                    }
                }
            } else {
                if(is_serialized($data_values)){
                    $profile_data = maybe_unserialize($data_values);
                    if(isset($profile_data['courses'])){
                        $courses = $profile_data['courses'];
                        $output = '';
                        foreach($courses as $course){
                            $output .= $course['course_name'].' - '.$course['course_place'].' '.$course['course_graduation'].'<br />';
                        }
                    }          
                }
            }
        }
        return $output;
    }


    //method to get talent's experience data from serialized array
    public function get_profile_data($profile_data_array = array(), $type = 'experience'){
        //I make sure first if it's serialized
        $profile_data_array = (is_serialized($profile_data_array)) ? maybe_unserialize($profile_data_array) : $profile_data_array;

        //I make sure is not an array
        if(is_array($profile_data_array)){


            if($type == 'experience'){
                //get experience
                if(isset($profile_data_array['experience'])){
                    foreach($profile_data_array['experience'] as $profile_data_experience){
                        $profile_data[] = array(
                            'company_name'          => $profile_data_experience['company_name'],
                            'company_job'           => $profile_data_experience['company_job'],
                            'company_description'   => $profile_data_experience['company_description'],
                            'company_time'          => $profile_data_experience['company_time'],
                            'company_time_type'     => $profile_data_experience['company_time_type'],                    
                        );
                    }
                }
            } else {
                if(isset($profile_data_array)){
                    foreach($profile_data_array['courses'] as $profile_data_courses){
                        $profile_data[] = array(
                            'course_name'       => $profile_data_courses['course_name'],
                            'course_place'      => $profile_data_courses['course_place'],
                            'course_description'=> $profile_data_courses['course_description'],
                            'course_graduation' => $profile_data_courses['course_graduation'],                  
                        );
                    }
                }
            }

            if(isset($profile_data)){
                return $profile_data;
            }    
        }
        
        return false;
    }

    //Get language data from array of ids
    public function get_languages_data($lang_array){
        //I make sure first if it's serialized
        $lang_array = (is_serialized($lang_array)) ? maybe_unserialize($lang_array) : $lang_array;

        //I make sure is an array
        if(is_array($lang_array)){
            foreach($lang_array as $lang_id){
                $languages[] = $this->languages[$lang_id];
            } 

            return $languages;
        }
        
        return false;
        
    }

    //method to show data about talent ID and skills
    public function list_by_talent_id($talent_id){
        $talent_id = intval($talent_id);
        $talent = $this->talents->get_by_id($talent_id);

        if($talent){

            $active = (intval($talent->active) == 1) ? __( 'Active', 'ik_talent_admin' ) : __( 'Inactive', 'ik_talent_admin' );

            $talent_data = array(
                'id' => $talent->id,    
                'role' => $this->skills->get_role_by_id($talent->role_id),    
                'skills' => $this->skills->get_skills_data($talent->skill_ids),    
                'languages' => $this->get_languages_data($talent->languages),    
                'techstack' => $this->techstack->get_data($talent->techstack_ids),    
                'experience' => $this->get_profile_data($talent->profile_data, 'experience'),    
                'courses' => $this->get_profile_data($talent->profile_data, 'courses'),    
                'date_created' => $talent->date_created,    
                'date_updated' => $talent->date_updated,    
                'name' => $talent->name,    
                'lastname' => $talent->lastname,
                'email' => $talent->email,    
                'phone' => $talent->phone,    
                'cv_file' => wp_upload_dir()['baseurl'].'/'.IK_TALENT_CVS_FOLDER_DIR.'/'.$talent->cv_file,    
                'details' => $talent->details,    
                'active' => $active,    
            );

            return $talent_data;
        }

        return false;
    }

    //Return array of names based on 
    private function get_names_list($dataset, $name = 'Skills', $object_type = true, $box_html = true){
        $name = sanitize_text_field($name);

        if(is_array($dataset) || is_object($dataset) ){
            $data_list = ($box_html) ? '<h4>'.__( $name, 'ik_talent_admin' ).'</h4>' : '';

            foreach($dataset as $data){
                $name = ($object_type) ? $data->name : $data['name'];

                $data_list .= ($box_html) ? '<div class="data_box">'.esc_html($name).'</div>' : esc_html($name).'<br />';

            }                                     

        } else {
            $data_list = '-';
        }

        return $data_list;

    }

    //method to show data about talent ID and skills
    public function show_modal_by_talent_id($talent_id){
        $talent_id = intval($talent_id);
        $talent_list = $this->list_by_talent_id($talent_id);

        if($talent_list){

            $skills_list = $this->get_names_list($talent_list['skills']);
            $languages_list = $this->get_names_list($talent_list['languages'], 'Languages', false);
            $techstack_list = $this->get_names_list($talent_list['techstack'], 'Tech Stack');

            $output = '<div class="modal" id="ik_talent_modal" tabindex="-1" role="dialog" aria-labelledby="ik_talent_modallLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="ik_talent_modalLabel">'.__( 'Talent Info', 'ik_talent_admin' ).' #'.$talent_list['id'].'</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>'.__( 'Name', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['name'].'</div>
                                        <h4>'.__( 'Last Name', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['lastname'].'</div>
                                        <h4>'.__( 'Role', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['role']->role_name.'</div>
                                        <h4>'.__( 'Email', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['email'].'</div>
                                        <h4>'.__( 'Phone', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['phone'].'</div>
                                    '.$skills_list.'
                                    </div>
                                    <div class="col-md-6">
                                    '.$techstack_list.'
                                    '.$languages_list.'
                                        <h4>'.__( 'Active?', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['active'].'</div>
                                        <h4>'.__( 'Details', 'ik_talent_admin' ).'</h4>
                                        <div class="data_info">'.$talent_list['details'].'</div>
                                        <div class="data_info">
                                            <a class="button" href="'.$talent_list['cv_file'].'" target="_blank">'.__( 'Download Resume', 'ik_talent_admin' ).'</a>
                                        </div>
                                    </div>
                                </div>                                                                                                                                                                                                                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            return $output;

        }

        return false;
    }

    //method to get a table with full data of talents
    public function get_table_all_talents(){
    
        $output_table = '<table id="table_export" style="display:none">
        <tr>
            <th>'.__( 'ID', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Name', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Last Name', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Phone', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Email', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Role', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Skills', 'ik_talent_admin' ).'</th>
            <th>'.__( 'CV URL', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Laguages', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Tech Stack', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Profile URL', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Experience', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Courses', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Details', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Active', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Date Created', 'ik_talent_admin' ).'</th>
            <th>'.__( 'Date Updated', 'ik_talent_admin' ).'</th>
        </tr>';


        $talents = $this->talents->get_talents();

        if($talents){
            foreach($talents as $talent){

                $talent_data = $this->list_by_talent_id($talent->id);

                if(is_array($talent_data['skills'])){
                    $skills_list = '';
    
                    foreach($talent_data['skills'] as $skill){
                        $skills_list .= $skill->name.', ';
                    }    
                    $skills_list = substr($skills_list, 0, -2);    

                } else {
                    $skills_list = '';
                }

                if(is_array($talent_data['languages'])){
                    $language_list = '';
    
                    foreach($talent_data['languages'] as $language){
                        $language_list .= $language['name'].', ';
                    }    
                    $language_list = substr($language_list, 0, -2);    
                } else {
                    $language_list = '';
                }

                $techstack_list = '';
                if(isset($talent_data['techstack'])){
                    if(is_array($talent_data['techstack'])){
                        $techstack_list = '';
        
                        foreach($talent_data['techstack'] as $techtack){
                            $techstack_list .= $techtack->name.', ';
                        }  
                        $techstack_list = substr($techstack_list, 0, -2);    
                    }
                }

                $experience_list = '';
                if(isset($talent_data['experience'])){
                    if(is_array($talent_data['experience'])){
                        $experience_list = '';
        
                        foreach($talent_data['experience'] as $experience){
                            $experience_list .= __( 'Company', 'ik_talent_admin' ).': '.$experience['company_name'].' ';
                            $experience_list .= __( 'Job Title', 'ik_talent_admin' ).': '.$experience['company_job'].' ';
                            $experience_list .= __( 'Description', 'ik_talent_admin' ).': '.$experience['company_description'].' ';
                            $experience_list .= __( 'Time', 'ik_talent_admin' ).': '.$experience['company_time'].' '.$experience['company_time_type'] . ' / '; 
                        }  
                        $experience_list = substr($experience_list, 0, -2);  
                    }
                }

                if(is_array($talent_data['courses'])){
                    $courses_list = '';
    
                    foreach($talent_data['courses'] as $course){
                        $courses_list .= __( 'Course', 'ik_talent_admin' ).': '.$course['course_name'].' ';
                        $courses_list .= __( 'Intitution', 'ik_talent_admin' ).': '.$course['course_place'].' ';
                        $courses_list .= __( 'Description', 'ik_talent_admin' ).': '.$course['course_description'].' ';
                        $courses_list .= __( 'Course Graduation', 'ik_talent_admin' ).': '.$course['course_graduation'] . ' / ';   
                    }   
                    $courses_list = substr($courses_list, 0, -2);  
 
                } else {
                    $courses_list = '';
                }

                $output_table .= '<tr>
                    <td>'.$talent->id.'</td>
                    <td>'.$talent->name.'</td>
                    <td>'.$talent->lastname.'</td>
                    <td>'.$talent->phone.'</td>
                    <td>'.$talent->email.'</td>
                    <td>'.$talent_data['role']->role_name.'</td>
                    <td>'.$skills_list.'</td>
                    <td>'.$talent_data['cv_file'].'</td>
                    <td>'.$language_list.'</td>
                    <td>'.$techstack_list.'</td>
                    <td>'.get_permalink($talent->post_id).'</td>
                    <td>'.$experience_list.'</td>
                    <td>'.$courses_list.'</td>
                    <td>'.$talent->details.'</td>
                    <td>'.$talent_data['active'].'</td>
                    <td>'.$talent->date_created.'</td>
                    <td>'.$talent->date_updated.'</td>
                </tr>';  
            }          
        }

        $output_table .= '</table>';

        return $output_table; 
    }

    //method to filter and return type of question for quiz
    public function sanitize_type_of_question($type_question){
        $type_question = sanitize_text_field($type_question);

        switch ($type_question) {
            case 'skills':
                $type = 'skills';
                break;
            case 'languages':
                $type = 'languages';
                break;
            case 'techstack':
                $type = 'techstack';
                break;
            case 'multiple':
                $type = 'multiple';
                break;
            default:
                //textarea
                $type = 'textarea';
                break;
        }

        return $type_question;
    }

    //method to return options pages to select terms or privacy for quizzes
    public function get_pages_select($selected_page_id, $name_attr){
        $selected_page_id = intval($selected_page_id);
        $name_attr = sanitize_text_field($name_attr);
        $args = array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        );

        $options_data_list = '<select required name="'.$name_attr.'">';
        $options_data_list .= '<option  value="0">'.__( 'Do not show', 'ik_talent_admin' ).'</option>';
        $option_data = new WP_Query( $args );
        while ( $option_data->have_posts() ) : $option_data->the_post();
            $selected_page = ($selected_page_id == get_the_id()) ? 'selected' : '';
            $options_data_list .= '<option '.$selected_page.' value="'.get_the_id().'">'.get_the_title().'</option>';
        endwhile;
        $options_data_list .= '</select>';

        wp_reset_query();
        
        return $options_data_list;
    }

    //method to return input radio with the different roles available to start the questions
    public function get_roles_selection(){
        $roles = $this->skills->get_roles();

        if($roles){
            $roles_inputs = '';
            foreach($roles as $role){
                $roles_inputs .= '
                <label>
                    <input type="radio" name="choice" value="'.$role->id_role.'">
                    <div class="ik_talent_content_option">
                        <span class="ik_talent_content_option_text">'.$role->role_name.'</span>
                        <div class="ik_talent_content_description">'.$role->description.'</div>
                    </div>
                </label>';
            }
        } else {
            $roles_inputs = 'Options not available.';
        }

        $output_html = '<div class="contents">
                    <div class="init_message">'.__( 'Thanks for your interest! Before we get started, we would like to ask a few questions to better understand your business needs.', 'ik_talent_admin' ).'</div>
                        <h2>'.__( 'What role would you like to hire?', 'ik_talent_admin' ).'</h2>
                        '.$roles_inputs.'
                    </div>
                </div>';
        $output_html .= '<button id="ik_talent_question_start">'.__( 'Get Started', 'ik_talent_admin' ).'</button>';  
        
        $output['html'] = $output_html;
        
        return $output;
    }

    //method to show navbar for the questions to find the right professional
    public function navbar_questions($nextbtn = true){

        $output = '';

        $next_button_output = ($nextbtn !== false) ? '<button id="ik_talent_question_next">'.__( 'Next', 'ik_talent_admin' ).' <span class="dashicons dashicons-arrow-right-alt2"></span></button>' : '';

        $output .= '<div class="ik_talent_questions_navbar">
            <button id="ik_talent_question_back"><span class="dashicons dashicons-arrow-left-alt2"></span> '.__( 'Back', 'ik_talent_admin' ).'</button>
            '.$next_button_output.'
        </div>';  
        
        return $output;
    }


    //method to send the questions to find the right professional
    public function send_questions($role_id = 0, $stage_id = 0){
        $role_id = intval($role_id);
        $stage_id = (intval($stage_id) > 0) ? intval($stage_id) : 0;

        $questions = get_option('ik_talent_questions_'.$role_id, true);

        //confirm data and quiz stage exists
        if(is_array($questions)){
            if(isset($questions[$stage_id])){

                //get data for question
                $question_title = (isset($questions[$stage_id]['question'])) ? $questions[$stage_id]['question'] : '';
                $type_question = $this->sanitize_type_of_question($questions[$stage_id]['type_question']);
                $saved_comment = (isset($_SESSION['ik_talent_qest_answer_comment'][$stage_id])) ? sanitize_textarea_field($_SESSION['ik_talent_qest_answer_comment'][$stage_id]) : '';
                $saved_comment = str_replace('\\', '', $saved_comment);

                //depending on the type of question
                if($type_question == 'multiple'){
                    $selected_radio = (isset($_SESSION['ik_talent_qest_answer'][$stage_id])) ? $_SESSION['ik_talent_qest_answer'][$stage_id] : '';
                
                    $possible_answers = '<div class="multiple-choice">';
                    foreach($questions[$stage_id]['answers'] as $answer_text){
                        $possible_answers .= '
                        <label>
                            <input type="radio" name="choice" value="'.$answer_text.'">
                            <div class="ik_talent_content_option">
                                <span class="ik_talent_content_option_text">'.$answer_text.'</span>
                            </div>
                        </label>';
                    }
                    $possible_answers .= '</div>';

                } else if($type_question == 'skills'){
                    $selected_ids = (isset($_SESSION['ik_talent_qest_answer'][$stage_id])) ? $_SESSION['ik_talent_qest_answer'][$stage_id] : array();
                 
                    $favorite_skills = $this->skills->get_popular_select_list($role_id);
                    $ik_talent_select_available = $this->skills->get_skills_by_role($role_id);
                    $possible_answers = '            
                                        <div class="select_data-choice_wrapper">
                                            <div class="select_data-choice_input">
                                                <input type="text" id="ik_talent_select_data_input" autocomplete="none" placeholder="'.__( 'Desired areas of expertise', 'ik_talent_admin' ).'" value="">
                                                <div id="ik_talent_select_data_selector"></div>
                                                <div class="select_data_selected_area"></div>
                                            </div>
                                            <div id="ik_talent_select_data_popular_choice">
                                                <h5>'.__( 'Popular Skills:', 'ik_talent_admin' ).'</h5>
                                                '.$favorite_skills.'
                                            </div>
                                        </div>';
                } else if($type_question == 'languages'){

                    $ik_talent_select_available = $this->languages;

                    //I get the popular languages to list
                    $popular_languages = '';
                    // get popular languages (popular => true)
                    foreach ($this->languages as $id => $lang) {
                        if ($lang['popular']) {
                            $popular_languages .= '<button type="button" class="" data_name="popular-language" data_value="'.$lang['id'].'"><span class="symbol_btn">+</span>'.$lang['name'].'</button>';
                        }
                    }

                    $selected_ids = (isset($_SESSION['ik_talent_qest_answer'][$stage_id])) ? $_SESSION['ik_talent_qest_answer'][$stage_id] : array();
                   
                    $possible_answers = '            
                                        <div class="select_data-choice_wrapper">
                                            <div class="select_data-choice_input">
                                                <input type="text" id="ik_talent_select_data_input" autocomplete="none" placeholder="'.__( 'Languages you speak', 'ik_talent_admin' ).'" value="">
                                                <div id="ik_talent_select_data_selector"></div>
                                                <div class="select_data_selected_area"></div>
                                            </div>
                                            <div id="ik_talent_select_data_popular_choice">
                                                <h5>'.__( 'Popular languages:', 'ik_talent_admin' ).'</h5>
                                                '.$popular_languages.'
                                            </div>
                                        </div>';
                } else if($type_question == 'techstack'){
                    $selected_ids = (isset($_SESSION['ik_talent_qest_answer'][$stage_id])) ? $_SESSION['ik_talent_qest_answer'][$stage_id] : array();
                 
                    $favorite_techstack = $this->techstack->get_popular_select_list();
                    $ik_talent_select_available = $this->techstack->get_techstacks();
                    $possible_answers = '            
                                        <div class="select_data-choice_wrapper">
                                            <div class="select_data-choice_input">
                                                <input type="text" id="ik_talent_select_data_input" autocomplete="none" placeholder="'.__( 'Desired areas of expertise', 'ik_talent_admin' ).'" value="">
                                                <div id="ik_talent_select_data_selector"></div>
                                                <div class="select_data_selected_area"></div>
                                            </div>
                                            <div id="ik_talent_select_data_popular_choice">
                                                <h5>'.__( 'Popular Skills:', 'ik_talent_admin' ).'</h5>
                                                '.$favorite_techstack.'
                                            </div>
                                        </div>';
                } else {
                    //textarea
                    $saved_text = (isset($_SESSION['ik_talent_qest_answer'][$stage_id])) ? sanitize_textarea_field($_SESSION['ik_talent_qest_answer'][$stage_id]) : '';
                    $saved_text = str_replace('\\', '', $saved_text);

                    $possible_answers = '<div class="textarea_wrapper">
                                        <textarea name="answer">'.$saved_text.'</textarea>
                                    </div>';
                }

                $text_field = ($questions[$stage_id]['text_field']) ? '<div class="textarea_wrapper"><textarea id="ik_talent_form_data_comments" name="comments">'.$saved_comment.'</textarea></div>' : '';


                //return content
                $output_html = '<div class="contents">
                                <h2>'.esc_html($question_title).'</h2>
                                '.$possible_answers.$text_field.'
                            </div>
                        </div>';      
                //$output .= __( 'Select a valid option.', 'ik_talent_admin' );
                $output_html .= $this->navbar_questions();
                $output['html'] = $output_html;

                //if selectable data available
                if(isset($ik_talent_select_available)){
                    $output['select_data'] = $ik_talent_select_available;
                }
                //if radio input in session and answered before
                if(isset($selected_radio)){
                    $output['selected_radio'] = $selected_radio;
                }
                //textarea comment in session and answered before
                if(isset($saved_comment)){
                    $output['saved_comment'] = $saved_comment;
                }
                //selected ids comment in session and answered before
                if(isset($selected_ids)){
                    $output['selected_ids'] = $selected_ids;
                }
                //textarea data in session and answered before
                if(isset($saved_text)){
                    $output['saved_text'] = $saved_text;
                }
                
                return $output;
            }
        }

        //Questionaire not existing or end of quizz
        return false;

    }

    //method to validate question and if correct increment stage id 
    public function validate_answers($role_id, $stage_id, $answer){
        $role_id = intval($role_id);
        $stage_id = (intval($stage_id) > 0) ? intval($stage_id) : 0;

        $questions = get_option('ik_talent_questions_'.$role_id, true);

        //if question data exists
        if(isset($questions[$stage_id])){
            //get the type of question to analize if correct anc check if textarea enabled
            $type_question = $this->sanitize_type_of_question($questions[$stage_id]['type_question']);
            $question_asked = $questions[$stage_id]['question'];
            $possible_answers = $questions[$stage_id]['answers'];
            $text_field = rest_sanitize_boolean($questions[$stage_id]['text_field']);

            $text_field_to_save = ($text_field) ? sanitize_textarea_field($answer['comments']) : '';
            $text_field_to_save = str_replace('\\', '', $text_field_to_save);

            //validate depending on the type of question
            if($type_question == 'multiple'){
                $answer_to_validate = sanitize_text_field($answer['radio_value']);
                $answer_to_validate = str_replace('\\', '', $answer_to_validate);
                foreach($possible_answers as $possible_answer){
                    //answer is valid an exists
                    if($possible_answer == $answer_to_validate){
                        $answer_validated = $possible_answer;
                        $data_raw = $answer_validated;
                    }
                }
            } else if($type_question == 'skills'){
                $selected_skills_ids = $answer['selected_values'];

                //I check the skills selected based on the existing ones for the role
                $existing_skills = $this->skills->get_skills_by_role($role_id);
                if(is_array($selected_skills_ids) && $existing_skills){
                    $data_raw = '';
                    foreach($existing_skills as $existing_skill){
                        //if selected skill is valid
                        if (in_array($existing_skill->id, $selected_skills_ids)){
                            $answer_validated[] = $existing_skill->id;
                            $data_raw .= '-'.$existing_skill->name;
                        }
                    }
                }
            } else if($type_question == 'languages'){
                $selected_language_ids = $answer['selected_values'];
                //I check the skills selected based on the existing ones for the role
                if(is_array($selected_language_ids)){
                    $data_raw = '';
                    foreach($this->languages as $language){
                        //if selected skill is valid
                        if (in_array($language['id'], $selected_language_ids)){
                            $answer_validated[] = $language['id'];
                            $data_raw .= '-'.$language['name'];
                        }
                    }
                }
            } else if($type_question == 'techstack'){
                $selected_techstack_ids = $answer['selected_values'];

                //I check the skills selected based on the existing ones for the role
                $existing_techstacks = $this->techstack->get_techstacks($role_id);
                if(is_array($selected_techstack_ids) && $existing_techstacks){
                    $data_raw = '';
                    foreach($existing_techstacks as $existing_techstack){
                        //if selected skill is valid
                        if (in_array($existing_techstack->id, $selected_techstack_ids)){
                            $answer_validated[] = $existing_techstack->id;
                            $data_raw .= '-'.$existing_techstack->name;
                        }
                    }
                }
            } else {
                //validate text area
                $answer_validated = sanitize_textarea_field($answer['answerInputvalue']);
                $answer_validated = str_replace('\\', '', $answer_validated);
            
                $data_raw = $answer_validated;
            }

            if(isset($answer_validated)){
                //save question answered data without repetitions
                if(isset($_SESSION['ik_talent_qest_question_answer'][$stage_id])){
                    $_SESSION['ik_talent_qest_question_answer'][$stage_id] = $question_asked.':::'.$data_raw.':::'.$text_field_to_save;
                    $_SESSION['ik_talent_qest_answer'][$stage_id] = $answer_validated;
                    $_SESSION['ik_talent_qest_answer_comment'][$stage_id] = $text_field_to_save;
                } else {
                    $_SESSION['ik_talent_qest_question_answer'][] = $question_asked.':::'.$data_raw.':::'.$text_field_to_save;
                    $_SESSION['ik_talent_qest_answer'][] = $answer_validated;
                    $_SESSION['ik_talent_qest_answer_comment'][] = $text_field_to_save;
                }

                return $stage_id + 1;
            }
        }

        return $stage_id;
    }
    
    //method to return a form asking for contact details
    public function get_contact_form(){
        $privacy_page_id = intval(get_option('ik_talent_privacy_policy_page'));
        $terms_page_id = intval(get_option('ik_talent_terms_page'));
        $privacy_policy = ($privacy_page_id > 0) ? '<a href="'.get_permalink($privacy_page_id).'" target="_blank">'.__( 'Privacy Policy', 'ik_talent_admin' ).'</a>' : '';
        $terms_page = ($terms_page_id > 0) ? '<a href="'.get_permalink($terms_page_id).'" target="_blank">'.__( 'Terms of Service', 'ik_talent_admin' ).'</a>' : '';
        $conditions_text = ($privacy_policy != '' && $terms_page != '') ? $privacy_policy.__( ' and ', 'ik_talent_admin' ).$terms_page : $privacy_policy.$terms_page;
        $form = '<h1>'.__( 'We are here to assist you in acquiring the talent you need!', 'ik_talent_admin' ).'</h1>';
        $form .= '<div class="ik_talent_contact_form_fields">
                <input type="email" name="email" id="ik_talent_select_data_form_email" autocomplete="email" placeholder="'.__( 'Email', 'ik_talent_admin' ).'">        
                <input type="text" name="company" id="ik_talent_select_data_form_company" autocomplete="organization" placeholder="'.__( 'Company Name', 'ik_talent_admin' ).'">      
                <input type="text" name="name" id="ik_talent_select_data_form_name" autocomplete="name" placeholder="'.__( 'Contact Name', 'ik_talent_admin' ).'">
                <input type="tel" name="phone" id="ik_talent_select_data_form_tel" autocomplete="tel" placeholder="'.__( '(123) 456-7891 (Optional)', 'ik_talent_admin' ).'">
            </div>';
        $form .= '<button id="ik_talent_submit_contact_form">'.__( 'Connect With Your Talent', 'ik_talent_admin' ).'</button>'; 
        
        if($privacy_policy != '' || $terms_page != ''){
            $form .= '<div class="ik_talent_contact_form_terms">'.__( 'By completing signup, you are agreeing to our ', 'ik_talent_admin' ).$conditions_text.'.</div>';
        }
    
        return $form;
    }

    //method to get data about a talent by post id
    public function get_talent_data_list($post_id, $type_of_data){
        if($post_id){
            $talent_id = $this->talents->get_talent_id_by_post($post_id);
            $talent_list = $this->list_by_talent_id($talent_id);
    
            if($talent_list){
                switch ($type_of_data) {
                    case 'languages':
                        return $this->get_names_list($talent_list['languages'], 'Languages', false, false);
                        break;
                    case 'techstack':
                        return $this->get_names_list($talent_list['techstack'], 'Tech Stack', true, false);
                        break;
                    case 'experience':
                        return $this->get_profile_data_list($post_id, 'experience', true, false);
                        break; 
                    case 'courses':
                        return $this->get_profile_data_list($post_id, 'courses', true, false);
                        break;                    
                    default:
                        return $this->get_names_list($talent_list['skills'], 'Skills', true, false);
                        break;
                }
            }
        }

        return '-';
    }


}
?>