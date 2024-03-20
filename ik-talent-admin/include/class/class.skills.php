<?php
/*

Talents Admin | Class Ik_Talent_Skills
Created: 15/07/2023
Update: 02/08/2023
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}
 
class Ik_Talent_Skills{

    public $skills_admin_url;
    private $db_table_skill_roles;
    private $db_table_skills;

    public function __construct() {

        global $wpdb;
        $this->skills_admin_url = get_admin_url().'admin.php?page='.IK_TALENTS_MENU_VAL_SKILLS;
        $this->db_table_skill_roles = $wpdb->prefix . "ik_talents_skill_roles";
        $this->db_table_skills = $wpdb->prefix . "ik_talents_skills";
    }

    //Get table names 
    public function get_table_name($type = 'base'){

        if($type == 'base'){
            return $this->db_table_skills;            
        } else {
            return $this->db_table_skill_roles;
        }
    }
    
    //Create skill
    public function create($args = array()){

        if(isset($args['name']) && isset($args['role_id'])){

            $name = sanitize_text_field($args['name']);
            $role_id = intval($args['role_id']);
        
            if ($role_id > 0 && !empty(trim($name))){
                
                $data_insert  = array (
                            'role_id'=> $role_id,	
                            'name'=> $name,	
                            'date_created'=> current_time( 'mysql' ),
                            'date_updated'=> current_time( 'mysql' ),
                );
                
                global $wpdb;
                $rowResult = $wpdb->insert($this->db_table_skills, $data_insert);   
                $skill_id = $wpdb->insert_id;
                
                return $skill_id;
          
            }

        }
        
        return false;
    }

    //method to add new role for skills
    public function add_role($role_name = '', $description = '', $order_n = 'no'){
        $role_name = sanitize_text_field($role_name);
        $role_name = str_replace('\\', '', $role_name);
        $description = sanitize_textarea_field($description);
        $description = str_replace('\\', '', $description);
        
        
        if (!empty(trim($role_name))){
            
            $insert_data  = array (
                'role_name'=> $role_name,
                'date_created'=> current_time( 'mysql' ),
                'date_updated'=> current_time( 'mysql' ),
            );

            if($description != ''){
                $insert_data['description'] = $description;
            }

            if(is_int($order_n)){
                $insert_data['order_n'] = absint($order_n);
            }         

            global $wpdb;
            $rowResult = $wpdb->insert($this->db_table_skill_roles, $insert_data);   
      
        }
        
        return false;
    }

    //method to edit skill name or role id
    public function edit($args = array()){
        $skill_id = (isset($args['skill_id'])) ? intval($args['skill_id']) : 0;
        $new_skill_name = sanitize_text_field($args['skill_name']);
        $new_skill_name = str_replace('\\', '', $new_skill_name);

        if(!empty(trim($new_skill_name)) && $skill_id > 0){

            $data_update  = array (
                'name' => $new_skill_name,
                'date_updated'=> current_time( 'mysql' ),
            );

            if(isset($args['role_id'])){
                $role_id = intval($args['role_id']);
                if($role_id > 0){
                    $data_update['role_id'] = $role_id;
                }
            }

            global $wpdb;
            $where = [ 'id' => $skill_id ];
            $rowResult = $wpdb->update($this->db_table_skills,  $data_update, $where);

            return $rowResult;
        }
        
        return false;
    }

    //method to edit skill role
    public function edit_role($role_id = 0, $new_role_name = '', $description = '', $order_n = 'no'){
        $role_id = intval($role_id);
        $new_role_name = sanitize_text_field($new_role_name);
        $new_role_name = str_replace('\\', '', $new_role_name);
        $description = sanitize_textarea_field($description);
        $description = str_replace('\\', '', $description);

        if(!empty(trim($new_role_name)) && $role_id > 0){

            $data_update  = array (
                'role_name' => $new_role_name,
                'date_updated'=> current_time( 'mysql' ),
            );

            $data_update['description'] = $description;

            if(is_int($order_n)){
                $data_update['order_n'] = absint($order_n);
            }

            global $wpdb;
            $where = [ 'id_role' => $role_id ];
            $rowResult = $wpdb->update($this->db_table_skill_roles,  $data_update, $where);

            return $rowResult;
        }
        
        return false;
    }

    //Get skill data by ID
    public function get_by_id($skill_id = 0){
        $skill_id = absint($skill_id);
        
        if ( $skill_id > 0){
            
            global $wpdb;
            $query = "SELECT * FROM ".$this->db_table_skills." WHERE id = ".$skill_id;
            $skill = $wpdb->get_results($query);
    
            if (isset($skill[0]->id)){ 
                return $skill[0];
            }
        }
        
        return false;
        
    }

    //Get all skills data
    public function get_skills(){
                    
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_skills." ORDER BY name ASC";
        $skills = $wpdb->get_results($query);

        if (isset($skills[0]->id)){ 
            return $skills;
        }
        
        return false;
        
    }

    //Get all skills data based on role_id
    public function get_skills_by_role($role_id){
        $role_id = absint($role_id);
                
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_skills." WHERE role_id = ".$role_id." ORDER BY name ASC";
        $skills = $wpdb->get_results($query);

        if (isset($skills[0]->id)){ 
            return $skills;
        }
        
        return false;
        
    }

    //Get skills data from array of ids
    public function get_skills_data($skills_array){
        //I make sure first if it's serialized
        $skills_array = (is_serialized($skills_array)) ? maybe_unserialize($skills_array) : $skills_array;

        //I make sure is not an array
        if(is_array($skills_array)){
            foreach($skills_array as $skill_id){
                $skill = $this->get_by_id($skill_id);

                if($skill){
                    $skills[] = $skill;
                }
            }  

            //if any of the skill ids were assigned to an existing array
            if(isset($skills)){
                return $skills;
            }    
        }
        
        return false;
        
    }

    //Get role data by ID
    public function get_role_by_id($role_id = 0){
        $role_id = absint($role_id);
        
        if ( $role_id > 0){
            
            global $wpdb;
            $query = "SELECT * FROM ".$this->db_table_skill_roles." WHERE id_role = ".$role_id;
            $role = $wpdb->get_results($query);
    
            if (isset($role[0]->id_role)){ 
                return $role[0];
            }
        }
        
        return false;
        
    }

    //Get role name by ID
    public function get_role_name_by_id($role_id = 0){
        $role_id = absint($role_id);
        
        if ( $role_id > 0){
            
            global $wpdb;
            $query = "SELECT role_name FROM ".$this->db_table_skill_roles." WHERE id_role = ".$role_id;
            $role = $wpdb->get_results($query);
    
            if (isset($role[0]->role_name)){ 
                return $role[0]->role_name;
            }
        }
        
        //nothing found returns empty
        return '';
        
    }

    //Get all skills roles
    public function get_roles(){
                
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_skill_roles." ORDER BY order_n ASC";
        $roles = $wpdb->get_results($query);

        if (isset($roles[0]->id_role)){ 
            return $roles;
        }
        
        return false;
        
    }

    //method to get popular skills by role id
    public function get_popular_by_role_id($role_id = 0){
        $role_id = intval($role_id);

        if ( $role_id > 0){
            
            global $wpdb;
            $query = "SELECT * FROM ".$this->db_table_skills." WHERE role_id = ".$role_id." AND popular = 1";
            $skills = $wpdb->get_results($query);
    
            if (isset($skills[0]->role_id)){ 

                return $skills;
            }
        }
        
        return false;
    }

    //get popular skill dashicon icon
    public function get_popular_icon($popular = 0){
        $icon_popular = ($popular == 1) ? '<span class="dashicons dashicons-star-filled"></span>' : '<span class="dashicons dashicons-star-empty"></span>';

        return $icon_popular;
    }

    //method to change popularity status of skills
    public function change_popular($id_skill){
        $id_skill = intval($id_skill);

        //default icon
        $icon_popular = $this->get_popular_icon();
        
        
        if ($id_skill > 0){
            $skill = $this->get_by_id($id_skill);

            //if skill exists
            if($skill){
                $popular_change = ($skill->popular == 0) ? 1 : 0;

                //change skill popular value in db
                $data_update = array (
                    'popular'       => $popular_change,
                    'date_updated'=> current_time( 'mysql' )
                );

                global $wpdb;
                $where = [ 'id' => $id_skill ];
                $rowResult = $wpdb->update($this->db_table_skills, $data_update, $where);

                $icon_popular = $this->get_popular_icon($popular_change);
            }
        }
        
        return $icon_popular;
    }

    //Count the quantity of skill records
    public function qty_records(){
    
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_skills;
        $result = $wpdb->get_results($query);

        if (isset($result[0]->id)){ 
            
            $count_result = count($result);

            return $count_result;
            
        } else {
            return false;
        }
    }

    //List skills
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
        $nameClass = $empty;
        $roleClass = $empty;
        $popularClass = $empty;
    
        
        if ($orderby != 'id'){	
            if ($orderby == 'name'){
                $orderQuery = ' ORDER BY '.$this->db_table_skills.'.name '.$orderDir;
                $nameClass = $orderClass;
            } else if ($orderby == 'popular'){
                $orderQuery = ' ORDER BY '.$this->db_table_skills.'.popular '.$orderDir;
                $popularClass = $orderClass;
            } else {
                $orderQuery = ' ORDER BY '.$this->db_table_skill_roles.'.role_name '.$orderDir;
                $roleClass = $orderClass;
            }
        } else {
            $orderQuery = ' ORDER BY '.$this->db_table_skills.'.id '.$orderDir;
            $idClass = $orderClass;
        }

        $classData = array(
            'id' => $idClass,
            'name' => $nameClass,
            'role' => $roleClass,
            'popular' => $popularClass,
        );

        if ($search != NULL){ 
            //Search by lot number, product name, botanical name and country of origin
            $where = " WHERE ".$this->db_table_skills.".name LIKE '%".$search."%' AND ".$this->db_table_skills.".role_id = ".$this->db_table_skill_roles.".id_role";
        } else {
            $where = " WHERE ".$this->db_table_skills.".role_id = ".$this->db_table_skill_roles.".id_role";
            $search = '';
        }

        $groupby = (isset($groupby)) ? $groupby : " GROUP BY ".$this->db_table_skills.".id ";

        global $wpdb;

        $query = "SELECT ".$this->db_table_skills.".id, ".$this->db_table_skills.".name, ".$this->db_table_skills.".popular, ".$this->db_table_skill_roles.".role_name
        FROM ".$this->db_table_skills.", ".$this->db_table_skill_roles." ".$where.$groupby.$orderQuery.$offsetList;

        $skills = $wpdb->get_results($query);
        $skills_data = array(
            'data' => $skills,
            'class' => $classData,
            'search_value' => $search,        
        );

        return $skills_data;

    }

    //Method to show list of skills for backend
    public function get_list_skills_wrapper_backend($qty = 30){

        $qty = absint($qty);

        $skills_data = $this->get_list($qty);
        $skills = $skills_data['data'];;
        $search = $skills_data['search_value'];;

        //classes for columns that are filtered
        $classData = $skills_data['class'];

        $idClass = $classData['id'];
        $nameClass = $classData['name'];
        $roleClass = $classData['role'];
        $popularClass = $classData['popular'];


        // If data exists
        if (isset($skills[0]->id)){

            $columnsheading = '<tr>
                <th><input type="checkbox" class="select_all" /></th>
                <th order="id" class="worder '.$idClass.'">'.__( 'ID', 'ik_talent_admin' ).' <span class="sorting-indicator '.$idClass.'"></span></th>
                <th order="name" class="wide-data worder '.$nameClass.'">'.__( 'Skill', 'ik_talent_admin' ).' <span class="sorting-indicator '.$nameClass.'"></span></th>
                <th order="role" class="wide-data worder '.$roleClass.'">'.__( 'Role', 'ik_talent_admin' ).' <span class="sorting-indicator '.$roleClass.'"></span></th>
                <th order="popular" class="wide-data worder '.$popularClass.'">'.__( 'Popular', 'ik_talent_admin' ).' <span class="sorting-indicator '.$popularClass.'"></span></th>
                <th class="wide-actions">
                    <button class="ik_talent_button_delete_bulk button action">'.__( 'Delete', 'ik_talent_admin' ).'</button></td>
                </th>
            </tr>';

            $searchBar = '<p class="search-box">
                <label class="screen-reader-text" for="tag-search-input">Search skill:</label>
                <input type="search" id="tag-search-input" name="search" value="'.$search.'">
                <input type="submit" id="searchbutton" class="button" value="'.__( 'Search', 'ik_talent_admin' ).'">
            </p>';
            $listing = '
            <div class="tablenav-pages">'.__( 'Total', 'ik_talent_admin' ).': '.$this->qty_records().' - '.__( 'Showing', 'ik_talent_admin' ).': '.count($skills).'</div>'.$searchBar;

            if ($search != NULL){
                $listing .= '<p class="show-all-button"><a href="'.$this->skills_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>';
            }

            $listing .= '<table id="ik_talent_existing">
                <thead>
                    '.$columnsheading.'
                </thead>
                <tbody>';
                foreach ($skills as $skill){

                    $popular_star = $this->get_popular_icon($skill->popular);
                                        
                    $listing .= '
                        <tr iddata="'.$skill->id.'">
                            <td><input type="checkbox" class="select_data" /></td>
                            <td class="ik_talent_id">'.$skill->id.'</td>
                            <td class="ik_talent_name">'.$skill->name.'</td>
                            <td class="ik_talent_role">'.$skill->role_name.'</td>
                            <td class="ik_talent_popular"><a href="#" class="button">'.$popular_star.'</a></td>
                            <td iddata="'.$skill->id.'">
                                <a href="'.$this->skills_admin_url.'&edit_id='.$skill->id.'" class="ik_talent_button_edit_skill button action">'.__( 'Edit', 'ik_talent_admin' ).'</a>
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
                $listing .= $talent_admin->paginator($this->qty_records(), $qty, $this->skills_admin_url);
            
            return $listing;
            
        } else {
            if ($search != NULL){
                $listing = $searchBar.'
                <div id="ik_talent_existing">
                    <p>'.__( 'Results not found', 'ik_talent_admin' ).'</p>
                    <p class="show-all-button"><a href="'.$this->skills_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>
                </div>';

                return $listing;
            }
        }
        
        return false;
    }    

    //delete skill by ID
    public function delete($skill_id){
        $skill_id = absint($skill_id);
        global $wpdb;
        $wpdb->delete( $this->db_table_skills , array( 'id' => $skill_id ) );
        
        return true;
    }

    //delete skill role by ID
    public function delete_role($role_id){
        $role_id = absint($role_id);
        global $wpdb;
        $wpdb->delete( $this->db_table_skill_roles , array( 'id_role' => $role_id ) );
        
        return true;
    }
    
    //return skills selector
    public function get_selector($role_id = 0, $ids_selected = array()){
        $role_id = absint($role_id);

        $ids_selected = (is_serialized($ids_selected)) ? maybe_unserialize($ids_selected) : $ids_selected;
        $selected_data = (is_array($ids_selected)) ? count($ids_selected) : 0;
        $existing_skills = $this->get_skills_by_role($role_id);
        $select_options = '<div id="skill_selectors"><div class="skill_selectors_content">';

        if($selected_data > 0 && is_array($ids_selected)){
            $first_option = true;
            foreach($ids_selected as $id_selected){
                $select_options .= '<div class="skill_selectors_wrapper"><select required name="skill_ids[]" class="ik_talent_skill_select"><option value="0">'. __( 'Select skill', 'ik_talent_admin' ).' *</option>';
                if ($existing_skills){
                    $id_selected = intval($id_selected);
                    foreach($existing_skills as $existing_skill){
                        $selected = ($id_selected == $existing_skill->id) ? 'selected' : '';
                        $select_options .= '<option '.$selected.' value="'.$existing_skill->id.'">'.$existing_skill->name.'</option>';
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
            $select_options .= '<div class="skill_selectors_wrapper"><select required name="skill_ids[]" class="ik_talent_skill_select select-w-delete"><option value="0">'. __( 'Select skill', 'ik_talent_admin' ).' *</option>';
            if ($existing_skills){
                foreach($existing_skills as $existing_skill){
                    $select_options .= '<option value="'.$existing_skill->id.'">'.$existing_skill->name.'</option>';
                }
            }
            $select_options .= '</select></div>';
        }

        $select_options .= '</div>';

        if ($existing_skills){
            $select_options .= '<a href="#" id="ik_talent_add_skill_field" class="button">'. __( 'Add skill', 'ik_talent_admin' ).'</a>';

        }
        $select_options .= '</div>';

        return $select_options;
    }

    //return skill roles selector
    public function get_roles_selector($id_selected = 0){

        $select_options = '<select name="role_id" id="role_select">
        <option id="option-selected" value="0">'. __( 'Select Role', 'ik_talent_admin' ).' *</option>';
        $existing_roles = $this->get_roles();
        if ($existing_roles){
            $id_selected = intval($id_selected);
            foreach($existing_roles as $existing_role){
                $selected = ($id_selected == $existing_role->id_role) ? 'selected' : '';
                $select_options .= '<option '.$selected.' value="'.$existing_role->id_role.'">'.$existing_role->role_name.'</option>';
            }
        }
        $select_options .= '</select>';

        return $select_options;
    }

    //create or update skills or roles
    public function update(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if (isset($_POST['new_skill'])){

                $args['name'] = sanitize_text_field($_POST['new_skill']);
                $args['role_id'] = (isset($_POST['role_id'])) ? absint($_POST['role_id']) : 0;
                    
                return $this->create($args);;
            }


            if (isset($_POST['role_name'])){

                if(is_array($_POST['role_name'])){

                    $order_n = 0;
                    foreach($_POST['role_name'] as $key => $role_name){
                        $role_name = sanitize_text_field($role_name);
                        $role_id = (isset($_POST['role_id'][$key])) ? absint($_POST['role_id'][$key]) : 0;
                        $role_description = (isset($_POST['role_description'][$key])) ? sanitize_textarea_field($_POST['role_description'][$key]) : '';
                        $role_description = str_replace('\\', '', $role_description);

                        if(!empty(trim($role_name))){

                            if($role_id > 0){
                                $result = $this->edit_role($role_id, $role_name, $role_description, $order_n);
                            } else {
                                $result = $this->add_role($role_name, $role_description, $order_n);
                            }

                            $order_n = $order_n + 1;

                        }
    
                    }
                    return true;
                }
            }

            if (isset($_POST['skill_id']) && isset($_POST['edit_skill'])){

                $args['skill_id'] = absint($_POST['skill_id']);
                $args['skill_name'] = (isset($_POST['edit_skill'])) ? sanitize_text_field($_POST['edit_skill']) : false;
                $args['role_id'] = (isset($_POST['role_id'])) ? intval($_POST['role_id']) : 0;

                return $this->edit($args);
            }
        }
        return false;
    }

    //method to list boxes of popular skills to select
    public function get_popular_select_list($role_id = 0){
        $role_id = intval($role_id);
        $output = '';

        //get popular skills for the role id selected
        $skills = $this->get_popular_by_role_id($role_id);

        if ($skills){
            foreach($skills as $skill){
                if($skill->popular == 1){
                    $output .= '<button type="button" class="" data_name="popular-skill" data_value="'.$skill->id.'"><span class="symbol_btn">+</span>'.$skill->name.'</button>';
                }
            }
        }
        
        return $output;
    }    

}

?>