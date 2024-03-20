<?php
/*

Talents Admin | Class Ik_Talent_TechStack
Created: 28/10/2023
Update: 28/10/2023
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}

class Ik_Talent_TechStack{

    public $techstack_admin_url;
    private $db_table_techstack;
 
    public function __construct() {

        global $wpdb;
        $this->techstack_admin_url = get_admin_url().'admin.php?page='.IK_TALENTS_MENU_VAL_TECHSTACKS;
        $this->db_table_techstack = $wpdb->prefix . "ik_talents_techstacks";
    }

    //Get table names 
    public function get_table_name(){
        return $this->db_table_techstack;
    }
    
    //Create techstack
    public function create($args = array()){

        if(isset($args['name'])){

            $name = sanitize_text_field($args['name']);
        
            if (!empty(trim($name))){
                
                $data_insert  = array (
                            'name'=> $name,	
                            'date_created'=> current_time( 'mysql' ),
                            'date_updated'=> current_time( 'mysql' ),
                );
                
                global $wpdb;
                $rowResult = $wpdb->insert($this->db_table_techstack, $data_insert);   
                $techstack_id = $wpdb->insert_id;
                
                return $techstack_id;
          
            }

        }
        
        return false;
    }

    //method to edit techstack name
    public function edit($args = array()){
        $techstack_id = (isset($args['techstack_id'])) ? intval($args['techstack_id']) : 0;
        $new_techstack_name = sanitize_text_field($args['techstack_name']);
        $new_techstack_name = str_replace('\\', '', $new_techstack_name);

        if(!empty(trim($new_techstack_name)) && $techstack_id > 0){

            $data_update  = array (
                'name' => $new_techstack_name,
                'date_updated'=> current_time( 'mysql' ),
            );

            global $wpdb;
            $where = [ 'id' => $techstack_id ];
            $rowResult = $wpdb->update($this->db_table_techstack,  $data_update, $where);

            return $rowResult;
        }
        
        return false;
    }

    //Get techstack data by ID
    public function get_by_id($techstack_id = 0){
        $techstack_id = absint($techstack_id);
        
        if ( $techstack_id > 0){
            
            global $wpdb;
            $query = "SELECT * FROM ".$this->db_table_techstack." WHERE id = ".$techstack_id;
            $techstack = $wpdb->get_results($query);
    
            if (isset($techstack[0]->id)){ 
                return $techstack[0];
            }
        }
        
        return false;
        
    }

    //Get all techstacks data
    public function get_techstacks(){
                    
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_techstack." ORDER BY name ASC";
        $techstacks = $wpdb->get_results($query);

        if (isset($techstacks[0]->id)){ 
            return $techstacks;
        }
        
        return false;
        
    }

    //Get techstacks data from array of ids
    public function get_techstacks_data($techstacks_array){
        //I make sure first if it's serialized
        $techstacks_array = (is_serialized($techstacks_array)) ? maybe_unserialize($techstacks_array) : $techstacks_array;

        //I make sure is not an array
        if(is_array($techstacks_array)){
            foreach($techstacks_array as $techstack_id){
                $techstack = $this->get_by_id($techstack_id);

                if($techstack){
                    $techstacks[] = $techstack;
                }
            }  

            //if any of the techstack ids were assigned to an existing array
            if(isset($techstacks)){
                return $techstacks;
            }    
        }
        
        return false;
        
    }

    //get popular techstack dashicon icon
    public function get_popular_icon($popular = 0){
        $icon_popular = ($popular == 1) ? '<span class="dashicons dashicons-star-filled"></span>' : '<span class="dashicons dashicons-star-empty"></span>';

        return $icon_popular;
    }

    //method to change popularity status of techstacks
    public function change_popular($id_techstack){
        $id_techstack = intval($id_techstack);

        //default icon
        $icon_popular = $this->get_popular_icon();
        
        
        if ($id_techstack > 0){
            $techstack = $this->get_by_id($id_techstack);

            //if techstack exists
            if($techstack){
                $popular_change = ($techstack->popular == 0) ? 1 : 0;

                //change techstack popular value in db
                $data_update = array (
                    'popular'       => $popular_change,
                    'date_updated'=> current_time( 'mysql' )
                );

                global $wpdb;
                $where = [ 'id' => $id_techstack ];
                $rowResult = $wpdb->update($this->db_table_techstack, $data_update, $where);

                $icon_popular = $this->get_popular_icon($popular_change);
            }
        }
        
        return $icon_popular;
    }

    //Count the quantity of techstack records
    public function qty_records(){
    
        global $wpdb;
        $query = "SELECT * FROM ".$this->db_table_techstack;
        $result = $wpdb->get_results($query);

        if (isset($result[0]->id)){ 
            
            $count_result = count($result);

            return $count_result;
            
        } else {
            return false;
        }
    }

    //List techstacks
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
        $popularClass = $empty;
    
        
        if ($orderby != 'id'){	
            if ($orderby == 'name'){
                $orderQuery = ' ORDER BY '.$this->db_table_techstack.'.name '.$orderDir;
                $nameClass = $orderClass;
            } else if ($orderby == 'popular'){
                $orderQuery = ' ORDER BY '.$this->db_table_techstack.'.popular '.$orderDir;
                $popularClass = $orderClass;
            }
        } else {
            $orderQuery = ' ORDER BY '.$this->db_table_techstack.'.id '.$orderDir;
            $idClass = $orderClass;
        }

        $classData = array(
            'id' => $idClass,
            'name' => $nameClass,
            'popular' => $popularClass,
        );

        if ($search != NULL){ 
            //Search by lot number, product name, botanical name and country of origin
            $where = " WHERE ".$this->db_table_techstack.".name LIKE '%".$search."%'";
        } else {
            $where = " ";
            $search = '';
        }

        $groupby = (isset($groupby)) ? $groupby : " GROUP BY ".$this->db_table_techstack.".id ";

        global $wpdb;

        $query = "SELECT ".$this->db_table_techstack.".id, ".$this->db_table_techstack.".name, ".$this->db_table_techstack.".popular
        FROM ".$this->db_table_techstack." ".$where.$groupby.$orderQuery.$offsetList;

        $techstacks = $wpdb->get_results($query);
        $techstacks_data = array(
            'data' => $techstacks,
            'class' => $classData,
            'search_value' => $search,        
        );

        return $techstacks_data;

    }

    //Method to show list of techstacks for backend
    public function get_list_techstacks_wrapper_backend($qty = 30){

        $qty = absint($qty);

        $techstacks_data = $this->get_list($qty);
        $techstacks = $techstacks_data['data'];;
        $search = $techstacks_data['search_value'];;

        //classes for columns that are filtered
        $classData = $techstacks_data['class'];

        $idClass = $classData['id'];
        $nameClass = $classData['name'];
        $popularClass = $classData['popular'];


        // If data exists
        if (isset($techstacks[0]->id)){

            $columnsheading = '<tr>
                <th><input type="checkbox" class="select_all" /></th>
                <th order="id" class="worder '.$idClass.'">'.__( 'ID', 'ik_talent_admin' ).' <span class="sorting-indicator '.$idClass.'"></span></th>
                <th order="name" class="wide-data worder '.$nameClass.'">'.__( 'Tech Stack', 'ik_talent_admin' ).' <span class="sorting-indicator '.$nameClass.'"></span></th>
                <th order="popular" class="wide-data worder '.$popularClass.'">'.__( 'Popular', 'ik_talent_admin' ).' <span class="sorting-indicator '.$popularClass.'"></span></th>
                <th class="wide-actions">
                    <button class="ik_talent_button_delete_bulk button action">'.__( 'Delete', 'ik_talent_admin' ).'</button></td>
                </th>
            </tr>';

            $searchBar = '<p class="search-box">
                <label class="screen-reader-text" for="tag-search-input">Search techstack:</label>
                <input type="search" id="tag-search-input" name="search" value="'.$search.'">
                <input type="submit" id="searchbutton" class="button" value="'.__( 'Search', 'ik_talent_admin' ).'">
            </p>';
            $listing = '
            <div class="tablenav-pages">'.__( 'Total', 'ik_talent_admin' ).': '.$this->qty_records().' - '.__( 'Showing', 'ik_talent_admin' ).': '.count($techstacks).'</div>'.$searchBar;

            if ($search != NULL){
                $listing .= '<p class="show-all-button"><a href="'.$this->techstack_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>';
            }

            $listing .= '<table id="ik_talent_existing">
                <thead>
                    '.$columnsheading.'
                </thead>
                <tbody>';
                foreach ($techstacks as $techstack){

                    $popular_star = $this->get_popular_icon($techstack->popular);
                                        
                    $listing .= '
                        <tr iddata="'.$techstack->id.'">
                            <td><input type="checkbox" class="select_data" /></td>
                            <td class="ik_talent_id">'.$techstack->id.'</td>
                            <td class="ik_talent_name">'.$techstack->name.'</td>
                            <td class="ik_talent_popular"><a href="#" class="button">'.$popular_star.'</a></td>
                            <td iddata="'.$techstack->id.'">
                                <a href="'.$this->techstack_admin_url.'&edit_id='.$techstack->id.'" class="ik_talent_button_edit_techstack button action">'.__( 'Edit', 'ik_talent_admin' ).'</a>
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
                $listing .= $talent_admin->paginator($this->qty_records(), $qty, $this->techstack_admin_url);
            
            return $listing;
            
        } else {
            if ($search != NULL){
                $listing = $searchBar.'
                <div id="ik_talent_existing">
                    <p>'.__( 'Results not found', 'ik_talent_admin' ).'</p>
                    <p class="show-all-button"><a href="'.$this->techstack_admin_url.'" class="button button-primary">'.__( 'Show All', 'ik_talent_admin' ).'</a></p>
                </div>';

                return $listing;
            }
        }
        
        return false;
    }    

    //delete techstack by ID
    public function delete($techstack_id){
        $techstack_id = absint($techstack_id);
        global $wpdb;
        $wpdb->delete( $this->db_table_techstack , array( 'id' => $techstack_id ) );
        
        return true;
    }

    //return techstacks selector
    public function get_selector($ids_selected = array()){

        $ids_selected = (is_serialized($ids_selected)) ? maybe_unserialize($ids_selected) : $ids_selected;
        $selected_data = (is_array($ids_selected)) ? count($ids_selected) : 0;
        $existing_techstacks = $this->get_techstacks();
        $select_options = '<div id="techstack_selectors"><div class="techstack_selectors_content">';

        if($selected_data > 0 && is_array($ids_selected)){
            $first_option = true;
            foreach($ids_selected as $id_selected){
                $select_options .= '<div class="techstack_selectors_wrapper"><select name="techstack_ids[]" class="ik_talent_techstack_select"><option value="0">'. __( 'Select techstack', 'ik_talent_admin' ).'</option>';
                if ($existing_techstacks){
                    $id_selected = intval($id_selected);
                    foreach($existing_techstacks as $existing_techstack){
                        $selected = ($id_selected == $existing_techstack->id) ? 'selected' : '';
                        $select_options .= '<option '.$selected.' value="'.$existing_techstack->id.'">'.$existing_techstack->name.'</option>';
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
            $select_options .= '<div class="techstack_selectors_wrapper"><select  name="techstack_ids[]" class="ik_talent_techstack_select"><option value="0">'. __( 'Select techstack', 'ik_talent_admin' ).' *</option>';
            if ($existing_techstacks){
                foreach($existing_techstacks as $existing_techstack){
                    $select_options .= '<option value="'.$existing_techstack->id.'">'.$existing_techstack->name.'</option>';
                }
            }
            $select_options .= '</select></div>';
        }

        $select_options .= '</div>';

        if ($existing_techstacks){
            $select_options .= '<a href="#" id="ik_talent_add_techstack_field" class="button">'. __( 'Add Tech Stack', 'ik_talent_admin' ).'</a>';

        }
        $select_options .= '</div>';

        return $select_options;
    }

    //create or update tech stacks
    public function update(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if (isset($_POST['new_techstack'])){

                $args['name'] = sanitize_text_field($_POST['new_techstack']);
                    
                return $this->create($args);;
            }

            if (isset($_POST['techstack_id']) && isset($_POST['edit_techstack'])){

                $args['techstack_id'] = absint($_POST['techstack_id']);
                $args['techstack_name'] = (isset($_POST['edit_techstack'])) ? sanitize_text_field($_POST['edit_techstack']) : false;

                return $this->edit($args);
            }
        }
        return false;
    }

    //method to list boxes of popular techstacks to select
    public function get_popular_select_list(){
        $output = '';

        //get popular tech stacks
        $techstacks = $this->get_techstacks();

        if ($techstacks){
            foreach($techstacks as $techstack){
                if($techstack->popular == 1){
                    $output .= '<button type="button" class="" data_name="popular-techstack" data_value="'.$techstack->id.'"><span class="symbol_btn">+</span>'.$techstack->name.'</button>';
                }
            }
        }
        
        return $output;
    }    

    //Get tech stack data from array of ids
    public function get_data($techstack_array){
        //I make sure first if it's serialized
        $techstack_array = (is_serialized($techstack_array)) ? maybe_unserialize($techstack_array) : $techstack_array;

        //I make sure is not an array
        if(is_array($techstack_array)){
            foreach($techstack_array as $techstack_id){
                $techstack = $this->get_by_id($techstack_id);

                if($techstack){
                    $techstacks[] = $techstack;
                }
            }  

            //if any of the skill ids were assigned to an existing array
            if(isset($techstacks)){
                return $techstacks;
            }    
        }
        
        return false;
        
    }

}

?>