<?php
/*

View and Edit Enquiries
Created: 20/07/2022
Update: 02/08/2023
Author: Gabriel Caroprese

*/
if ( ! defined('ABSPATH')) exit('restricted access');

$talent_admin = new Ik_Talent_Admin();

?>
<div id="ik_talent_content" class="wrap">
    <h1><?php echo __( 'Enquiries', 'ik_talent_admin' ) ?></h1>
    <div id ="ik_talent_existing">
        <?php echo $talent_admin->requests->get_list_enquiries_wrapper_backend(); ?>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {

        jQuery('#ik_talent_existing thead .export_enabled_header').each(function() {
            jQuery(this).removeClass("export_enabled_header");
            jQuery(this).addClass("export_enabled");
        });

        jQuery("#ik_talent_existing .ik_talent_request_view_data_popup").on( "click", function() {
            jQuery("#ik_talent_existing .ik_talent_request_data_popup").fadeOut(100);
            let btn_popup = jQuery(this);
            btn_popup.parent().find(".ik_talent_request_data_popup").fadeIn(500);
        });

        jQuery("#ik_talent_existing .ik_talent_request_close_data_popup").on( "click", function() {
            let btn_popup = jQuery(this);
            btn_popup.parent().parent().find(".ik_talent_request_data_popup").fadeOut(100);
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
                    action: "ik_talent_ajax_delete_enquiry_role",
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
                } else if (order == 'role_id'){
                    var orderby = '&orderby=role_id&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'company'){
                    var orderby = '&orderby=company&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'contact_name'){
                    var orderby = '&orderby=contact_name&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'email'){
                    var orderby = '&orderby=email&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'phone'){
                    var orderby = '&orderby=phone&orderdir='+direc;
                    window.location.href = urlnow+orderby;
                } else if (order == 'submited_date'){
                    var orderby = '&orderby=submited_date&orderdir='+direc;
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
                            action: "ik_talent_ajax_delete_enquiry",
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
                    action: 'ik_talent_ajax_delete_enquiry',
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
                action: 'ik_talent_ajax_popular_enquiry',
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