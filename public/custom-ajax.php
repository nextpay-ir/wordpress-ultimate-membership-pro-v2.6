<?php 
require_once("../../../../wp-load.php");


if (!empty($_REQUEST['ihc_coupon']) && !empty($_REQUEST['l_id'])){
	//use this only for stripe payment
	$coupon_data = ihc_check_coupon($_REQUEST['ihc_coupon'], $_REQUEST['l_id']);
	if ($coupon_data){
		$level_data = ihc_get_level_by_id($_REQUEST['l_id']);
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
	if (isset($_REQUEST['initial_price'])){
		echo json_encode(array('price'=>$_REQUEST['initial_price']));//new price not available, problems with coupon
	}
	die();
}
echo 0;
die();
