<?php
/* 
Talents Admin | Talents Template
Created: 01/21/2022
Last Update: 03/12/2022
Author: Gabriel Caroprese
*/

if ( ! defined('ABSPATH')) exit('restricted access');
// Media uploader
wp_enqueue_media();

$talent_admin = new Ik_Talent_Admin();
$result_update = $talent_admin->talents->update();
$edit_talent = (isset($_GET['edit_id'])) ? $talent_admin->talents->get_by_id(absint($_GET['edit_id'])) : false;
?>

<div id="ik_talent_add_records">
    <h2><?php echo __( 'Professionals', 'ik_talent_admin' ); ?></h2>
    <?php if($edit_talent !== false){ 
        //to see if talent professional is active searching
        $active_checked = ($edit_talent->active == 1) ? 'checked' : '';
        ?>
    <form action="" method="post" name="update_talent" enctype="multipart/form-data" autocomplete="no">
        <div class="ik_talent_fields">	
            <h3><?php echo __( 'Edit Talent', 'ik_talent_admin' ); ?></h3>
            <input type="hidden" name="talent_id" value="<?php echo $edit_talent->id; ?>" />
            <label>
                <h4><?php echo __( 'Active', 'ik_talent_admin' ) ?></h4>
                <input type="checkbox" name="active" value="1" <?php echo $active_checked; ?> /> <?php echo __( 'Active Search', 'ik_talent_admin' ) ?>
            </label>
            <label>
                <h4><?php echo __( 'Name', 'ik_talent_admin' ) ?></h4>
                <input type="text" required name="name" placeholder="<?php echo __( 'Name', 'ik_talent_admin' ) ?>" value="<?php echo $edit_talent->name; ?>">
            </label>
            <label>
                <h4><?php echo __( 'Last Name', 'ik_talent_admin' ) ?></h4>
                <input type="text" required name="lastname" placeholder="<?php echo __( 'Last Name', 'ik_talent_admin' ) ?>" value="<?php echo $edit_talent->lastname; ?>">
            </label>
            <label>
                <h4><?php echo __( 'Email', 'ik_talent_admin' ) ?></h4>
                <input type="email" required name="email" placeholder="<?php echo __( 'Email', 'ik_talent_admin' ) ?>" value="<?php echo $edit_talent->email; ?>">
            </label>
            <label>
                <h4><?php echo __( 'Phone', 'ik_talent_admin' ) ?></h4>
                <input type="text" required name="phone" id="ik_talent_phone_field" placeholder="<?php echo __( 'Phone', 'ik_talent_admin' ) ?>" value="<?php echo $edit_talent->phone; ?>">
            </label>
            <label>
                <h4><?php echo __( 'Languages', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->get_language_selector($edit_talent->languages); ?>
            </label>
            <label>
                <h4><?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->techstack->get_selector($edit_talent->techstack_ids); ?>
            </label>
            <label>
                <h4><?php echo __( 'Role', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->skills->get_roles_selector($edit_talent->role_id); ?>
            </label>
            <label>
                <h4><?php echo __( 'Skills', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->skills->get_selector($edit_talent->role_id, $edit_talent->skill_ids); ?>
            </label>
            <label>
                <h4><?php echo __( 'Details', 'ik_talent_admin' ); ?></h4>
                <textarea name="details"><?php echo $edit_talent->details; ?></textarea>
            </label>
            <label>
                <h4><?php echo __( 'CV - PDF/Doc (Replace)', 'ik_talent_admin' ); ?></h4>
                <input type="file" name="file" /> <br /> 
                <?php 
                if($edit_talent){
                    if($edit_talent->cv_file != ''){?>
                    <a href="<?php echo wp_upload_dir()['baseurl'].'/'.IK_TALENT_CVS_FOLDER_DIR.'/'.$edit_talent->cv_file; ?>" class="button" target="_blank"><?php echo __( 'View', 'ik_talent_admin' ); ?></a>  
                    <?php 
                    }
                } ?>
            </label>
            <label>
                <h4><?php echo __( 'Profile Image', 'ik_talent_admin' ); ?></h4>
                <?php echo $talent_admin->profile_image_uploader($edit_talent->image_id); ?>
            </label>
            <label>
                <h4><?php echo __( 'Experience', 'ik_talent_admin' ); ?></h4>
                <div class="ik_talent_data_profile" id="ik_talent_data_experiences">            
                    <div class="ik_talent_data_profile" id="ik_talent_data_main">
                        <?php echo $talent_admin->get_profile_fields('experience',$edit_talent->profile_data); ?>
                    </div>
                </div>
                <a href="#" class="button button-primary" id="ik_talent_add_experience_box"><?php echo __( 'Add More', 'ik_talent_admin' ); ?></a>
            </label>
            <label>
                <h4><?php echo __( 'Courses / Certifications', 'ik_talent_admin' ); ?></h4>
                <div class="ik_talent_data_profile" id="ik_talent_data_courses">            
                    <div class="ik_talent_data_profile" id="ik_talent_data_main">
                        <?php echo $talent_admin->get_profile_fields('courses', $edit_talent->profile_data); ?>
                    </div>
                </div>
                <a href="#" class="button button-primary" id="ik_talent_add_courses_box"><?php echo __( 'Add More', 'ik_talent_admin' ); ?></a>
            </label>
        </div>
        <input type="submit" class="button button-primary" value="<?php echo __( 'Update Talent', 'ik_talent_admin' ); ?>">
        <br /><br />
        <div>
            <a href="<?php echo get_site_url().'/wp-admin/admin.php?page='.IK_TALENTS_MENU_VAL_ENTRIES;  ?>" class="button"><?php echo __( 'Add New Talent', 'ik_talent_admin' ) ?></a>
        </div>
    </form>
    <?php } else { ?>
    <form action="" method="post" name="new_talent" enctype="multipart/form-data" autocomplete="no">
        <div class="ik_talent_fields">
            <h3><?php echo __( 'Add New Talent', 'ik_talent_admin' ); ?></h3>
            <label>
                <h4><?php echo __( 'Active', 'ik_talent_admin' ) ?></h4>
                <input type="checkbox" name="active" value="1" checked /> <?php echo __( 'Active Search', 'ik_talent_admin' ) ?>
            </label>
            <label>
                <h4><?php echo __( 'Name', 'ik_talent_admin' ) ?></h4>
                <input type="text" required name="name" placeholder="<?php echo __( 'Name', 'ik_talent_admin' ) ?>">
            </label>
            <label>
                <h4><?php echo __( 'Last Name', 'ik_talent_admin' ) ?></h4>
                <input type="text" required name="lastname" placeholder="<?php echo __( 'Last Name', 'ik_talent_admin' ) ?>">
            </label>
            <label>
                <h4><?php echo __( 'Email', 'ik_talent_admin' ) ?></h4>
                <input type="email" required name="email" placeholder="<?php echo __( 'Email', 'ik_talent_admin' ) ?>">
            </label>
            <label>
                <h4><?php echo __( 'Phone', 'ik_talent_admin' ) ?></h4>
                <input type="text" required name="phone" id="ik_talent_phone_field" placeholder="<?php echo __( 'Phone', 'ik_talent_admin' ) ?>">
            </label>
            <label>
                <h4><?php echo __( 'Languages', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->get_language_selector(); ?>
            </label>
            <label>
                <h4><?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->techstack->get_selector(); ?>
            </label>
            <label>
                <h4><?php echo __( 'Role', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->skills->get_roles_selector(); ?>
            </label>
            <label>
                <h4><?php echo __( 'Skills', 'ik_talent_admin' ) ?></h4>
                <?php echo $talent_admin->skills->get_selector(); ?>
            </label>
            <label>
                <h4><?php echo __( 'Details', 'ik_talent_admin' ); ?></h4>
                <textarea name="details"></textarea>
            </label>
            <label>
                <h4><?php echo __( 'CV - PDF/Doc', 'ik_talent_admin' ); ?></h4>
                <input type="file" name="file" />
            </label>
            <label>
                <h4><?php echo __( 'Profile Image', 'ik_talent_admin' ); ?></h4>
                <?php echo $talent_admin->profile_image_uploader(); ?>
            </label>
            <label>
                <h4><?php echo __( 'Experience', 'ik_talent_admin' ); ?></h4>
                <div class="ik_talent_data_profile" id="ik_talent_data_experiences">            
                    <div class="ik_talent_data_profile" id="ik_talent_data_main">
                        <?php echo $talent_admin->get_profile_fields('experience'); ?>
                    </div>
                </div>
                <a href="#" class="button button-primary" id="ik_talent_add_experience_box"><?php echo __( 'Add More', 'ik_talent_admin' ); ?></a>
            </label>
            <label>
                <h4><?php echo __( 'Courses / Certifications', 'ik_talent_admin' ); ?></h4>
                <div class="ik_talent_data_profile" id="ik_talent_data_courses">            
                    <div class="ik_talent_data_profile" id="ik_talent_data_main">
                        <?php echo $talent_admin->get_profile_fields('courses'); ?>
                    </div>
                </div>
                <a href="#" class="button button-primary" id="ik_talent_add_courses_box"><?php echo __( 'Add More', 'ik_talent_admin' ); ?></a>
            </label>
        </div>
        <input type="submit" class="button button-primary" value="<?php echo __( 'Add Talent', 'ik_talent_admin' ) ?>">
    </form>
    <?php } ?>
</div>
<div id ="ik_talent_existing">
    <?php echo $talent_admin->talents->get_list_talents_wrapper_backend(); ?>
</div>

<script>
    jQuery(document).ready(function ($) {
        var experience_field = <?php echo json_encode($talent_admin->get_profile_fields('experience')); ?>;
        var courses_field = <?php echo json_encode($talent_admin->get_profile_fields('courses')); ?>;
        var ik_talent_edit_profile_image = document.getElementById("ik_talent_edit_profile_image");

        jQuery("#ik_talent_existing th .select_all").on( "click", function() {
            if (jQuery(this).attr('selected') != 'selected'){
                jQuery('#ik_talent_existing th .select_all').prop('checked', true);
                jQuery('#ik_talent_existing th .select_all').attr('checked', 'checked');
                jQuery('#ik_talent_existing tbody tr').each(function() {
                    jQuery(this).find('.select_data').prop('checked', true);
                    jQuery(this).find('.select_data').attr('checked', 'checked');
                });        
                jQuery(this).attr('selected', 'selected');
            } else {
                jQuery('#ik_talent_existing th .select_all').prop('checked', false);
                jQuery('#ik_talent_existing th .select_all').removeAttr('checked');
                jQuery('#ik_talent_existing tbody tr').each(function() {
                    jQuery(this).find('.select_data').prop('checked', false);
                    jQuery(this).find('.select_data').removeAttr('checked');
                });   
                jQuery(this).removeAttr('selected');
                
            }
        });
        jQuery("#ik_talent_existing td .select_data").on( "click", function() {
            jQuery('#ik_talent_existing th .select_all').prop('checked', false);
            jQuery('#ik_talent_existing th .select_all').removeAttr('checked');
            jQuery(this).removeAttr('selected');
        });

        jQuery('#ik_talent_existing').on('click','th.worder', function(e){
            e.preventDefault();

            var order = jQuery(this).attr('order');
            var urlnow = window.location.href;
            
            if (order != undefined){
                if (jQuery(this).hasClass('desc')){
                    var direc = 'asc';
                } else {
                    var direc = 'desc';
                }
                if (order == 'id'){
                    var orderby = '&orderby=id&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'name'){
                    var orderby = '&orderby=name&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'role'){
                    var orderby = '&orderby=role&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                }
            }

        });

        jQuery("#ik_talent_existing .ik_talent_button_delete_bulk").on( "click", function() {
            var confirmar = confirm('<?php echo __( 'Are you sure to delete?', 'ik_talent_admin' ); ?>');
            if (confirmar == true) {
                jQuery('#ik_talent_existing tbody tr').each(function() {
                var elemento_borrar = jQuery(this).parent();
                    if (jQuery(this).find('.select_data').prop('checked') == true){
                        
                        var registro_tr = jQuery(this);
                        var iddata = registro_tr.attr('iddata');
                        
                        var data = {
                            action: "ik_talent_ajax_delete_talent",
                            "post_type": "post",
                            "iddata": iddata,
                        };  
            
                        jQuery.post( ajaxurl, data, function(response) {
                            if (response){
                                registro_tr.fadeOut(700);
                                registro_tr.remove();
                            }        
                        });
                    }
                });
            }
            jQuery('#ik_talent_existing th .select_all').attr('selected', 'no');
            jQuery('#ik_talent_existing th .select_all').prop('checked', false);
            jQuery('#ik_talent_existing th .select_all').removeAttr('checked');
            return false;
        });
	
        jQuery('#ik_talent_existing').on('click','td .ik_talent_button_delete', function(e){
            e.preventDefault();
            var confirmar =confirm('<?php echo __( 'Are you sure to delete?', 'ik_talent_admin' ); ?>');
            if (confirmar == true) {
                var iddata = jQuery(this).parent().attr('iddata');
                var registro_tr = jQuery('#ik_talent_existing tbody').find('tr[iddata='+iddata+']');
                
                var data = {
                    action: 'ik_talent_ajax_delete_talent',
                    "iddata": iddata,
                };  
        
                jQuery.post( ajaxurl, data, function(response) {
                    if (response){
                        registro_tr.fadeOut(700);
                        registro_tr.remove();
                    }        
                });
            }
        });
        jQuery('#ik_talent_existing').on('click','#searchbutton', function(e){
            e.preventDefault();
            
            var search_value = jQuery('#tag-search-input').val();
            var urlnow = window.location.href;
            window.location.href = urlnow+"&search="+search_value;

        });
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
        jQuery('#ik_talent_add_records').on('click','#ik_talent_add_skill_field', function(e){
            e.preventDefault();
            var selectors_content = jQuery("#skill_selectors .skill_selectors_content");
            var skill_selector = jQuery("#skill_selectors .skill_selectors_wrapper:first-child");
            skill_selector.clone().appendTo(selectors_content);
            selectors_content.find(".skill_selectors_wrapper:last-child select").val("0");
            selectors_content.find(".skill_selectors_wrapper:last-child").append('<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>');

            return false;
        });
        jQuery('#ik_talent_add_records').on('click','#ik_talent_add_language_field', function(e){
            e.preventDefault();
            var selectors_content = jQuery("#language_selectors .language_selectors_content");
            var skill_selector = jQuery("#language_selectors .language_selectors_wrapper:first-child");
            skill_selector.clone().appendTo(selectors_content);
            selectors_content.find(".language_selectors_wrapper:last-child select").val("0");
            selectors_content.find(".language_selectors_wrapper:last-child").append('<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>');

            return false;
        });
        jQuery('#ik_talent_add_records').on('click','#ik_talent_add_techstack_field', function(e){
            e.preventDefault();
            var selectors_content = jQuery("#techstack_selectors .techstack_selectors_content");
            var techstack_selector = jQuery("#techstack_selectors .techstack_selectors_wrapper:first-child");
            techstack_selector.clone().appendTo(selectors_content);
            selectors_content.find(".techstack_selectors_wrapper:last-child select").val("0");
            selectors_content.find(".techstack_selectors_wrapper:last-child").append('<a href="#" class="ik_talent_delete_field button"><span class="dashicons dashicons-trash"></span></a>');

            return false;
        });

        jQuery('#ik_talent_add_records').on("click",".ik_talent_open_field", function() {
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



        jQuery('#ik_talent_add_records').on("click","#ik_talent_add_experience_box", function() {
            jQuery(this).parent().find('.ik_talent_data_profile_container').parent().append(experience_field);
            ik_talent_update_fields_order();

            return false;
        });
        jQuery('#ik_talent_add_records').on("click","#ik_talent_add_courses_box", function() {
            jQuery(this).parent().find('.ik_talent_data_profile_container').parent().append(courses_field);
            ik_talent_update_fields_order();

            return false;
        });
        jQuery('#ik_talent_add_records').on('click','.ik_talent_delete_field', function(e){
            e.preventDefault();
            jQuery(this).parent().remove();

            return false;
        }); 
        jQuery("#ik_talent_phone_field").on("blur", function() {
            let input_phone = jQuery(this);
            let pattern_phone = /^[0-9+()\-\s]{7,22}$/;
            if (!pattern_phone.test(input_phone.val())) {
                input_phone.val("");
            }
        });
        jQuery('#ik_talent_existing').on('click','.ik_talent_button_view_modal', function(e){
            e.preventDefault();
            let button = jQuery(this);
            let iddata = button.parent().parent().attr('iddata');
            button.find('.dashicons').removeClass('dashicons-visibility');
            button.find('.dashicons').addClass('dashicons-update');

            var data = {
                action: 'ik_talent_ajax_show_backend_talent_data',
                "iddata": iddata,
            };
    
            jQuery.post( ajaxurl, data, function(response) {
                if (response){
                    jQuery("#ik_talent_modal").modal("hide");
                    jQuery("#ik_talent_modal").remove();
                    jQuery("body").append(response);
                    jQuery("#ik_talent_modal").modal("show");
					button.find('.dashicons').removeClass('dashicons-update');
                    button.find('.dashicons').addClass('dashicons-visibility');

                }
            });
            return false;
        });
        jQuery("#csv_export_talents").on( "click", function() {
            var data = {
                action: 'ik_talent_ajax_get_table_all_talents',
            };
            jQuery.post( ajaxurl, data, function(response) {
                if (response){
                    jQuery("#table_export").remove();
                    jQuery("body").append(response);
                    exportTableToCSV_additional_fields('talents'+Date.now()+'.csv')
                }
            });
            
            return false;
        });
        
        jQuery("body").on( "click", "input#ik_talent_upload_profile_img", function(e) {
            e.preventDefault();
            var image_frame;
            if(image_frame){
                image_frame.open();
            }
            // Define image_frame as wp.media object
            image_frame = wp.media({
                title: '<?php echo __( 'Change Image ', 'ik_talent_admin' ); ?>',
                multiple : false,
                library : {
                    type : 'image',
                }
            });

        image_frame.on('close',function() {
                // On close, get selections and save to the hidden input
                // plus other AJAX stuff to refresh the image preview
                var selection =  image_frame.state().get('selection');
                var gallery_ids = new Array();
                var my_index = 0;
                selection.each(function(attachment) {
                    gallery_ids[my_index] = attachment['id'];
                    my_index++;
                });
                
                var ids = gallery_ids.join(",");
                jQuery('input#ik_talent_upload_profile_image_id').val(ids);
                ik_talent_refresh_image(ids);
            });

            image_frame.on('open',function() {
                // On open, get the id from the hidden input
                // and select the appropiate images in the media manager
                var selection =  image_frame.state().get('selection');
                var ids = jQuery('input#ik_talent_upload_profile_image_id').val().split(',');
                ids.forEach(function(id) {
                    var attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            });
            
            image_frame.open();
        });

        // Ajax para cargar datos en input
        function ik_talent_refresh_image(image_id){
            var data = {
                action: 'ik_talent_ajax_upload_profile_img',
                image_id: image_id
            };
        
            jQuery.get(ajaxurl, data, function(response) {
                if (response){
                        ik_talent_edit_profile_image.innerHTML = response;
                }
            });
        }

        jQuery("body").on( "click", "input#ik_talent_delete_profile_img", function(e) {
            e.preventDefault();
            var data = {
                action: 'ik_talent_ajax_upload_profile_img',
                image_id: 0
            };
        
            jQuery.get(ajaxurl, data, function(response) {
                if(response) {
                    ik_talent_edit_profile_image.innerHTML = response;
                }
            });

        });
    });
</script>