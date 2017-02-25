<?php

function ihc_post_metas($post_id, $return_name=FALSE){
	/*
	 * @param int, bool
	 * @return array
	 */	
	$arr = array(
					'ihc_mb_type' => 'show',
					'ihc_mb_who' => '',
					'ihc_mb_block_type' => 'redirect',
					'ihc_mb_redirect_to' => -1,
					'ihc_replace_content' => '',
					//DRIP CONTENT
					'ihc_drip_content' => 0,
					'ihc_drip_start_type' => 1,
					'ihc_drip_end_type' => 1,
					'ihc_drip_start_numeric_type' => 'days',
					'ihc_drip_start_numeric_value' => '',
					'ihc_drip_end_numeric_type' => 'days',
					'ihc_drip_end_numeric_value' => '',
					'ihc_drip_start_certain_date' => '',
					'ihc_drip_end_certain_date' => '',
				);
	if($return_name==TRUE) return $arr;
	foreach($arr as $k=>$v){
		$data = get_post_meta($post_id, $k, true);
		if( $data!==FALSE && $data!='' )
			$arr[$k] = $data;
	}
	return $arr;	
}

function ihc_get_all_pages(){
	/*
	 * @param none
	 * @return array
	 */
	$arr = array();
	$args = array(
			'sort_order' => 'ASC',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'child_of' => 0,
			'parent' => -1,
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
	);
	$pages = get_pages($args);
	if (isset($pages) && count($pages)>0){
		foreach ($pages as $page){
			if ($page->post_title=='') $page->post_title = '(no title)';
			$arr[$page->ID] = $page->post_title;
		}
	}
	return $arr;
}


function ihc_locker_meta_keys(){
	/*
	 * @param none
	 * @return array
	 */
	//meta keys for ihc_lockers
	$arr = array(
					'ihc_locker_name' => 'Untitled Locker',
					'ihc_locker_custom_content' => '<h2>This content is locked</h2>
													Login To Unlock The Content!',
					'ihc_locker_custom_css' => '.ihc-locker-wrap{}',
					'ihc_locker_template' => '',
					'ihc_locker_login_template' => '',
					'ihc_locker_login_form' => 1,
					'ihc_locker_additional_links' => 1,
					'ihc_locker_display_sm' => 0,
				 );
	return $arr;
}

function ihc_return_meta($name, $id=false){
	/*
	 * @param string, string|bool
	 * @return ...
	 */
	$data = get_option($name);
	if ($data!==FALSE){
		if($data && isset($data[$id])) return $data[$id];
		return $data;
	}
	else return FALSE;	
}

function ihc_return_meta_arr($type, $only_name=false, $return_default=false){
	/*
	 * @param string, bool, bool
	 * @return array
	 */
	//all metas
	switch ($type){
		case 'payment':
			$arr = array(
							'ihc_currency' => 'IRR',
							'ihc_payment_set' => 'predefined',
							'ihc_payment_selected' => 'nextpay',
						);
		break;
		case 'payment_nextpay':
			$arr = array(
							'ihc_nextpay_key' => '',
							'ihc_nextpay_return_page' => -1,
							'ihc_nextpay_status' => 0,
							'ihc_nextpay_label' => 'Nextpay',
							'ihc_nextpay_select_order' => 1,
						);
		break;
		case 'payment_bank_transfer':
			$arr = array(
					'ihc_bank_transfer_status' => 0,
					'ihc_bank_transfer_message' => '',
					'ihc_bank_transfer_label' => 'Bank Transfer',
					'ihc_bank_transfer_select_order' => 5,
			);			
		break;
		case 'login':
			$arr = array(
						   'ihc_login_remember_me' => 1,
						   'ihc_login_register' => 1,
						   'ihc_login_pass_lost' => 1,
						   'ihc_login_template' => 'template_1',
						   'ihc_login_custom_css' => '',
						   'ihc_login_show_sm' => 0,
						);
		break;
		case 'login-messages':
			$arr = array(
							'ihc_login_succes' => 'Welcome!',
							'ihc_login_pending' => 'Your account was not approved yet.',
							'ihc_login_error' => 'Error',
							'ihc_reset_msg_pass_err' => 'Wrong E-mail!',
							'ihc_reset_msg_pass_ok' => 'A new password has been send to your email address',	
							'ihc_login_error_email_pending' => 'E-mail address has not been verified'		
						);
		break;
		case 'general-defaults':
			$arr = array(
							//default pages							
							'ihc_general_login_default_page' => '',
							'ihc_general_register_default_page'=>'',
							'ihc_general_lost_pass_page' => '',
							'ihc_general_logout_page' => '',
							'ihc_general_user_page' => '',
							'ihc_general_tos_page' => '',
							'ihc_subscription_plan_page' => '',
							//redirects
							'ihc_general_redirect_default_page' => '',
							'ihc_general_logout_redirect' => '',
							'ihc_general_register_redirect' => '',
							'ihc_general_login_redirect' => '',							
						);
		break;
		case 'general-captcha':
			//recapcha
			$arr = array(
							'ihc_recaptcha_public' => '',
							'ihc_recaptcha_private' => '',			
						);
		break;
		case 'general-subscription':
			$arr = array(
							'ihc_level_template' => 'ihc_level_template_2',
							'ihc_select_level_custom_css' => '.ich_level_wrap{}',
						);
		break;
		case 'general-msg':
			$arr = array(
							'ihc_general_update_msg' => 'Successfully Update!',
						);
		break;				
		case 'register':
			$arr = array(
							'ihc_register_template' => 'ihc-register-1',						
							'ihc_register_admin_notify' => 1,
							'ihc_register_pass_min_length' => 6,
							'ihc_register_pass_options' => 1,
							'ihc_register_new_user_level' => -1,//'none'
							'ihc_register_new_user_role' => 'pending_user',
							'ihc_register_custom_css' => '',
							'ihc_register_terms_c' => 'Accept our Terms&Conditions',							
							'ihc_subscription_type' => 'predifined_level',
							'ihc_register_opt-in' => 0,
							'ihc_register_opt-in-type' => 'email_list',
							'ihc_register_show_level_price' => 0,
							'ihc_register_auto_login' => 0,
							'ihc_register_double_email_verification' => 0,
							'ihc_automatically_switch_role' => 0,
							'ihc_automatically_new_role' => 'subscriber',
						);
		break;
		case 'register-msg':
			$arr = array(
							//messages
							'ihc_register_username_taken_msg' => 'Username is taken',
							'ihc_register_error_username_msg' => 'Invalid Username',
							'ihc_register_email_is_taken_msg' => 'Email address is taken',
							'ihc_register_invalid_email_msg' => 'You must enter a valid email address.',
							'ihc_register_emails_not_match_msg' => 'Email Addresses did not match!',
							'ihc_register_pass_not_match_msg' => 'Password did not match',
							'ihc_register_pass_letter_digits_msg' => 'Password must contain characters and digits!',
							'ihc_register_pass_let_dig_up_let_msg' => 'Password must contain characters, digits and minimum one uppercase letter!',
							'ihc_register_pass_min_char_msg' => 'Password must contain minimum {X} characters!',
							'ihc_register_pending_user_msg' => 'Your account has not been approved yet. Please try again later!',
							'ihc_register_err_req_fields' => 'Please complete all required fields!',
							'ihc_register_err_recaptcha' => 'Captcha Error',
							'ihc_register_err_tos' => 'Error On Terms & Conditions',
							'ihc_register_success_meg' => 'Successfully Register!',
							'ihc_register_update_msg' => 'Successfully Updated!',			
						);			
		break;
		case 'register-custom-fields':
			$arr = array(
							'ihc_user_fields' => ihc_native_user_field(),
						);
		break;
		case 'opt_in':
			$arr = array(
							'ihc_main_email' => '',
							//aweber
							'ihc_aweber_auth_code' => '',
							'ihc_aweber_list' => '',
							'ihc_aweber_consumer_key' => '',
							'ihc_aweber_consumer_secret' => '',
							'ihc_aweber_acces_key' => '',
							'ihc_aweber_acces_secret' => '',
							//mailchimp
							'ihc_mailchimp_api' => '',
							'ihc_mailchimp_id_list' => '',
							//get response
							'ihc_getResponse_api_key' => '',
							'ihc_getResponse_token' => '',
							//campaign monitor
							'ihc_cm_api_key' => '',
							'ihc_cm_list_id' => '',
							//icontact
							'ihc_icontact_user' => '',
							'ihc_icontact_appid' => '',
							'ihc_icontact_pass' => '',
							'ihc_icontact_list_id' => '',
							//constant contact
							'ihc_cc_user' => '',
							'ihc_cc_pass' => '',
							'ihc_cc_list' => '',
							//Wysija Contact
							'ihc_wysija_list_id' => '',
							//MyMail
							'ihc_mymail_list_id' => '',
							//Mad Mimi
							'ihc_madmimi_username' => '',
							'ihc_madmimi_apikey' => '',
							'ihc_madmimi_listname' => '',
							//indeed email list
							'ihc_email_list' => '',
						);
		break;
		case 'notifications':
			$arr = array(
							'ihc_notification_email_from' => '',
							'ihc_notification_before_time' => 5,
							'ihc_notification_name' => '',
						);
		break;
		case 'extra_settings':
			$arr = array(
							'ihc_grace_period' => '',
							'ihc_debug_payments_db' => '',
							'ihc_upload_extensions' => 'txt,doc,pdf,jpg,jpeg,png,gif,mp3,zip',
							'ihc_upload_max_size' => 5,
							'ihc_avatar_max_size' => 1,
						);
			break;
		case 'account_page':
			$arr = array(	'ihc_ap_theme' => 'ihc-ap-theme-1',
							'ihc_ap_edit_show_avatar' => 0,
							'ihc_ap_tabs' => 'overview,profile',
							'ihc_ap_overview_msg' => 'Hey There,
														This is the Overview section.
														&nbsp;
														Enjoy the sun.',
							'ihc_ap_welcome_msg' => '<span class="iump-user-page-mess-special">Hello</span> <span class="iump-user-page-name"> {last_name} {first_name}</span>,
														<span class="iump-user-page-mess">you\'re logged as</span><span class="iump-user-page-mess-special"> {username}</span>
														<span class="iump-user-page-mess">and you\'re awesome e-mail address is : <strong>{user_email}</strong></span>',
							'ihc_account_page_custom_css' => '',
							'ihc_ap_social_plus_message' => '',
					);
			break;
		case 'fb':
			$arr = array(
							'ihc_fb_app_id' => '',
							'ihc_fb_app_secret' => '',
							'ihc_fb_status' => 0,
						);
			break;
		case 'tw':
			$arr = array(
							'ihc_tw_app_key' => '',
							'ihc_tw_app_secret' => '',
							'ihc_tw_status' => 0,
			);
			break;	
		case 'in':
			$arr = array(
							'ihc_in_app_key' => '',
							'ihc_in_app_secret' => '',
							'ihc_in_status' => 0,
			);
			break;
		case 'tbr':
			$arr = array(
							'ihc_tbr_app_key' => '',
							'ihc_tbr_app_secret' => '',
							'ihc_tbr_status' => 0,
			);
			break;	
		case 'ig':
				$arr = array(
					'ihc_ig_app_id' => '',
					'ihc_ig_app_secret' => '',
					'ihc_ig_status' => 0,
				);
			break;
		case 'vk':
				$arr = array(
					'ihc_vk_app_id' => '',
					'ihc_vk_app_secret' => '',
					'ihc_vk_status' => 0,
				);
			break;	
		case 'goo':
				$arr = array(
					'ihc_goo_app_id' => '',
					'ihc_goo_app_secret' => '',
					'ihc_goo_status' => 0,
				);
			break;	
		case 'social_media':
			$arr = array(
							"ihc_sm_template" => "ihc-sm-template-1",
							"ihc_sm_custom_css" => ".ihc-sm-wrapp-fe{}",
							"ihc_sm_show_label" => 1,
						);
			break;	
		case 'double_email_verification':
			$arr = array(
							'ihc_double_email_expire_time' => -1,
							'ihc_double_email_redirect_success' => '',
							'ihc_double_email_redirect_error' => '',
							'ihc_double_email_delete_user_not_verified' => -1,
						);
			break;
	}
	
	if ($return_default){
		//return default values
		return $arr;
	}
	
	if (isset($arr)){
		if ($only_name){
			return $arr;
		}
		foreach ($arr as $k=>$v){
			$data = get_option($k);
			if ($data!==FALSE){
				$arr[$k] = $data;
			} else {
				add_option($k, $v);
			}
		}
		return $arr;
	}
	return FALSE;
}

