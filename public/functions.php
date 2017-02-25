<?php 
function ihc_test_if_must_block($block_or_show, $user_levels, $target_levels, $post_id=-1){
	/*
	 * return if user must be block of not
	 * @param:
	 * block_or_show = string 'block' or 'show'
	 * $user_levels = string (all current user levels seppareted by comma)
	 * target levels = array (show/hide content for users with these levels)
	 * @return :
	 * 0 - show
	 * 1 - block
	 * 2 - expired
	 */
	global $current_user;
	$block = 0; //SHOW
	
	if (!$target_levels || (isset($target_levels[0]) && $target_levels[0]=='')){
		return $block;
	}
	$registered_user = ($user_levels=='unreg') ? 'other' : 'reg';

	$arr = explode(',', $user_levels);	

	if ($target_levels && is_array($target_levels)){
		switch($block_or_show){
			case 'block': {
				$block = 0;
				break;
			}
			case 'show': {
				$block = 1;
				break;
			}
		}
		
		foreach ($target_levels as $current_user_level){		
		  switch($block_or_show){
			case 'block': {
				if(in_array($current_user_level, $arr)){	
					
					//===LEVEL VALIDATION
					$is_expired = ihc_is_user_level_expired($current_user->ID, $current_user_level);
					// :0 - level not expired
					// :1 - level expired
					
					$is_ontime = ihc_is_user_level_ontime($current_user_level);
					// :0 - level OUT time
					// :1 - level ON time
					
					if ($is_expired == 0 && $is_ontime == 1)
						//level VALIDATED for BLOCK
						$block = 1;	
					//===END LEVEL VALIDATION
					
				}elseif( in_array('all', $target_levels) || in_array($registered_user, $target_levels) ){
						$block = 1;	
				}	
				 break;
			}
			case 'show': {
				if(in_array($current_user_level, $arr)){
					
					//===LEVEL VALIDATION					
					$is_expired = ihc_is_user_level_expired($current_user->ID, $current_user_level);
					// :0 - level not expired
					// :1 - level expired
					
					$is_ontime = ihc_is_user_level_ontime($current_user_level);
					// :0 - level OUT time
					// :1 - level ON time
					
					if ($is_expired == 0 && $is_ontime == 1)
						//level VALIDATED for SHOW
						$block = 0;	
					//===END LEVEL VALIDATION
					
				}elseif( in_array('all', $target_levels) || in_array($registered_user, $target_levels)){
						$block = 0;	
				}	
				if ($block == 0){
					$block = ihc_check_drip_content($current_user->ID, $current_user_level, $post_id);
					return $block;
				}					
				break;
			}
		  }
		}
	}
	return $block;
}

