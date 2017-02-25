<?php
/*
Plugin Name: Indeed Ultimate Membership Pro
Plugin URI: http://www.wpindeed.com/
Description: The most complete and easy to use Membership Plugin, ready to allow or restrict your content, Page for certain Users.
Version: 2.6
Author: indeed
Author URI: http://www.wpindeed.com
*/
///setting the paths
if (!defined('IHC_PATH')){
	define('IHC_PATH', plugin_dir_path(__FILE__));
}
if (!defined('IHC_URL')){
	define('IHC_URL', plugin_dir_url(__FILE__));
}
if (!defined('IHC_PROTOCOL')){
	if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
		define('IHC_PROTOCOL', 'https://');
	}else define('IHC_PROTOCOL', 'http://');	
}

//LANGUAGES
add_action('init', 'ihc_load_language');
function ihc_load_language(){
	load_plugin_textdomain( 'ihc', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}

require_once IHC_PATH . 'utilities.php';
if (is_admin()){
	//go to admin  
	require_once IHC_PATH . 'admin/main.php';
} else {
	//go to public
	require_once IHC_PATH . 'public/main.php';
}


//on activating the plugin
function ihc_initiate_plugin() {
	//and new role to users
	add_role( 'pending_user', 'Pending', array( 'read' => false, 'level_0' => true ) );
	
	//save the metas to db
	$values = array('payment', 'payment_nextpay',
                    'payment_bank_transfer', 'login', 'login-messages', 'general-defaults',
					'general-captcha', 'general-subscription', 'general-msg', 'register', 'register-msg',
					'register-custom-fields', 'opt_in', 'notifications', 'extra_settings', 'account_page',
					'social_media', 'double_email_verification');
	
	foreach ($values as $value){
		ihc_return_meta_arr($value);
	}
	
	global $wpdb;
	//create tables
	////////// indeed_members_payments
	$table_name = $wpdb->prefix . 'indeed_members_payments';
	if ($wpdb->get_var( "show tables like '$table_name'" ) != $table_name){
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql = "CREATE TABLE " . $table_name . " (
					id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					txn_id VARCHAR(100) DEFAULT NULL,
					u_id int(9) DEFAULT NULL,
					payment_data text DEFAULT NULL,
					history TEXT,
					paydate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
			);";
		dbDelta($sql);
	}
	
	//ihc_notifications
	$table_name = $wpdb->prefix . "ihc_notifications";
	if ($wpdb->get_var( "show tables like '$table_name'" ) != $table_name){
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql = "CREATE TABLE " . $table_name . " (
					id int(11) NOT NULL AUTO_INCREMENT,
					notification_type VARCHAR(200),
					level_id VARCHAR(200),
					subject TEXT,
					message TEXT,
					status TINYINT(1),
					PRIMARY KEY (`id`)
				);";
		dbDelta($sql);
	}
	
}
register_activation_hook( __FILE__, 'ihc_initiate_plugin' );

function ihc_send_notification_before_after_expire(){
	global $wpdb;
	$before_expire = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "ihc_notifications` WHERE `notification_type`='before_expire' ORDER BY id DESC LIMIT 1;");
	if ($before_expire){
		//we have notification before expire
		$days = get_option("ihc_notification_before_time");
		if (!$days){
			$days = 5;
		}
		$time_diff = $days*24*60*60;
		$u_ids = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."ihc_user_levels` 
										WHERE 1=1 
										AND notification=0
										AND UNIX_TIMESTAMP(expire_time)<(UNIX_TIMESTAMP(NOW())+".$time_diff.")
									;");		
		if ($u_ids){
			foreach ($u_ids as $u_data){
				$sent = ihc_send_user_notifications($u_data->user_id, 'before_expire', $u_data->level_id);
				if ($sent){
					$wpdb->query("UPDATE `".$wpdb->prefix."ihc_user_levels` SET notification='1' WHERE `id`='".$u_data->id."'; ");
				}
			}
		}
	}
	$expire = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "ihc_notifications` WHERE `notification_type`='expire' ORDER BY id DESC LIMIT 1;");	
	if ($expire){
		$u_ids = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."ihc_user_levels`
										WHERE 1=1
										AND notification<>2
										AND DATE(expire_time)=DATE(NOW())
									;");
		if ($u_ids){
			foreach ($u_ids as $u_data){
				$sent = ihc_send_user_notifications($u_data->user_id, 'expire', $u_data->level_id);
				if ($sent){
					$wpdb->query("UPDATE `".$wpdb->prefix."ihc_user_levels` SET notification='2' WHERE `id`='".$u_data->id."'; ");
				}
			}
		}		
	}
}

add_action( 'ihc_notifications_job', 'ihc_send_notification_before_after_expire', 82 );