function ihc_native_user_field(){
	/*
	 * @param none
	 * @return array
	 */
	//$arr[] = array('display_public_reg'=>'', 'display_public_ap'=>'', 'display_admin'=>'', 'name'=>'', 'label'=>'', 'type'=>'', 'native_wp' => '', 'req' => '' );
	//order will be each key . ex: array( n=>array())
	//arr[]['display'] 0 not show, 1 show, 2 show always cannot be removed from register form
	//arr['req'] 0 not, 1 require, 2 if is selected it will be automatically require
	$arr = array(
			array( 'display_admin'=>2, 'display_public_reg'=>2, 'display_public_ap'=>2, 'name'=>'user_login', 'label'=>'Username', 'type'=>'text', 'native_wp' => 1, 'req' => 1, 'sublevel' => '' ),
			array( 'display_admin'=>2, 'display_public_reg'=>2, 'display_public_ap'=>2, 'name'=>'user_email', 'label'=>'Email', 'type'=>'text', 'native_wp' => 1, 'req' => 1, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'confirm_email', 'label'=>'Confirm Email', 'type'=>'text', 'native_wp' => 0, 'req' => 2, 'sublevel' => '' ),
			array( 'display_admin'=>1, 'display_public_reg'=>1, 'display_public_ap'=>1, 'name'=>'first_name', 'label'=>'First Name', 'type'=>'text', 'native_wp' => 1, 'req' => 1, 'sublevel' => '' ),
			array( 'display_admin'=>1, 'display_public_reg'=>1, 'display_public_ap'=>1, 'name'=>'last_name', 'label'=>'Last Name', 'type'=>'text', 'native_wp' => 1, 'req' => 1, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'user_url', 'label'=>'Website', 'type'=>'text', 'native_wp' => 1, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>1, 'display_public_reg'=>2, 'display_public_ap'=>1, 'name'=>'pass1', 'label'=>'Password', 'type'=>'password', 'native_wp' => 1, 'req' => 1, 'sublevel' => '' ),
			array( 'display_admin'=>1, 'display_public_reg'=>1, 'display_public_ap'=>1, 'name'=>'pass2', 'label'=>'Confirm Password', 'type'=>'password', 'native_wp' => 1, 'req' => 2, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'description', 'label'=>'Biographical Info', 'type'=>'textarea', 'native_wp' => 1, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'phone', 'label'=>'Phone', 'type'=>'number', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'addr1', 'label'=>'Address 1', 'type'=>'textarea', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'addr2', 'label'=>'Address 2', 'type'=>'textarea', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'zip', 'label'=>'Zip', 'type'=>'text', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'city', 'label'=>'City', 'type'=>'text', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'thestate', 'label'=>'State', 'type'=>'text', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'country', 'label'=>'Country', 'type'=>'text', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>1, 'display_public_reg'=>1, 'display_public_ap'=>1, 'name'=>'tos', 'label'=>'Accept', 'type'=>'checkbox', 'native_wp' => 0, 'req' => 2, 'sublevel' => '' ),
			array( 'display_admin'=>1, 'display_public_reg'=>1, 'display_public_ap'=>1, 'name'=>'recaptcha', 'label'=>'Capcha', 'type'=>'capcha', 'native_wp' => 0, 'req' => 2, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'ihc_avatar', 'label'=>'Avatar', 'type'=>'upload_image', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),			
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'ihc_coupon', 'label'=>'Coupon', 'type'=>'text', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
			array( 'display_admin'=>0, 'display_public_reg'=>0, 'display_public_ap'=>0, 'name'=>'ihc_social_media', 'label'=>'-', 'type'=>'social_media', 'native_wp' => 0, 'req' => 0, 'sublevel' => '' ),
	);
	
	return $arr;
}

function ihc_get_user_reg_fields(){
	/*
	 * @param none
	 * @return array
	 */
	$option_name = 'ihc_user_fields';
	$data = get_option($option_name);
	if ($data!==FALSE){
		return $data;
	} else {
		$data = ihc_native_user_field();
		add_option($option_name, $data);
		return $data;
	}
}

function ihc_print_form_password($meta_arr){
	/*
	 * @param attr
	 * @return string with form for lost password
	 */
	$str = '';
	
	if($meta_arr['ihc_login_custom_css']){
		$str .= '<style>'.$meta_arr['ihc_login_custom_css'].'</style>';
	}
	
	$str .= '<div class="ihc-pass-form-wrap '.$meta_arr['ihc_login_template'].'">';
	$str .= '<form action="" method="post" >'
					. '<input name="ihcaction" type="hidden" value="reset_pass">';
	
	switch($meta_arr['ihc_login_template']){
	
	case 'ihc-login-template-3':
		$str .=  '<div class="impu-form-line-fr">'
						. '<input type="text" value="" name="email_or_userlogin" placeholder="'.__('Username or E-mail').'" />'
					. '</div>'
					. '<div class="impu-form-submit">'
						. '<input type="submit" value="'.__('Get New Password', 'ihc').'" name="Submit" class="button button-primary button-large">'
					. '</div>';
	break;
	
	case 'ihc-login-template-4':
		$str .=  '<div class="impu-form-line-fr">'
						. '<i class="fa-ihc fa-username-ihc"></i><input type="text" value="" name="email_or_userlogin" placeholder="'.__('Username or E-mail').'" />'
					. '</div>'
					. '<div class="impu-form-submit">'
						. '<input type="submit" value="'.__('Get New Password', 'ihc').'" name="Submit" class="button button-primary button-large">'
					. '</div>';
	break;	
						
	default:
		$str .=  '<div class="impu-form-line-fr">'
					. '<span class="impu-form-label-fr impu-form-label-username">'.__('Username or E-mail', 'ihc').': </span>'
						. '<input type="text" value="" name="email_or_userlogin" />'
					. '</div>'
					. '<div class="impu-form-submit">'
						. '<input type="submit" value="'.__('Get New Password', 'ihc').'" name="Submit" class="button button-primary button-large">'
					. '</div>';
	break;
	}
	$str .=   '</form>';		
	$str .= '</div>';			
	return $str;
}

function ihc_print_form_login($meta_arr){
	/*
	 * @param array
	 * @return string
	 */
	$str = '';
	if($meta_arr['ihc_login_custom_css']){
		$str .= '<style>'.$meta_arr['ihc_login_custom_css'].'</style>';
	}
	
	$sm_string = (!empty($meta_arr['ihc_login_show_sm'])) ? ihc_print_social_media_icons('login', array(), @$meta_arr['is_locker']) : '';
	
	$str .= '<div class="ihc-login-form-wrap '.$meta_arr['ihc_login_template'].'">'
			.'<form action="" method="post" id="ihc_login_form">'
			. '<input type="hidden" name="ihcaction" value="login" />';
	
	if (!empty($meta_arr['is_locker'])){
		$str .= '<input type="hidden" name="locker" value="1" />';	
	}
	
	switch($meta_arr['ihc_login_template']){
	
	case 'ihc-login-template-2':
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'ihc').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'ihc').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;			
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-form-line-fr impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'ihc').'</span> </div>';
		}
		//>>>>
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-line-fr impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
	break;
		
	case 'ihc-login-template-3':
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">'
				. '<input type="text" value="" name="log" placeholder="'.__('Username', 'ihc').'"/>'
				. '</div>'
				. '<div class="impu-form-line-fr">'
				. '<input type="password" value="" name="pwd" placeholder="'.__('Password', 'ihc').'"/>'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		
		$str .=    '<div class="impu-temp3-bottom">';		 
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-remember">'.__('Remember Me', 'ihc').'</span> </div>';
		}
		//>>>>
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>	
		$str .= '<div class="iump-clear"></div>';
		$str .= '</div>';
		
		break;
		
	case 'ihc-login-template-4':
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">'
				. '<i class="fa-ihc fa-username-ihc"></i><input type="text" value="" name="log" placeholder="'.__('Username', 'ihc').'"/>'
				. '</div>'
				. '<div class="impu-form-line-fr">'
				. '<i class="fa-ihc fa-pass-ihc"></i><input type="password" value="" name="pwd" placeholder="'.__('Password', 'ihc').'"/>'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-remember">'.__('Remember Me', 'ihc').'</span> </div>';
		}
		//>>>>
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.' />'
				 . '</div>';
				 
		
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		
		break;
	case 'ihc-login-template-5':	
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'ihc').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'ihc').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		$str .=    '<div class="impu-temp5-row">';	
		$str .=    '<div class="impu-temp5-row-left">';		
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'ihc').'</span> </div>';
		}
		//>>>>
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-line-fr impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		$str .= '</div>';
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
		$str .= '<div class="iump-clear"></div>';
		$str .= '</div>';	
		
		break;
		case 'ihc-login-template-6':	
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'ihc').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'ihc').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		$str .=    '<div class="impu-temp6-row">';	
		$str .=    '<div class="impu-temp6-row-left">';		
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'ihc').'</span> </div>';
		}
		//>>>>
		
		$str .= '</div>';
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
		$str .= '<div class="iump-clear"></div>';
		$str .= '</div>';	
		
		break;	
		
		case 'ihc-login-template-7':	
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'ihc').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'ihc').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;
		$str .=    '<div class="impu-temp5-row">';	
		$str .=    '<div class="impu-temp5-row-left">';		
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me', 'ihc').'</span> </div>';
		}
		//>>>>
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		$str .= '</div>';
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.'/>'
				 . '</div>';
		//>>>>
		$str .= '<div class="iump-clear"></div>';
		$str .= '</div>';	
		
		break;
			
	default:			
		//<<<< FIELDS		
		$str .= '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-username">'.__('Username', 'ihc').':</span>'
				. '<input type="text" value="" name="log" />'
				. '</div>'
				. '<div class="impu-form-line-fr">' . '<span class="impu-form-label-fr impu-form-label-pass">'.__('Password', 'ihc').':</span>'
				. '<input type="password" value="" name="pwd" />'
				. '</div>';
		//>>>>
		$str .= $sm_string;	
		//<<<< REMEMBER ME			
		if($meta_arr['ihc_login_remember_me']){
			$str .= '<div class="impu-form-line-fr impu-remember-wrapper"><input type="checkbox" value="forever" name="rememberme" class="impu-form-input-remember" /><span class="impu-form-label-fr impu-form-label-remember">'.__('Remember Me').'</span> </div>';
		}
		//>>>>
		
		//<<<< ADDITIONAL LINKS
		if($meta_arr['ihc_login_register'] || $meta_arr['ihc_login_pass_lost']){
		$str .= '<div  class="impu-form-line-fr impu-form-links">';
			if($meta_arr['ihc_login_register']){
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}
			}
			if($meta_arr['ihc_login_pass_lost']){
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}
			}
		$str .= '</div>';
		}
		//>>>>
		
		//SUBMIT BUTTON
		$disabled = '';
		if(isset($meta_arr['preview']) && $meta_arr['preview']){
			$disabled = 'disabled';
		}
		$str .=    '<div class="impu-form-line-fr impu-form-submit">'
					. '<input type="submit" value="'.__('Log In', 'ihc').'" name="Submit" '.$disabled.' class="button button-primary button-large"/>'
				 . '</div>';
		//>>>>
		break;
	}
	
	$str .=   '</form>'
			.'</div>';
			
	return $str;
}


function ihc_print_social_media_icons($type='login', $already_registered_sm=array(), $is_locker=FALSE){
	/*
	 * @param string (login, register, update), array, bool
	 * @return string
	 */

	$current_url = IHC_PROTOCOL . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$metas = ihc_return_meta_arr('social_media');
	
	$arr = array(
			"fb" => "Facebook",
			"tw" => "Twitter",
			"goo" => "Google",
			"in" => "LinkedIn",
			"vk" => "Vkontakte",
			"ig" => "Instagram",
			"tbr" => "Tumblr",
	);
	
	$str = '';
	foreach ($arr as $k=>$v){
		$data = ihc_check_social_status($k);
		$label = (empty($metas['ihc_sm_show_label'])) ? "" : '<span class="ihc-sm-item-label">'.$v.'</span>';
		
		if ($data['settings']=='Completed' && $data['active']){
			$extra_class = 'ihc-' . $k;
			$icon = '<i class="fa-ihc-sm fa-ihc-' . $k . '"></i>';
			if ($type=='login'){
				$href = IHC_URL . '/public/social_handler.php?sm_login=' . $k . '&ihc_current_url=' . urlencode($current_url);
				if (!empty($is_locker)){
					$href .= '&is_locker=1';
				}
				$str .= '<div class="ihc-sm-item ' . $extra_class . '"><a href="' . $href . '">' . $icon . $label . '</a></div>';				
			} else if ($type=='register'){
				$str .= '<div onClick="ihc_run_social_reg(\''.$k.'\');" class="ihc-sm-item ' . $extra_class . '">' . $icon . $label . '<div class="iump-clear"></div></div>';
			} else if ($type=='update'){
				$already_class = '';
				if ($already_registered_sm && in_array($k, $already_registered_sm)){
					$already_class = ' ihc-sm-already-reg';
				}
				$href = IHC_URL . '/public/social_handler.php?reg_ext_usr=' . $k . '&ihc_current_url=' . urlencode($current_url);
				$str .= '<div class="ihc-sm-item ' . $extra_class . ' ' . $already_class . '"><a href="' . $href . '">' . $icon . $label . '<div class="iump-clear"></div></a></div>';
			}
		}
	}
	if ($str){
		$str = '<div class="ihc-sm-wrapp-fe ' . @$metas['ihc_sm_template'] . '">' . $str . '</div>';
		if (!empty($metas['ihc_sm_custom_css'])){
			$str = '<style>' . $metas['ihc_sm_custom_css'] . '</style>' . $str;
		}
	}
	return $str;
}

function ihc_print_links_login(){
	/*
	 * @param none
	 * @return string
	 */
	$str ='';
	$str .= '<div  class="impu-form-line-fr impu-form-links">';
				$pag_id = get_option('ihc_general_register_default_page');
				if($pag_id!==FALSE){
					$register_page = get_permalink( $pag_id );
					if (!$register_page) $register_page = get_home_url();
					$str .= '<div class="impu-form-links-reg"><a href="'.$register_page.'">'.__('Register', 'ihc').'</a></div>';
				}

			
				$pag_id = get_option('ihc_general_lost_pass_page');
				if($pag_id!==FALSE){
					$lost_pass_page = get_permalink( $pag_id );		
					if (!$lost_pass_page) $lost_pass_page = get_home_url(); 
					$str .= '<div class="impu-form-links-pass"><a href="'.$lost_pass_page.'">'.__('Lost your password?', 'ihc').'</a></div>';
				}

		$str .= '</div>';
	return $str;
}

