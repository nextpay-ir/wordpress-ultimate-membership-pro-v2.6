<?php 
/////////LOGIN
function ihc_login($url){	
	if (isset($_REQUEST['log']) && $_REQUEST['log']!='' && isset($_REQUEST['pwd']) && $_REQUEST['pwd']!=''){
		$arr['user_login'] = sanitize_user($_REQUEST['log']);
		$arr['user_password'] = $_REQUEST['pwd'];
		$arr['remember'] = ( isset( $_REQUEST['rememberme'] ) == 'forever' ) ? true : false;
		$user = wp_signon( $arr, false );
		if (!is_wp_error($user)){
			
			//============== Check E-mail verification status
			ihc_check_email_verification_status($user->ID, $url);
			
			if (isset($user->roles[0]) && $user->roles[0]=='pending_user'){
				//=================== PENDING USER				
				wp_clear_auth_cookie();//logout
				do_action( 'wp_logout' );
				nocache_headers();
				$url = add_query_arg( array('ihc_login_pending' => 'true'), $url );
				wp_redirect( $url );
				exit();
			} else {				
				//================== LOGIN SUCCESS
				$url = add_query_arg( array( 'ihc_success_login' => 'true' ), $url );
				
				//LOCKER REDIRECT
				if (!empty($_REQUEST['locker'])){
					wp_redirect($url);
					exit();
				}
				//LOCKER REDIRECT
				
				$redirect_p_id = get_option('ihc_general_login_redirect');
				if ($redirect_p_id && $redirect_p_id!=-1){
					$redirect_url = get_permalink($redirect_p_id);
					if (!$redirect_url){
						$redirect_url = ihc_get_redirect_link_by_label($redirect_p_id);
					}
					if ($redirect_url){
						wp_redirect( $redirect_url );
						exit();						
					}
				}			
				wp_redirect( $url );
				exit();
			}
		}
	}
	
	//===================== LOGIN FAILD
	$url = add_query_arg( array('ihc_login_fail'=>'true'), $url );
	wp_redirect( $url );
	exit();
}

function ihc_login_social($login_data){
	/*
	 * @param array
	 * @return none
	 */
	$uid = -1;	
	
	$meta_key = "ihc_" . $login_data['sm_type'];
	global $wpdb;
	$data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "usermeta
								WHERE meta_key='" . $meta_key . "'
								AND meta_value='" . $login_data['sm_uid'] . "';");
	if (isset($data) && isset($data->user_id)){
		$uid = $data->user_id;
	}
	if ($uid>-1){		
		$user_data = get_userdata($uid);		
		if (isset($user_data->roles['0']) && $user_data->roles['0']=='pending_user'){
			//=================== PENDING USER	
			if (isset($login_data['url'])){
				$url = add_query_arg( array('ihc_login_pending' => 'true'), $login_data['url'] );
				wp_redirect( $url );
				exit();
			}	
			return;
		}
		
		
		//======================== EMAIL VERIFICATION STATUS
		ihc_check_email_verification_status($uid, @$login_data['url']);
		
		
		//======================== LOGIN SUCCESS
		wp_set_auth_cookie($uid);//we set the user
		

		/********** REDIRECT ************/
		
		//LOCKER REDIRECT
		if (!empty($login_data['is_locker']) && !empty($login_data['url'])){
			wp_redirect( $login_data['url'] );
			exit();
		}
		//LOCKER REDIRECT
		
		$redirect = get_option('ihc_general_login_redirect');

		if ($redirect && $redirect!=-1){
			$redirect_url = get_permalink($redirect);
			if (!$redirect_url){
				//custom redirect url
				$redirect_url = ihc_get_redirect_link_by_label($redirect);
			}
			if ($redirect_url){
				wp_redirect($redirect_url);
				exit();				
			}
		} else if (isset($login_data['url'])){
			$url = $login_data['url'];
			$url = add_query_arg( array( 'ihc_success_login' => 'true' ), $url );
		} else {
			$url = home_url();
		}
		
		wp_redirect( $url );
		exit();
	}
	
	//======================== LOGIN FAILD
	if (isset($login_data['url'])){
		$url = add_query_arg( array('ihc_login_fail' => 'true'), $login_data['url'] );
		wp_redirect( $url );
		exit();
	}	
}

function ihc_check_email_verification_status($uid, $redirect_url=''){
	/*
	 * logout and redirect if verification status is -1
	 * @param uid - int, redirect_url - string
	 * @return 
	 */
	$email_verification = get_user_meta($uid, 'ihc_verification_status', TRUE);
	if ($email_verification==-1){
		wp_clear_auth_cookie();//logout
		do_action( 'wp_logout' );
		nocache_headers();
		if (!$redirect_url){
			$redirect_url = home_url();
		}
		$redirect_url = add_query_arg( array( 'ihc_pending_email' => 'true' ), $redirect_url );
		wp_redirect( $redirect_url );
		exit();		
	}	
}