////downgrade level
function ihc_check_if_level_expire_downgrade(){
	/*
	 * main function for "add another level after expire current level"
	 * @param none
	 * @return none
	 */
	global $wpdb;
	$grace_period = get_option('ihc_grace_period');
	$q = "SELECT * FROM `" . $wpdb->prefix . "ihc_user_levels`
			WHERE 1=1
			AND DATE(expire_time)<=DATE(NOW())
			AND DATE(expire_time)>DATE('0000-00-00 00:00:00')";
	$u_ids = $wpdb->get_results($q);	
	if ($u_ids){
		foreach ($u_ids as $u_data){
			if ($grace_period){
				$expire_time_after_grace = strtotime($u_data->expire_time) + $grace_period * 24 * 60 * 60;
				if ($expire_time_after_grace>time()){
					continue;
				}
			}
			if (isset($u_data->level_id) && isset($u_data->user_id)){
				$added = ihc_downgrade_levels_when_expire($u_data->user_id, $u_data->level_id);
				if ($added){
					//ihc_delete_user_level_relation($u_data->level_id, $u_data->user_id);//remove the older level
				}
			}
		}
	}
}
add_action( 'ihc_check_level_downgrade', 'ihc_check_if_level_expire_downgrade', 83);

function ihc_run_check_verify_email_status(){
	/*
	 * Search for users that not verified their email address, and delete them if it's time.
	 * @param none
	 * @return none
	 */
	$time_limit = (int)get_option('ihc_double_email_delete_user_not_verified');
	if ($time_limit>-1){
		$time_limit = $time_limit * 24 * 60 * 60;
		global $wpdb;
		$data = $wpdb->get_results("SELECT user_id FROM " . $wpdb->prefix . "usermeta
										WHERE meta_key='ihc_verification_status'
										AND meta_value='-1';");
		if (!empty($data)){		
			foreach ($data as $k=>$v){
				if (!empty($v->user_id)){
					$time_data = $wpdb->get_row("SELECT user_registered FROM " . $wpdb->prefix . "users
							WHERE ID='" . $v->user_id . "';");
					if (!empty($time_data->user_registered)){
						if (strtotime($time_data->user_registered)+$time_limit>time()){
							//delete user
							wp_delete_user( $v->user_id );
							$wpdb->query("DELETE FROM " . $wpdb->prefix . "ihc_user_levels WHERE user_id='" . $v->user_id . "';");
							//send notification
							ihc_send_user_notifications($v->user_id, 'delete_account');
						}
					}
				}
			}
		}		
	}
}
add_action( 'ihc_check_verify_email_status', 'ihc_run_check_verify_email_status', 84);

//2checkout ajax ins
add_action('wp_ajax_ihc_twocheckout_ins', 'twocheckout_ins_ihc');
add_action('wp_ajax_nopriv_ihc_twocheckout_ins', 'twocheckout_ins_ihc');
function twocheckout_ins_ihc(){
	require_once IHC_PATH . "twocheckout_ins.php";
	exit;
}


//delete attachment ajax
add_action('wp_ajax_nopriv_ihc_delete_attachment_ajax_action', 'ihc_delete_attachment_ajax_action');
add_action('wp_ajax_ihc_delete_attachment_ajax_action', 'ihc_delete_attachment_ajax_action');
function ihc_delete_attachment_ajax_action(){
	if (!empty($_REQUEST['attachemnt_id'])){
		wp_delete_attachment( $_REQUEST['attachemnt_id'], TRUE );
	}
	if ($_REQUEST['user_id']!=-1 && isset($_REQUEST['field_name'])){
		update_user_meta($_REQUEST['user_id'], $_REQUEST['field_name'], '');
	}
	echo 1;
	die();
}

add_action("wp_ajax_nopriv_ihc_check_coupon_code_via_ajax", "ihc_check_coupon_code_via_ajax");
add_action('wp_ajax_ihc_check_coupon_code_via_ajax', 'ihc_check_coupon_code_via_ajax');
function ihc_check_coupon_code_via_ajax(){
	/*
	 * use this only for stripe
	 * @param none
	 * @return array or int 0
	 */
	if (!empty($_REQUEST['code']) && !empty($_REQUEST['lid'])){
		$coupon_data = ihc_check_coupon($_REQUEST['code'], $_REQUEST['lid']);
		if ($coupon_data){
			$level_data = ihc_get_level_by_id($_REQUEST['lid']);
			$reccurence = FALSE;
			if (!empty($level_data['access_type']) && $level_data['access_type']=='regular_period'){
				$reccurence = TRUE;
			}
			if ($level_data['price'] && $coupon_data && (!empty($coupon_data['reccuring']) || !$reccurence) ){
				if ($coupon_data['discount_type']=='percentage'){
					$price = $level_data['price'] - ($level_data['price']*$coupon_data['discount_value']/100);
				} else {
					$price = $level_data['price'] - $coupon_data['discount_value'];
				}
				$price = $price * 100;
				$price = round($price, 2);
				echo json_encode(array('price'=>$price));
				die();
			}
		}
	}
	echo 0;
	die();
}

add_filter('send_password_change_email', 'ihc_update_passowrd_filter', 99, 2);
function ihc_update_passowrd_filter($return, $user_data){
	/*
	 * send custom e-mail notification when user change his password
	 * @param return - boolean, $user_data - array
	 * @return boolean
	 */
	if (isset($user_data['ID']) && $return){
		$sent_mail = ihc_send_user_notifications($user_data['ID'], 'change_password');
		if ($sent_mail){
			return FALSE;
		}
	}
	return $return;
}


add_action("wp_ajax_nopriv_ihc_check_reg_field_ajax", "ihc_check_reg_field_ajax");
add_action('wp_ajax_ihc_check_reg_field_ajax', 'ihc_check_reg_field_ajax');
function ihc_check_reg_field_ajax(){
	$register_msg = ihc_return_meta_arr('register-msg');
	if (isset($_REQUEST['type']) && isset($_REQUEST['value'])){		
		echo ihc_check_value_field($_REQUEST['type'], $_REQUEST['value'], $_REQUEST['second_value'], $register_msg);
	} else if (isset($_REQUEST['fields_obj'])){
		$arr = $_REQUEST['fields_obj'];
		foreach ($arr as $k=>$v){
			$return_arr[] = array( 'type' => $v['type'], 'value' => ihc_check_value_field($v['type'], $v['value'], $v['second_value'], $register_msg) );
		}
		echo json_encode($return_arr);
	}
	die();
}

function ihc_check_value_field($type='', $value='', $val2='', $register_msg=array()){
	if (isset($value) && $value!=''){
		switch ($type){
			case 'user_login':
				if (!validate_username($value)){
					$return = $register_msg['ihc_register_error_username_msg'];
				}
				if (username_exists($value)) {
					$return = $register_msg['ihc_register_username_taken_msg'];
				}
				break;
			case 'user_email':
				if (!is_email($value)) {
					$return = $register_msg['ihc_register_invalid_email_msg'];
				}
				if (email_exists($value)){
					$return = $register_msg['ihc_register_email_is_taken_msg'];
				}
				break;
			case 'confirm_email':
				if ($value==$val2){
					$return = 1;
				} else {
					$return = $register_msg['ihc_register_emails_not_match_msg'];
				}
				break;
			case 'pass1':
				$register_metas = ihc_return_meta_arr('register');
				if ($register_metas['ihc_register_pass_options']==2){
					//characters and digits
					if (!preg_match('/[a-z]/', $value)){
						$return = $register_msg['ihc_register_pass_letter_digits_msg'];
					}
					if (!preg_match('/[0-9]/', $value)){
						$return = $register_msg['ihc_register_pass_letter_digits_msg'];
					}
				} else if ($register_metas['ihc_register_pass_options']==3){
					//characters, digits and one Uppercase letter
					if (!preg_match('/[a-z]/', $value)){
						$return = $register_msg['ihc_register_pass_let_dig_up_let_msg'];
					}
					if (!preg_match('/[0-9]/', $value)){
						$return = $register_msg['ihc_register_pass_let_dig_up_let_msg'];
					}
					if (!preg_match('/[A-Z]/', $value)){
						$return = $register_msg['ihc_register_pass_let_dig_up_let_msg'];
					}
				}
				//check the length of password
				if($register_metas['ihc_register_pass_min_length']!=0){
					if (strlen($value)<$register_metas['ihc_register_pass_min_length']){
						$return = str_replace( '{X}', $register_metas['ihc_register_pass_min_length'], $register_msg['ihc_register_pass_min_char_msg'] );
					}
				}
				break;
			case 'pass2':
				if ($value==$val2){
					$return = 1;
				} else {
					$return = $register_msg['ihc_register_pass_not_match_msg'];
				}
				break;
			case 'tos':
				if ($value==1){
					$return = 1;
				} else {
					$return = $register_msg['ihc_register_err_tos'];
				}
				break;
			
			default:
				//required conditional field
				$check = ihc_required_conditional_field_test($type, $value);
				if ($check){
					$return = $check;
				} else {
					$return = 1;
				}
				break;
		}
		if (empty($return)){
			$return = 1;
		}
		return $return;
	} else {
		$check = ihc_required_conditional_field_test($type, $value);//Check for required conditional field
		if ($check){
			return $check;
		} else {
			return $register_msg['ihc_register_err_req_fields'];	
		}
	}	
}

add_action("wp_ajax_nopriv_ihc_check_logic_condition_value", "ihc_check_logic_condition_value");
add_action('wp_ajax_ihc_check_logic_condition_value', 'ihc_check_logic_condition_value');
function ihc_check_logic_condition_value(){
	/*
	 * @param none
	 * @return none (print 1 the test was passed, 0 otherwise)
	 */
	if (isset($_REQUEST['val']) && isset($_REQUEST['field'])){
		$fields_meta = ihc_get_user_reg_fields();
		$key = ihc_array_value_exists($fields_meta, $_REQUEST['field'], 'name');
		if ($key){
			if (isset($fields_meta[$key]['conditional_logic_corresp_field_value'])){
				if ($fields_meta[$key]['conditional_logic_cond_type']=='has'){
					//has value
					if ($fields_meta[$key]['conditional_logic_corresp_field_value']==$_REQUEST['val']){
						echo 1;
						die();						
					}					
				} else {
					//contain value
					if (strpos($_REQUEST['val'], $fields_meta[$key]['conditional_logic_corresp_field_value'])!==FALSE){
						echo 1;
						die();
					}
				}		
			}
		}
	}
	echo 0;
	die();
}