function ihc_get_level_by_id($id){
	/*
	 * @param int
	 * @return array|bool
	 */
	$data = get_option('ihc_levels');
	if ($data!==FALSE){
		foreach ($data as $k=>$v){
			if ((int)$k==(int)$id){
				return $v;
			}
		}
	}
	return FALSE;
}

function ihc_format_str_like_wp( $str ){
	/*
	 * @param string
	 * @return string
	 */
	$str = preg_replace("/\n\n+/", "\n\n", $str);
	$str_arr = preg_split('/\n\s*\n/', $str, -1, PREG_SPLIT_NO_EMPTY);
	$str = '';

	foreach ( $str_arr as $str_val ) {
		$str .= '<p>' . trim($str_val, "\n") . "</p>\n";
	}
	return $str;
}

function ihc_array_value_exists($haystack, $needle, $key){
	/*
	 * @param array, string, string
	 * @return string|int, bool 
	 */
	foreach ($haystack as $k=>$v){
		if ($v[$key]==$needle){
			return $k;
		}
	}
	return FALSE;
}

function ihc_array_key_recursive($arr, $key){
	/*
	 * @param array, string|int
	 * @return string|int, bool
	 */
	foreach ($arr as $k=>$v){
		if (array_key_exists($key, $v)) return $k;
	}
	return FALSE;
}


function ihc_correct_text($str, $wp_editor_content=false){
	/*
	 * @param string, bool
	 * @return string
	 */
	$str = stripcslashes(htmlspecialchars_decode($str));
	if ($wp_editor_content){
		return ihc_format_str_like_wp($str);
	}
	return $str;
}

///////////forms utility

function indeed_create_form_element($attr=array()){
	/*
	 * @param string
	 * @return string
	 */
	foreach (array('name', 'id', 'value', 'class', 'other_args', 'disabled', 'placeholder', 'multiple_values', 'user_id', 'sublabel') as $k){
		if (!isset($attr[$k])){
			$attr[$k] = '';
		}
	}
	
	$str = '';
	if (isset($attr['type']) && $attr['type']){
		switch ($attr['type']){
			case 'text':
			case 'conditional_text':
				$str = '<input type="text" name="'.$attr['name'].'" id="'.$attr['id'].'" class="'.$attr['class'].'" value="' . ihc_correct_text($attr['value']) . '" placeholder="'.$attr['placeholder'].'" '.$attr['other_args'].' '.$attr['disabled'].' />';
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}
				break;
		
			case 'number':
				$str = '<input type="number" name="'.$attr['name'].'" id="'.$attr['id'].'" class="'.$attr['class'].'" value="'.$attr['value'].'"  '.$attr['other_args'].' '.$attr['disabled'].' />';
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}				
				break;
		
			case 'textarea':
				$str = '<textarea name="'.$attr['name'].'" id="'.$attr['id'].'" class="iump-form-textarea '.$attr['class'].'" '.$attr['other_args'].' '.$attr['disabled'].' >' . ihc_correct_text($attr['value']) . '</textarea>';
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}				
				break;
		
			case 'password':
				$str = '<input type="password" name="'.$attr['name'].'" id="'.$attr['id'].'" class="'.$attr['class'].'" value="'.$attr['value'].'" placeholder="'.$attr['placeholder'].'" '.$attr['other_args'].' />';
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}				
				break;
		
			case 'hidden':
				$str = '<input type="hidden" name="'.$attr['name'].'" id="'.$attr['id'].'" class="'.$attr['class'].'" value="'.$attr['value'].'" '.$attr['other_args'].' />';
				break;
		
			case 'checkbox':
				$str = '';
				if ($attr['multiple_values']){
					$id = 'ihc_checkbox_parent_' . rand(1,1000);
					$str .= '<div class="iump-form-checkbox-wrapper" id="' . $id . '">';
					foreach ($attr['multiple_values'] as $v){
						if (is_array($attr['value'])){
							$checked = (in_array($v, $attr['value'])) ? 'checked' : '';
						} else {
							$checked = ($v==$attr['value']) ? 'checked' : '';
						}
						$str .= '<div class="iump-form-checkbox">';
						$str .= '<input type="checkbox" name="'.$attr['name'].'[]" id="'.$attr['id'].'" class="'.$attr['class'].'" value="' . ihc_correct_text($v) . '" '.$checked.' '.$attr['other_args'].' '.$attr['disabled'].'  />';
						$str .= ihc_correct_text($v);
						$str .= '</div>';
					}
					$str .= '</div>';
				}
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}
				break;
		
			case 'radio':
				$str = '';
				if ($attr['multiple_values']){
					$id = 'ihc_radio_parent_' . rand(1,1000);
					$str .= '<div class="iump-form-radiobox-wrapper" id="' . $id . '">';
					foreach ($attr['multiple_values'] as $v){
						$checked = ($v==$attr['value']) ? 'checked' : '';
						$str .= '<div class="iump-form-radiobox">';
						$str .= '<input type="radio" name="'.$attr['name'].'" id="'.$attr['id'].'" class="'.$attr['class'].'" value="' . ihc_correct_text($v) . '" '.$checked.' '.$attr['other_args'].' '.$attr['disabled'].'  />';
						$str .= ihc_correct_text($v);
						$str .= '</div>';
					}
					$str .= '</div>';
				}
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}
				break;
		
			case 'select':
				$str = '';
				if ($attr['multiple_values']){					
					$str .= '<select name="'.$attr['name'].'" id="'.$attr['id'].'" class="iump-form-select '.$attr['class'].'" '.$attr['other_args'].' '.$attr['disabled'].' >';
					if ($attr['multiple_values']){
						foreach ($attr['multiple_values'] as $k=>$v){
							$selected = ($k==$attr['value']) ? 'selected' : '';
							$str .= '<option value="'.$k.'" '.$selected.'>' . ihc_correct_text($v) . '</option>';
						}						
					}
					$str .= '</select>';
				}
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}
				break;
				
			case 'multi_select':
				$str = '';
				if ($attr['multiple_values']){
					$str .= '<select name="'.$attr['name'].'[]" id="'.$attr['id'].'" class="iump-form-multiselect '.$attr['class'].'" '.$attr['other_args'].' '.$attr['disabled'].' multiple>';
					foreach ($attr['multiple_values'] as $k=>$v){
						if (is_array($attr['value'])){
							$selected = (in_array($v, $attr['value'])) ? 'selected' : '';
						} else {
							$selected = ($v==$attr['value']) ? 'selected' : '';
						}
						$str .= '<option value="'.$k.'" '.$selected.'>' . ihc_correct_text($v) . '</option>';
					}
					$str .= '</select>';
				}	
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}			
				break;
		
			case 'submit':
				$str = '<input type="submit" value="' . ihc_correct_text($attr['value']) . '" name="'.$attr['name'].'" id="'.$attr['id'].'" class="'.$attr['class'].'" '.$attr['other_args'].' '.$attr['disabled'].' />';
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}				
				break;
				
			case 'date':
				if (empty($attr['class'])){
					$attr['class'] = 'ihc-date-field';
				}
				$str = '';
				
				global $ihc_jquery_ui_min_css;
				if (empty($ihc_jquery_ui_min_css)){
					$ihc_jquery_ui_min_css = TRUE;
					$str .= '<link rel="stylesheet" type="text/css" href="' . IHC_URL . 'admin/assets/css/jquery-ui.min.css"/>' ;	
				}
				
				$str .= '<script>
					jQuery(document).ready(function() {
						jQuery(".'.$attr['class'].'").datepicker({
						dateFormat : "dd-mm-yy"
					});
				});
				</script>
				';
				$str .= '<input type="text" value="'.$attr['value'].'" name="'.$attr['name'].'" id="'.$attr['id'].'" class="iump-form-datepicker '.$attr['class'].'" '.$attr['other_args'].' '.$attr['disabled'].' />';
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}				
				break;	

			case 'file':
				$upload_settings = ihc_return_meta_arr('extra_settings');
				$max_size = $upload_settings['ihc_upload_max_size'] * 1000000; 
				$rand = rand(1,10000);
				$str .= '<div id="ihc_fileuploader_wrapp_' . $rand . '" class="ihc-wrapp-file-upload" style=" vertical-align: text-top;">';
				$str .= '<div class="ihc-file-upload ihc-file-upload-button">Upload</div>
						<script>						
							jQuery(document).ready(function() {
								jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").uploadFile({
									onSelect: function (files) {		
											jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ajax-file-upload-container").css("display", "block");									
											var check_value = jQuery("#ihc_upload_hidden_'.$rand.'").val();
											if (check_value!="" ){
												alert("To add a new image please remove the previous one!");
												return false;
											}	
                							return true;
            						},
									url: "'.IHC_URL.'public/ajax-upload.php",
									fileName: "ihc_file",
									dragDrop: false,
									showFileCounter: false,
									showProgress: true,
									showFileSize: false,
									maxFileSize: ' . $max_size . ',
									allowedTypes: "' . $upload_settings['ihc_upload_extensions'] . '",
									onSuccess: function(a, response, b, c){					
										if (response){
											var obj = jQuery.parseJSON(response);	
											jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").prepend("<div onClick=\"ihc_delete_file_via_ajax("+obj.id+", -1, \'#ihc_fileuploader_wrapp_' . $rand . '\', \'' . $attr['name'] . '\', \'#ihc_upload_hidden_'.$rand.'\');\" class=\'ihc-delete-attachment-bttn\'>Remove</div>");
											switch (obj.type){
												case "image":
													jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").prepend("<img src="+obj.url+" class=\'ihc-member-photo\' /><div class=\'ihc-clear\'></div>");
												break;
												case "other":
													jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").prepend("<div class=ihc-icon-file-type></div><div class=ihc-file-name-uploaded>"+obj.name+"</div>");
												break;
											}
											jQuery("#ihc_upload_hidden_'.$rand.'").val(obj.id);
											setTimeout(function(){
												jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ajax-file-upload-container").css("display", "none");
											}, 3000);										
										}
									}
								});
							});
						</script>';
				if ($attr['value']){
					$attachment_type = ihc_get_attachment_details($attr['value'], 'extension');	
					$url = wp_get_attachment_url($attr['value']);				
					switch ($attachment_type){
						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'gif':
							//print the picture
							$str .= '<img src="' . $url . '" class="ihc-member-photo" /><div class="ihc-clear"></div>';
							break;
						default:
							//default file type
							$str .= '<div class="ihc-icon-file-type"></div>';
							break;
					}
					$attachment_name = ihc_get_attachment_details($attr['value']);
					$str .= '<div class="ihc-file-name-uploaded"><a href="' . $url . '" target="_blank">' . $attachment_name . '</a></div>';
					$str .= '<div onClick=\'ihc_delete_file_via_ajax(' . $attr['value'] . ', '.$attr['user_id'].', "#ihc_fileuploader_wrapp_' . $rand . '", "' . $attr['name'] . '", "#ihc_upload_hidden_' . $rand . '");\' class="ihc-delete-attachment-bttn">Remove</div>';
				}
				$str .= '<input type="hidden" value="'.$attr['value'].'" name="' . $attr['name'] . '" id="ihc_upload_hidden_'.$rand.'" />';
				$str .= "</div>";
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}
				break;
				

			case 'upload_image':
				$upload_settings = ihc_return_meta_arr('extra_settings');
				$max_size = $upload_settings['ihc_avatar_max_size'] * 1000000;
				$rand = rand(1,10000);
				$str .= '<div id="ihc_fileuploader_wrapp_' . $rand . '" class="ihc-wrapp-file-upload" style=" vertical-align: text-top;">';
								$str .= '		<script>						
							jQuery(document).ready(function() {
								jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").uploadFile({
									onSelect: function (files) {			
											jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ajax-file-upload-container").css("display", "block");						
											var check_value = jQuery("#ihc_upload_hidden_'.$rand.'").val();
											if (check_value!="" ){
												alert("To add a new image please remove the previous one!");
												return false;
											}	
                							return true;
            						},
									url: "'.IHC_URL.'public/ajax-upload.php",
									allowedTypes: "jpg,png,jpeg,gif",
									fileName: "avatar",
									maxFileSize: ' . $max_size . ',
									dragDrop: false,
									showFileCounter: false,
									showProgress: true,
									onSuccess: function(a, response, b, c){
										if (response){
											var obj = jQuery.parseJSON(response);	
											jQuery("#ihc_upload_hidden_'.$rand.'").val(obj.id);
											jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").prepend("<div onClick=\"ihc_delete_file_via_ajax("+obj.id+", -1, \'#ihc_fileuploader_wrapp_' . $rand . '\', \'' . $attr['name'] . '\', \'#ihc_upload_hidden_'.$rand.'\');\" class=\'ihc-delete-attachment-bttn\'>Remove</div>");
											jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ihc-file-upload").prepend("<img src="+obj.url+" class=\'ihc-member-photo\' /><div class=\'ihc-clear\'></div>");	
											jQuery(".ihc-no-avatar").remove();
											setTimeout(function(){
												jQuery("#ihc_fileuploader_wrapp_' . $rand . ' .ajax-file-upload-container").css("display", "none");
											}, 3000);
										}
									}
								});
							});
						</script>';
				
				$str .= '<input type="hidden" value="'.$attr['value'].'" name="ihc_avatar"  id="ihc_upload_hidden_'.$rand.'" />';		
				
				if ($attr['value']){
					if (strpos($attr['value'], "http")===0){
						$url = $attr['value'];
					} else {
						$data = wp_get_attachment_image_src($attr['value']);
						if (!empty($data[0])){
							$url = $data[0];
						}
					}
					
					if (isset($url)){						
						$str .= '<img src="' . $url . '" class="ihc-member-photo" /><div class="ihc-clear"></div>';
						if (strpos($attr['value'], "http")===0){
							$str .= '<div onClick=\'ihc_delete_file_via_ajax("", '.$attr['user_id'].', "#ihc_fileuploader_wrapp_' . $rand . '", "' . $attr['name'] . '", "#ihc_upload_hidden_'.$rand.'" );\' class="ihc-delete-attachment-bttn">' . __("Remove", "ihc") . '</div>';							
						} else {
							$str .= '<div onClick=\'ihc_delete_file_via_ajax(' . $attr['value'] . ', '.$attr['user_id'].', "#ihc_fileuploader_wrapp_' . $rand . '", "' . $attr['name'] . '", "#ihc_upload_hidden_'.$rand.'" );\' class="ihc-delete-attachment-bttn">' . __("Remove", "ihc") . '</div>';							
						}
					}	
					$str .= '<div class="ihc-file-upload ihc-file-upload-button" style="display: none;">' . __("Upload", 'ihc') . '</div>';
				} else {
					$str .= '<div class="ihc-no-avatar ihc-member-photo"></div>';
					$str .= '<div class="ihc-file-upload ihc-file-upload-button" style="display: block;">' . __("Upload", 'ihc') . '</div>';
				}
					
				$str .= "</div>";	
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}			
				break;	

			case 'plain_text':
				$str = ihc_correct_text($attr['value']);
				if (!empty($attr['sublabel'])){
					$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($attr['sublabel']) . '</label>';
				}
				break;
		}		
	}
	return $str;
}

