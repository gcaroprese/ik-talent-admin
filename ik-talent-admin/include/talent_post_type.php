<?php
/*

Talents Admin | Talent Post Type
Created: 21/10/2023
Update: 29/10/2023
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}

// Register the 'talent' custom post type
function ik_talent_register_talent_post_type() {
    $labels = array(
        'name'               => _x('Talents', 'post type general name', 'ik_talent_admin'),
        'singular_name'      => _x('Talent', 'post type singular name', 'ik_talent_admin'),
        'menu_name'          => _x('Talents', 'admin menu', 'ik_talent_admin'),
        'name_admin_bar'     => _x('Talent', 'add new on admin bar', 'ik_talent_admin'),
        'add_new'            => _x('Add New', 'talent', 'ik_talent_admin'),
        'add_new_item'       => __('Add New Talent', 'ik_talent_admin'),
        'new_item'           => __('New Talent', 'ik_talent_admin'),
        'edit_item'          => __('Edit Talent', 'ik_talent_admin'),
        'view_item'          => __('View Talent', 'ik_talent_admin'),
        'all_items'          => __('Talents', 'ik_talent_admin'),
        'search_items'       => __('Search Talents', 'ik_talent_admin'),
        'parent_item_colon'  => __('Parent Talents:', 'ik_talent_admin'),
        'not_found'          => __('No talents found.', 'ik_talent_admin'),
        'not_found_in_trash' => __('No talents found in Trash.', 'ik_talent_admin')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ik_talent_main',
        'query_var'          => true,
        'rewrite'            => array('slug' => 'talent'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 2,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt')
    );

    register_post_type(IK_TALENTS_POST_TYPE_ID, $args);
}

// Hook into the 'init' action to register the custom post type
add_action('init', 'ik_talent_register_talent_post_type');

//add it to the plugin menu
function ik_talent_add_talent_to_menu() {
    add_submenu_page(
        'ik_talent_main',
        __('Talents', 'ik_talent_admin'), 
        __('Talents', 'ik_talent_admin'),
        'manage_options',
        'edit.php?post_type='.IK_TALENTS_POST_TYPE_ID
    );
}
add_action('ik_talent_main_menu', 'ik_talent_add_talent_to_menu');

// I create the metaboxes for translax_jobs
function ik_talent_data_metaboxes() {
    add_meta_box(
		'ik_talent_post_type_main_role',
		__('Main Role', 'ik_talent_admin'),
		'ik_talent_post_type_main_role',
		IK_TALENTS_POST_TYPE_ID,
		'normal',
		'default'
	);
	add_meta_box(
		'ik_talent_post_type_fields',
		__('Edit Talent', 'ik_talent_admin'),
		'ik_talent_post_type_fields',
		IK_TALENTS_POST_TYPE_ID,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'ik_talent_data_metaboxes' );

//Main Area of Expertise
function ik_talent_post_type_main_role(){
    wp_nonce_field( basename( __FILE__ ), 'ik_talent_post_type_fields_nonce' );
	global $post;
	$databoxes = get_post_meta( $post->ID, 'ik_talent_main_role', true );
	// Output the field
	echo '<input type="text" name="ik_talent_main_role" value="' . esc_textarea( $databoxes ) . '" class="widefat">';
}
//fields connected to the talent_list db
function ik_talent_post_type_fields() {
	global $post;
	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'ik_talent_post_type_fields_nonce' );
	// Output the field
    $talent_admin = new Ik_Talent_Admin();
    if(isset($post->ID)){
        $talent_data = $talent_admin->talents->get_by_post_id($post->ID);
    }
    $id_talent_main = ($talent_data) ? $talent_data->id : '';
    $active = ($talent_data) ? $talent_data->active : '';
    $active_checked = ($active == 0) ? '' : 'checked';
    $name = ($talent_data) ? $talent_data->name : '';
    $lastname =  ($talent_data) ? $talent_data->lastname : '';
    $email = ($talent_data) ? $talent_data->email : '';
    $phone = ($talent_data) ? $talent_data->phone : '';
    $cv_file = ($talent_data) ? $talent_data->cv_file : '';
    $languages = ($talent_data) ? $talent_data->languages : array();
    $techstack_ids = ($talent_data) ? $talent_data->techstack_ids : array();
    $role_id = ($talent_data) ? $talent_data->role_id : 0;
    $skill_ids = ($talent_data) ? $talent_data->skill_ids : array();
    $profile_data = ($talent_data) ? $talent_data->profile_data : array();
    ?>
    <div class="ik_talent_fields">	
        <div class="ik_talent_fields_basic_info talent_post_fields">
            <div class="container">
                <label>
                    <h4><?php echo __( 'Active', 'ik_talent_admin' ) ?></h4>
                    <input type="checkbox" name="active" value="1" <?php echo $active_checked; ?> /> <?php echo __( 'Active Search', 'ik_talent_admin' ) ?>
                </label>
                <label>
                    <h4><?php echo __( 'Name', 'ik_talent_admin' ) ?></h4>
                    <input type="text" required name="name" placeholder="<?php echo __( 'Name', 'ik_talent_admin' ) ?>" value="<?php echo $name; ?>">
                </label>
                <label>
                    <h4><?php echo __( 'Last Name', 'ik_talent_admin' ) ?></h4>
                    <input type="text" required name="lastname" placeholder="<?php echo __( 'Last Name', 'ik_talent_admin' ) ?>" value="<?php echo $lastname; ?>">
                </label>
                <label>
                    <h4><?php echo __( 'Email', 'ik_talent_admin' ) ?></h4>
                    <input type="email" required name="email" placeholder="<?php echo __( 'Email', 'ik_talent_admin' ) ?>" value="<?php echo $email; ?>">
                </label>
            </div>
            <div class="container">
                <label>
                    <h4><?php echo __( 'Phone', 'ik_talent_admin' ) ?></h4>
                    <input type="text" required name="phone" id="ik_talent_phone_field" placeholder="<?php echo __( 'Phone', 'ik_talent_admin' ) ?>" value="<?php echo $phone; ?>">
                </label>
                <label>
                    <h4><?php echo __( 'Languages', 'ik_talent_admin' ) ?></h4>
                    <?php echo $talent_admin->get_language_selector($languages); ?>
                </label>
                <label>
                    <h4><?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?></h4>
                    <?php echo $talent_admin->techstack->get_selector($techstack_ids); ?>
                </label>
            </div>
            <div class="container">
                <label>
                    <h4><?php echo __( 'Role', 'ik_talent_admin' ) ?></h4>
                    <?php echo $talent_admin->skills->get_roles_selector($role_id); ?>
                </label>
                <label>
                    <h4><?php echo __( 'Skills', 'ik_talent_admin' ) ?></h4>
                    <?php echo $talent_admin->skills->get_selector($role_id, $skill_ids); ?>
                </label>
            </div>
        </div>
        <div class="ik_talent_fields_extra_info talent_post_fields">
            <label>
                <h4><?php echo __( 'CV - PDF/Doc', 'ik_talent_admin' ); ?></h4>
                <input type="file" name="file" /> <br /> 
                <?php 
                if($cv_file != ''){?>
                    <a href="<?php echo wp_upload_dir()['baseurl'].'/'.IK_TALENT_CVS_FOLDER_DIR.'/'.$cv_file; ?>" class="button" target="_blank"><?php echo __( 'View', 'ik_talent_admin' ); ?></a>  
                <?php 
                } ?>
            </label>
            <label>
                <h4><?php echo __( 'Experience', 'ik_talent_admin' ); ?></h4>
                <div class="ik_talent_data_profile" id="ik_talent_data_experiences">            
                    <div class="ik_talent_data_profile" id="ik_talent_data_main">
                        <?php echo $talent_admin->get_profile_fields('experience', $profile_data); ?>
                    </div>
                </div>
                <a href="#" class="button button-primary" id="ik_talent_add_experience_box"><?php echo __( 'Add More', 'ik_talent_admin' ); ?></a>
            </label>
            <label>
                <h4><?php echo __( 'Courses / Certifications', 'ik_talent_admin' ); ?></h4>
                <div class="ik_talent_data_profile" id="ik_talent_data_courses">            
                    <div class="ik_talent_data_profile" id="ik_talent_data_main">
                        <?php echo $talent_admin->get_profile_fields('courses', $profile_data); ?>
                    </div>
                </div>
                <a href="#" class="button button-primary" id="ik_talent_add_courses_box"><?php echo __( 'Add More', 'ik_talent_admin' ); ?></a>
            </label>
        </div>
    </div>
    <script>
    jQuery(document).ready(function ($) {
        var experience_field = <?php echo json_encode($talent_admin->get_profile_fields('experience')); ?>;
        var courses_field = <?php echo json_encode($talent_admin->get_profile_fields('courses')); ?>;
        var ik_talent_edit_profile_image = document.getElementById("ik_talent_edit_profile_image");

        jQuery("#role_select").on( "change", function() {
            var element = jQuery(this);
            var skill_selector = jQuery("#skill_selectors");
            var iddata = element.val();
            
            var data = {
                action: 'ik_talent_ajax_update_skills_selector',
                "iddata": iddata,
            };  
    
            jQuery.post( ajaxurl, data, function(response) {
                if (response){
                    skill_selector.replaceWith(response);
                }        
            });                
        });
        jQuery('.ik_talent_fields').on('click','#ik_talent_add_skill_field', function(e){
            e.preventDefault();
            var selectors_content = jQuery("#skill_selectors .skill_selectors_content");
            var skill_selector = jQuery("#skill_selectors .skill_selectors_wrapper:first-child");
            skill_selector.clone().appendTo(selectors_content);
            selectors_content.find(".skill_selectors_wrapper:last-child select").val("0");
            selectors_content.find(".skill_selectors_wrapper:last-child").append('<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>');

            return false;
        });
        jQuery('.ik_talent_fields').on('click','#ik_talent_add_language_field', function(e){
            e.preventDefault();
            var selectors_content = jQuery("#language_selectors .language_selectors_content");
            var skill_selector = jQuery("#language_selectors .language_selectors_wrapper:first-child");
            skill_selector.clone().appendTo(selectors_content);
            selectors_content.find(".language_selectors_wrapper:last-child select").val("0");
            selectors_content.find(".language_selectors_wrapper:last-child").append('<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>');

            return false;
        });
        jQuery('.ik_talent_fields').on('click','#ik_talent_add_techstack_field', function(e){
            e.preventDefault();
            var selectors_content = jQuery("#techstack_selectors .techstack_selectors_content");
            var techstack_selector = jQuery("#techstack_selectors .techstack_selectors_wrapper:first-child");
            techstack_selector.clone().appendTo(selectors_content);
            selectors_content.find(".techstack_selectors_wrapper:last-child select").val("0");
            selectors_content.find(".techstack_selectors_wrapper:last-child").append('<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>');

            return false;
        });

        jQuery('.ik_talent_fields').on("click",".ik_talent_open_field", function() {
            let button_open = jQuery(this);
            let element_hidden = button_open.parent().find('.ik_talent_data_profile_container_inner');

            if(element_hidden.hasClass('hidden')){
                element_hidden.removeClass('hidden');
                button_open.find('.dashicons').removeClass('dashicons-arrow-down');
                button_open.find('.dashicons').addClass('dashicons-arrow-up');
            } else {
                element_hidden.addClass('hidden');
                button_open.find('.dashicons').removeClass('dashicons-arrow-up');
                button_open.find('.dashicons').addClass('dashicons-arrow-down');
            }
            return false;
        });

        function ik_talent_update_fields_order() {
            var order_n_experiences = 0;
            var order_n_courses = 0;
            jQuery('#ik_talent_data_experiences .ik_talent_data_profile_container').each(function(index) {
                jQuery(this).find('input').each(function() {
                    let currentName = jQuery(this).attr('name');
                    let updatedName = currentName.replace(/\[\d+\]/, '[' + order_n_experiences + ']');
                    jQuery(this).attr('name', updatedName);
                });

                jQuery(this).find('textarea').each(function() {
                    let currentName = jQuery(this).attr('name');
                    let updatedName = currentName.replace(/\[\d+\]/, '[' + order_n_experiences + ']');
                    jQuery(this).attr('name', updatedName);
                });
                order_n_experiences++;
            });
            jQuery('#ik_talent_data_courses .ik_talent_data_profile_container').each(function(index) {
                jQuery(this).find('input').each(function() {
                    let currentName = jQuery(this).attr('name');
                    let updatedName = currentName.replace(/\[\d+\]/, '[' + order_n_courses + ']');
                    jQuery(this).attr('name', updatedName);
                });

                jQuery(this).find('textarea').each(function() {
                    let currentName = jQuery(this).attr('name');
                    let updatedName = currentName.replace(/\[\d+\]/, '[' + order_n_courses + ']');
                    jQuery(this).attr('name', updatedName);
                });
                order_n_courses++;
            });
        }

        jQuery('.ik_talent_fields').on("click","#ik_talent_add_experience_box", function() {
            jQuery(this).parent().find('.ik_talent_data_profile_container').parent().append(experience_field);
            ik_talent_update_fields_order();

            return false;
        });
        jQuery('.ik_talent_fields').on("click","#ik_talent_add_courses_box", function() {
            jQuery(this).parent().find('.ik_talent_data_profile_container').parent().append(courses_field);
            ik_talent_update_fields_order();

            return false;
        });
        jQuery('.ik_talent_fields').on('click','.ik_talent_delete_field', function(e){
            e.preventDefault();
            jQuery(this).parent().remove();

            return false;
        });
    }); 
    </script>
<?php
}

//function to save custom post fields for talents
function ik_talent_fields_save_meta( $post_id, $post ) {
	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

    if (isset($_POST['ik_talent_post_type_fields_nonce'])){
    	if ( ! wp_verify_nonce( $_POST['ik_talent_post_type_fields_nonce'], basename(__FILE__) ) ) {
    		return $post_id;
    	}

        //check if post id has a talent id from talent list table assigned
        $profile_img = get_post_meta($post_id, '_thumbnail_id', true);
        $details = $post->post_content;

        $talent_admin = new Ik_Talent_Admin();
        //with false to not update post again
        $talent_id = $talent_admin->talents->update($post_id, false);
        

        //update additional data from post type to talent db table
        $active = ($_POST['active'] == 1) ? 1 : 0;
        $args = array(
            'talent_id' => $talent_id,
            'post_id' => $post_id,
            'details' => $details,
            'image_id' => $profile_img,
            'active' => $active
        );
        $talent_admin->talents->edit($args, false);

        $talent_post_meta['role_id'] = intval( $_POST['role_id'] );
        $talent_post_meta['ik_talent_main_role'] = isset( $_POST['ik_talent_main_role'] ) ? sanitize_text_field( $_POST['ik_talent_main_role'] ) : false;
        $talent_post_meta['ik_talent_main_role'] = str_replace('\\', '', $talent_post_meta['ik_talent_main_role']);

        //save meta data to post
        foreach ( $talent_post_meta as $key => $value ) :
            // Don't store custom data twice
            if ( 'revision' === $post->post_type ) {
                return;
            }
            update_post_meta( $post_id, $key, $value );
            if ( ! $value || empty($value)) {
                // Delete the meta key if there's no value
                delete_post_meta( $post_id, $key );
            }
        endforeach;
    }
}
add_action( 'save_post', 'ik_talent_fields_save_meta', 1, 2 );

//when a post ik_talent is deleted the same happens with the db table talents_list
add_action('before_delete_post', 'ik_talent_post_deleted');
function ik_talent_post_deleted($postid) {
    $talent = new Ik_Talents();
    $deleted = $talent->delete_by_post_id($postid);
}
