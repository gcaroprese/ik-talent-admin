<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/* 
IK Talent Admin Questions Shortcode
Created: 06/08/2023
Last Update: 27/10/2023
Author: Gabriel Caroprese
*/

function ik_talent_questions_shortcode(){
    wp_enqueue_style('dashicons');

    $output = '<style>
    .hidden {
        display: none;
    }
    #ik_talent_questions {
        max-width: 600px;
        margin: 3.5em auto;
        padding: 30px;
        background: #fcfcfc;
        border-radius: 25px;
    }
    #ik_talent_questions .init_message{
        padding: 15px;
        background: #8998c2;
        color: #f7f7f7;
        font-size: 14px;
        margin: 0 auto 20px;
    }
    #ik_talent_questions label {
        display: flex;
        cursor: pointer;
        margin-bottom: 15px;
    }
    #ik_talent_questions input[type="radio"]{
        margin-right: 12px;
    }
    #ik_talent_questions .ik_talent_content_option_text{
        font-weight: 600;
        color: #333;
        font-size: 20px;
    }
    #ik_talent_questions .ik_talent_questions_navbar{
        border-top: 2px solid #f7f7f7;
        padding: 25px 20px;
    }
    #ik_talent_questions .ik_talent_questions_navbar button{
        cursor: pointer;
    }
    #ik_talent_question_back{
        background: transparent;
        border: transparent;
        color: #333;
        text-align: left;
    }
    #ik_talent_question_next{
        float: right;
    }
    #ik_talent_questions .ik_talent_questions_navbar span{
        vertical-align: sub;
    }
    #ik_talent_questions .ik_talent_contact_form_terms{
        font-size: 13px;
        padding-top: 20px;
    }
    #ik_talent_questions .ik_talent_contact_form_terms a{
        text-decoration: underline;
    }
    #ik_talent_questions h5{
        font-size: 14px;
        margin-bottom: 7px;
    }
    #ik_talent_questions button:not(#ik_talent_question_back), #ik_talent_questions .button{
		background-color: #197DBC;
		border-color: #197DBC;
		color: #fff;
	}
    #ik_talent_questions .button{
        padding: 8px 16px;
        border-radius: 9px;
        margin-top: 20px;
        display: inline-block;
    }
	#ik_talent_questions #ik_talent_question_back{
		background-color: transparent;
		border-color: transparent;
		color: #333;
	}
	#ik_talent_questions button{
		padding: 4px 16px;
		margin: 4px 8px 16px 0px;
	}
	#ik_talent_question_start, #ik_talent_question_back, #ik_talent_question_next{
		font-size: 16px;
		line-height: 28px;
		font-weight: 500;
		padding: 9px 36px;
		border-radius: 30px;
		display: inline-block;
		-webkit-appearance: none;
		transition: all ease 0.3s;
		white-space: nowrap;	
    }
	#ik_talent_questions input[type=email], #ik_talent_questions input[type=tel], #ik_talent_questions input[type=text], #ik_talent_questions #ik_talent_questions input[type=url] {
		width: 100%;
		border: 1px solid #666;
		border-radius: 3px;
		padding: 0.5rem 1rem;
		transition: all .3s;
		background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0));
		font-size: 16px;
		line-height: 28px;
		font-weight: 400;
		padding: 8px 20px;
		border-radius: 8px;
		border-style: solid;
		border-width: 1px;
		border-color: #eff5fb;
		background-color: #eff5fb;
		margin-bottom: 20px;
		text-shadow: none;
		box-shadow: none;
		box-sizing: border-box;
		transition: all ease 0.3s;
		color: #5e709d;
		max-width: 260px;
		margin-right: 6px;
	}
    #ik_talent_questions .ik_talent_content_description {
        line-height: 1.3em;
        margin-top: 5px;
    }
    #ik_talent_questions button .symbol_btn{
        padding-right: 7px;
        font-size: 1.3em;
    }
    #ik_talent_questions button.disabled, #ik_talent_questions button[disabled="disabled"]{
        pointer-events: none;
        user-select: none;
        opacity: 0.7;
        background: #ddd;
    }
    #ik_talent_select_data_selector {
        list-style: none;
        border: 1px solid #ccc;
        position: absolute;
        background-color: #fff;
        max-height: 150px;
        overflow-y: auto;
        margin: 0;
        padding: 0;
        z-index: 1;
    }
    
    #ik_talent_select_data_selector li {
        padding: 8px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
    }
    
    #ik_talent_select_data_selector li:hover {
        background-color: #f2f2f2;
    }
    #ik_talent_questions .select_data-choice_input{
        margin-bottom: 24px;
    }
    </style>
    <div id="ik_talent_questions"></div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selector_distance = 0;
           
            var ik_talent_ids_selected = [];

            var talentQuestionsDiv = document.getElementById("ik_talent_questions");
            
            function initialize_data_selection(select_data, ids_selected) {

                ik_talent_ids_selected = ids_selected;

                var ik_talent_select_data_available = select_data;

                var ik_talent_input_select_data = document.getElementById("ik_talent_select_data_input");
                var ik_talent_select_data_selector = document.getElementById("ik_talent_select_data_selector");
                var ik_talent_select_data_selected_area = document.querySelector(".select_data_selected_area");
                var popularChoiceButtons = document.querySelectorAll("#ik_talent_select_data_popular_choice button");

                function getselected_dataNameById(select_id) {
                    let id = parseInt(select_id);
                    let selected_data = ik_talent_select_data_available.find(selected_data => selected_data.id === select_id);
                    if(selected_data === undefined){
                        selected_data = ik_talent_select_data_available.find(selected_data => selected_data.id === id);                    
                    }

                    return selected_data ? selected_data.name : null;
                }

                if (ik_talent_input_select_data !== null) {
                    function ik_talent_select_data_add_btn(name, id) {
                        var select_btn = document.createElement("button");
                        select_btn.innerHTML = \'<span class="symbol_btn">x</span>\' + name;
                    
                        var isAlreadySelected = ik_talent_ids_selected.includes(id);
                        var popularChoiceButton = jQuery(\'#ik_talent_select_data_popular_choice button[data_value="\' + id + \'"]\');

                        popularChoiceButton.attr("disabled","disabled");
                        popularChoiceButton.addClass("disabled"); 
                    
                        select_btn.addEventListener("click", function() {
                            var index = ik_talent_ids_selected.indexOf(id);
                            if (index !== -1) {
                                ik_talent_ids_selected.splice(index, 1);
                            }
                            ik_talent_select_data_selected_area.removeChild(select_btn);
                            ik_talent_select_data_selector.style.display = "block";

                            var popularChoiceButton = jQuery(\'#ik_talent_select_data_popular_choice button[data_value="\' + id + \'"]\');
                            popularChoiceButton.removeAttr("disabled");
                            popularChoiceButton.removeClass("disabled");
                        });
                    
                        ik_talent_select_data_selected_area.appendChild(select_btn);
                    }

                    popularChoiceButtons.forEach(function(button) {
                        button.addEventListener("click", function(event) {
                            var dataId = event.target.getAttribute("data_value");
                            ik_talent_ids_selected.push(dataId);
                            var select_name = getselected_dataNameById(dataId);

                            if (select_name !== null) {

                                ik_talent_select_data_add_btn(select_name, dataId);
        
                                var popularChoiceButton = jQuery(\'#ik_talent_select_data_popular_choice button[data_value="\' + dataId + \'"]\');
                            
                                popularChoiceButton.attr("disabled","disabled");
                                popularChoiceButton.addClass("disabled"); 
                            }
                        });
                    });
        
                    ik_talent_input_select_data.addEventListener("keyup", function() {
                        var availableOptions = ik_talent_select_data_available.filter(function(option) {
                            return !ik_talent_ids_selected.includes(option.id); // Exclude items in the ik_talent_ids_selected array
                        });
        
                        var inputValue = ik_talent_input_select_data.value.toLowerCase();
                        var matchingOptions = availableOptions.filter(function(option) {
                            return option.name.toLowerCase().includes(inputValue);
                        });
        
                        ik_talent_select_data_selector.innerHTML = "";
        
                        matchingOptions.forEach(function(option) {
                            var optionItem = document.createElement("li");
                            optionItem.textContent = option.name;
                            optionItem.addEventListener("click", function() {
                                ik_talent_select_data_add_btn(option.name, option.id);
                                ik_talent_ids_selected.push(option.id);
                                ik_talent_select_data_selector.style.display = "none";
                                ik_talent_input_select_data.value = "";
                                ik_talent_select_data_selector.innerHTML = "";
                            });
                            ik_talent_select_data_selector.appendChild(optionItem);
                        });
                        var inputRect = ik_talent_input_select_data.getBoundingClientRect();
                        var selectorRect = ik_talent_select_data_selector.getBoundingClientRect();
                
                        var distance = selectorRect.top - inputRect.bottom;
                
                        if (Math.round(distance) > 0) {
                            selector_distance = "-" + Math.round(distance) + "px";
                            ik_talent_select_data_selector.style.marginTop = selector_distance;
                        } else {
                            ik_talent_select_data_selector.style.marginTop = selector_distance;
                        }
                
                        ik_talent_select_data_selector.style.left = inputRect.left + "px";
                        ik_talent_select_data_selector.style.width = inputRect.width + "px";
                        ik_talent_select_data_selector.style.display = "block";

                        if (inputValue === "" || matchingOptions.length === 0) {
                            ik_talent_select_data_selector.style.display = "none";
                        }
                    });
                
                    document.addEventListener("click", function(event) {
                        if (event.target !== ik_talent_input_select_data && event.target !== ik_talent_select_data_selector) {
                            ik_talent_select_data_selector.style.display = "none";
                        }
                    });
                    ik_talent_ids_selected.forEach(function(selectedId) {
                        if (ik_talent_ids_selected.includes(selectedId)) {
                            ik_talent_select_data_add_btn(getselected_dataNameById(selectedId), selectedId);
                        }
                    });
                }
            }
            document.addEventListener("DOMContentLoaded", function() {
                var originalId = "ik_talent_questions";
                var div = document.getElementById(originalId);
                document.addEventListener("click", function(event) {
                  if (event.target !== div) {
                    if (div.getAttribute("id") !== originalId) {
                      div.setAttribute("id", originalId);
                    }
                  }
                });
            });
            function ik_talent_update_questions(go_back){                
                var selectedRadio = talentQuestionsDiv.querySelector(\'input[name="choice"]:checked\');
                var selectedRadioValue = selectedRadio ? selectedRadio.value : null;
            
                var answerInput = talentQuestionsDiv.querySelector(\'textarea[name="answer"]\');
                var answerInputvalue = "";
                if (answerInput !== null) {
                    answerInputvalue = answerInput.value;
                } else {
                    answerInputvalue = "";
                }

                var selected_values = "";
                if (ik_talent_ids_selected !== null) {
                    selected_values = JSON.stringify(ik_talent_ids_selected);
                }
            
                var textarea_datafield = talentQuestionsDiv.querySelector("#ik_talent_form_data_comments");
                var textareaValue = textarea_datafield ? textarea_datafield.val() : null;

                jQuery.ajax({
                    url: "'.admin_url('admin-ajax.php').'",
                    type: "POST",
                    data: {
                    action: "ik_talent_ajax_update_questions",
                    answerInputvalue: answerInputvalue,
                    radio_value: selectedRadioValue,
                    selected_values: ik_talent_ids_selected,
                    comments: textareaValue,
                    go_back: go_back
                    },
                    success: function(response) {
                        talentQuestionsDiv.innerHTML = response.html;

                        if (response && response.select_data !== undefined && response.select_data !== null && response && response.selected_ids !== undefined && response.selected_ids !== null) {
                            var select_data_array = Array.isArray(response.select_data) ? response.select_data : Object.values(response.select_data);
                            var selected_ids_saved = Array.isArray(response.selected_ids) ? response.selected_ids : Object.values(response.selected_ids);
                            initialize_data_selection(select_data_array, selected_ids_saved);
                            console.log(selected_ids_saved);
                        }

                        if (response && response.selected_radio !== undefined && response.selected_radio !== null) {
                            var radios_to_select = talentQuestionsDiv.querySelector(\'input[value="\'+response.selected_radio+\'"]\');
                            if(radios_to_select !== null){
                                radios_to_select.checked = true;
                            }
                        }

                        var startButton = document.getElementById("ik_talent_question_start");
                        var nextButton = document.getElementById("ik_talent_question_next");
                        var backButton = document.getElementById("ik_talent_question_back");
                        var ik_talent_submit_form_btn = document.getElementById("ik_talent_submit_contact_form");

                        
                        if (startButton !== null) {
                            startButton.addEventListener("click", function() {
                                startButton.disabled = true;
                                ik_talent_update_questions(false);
                            });
                        }
                        if (nextButton !== null) {
                            nextButton.addEventListener("click", function() {
                                nextButton.disabled = true;
                                ik_talent_update_questions(false);
                            });
                        }
                        if (backButton !== null) {
                            backButton.addEventListener("click", function() {
                                backButton.disabled = true;
                                ik_talent_go_stage_back();
                            });
                        }
                        if (ik_talent_submit_form_btn !== null) {
                            ik_talent_form_submit_init(ik_talent_submit_form_btn);
                        }
                    }
                });

            }
            function ik_talent_go_stage_back(){
                jQuery.ajax({
                    url: "'.admin_url('admin-ajax.php').'",
                    type: "POST",
                    data: {
                    action: "ik_talent_ajax_back_questions",
                    go_back: "true"
                    },
                    success: function(response) {
                        ik_talent_update_questions(true);
                    }
                });
            }
            function ik_talent_form_submit_init(ik_talent_submit_form_btn){
                if (ik_talent_submit_form_btn !== null) {
                    var ik_talent_select_data_form_email = document.getElementById("ik_talent_select_data_form_email");
                    var ik_talent_select_data_form_company = document.getElementById("ik_talent_select_data_form_company");
                    var ik_talent_select_data_form_name = document.getElementById("ik_talent_select_data_form_name");
                    var ik_talent_select_data_form_tel = document.getElementById("ik_talent_select_data_form_tel");

                    ik_talent_submit_form_btn.addEventListener("click", function() {
                        var form_email = ik_talent_select_data_form_email ? ik_talent_select_data_form_email.value : "";
                        var form_company = ik_talent_select_data_form_company ? ik_talent_select_data_form_company.value : "";
                        var form_name = ik_talent_select_data_form_name ? ik_talent_select_data_form_name.value : "";
                        var form_tel = ik_talent_select_data_form_tel ? ik_talent_select_data_form_tel.value : "";

                        var name_check = ik_talent_form_valid_input("name", form_name);
                        var company_check = ik_talent_form_valid_input("name", form_company);
                        var email_check = ik_talent_form_valid_input("email", form_email);
                        var tel_check = ik_talent_form_valid_input("phone", form_tel);
                        
                        // tel is optional
                        if(!tel_check && form_tel == ""){
                            tel_check = true;
                        }

                        if(name_check && company_check && email_check && tel_check){

                            jQuery.ajax({
                                url: "'.admin_url('admin-ajax.php').'",
                                type: "POST",
                                data: {
                                action: "ik_talent_ajax_submit_form",
                                email: form_email,
                                company: form_company,
                                name: form_name,
                                tel: form_tel
                                },
                                success: function(response) {
                                    talentQuestionsDiv.innerHTML = response.html;
                                    ik_talent_submit_form_btn = document.getElementById("ik_talent_submit_contact_form");
                                    ik_talent_form_submit_init(ik_talent_submit_form_btn);
                                }
                            });
                        } else {
                            if(!name_check){
                                if (ik_talent_select_data_form_name !== null) {
                                    if (ik_talent_select_data_form_name.value !== undefined) {
                                        ik_talent_select_data_form_name.value = "";
                                    }
                                } 
                            }
                            if(!company_check){
                                if (ik_talent_select_data_form_company !== null) {
                                    if (ik_talent_select_data_form_company.value !== undefined) {
                                        ik_talent_select_data_form_company.value = "";
                                    }
                                } 
                            } 
                            if(!email_check){
                                if (ik_talent_select_data_form_email !== null) {
                                    if (ik_talent_select_data_form_email.value !== undefined) {
                                        ik_talent_select_data_form_email.value = "";
                                    }
                                } 
                            } 
                            if(!tel_check){
                                if (ik_talent_select_data_form_tel !== null) {
                                    if (ik_talent_select_data_form_tel.value !== undefined) {
                                        ik_talent_select_data_form_tel.value = "";
                                    }
                                } 
                            } 
                        }   
                    });
                }
            }

            function ik_talent_form_valid_input(type,val) {

                if(type == "phone" && val !== null && val !== undefined){
                    let pattern_phone = /^[0-9+()\-\s]{7,22}$/;
                    if (pattern_phone.test(val)) {
                        return true;
                    }
                } else if(type == "email" && val !== null && val !== undefined){
                    let pattern_email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (pattern_email.test(val)) {
                        return true;
                    }
                } else if(type == "name" && val !== null && val !== undefined){
                    let pattern_name = /^[a-zA-Z0-9\\s\'’`]{1,60}$/;
                    if (pattern_name.test(val)) {
                        return true;
                    }
                }

                return false;
            }
            
            ik_talent_update_questions(false);
        });
    </script>
    
    ';

    return $output;
    
}
add_shortcode('IK_TALENT_QUEST', 'ik_talent_questions_shortcode');

?>