<?php 
function ihc_reset_pass(){
	add_filter( 'send_password_change_email', '__return_false', 1);
	global $ihc_reset_pass;
	$ihc_reset_pass = -1;
	$user = get_user_by( 'email', $_REQUEST['email_or_userlogin'] );
	if ($user){		
		$fields['ID'] = $user->data->ID;
		$email_addr = $_REQUEST['email_or_userlogin'];
	} else {
		//get user by user_login
		global $wpdb;
		$data = $wpdb->get_row("SELECT ID, user_email FROM " . $wpdb->prefix . "users WHERE `user_login`='" . $_REQUEST['email_or_userlogin'] . "';");
		if (isset($data->ID) && isset($data->user_email)){
			$fields['ID'] = $data->ID;
			$email_addr = $data->user_email;
		}
	}
	
	if (isset($email_addr) && isset($fields['ID'])){
		$new_pass = wp_generate_password(10, true);
		$fields['user_pass'] = $new_pass;
		$user_id = wp_update_user($fields);		
		if($user_id==$fields['ID']){
			$sent = ihc_send_user_notifications($user_id, 'reset_password', FALSE, array('{NEW_PASSWORD}'=>$new_pass));
			if (!$sent){
				$subject = __('Password reset on ', 'ihc') . get_option('blogname');
				$msg = __('Your new password it\'s: ', 'ihc') . $new_pass;
				wp_mail( $email_addr, $subject, $msg );				
			}
			$ihc_reset_pass = 1;			
		}		
	}	
}