function ihc_is_user_level_expired($u_id, $l_id, $not_started_check=TRUE, $expire_check=TRUE){
	/*
	 * test if user level is expired
	 * @param: user id, level id
	 * @return:
	 * 1 - expired/not available yet
	 * 0 - ok 
	 */

	global $wpdb;
	$grace_period = get_option('ihc_grace_period');
	$data = $wpdb->get_row('SELECT expire_time, start_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $u_id . '" AND level_id="' . $l_id . '";');
	$current_time = time();
	if (!empty($data->start_time) && $not_started_check){
		$start_time = strtotime($data->start_time);
		if ($current_time<$start_time){
			//it's not available yet
			return 1;
		}				
	}	
	if (!empty($data->expire_time) && $expire_check){
		$expire_time = strtotime($data->expire_time) + ((int)$grace_period * 24 * 60 *60);
		if ($current_time>$expire_time){
			//it's expired
			return 1;
		}
	}
	return 0;	
}

function ihc_is_user_level_ontime($l_id){
	/*
	 * test if user level is on time
	 * @param: level_id
	 * @return:
	 * 1 - ON time - ok
	 * 0 - OFF time
	 */
	$on_time = 1;
	$level_data = ihc_get_level_by_id($l_id);
	if (isset($level_data['special_weekdays'])){
		$day = date( "w", time());
		if ($level_data['special_weekdays']=='weekdays' && ($day==6 || $day==0) ){
			//WEEK DAYS
			$on_time = 0;
		} else if ($level_data['special_weekdays']=='weekend'  && ($day!=6 && $day!=0) ){
			// WEEK-END
			$on_time = 0;
		}
	}	
	return $on_time;
}

function ihc_check_drip_content($uid, $lid, $post_id){
	/*
	 * /////// DRIP CONTENT \\\\\\\\
	 * @param int, int, int
	 * @return int ( 1 : block, 0 : unblock)
	 */

	global $wpdb;
	if ($post_id>-1){
		$post_meta = ihc_post_metas($post_id);
		if (!empty($post_meta['ihc_drip_content'])){ //////////DRIP CONTENT ACTIVE
			$data = $wpdb->get_row("SELECT start_time FROM " . $wpdb->prefix . "ihc_user_levels WHERE user_id='" . $uid . "' AND level_id='" . $lid . "';");
			if (!empty($data->start_time)){
				$subscription_start = strtotime($data->start_time);
				$current_time = time();
				
				//SET START TIME
				if ($post_meta['ihc_drip_start_type']==1){
					//initial
					$start_time = $subscription_start;
				} else if ($post_meta['ihc_drip_start_type']==2){
					//after
					if ($post_meta['ihc_drip_start_numeric_type']=='days'){
						$start_time = $subscription_start + $post_meta['ihc_drip_start_numeric_value'] * 24 * 60 * 60;
					} else if ($post_meta['ihc_drip_start_numeric_type']=='weeks'){
						$start_time = $subscription_start + $post_meta['ihc_drip_start_numeric_value'] * 7 * 24 * 60 * 60;
					} else {
						$start_time = $subscription_start + $post_meta['ihc_drip_start_numeric_value'] * 30 * 24 * 60 * 60;
					}
				} else {
					//certain date
					$start_time = strtotime($post_meta['ihc_drip_start_certain_date']);
				}
				if (empty($start_time)){
					$start_time = $subscription_start;
				}
								
				//SET END TIME
				if ($post_meta['ihc_drip_end_type']==1){
					//infinite
					$end_time = $start_time + 3600 * 24 * 60 * 60;// 10years should be enough
				} else if ($post_meta['ihc_drip_end_type']==2){
					//after
					if ($post_meta['ihc_drip_end_numeric_type']=='days'){
						$end_time = $start_time + $post_meta['ihc_drip_end_numeric_value'] * 24 * 60 * 60;
					} else if ($post_meta['ihc_drip_end_numeric_type']=='weeks'){
						$end_time = $start_time + $post_meta['ihc_drip_end_numeric_value'] * 7 * 24 * 60 * 60;
					} else {
						$end_time = $start_time + $post_meta['ihc_drip_end_numeric_value'] * 30 * 24 * 60 * 60;
					}
				} else {
					//certain date
					$end_time = strtotime($post_meta['ihc_drip_end_certain_date']);
				}
				if (empty($end_time)){
					$end_time = $start_time + 3600 * 24 * 60 * 60;
				}
				
				if ($current_time<$start_time){//to early
					return 1;
				} 
				if ($current_time>$end_time){//to late
					return 1;
				}
			}		
		}
	}
	return 0;
}

function ihc_block_url($url, $current_user, $post_id){
	if (!$current_user){
		$current_user = ihc_get_user_type();
	}
	if ($current_user=='admin'){
		//admin can view anything
		return;
	}

	$redirect_link = false;
	$data = get_option('ihc_block_url_entire');
	if ($data){
		//////////////////////// BLOCK URL
		$key = ihc_array_value_exists($data, $url, 'url');
		if ($key){
			if ($data[$key]['target_users']!='' && $data[$key]['target_users']!=-1){
				$target_users = explode(',', $data[$key]['target_users']);
			} else {
				$target_users = FALSE;
			}
			
			$block = ihc_test_if_must_block('block', $current_user, $target_users, $post_id);	//test if user must be block		
			
			if ($block){
				if ($data[$key]['redirect'] && $data[$key]['redirect']!=-1){
					$redirect_link = get_permalink($data[$key]['redirect']);
					if (!$redirect_link){
						$redirect_link = ihc_get_redirect_link_by_label($data[$key]['redirect']);
					}
				} else {
					//if not exists go to homepage
					$redirect_link = get_home_url();
				}
			}		
		}
	}
	$data = get_option('ihc_block_url_word');
	if ($data){
		///////////////// BLOCK IF URL CONTAINS A SPECIFIED WORD
		foreach($data as $k=>$arr){
			if (strpos($url, $arr['url'])!==FALSE) { //$arr['url'] is the word we search in current url 
				if ($arr['target_users']!='' && $arr['target_users']!=-1){
					$target_users = explode(',', $arr['target_users']);
				} else {
					$target_users = FALSE;
				}
				
				$block = ihc_test_if_must_block('block', $current_user, $target_users, $post_id);	//test if user must be block	
				
				if ( $block ){//test if user must be block
					if ($arr['redirect'] && $arr['redirect']!=-1){
						$redirect_link = get_permalink($arr['redirect']);
						if (!$redirect_link){
							$redirect_link = ihc_get_redirect_link_by_label($arr['redirect']);
						}
					} else {
						//if not exists go to homepage
						$redirect_link = get_home_url();
					}					
					break;
				}				
			}
		}
	}
	if($redirect_link){
		wp_redirect($redirect_link);
		exit();
	}
}

function ihc_if_register_url($url){
	/*
	 * test if current page is register page
	 * if is register page and lid(level id) is not set redirect to subscription plan (if its set and available one)
	 */
	
	$reg_page = get_option('ihc_general_register_default_page');
	
	if ($reg_page && $reg_page!=-1){
		
		$reg_page_url = get_permalink($reg_page);
		if ($reg_page_url==$url){
			//current page is register page
			
			if ( isset($_GET['lid']) ) return;
			$subscription_type = get_option('ihc_subscription_type');
			if ($subscription_type=='predifined_level') return;	
			
			$subscription_pid = get_option('ihc_subscription_plan_page');
			if ($subscription_pid && $subscription_pid!=-1){
				$subscription_link = get_permalink($subscription_pid);
				if ($subscription_link){
					wp_redirect($subscription_link);
					exit();
				}				
			}
		}
	}
}

function ihc_block_page_content($postid, $url){
	/*
	 * test if current post, page content must to blocked
	 */
	$meta_arr = ihc_post_metas($postid);
	if(isset($meta_arr['ihc_mb_block_type']) && $meta_arr['ihc_mb_block_type']){
		if($meta_arr['ihc_mb_block_type']=='redirect'){
			/////////////////////// REDIRECT
			if(isset($meta_arr['ihc_mb_who'])){
					
				//getting current user type and target user types
				$current_user = ihc_get_user_type();
				if($meta_arr['ihc_mb_who']!=-1 && $meta_arr['ihc_mb_who']!=''){
					$target_users = explode(',', $meta_arr['ihc_mb_who']);
				} else {
					$target_users = FALSE;
				}
				//test if current user must be redirect
				if($current_user=='admin') return;//show always for admin

				$redirect = ihc_test_if_must_block($meta_arr['ihc_mb_type'], $current_user, $target_users, $postid);
	
				if($redirect){
					//getting default redirect id
					$default_redirect_id = get_option('ihc_general_redirect_default_page');
	
					//PREVENT INFINITE REDIRECT LOOP - if current page is default redirect page return
					if($default_redirect_id==$postid) return;
	
					if (isset($meta_arr['ihc_mb_redirect_to']) && $meta_arr['ihc_mb_redirect_to']!=-1){
						$redirect_id = $meta_arr['ihc_mb_redirect_to'];//redirect to value that was selected in meta box
						//test if redirect page exists
	
						if(get_post_status($redirect_id)){
							$redirect_link = get_permalink($redirect_id);
						} else {
							//custom redirect link
							$redirect_link = ihc_get_redirect_link_by_label($redirect_id);
							if (!$redirect_link){
								//if not exists go to homepage
								$redirect_link = home_url();								
							}
						}
					} else {
						if ($default_redirect_id && $default_redirect_id!=-1){
							if (get_post_status($default_redirect_id)){
								$redirect_link = get_permalink($default_redirect_id); //default redirect page, selected in general settings
							} else {
								//custom redirect link
								$redirect_link = ihc_get_redirect_link_by_label($redirect_id);
								if (!$redirect_link){
									//if not exists go to homepage
									$redirect_link = home_url();								
								}
							}
						} else {
							$redirect_link = home_url();//if default redirect page is not set, redirect to home
						}
					}

					if ($url==$redirect_link){
						//PREVENT INFINITE REDIRECT LOOP
						return;
					}
	
					wp_redirect($redirect_link);
					exit();
				}
			}
		}else{
			////////////////////// REPLACE CONTENT, adding filter to block, show only the content
			add_filter('the_content', 'ihc_filter_content');
		}
	}	
}

function ihc_init_form_action($url){
	/*
	 * used on init action
	 * form actions : 
	 * REGISTER
	 * LOGIN 
	 * UPDATE
	 * LOGOUT
	 * RESET PASS
	 * PAY NEW LEVEL 
	 * DELETE LEVEL FROM ACCOUNT PAGE
	 * CANCEL LEVEL FROM ACCOUNT PAGE  
	 * RENEW LEVEL 
	 */
	switch ($_REQUEST['ihcaction']){
		case 'login':
			//login
			include_once IHC_PATH . 'public/functions/login.php';
			ihc_login($url);
		break;
		
		case 'logout':
			//logout
			include_once IHC_PATH . 'public/functions/logout.php';
			ihc_do_logout($url);
		break;
		
		case 'register':
			///////////////////////////////register
			include_once IHC_PATH . 'classes/UserAddEdit.class.php';
			$args = array(
					'user_id' => false,
					'type' => 'create',
					'tos' => true,
					'captcha' => true,
					'action' => '',
					'is_public' => true,
					'url' => $url,
			);
			$obj = new UserAddEdit();
			$obj->setVariable($args);//setting the object variables
			$obj->save_update_user();
		break;
		
		case 'update':
			/////////////////////// UPDATE
			if( is_user_logged_in() ){
				$current_user = wp_get_current_user();
				$user_id = $current_user->ID;
				
				include_once IHC_PATH . 'classes/UserAddEdit.class.php';
				$args = array(
						'user_id' => $user_id,
						'type' => 'edit',
						'tos' => false,
						'captcha' => false,
						'action' => '',
						'is_public' => true,
				);
				$obj = new UserAddEdit();
				$obj->setVariable($args);
				$obj->save_update_user();
			}
		break;
				
		case 'reset_pass':
			include_once IHC_PATH . 'public/functions/lost_pass.php';
			ihc_reset_pass();
		break;
		
		case 'paynewlid':
			//////////////// ACQUIRE NEW LEVEL
			if (isset($_REQUEST['lid'])){
				global $current_user;
				if (isset($current_user->ID)){	//only we have a user id to proceed
					$uid = $current_user->ID;
					$return_url = (isset($_GET['urlr'])) ? urldecode($_GET['urlr']) : '';
					$level_data = ihc_get_level_by_id($_GET['lid']);	
					require_once IHC_PATH . 'classes/UserAddEdit.class.php';
					$args = array(
							'user_id' => $uid,
							'type' => 'edit',
							'tos' => false,
							'captcha' => false,
							'action' => '',
							'is_public' => true,
					);
					$obj = new UserAddEdit();
					$obj->setVariable($args);//setting the object variables
					$obj->set_coupon();
					$obj->update_level($return_url);
					$obj->save_coupon();
				}
			}
		break;
		
		case 'renew_cancel_delete_level_ap':
			global $current_user;
			
			if (isset($_POST['ihc_delete_level']) && $_POST['ihc_delete_level']!=''){
				//delete level				
				if (isset($current_user->ID)){
					ihc_delete_user_level_relation($_POST['ihc_delete_level'], $current_user->ID);
				}
				$level_data = ihc_get_level_by_id($_POST['ihc_delete_level']);
				if (isset($level_data['access_type']) && $level_data['access_type']=='regular_period'){
					//RECURRENCE, must do cancel
					$_POST['ihc_cancel_level'] = $_POST['ihc_delete_level'];		
				}
			} 
			
			if (isset($_POST['ihc_cancel_level']) && $_POST['ihc_cancel_level']!=''){
				//cancel level
				ihc_cancel_level($current_user->ID, $_POST['ihc_cancel_level']);
			}
			
			if (isset($_POST['ihc_renew_level']) && $_POST['ihc_renew_level']){
				$payment_type = (!empty($_POST['ihc_payment_gateway'])) ? $_POST['ihc_payment_gateway'] : '';
				if (ihc_check_payment_available($payment_type)){
					ihc_renew_level($current_user->ID, $_POST['ihc_renew_level'], $payment_type);					
				}
			}
			
		break;
	}
}//end of ihc_init_form_action()



function ihc_add_stripe_public_form($content){
	$publishable_key = get_option('ihc_stripe_publishable_key');
	global $current_user;
	$currency = get_option('ihc_currency');
	$str = '';
	$str .= '<form action="" method="post" class="ihc-stripe-form-payment">';
	$str .= '<input type="hidden" name="uid" value="'.$current_user->ID.'" />';
	$str .= '<input type="hidden" name="lid" id="ihc_lid_stripe" value="" />';
	$str .= '</form>';
	$str .= '<script src="https://checkout.stripe.com/checkout.js"></script>
			<script>
				var handler = StripeCheckout.configure({
				key: "' . $publishable_key . '",
				locale: "auto",
				token: function(response) {
					var input = jQuery("<input type=hidden name=stripeToken id=stripeToken />").val(response.id);
					var email = jQuery("<input type=hidden name=stripeEmail id=stripeEmail />").val(response.email);
					jQuery(".ihc-stripe-form-payment").append(input);
					jQuery(".ihc-stripe-form-payment").append(email);
					jQuery(".ihc-stripe-form-payment").submit();
				}
				});
				
				function ihc_stripe_payment(l_name, l_amount, lid){
					var l_amount = l_amount * 100;
					if (l_amount<50){
						l_amount = 50;
					}
					jQuery("#ihc_lid_stripe").val(lid);
					if (jQuery("#ihc_coupon").val()){
						//with coupon
						jQuery.ajax({
									type : "post",
									url : "' . IHC_URL . 'public/custom-ajax.php",
									data : {
										    ihc_coupon: jQuery("#ihc_coupon").val(),
										    l_id: lid,
										    initial_price: l_amount,
									},
									success: function (data) {										
										if (data!=0){
											var obj = jQuery.parseJSON(data);
											if (typeof obj.price!="undefined"){
												jQuery(".ihc-stripe-form-payment").append("<input type=hidden value=" + jQuery("#ihc_coupon").val() + " name=ihc_coupon />");
												var l_amount = obj.price;
												if (obj.price==0){
								   	 				jQuery(".ihc-stripe-form-payment").append("<input type=hidden name=stripeToken value=stripe />");
								   	 				jQuery(".ihc-stripe-form-payment").append("<input type=hidden name=stripeEmail value=stripe />");
								   	 				jQuery(".ihc-stripe-form-payment").submit();
								   	 				return;
								   	 			} else if (obj.price<50){
								   	 				obj.price = 50;
								   	 			}
												handler.open({
													name: l_name,
													description: "Level "+lid,
													amount: l_amount,
													currency: "' . $currency . '"
												});	
											}															
										} 
									}
						});						
					} else {
						//without coupon
						jQuery("#ihc_lid_stripe").val(lid);
							handler.open({
							name: l_name,
							description: "Level "+lid,
							amount: l_amount,
							currency: "' . $currency . '"
						});	
					}
				}
			</script>';
	return do_shortcode($content) . $str;
}

function ihc_pay_new_lid_with_stripe($request){
	if (isset($request['stripeToken']) && isset($request['stripeEmail']) && isset($request['lid']) && isset($request['uid']) ) {
		
		if (!class_exists('ihcStripe')){
			require_once IHC_PATH . 'classes/ihcStripe.class.php';
		}		
		
		$lid = $request['lid'];
		$uid = $request['uid'];
		$level_data = ihc_get_level_by_id($lid);
		$post_data = array(
				'lid' => $lid,
				'stripeToken' => $request['stripeToken'],
				'stripeEmail' => $request['stripeEmail'],
		);
		$post_data['ihc_coupon'] = (!empty($request['ihc_coupon'])) ? $request['ihc_coupon'] : '';
		
		if (ihc_dont_pay_after_discount($lid, @$request['ihc_coupon'], $level_data, TRUE)){
			// 0 amount to pay
			ihc_update_user_level_expire($level_data, $lid, $uid);
		} else {
			//payment
			$payment_obj = new ihcStripe();
			$pay_result = $payment_obj->charge($post_data);
			if ($pay_result['message'] == "success") {
				/////////////////////////////////////////MODF HERE
				//if (!empty($pay_result['subscription'])){
				//	$trans_id = $pay_result['subscription'];
				//} else {
					$trans_id = $pay_result['trans_id'];
				//}
				
				$trans_info = $pay_result;				
				ihc_update_user_level_expire($level_data, $lid, $uid);
				ihc_insert_update_transaction($uid, $trans_id, $trans_info);
			}			
		}
		
		$user_levels = get_user_meta($uid, 'ihc_user_levels', true);
		if ($user_levels!==FALSE && $user_levels!=''){
			$user_levels_arr = explode(',', $user_levels);
			if (!in_array($lid, $user_levels_arr)){
				$user_levels_arr[] = $lid;
			}
			$user_levels = implode(',', $user_levels_arr);
		} else {
			$user_levels = $lid;
		}
		$succees = ihc_handle_levels_assign($uid, $lid);
		if ($succees){
			update_user_meta($uid, 'ihc_user_levels', $user_levels);//assign the new level
			return TRUE;
		}
	}
}

function ihc_pay_new_lid_with_authorize($uid=0, $data=array()){
	/*
	 * Used only in buy new level (reccuring) from account page and subscription plan page for registered users.
	 * @param uid - int, data - array
	 * @return boolean
	 */	
	$level_data = ihc_get_level_by_id($data['lid']);
	
	if (!class_exists('ihcAuthorizeNet')){
		require_once IHC_PATH . 'classes/ihcAuthorizeNet.class.php';
	}	
	$auth_pay = new ihcAuthorizeNet();
	$charge = $auth_pay->charge($data);

	if ($charge){
		$pay_result = $auth_pay->subscribe($data);
		if ($pay_result['code'] == 2){
			$trans_id = $pay_result['trans_id'];
			$trans_info = $pay_result;
			$trans_info['ihc_payment_type'] = 'authorize';
			
			$user_levels = get_user_meta($uid, 'ihc_user_levels', true);
			if ($user_levels!==FALSE && $user_levels!=''){
				$user_levels_arr = explode(',', $user_levels);
				if (!in_array($data['lid'], $user_levels_arr)){
					$user_levels_arr[] = $data['lid'];
				}
				$user_levels = implode(',', $user_levels_arr);
			} else {
				$user_levels = $data['lid'];
			}
			$succees = ihc_handle_levels_assign($uid, $data['lid']);
			if ($succees){
				update_user_meta($uid, 'ihc_user_levels', $user_levels);//assign the new level
				ihc_update_user_level_expire($level_data, $data['lid'], $uid);
				ihc_insert_update_transaction($uid, $trans_id, $trans_info);
				return TRUE;
			}		
		}
	}
	return FALSE;
}

function ihc_renew_level($u_id, $l_id, $payment_type=''){
	/*
	 * @param user id, level id
	 * @return none
	 */
	if (!$payment_type){
		$payment_type = get_option('ihc_payment_selected');
	}
	switch ($payment_type){
		case 'nextpay':
			$href = IHC_URL . 'public/nextpay_payment.php?lid=' . $l_id . '&uid=' . $u_id;
			if (!empty($_REQUEST['ihc_coupon'])){
				$href .= '&ihc_coupon=' . $_REQUEST['ihc_coupon'];
			}
			wp_redirect($href);
			exit();
			break;
		case 'bank_transfer':
			/*
			 * RENEW bank transfer
			 */
			$url = IHC_PROTOCOL . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$url = add_query_arg( 'ihc_success_bt', true, $url );
			$url = add_query_arg( 'ihc_lid', $l_id, $url );
			$url .= '#ihc_bt_success_msg';
			wp_redirect($url);
			exit();
			break;
	}
}

