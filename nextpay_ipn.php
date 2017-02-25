<?php
require_once '../../../wp-load.php';
require_once 'utilities.php';
// insert this request into debug payments table
if (get_option('ihc_debug_payments_db')){
	ihc_insert_debug_payment_log('nextpay', $_POST);
}


if ( isset($_POST['trans_id']) && isset($_POST['order_id']) ){

    if (ihc_get_level_by_id($_GET['lid'])){
        $level_data = ihc_get_level_by_id($_GET['lid']);
        if ($level_data['payment_type']=='free' || $level_data['price']=='')  header( 'location:'. get_home_url());
    } else {
        header( 'location:'. get_home_url() );
        exit();
    }

    $r_url = get_option('ihc_nextpay_return_page');

    if(!$r_url || $r_url==-1){
        $r_url = get_option('page_on_front');
    }

    $r_url = get_permalink($r_url);
    if (!$r_url){
        $r_url = get_home_url();
    }

    $api_key = get_option('ihc_nextpay_key');
    $currency = get_option('ihc_currency');

    $trans_id = $_POST['trans_id'];
    $order_id = $_POST['order_id'];

    $amount = $level_data['price'];


    if ($currency == 'IRR')  {
        $amount = $amount  / 10 ;
    }

	$debug = FALSE;	
	$path = str_replace('nextpay_ipn.php', '', __FILE__);
	$log_file = $path . 'nextpay.log';

    $client = new SoapClient('https://api.nextpay.org/gateway/verify.wsdl', array('encoding' => 'UTF-8'));
    $result = $client->PaymentVerification(
        array(
            'api_key' => $api_key,
            'trans_id'  => $trans_id,
            'amount'	 => $amount,
            'order_id'	=> $order_id
        )
    );


    $result = $result->PaymentVerificationResult;


    // extend POST details

    $_POST['ihc_payment_type'] = 'nextpay';
    $_POST['amount'] = $level_data['price']; ;
    $_POST['currency'] = $currency ;
    $_POST['level'] = $_GET['lid'];

    if(intval($result->code) == 0){

        ihc_update_user_level_expire($level_data, $_GET['lid'], $_GET['uid']);
        ihc_send_user_notifications($_GET['uid'], 'payment', $_GET['lid']);//send notification to user
        ihc_switch_role_for_user($_GET['uid']);

        $_POST['payment_status'] = 'Completed' ;
        //record transation
        ihc_insert_update_transaction($_GET['uid'], $trans_id, $_POST);

        header( 'location:'. $r_url );

	} else {
        $_POST['payment_status'] = 'Failed' ;
        //record transation
        ihc_insert_update_transaction($_GET['uid'], $trans_id, $_POST);
        // failed transaction
        header( 'location:'. $r_url );
	}
} else {
	//non Nextpay tries to access this file
	header('Status: 404 Not Found');
	exit();	
}