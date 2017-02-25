<?php
//file_put_contents( "authorize_log.log", json_encode($_POST), FILE_APPEND | LOCK_EX );//debug
require_once '../../../wp-load.php';
require_once 'utilities.php';

//insert this request into debug payments table
if (get_option('ihc_debug_payments_db')){
	ihc_insert_debug_payment_log('authorize', $_POST);
}

$r_url = get_home_url();


if ( isset($_POST['x_MD5_Hash']) && isset($_POST['x_response_code'])  && !empty($_POST['x_cust_id']) && !empty($_POST['x_po_num']) ){
	$level_id = $_POST['x_po_num'];
	$user_id = $_POST['x_cust_id'];
	$level_data = ihc_get_level_by_id($level_id);//getting details about current level
	
	switch ($_POST['x_response_code']){
		case '1':
			ihc_update_user_level_expire($level_data, $level_id, $user_id);		
			ihc_switch_role_for_user($user_id);
			ihc_send_user_notifications($user_id, 'payment', $level_id);//send notification to user
			break;
		case '2':
		case '3':
			if (!function_exists('ihc_is_user_level_expired')){
				require_once IHC_PATH . 'public/functions.php';
			}
			$expired = ihc_is_user_level_expired($user_id, $level_id, FALSE, TRUE);
			if ($expired){
				//delete user - level relationship
				ihc_delete_user_level_relation($level_id, $user_id);
			}				
			break;
		case '4':	
			break;				
	}
	
	if (isset($_POST['x_trans_id'])){
		//record transation
		$_POST['x_currency_code']= get_option('ihc_currency');
		$_POST['item_name']= $level_data['name'];	
		$_POST['ihc_payment_type'] = 'authorize';
		ihc_insert_update_transaction($user_id, $_POST['x_trans_id'], $_POST);
	}
} else if (isset($_POST['x_MD5_Hash']) && isset($_POST['x_subscription_id']) && isset($_POST['x_response_code'])){
	//ARB SECTION
	global $wpdb;
	$data = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'indeed_members_payments WHERE txn_id="' . $_POST['x_subscription_id'] . '";');
	if ( isset($data->u_id) && isset($data->payment_data) ){
		$payment_data = json_decode($data->payment_data, TRUE);		
		$level_data = ihc_get_level_by_id($payment_data['level']);//getting details about current level
		
		switch ($_POST['x_response_code']){
			case '1':
				ihc_update_user_level_expire($level_data, $payment_data['level'], $data->u_id);	
				ihc_switch_role_for_user($data->u_id);
				ihc_send_user_notifications($data->u_id, 'payment', $payment_data['level']);//send notification to user
				break;
			case '2':
			case '3':
				if (!function_exists('ihc_is_user_level_expired')){
					require_once IHC_PATH . 'public/functions.php';
				}
				$expired = ihc_is_user_level_expired($data->u_id, $payment_data['level'], FALSE, TRUE);
				if ($expired){
					//delete user - level relationship
					ihc_delete_user_level_relation($payment_data['level'], $data->u_id);
				}
				break;
			case '4':
				break;
		}
		if (!empty($payment_data)){
			$insert_data = $payment_data;
			$insert_data['code'] = $_POST['x_response_code'];
			if ($insert_data['code']==1){
				$insert_data['message'] = 'success';
			} else {
				$insert_data['message'] = $_POST['x_response_reason_text'];
			}
			$insert_data = array_merge($insert_data, $_POST);
			
			//set payment type
			$insert_data['ihc_payment_type'] = 'authorize';
			
			//record transation
			$_POST['x_currency_code']= get_option('ihc_currency');
			$_POST['item_name']= $level_data['name'];
			ihc_insert_update_transaction($data->u_id, $_POST['x_subscription_id'], $insert_data);			
		}	
	}
}
?>
<html>
 <head>
 <script type="text/javascript" charset"utf-8">
 window.location='<?php echo $r_url; ?>';
 </script>
 <noscript>
 <meta http-equiv="refresh" content="1;url=<?php echo $r_url; ?>">
 </noscript>
 </head>
 <body></body>
</html>