function ihc_from_simple_array_to_k_v($arr){
	/*
	 * @param array
	 * @return array
	 */
	$return_arr = array();
	foreach ($arr as $v){
		$return_arr[$v] = $v;
	}
	return $return_arr;
}

function indeed_form_start($action=false, $method=false, $other_stuff=''){
	/*
	 * @param bool, bool, string
	 * @return string
	 */
	$str = '<form action="';
	if($action) $str .= $action;
	else $str .= '';
	$str .= '" method="';
	if($method) $str .= $method;
	else $str .= 'post';
	$str .= '" ';
	$str .= $other_stuff;
	$str .= '>';
	return $str;
}

function indeed_form_end(){
	/*
	 * @param none
	 * @return string
	 */
	return '</form>';
}

function ihc_reorder_arr($arr){
	/*
	 * @param array
	 * @return array
	 */
	if (isset($arr) && count($arr)>0 && $arr !== false){
		$new_arr = false;
		foreach ($arr as $k=>$v){
			$order = $v['order'];
			$new_arr[$order][$k] = $v;
		}
		if ($new_arr && count($new_arr)){
			ksort($new_arr);
			foreach ($new_arr as $k=>$v){
				$return_arr[key($v)] = $v[key($v)];
			}
			return $return_arr;	
		}	
	}
	return $arr;
}

function ihc_check_show($arr=array()){
	/*
	 * @param array
	 * @return array
	 */
	if ($arr!==FALSE && count($arr)>0){
		$new_arr = array();
		foreach ($arr as $k=>$v){
			if (isset($v['show_on'])){
				if($v['show_on'] == 1)
					$new_arr[$k] = $v;
			} else {
				$new_arr[$k] = $v;
			}
		}
		return $new_arr;					
	}
	return $arr;
}

function ihc_return_cc_list($ips_cc_user, $ips_cc_pass){
	/*
	 * @param string, string
	 * @return array
	 */
	if (!class_exists('cc')){
		include_once IHC_PATH .'classes/email_services/constantcontact/class.cc.php';		
	}
	$list = array();
	$cc = new cc($ips_cc_user, $ips_cc_pass);
	$lists = $cc->get_lists('lists');
	if ($lists){
		foreach ((array) $lists as $v){
			$list[$v['id']] = array('name' => $v['Name']);
		}
	}
	return $list;
}


function ihc_get_all_post_types(){
	/*
	 * use this in front-end, returns all the custom post type available in db
	 * @param none
	 * @return array
	 */
	global $wpdb;
	$arr = array();
	$data = $wpdb->get_results('SELECT DISTINCT post_type FROM ' . $wpdb->prefix . 'posts WHERE post_status="publish";');
	if ($data && count($data)){
		foreach ($data as $obj){
			$arr[] = $obj->post_type;
		}		
	}
	return $arr;
}

function ihc_get_post_types_be(){
	/*
	 * @param none
	 * @return all custom post type that are registered
	 * use this for back-end actions
	 */
	$args = array('public'=>true, '_builtin'=>false);
	$data = get_post_types($args);
	return $data;
}


function ihc_get_post_id_by_cpt_name($custom_post_type, $post_name){
	/*
	 * @param custom post type - string
	 * cpt_name = name of current post
	 * @return - id of post
	 */
	global $wpdb;
	$data = $wpdb->get_row('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_type="' . $custom_post_type . '" AND post_name="' . $post_name . '"');
	if (!empty($data->ID)) return $data->ID;

	return FALSE;
}

function ihc_get_wp_roles_list(){
	/*
	 * @param none
	 * @return array with all wp roles available without administrator
	 */
	global $wp_roles;
	$roles = $wp_roles->get_names();
    if (!empty($roles)){
    	unset($roles['administrator']);// remove admin role from our list
    	return $roles;
    }
	return FALSE;
}

function ihc_get_multiply_time_value($time_type){
	/*
	 * @param string D,W,M,Y
	 * @return time in seconds
	 */
	$multiply = FALSE;
	switch ($time_type){
		case 'D':
			$multiply = 60*60*24;
		break;
		case 'W':
			$multiply = 60*60*24*7;
		break;
		case 'M':
			$multiply = 60*60*24*31;
		break;
		case 'Y':
			$multiply = 60*60*24*365;
		break;
	}
	return $multiply;
}

function ihc_delete_user_level_relation($l_id=FALSE, $u_id=FALSE){
	/*
	 * delete user meta level, delete relation from table ihc_user_levels
	 * @param level id and user id
	 * @return none
	 */
	if ($u_id && $l_id){		
		$levels_str = get_user_meta($u_id, 'ihc_user_levels', true);
		$levels_arr = explode(',', $levels_str);
		if (!is_array($l_id)){
			$lid_arr[] = $l_id;
		}
		$levels_arr = array_diff($levels_arr, $lid_arr);
		$levels_str = implode(',', $levels_arr);
		update_user_meta($u_id, 'ihc_user_levels', $levels_str);
		global $wpdb;
		$table_name = $wpdb->prefix . "ihc_user_levels";
		$wpdb->query('DELETE FROM ' . $table_name . ' WHERE user_id="'.$u_id.'" AND level_id="'.$l_id.'";');
		ihc_downgrade_levels_when_expire($u_id, $l_id);		
	}
}

function ihc_update_user_level_expire($level_data, $l_id, $u_id){
	/*
	 * update expire level for a user with the right expire time
	 * use this only when user has made the payment
	 * @param:
	 * - array with level metas
	 * - level id int
	 * - user id int
	 * @return none
	 */
	global $wpdb;
	$table = $wpdb->prefix . 'ihc_user_levels';
	
	if (empty($level_data['access_type'])){
		$level_data['access_type'] = 'unlimited';
	}
	
	$current_time = time();
	//getting the current expire time, if it's exists. Old expire time will be current time
	$data = $wpdb->get_row("SELECT expire_time FROM $table WHERE user_id='$u_id' AND level_id='$l_id';");
	if ($data && is_object($data) && !empty($data->expire_time)){
		$expire_time = strtotime($data->expire_time);
		if ($expire_time>0){
			$current_time = $expire_time; 
		}
	}
	
	//set end time
	switch ($level_data['access_type']){
		case 'unlimited':
			$end_time = strtotime('+10 years', $current_time);//unlimited will be ten years
		break;
		case 'limited':
			if (!empty($level_data['access_limited_time_type']) && !empty($level_data['access_limited_time_value'])){
				$multiply = ihc_get_multiply_time_value($level_data['access_limited_time_type']);
				$end_time = $current_time + $multiply * $level_data['access_limited_time_value'];
			}
		break;
		case 'date_interval':
			if (!empty($level_data['access_interval_end'])){
				$end_time = strtotime($level_data['access_interval_end']);
			}
		break;
		case 'regular_period':
			if (!empty($level_data['access_regular_time_type']) && !empty($level_data['access_regular_time_value'])){
				$multiply = ihc_get_multiply_time_value($level_data['access_regular_time_type']);
				$end_time = $current_time + $multiply * $level_data['access_regular_time_value'];
			}
		break;
	}
	
	$update_time = date('Y-m-d H:i:s', time());
	$end_time = date('Y-m-d H:i:s', $end_time);
	$q = 'UPDATE ' . $table . ' SET update_time="' . $update_time . '", expire_time="' . $end_time . '", notification=0, status=1 WHERE user_id="' . $u_id . '" AND level_id="' . $l_id . '";';
	$wpdb->query($q);
}

function ihc_get_start_expire_date_for_user_level($u_id, $l_id){
	/*
	 * @param user id, level id
	 * @return date when will expire
	 */
	global $wpdb;
	$arr = array('expire_time'=>FALSE, 'start_time'=>FALSE);
	$data = $wpdb->get_row('SELECT expire_time, start_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="'.$u_id.'" AND level_id="'.$l_id.'";');
	if (isset($data->start_time)){
		$arr['start_time'] = $data->start_time;
	}
	if (isset($data->expire_time)){
		$arr['expire_time'] = $data->expire_time;
	}
	return $arr;
}

function ihc_set_time_for_user_level($u_id, $l_id, $start, $expire){
	/*
	 * @param user id, level id, start time , expire time
	 * @return none
	 */	
	global $wpdb;	
	$update_time = date('Y-m-d H:i:s', time());
	
	$table = $wpdb->prefix . 'ihc_user_levels';
	$exists = $wpdb->get_row('SELECT id FROM ' . $table . ' WHERE user_id="'.$u_id.'" AND level_id="' . $l_id . '"; ');
	if (isset($exists->id)){
		//it's gonna be an update
		$q = 'UPDATE ' . $table . ' SET update_time="' . $update_time . '", start_time=';
		if (!$start){
			$q .= 'null';
		} else {
			$q .= '"'.$start.'"';
		}
		$q .= ', expire_time='; 
		if (!$expire){
			$q .= 'null';
		} else {
			$q .= '"'.$expire.'"';
		}
		$q .= ' WHERE user_id="' . $u_id . '" AND level_id="' . $l_id . '";';
		
	} else {
		//go create new row in db
		$q = 'INSERT INTO ' . $table . ' VALUES (null, "' . $u_id . '", "' . $l_id . '",';
		if (!$start){
			$q .= 'null';
		} else {
			$q .= '"'.$start.'"';
		}
		$q .= ',';
		if (!$expire){
			$q .= 'null';
		} else {
			$q .= '"'.$expire.'"';
		}
		$q .=  ', "' . $update_time . '", "' . $expire . '", 0, 1)';
	}
	$wpdb->query($q);
}

function ihc_insert_update_transaction($u_id, $txn_id, $post_data){
	/*
	 * @param user id, trascation id, post data from paypal
	 * @return none
	 */
	
	//remove quotes from post data
	foreach ($post_data as $k=>$v){
		if (is_string($post_data[$k])){
			if (strpos($post_data[$k], "'")!==FALSE){
				$post_data[$k] = stripslashes($post_data[$k]);
				$post_data[$k] = str_replace("'", "", $post_data[$k]);
			} else if (strpos($post_data[$k], "\'")!==FALSE){
				$post_data[$k] = stripslashes($post_data[$k]);
				$post_data[$k] = str_replace("\'", "", $post_data[$k]);
			}			
		}
	}
	
	global $wpdb;
	$table = $wpdb->prefix . 'indeed_members_payments';
	$exists = $wpdb->get_row('SELECT * FROM '.$table.' WHERE txn_id="'.$txn_id.'";');
	if ($exists){
		/************** UPDATE ***************/
		$history = '';
		$history_data = $wpdb->get_row('SELECT history FROM '.$table.' WHERE txn_id="'.$txn_id.'";');
		if ($history_data && isset($history_data->history)){
			//$history_data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $history_data->history);
			$history = unserialize($history_data->history);
		} else {
			$history_data = $wpdb->get_row('SELECT payment_data FROM '.$table.' WHERE txn_id="'.$txn_id.'";');
			if (isset($history_data->payment_data)){
				$temp = (array)json_decode($history_data->payment_data);
				if (isset($temp['custom'])) unset($temp['custom']);
				if (isset($temp['transaction_subject'])) unset($temp['transaction_subject']);
				$history[] = $temp;
			}
		}
		//remove custom from history
		$post_data_history = $post_data;
		if (isset($post_data_history['custom'])) unset($post_data_history['custom']);
		if (isset($post_data_history['transaction_subject'])) unset($post_data_history['transaction_subject']);
		$history[time()] = $post_data_history;
		$history_string = serialize($history);

		$wpdb->query('UPDATE '.$table.' SET history=\''.$history_string.'\' WHERE txn_id="'.$txn_id.'";');

		//////////update payment_data (last $_REQUEST )
		$post_data = json_encode($post_data);
		$wpdb->query('UPDATE '.$table.' SET payment_data=\''.$post_data.'\' WHERE txn_id="'.$txn_id.'";');

	} else {
		/************* insert ************/

		/////the history
		$post_data_history = $post_data;
		if (isset($post_data_history['custom'])) unset($post_data_history['custom']);
		if (isset($post_data_history['transaction_subject'])) unset($post_data_history['transaction_subject']);
		$history[time()] = $post_data_history;
		$history_str = serialize($history);

		////the payment data
		$post_data = json_encode($post_data);

		$wpdb->query( 'INSERT INTO '.$table.' VALUES (null, "'.$txn_id.'", '.$u_id.', \''.$post_data.'\', \''.$history_str.'\', null);' );
	}
}

