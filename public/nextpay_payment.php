<?php 
	require '../../../../wp-load.php';
	
	$api_key = get_option('ihc_nextpay_key');
	$currency = get_option('ihc_currency');
	$levels = get_option('ihc_levels');
	$r_url = get_option('ihc_nextpay_return_page');
	
	if(!$r_url || $r_url==-1){
		$r_url = get_option('page_on_front');
	}
	$r_url = get_permalink($r_url);
	if (!$r_url){
		$r_url = get_home_url();
	}
		
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
		// if level it's not available for some reason, go back to prev page
		header( 'location:'. $r_url );
		exit();
	}
	
	$notify_url = str_replace('public/', 'nextpay_ipn.php?lid=' . $_GET['lid'] . '&uid=' . $uid , plugin_dir_url(__FILE__));
	
	$reccurrence = FALSE;
	if (isset($level_arr['access_type']) && $level_arr['access_type']=='regular_period'){
		$reccurrence = TRUE;
	}


	//coupons
	$coupon_data = array();
	if (!empty($_GET['ihc_coupon'])){
		$coupon_data = ihc_check_coupon($_GET['ihc_coupon'], $_GET['lid']);
	}

    if ($coupon_data){
        $level_arr['price'] = ihc_coupon_return_price_after_decrease($level_arr['price'], $coupon_data);
    }

    if ($currency == 'IRR')  {
        $level_arr['price'] = $level_arr['price']  / 10 ;
    }
	$options = array(
				'cache_wsdl' => 0,
				'encoding' => 'UTF-8',
				'trace' => 1,
				'stream_context' => stream_context_create(array(
							'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
							)
					))
			);

    $client = new SoapClient('https://api.nextpay.org/gateway/token.wsdl', $options);

    $order_id = md5(uniqid(rand(), true));
    $result = $client->TokenGenerator(
        array(
            'api_key' 	=> $api_key,
            'order_id'	=> date("His").rand(1234, 9632),
            'amount' 		=> $level_arr['price'],
            'callback_uri' 	=> $notify_url
        )
    );
    $result = $result->TokenGeneratorResult;

    $go = "https://api.nextpay.org/gateway/payment/" . $result->trans_id;

	header( 'location:' . $go );
	exit();
	
	