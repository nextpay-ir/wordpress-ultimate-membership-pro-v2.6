<?php 
	require '../../../../wp-load.php';
	
	$loginID = get_option('ihc_authorize_login_id');
	$transactionKey = get_option('ihc_authorize_transaction_key');
	$currency = get_option('ihc_currency');
	$levels = get_option('ihc_levels');
	$sandbox = get_option('ihc_authorize_sandbox');

	$r_url = get_home_url();

	if ($sandbox){
		$url = 'https://test.authorize.net/gateway/transact.dll';
	} else{
		$url = 'https://secure.authorize.net/gateway/transact.dll';
	}
	$relay_url = str_replace('public/', 'authorize_response.php', plugin_dir_url(__FILE__));
		
	$err = false;	
	//LEVEL
	if (isset($levels[$_GET['lid']])){
		$level_arr = $levels[$_GET['lid']];
		if ($level_arr['payment_type']=='free' || $level_arr['price']=='') $err = true;
	} else {
		$err = true;
	}
	// USER ID
	if (isset($_GET['uid']) && $_GET['uid']){
		$uid = $_GET['uid'];
	} else {
		$uid = get_current_user_id();
	}
	if (!$uid){
		$err = true;	
	}
		
	if ($err){
		////if level it's not available for some reason, go back to prev page
		header( 'location:'. $r_url );
		exit();
	}
	
	$reccurrence = FALSE;
	if (isset($level_arr['access_type']) && $level_arr['access_type']=='regular_period'){
		$reccurrence = TRUE;
	}
	if ($reccurrence){
		
		
	} else {
		if (!empty($_GET['ihc_coupon'])){
			$coupon_data = ihc_check_coupon($_GET['ihc_coupon'], $_GET['lid']);
			$level_arr['price'] = ihc_coupon_return_price_after_decrease($level_arr['price'], $coupon_data);
		}
		$amount = urlencode($level_arr['price']);
		$description 	= $level_arr['label'];
		$label 			= $level_arr['label'];
		// an invoice is generated using the date and time
		$invoice	= date('YmdHis');
		// a sequence number is randomly generated
		$sequence	= rand(1, 1000);
		// a timestamp is generated
		$timeStamp	= time();
		$testMode		= "false";
		
		if( phpversion() >= '5.1.2' )
			{ $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^" . $currency, $transactionKey); }
		else 
			{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^". $currency, $transactionKey)); }
		$q = '?';
		$q .= 'x_login=' . $loginID . '&';
		$q .= 'x_amount=' . $amount . '&';
		$q .= 'x_currency_code=' . $currency . '&';
		$q .= 'x_type="AUTH_ONLY"&';
		$q .= 'x_description=' . $description . '&';
		$q .= 'x_invoice_num=' . $invoice . '&';
		$q .= 'x_fp_sequence=' . $sequence . '&';
		$q .= 'x_fp_timestamp=' . $timeStamp . '&';
		$q .= 'x_fp_hash=' . $fingerprint . '&';
		$q .= 'x_relay_response="TRUE"&';
		$q .= 'x_relay_url=' . $relay_url . '&';
		$q .= 'x_cust_id=' . $uid . '&';
		$q .= 'x_po_num=' . $_GET['lid'] . '&';
		$q .= 'x_test_request=' . $testMode . '&';
		$q .= 'x_show_form=PAYMENT_FORM';
		
		header( 'location:' . $url . $q );
		exit();
	
	}
	
header( 'location:'. $r_url );
exit();
	
	
	