function ihc_user_has_level($u_id, $l_id){
	/*
	 * test if user has a certain level
	 * @param user level
	 * @return bool
	 */
	$user_levels = get_user_meta($u_id, 'ihc_user_levels', true);
	if($user_levels){
		$levels = explode(',', $user_levels);
		if (isset($levels) && count($levels) && in_array($l_id, $levels)){
			$user_time = ihc_get_start_expire_date_for_user_level($u_id, $l_id);
			if(strtotime($user_time['expire_time']) > time())
				return TRUE;
		}
	}
	return FALSE;
}

function ihc_user_has_level_admin($uid, $lid){
	/*
	 * @param int, int
	 * @return bool
	 */
	
	global $wpdb;
	$data = $wpdb->get_row("SELECT id FROM " . $wpdb->prefix . "ihc_user_levels
			WHERE user_id='" . $uid . "'
			AND level_id='" . $lid . "';");
	if ($data!==FALSE && isset($data->id)){
		return TRUE;
	}
	return FALSE;
}

function ihc_insert_debug_payment_log($source, $data){
	/*
	 * insert into ihc_debug_payments
	 * @param source = type of payment service (paypall)
	 * data = the request from payment service
	 * @return none
	 */
	global $wpdb;
	$table = $wpdb->prefix . "ihc_debug_payments";
	$time = date('Y-m-d H:i:s', time());
	$data = serialize($data);
	$wpdb->query('INSERT INTO ' . $table . ' VALUES(null, "'.$source.'", \''.$data.'\', "'.$time.'");');
}

function ihc_send_user_notifications($u_id=FALSE, $notification_type='', $l_id=FALSE, $dynamic_data=array()){
	/*
	 * main function for notification module
	 * send e-mail to user
	 * @param:
	 * user id ($u_id) - int, 
	 * notification type ($notification_type) - string
	 * optional level id ($l_id) - int, -1 means all levels
	 * dynamic_data - array
	 * @return TRUE if mail was sent, FALSE otherwise
	 */
	global $wpdb;
	if ($u_id && $notification_type){

		//check if we have instances for this notification type [and for level]
		if ($l_id!==FALSE && $l_id>-1){
			$q = "SELECT * FROM " . $wpdb->prefix . "ihc_notifications 
					WHERE 1=1 
					AND notification_type='" . $notification_type . "' 
					AND level_id='" . $l_id . "'
					ORDER BY id DESC LIMIT 1;";
			$data = $wpdb->get_results($q);
		}
		
		if ($l_id===FALSE || $l_id==-1 || empty($data)){
			$q = "SELECT * FROM " . $wpdb->prefix . "ihc_notifications
					WHERE 1=1
					AND notification_type='" . $notification_type . "'
					AND level_id='-1'
					ORDER BY id DESC LIMIT 1;";
			$data = $wpdb->get_results($q);			
		}
		if (!empty($data)){
			$subject = $data[0]->subject;
			$message = $data[0]->message;				
			
			$from_name = get_option('ihc_notification_name');
			if (!$from_name){
				$from_name = get_option("blogname");
			}
			
			//user levels
			$level_list_data = get_user_meta($u_id, 'ihc_user_levels', true);
			if (isset($level_list_data)){
				$level_list_data = explode(',', $level_list_data);
				foreach ($level_list_data as $id){
					$temp_level_data = ihc_get_level_by_id($id);
					$level_list_arr[] = $temp_level_data['label'];
				}
				if ($level_list_arr){
					$level_list = implode(',', $level_list_arr);
				}
			}
			
			//user data
			$u_data = get_userdata($u_id);
			$user_email = $u_data->data->user_email;
			//from email
			$from_email = get_option('ihc_notification_email_from');
			if (!$from_email){
				$from_email = get_option('admin_email');
			}
			$message = ihc_replace_constants($message, $u_id, $l_id, $l_id, $dynamic_data);
			$subject = ihc_replace_constants($subject, $u_id, $l_id, $l_id, $dynamic_data);
			
			$message = stripslashes(htmlspecialchars_decode(ihc_format_str_like_wp($message)));
			
			$message = "<html><head></head><body>" . $message . "</body></html>";
			
			if ($subject && $message && $user_email){
				$headers[] = "From: $from_name <$from_email>";
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				$sent = wp_mail( $user_email, $subject, $message, $headers );
				return $sent;
			}
		
		}
	}
	return FALSE;
}

function ihc_get_uid_lid_by_stripe($stripe_txn_id){
	/*
	 * @param transaction id - string
	 * @return array 
	 */
	global $wpdb;	
	$db_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix ."indeed_members_payments WHERE `txn_id`='" . $stripe_txn_id . "';");
	$data = array();
	if ($db_data){
		if (isset($db_data->u_id)){
			$data['uid'] = $db_data->u_id;
		}
		if (isset($db_data->payment_data)){
			$data_db_json = json_decode($db_data->payment_data, TRUE);
			if (isset($data_db_json['level'])){
				$data['lid'] = $data_db_json['level'];
			}
			$data['payment_data'] = $data_db_json;			
		}
	}
	return $data;
}

function ihc_twocheckout_submit($u_id, $l_id, $code=''){
	/*
	 * Redirect to 2checkout payment
	 * @param user id, level id
	 * @return none
	 */
	$level_data = get_option('ihc_levels');
	$amount = $level_data[$l_id]['price'];
	$currency = get_option('ihc_currency');
	$checkout_account_num = get_option('ihc_twocheckout_account_number');
	
	//========= DISCOUNT
	if ($code){
		$coupon_data = ihc_check_coupon($code, $l_id);
		if ($coupon_data){
			if (isset($level_data[$l_id]['access_type']) && $level_data[$l_id]['access_type']=='regular_period'){
				//discount on recurring payment
				if (empty($coupon_data['reccuring'])){
					//just one time
					$discount_once = -($amount - ihc_coupon_return_price_after_decrease($amount, $coupon_data));
				} else {
					//on every payment
					$amount = ihc_coupon_return_price_after_decrease($amount, $coupon_data);
				}
			} else {
				//discount on single payment
				$amount = ihc_coupon_return_price_after_decrease($amount, $coupon_data);
			}
		}
	}
	
	
	$params_arr = array(
			'sid' => $checkout_account_num,
			'mode' => '2CO',
			'pay_method' => 'CC',
			'li_0_type' => 'product',
			'li_0_name' => 'Level ' . $l_id,
			'li_0_product_id' => $l_id,
			'li_0_quantity' => 1,
			'li_0_price' => $amount,
			'li_0_tangible' => 'N',
			'li_0_description' => json_encode(array("u_id" => $u_id, "l_id" => $l_id)),
			'currency_code' => $currency,
			'x_receipt_link_url' => admin_url("admin-ajax.php") . "?action=ihc_twocheckout_ins",//
			'purchase_step' => 'billing-information',
	);
	
	//====================== RECURRING
	if (isset($level_data[$l_id]['access_type']) && $level_data[$l_id]['access_type']=='regular_period'){
	
		switch ($level_data[$l_id]['access_regular_time_type']){
			case 'D':
				$weeks = $level_data[$l_id]['access_regular_time_value'] / 7;
				if ($weeks<1){
					$weeks = 1;
				}
				$reccurence_time = ceil($weeks) . ' Week';
				$billing = ceil($weeks) . ' Week';
				break;
			case 'W':
				$reccurence_time = $level_data[$l_id]['access_regular_time_value'] . ' Week';
				$billing = $level_data[$l_id]['billing_limit_num'] . ' Week';
				break;
			case 'M':
				$reccurence_time = $level_data[$l_id]['access_regular_time_value'] . ' Month';
				$billing = $level_data[$l_id]['billing_limit_num'] . ' Month';
				break;
			case 'Y':
				$reccurence_time = $level_data[$l_id]['access_regular_time_value'] . ' Year';
				$billing = $level_data[$l_id]['billing_limit_num'] . ' Year';
				break;
		}
		$params_arr['li_0_recurrence'] = $reccurence_time;//billing frequency. Ex. �1 Week� to bill order once a week. (Can use # Week, # Month, or # Year)
		$params_arr['li_0_duration'] = $billing;//how long to continue billing. Ex. �1 Year�, to continue billing for 1 year. (Forever or # Week, # Month, # Year)
	
		//trial for a single subscribe payment
		if (isset($level_data[$l_id]['access_trial_type']) && $level_data[$l_id]['access_trial_type']==2 && isset($level_data[$l_id]['access_trial_couple_cycles']) && $level_data[$l_id]['access_trial_couple_cycles']>0){
			////DISCOUNT
			$params_arr['li_0_startup_fee'] = $level_data[$l_id]['access_trial_price'] - $amount;
			if (!empty($discount_once)){
				//discount just once on reccuring with trial period
				$params_arr['li_0_startup_fee'] = $params_arr['li_0_startup_fee'] + $discount_once;
			}
		} else if (!empty($discount_once)){
			//discount just once on reccuring without trial period
			$params_arr['li_0_startup_fee'] = $discount_once;
		}
	}
	
	$sandbox = get_option('ihc_twocheckout_sandbox');
	if ($sandbox){
		$base_url = "sandbox.2checkout.com";
		$params_arr['demo'] = 'Y';
	} else {
		$base_url = "www.2checkout.com";
	}
	
	$params_str = '';
	foreach ($params_arr as $k=>$v){
		if (empty($params_str)){
			$params_str = '?';
		} else {
			$params_str .= '&';
		}
		$params_str .= urlencode($k) . "=" . urlencode($v);
	}
	
	$redirect_url = 'https://' . $base_url . '/checkout/purchase' . $params_str;
	
	//logout user...
	wp_logout();
	
	wp_redirect( $redirect_url );
	exit;
}

function ihc_print_bank_transfer_order($u_id, $l_id){
	/*
	 * print the bank transfer message
	 * @param user id & level id
	 * @return string
	 */
	$msg = get_option('ihc_bank_transfer_message');
	$msg = ihc_replace_constants($msg, $u_id, $l_id, $l_id);
	//send e-mail
	ihc_send_user_notifications($u_id, 'bank_transfer', $l_id);
	return '<div class="ihc-bank-transfer-msg" id="ihc_bt_success_msg">' . ihc_correct_text($msg) . '</div>';
}

function ihc_downgrade_levels_when_expire($uid, $lid){
	/*
	 * add after expire level for specified user
	 * @param user id, level id
	 * @return bool, true if succeed
	 */
	$level_data = ihc_get_level_by_id($lid);
	if (isset($level_data['afterexpire_level']) && $level_data['afterexpire_level']!=-1){
		$user_levels = get_user_meta($uid, 'ihc_user_levels', true);
		if ($user_levels!==FALSE && $user_levels!=''){
			$user_levels_arr = explode(',', $user_levels);
			if (!in_array($level_data['afterexpire_level'], $user_levels_arr)){
				$user_levels_arr[] = $level_data['afterexpire_level'];
			}
			$user_levels = implode(',', $user_levels_arr);
		} else {
			$user_levels = $level_data['afterexpire_level'];
		}
		$succees = ihc_handle_levels_assign($uid, $level_data['afterexpire_level']);//assign the new level expire time and stuff...
		if ($succees){
			update_user_meta($uid, 'ihc_user_levels', $user_levels);//assign the new level
			return TRUE;			
		}
	}
	return FALSE;
}

