<?php 
require_once '../../../wp-load.php';

if (!empty($_GET['uid']) && !empty($_GET['ihc_code'])){
	require_once 'utilities.php';
	$time_before_expire = get_option('ihc_double_email_expire_time');
	$user_data = get_userdata($_GET['uid']);
	$error = FALSE;

	//checking expire time if it's case
	if (!empty($user_data)){	
		if ($time_before_expire!=-1){
			$expire_time = strtotime($user_data->data->user_registered) + floatval($time_before_expire);
		}			
	} else {
		$error = TRUE;
	}
	if (!$error && $time_before_expire!=-1){
		$current_time = time();
		if ($current_time>$expire_time){
			$error = TRUE;
		}
	}
	
	//activate if it's case
	if (!$error){
		$hash = get_user_meta($_GET['uid'], 'ihc_activation_code', TRUE);
		if ($_GET['ihc_code']==$hash){
			//success
			delete_user_option($_GET['uid'], 'ihc_activation_code');//remove code
			update_user_meta($_GET['uid'], 'ihc_verification_status', 1);
			//opt in
			if (!empty($user_data->data->user_email)){
				ihc_run_opt_in($user_data->data->user_email);
			}		
			//send notification
			ihc_send_user_notifications($_GET['uid'], 'email_check_success');
		} else {
			$error = TRUE;
		}	
	}
	
	//redirect
	if ($error){
		//error redirect
		$redirect = get_option('ihc_double_email_redirect_error');
	} else {
		//success redirect
		$redirect = get_option('ihc_double_email_redirect_success');
	}
}
if (!empty($redirect) || $redirect!=-1){
	$redirect_url = get_permalink($redirect);
	if (!$redirect_url){
		// maybe custom redirect url
		$redirect_url = ihc_get_redirect_link_by_label($redirect);
	}
}

if (empty($redirect_url)){
	//go home
	$redirect_url = get_home_url();
}

wp_redirect($redirect_url);
exit;