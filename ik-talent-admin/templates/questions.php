<?php
/*

Talents Admin | Questions Template
Created: 20/07/2023
Update: 18/08/2023
Author: Gabriel Caroprese

*/

if ( ! defined('ABSPATH')) exit('restricted access');
$Talent_Admin = new Ik_Talent_Admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $answers = array(); 
    if (isset($_GET['role_id']) && isset($_POST['question_text']) && isset($_POST['selection_type']) && isset($_POST['answer_text'])){
        
        $role_id = absint($_GET['role_id']);

        if(is_array($_POST['question_text'])){
            
            foreach($_POST['question_text'] as $order => $question_text){
				unset($answer);
                $answers = array();
                $question_text = sanitize_text_field($question_text);
                $question_text = str_replace('\\', '', $question_text);

                //make sure not empty
                if ((trim($question_text)) !== '') {     
                    $order = intval($order);

                    $type_question = $Talent_Admin->sanitize_type_of_question($_POST['selection_type'][$order]);
                    
                    if(isset($_POST['answer_text'][$order])){
                        if(is_array($_POST['answer_text'][$order])){
                            foreach($_POST['answer_text'][$order] as $answer){
                                //make sure not empty
                                if ((trim($answer)) !== '') {
                                    $answers_field = sanitize_text_field($answer);
                                    $answers_field = str_replace('\\', '', $answers_field);
                                    $answers[] = $answers_field;
                                }
                            }
                        }
                    }

                    $text_field = (isset($_POST['add_text_field'][$order])) ? true : false;

                    $questions[] = array(
                        'order' => $order,
                        'question' => $question_text,
                        'type_question' => $type_question,
                        'answers' => $answers,
                        'text_field' => $text_field,
                    );
                }
            }

            if(isset($questions)){
                update_option('ik_talent_questions_'.$role_id, $questions);
            }

        }
    }
}