function ihc_handle_levels_assign($uid=FALSE, $lid=FALSE){
	/*
	 * insert into db when user was start using this level,
	 * @param user id, level id
	 * @return bool, true if succeed
	 */
	if ($uid && $lid){	
		$old_levels = get_user_meta($uid, 'ihc_user_levels', true);
		
		if (strpos($old_levels, $lid)===FALSE){
			//we got a new level to assign
			$level_data = ihc_get_level_by_id($lid);//getting details about current level
			$current_time = time();
						
			if (empty($level_data['access_type'])){
				$level_data['access_type'] = 'unlimited';
			}
						
			//set start time
			if ( $level_data['access_type']=='date_interval' && !empty($level_data['access_interval_start']) ){
				$start_time = strtotime($level_data['access_interval_start']);
			} else {
				$start_time = $current_time;
			}
						
			//set end time
			if ($level_data['payment_type']=='payment'){
				//end time will be expired, updated when payment
				$end_time = '0000-00-00 00:00:00';
			} else {
				//it's free so we set the correct expire time
				switch ($level_data['access_type']){
					case 'unlimited':
						$end_time = strtotime('+10 years', $current_time);//unlimited will be ten years
						break;
					case 'limited':
						if (!empty($level_data['access_limited_time_type']) && !empty($level_data['access_limited_time_value'])){
							$multiply = ihc_get_multiply_time_value($level_data['access_limited_time_type']);
							$end_time = $current_time + $multiply * $level_data['access_limited_time_value'];
						}
						break;
					case 'date_interval':
						if (!empty($level_data['access_interval_end'])){
							$end_time = strtotime($level_data['access_interval_end']);
						}
						break;
					case 'regular_period':
						if (!empty($level_data['access_regular_time_type']) && !empty($level_data['access_regular_time_value'])){
							$multiply = ihc_get_multiply_time_value($level_data['access_regular_time_type']);
							$end_time = $current_time + $multiply * $level_data['access_regular_time_value'];
						}
						break;
				}//end of switch
				$end_time = date('Y-m-d H:i:s', $end_time);
			}
						
			$update_time = date('Y-m-d H:i:s', $current_time);
			$start_time = date('Y-m-d H:i:s', $start_time);
						
			global $wpdb;
			$table = $wpdb->prefix . 'ihc_user_levels';
			$exists = $wpdb->get_row('SELECT * FROM ' . $table . ' WHERE user_id="' . $uid . '" AND level_id="' . $lid . '";');
			if (!empty($exists)){
				$wpdb->query('DELETE FROM ' . $table .' WHERE user_id="' . $uid . '" AND level_id="' . $lid . '";');//assure that pair user_id - level_id entry not exists
			}
			$wpdb->query('INSERT INTO ' . $table . '
							VALUES(null, "' . $uid . '", "' . $lid . '", "' . $start_time . '", "' . $update_time . '", "' . $end_time . '", 0, 1);');
			return TRUE;
		}
	}
	return FALSE;
}

function ihc_make_csv_user_list(){
	/*
	 * generate csv file with all users
	 * @param none
	 * @return string, link to csv file or empty string
	 */
	global $wpdb;		
	$users_obj = new WP_User_Query(array(
			'meta_query' => array(
				array(
					'key' => $wpdb->get_blog_prefix() . 'capabilities',
					'value' => 'administrator',
					'compare' => 'NOT LIKE'
				)
			)
	));
	$users = $users_obj->results;

	if ($users){
		//if we have users
		$file_path = IHC_PATH . 'users.csv';
		$file_link = IHC_URL . 'users.csv';
		if (file_exists($file_path)){
			unlink($file_path);
		}
		$file_resource = fopen($file_path, 'w');
		
		$register_fields = ihc_get_user_reg_fields();
		foreach ($register_fields as $k=>$v){
			if ($v['name']=='pass1' || $v['name']=='pass2' || $v['name']=='tos' || $v['name']=='recaptcha' || $v['name']=='confirm_email' || $v['name']=='ihc_social_media'){
				unset($register_fields[$k]);
			} else {
				if (isset($v['native_wp']) && $v['native_wp']){
					$data[] = __($v['label'], 'ihc');
				} else {
					$data[] = $v['label'];
				}
			}			
		}
		$data[] = __('Level', 'ihc');
		$data[] = __('WP User Roles', 'ihc');
		$data[] = __('Join Date', 'ihc');
		fputcsv($file_resource, $data, ",");
		unset($data);
		
		foreach ($users as $user){
			foreach ($register_fields as $v){
				if (isset($user->data->$v['name'])){
					$data[] = $user->data->$v['name'];
				} else {
					$user_data = get_user_meta($user->data->ID, $v['name'], true);
					if ($user_data!==FALSE){
						if (is_array($user_data)){
							$data[] = implode(",", $user_data);
						} else {
							$data[] = $user_data;
						}
					} else {
						$data[] = ' ';
					}
				}	
			}
			$levels = get_user_meta($user->data->ID, 'ihc_user_levels', true);
			if (isset($levels)){
				$levels_arr = explode(",", $levels);
				foreach ($levels_arr as $lid){
					$current_level_data = ihc_get_level_by_id($lid);
					if (isset($current_level_data['label'])){
						$level_arr_to_write[] = $current_level_data['label'];
					} else {
						$level_arr_to_write[] = ' ';
					}			
				}
				if (isset($level_arr_to_write)){
					$write_str = implode(',', $level_arr_to_write);
					$data[] = $write_str;
					unset($write_str);
					unset($level_arr_to_write);
				} else {
					$data[] = " ";
				}
			} else {
				$data[] = " ";
			}
			
			$data[] = $user->roles[0];
			$data[] = $user->data->user_registered;
			fputcsv($file_resource, $data, ",");
			unset($data);
		}	
		fclose($file_resource);
		return $file_link;
	}
	return '';
}

function ihc_get_attachment_details($id, $return_type='name'){
	/*
	 * @param attachment id, what to return: name or extension
	 * @return string : 
	 */
	$attachment_data = wp_get_attachment_url($id);
	if (isset($attachment_data)){
		$attachment_arr = explode('/', $attachment_data);
		if (isset($attachment_arr)){
			end($attachment_arr);
			$attachment_name = $attachment_arr[key($attachment_arr)];
			if ($return_type=='name'){
				return $attachment_name;
			}
			$attachment_type = explode('.', $attachment_name);
			if (isset($attachment_type)){
				end($attachment_type);
				if (isset($attachment_type[key($attachment_type)])){
					return $attachment_type[key($attachment_type)];
				}				
			}		
		}
	}
	return 'Unknown';
}

function ihc_replace_constants($str = '', $u_id = FALSE, $current_level_id=FALSE, $l_id=FALSE, $dynamic_data = array()){
	/*
	 * @param $str - string where to replace,
	 * user id - int, 
	 * current level id - int, 
	 * level id - int, 
	 * dynamic_data must be an array ( {name of constant} => {value} )
	 * @return string
	 */
	if ($u_id){
		//$u_id = '';//from param
		//$l_id = ''; // from param
		$username = '';
		$first_name = '';
		$last_name = '';		
		$current_level = '';
		$level_expire_time = '';
		$level_list = '';
		$user_email = '';
		$account_page = '';
		$login_page = '';
		$blogname = '';
		$blogurl = '';		
		$level_name = '';
		$amount = '';
		$currency = '';
		$site_url = '';

		//user levels
		$level_list_data = get_user_meta($u_id, 'ihc_user_levels', true);
		if (isset($level_list_data)){
			$level_list_data = explode(',', $level_list_data);
			foreach ($level_list_data as $id){
				$temp_level_data = ihc_get_level_by_id($id);
				$level_list_arr[] = $temp_level_data['label'];
			}
			if ($level_list_arr){
				$level_list = implode(',', $level_list_arr);
			}
		}

		//user data
		$u_data = get_userdata($u_id);
		$user_email = $u_data->data->user_email;
		$username = $u_data->data->user_login;
		$first_name = get_user_meta($u_id, 'first_name', true);
		$last_name = get_user_meta($u_id, 'last_name', true);
		$blogname = get_option("blogname");
		$blogurl = get_option("siteurl");
		$currency = get_option('ihc_currency');
		$site_url = get_option('siteurl');

		//current_level,current_level_expire_date
		if ($current_level_id!==FALSE){
			$current_level_data = ihc_get_level_by_id($current_level_id);
			$current_level = $current_level_data['label'];
			$time = ihc_get_start_expire_date_for_user_level($u_id, $current_level_id);
			$level_expire_time = $time['expire_time'];
		}

		//account page
		$account_page = get_option("ihc_general_user_page");
		if ($account_page){
			$account_page = get_permalink($account_page);
		}
		//login page
		$login_page = get_option("ihc_general_login_default_page");
		if ($login_page){
			$login_page = get_permalink($login_page);
		}

		if ($l_id!==FALSE){
			$level_data = ihc_get_level_by_id($l_id);
			$level_name = $level_data['label'];
			$amount = $level_data['price'];
		} else {
			$l_id = '';
		}
		
		$replace = array(
				"{username}" => $username,
				"{first_name}" => $first_name,
				"{last_name}" => $last_name,
				"{user_id}" => $u_id,
				"{current_level}" => $current_level,
				"{current_level_expire_date}" => $level_expire_time,
				"{level_list}" => $level_list,
				"{user_email}" => $user_email,
				"{account_page}" => $account_page,
				"{login_page}" => $login_page,
				"{blogname}" => $blogname,
				"{blogurl}" => $blogurl,
				"{level_id}" => $l_id,
				"{level_name}" => $level_name,
				"{amount}" => $amount,
				"{currency}" => $currency,
				"{siteurl}" => $site_url,
		);
		
		foreach (ihc_get_custom_constant_fields() as $k=>$v){
			$replace[$k] = get_user_meta($u_id, $v, TRUE);
			if (is_array($replace[$k])){
				$replace[$k] = implode(',', $replace[$k]);
			}
		}
		
		if (!empty($dynamic_data['{verify_email_address_link}'])){
			$replace['{verify_email_address_link}'] = $dynamic_data['{verify_email_address_link}'];
		}
		
		if (!empty($dynamic_data['{NEW_PASSWORD}'])){
			$replace['{NEW_PASSWORD}'] = $dynamic_data['{NEW_PASSWORD}'];
		}

		foreach ($replace as $k=>$v){
			$str = str_replace($k, $v, $str);
		}

	}
	return $str;
}

function ihc_random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
	/*
	 * @param length - int, keyspace - string
	 * @return string
	 */
	$str = '';
	$max = mb_strlen($keyspace, '8bit') - 1;
	for ($i = 0; $i < $length; ++$i) {
		$str .= $keyspace[rand(0, $max)];
	}
	return $str;
}

function ihc_cancel_twocheckout_subscription($transaction_id){
	/*
	 * @param string
	 * @return boolean
	 */
	require_once IHC_PATH . 'classes/twocheckout/Twocheckout.php';
	//set API connection vars
	$api_user = get_option('ihc_twocheckout_api_user');
	$api_pass = get_option('ihc_twocheckout_api_pass');
	$api_private_key = get_option('ihc_twocheckout_private_key');
	$account_num = get_option('ihc_twocheckout_account_number');	
	$sandbox = get_option('ihc_twocheckout_sandbox');
	
	Twocheckout::sellerId($account_num);
	Twocheckout::privateKey($api_private_key);
	Twocheckout::username($api_user);
	Twocheckout::password($api_pass);
	Twocheckout::$verifySSL = false;

	$params = array();
	$params['sale_id'] = $transaction_id;
	if($sandbox){
		Twocheckout::sandbox(true);
		$params['demo'] = 'Y';
	} else {
		Twocheckout::sandbox(false);
	}
	try {
		$result = Twocheckout_Sale::stop( $params );
	} catch(Exception $e){
		
	}
	
	// Successfully cancelled
	if (isset($result['response_code']) && $result['response_code'] === 'OK') {
		return true;
	} else {
		//fail
		return false;
	}
}

function ihc_show_cancel_level_link($u_id, $l_id){
	/*
	 * @param user id, level id
	 * @return bool, true if we can show the cancel buntton
	 */
	$level_data = ihc_get_level_by_id($l_id);
	if (isset($level_data['access_type']) && $level_data['access_type']=='regular_period'){//only for reccurence
		global $wpdb;
		$data = $wpdb->get_row("SELECT status FROM " . $wpdb->prefix . "ihc_user_levels WHERE user_id='" . $u_id . "' AND level_id='" . $l_id . "';");
		if ($data->status){
			return TRUE;
		}
	}
	return FALSE;
}

function ihc_cancel_level($u_id, $l_id){
	/*
	 * cancel subscription from payments services
	 * @param u_id - user id (int), l_id - level id (int)
	 * @return none
	 */
	$txn_id = '';
	$payment_type = '';
	global $wpdb;
	$data = $wpdb->get_results("SELECT txn_id, payment_data FROM " . $wpdb->prefix . "indeed_members_payments
			WHERE `u_id`='" . $u_id . "' ORDER BY paydate DESC;" );
	//we need to select last transaction that involved this level id
	foreach ($data as $obj){
		$arr = json_decode($obj->payment_data, TRUE);
		
		$completed = FALSE;
		if (!empty($arr['payment_status'])){
			$completed = TRUE;
		} else if (isset($arr['x_response_code']) && ($arr['x_response_code'] == 1)){
			$completed = TRUE;
		} else if (isset($arr['code']) && ($arr['code'] == 2)){
			$completed = TRUE;
		} else if (isset($arr['message']) && $arr['message']=='success'){
			$completed = TRUE;
		}
		
		if (!$completed){
			continue;	
		}
		
		if (isset($arr['ihc_payment_type'])){
			//in case we know the payment type
			$payment_type = $arr['ihc_payment_type'];
			switch ($arr['ihc_payment_type']){
				case 'nextpay':
					$custom = json_decode($arr['custom'], TRUE);
					if ($custom['level_id']==$l_id){
						//it what we looking for
						$txn_id = $obj->txn_id;
						$payment_type = 'nextpay';
						break 2;
					}
					break;
			}//end of switch
		} else {
			//don't know from where the payment was made
			$payment_type = get_option('ihc_payment_selected');
			if (isset($arr['custom'])){
				$custom = json_decode($arr['custom'], TRUE);
				if ($custom['level_id']==$l_id){
					//it's paypal and it's the level we want
					$txn_id = $obj->txn_id;
					$payment_type = 'nextpay';
					break;
				}
			} else if (isset($arr['level']) && $arr['level']==$l_id){
				$txn_id = $obj->txn_id;
			}
		}
		
	}//end of foreach

	if ($txn_id && $payment_type){
		//if we have the transaction id, payment type && user id we can go further
		switch ($payment_type){
			case 'nextpay':
				$alias = get_option('ihc_nextpay_key');
                $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=" . urlencode($alias);

				wp_redirect($url);
				exit;
				break;
		}

		//after we cancel the subscription in payment service, we must modify the status in our db
		$wpdb->query("UPDATE " . $wpdb->prefix . "ihc_user_levels SET status='0' WHERE user_id='" . $u_id . "' AND level_id='" . $l_id . "';");
	}
}

function ihc_show_renew_level_link($l_id){
	/*
	 * @param level id
	 * @return bool, true if we must show to renew level link
	 */
	$level_data = ihc_get_level_by_id($l_id);
	if (isset($level_data['access_type']) && $level_data['access_type']=='limited'){
		return TRUE;
	}
	return FALSE;
}


function ihc_stripe_renew_script($form_id){
	/*
	 * @param string
	 * @return string
	 */
	$publishable_key = get_option('ihc_stripe_publishable_key');
	$str ='';
	$str .= '<script src="https://checkout.stripe.com/checkout.js"></script>
	<script>
	var renew_stripe = StripeCheckout.configure({
		key: "' . $publishable_key . '",
		locale: "auto",
		token: function(response) {
			var input = jQuery("<input type=hidden name=stripeToken id=stripeToken />").val(response.id);
			var email = jQuery("<input type=hidden name=stripeEmail id=stripeEmail />").val(response.email);
			jQuery("' . $form_id . '").append(input);
			jQuery("' . $form_id . '").append(email);
			jQuery("' . $form_id . '").submit();
		}
	});
	
	function ihc_stripe_renew_payment(l_name, l_amount, lid){
		var l_amount = l_amount * 100;
		if (l_amount<50){
			l_amount = 50;
		}
		jQuery("#ihc_renew_level").val(lid);
		if (jQuery("#ihc_coupon").val()){
			jQuery.ajax({
						type : "post",
						url : "' . IHC_URL . 'public/custom-ajax.php",
						data : {
							    ihc_coupon: jQuery("#ihc_coupon").val(),
							    l_id: lid,
							    initial_price: l_amount
						},
						success: function (data) {
							if (data!=0){
								if (jQuery("#ihc_coupon").val()){
									jQuery("' . $form_id . '").append("<input type=hidden value=" + jQuery("#ihc_coupon").val() + " name=ihc_coupon />");
								}
								var obj = jQuery.parseJSON(data);
								if (typeof obj.price!="undefined"){
									var l_amount = obj.price;
									if (l_amount<50){
										l_amount = 50;
									}
									renew_stripe.open({
										name: l_name,
										description: "Level "+lid,
										amount: l_amount,
									});
								}
							}
						}
			});
		} else {
			renew_stripe.open({
				name: l_name,
				description: "Level "+lid,
				amount: l_amount,
			});
		}
	}
	</script>';
	return $str;
}

function ihc_get_user_level_status_for_ac($u_id, $l_id){
	/*
	 * @param int, int
	 * @return string
	 */
	$status = __('Active', 'ihc');
	global $wpdb;
	$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix ."ihc_user_levels WHERE user_id='$u_id' AND level_id='$l_id' ");
	if ($data){
		if ($data->status==0){
			$status =  __('Canceled', 'ihc');
		} else {
			$grace_period = get_option('ihc_grace_period');
			$expire_time_after_grace = strtotime($data->expire_time) + $grace_period * 24 * 60 * 60;
			if ($expire_time_after_grace<0){
				$status = __("Hold", 'ihc');
			} else if (time()>$expire_time_after_grace){
				$status = __("Expired", 'ihc');
			} else if (strtotime($data->start_time)>time()){
				$status = __("Inactive", 'ihc');
			}
		}	
	}	
	return $status;
}

