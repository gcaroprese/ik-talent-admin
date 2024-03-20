<?php
/*

Talents Admin | Class Ik_Talent_Requests
Created: 15/07/2023
Update: 02/08/2023
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}

class Ik_Talent_Requests{

    private $db_table_requests;
    public $requests_admin_url;
 
    public function __construct() {

        global $wpdb;
        $this->db_table_requests = $wpdb->prefix . "ik_talents_requests";
        $this->requests_admin_url = get_admin_url().'admin.php?page='.IK_TALENTS_MENU_VAL_REQ;
    }

    //Get Talent Requests table name
    public function get_table_name(){
        
        return $this->db_table_requests;
    }

  
    //Create talent request
    public function create($args = array()){

        $quest_answers_full = '{{{';
        if(is_array($args['answers'])){
            foreach($args['answers'] as $answer){
                $quest_answers_full .= $answer.'{{{';
            }
        }

        $data_insert  = array (
            'role_id'=> intval($args['role_id']),	
            'request_data'=> $quest_answers_full,	
            'company' => sanitize_text_field($args['company']),
            'contact_name'=> sanitize_text_field($args['name']),	
            'email' => sanitize_email($args['email']),
            'phone' => sanitize_text_field($args['phone']),
            'submited_date'=> current_time( 'mysql' ),
        );
        
        global $wpdb;
        $rowResult = $wpdb->insert($this->db_table_requests, $data_insert);   
        $request_id = $wpdb->insert_id;


        //after form url button
        $after_form_url_id = intval(get_option('ik_talent_after_form_url'));
        $after_form_button = ($after_form_url_id > 0) ? '<a class="button" href="'.get_permalink($after_form_url_id).'">'.__( 'Discover More Professionals', 'ik_talent_admin' ).'</a>' : '';

        if($after_form_url !== ''){
            $button_to_talents = $after_form_button;
        } else {
            $button_to_talents = '';
        }

        $output_html = '<h3>'.__( 'Thank you for your time. We will be in touch soon to assign you a professional.', 'ik_talent_admin' ).'</h3>'.$button_to_talents;


        //send email
        $subject = __( 'New Talent Requested: Someone just submitted a quiz', 'ik_talent_admin' );
        $message = __( 'Someone has recently submitted a quiz in search of a talent', 'ik_talent_admin' );
        $message .= __( 'More Info:', 'ik_talent_admin' ).'<a href="'.$this->requests_admin_url.'">Click Here.</a>';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $email_answers = (get_option('ik_talent_email_responses')) ? get_option('ik_talent_email_responses') : get_option('admin_email');
        $sent = wp_mail($email_answers, $subject, $message, $headers);

        $output['email_sent'] = $sent;
        $output['id_created'] = $request_id;
        $output['html'] = $output_html;
        session_unset();
        
        return $output;

    }
    
    //method to return question input fields 
    public function get_question_input_field($args = array()){

        $order = (isset($args['order'])) ? intval($args['order']) : 0;
        $question = (isset($args['question'])) ? sanitize_text_field($args['question']) : '';

        $type_question = (isset($args['type_question'])) ? sanitize_text_field($args['type_question']) : '';

        $empty = '';
        $selection_type_multiple = $empty;
        $selection_type_skills = $empty;
        $selection_type_languages = $empty;
        $selection_type_techstack = $empty;
        $selection_type_textarea = $empty;

        switch ($type_question) {
            case 'skills':
                $answers_choices_class = 'hidden';
                $add_text_field_class = '';
                $selection_type_skills = 'checked';
                break;
            case 'languages':
                $answers_choices_class = 'hidden';
                $add_text_field_class = '';
                $selection_type_languages = 'checked';
                break;
            case 'techstack':
                $answers_choices_class = 'hidden';
                $add_text_field_class = '';
                $selection_type_techstack = 'checked';
                break;
            case 'textarea':
                $answers_choices_class = 'hidden';
                $add_text_field_class = 'hidden';
                $selection_type_textarea = 'checked';
                break;
            case 'multiple':
                $answers_choices_class = '';
                $add_text_field_class = '';
                $selection_type_multiple = 'checked';
                break;
            default:
                $answers_choices_class = 'hidden';
                $add_text_field_class = 'hidden';
                $selection_type_multiple = '';
                break;
        }

        if(isset($args['answers'])){
            $answers = (is_serialized($args['answers'])) ? maybe_serialize($args['answers']) : $args['answers'];
            if(is_array($answers)){
                $answers_list = '';
                foreach($answers as $answer){
                    $answers_list .= '<div>
                        <input type="text" name="answer_text['.$order.'][]" placeholder="'.__( 'Write answer here...', 'ik_talent_admin' ).'" value="'.$answer.'" />
                        <a href="#" class="ik_talent_delete_answer button"><span class="dashicons dashicons-trash"></span></a>
                    </div>';
                }
            }
        }

        if(!isset($answers_list)){
            $answers_list = '<div>
                <input type="text" name="answer_text[0][]" placeholder="'.__( 'Write answer here...', 'ik_talent_admin' ).'" />
                <a href="#" class="ik_talent_delete_answer button"><span class="dashicons dashicons-trash"></span></a>
            </div>';
        }

        $add_text_field = (isset($args['text_field'])) ? rest_sanitize_boolean($args['text_field']) : false;
        $add_text_field_checked = ($add_text_field) ? 'checked' : ''; 


        $question = '<li class="draggable-item multiple_fields">
                <div class="question">
                    <label for="question_text">'.__( 'Question', 'ik_talent_admin' ).'</label>
                    <input type="text" required name="question_text['.$order.']" placeholder="'.__( 'Write the question here...', 'ik_talent_admin' ).'" value="'.$question.'" />
                    <a href="#" class="ik_talent_delete_field button">'.__( 'Delete', 'ik_talent_admin' ).'</a><a href="#" class="ik_talent_open_field button"><span class="dashicons dashicons-arrow-down"></span></a>
                </div>
                <div class="talent_expanded_fields hidden">
                    <div class="multiple-choice">
                        <label>
                            <input type="radio" name="selection_type['.$order.']" value="multiple" '.$selection_type_multiple.' /> '.__( 'Multiple Choice', 'ik_talent_admin' ).'
                        </label>
                        <label>
                            <input type="radio" name="selection_type['.$order.']" value="skills" '.$selection_type_skills.' /> '.__( 'Skill Select', 'ik_talent_admin' ).'
                        </label>
                        <label>
                            <input type="radio" name="selection_type['.$order.']" value="languages" '.$selection_type_languages.' /> '.__( 'Language Select', 'ik_talent_admin' ).'
                        </label>
                        <label>
                            <input type="radio" name="selection_type['.$order.']" value="techstack" '.$selection_type_techstack.' /> '.__( 'Tech Stack Select', 'ik_talent_admin' ).'
                        </label>
                        <label>
                            <input type="radio" name="selection_type['.$order.']" value="textarea" '.$selection_type_textarea.' /> '.__( 'Text Field', 'ik_talent_admin' ).'
                        </label>
                    </div>
                    <div class="answers_choices '.$answers_choices_class.'">
                        <div class="answers_choices_container">
                            '.$answers_list.'
                        </div>
                        <a href="#" class="button button-primary ik_talent_add_answer">'.__( 'Add Answer', 'ik_talent_admin' ).'</a>
                    </div>
                    <br />
                    <div class="add_text_field '.$add_text_field_class.'">
                        <label for="add_text_field">    
                            <input type="checkbox" name="add_text_field['.$order.']" value="yes" '.$add_text_field_checked.' />
                            '.__( 'Add text field at the end', 'ik_talent_admin' ).'
                        </label>
                    </div>
                </div>
            </li>';

        return $question;
    } 

    //Count the quantity of enquiries records
    public function qty_records(){

        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_requests;
        $result = $wpdb->get_results($query);

        if (isset($result[0]->id)){ 
            
            $count_result = count($result);

            return $count_result;
            
        } else {
            return false;
        }
    }

    //List requests enquiries
    private function get_list($qty = 60){
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
            $orderClass= 'sorted';
        } 
        if (is_int($offset)){
            $offsetList = ' LIMIT '.$qty.' OFFSET '.$offset;
        } else {
            $offsetList = ' LIMIT '.absint($qty);
        }
        
        //Values to order filters CSS classes
        $empty = '';
        $idClass = $empty;
        $role_idClass = $empty;
        $companyClass = $empty;
        $contact_nameClass = $empty;
        $emailClass = $empty;
        $phoneClass = $empty;
        $submited_dateClass = $empty;
    
        
        if ($orderby != 'id'){	
            if ($orderby == 'role_id'){
                $orderQuery = ' ORDER BY '.$this->db_table_requests.'.role_id '.$orderDir;
                $role_idClass = $orderClass;
            } else if ($orderby == 'company'){
                $orderQuery = ' ORDER BY '.$this->db_table_requests.'.company '.$orderDir;
                $companyClass = $orderClass;
            } else if ($orderby == 'contact_name'){
                $orderQuery = ' ORDER BY '.$this->db_table_requests.'.contact_name '.$orderDir;
                $contact_nameClass = $orderClass;
            } else if ($orderby == 'email'){
                $orderQuery = ' ORDER BY '.$this->db_table_requests.'.email '.$orderDir;
                $emailClass = $orderClass;
            } else if ($orderby == 'phone'){
                $orderQuery = ' ORDER BY '.$this->db_table_requests.'.phone '.$orderDir;
                $phoneClass = $orderClass;
            } else {
                $orderQuery = ' ORDER BY '.$this->db_table_requests.'.submited_date '.$orderDir;
                $submited_dateClass = $orderClass;
            }
        } else {
            $orderQuery = ' ORDER BY '.$this->db_table_requests.'.id '.$orderDir;
            $idClass = $orderClass;
        }

        $classData = array(
            'id' => $idClass,
            'role_id' => $role_idClass,
            'company' => $companyClass,
            'contact_name' => $contact_nameClass,
            'email' => $emailClass,
            'phone' => $phoneClass,
            'submited_date' => $submited_dateClass,
        );

        if ($search != NULL){ 
            //Search by lot number, product name, botanical name and country of origin
            $where = " WHERE ".$this->db_table_requests.".request_data LIKE '%".$search."%' OR ".$this->db_table_requests.".company  OR ".$this->db_table_requests.".contact_name OR ".$this->db_table_requests.".email";
        } else {
            $where = "";
            $search = '';
        }

        $groupby = (isset($groupby)) ? $groupby : " GROUP BY ".$this->db_table_requests.".id ";

        global $wpdb;

        $query = "SELECT * FROM ".$this->db_table_requests." ".$where.$groupby.$orderQuery.$offsetList;

        $skills = $wpdb->get_results($query);
        $enquiries_data = array(
            'data' => $skills,
            'class' => $classData,
            'search_value' => $search,        
        );

        return $enquiries_data;

    }

    //Method to show list of skills for backend
    public function get_list_enquiries_wrapper_backend($qty = 60){

        $qty = absint($qty);

        $enquiries_data = $this->get_list($qty);
        $enquiries = $enquiries_data['data'];;
        $search = $enquiries_data['search_value'];;

        //classes for columns that are filtered
        $classData = $enquiries_data['class'];

        $idClass = $classData['id'];
        $role_idClass = $classData['role_id'];
        $companyClass = $classData['company'];
        $contact_nameClass = $classData['contact_name'];
        $emailClass = $classData['email'];
        $phoneClass = $classData['phone'];
        $submited_dateClass = $classData['submited_date'];

        // If data exists
        if (isset($enquiries[0]->id)){

            $columnsheading = '<tr>
                <th><input type="checkbox" class="select_all" /></th>
                <th order="id" class="export_enabled_header worder '.$idClass.'">'.__( 'ID', 'ik_talent_admin' ).' <span class="sorting-indicator '.$idClass.'"></span></th>
                <th order="role_id" class="export_enabled_header worder '.$role_idClass.'">'.__( 'Role', 'ik_talent_admin' ).' <span class="sorting-indicator '.$role_idClass.'"></span></th>
                <th>'.__( 'Form Data', 'ik_talent_admin' ).'</th>
                <th class="export_enabled_header" style="display:none! important;">'.__( 'Form Data', 'ik_talent_admin' ).'</th>
                <th order="company" class="export_enabled_header worder '.$companyClass.'">'.__( 'Company', 'ik_talent_admin' ).' <span class="sorting-indicator '.$companyClass.'"></span></th>
                <th order="contact_name" class="export_enabled_header worder '.$contact_nameClass.'">'.__( 'Name', 'ik_talent_admin' ).' <span class="sorting-indicator '.$contact_nameClass.'"></span></th>
                <th order="email" class="export_enabled_header worder '.$emailClass.'">'.__( 'Email', 'ik_talent_admin' ).' <span class="sorting-indicator '.$emailClass.'"></span></th>
                <th order="phone" class="export_enabled_header worder '.$phoneClass.'">'.__( 'Phone', 'ik_talent_admin' ).' <span class="sorting-indicator '.$phoneClass.'"></span></th>
                <th order="submited_date" class="export_enabled_header worder '.$submited_dateClass.'">'.__( 'Date', 'ik_talent_admin' ).' <span class="sorting-indicator '.$submited_dateClass.'"></span></th>
                <th class="wide-actions">
                    <button class="ik_talent_button_delete_bulk button action">'.__( 'Delete', 'ik_talent_admin' ).'</button></td>
                </th>
            </tr>';

            //I create the export to CSV btn
            $exportName = "report-requests-".date('m-d-Y').".csv";
            $export_csv_button = '<div class="ik_talent_btn_export_csv_content"><button id="ik_export_csv" class="button button-primary" onclick="exportTableToCSV(\''.$exportName.'\')">'.__( 'Export to CSV', 'ik_talent_admin' ).'</button></div>';

            $searchBar = '<p class="search-box">
                <label class="screen-reader-text" for="tag-search-input">Search skill:</label>
                <input type="search" id="tag-search-input" name="search" value="'.$search.'">
                <input type="submit" id="searchbutton" class="button" value="'.__( 'Search', 'ik_talent_admin' ).'">
            </p>';
            $listing = $export_csv_button.'
            <div class="tablenav-pages">'.__( 'Total', 'ik_talent_admin' ).': '.$this->qty_records().' - '.__( 'Showing', 'ik_talent_admin' ).': '.count($enquiries).'</div>'.$searchBar;

            if ($search != NULL){
                $listing .= '<p class="show-all-button"><a href="'.$this->requests_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>';
            }

            $listing .= '<table id="ik_talent_existing">
                <thead>
                    '.$columnsheading.'
                </thead>
                <tbody>';

                $Talent_Admin = new Ik_Talent_Admin();    
                
                foreach ($enquiries as $enquiry){
                    
                    // Verify if it has content
                    $QA_data_export = '';
                    if (strlen($enquiry->request_data) > 0) {
                        // Split by triple curly braces {{{ and remove them
                        $question_and_answers = explode('{{{', $enquiry->request_data);
                        $question_and_answers_clean = array_map('trim', $question_and_answers); // Remove whitespace around the parts
                    
                        // Initialize a variable to accumulate the subparts
                        $QA_data = '';
                    
                        // Split each part by :::
                        foreach ($question_and_answers_clean as $part) {
                            // Split the part by :::
                            $QA_data .= rtrim($part, ':::')."<br /><hr>";
                            $QA_data = str_replace(':::', ': ', $QA_data);
                        }

                        // for export csv data
                        foreach ($question_and_answers_clean as $part) {
                            // Split the part by :::
                            $QA_data_export .= rtrim($part, ':::')." | ";
                            $QA_data_export = str_replace(':::', ': ', $QA_data_export);
                        }
                        $QA_data_export = rtrim($QA_data_export, '| ')."";
                        $icon_view = '<span class="dashicons dashicons-visibility ik_talent_request_view_data_popup"></span>';
                    } else {
                        $QA_data = "";
                        $icon_view = '-';
                    }
                    
    
                                        
                    $listing .= '
                        <tr iddata="'.$enquiry->id.'">
                            <td><input type="checkbox" class="select_data" /></td>
                            <td class="ik_talent_id export_enabled">'.$enquiry->id.'</td>
                            <td class="ik_talent_role_id export_enabled">'.$Talent_Admin->skills->get_role_name_by_id($enquiry->role_id).'</td>
                            <td class="ik_talent_request_data">
                                <div class="ik_talent_request_data_popup">
                                    <span class="dashicons dashicons-no ik_talent_request_close_data_popup"></span>
                                    <div class="ik_talent_request_data_popup_box">
                                    <h3>'.__( 'Details', 'ik_talent_admin' ).'</h3>
                                    <h4>#'.$enquiry->id.' - '.$enquiry->company.'</h4>
                                        <div class="ik_talent_request_data_popup_container">'.$QA_data.'</div>
                                    </div>
                                </div>
                                '.$icon_view.'
                            </td>
                            <td class="ik_talent_role_id export_enabled" style="display:none! important;">'.$QA_data_export.'</td>
                            <td class="ik_talent_company export_enabled">'.$enquiry->company.'</td>
                            <td class="ik_talent_contact_name export_enabled">'.$enquiry->contact_name.'</td>
                            <td class="ik_talent_email export_enabled">'.$enquiry->email.'</td>
                            <td class="ik_talent_phone export_enabled">'.$enquiry->phone.'</td>
                            <td class="ik_talent_submited_date export_enabled">'.$enquiry->submited_date.'</td>
                            <td iddata="'.$enquiry->id.'">
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
                $listing .= $talent_admin->paginator($this->qty_records(), $qty, $this->requests_admin_url);
            
            return $listing;
            
        } else {
            if ($search != NULL){
                $listing = $searchBar.'
                <div id="ik_talent_existing">
                    <p>'.__( 'Results not found', 'ik_talent_admin' ).'</p>
                    <p class="show-all-button"><a href="'.$this->requests_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>
                </div>';

                return $listing;
            } else {
                return '<p>'.__( 'Nothing to show yet!', 'ik_talent_admin' ).'</p>';
            }
        }
        
        return false;
    }    

    //delete request enquiry by ID
    public function delete($req_id){
        $req_id = absint($req_id);
        global $wpdb;
        $wpdb->delete( $this->db_table_requests , array( 'id' => $req_id ) );
        
        return true;
    }

}
?>