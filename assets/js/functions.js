function ihc_delete_file_via_ajax(id, u_id, parent, name, hidden_id){
   	jQuery.ajax({
        type : "post",
        url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
        data : {
                   action: "ihc_delete_attachment_ajax_action",
                   attachemnt_id: id,
                   user_id: u_id,
                   field_name: name,
               },
        success: function (data) {   
        	jQuery(hidden_id).val('');
        	jQuery(parent + ' .ajax-file-upload-filename').remove();        	
        	jQuery(parent + ' .ihc-delete-attachment-bttn').remove();
        	if (jQuery(parent + ' .ihc-member-photo').length){
        		jQuery(parent + ' .ihc-member-photo').remove();
        		if (name=='ihc_avatar'){
        			jQuery(parent).prepend("<div class='ihc-no-avatar ihc-member-photo'></div>");
        			jQuery(parent + " .ihc-file-upload").css("display", 'block');
        		}        		
        	}
        	
        	if (jQuery(parent + " .ihc-file-name-uploaded").length){
        		jQuery(parent + " .ihc-file-name-uploaded").remove();
        	}
        	
        	if (jQuery(parent + ' .ajax-file-upload-progress').length){
        		jQuery(parent + ' .ajax-file-upload-progress').remove();
        	}
        	if (jQuery(parent + ' .ihc-icon-file-type').length){
        		jQuery(parent + ' .ihc-icon-file-type').remove();
        	}
        }
   });
}

function ihc_set_form_i(i_id, f_id, l_id){
	/*
	 * i_id = input hidden id
	 * f_id = form id
	 * l_id = level id
	 */
	if (jQuery("#ihc_coupon").val()){
		jQuery(f_id).append("<input type=hidden value=" + jQuery("#ihc_coupon").val() + " name=ihc_coupon />");
	}
	jQuery(i_id).val(l_id);
	jQuery(f_id).submit();
}       

function ihc_dh_selector(id, display){
	if (display){
		jQuery(id).css('visibility', 'visible');
	} else {
		jQuery(id).css('visibility', 'hidden');
	}
}

function ihc_set_level_ap(l){
	jQuery('#ihc_renew_level').val(l);
	jQuery('#ihc_form_ap_subscription_page').submit();
}

function ihc_run_social_reg(s){
	var form = jQuery("form#createuser");
	jQuery("form#createuser input, form#createuser textarea").each(function(){
		ihc_append_input(this.name, this.value, "#ihc_social_login_form");
	});
	ihc_append_input('sm_type', s, "#ihc_social_login_form");
	jQuery("#ihc_social_login_form").submit();
}

function ihc_append_input(n,v,w){
	jQuery(w).append("<input type=hidden value="+v+" name="+n+" />");
}

function ihc_buy_new_level(href){
	if (jQuery("#ihc_coupon").val()){
		//we have a coupon
		var url = href + "&ihc_coupon=" + jQuery("#ihc_coupon").val();
		window.location.href = url;
	} else {
		window.location.href = href;
	}
}

jQuery(document).ready(function(){
	jQuery('.ihc-mobile-bttn').on('click', function(){
		jQuery('.ihc-ap-menu').toggle();
	});	
});

function ihc_register_check_via_ajax(the_type){
	var target_id = '#' + jQuery('.ihc-form-create-edit [name='+the_type+']').parent().attr('id');
	var val1 = jQuery('.ihc-form-create-edit [name='+the_type+']').val();
	var val2 = '';
	
	if (the_type=='pass2'){
		val2 = jQuery('[name=pass1]').val();
	} else if (the_type=='confirm_email'){
		val2 = jQuery('[name=user_email]').val();
	}
	
   	jQuery.ajax({
        type : "post",
        url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
        data : {
                   action: "ihc_check_reg_field_ajax",
                   type: the_type,
                   value: val1,
                   second_value: val2
               },
        success: function (data) {
        	//remove prev notice, if its case
        	jQuery(target_id + ' .ihc-register-notice').remove();
        	jQuery('.ihc-form-create-edit [name='+the_type+']').removeClass('ihc-input-notice');
        	if (data==1){
        		// it's all good 
        	} else {
        		jQuery(target_id).append('<div class="ihc-register-notice">'+data+'</div>');
        		jQuery('.ihc-form-create-edit [name='+the_type+']').addClass('ihc-input-notice');
        	}
        }
   	});
}


function ihc_get_checkbox_radio_value(type, selector){
	if (type=='radio'){
		var r = jQuery('[name='+selector+']:checked').val();
		if (typeof r!='undefined'){
			return r;
		}
	} else {
		var arr = [];
		jQuery('[name=\''+selector+'[]\']:checked').each(function(){
			arr.push(this.value);
		});
		if (arr.length>0){
			return arr.join(',');
		}		
	}
	return '';
}