function ihc_set_level_status($u_id='', $l_id='', $status=''){
	/*
	 * @param: user id, level id, status
	 * status must be : 1 (in case the level can be renew) or 2 (in case of level it's renewed)
	 * @return none
	 */
	global $wpdb;
	$table = $wpdb->prefix . 'ihc_user_levels';
	$exists = $wpdb->get_row("SELECT * FROM $table WHERE user_id='$u_id' AND level_id='$l_id';");
	if ($exists){
		$wpdb->query("UPDATE $table SET status='$status' WHERE user_id='$u_id' AND level_id='$l_id'; ");
	}
}



function ihc_check_social_status($type){
	/*
	 * @param string name of social media
	 * @return array
	 */
	$return = array();
	$return['active'] = '';
	$return['status'] = 0;
	$return['settings'] = 'Uncompleted';
	switch ($type){
		case 'fb':
			$arr = ihc_return_meta_arr('fb');
			if (!empty($arr['ihc_fb_app_id']) && !empty($arr['ihc_fb_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_fb_status'])){
				$return['status'] = 1;
				$return['active'] = 'fb-active';
			}
			break;
		case 'tw':
			$arr = ihc_return_meta_arr('tw');
			if (!empty($arr['ihc_tw_app_key']) && !empty($arr['ihc_tw_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_tw_status'])){
				$return['status'] = 1;
				$return['active'] = 'tw-active';
			}
			break;
		case 'in':
			$arr = ihc_return_meta_arr('in');
			if (!empty($arr['ihc_in_app_key']) && !empty($arr['ihc_in_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_in_status'])){
				$return['status'] = 1;
				$return['active'] = 'in-active';
			}				
			break;
		case 'tbr':
			$arr = ihc_return_meta_arr('tbr');
			if (!empty($arr['ihc_tbr_app_key']) && !empty($arr['ihc_tbr_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_tbr_status'])){
				$return['status'] = 1;
				$return['active'] = 'tbr-active';
			}
			break;
		case 'ig':
			$arr = ihc_return_meta_arr('ig');
			if (!empty($arr['ihc_ig_app_id']) && !empty($arr['ihc_ig_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_ig_status'])){
				$return['status'] = 1;
				$return['active'] = 'ig-active';
			}
			break;
		case 'vk':
			$arr = ihc_return_meta_arr('vk');
			if (!empty($arr['ihc_vk_app_id']) && !empty($arr['ihc_vk_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_vk_status'])){
				$return['status'] = 1;
				$return['active'] = 'vk-active';
			}				
			break;
		case 'goo':
			$arr = ihc_return_meta_arr('goo');
			if (!empty($arr['ihc_goo_app_id']) && !empty($arr['ihc_goo_app_secret'])){
				$return['settings'] = 'Completed';
			}
			if (!empty($arr['ihc_goo_status'])){
				$return['status'] = 1;
				$return['active'] = 'goo-active';
			}				
			break;
	}
	return $return;
}

function ihc_generate_color_hex(){
	/*
	 * @param none
	 * @return string
	 */
	$colors =  array('#0a9fd8', '#38cbcb', '#27bebe', '#0bb586', '#94c523', '#6a3da3', '#f1505b', '#ee3733', '#f36510', '#f8ba01');
	return $colors[rand(0, (count($colors)-1) )];
}

//=================== COUPONS
function ihc_create_coupon($post_data=array()){
	/*
	 * @param post_data (array)
	 * @return boolean
	 */
	if ($post_data){
		global $wpdb;
		//check if this code already exists
		$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix ."ihc_coupons WHERE code='" . $post_data['code'] . "';");
		if ($data){
			return FALSE;
		}
		$code = $post_data['code'];
		unset($post_data['code']);
		$settings = serialize($post_data);
		$wpdb->query("INSERT INTO " . $wpdb->prefix ."ihc_coupons VALUES(null, '" . $code ."', '" . $settings . "', 0, 1);");
	}
}

function ihc_update_coupon($post_data){
	/*
	 * @param post_data (array)
	 * @return none
	 */
	global $wpdb;
	$id = $post_data['id'];
	unset($post_data['id']);
	$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix ."ihc_coupons WHERE id='" . $id . "';");
	if ($data){
		$code = $post_data['code'];
		unset($post_data['code']);
		unset($post_data['id']);
		$settings = serialize($post_data);
		$wpdb->query("UPDATE " . $wpdb->prefix ."ihc_coupons
						SET code='" . $code . "', settings='" . $settings . "'
						WHERE id='".$id."';
				");
	}
}

function ihc_delete_coupon($id){
	/*
	 * @param id (int)
	 * @return none
	 */
	global $wpdb;
	$exists = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."ihc_coupons WHERE id='" . $id . "';");
	if ($exists){
		$wpdb->query("DELETE FROM ".$wpdb->prefix."ihc_coupons WHERE id='" . $id . "';");
	}
}

