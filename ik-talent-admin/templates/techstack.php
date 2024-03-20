<?php
/*

View and Edit Techstacks
Created: 28/10/2023
Update: 28/10/2023
Author: Gabriel Caroprese

*/
if ( ! defined('ABSPATH')) exit('restricted access');

$talent_admin = new Ik_Talent_Admin();
$update_data = $talent_admin->techstack->update();

wp_enqueue_script('jquery-min-ik_talent', IK_TALENT_PUBLIC . '/js/jquery.min.1-11-2.js', array(), '1.11.2', true );
wp_enqueue_script('jquery-ui-ik_talent', IK_TALENT_PUBLIC . '/js/jquery-ui.min.1-11-4.js', array(), '1.11.4', true );
wp_enqueue_script('jquery-ui-tabs', '', array('jquery-ui-core'));

$edit_techstack = (isset($_GET['edit_id'])) ? $talent_admin->techstack->get_by_id(absint($_GET['edit_id'])) : false;

?>
<div id="ik_talent_content" class="wrap">
    <h1><?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?></h1>
 
    <div id="ik_talent_add_records">
        <h2><?php echo __( 'Techstacks', 'ik_talent_admin' ); ?></h2>
        <?php if($edit_techstack == true && $update_data != $edit_techstack->id){ ?>
        <form action="" method="post" name="update_techstack" enctype="multipart/form-data" autocomplete="no">
            <div class="ik_talent_fields">
                <h3><?php echo __( 'Edit Tech Stack', 'ik_talent_admin' ); ?></h3>
                <input type="hidden" name="techstack_id" value="<?php echo $edit_techstack->id; ?>" />
                <label>
                    <h4><?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?></h4>
                    <input type="text" required name="edit_techstack" placeholder="<?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?>" value="<?php echo $edit_techstack->name; ?>">
                </label>
            </div>
            <input type="submit" class="button button-primary" value="<?php echo __( 'Update Tech Stack', 'ik_talent_admin' ) ?>">
            <br /><br />
            <div>
                <a href="<?php echo get_site_url().'/wp-admin/admin.php?page='.IK_TALENTS_MENU_VAL_SKILLS;  ?>" class="button"><?php echo __( 'Add New Tech Stack', 'ik_talent_admin' ) ?></a>
            </div>
        </form>
        <?php } else { ?>
        <form action="" method="post" name="new_techstack" enctype="multipart/form-data" autocomplete="no">
            <div class="ik_talent_fields">
                <h3><?php echo __( 'Add New Tech Stack', 'ik_talent_admin' ); ?></h3>
                <label>
                    <h4><?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?></h4>
                    <input type="text" required name="new_techstack" placeholder="<?php echo __( 'Tech Stack', 'ik_talent_admin' ) ?>">
                </label>
            </div>
            <input type="submit" class="button button-primary" value="<?php echo __( 'Add Tech Stack', 'ik_talent_admin' ) ?>">
        </form>
        <?php } ?>
    </div>
    <div id ="ik_talent_existing">
        <?php echo $talent_admin->techstack->get_list_techstacks_wrapper_backend(); ?>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {

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
                            action: "ik_talent_ajax_delete_techstack",
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
                    action: 'ik_talent_ajax_delete_techstack',
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
                action: 'ik_talent_ajax_popular_techstack',
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