function ihc_register_check_via_ajax_rec(types_arr){
	jQuery('.ihc-register-notice').remove();
	var fields_to_send = [];
	
	//EXCEPTIONS
	var exceptions = jQuery("#ihc_exceptionsfields").val();
	if (exceptions){
		var exceptions_arr = exceptions.split(',');
	}

	for (var i=0; i<types_arr.length; i++){
		//CHECK IF FIELD is in exceptions
		if (exceptions_arr && exceptions_arr.indexOf(types_arr[i])>-1){
			continue;
		}
		
		jQuery('.ihc-form-create-edit [name='+types_arr[i]+']').removeClass('ihc-input-notice');
		
		var field_type = jQuery('[name=' + types_arr[i] + ']').attr('type');
		if (typeof field_type=='undefined'){			
			var field_type = jQuery('[name=\'' + types_arr[i] + '[]\']').attr('type');	
		}
		if (typeof field_type=='undefined'){				
			var field_type = jQuery('[name=\'' + types_arr[i] + '\']').prop('nodeName');
		}
		if (typeof field_type=='undefined'){				
			var field_type = jQuery('[name=\'' + types_arr[i] + '[]\']').prop('nodeName');
			if (field_type=='SELECT'){
				field_type = 'multiselect';
			}	
		}		
		
		if (field_type=='checkbox' || field_type=='radio'){
			var val1 = ihc_get_checkbox_radio_value(field_type, types_arr[i]);
		} else if ( field_type=='multiselect' ){
			val1 = jQuery('[name=\'' + types_arr[i] + '[]\']').val();
			if (typeof val1=='object' && val1!=null){
				val1 = val1.join(',');
			}
		} else {
			var val1 = jQuery('[name='+types_arr[i]+']').val();
		}	
		
		var val2 = '';
		if (types_arr[i]=='pass2'){
			val2 = jQuery('[name=pass1]').val();
		} else if (types_arr[i]=='confirm_email'){
			val2 = jQuery('[name=user_email]').val();
		} else if (types_arr[i]=='tos') {
			if (jQuery('[name=tos]').is(':checked')){
				val1 = 1;
			} else {
				val1 = 0;
			}
		}
		fields_to_send.push({type: types_arr[i], value: val1, second_value: val2});
	}
	
   	jQuery.ajax({
        type : "post",
        url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
        data : {
                   action: "ihc_check_reg_field_ajax",
                   fields_obj: fields_to_send
               },
        success: function (data) {
        	var obj = JSON.parse(data);
        	var must_submit = 1;
        	for (var j=0; j<obj.length; j++){
        		var field_type = jQuery('[name=' + obj[j].type + ']').attr('type');
        		if (typeof field_type=='undefined'){
        			var field_type = jQuery('[name=\'' + obj[j].type + '[]\']').attr('type');	
        		}
        		if (typeof field_type=='undefined'){				
        			var field_type = jQuery('[name=\'' + obj[j].type + '\']').prop('nodeName');
        		}
        		if (typeof field_type=='undefined'){				
        			var field_type = jQuery('[name=\'' + obj[j].type + '[]\']').prop('nodeName');
        			if (field_type=='SELECT'){
        				field_type = 'multiselect';
        			}	
        		}	
        		
            	if (field_type=='radio'){
            		var target_id = jQuery('.ihc-form-create-edit [name='+obj[j].type+']').parent().parent().attr('id');
            	} else if (field_type=='checkbox' && obj[j].type!='tos'){
            		var target_id = jQuery('.ihc-form-create-edit [name=\''+obj[j].type+'[]\']').parent().parent().attr('id');
            	} else if ( field_type=='multiselect'){
            		var target_id = jQuery('.ihc-form-create-edit [name=\''+obj[j].type+'[]\']').parent().attr('id');
            	} else {
            		var target_id = jQuery('.ihc-form-create-edit [name='+obj[j].type+']').parent().attr('id');
            	}
            		
            	if (obj[j].value==1){
            		// it's all good 
            	} else {
            		//errors
                	if (typeof target_id=='undefined'){
                		//no target id...insert msg after input
                		jQuery('.ihc-form-create-edit [name='+obj[j].type+']').after('<div class="ihc-register-notice">'+obj[j].value+'</div>');
                		must_submit = 0;	
                	} else {
                		jQuery('#'+target_id).append('<div class="ihc-register-notice">'+obj[j].value+'</div>');
                		jQuery('.ihc-form-create-edit [name=' + obj[j].type + ']').addClass('ihc-input-notice');
                		must_submit = 0;                		
                	}
            	}            	
        	}

        	if (must_submit==1){
    			window.must_submit=1;
    			jQuery(".ihc-form-create-edit").submit();     		
        	} else {
    			return false;        		
        	}      	
        }
   	});  
   	
}