if (isset($_GET['role_id'])){
    $role_id = absint($_GET['role_id']);

    $requests = $Talent_Admin->requests;
    $field_empty = $requests->get_question_input_field();
    $field_empty_js_code = str_replace(array("\r", "\n", "\t"), '', $field_empty);

    ?>
    <div id="ik_talent_panel_page">
    <h1><?php echo __( 'Questions for', 'ik_talent_admin' ). ' '.$Talent_Admin->skills->get_role_name_by_id($role_id); ?></h1>
    <p><?php echo __( 'Use Shortcode', 'ik_talent_admin' ). ' [IK_TALENT_QUEST]'; ?></p>
    <form action="" id="ik_talent_fields_draggable" method="post" enctype="multipart/form-data" autocomplete="no">
    <div class="ik_talent_fields">
        <ul>
            <?php
            //Existing on demand products
            $list_questions = get_option('ik_talent_questions_'.$role_id);
            if(is_array($list_questions)){
                foreach ($list_questions as $question){
                    echo $requests->get_question_input_field($question);
                }
            } else {
                echo $requests->get_question_input_field();
            }
            ?>
        </ul>
        <a href="#" class="button button-primary" id="ik_talent_add_question"><?php echo __( 'Add Question', 'ik_talent_admin' ) ?></a>
    </div>
    <input type="submit" class="button button-primary" value="Save" />
    </form>
    <a class="button" id="ik_talent_return" href="<?php echo get_admin_url().'admin.php?page='.IK_TALENTS_MENU_VAL_QUEST; ?>"><?php echo __( 'Return', 'ik_talent_admin' ) ?></a> 
    </div>
    <script>
    jQuery(document).ready(function ($) {

        var question_element = '<?php echo $field_empty_js_code; ?>';
        var answer_element = jQuery('.multiple_fields:first-child .answers_choices_container div:first-child').html();

        jQuery('.multiple-choice input').each(function() {
            if(jQuery(this).attr('checked')){
                jQuery(this).prop('checked', true);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var radios = document.querySelectorAll('input[type="radio"]');
            
            radios.forEach(function(radio) {
            radio.addEventListener('click', function() {
                radios.forEach(function(otherRadio) {
                if (otherRadio !== radio) {
                    otherRadio.removeAttribute('checked');
                }
                });
            });
            });
        });

        jQuery('#ik_talent_fields_draggable').on("click",".ik_talent_open_field", function() {
            let button_open = jQuery(this);
            let element_hidden = button_open.parent().parent().find('.talent_expanded_fields');

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

        jQuery('#ik_talent_fields_draggable').on("click",".multiple-choice label", function() {
            let question_item = jQuery(this).parent().parent().parent();
            let selection_value = jQuery(this).find('input').val();
            if(selection_value == 'multiple'){
                question_item.find('.answers_choices').removeClass('hidden');
                question_item.find('.add_text_field').removeClass('hidden');
                question_item.find('.answers_choices_container input').each(function() {
                    jQuery(this).prop('required', true);
                });
            } else if(selection_value == 'skills'){
                question_item.find('.answers_choices').addClass('hidden');
                question_item.find('.add_text_field').removeClass('hidden');
                question_item.find('.answers_choices_container input').each(function() {
                    jQuery(this).prop('required', false);
                });
            } else {
                question_item.find('.answers_choices').addClass('hidden');
                question_item.find('.add_text_field').addClass('hidden');
                question_item.find('.answers_choices_container input').each(function() {
                    jQuery(this).prop('required', false);
                });
            }
            return true;
        });

        jQuery('#ik_talent_fields_draggable').on("click",".ik_talent_add_answer", function() {
            jQuery(this).parent().find('.answers_choices_container').append('<div>'+answer_element+'</div>');
            jQuery(this).parent().find('.answers_choices_container div:last-child input').val('');
            ik_talent_update_order();

            return false;
        });

        jQuery('#ik_talent_fields_draggable').on("click",".ik_talent_delete_answer", function() {
            jQuery(this).parent().remove();
            return false;
        });

        jQuery('#ik_talent_fields_draggable').on("click",".ik_talent_delete_field", function() {
            jQuery(this).parent().parent().remove();
            ik_talent_update_order();
            return false;
        });

        jQuery("#ik_talent_fields_draggable #ik_talent_add_question").on( "click", function() {
            jQuery('#ik_talent_fields_draggable .ik_talent_fields ul').append(question_element);
            ik_talent_update_order();
            jQuery('.multiple-choice input').each(function() {
                if(jQuery(this).attr('checked')){
                    jQuery(this).prop('checked', true);
                }
            });
            return false;
        });

        init_draggable(jQuery('#ik_talent_fields_draggable .draggable-item'));

        jQuery('#ik_talent_fields_draggable ul').sortable({
            items: '.draggable-item',
            start: function(event, ui) {
                jQuery('#ik_talent_fields_draggable ul').sortable('enable');
            },
            stop: function(event, ui) {
                ik_talent_update_order();
            }
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

        function ik_talent_update_order() {
            let order_n = 0;
            let inputQuestion = '';
            let text_field = '';
            let name_inputQuestion = '';
            let name_radioType = '';
            let name_answers = '';
            let name_text_field = '';
            
            jQuery('#ik_talent_fields_draggable .multiple_fields').each(function() {
                inputQuestion = jQuery(this).find('.question input');
                $name_inputQuestion = inputQuestion.attr('name').substring(0, inputQuestion.attr('name').indexOf('[') + 1)+''+order_n+']';
                jQuery(inputQuestion).attr('name', $name_inputQuestion);
                text_field = jQuery(this).find('.add_text_field input');
                $name_text_field = text_field.attr('name').substring(0, text_field.attr('name').indexOf('[') + 1)+''+order_n+']';
                jQuery(text_field).attr('name', $name_text_field);

                jQuery(this).find('.multiple-choice input').each(function() {
                    name_radioType = jQuery(this).attr('name').substring(0, jQuery(this).attr('name').indexOf('[') + 1)+''+order_n+']';
                    jQuery(this).attr('name', name_radioType);
                });
                jQuery(this).find('.answers_choices_container input').each(function() {
                    name_answers = jQuery(this).attr('name').substring(0, jQuery(this).attr('name').indexOf('[') + 1)+''+order_n+'][]';
                    jQuery(this).attr('name', name_answers);
                });

                order_n = order_n + 1;
            });
        }

    });
    </script>

<?php
} else {


    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['privacy_policy_page_id']) && isset($_POST['terms_page_id']) && isset($_POST['after_form_url'])){
            $privacy_page_id = intval($_POST['privacy_policy_page_id']);
            $terms_page_id = intval($_POST['terms_page_id']);          
            $after_form_url = intval($_POST['after_form_url']);          
            $email_answers = sanitize_email($_POST['email_answers']);          
            
            update_option('ik_talent_privacy_policy_page', $privacy_page_id);
            update_option('ik_talent_terms_page', $terms_page_id);
            update_option('ik_talent_after_form_url', $after_form_url);
            if ((trim($email_answers)) !== '') {     
                update_option('ik_talent_email_responses', $email_answers);
            }
        }
    }

    $roles = $Talent_Admin->skills->get_roles();

    if($roles){
        $privacy_page_id = intval(get_option('ik_talent_privacy_policy_page'));
        $terms_page_id = intval(get_option('ik_talent_terms_page'));
        $after_form_url = intval(get_option('ik_talent_after_form_url'));
        $email_answers = (get_option('ik_talent_email_responses')) ? get_option('ik_talent_email_responses') : get_option('admin_email');
        ?>
        <h1><?php echo __( 'Create Quizzes', 'ik_talent_admin' ); ?></h1>
        <p><?php echo __( 'Use Shortcode', 'ik_talent_admin' ). ' [IK_TALENT_QUEST]'; ?></p>
        <form class="ik_talent_regular_fields" action="" method="post" enctype="multipart/form-data">
            <label>
                <?php echo __( 'Privacy Policy Page', 'ik_talent_admin' ); ?>
                <?php echo $Talent_Admin->get_pages_select($privacy_page_id, 'privacy_policy_page_id'); ?>
            </label>
            <label>
                <?php echo __( 'Terms of Service', 'ik_talent_admin' ); ?>
                <?php echo $Talent_Admin->get_pages_select($terms_page_id, 'terms_page_id'); ?>
            </label>
            <label>
                <?php echo __( 'After Form Button', 'ik_talent_admin' ); ?>
                <?php echo $Talent_Admin->get_pages_select($after_form_url, 'after_form_url'); ?>
            </label>
            <label>
                <?php echo __( 'Email to get responses', 'ik_talent_admin' ); ?>
                <input type="email" name="email_answers" style="min-width: 200px;" value="<?php echo $email_answers; ?>" />
            </label>
            <label>
            <input type="submit" class="button-primary" value="<?php echo __( 'Save', 'ik_talent_admin' ); ?>" />
            </label>
        </form>
        <div id="ik_talent_roles_boxes">
            <h2><?php echo __( 'Select Roles:', 'ik_talent_admin' ); ?></h2>
            <?php
            foreach($roles as $role){
            ?>
                <a href="<?php echo get_admin_url().'admin.php?page='.IK_TALENTS_MENU_VAL_QUEST.'&role_id='.$role->id_role; ?>"><?php echo $role->role_name; ?></a>
            <?php
            }
            ?>
        </div>
        <?php
    } else {
        ?>
            <div id="ik_talent_not_found"><?php echo __( 'Create Skill Roles First', 'ik_talent_admin' ); ?></div>
        <?php
    }


?>
<?php
}
?>