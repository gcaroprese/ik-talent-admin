<?php
/*

Talents Admin | Class Ik_Talents
Created: 15/07/2023
Update: 02/08/2023
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}

class Ik_Talents{
    
    private $db_table_talent;
    public $talents_admin_url;
 
    public function __construct() {

        global $wpdb;
        $this->db_table_talent = $wpdb->prefix . "ik_talents_list";
        $this->talents = new Ik_Talent_Skills();
        $this->talents_admin_url = get_admin_url().'admin.php?page='.IK_TALENTS_MENU_VAL_ENTRIES;
    }

    //return table name for talents table 
    public function get_table_name(){
        return $this->db_table_talent;
    }

    private function sanitize_talent_args($args){

        $name =  (isset($args['name'])) ? sanitize_text_field($args['name']) : '';
        $args_sanitized['name'] = str_replace('\\', '', $name);
        $lastname = (isset($args['lastname'])) ? sanitize_text_field($args['lastname']) : '';
        $args_sanitized['lastname'] = str_replace('\\', '', $lastname);
        $args_sanitized['email'] = (isset($args['email'])) ?  sanitize_email($args['email']) : '';
        $args_sanitized['phone'] = (isset($args['phone'])) ?  sanitize_text_field($args['phone']) : '';
        $args_sanitized['role_id'] = (isset($args['role_id'])) ?  absint($args['role_id']) : '';
        $details = (isset($args['details'])) ?  sanitize_textarea_field($args['details']) : '';
        $args_sanitized['details'] = str_replace('\\', '', $details);
        $args_sanitized['image_id'] = (isset($args['image_id'])) ?  intval($args['image_id']) : 0;
        $args_sanitized['cv_file'] = (isset($args['cv_file'])) ? sanitize_text_field($args['cv_file']) : '';
        $args_sanitized['active'] = (isset($args['active'])) ? $args['active'] : 0;
        $args_sanitized['active'] = ($args_sanitized['active'] == 1) ? 1 : 0;

        $profile_data['company_name'] = (isset($args['company_name'])) ? $args['company_name'] : '';
        $profile_data['company_job'] = (isset($args['company_job'])) ? $args['company_job'] : '';
        $profile_data['company_description'] = (isset($args['company_description'])) ? $args['company_description'] : '';
        $profile_data['company_time'] = (isset($args['company_time'])) ? $args['company_time'] : '';
        $profile_data['company_time_type'] = (isset($args['company_time_type'])) ? $args['company_time_type'] : '';
        $profile_data['course_name'] = (isset($args['course_name'])) ? $args['course_name'] : '';
        $profile_data['course_place'] = (isset($args['course_place'])) ? $args['course_place'] : '';
        $profile_data['course_description'] = (isset($args['course_description'])) ? $args['course_description'] : '';
        $profile_data['course_graduation'] = (isset($args['course_graduation'])) ? $args['course_graduation'] : '';

        //I validate skill ids array
        $args_sanitized['skill_ids'] = (isset($args['skill_ids'])) ? $this->process_ids_to_serialize($args['skill_ids']) : '';
        //I validate tech stack ids array
        $args_sanitized['techstack_ids'] = (isset($args['techstack_ids'])) ? $this->process_ids_to_serialize($args['techstack_ids']) : '';
        //I validate language ids array
        $args_sanitized['languages'] = (isset($args['languages'])) ? $this->process_ids_to_serialize($args['languages']) : '';
        //experience and courses
        $args_sanitized['profile_data'] = (isset($args['profile_data'])) ? $args['profile_data'] : $this->process_profile_data($profile_data);

        return $args_sanitized;

    }

    //method to see fields that were edited from talent
    private function validate_talent_args($args){
        $fields['name'] = (isset($args['name'])) ? true : false;
        $fields['lastname'] = (isset($args['lastname'])) ? true : false;
        $fields['email'] = (isset($args['email'])) ? true : false;
        $fields['phone'] = (isset($args['phone'])) ? true: false;
        $fields['role_id'] = (isset($args['role_id'])) ? true : false;
        $fields['details'] = (isset($args['details'])) ? true : false;
        $fields['active'] = (isset($args['active'])) ? true : false;
        $fields['image_id'] = (isset($args['image_id'])) ? true : false;
        $fields['cv_file'] = (isset($args['cv_file'])) ? true : false;
        $fields['skill_ids'] = (isset($args['skill_ids'])) ? true : false;
        $fields['techstack_ids'] = (isset($args['techstack_ids'])) ? true : false;
        $fields['languages'] = (isset($args['languages'])) ? true : false;
        $fields['profile_data'] = (isset($args['company_name']) || isset($args['profile_data'])) ? true : false;

        return $fields;

    }

    //Create talent
    public function create($args = array(), $post_id = -1){
        
        $args = $this->sanitize_talent_args($args);

        //create post id and assign role id
        if (intval($post_id) == -1){
            // Create post object
            $post = array(
                'post_content'   => $args['details'],
                'post_title'     => $args['name'].' '.$args['lastname'],
                'post_status'    => 'publish',
                'post_type'      => IK_TALENTS_POST_TYPE_ID,
            );

            // Insert the post into the database
            $post_id = wp_insert_post($post);
            update_post_meta($post_id, 'role_id', $args['role_id']);
            set_post_thumbnail($post_id, $args['image_id']);
        }

        if ($args['role_id'] > 0 && !empty(trim($args['name'])) && !empty(trim($args['lastname'])) && !empty(trim($args['email']))){
            
            $data_insert = array (
                'role_id'	    => $args['role_id'],
                'post_id'	    => $post_id,
                'skill_ids'     => $args['skill_ids'],
                'date_created'  => current_time('mysql'),
                'date_updated'  => current_time('mysql'),
                'name'          => $args['name'],
                'lastname'      => $args['lastname'],
                'email'         => $args['email'],
                'phone'         => $args['phone'],
                'cv_file'       => $args['cv_file'],
                'details'	    => $args['details'],
                'languages'	    => $args['languages'],
                'techstack_ids'	=> $args['techstack_ids'],
                'image_id'	    => $args['image_id'],
                'profile_data'	=> $args['profile_data'],
                'active'	    => $args['active']
            );
            
            global $wpdb;
            $rowResult = $wpdb->insert($this->db_table_talent, $data_insert, NULL);   
            $talent_id = $wpdb->insert_id;
            
            return $talent_id;
        }
        
        return false;
    }

    //method to edit talent name
    public function edit($args = array(), $update_post = true){
        
        if (isset($args['talent_id'])){
            $talent_id = absint($args['talent_id']);

            //check fields that are being edited
            $editable_fields = $this->validate_talent_args($args);
            //sanitize fields
            $args = $this->sanitize_talent_args($args);
            
            //I add the non false arrays to the update array
            foreach ($editable_fields as $key=> $field){
                if($field != false){
                    $data_update[$key] = $args[$key];
                }
            }

            if(isset($data_update)){
                if($update_post){
                    $edit_post = $this->edit_talent_post($talent_id, $data_update);
                }
                $data_update['date_updated'] = current_time('mysql');
    
                global $wpdb;
                $where = [ 'id' => $talent_id ];
                $rowResult = $wpdb->update($this->db_table_talent, $data_update, $where);

                //update post 

    
                return $talent_id;
            }
        }
        
        return false;
    }

    //edit some talent posts based on the talent id
    private function edit_talent_post($talent_id, $args){
        $talent_id = intval($talent_id);
        $post_id = $this->get_post_id($talent_id);
        if($post_id){
            //update postmeta role id for post
            if(isset($args['role_id'])){
                update_post_meta($post_id, 'role_id', $args['role_id']);
            }
            //update feature image
            if(isset($args['image_id'])){
                set_post_thumbnail($post_id, $args['image_id']);
            }
            //update post content
            if(isset($args['details'])){
                $post_data = array(
                    'ID' => $post_id,
                    'post_content' => $args['details'],
                );
                wp_update_post($post_data);
            }
        }

        return;
    }

    //Get talent data by ID
    public function get_by_id($talent_id = 0){
        $talent_id = absint($talent_id);
        
        if ( $talent_id > 0){
            
            global $wpdb;
            $query = "SELECT * FROM ".$this->db_table_talent." WHERE id = ".$talent_id;
            $talent = $wpdb->get_results($query);
    
            if (isset($talent[0]->id)){ 
                return $talent[0];
            }
        }
        
        return false;
    }

    //Get post_ID
    public function get_post_id($talent_id = 0){
        $talent_id = absint($talent_id);
        
        if ( $talent_id > 0){
            
            global $wpdb;
            $query = "SELECT post_id FROM ".$this->db_table_talent." WHERE id = ".$talent_id;
            $talent = $wpdb->get_results($query);

            if (isset($talent[0]->post_id)){ 
                return $talent[0]->post_id;
            }
        }
        
        return false; 
    }

    //Get talend Id by post_ID
    public function get_talent_id_by_post($post_id = 0){
        $post_id = absint($post_id);
        
        if ( $post_id > 0){
            
            global $wpdb;
            $query = "SELECT id FROM ".$this->db_table_talent." WHERE post_id = ".$post_id;
            $talent = $wpdb->get_results($query);

            if (isset($talent[0]->id)){ 
                return $talent[0]->id;
            }
        }
        
        return false; 
    }

    //Get talent data by post_ID
    public function get_by_post_id($post_id = 0){
        $post_id = absint($post_id);
        
        if ( $post_id > 0){
            
            global $wpdb;
            $query = "SELECT * FROM ".$this->db_table_talent." WHERE post_id = ".$post_id;
            $talent = $wpdb->get_results($query);
    
            if (isset($talent[0]->id)){ 
                return $talent[0];
            }
        }
        
        return false;
        
    }

    //Get all talents data
    public function get_talents(){
                    
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_talent." ORDER BY name ASC";
        $talents = $wpdb->get_results($query);

        if (isset($talents[0]->id)){ 
            return $talents;
        }
        
        return false;
        
    }

    //Count the quantity of talent records
    public function qty_records(){

        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_talent;
        $result = $wpdb->get_results($query);

        if (isset($result[0]->id)){ 
            
            $count_result = count($result);

            return $count_result;
            
        } else {
            return false;
        }
    }

    //List talents
    private function get_list($qty = 30){
        $qty = absint($qty);

        if (isset($_GET["list"])){
            // I check if value is integer to avoid errors
            if (strval($_GET["list"]) == strval(intval($_GET["list"])) && $_GET["list"] > 0){
                $page = intval($_GET["list"]);
            } else {
                $page = 1;
            }
        } else {
             $page = 1;
        }

        $offset = ($page - 1) * $qty;

        if (isset($_GET['search'])){
            $search = sanitize_text_field($_GET['search']);
        } else {
            $search = NULL;
        }

        if (isset($_GET['active'])){
            $active = (intval($_GET['active']) == 0) ? 'active = 0' : 'active = 1';
        } else {
            $active = false;
        }
        
        
        // Chechking order
        if (isset($_GET["orderby"]) && isset($_GET["orderdir"])){
            $orderby = sanitize_text_field($_GET["orderby"]);
            $orderdir = sanitize_text_field($_GET["orderdir"]);  
            if (strtoupper($orderdir) != 'DESC'){
                $orderDir= ' ASC';
                $orderClass= 'sorted asc';
            } else {
                $orderDir = ' DESC';
                $orderClass= 'sorted desc';
            }
        } else {
            $orderby = 'id';
            $orderDir = 'ASC';
            $orderClass= 'sorted asc';
        } 
        if (is_int($offset)){
            $offsetList = ' LIMIT '.$qty.' OFFSET '.$offset;
        } else {
            $offsetList = ' LIMIT '.absint($qty);
        }
        
        //Values to order filters CSS classes
        $empty = '';
        $idClass = $empty;
        $nameClass = $empty;
        $roleClass = $empty;
    
        
        if ($orderby != 'id'){	
            if ($orderby == 'name'){
                $orderQuery = ' ORDER BY '.$this->db_table_talent.'.name '.$orderDir;
                $nameClass = $orderClass;
            } else {
                $orderQuery = ' ORDER BY '.$this->db_table_talent_roles.'.role_name '.$orderDir;
                $roleClass = $orderClass;
            }
        } else {
            $orderQuery = ' ORDER BY '.$this->db_table_talent.'.id '.$orderDir;
            $idClass = $orderClass;
        }

        $classData = array(
            'id' => $idClass,
            'name' => $nameClass,
            'role' => $roleClass,
        );

        if ($search != NULL){ 
            //Search by lot number, product name, botanical name and country of origin
            $where = " WHERE ".$this->db_table_talent.".name LIKE '%".$search."%'";
            if($active){
                $where .= "AND ".$active;
            }
        } else {
            if($active){
                $where = "WHERE ".$active;
            } else {
                $where = "";
            }
            $search = '';
        }

        $groupby = (isset($groupby)) ? $groupby : " GROUP BY ".$this->db_table_talent.".id ";

        global $wpdb;

        $query = "SELECT * FROM ".$this->db_table_talent." ".$where.$groupby.$orderQuery.$offsetList;

        $talents = $wpdb->get_results($query);
        $talents_data = array(
            'data' => $talents,
            'class' => $classData,
            'search_value' => $search,        
        );

        return $talents_data;

    }

    
    //method to process profile data such as experience and courses or certifications
    public function process_profile_data($args = array()){

        if (isset($args['company_name']) && isset($args['company_job']) && isset($args['company_time']) && isset($args['company_time_type'])){
            
            if(is_array($args['company_name'])){
                $counter = 0;
                foreach($args['company_name'] as $company){
                    $company = sanitize_text_field($company);
                    $company = str_replace('\\', '', $company);
    
                    //make sure not empty
                    if ((trim($company)) !== '') {     
                        $company_job = (isset($args['company_job'][$counter])) ? sanitize_text_field($args['company_job'][$counter]) : '';
                        $company_job = str_replace('\\', '', $company_job);
                        $company_description = (isset($args['company_description'][$counter])) ? sanitize_textarea_field($args['company_description'][$counter]) : '';
                        $company_description = str_replace('\\', '', $company_description);
                        $company_time = intval($args['company_time'][$counter]);
                        $company_time_type = (isset($args['company_time_type'][$counter])) ? sanitize_text_field($args['company_time_type'][$counter]) : 'year';
                                
                        $data_profile['experience'][] = array(
                            'company_name' => $company,
                            'company_job' => $company_job,
                            'company_description' => $company_description,
                            'company_time' => $company_time,
                            'company_time_type' => $company_time_type,
                        );
                    }
                    $counter = $counter + 1;
                }
            }
        }

        if (isset($args['course_name']) && isset($args['course_place']) && isset($args['course_graduation'])){
            
            if(is_array($args['course_name'])){
                $counter = 0;
                foreach($args['course_name'] as $course_name){
                    $course_name = sanitize_text_field($course_name);
                    $course_name = str_replace('\\', '', $course_name);
    
                    //make sure not empty
                    if ((trim($course_name)) !== '') {
                        $course_place = (isset($args['course_place'][$counter])) ? sanitize_text_field($args['course_place'][$counter]) : '';
                        $course_place = str_replace('\\', '', $course_place);
                        $course_description = (isset($args['course_description'][$counter])) ? sanitize_textarea_field($args['course_description'][$counter]) : '';
                        $course_description = str_replace('\\', '', $course_description);
                        $course_graduation = (isset($args['course_graduation'][$counter])) ? intval($args['course_graduation'][$counter]) : 0;
                                
                        $data_profile['courses'][] = array(
                            'course_name' => $course_name,
                            'course_place' => $course_place,
                            'course_description' => $course_description,
                            'course_graduation' => $course_graduation,
                        );
                    }
                    $counter = $counter + 1;
                }
    
            }
        }

        if(!isset($data_profile)){
            $data_profile = maybe_serialize(array());
        }

        return maybe_serialize($data_profile);

    }

    //Method to show list of talents for backend
    public function get_list_talents_wrapper_backend($qty = 30){

        $qty = absint($qty);

        $talents_data = $this->get_list($qty);
        $talents = $talents_data['data'];;
        $search = $talents_data['search_value'];;

        //classes for columns that are filtered
        $classData = $talents_data['class'];

        $idClass = $classData['id'];
        $nameClass = $classData['name'];
        $roleClass = $classData['role'];

        if (isset($_GET['active'])){
            $active_selected = (intval($_GET['active']) == 0) ? 'l' : 'selected';
            $inactive_selected = (intval($_GET['active']) == 0) ? 'selected' : 'lj';
        } else {
            $active_selected = '';
            $inactive_selected = '';
        }

        // If data exists
        if (isset($talents[0]->id)){

            $columnsheading = '<tr>
                <th><input type="checkbox" class="select_all" /></th>
                <th order="id" class="worder '.$idClass.'">'.__( 'ID', 'ik_talent_admin' ).' <span class="sorting-indicator '.$idClass.'"></span></th>
                <th order="name" class="wide-data worder '.$nameClass.'">'.__( 'Name', 'ik_talent_admin' ).' <span class="sorting-indicator '.$nameClass.'"></span></th>
                <th order="cv" class="wide-data worder '.$roleClass.'">'.__( 'CV', 'ik_talent_admin' ).' <span class="sorting-indicator '.$roleClass.'"></span></th>
                <th class="midle-actions">'.__( 'Details', 'ik_talent_admin' ).'</th>
                <th class="midle-actions">'.__( 'Profile', 'ik_talent_admin' ).'</th>
                <th class="wide-actions">
                    <button class="ik_talent_button_delete_bulk button action">'.__( 'Delete', 'ik_talent_admin' ).'</button></td>
                </th>
            </tr>';

            $searchBar = '<p class="search-box">
                <label class="screen-reader-text" for="tag-search-input">Search talent:</label>
                <input type="search" id="tag-search-input" name="search" value="'.$search.'">
                <input type="submit" id="searchbutton" class="button" value="Search">
            </p>';

            $filterSelect = '<select class="ik-filter ik-filter-active" name="filter_active" onchange="location = this.value;">
                <option value="'.$this->talents_admin_url.'">'.__( 'Show All', 'ik_talent_admin' ).'</option>
                <option '.$active_selected.' value="'.$this->talents_admin_url.'&active=1">'.__( 'Active', 'ik_talent_admin' ).'</option>
                <option '.$inactive_selected.' value="'.$this->talents_admin_url.'&active=0">'.__( 'Inactive', 'ik_talent_admin' ).'</option>
            </select>';

            $csv_export = '<button class="button-primary panel_button" id="csv_export_talents" href="#">'.__( 'Export CSV', 'ik_talent_admin' ).'</button>';

            $listing = '
            <div class="tablenav-pages">'.__( 'Total', 'ik_talent_admin' ).': '.$this->qty_records().' - '.__( 'Showing', 'ik_talent_admin' ).': '.count($talents).'</div>'.$searchBar.$filterSelect.$csv_export;

            if ($search != NULL){
                $listing .= '<p class="show-all-button"><a href="'.$this->talents_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>';
            }

            $listing .= '<table id="ik_talent_existing">
                <thead>
                    '.$columnsheading.'
                </thead>
                <tbody>';
                foreach ($talents as $talent){
                    $file_cv = ($talent->cv_file != '') ? wp_upload_dir()['baseurl'].'/'.IK_TALENT_CVS_FOLDER_DIR.'/'.$talent->cv_file : '#';
                    
                    $listing .= '
                        <tr iddata="'.$talent->id.'">
                            <td><input type="checkbox" class="select_data" /></td>
                            <td class="ik_talent_id">'.$talent->id.'</td>
                            <td class="ik_talent_name">'.$talent->name.' '.$talent->lastname.'</td>
                            <td class="ik_talent_role">
                                <a href="'.$file_cv.'" class="button" target="_blank">
                                    <span class="dashicons dashicons-media-document"></span>
                                </a>
                            </td>
                            <td class="ik_talent_datamodal"><button href="#" class="ik_talent_button_view_modal button action"><span class="dashicons dashicons-visibility"></span></button></td>
                            <td><a target="_blank" href="'.get_permalink($talent->post_id).'" class="button"></span> '.__( 'View', 'ik_talent_admin' ).'</a></td>
                            <td iddata="'.$talent->id.'">
                                <a href="'.$this->talents_admin_url.'&edit_id='.$talent->id.'" class="ik_talent_button_edit_talent button action">'.__( 'Edit', 'ik_talent_admin' ).'</a>
                                <button class="ik_talent_button_delete button action">'.__( 'Delete', 'ik_talent_admin' ).'</button></td>
                        </tr>';
                }
                $listing .= '
                </tbody>
                <tfoot>
                    '.$columnsheading.'
                </tfoot>
                <tbody>
            </table>';

                //pagination
                $talent_admin = new Ik_Talent_Admin();
                $listing .= $talent_admin->paginator($this->qty_records(), $qty, $this->talents_admin_url);
            
            return $listing;
            
        } else {
            if ($search != NULL){
                $listing = $searchBar.$filterSelect.'
                <div id="ik_talent_existing">
                    <p>'.__( 'Results not found', 'ik_talent_admin' ).'</p>
                    <p class="show-all-button"><a href="'.$this->talents_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>
                </div>';

                return $listing;
            }
        }
        
        return false;
    }

    //method to process cv pdf file upload
    private function process_cv_pdf_upload($file_to_upload = NULL){
        if ($file_to_upload != NULL){
        
            //Formats accepted
            $files_supported = array('application/pdf', 'application/msword');
            
            if (in_array($file_to_upload['type'], $files_supported)) {
                
                $file_size = $file_to_upload['size'];
                $file = $file_to_upload;
                $upload = wp_upload_dir();
                $upload_dir = $upload['basedir'];
                $target_dir = $upload_dir . '/'.IK_TALENT_CVS_FOLDER_DIR.'/';
            
                $upload_file_name = preg_replace('/\s+/', '', basename($_FILES["file"]["name"]));
                
                // Evito nombres repetidos
                $filecode = substr(md5(uniqid(rand(), true)), 4, 4); // 4 caracteres
                $filenowDate = date("Y/m/d");
                $filenumberedDate = strtotime($filenowDate);
                $filecodigoInv = strtoupper($filecode.$filenumberedDate);
                
                $file_name = rand().$filecodigoInv. sanitize_file_name($upload_file_name);
                $target_file = $target_dir . $file_name;
                move_uploaded_file($file_to_upload['tmp_name'], $target_file);

                return $file_name;
            } 
        }

        return false;
    }

    /*
        Method to process skills ids languages ids and other kinds of ids arrays
        Returns a serializa array or an empty string if fails
    */
    private function process_ids_to_serialize($values_array){
        $default_array = '';
        $values_array = (is_serialized($values_array)) ? maybe_unserialize($values_array) : $values_array;

        if(is_array($values_array)){
            foreach($values_array as $value){
                $ids_array[] = absint($value);
            }
        }

        if(isset($ids_array)){
            return maybe_serialize($ids_array);
        } else {
            return $default_array;
        }
    }

    //Process create or update talent forms
    public function update($post_id = -1, $update_post = true){
        //in case it comes from a post_id
        $post_id = intval($post_id);
        
        $result = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['email']) 
            && isset($_POST['phone']) && isset($_POST['role_id']) && isset($_POST['skill_ids'])){

                $args = $this->sanitize_talent_args($_POST);

                //if file uploaded
                $file_name = (isset($_FILES['file'])) ? $this->process_cv_pdf_upload($_FILES['file']) : '';

                // i checke if it's edit or creation of a talent in the db
                $talent_post = $this->get_by_post_id($post_id);
                $talent_id_from_post_id = (isset($talent_post->id)) ? $talent_post->id : false;
                if ($talent_id_from_post_id){
                    $edit = true;
                } else if(isset($_POST['talent_id']) && isset($_GET['edit_id'])){
                    $edit = true;
                } else {
                    $edit = false;
                }

                if ($edit){
                    $talent_id = ($talent_id_from_post_id) ? $talent_id_from_post_id : absint($_GET['edit_id']);
                    $talent = $this->get_by_id($talent_id);
                    
                    if($talent){
                        $args['cv_file'] = (isset($file_name)) ? $file_name : $talent->cv_file;
                        $args['talent_id'] = $talent_id;

                        $result = $this->edit($args, $update_post);
                    } else {
                        $result = 'Data Incomplete';
                    }
                } else {
                    $args['cv_file'] = ($file_name) ? $file_name : '';
                    $result = $this->create($args, $post_id);
                }
            }
        }

        return $result;
    }

    //delete talent by ID
    public function delete($talent_id){
        $talent_id = absint($talent_id);

        //I delete post type first if exists
        $post_id = $this->get_post_id($talent_id);

        if($post_id){
            $deleted_post = wp_delete_post($post_id);
        }

        global $wpdb;
        $wpdb->delete( $this->db_table_talent , array( 'id' => $talent_id ) );
        
        return true;
    }
    //delete talent by post ID usually because talent post was deleted
    public function delete_by_post_id($post_id){
        $post_id = absint($post_id);
        global $wpdb;
        $wpdb->delete( $this->db_table_talent , array( 'post_id' => $post_id ) );
        
        return true;
    }
}

?>