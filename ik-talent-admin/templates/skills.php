<?php
/*

View and Edit Skills
Created: 20/07/2023
Update: 02/08/2023
Author: Gabriel Caroprese

*/
if ( ! defined('ABSPATH')) exit('restricted access');

$talent_admin = new Ik_Talent_Admin();
$update_data = $talent_admin->skills->update();

wp_enqueue_script('jquery-min-ik_talent', IK_TALENT_PUBLIC . '/js/jquery.min.1-11-2.js', array(), '1.11.2', true );
wp_enqueue_script('jquery-ui-ik_talent', IK_TALENT_PUBLIC . '/js/jquery-ui.min.1-11-4.js', array(), '1.11.4', true );
wp_enqueue_script('jquery-ui-tabs', '', array('jquery-ui-core'));

$edit_skill = (isset($_GET['edit_id'])) ? $talent_admin->skills->get_by_id(absint($_GET['edit_id'])) : false;

?>
<div id="ik_talent_content" class="wrap">
    <h1><?php echo __( 'Skills', 'ik_talent_admin' ) ?></h1>
 
    <h2 class="nav-tab-wrapper">
        <a href="#tab-1" class="nav-tab nav-tab-active"><?php echo __( 'Edit Skills', 'ik_talent_admin' ) ?></a>
        <a href="#tab-2" class="nav-tab"><?php echo __( 'Roles', 'ik_talent_admin' ) ?></a>
    </h2>
 
    <div id="tab-1" class="tab-content">
        <div id="ik_talent_add_records">
            <h2><?php echo __( 'Skills', 'ik_talent_admin' ); ?></h2>
            <?php if($edit_skill == true && $update_data != $edit_skill->id){ ?>
            <form action="" method="post" name="update_skill" enctype="multipart/form-data" autocomplete="no">
                <div class="ik_talent_fields">
                    <h3><?php echo __( 'Edit Skill', 'ik_talent_admin' ); ?></h3>
                    <input type="hidden" name="skill_id" value="<?php echo $edit_skill->id; ?>" />
                    <label>
                        <h4><?php echo __( 'Skill', 'ik_talent_admin' ) ?></h4>
                        <input type="text" required name="edit_skill" placeholder="<?php echo __( 'Skill', 'ik_talent_admin' ) ?>" value="<?php echo $edit_skill->name; ?>">
                    </label>
                    <label>
                        <h4><?php echo __( 'Role', 'ik_talent_admin' ) ?></h4>
                        <?php echo $talent_admin->skills->get_roles_selector($edit_skill->role_id); ?>
                    </label>
                </div>
                <input type="submit" class="button button-primary" value="<?php echo __( 'Update Skill', 'ik_talent_admin' ) ?>">
                <br /><br />
                <div>
                    <a href="<?php echo get_site_url().'/wp-admin/admin.php?page='.IK_TALENTS_MENU_VAL_SKILLS;  ?>" class="button"><?php echo __( 'Add New Skill', 'ik_talent_admin' ) ?></a>
                </div>
            </form>
            <?php } else { ?>
            <form action="" method="post" name="new_skill" enctype="multipart/form-data" autocomplete="no">
                <div class="ik_talent_fields">
                    <h3><?php echo __( 'Add New Skill', 'ik_talent_admin' ); ?></h3>
                    <label>
                        <h4><?php echo __( 'Skill', 'ik_talent_admin' ) ?></h4>
                        <input type="text" required name="new_skill" placeholder="<?php echo __( 'Skill', 'ik_talent_admin' ) ?>">
                    </label>
                    <label>
                        <h4><?php echo __( 'Role', 'ik_talent_admin' ) ?></h4>
                        <?php echo $talent_admin->skills->get_roles_selector(); ?>
                    </label>
                </div>
                <input type="submit" class="button button-primary" value="<?php echo __( 'Add Skill', 'ik_talent_admin' ) ?>">
            </form>
            <?php } ?>
        </div>
        <div id ="ik_talent_existing">
            <?php echo $talent_admin->skills->get_list_skills_wrapper_backend(); ?>
        </div>
    </div>

    <div id="tab-2" class="tab-content hide">
        <form action="" id="ik_talent_fields_draggable" method="post" enctype="multipart/form-data" autocomplete="no">
            <div class="ik_talent_fields">
                <ul>
                    <?php
                    $list_roles = $talent_admin->skills->get_roles();
                    if(is_array($list_roles)){
                        foreach ($list_roles as $role){
                            ?>
                            <li class="draggable-item">
                                <input type="hidden" class="role_id_data" name="role_id[]" value="<?php echo $role->id_role; ?>" />
                                <input type="text" required name="role_name[]" placeholder="Role" value="<?php echo $role->role_name; ?>" />
                                <textarea name="role_description[]" placeholder="<?php echo __( 'Description', 'ik_talent_admin' ); ?>"><?php echo $role->description; ?></textarea>
                                <a href="#" class="ik_talent_delete_field button"><?php echo __( 'Delete', 'ik_talent_admin' ); ?></a>
                            </li>
                            <?php
                        }
                    } else {
                        ?>
                        <li class="draggable-item">
                            <input type="hidden" class="role_id_data" name="role_id[]" value="0" />
                            <input type="text" required name="role_name[]" placeholder="Role" />
                            <textarea name="role_description[]" placeholder="<?php echo __( 'Description', 'ik_talent_admin' ); ?>"></textarea>
                            <a href="#" class="ik_talent_delete_field button"><?php echo __( 'Delete', 'ik_talent_admin' ); ?></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <a href="#" class="button button-primary" id="ik_talent_add_fields">Add Roles</a>
            </div>
            <input type="submit" class="button button-primary" value="Save" />
        </form>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        jQuery('a.nav-tab').on('click', function(){
            let tabID = jQuery(this).attr('href');
            jQuery('a.nav-tab').removeClass('nav-tab-active');
            jQuery(this).addClass('nav-tab-active');
            jQuery('.tab-content').addClass('hide');
            jQuery(tabID).removeClass('hide');

            return false;
        });

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

        jQuery('#ik_talent_fields_draggable').on("click",".ik_talent_delete_field", function() {

            let role_id = jQuery(this).parent().find(".role_id_data").val();
            let element_to_delete = jQuery(this).parent();

            if(role_id != '0'){
                var data = {
                    action: "ik_talent_ajax_delete_skill_role",
                    "post_type": "post",
                    "iddata": role_id,
                };  
    
                jQuery.post( ajaxurl, data, function(response) {
                    if (response){
                        element_to_delete.remove();
                    }        
                });
            } else {
                element_to_delete.remove();
            }
            return false;
        });

        jQuery("#ik_talent_fields_draggable #ik_talent_add_fields").on( "click", function() {
            jQuery('#ik_talent_fields_draggable .ik_talent_fields ul').append('<li class="draggable-item"> <input type="hidden" class="role_id_data" name="role_id[]" value="0" /><input type="text" required name="role_name[]" placeholder="Role" /> <textarea name="role_description[]" placeholder="<?php echo __( 'Description', 'ik_talent_admin' ); ?>"></textarea> <a href="#" class="ik_talent_delete_field button">Delete</a></li>');
            return false;
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
        
        init_draggable(jQuery('#ik_talent_fields_draggable .draggable-item'));

        jQuery('#ik_talent_fields_draggable ul').sortable({
            items: '.draggable-item',
            start: function(event, ui) {
                jQuery('#ik_talent_fields_draggable ul').sortable('enable');
            },
        });

        function init_draggable(widget) {
            widget.draggable({
                connectToSortable: '#ik_talent_fields_draggable ul',
                stack: '.draggable-item',
                revert: true,
                revertDuration: 200,
                start: function(event, ui) {
                    jQuery('#ik_talent_fields_draggable ul').sortable('disable');
                }
            });
        }

        jQuery("#ik_talent_existing .ik_talent_button_delete_bulk").on( "click", function() {
            var confirmar = confirm('<?php echo __( 'Are you sure to delete?', 'ik_talent_admin' ); ?>');
            if (confirmar == true) {
                jQuery('#ik_talent_existing tbody tr').each(function() {
                var elemento_borrar = jQuery(this).parent();
                    if (jQuery(this).find('.select_data').prop('checked') == true){
                        
                        var registro_tr = jQuery(this);
                        var iddata = registro_tr.attr('iddata');
                        
                        var data = {
                            action: "ik_talent_ajax_delete_skill",
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
                    action: 'ik_talent_ajax_delete_skill',
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
	
        jQuery('#ik_talent_existing').on('click','td.ik_talent_popular a', function(e){
            e.preventDefault();
            var button_popular = jQuery(this);
            var iddata = button_popular.parent().parent().attr('iddata');
            button_popular.prop('disabled', true);
            
            var data = {
                action: 'ik_talent_ajax_popular_skill',
                "iddata": iddata,
            };  
    
            jQuery.post( ajaxurl, data, function(response) {
                if (response){
                    button_popular.html(response);
                }        
            });
        });
        jQuery('#ik_talent_existing').on('click','#searchbutton', function(e){
            e.preventDefault();
            
            var search_value = jQuery('#tag-search-input').val();
            var urlnow = window.location.href;
            window.location.href = urlnow+"&search="+search_value;

        
        });
    });
</script>