function ihc_payment_gateway_update(v, is_r){
	//remove authorize block
	//remove stripe stuff
	jQuery('[name=ihc_payment_gateway]').val(v);
	jQuery('#ihc_submit_bttn').unbind('click');
	jQuery('#ihc_authorize_r_fields').fadeOut(200);
	if (v=='stripe'){
		jQuery('#ihc_submit_bttn').bind('click', function(e){
			e.preventDefault();
			ihc_stripe_pay_on_register();
		});
	} else if (v=='authorize' && is_r==1){
		jQuery('#ihc_authorize_r_fields').fadeIn(200);
	}	
}

function ihc_buy_new_level_from_ap(l_name, l_amount, lid, url){
	var v = jQuery('[name=ihc_payment_gateway]').val();
	if (v=='stripe'){
		ihc_stripe_payment(l_name, l_amount, lid);
	} else {
		ihc_buy_new_level(url+'&ihc_payment_gateway='+v);
	}
}

function ihc_renew_function(i_id, f_id, l_id, l_name, l_amount){
	/*
	 * i_id = input hidden id
	 * f_id = form id
	 * l_id = level id 
	 * l_name = level name
	 * l_amount = level amount
	 */
	var v = jQuery('[name=ihc_payment_gateway]').val();
	if (v=='stripe'){
		if (typeof ihc_stripe_renew_payment == 'function') { 
			ihc_stripe_renew_payment(l_name, l_amount, l_id);
			return false;
		}
	} else {
		ihc_set_form_i(i_id, f_id, l_id);
	}	
}

function ihc_payment_select_icon(t){
	jQuery('.ihc-payment-icon').removeClass('ihc-payment-select-img-selected');
	jQuery('#ihc_payment_icon_'+t).addClass('ihc-payment-select-img-selected');
}

//////////////logic condition

function ihc_ajax_check_field_condition_onblur_onclick(check_name, field_id, field_name, show){
	var check_value = jQuery(".ihc-form-create-edit [name="+check_name+"]").val();
	ihc_ajax_check_field_condition(check_value, field_id, field_name, show);
}

function ihc_ajax_check_onClick_field_condition(check_name, field_id, field_name, type, show){	
	if (type=='checkbox'){
		var vals = [];
		jQuery(".ihc-form-create-edit [name='"+check_name+"[]']:checked").each(function() {
			vals.push(jQuery(this).val());
	    });
		var check_value = vals.join(',');		
	} else {
		var check_value = jQuery(".ihc-form-create-edit [name="+check_name+"]:checked").val();
	}
	
	ihc_ajax_check_field_condition(check_value, field_id, field_name, show);	
}

function ihc_ajax_check_onChange_multiselect_field_condition(check_name, field_id, field_name, show){
	var obj = jQuery(".ihc-form-create-edit [name='"+check_name+"[]']").val();
	if (obj!=null){
		var check_value = obj.join(',');
		ihc_ajax_check_field_condition(check_value, field_id, field_name, show);		
	}
}

function ihc_ajax_check_field_condition(check_value, field_id, field_name, show){
   	jQuery.ajax({
        type : "post",
        url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
        data : {
                   action: "ihc_check_logic_condition_value",
                   val: check_value,
                   field: field_name
               },
        success: function (data){
        	var str = jQuery("#ihc_exceptionsfields").val();
        	if (str){
            	var arr = str.split(',');
            	var index = arr.indexOf(field_name);        		
        	} else {
        		var arr = [];
        	}
        	
        	if (data=='1'){        		
                if (show==1){
                	jQuery(field_id).fadeIn(200);
                	if (arr.indexOf(field_name)!=-1){
                        arr.splice(index, 1);                		
                	}           	
                } else {
                	jQuery(field_id).fadeOut(200);
                	if (arr.indexOf(field_name)==-1){
                		arr.push(field_name);
                	}        			
        			            	
                }                
        	} else {        		
                    if (show==1){
                    	jQuery(field_id).fadeOut(200);
                    	if (arr.indexOf(field_name)==-1){
                    		arr.push(field_name);
                    	}         	
                    } else {
                    	jQuery(field_id).fadeIn(200);
                    	if (arr.indexOf(field_name)!=-1){
                            arr.splice(index, 1);                            		
                    	}  	
                    } 
        	}
        	if (arr){
            	var str = arr.join(',');   
            	jQuery("#ihc_exceptionsfields").val(str);        		
        	}
        }
   	});
}