function ihc_submit_coupon($code=''){
	/*
	 * @param string
	 * @return boolean
	 */
	global $wpdb;
	//check if this code already exists
	$data = $wpdb->get_row("SELECT submited_coupons_count FROM " . $wpdb->prefix ."ihc_coupons WHERE code='" . $code . "';");
	if (isset($data->submited_coupons_count)){
		$submited_coupons_count = (int)$data->submited_coupons_count;
		$submited_coupons_count++;
		$wpdb->query("UPDATE " . $wpdb->prefix ."ihc_coupons
				SET submited_coupons_count='" . $submited_coupons_count . "'
				WHERE code='" . $code . "';
				");
		return TRUE;
	}
	return FALSE;
}

function ihc_get_coupon_by_code($code=''){
	/*
	 * @param string
	 * @return array
	 */
	$return_data = array();
	if ($code){
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ihc_coupons
				WHERE code='" . $code . "';");
		if ($data){
			$return_data = unserialize($data->settings);
			$return_data['code'] = $data->code;
			$return_data['submited_coupons_count'] = $data->submited_coupons_count;			
		}
	}
	return $return_data;
}

function ihc_get_all_coupons(){
	/*
	 * @param none
	 * @return array
	 */
	$return_data = array();
	global $wpdb;
	$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ihc_coupons;");
	if ($data){
		foreach ($data as $obj){
			$return_data[$obj->id]['code'] = $obj->code;
			$return_data[$obj->id]['settings'] = unserialize($obj->settings);
			$return_data[$obj->id]['submited_coupons_count'] = $obj->submited_coupons_count;
		}
	}
	return $return_data;
}

function ihc_get_coupon_by_id($id=0){
	/*
	 * @param string
	 * @return array
	 */
	$arr = array();
	if ($id){
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ihc_coupons
				WHERE id='" . $id . "';");
		$arr = unserialize($data->settings);
		$arr['code'] = $data->code;
	} else {
		$arr = array(
						"code" => "",
						"discount_type" => "price",
						"discount_value" => '',
						"period_type" => "date_range",
						"repeat" => "",
						"target_level" => "",
						"reccuring" => "",
						"start_time" => '',
						"end_time" => '',
						"box_color" => ihc_generate_color_hex()
					);
	}
	return $arr;	
}

function ihc_check_coupon($coupon='', $level_id=-1){
	/*
	 * @param coupon string, level id int
	 * @return array
	 */
	$empty = array();
	if (!$coupon || $level_id==-1){
		return $empty;
	}
	$coupon_data = ihc_get_coupon_by_code($coupon);
	if ($coupon_data){
		if (!empty($coupon_data['repeat']) && ($coupon_data['repeat']<=$coupon_data['submited_coupons_count'])){
			//out of repeat number
			return $empty;
		}
		if ($coupon_data['period_type']=='date_range' && !empty($coupon_data['start_time']) && !empty($coupon_data['end_time'])){
			//we must check the time
			$start_time = strtotime($coupon_data['start_time']);
			$end_time = strtotime($coupon_data['end_time']);
			$current_time = time();
			if ($start_time>$current_time){
				//not begin coupon time
				return $empty;
			}
			if ($current_time>$end_time){
				//out of date
				return $empty;
			}
		}
		if ($coupon_data['target_level']>-1){
			if ($coupon_data['target_level']!=$level_id){
				//it's not the target level
				return $empty;
			}
		}
		return array(	"discount_type" => $coupon_data['discount_type'],
						"discount_value" => $coupon_data['discount_value'],
						"reccuring" => $coupon_data['reccuring'],
						"code" => $coupon,
					);
	}
	return $empty;
}

function ihc_coupon_return_price_after_decrease($price=0, $coupon_data=array(), $update_coupon_count=TRUE){
	/*
	 * @param price int, coupon data array, update coupon count bool
	 * @return price int
	 */
	if ($price && $coupon_data){
		if ($coupon_data['discount_type']=='percentage'){
			$price = $price - ($price*$coupon_data['discount_value']/100);
		} else {
			$price = $price - $coupon_data['discount_value'];
		}
		$price = round($price, 2);
		if ($update_coupon_count){
			//lets update the coupon count in db
			ihc_submit_coupon($coupon_data['code']);			
		}
	}
	return $price;
}

function ihc_dont_pay_after_discount($level_id, $coupon, $level_arr, $update_coupon_count=FALSE){
	/*
	 * if the price after discount if 0 will return TRUE
	 * @param level_id - int, coupon - string, level_arr - array, update_coupon_count - array
	 * @return boolean 
	 */
	if (!empty($coupon)){
		if (isset($level_arr['access_type']) && $level_arr['access_type']!='regular_period'){
			//not reccurence
			$coupon_data = ihc_check_coupon($coupon, $level_id);
			$level_arr['price'] = ihc_coupon_return_price_after_decrease($level_arr['price'], $coupon_data, FALSE);
			if ($level_arr['price']==0){
				if ($update_coupon_count){
					//update coupon count
					ihc_submit_coupon($coupon);					
				}
				return TRUE;
			}
		}		
	}
	return FALSE;
}

function ihc_get_redirect_link_by_label($name=''){
	/*
	 * @param string
	 * @return string
	 */
	$data = get_option("ihc_custom_redirect_links_array");
	if (isset($data[$name])){
		return $data[$name];
	}
	return '';
}

function ihc_run_opt_in($email=''){
	/*
	 * @param string
	 * @return none
	 */
	$target_opt_in = get_option('ihc_register_opt-in-type');
	if ($target_opt_in && $email){
		if (!class_exists('IhcMailServices')){
			require_once IHC_PATH . 'classes/IhcMailServices.class.php';
		}
		$indeed_mail = new IhcMailServices();
		$indeed_mail->dir_path = IHC_PATH . 'classes';
		switch ($target_opt_in){
			case 'aweber':
				$awListOption = get_option('ihc_aweber_list');
				if ($awListOption){
					$aw_list = str_replace('awlist', '', $awListOption);
					$consumer_key = get_option( 'aweber_consumer_key' );
					$consumer_secret = get_option( 'aweber_consumer_secret' );
					$access_key = get_option( 'aweber_acces_key' );
					$access_secret = get_option( 'aweber_acces_secret' );
					if ($consumer_key && $consumer_secret && $access_key && $access_secret){
						$indeed_mail->indeed_aWebberSubscribe( $consumer_key, $consumer_secret, $access_key, $access_secret, $aw_list, $email );
					}
				}
				break;
	
			case 'email_list':
				$email_list = get_option('ihc_email_list');
				$email_list .= $email . ',';
				update_option('ihc_email_list', $email_list);
				break;
	
			case 'mailchimp':
				$mailchimp_api = get_option( 'ihc_mailchimp_api' );
				$mailchimp_id_list = get_option( 'ihc_mailchimp_id_list' );
				if ($mailchimp_api && $mailchimp_id_list){
					$indeed_mail->indeed_mailChimp( $mailchimp_api, $mailchimp_id_list, $email );
				}
				break;
	
			case 'get_response':
				$api_key = get_option('ihc_getResponse_api_key');
				$token = get_option('ihc_getResponse_token');
				if ($api_key && $token){
					$indeed_mail->indeed_getResponse( $api_key, $token, $email );
				}
				break;
	
			case 'campaign_monitor':
				$listId = get_option('ihc_cm_list_id');
				$apiID = get_option('ihc_cm_api_key');
				if ($listId && $apiID){
					$indeed_mail->indeed_campaignMonitor( $listId, $apiID, $email );
				}
				break;
	
			case 'icontact':
				$appId = get_option('ihc_icontact_appid');
				$apiPass = get_option('ihc_icontact_pass');
				$apiUser = get_option('ihc_icontact_user');
				$listId = get_option('ihc_icontact_list_id');
				if ($appId && $apiPass && $apiUser && $listId){
					$indeed_mail->indeed_iContact( $apiUser, $appId, $apiPass, $listId, $email );
				}
				break;
	
			case 'constant_contact':
				$apiUser = get_option('ihc_cc_user');
				$apiPass = get_option('ihc_cc_pass');
				$listId = get_option('ihc_cc_list');
				if ($apiUser && $apiPass && $listId){
					$indeed_mail->indeed_constantContact($apiUser, $apiPass, $listId, $email);
				}
				break;
	
			case 'wysija':
				$listID = get_option('ihc_wysija_list_id');
				if ($listID){
					$indeed_mail->indeed_wysija_subscribe( $listID, $email );
				}
				break;
	
			case 'mymail':
				$listID = get_option('ihc_mymail_list_id');
				if ($listID){
					$indeed_mail->indeed_myMailSubscribe( $listID, $email );
				}
				break;
	
			case 'madmimi':
				$username = get_option('ihc_madmimi_username');
				$api_key =  get_option('ihc_madmimi_apikey');
				$listName = get_option('ihc_madmimi_listname');
				if ($username && $api_key && $listName){
					$indeed_mail->indeed_madMimi($username, $api_key, $listName, $email);
				}
				break;
		}
	}
}

function ihc_get_custom_constant_fields(){
	/*
	 * @param none
	 * @return array
	 */
	$data = get_option('ihc_user_fields');
	foreach ($data as $arr){
		$fields["{CUSTOM_FIELD_" . $arr['name'] ."}"] = $arr['name'];
	}
	$diff = array('ihc_social_media', 'ihc_coupon', 'recaptcha', 'tos', 'pass2', 'pass1', 'user_login', 'user_email', 'confirm_email', 'first_name', 'last_name', 'ihc_avatar');
	$fields = array_diff($fields, $diff);
	return $fields;
}

function ihc_update_stripe_subscriptions(){
	/*
	 * Update Stripe Transactions ID, run this just once on update plugin.
	 * @param none
	 * @return none
	 */
	global $wpdb;
	$data = $wpdb->get_results("SELECT id, txn_id, payment_data FROM " . $wpdb->prefix . "indeed_members_payments
									WHERE txn_id LIKE 'ch_%';");
	if (count($data)){

		//loading stripe libs
		require_once IHC_PATH . 'classes/stripe/init.php';
		$secret_key = get_option('ihc_stripe_secret_key');
		\Stripe\Stripe::setApiKey($secret_key);

		foreach ($data as $obj){
			$payment_data = json_decode($obj->payment_data);
			if (!empty($payment_data->customer)){
				$replace_txn_id = $payment_data->customer;
			} else {
				$stripe_obj = \Stripe\Charge::retrieve($obj->txn_id);
				if (!empty($stripe_obj->customer)){
					$replace_txn_id = $stripe_obj->customer;
				}
				unset($stripe_obj);
			}
			if (!empty($replace_txn_id)){
				$wpdb->query("UPDATE " . $wpdb->prefix . "indeed_members_payments
								SET txn_id='" . $replace_txn_id . "'
								WHERE id= '" . $obj->id . "';
						");
				unset($replace_txn_id);
			}
		}//end foreach
	}
}

function ihc_get_active_payments_services($only_keys=FALSE){
	/*
	 * @param none
	 * @return array
	 */
	$arr = array();
	if (!function_exists('ihc_check_payment_status')){
		require_once IHC_PATH . 'admin/includes/functions.php';
	}
	$gateways = array('nextpay' => 'Nextpay',
					  'bank_transfer' => 'Bank Transfer');
	
	$gateways_without_labels = array();
	foreach ($gateways as $key=>$value){
		$order = get_option('ihc_' . $key . '_select_order');
		if ($order===FALSE){
			$order = array_search($key, array_keys($gateways));
		}
		while (!empty($gateways_without_labels[$order])){
			$order = $order+1;
		}
		$gateways_without_labels[$order] = $key;
	}
	ksort($gateways_without_labels);
	
	foreach ($gateways_without_labels as $k){
		$data = ihc_check_payment_status($k);
		if ($data['status'] && $data['settings']=='Completed'){
			if ($only_keys){
				$arr[] = $k;
			} else {
				$arr[$k] = $gateways[$k];
			}
		}
	}
	return $arr;
}

function ihc_is_level_reccuring($lid=-1){
	/*
	 * @param int
	 * @return bool
	 */
	if ($lid>-1){
		$level_data = ihc_get_level_by_id($lid);
		if (!empty($level_data['access_type']) && $level_data['access_type']=='regular_period'){
			return TRUE;
		}
	}
	return FALSE;
}

function ihc_print_payment_select($default_payment='', $field_data = array(), $payments_available, $is_reccurence=0, $required_field=FALSE){
	/*
	 * @param string, array, array, int, bool
	 * @return string
	 */
	
	$str = '';
	if (empty($field_data['theme'])){
		$field_data['theme'] = 'ihc-select-payment-theme-1';
	}
	$css_class = $field_data['theme'];
	$str .= '<div class="iump-form-line-register ' . $css_class . ' ' . @$field_data['class'] . '">';
	$str .= '<label class="iump-labels-register">';
	if ($required_field){
		$str .= '<span style="color: red;">*</span>';
	}
	if (!empty($field_data['label'])){
		$str .= ihc_correct_text($field_data['label']);
	} else {
		$str .= __('Select Payment Method', 'ihc');
	}
	$str .= '</label>';
	
	if ($field_data['theme']=='ihc-select-payment-theme-3') {
		$str .= '<select onChange="ihc_payment_gateway_update(this.value, ' . $is_reccurence . ');">';
	}
	
	foreach ($payments_available as $k => $v){

		$onclick = "ihc_payment_gateway_update('" . $k . "', " . $is_reccurence . ");";
		
		$label = get_option('ihc_' . $k . '_label');
		if (empty($label)){
			$label = $v;
		}
		
		if ($field_data['theme']=='ihc-select-payment-theme-1'){
			$selected = ($default_payment==$k) ? 'checked' : '';
			$str .= '<div class="iump-form-paybox"><input type="radio" name="ihc_payment_gateway_radio" value="' . $k . '" onClick="' . $onclick . '" ' . $selected . ' />' . ihc_correct_text($label) . '</div>';			
		} else if ($field_data['theme']=='ihc-select-payment-theme-2'){
			$onclick = "ihc_payment_select_icon('".$k."');" . $onclick;
			$class = ($default_payment==$k) ? 'ihc-payment-select-img-selected' : '';
			$str .= '<div class="iump-form-paybox" onClick="' . $onclick . '" class="ihc-payment-icon-wrap">';
			$str .= '<img src="' . IHC_URL . 'assets/images/'.$k.'.png" class="ihc-payment-icon ' . $class . '" id="ihc_payment_icon_' . $k . '"/>';
			$str .= '</div>';			
		} else if ($field_data['theme']=='ihc-select-payment-theme-3'){
			$selected = ($default_payment==$k) ? 'selected' : '';
			$str .= '<option value="' . $k . '" ' . $selected . '>' . ihc_correct_text($label) . '</option>';
		}
	}
	
	if ($field_data['theme']=='ihc-select-payment-theme-3') {
		$str .= '</select>';
	}
	if (!empty($field_data['sublabel'])){
		$str .= '<label class="iump-form-sublabel">' . ihc_correct_text($field_data['sublabel']) . '</label>';
	}
	$str .= '</div>';
	return $str;
}

function ihc_check_payment_available($type=''){
	/*
	 * check if a payment service it's enabled and has the required keys set
	 * @param string - type of payment
	 * @return bool
	 */
	if ($type){
		$payment_metas = ihc_return_meta_arr('payment_' . $type);
		switch ($type){
			case 'nextpay':
				if (!empty($payment_metas['ihc_nextpay_key']) && !empty($payment_metas['ihc_nextpay_status'])){
					return TRUE;
				}
				break;
			case 'authorize':
				if (!empty($payment_metas['ihc_authorize_login_id']) && !empty($payment_metas['ihc_authorize_transaction_key']) && !empty($payment_metas['ihc_authorize_status'])){
					return TRUE;
				}
				break;
			case 'bank_transfer':
				if (!empty($payment_metas['ihc_bank_transfer_status']) && !empty($payment_metas['ihc_bank_transfer_message'])){
					return TRUE;
				}
				break;
		}
	}
	return FALSE;
}

function ihc_switch_role_for_user($uid=0){
	/*
	 * Switch User Role when Complete a Payment.
	 * @param int
	 * @return none
	 */
	$do_switch = get_option('ihc_automatically_switch_role');
	if ($do_switch && $uid){
		$data = get_userdata($uid);
		if ($data && isset($data->roles) && isset($data->roles[0]) && $data->roles[0]=='pending_user'){
			$role = get_option('ihc_automatically_new_role');
			if (empty($role)){
				$role = 'subscriber';
			}
			$arr['role'] = $role;
			$arr['ID'] = $uid;
			wp_update_user($arr);
		}
	}
}

function ihc_get_currencies_list($return='all'){
	/*
	 * @param string : all, basic, custom
	 * @return array
	 */
	$basic = array(
			'IRR' => 'Iranian Rial',
            'IRT' => 'Iranian Toman',
	);
	$data = get_option('ihc_currencies_list');
	if ($return=='all'){
		if ($data!==FALSE && is_array($data)){
			return $basic+$data;
		}
		return $basic;
	} else if ($return=='basic'){
		return $basic;
	} else {
		return $data;
	}
}

function ihc_get_user_type(){
	/*
	 * @param none
	 * @return string
	 */
	$type = 'unreg';
	if (is_user_logged_in()){
		if (current_user_can('administrator')) return 'admin';
		//pending user
		global $current_user;
		if ($current_user){
			if (isset($current_user->roles[0]) && $current_user->roles[0]=='pending_user'){
				$type = 'pending';
			}else{
				$type = 'reg';
				$current_user = wp_get_current_user();
				$u_capability = get_user_meta($current_user->ID, 'ihc_user_levels', true);
				if ($u_capability!==FALSE && $u_capability!=''){
					$type = $u_capability;
				}
			}
		}
	}
	return $type;
}

function ihc_required_conditional_field_test($name='', $match_string=''){
	/*
	 * @param string, string
	 * @return string with error if it's case, empty string if it's ok
	 */
	$fields_meta = ihc_get_user_reg_fields();
	$key = ihc_array_value_exists($fields_meta, $name, 'name');
	if ($key && $fields_meta[$key]['type']=='conditional_text' && !empty($fields_meta[$key]['conditional_text'])){
		if ($fields_meta[$key]['conditional_text']!=$match_string){
			return ihc_correct_text($fields_meta[$key]['error_message']);
		}
	}
	return '';
}

function ihc_get_public_register_fields($exclude_field=''){
	/*
	 * used only in register.php admin section, 
	 * @param string
	 * @return array
	 */
	$return = array();
	$fields_meta = ihc_get_user_reg_fields();
	foreach ($fields_meta as $arr){
		if ($arr['display_public_reg']>0 && !in_array($arr['type'], array('payment_select', 'social_media', 'upload_image', 'plain_text', 'file', 'capcha')) && $arr['name']!='tos'){
			if ($exclude_field && $exclude_field==$arr['name']){
				continue;
			}
			$return[$arr['name']] = $arr['name'];
		}
	}
	return $return;
}

function ihc_check_field_is_in_logic_conditional($field_name=''){
	/*
	 * check if this field it's mentionated in other fields conditions
	 * @param name of field
	 * @return boolean
	 */
	$fields_meta = ihc_get_user_reg_fields();
	$key = ihc_array_value_exists($fields_meta, $field_name, 'name');
	if ($key){
		if (!empty($fields_meta[$key]['conditional_logic_corresp_field']) && $fields_meta[$key]['conditional_logic_corresp_field']!=-1){
			return TRUE;
		}
	}
	return FALSE;
}