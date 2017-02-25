<?php 
if (!class_exists('ihcAccountPage')){
	class ihcAccountPage{
		private $url = '';
		private $current_user = array();
		private $settings = array();
		private $tab = '';
		private $users_sm = array();
		private $show_tabs = array();
		
		public function __construct(){
			$account_page = get_option('ihc_general_user_page');
			if ($account_page!==FALSE && $account_page>-1){
				$this->url = get_permalink($account_page);
			} else {
				$this->url = IHC_PROTOCOL . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			}			
			$this->current_user = wp_get_current_user();
			$this->settings = ihc_return_meta_arr('account_page');
		}
		
		public function print_page($tab){	
			/*
			 * @param string 
			 * @return string
			 */
			$this->tab = $tab;			
			$str = '';
			if (!empty($this->settings['ihc_account_page_custom_css'])){
				//the style
				$str .= '<style>';
				$str .= $this->settings['ihc_account_page_custom_css'];
				$str .= '</style>';
			}
			$str .= $this->print_head();
			$str .= $this->print_tabs();
			$str .= $this->print_content();			
			$str = '<div class="ihc-account-page-wrapp ' . $this->settings['ihc_ap_theme'] . '" id="ihc_account_page_wrapp">' . $str . '</div>';
			return $str;
		}
		
		private function print_head(){
			/*
			 * print the top section with photo and welcome message
			 * @param none
			 * @return string
			 */
			$str = '';
				$str .='<div class="iump-user-page-details">';
				$show_avatar = $this->settings['ihc_ap_edit_show_avatar'];
				if ($show_avatar){
					$avatar = get_user_meta($this->current_user->ID, 'ihc_avatar', true);
					if (strpos($avatar, "http")===0){
						$avatar_url = $avatar;
					} else {
						$avatar_url = wp_get_attachment_url($avatar);
					}	
					if ($avatar_url){
						$str .= '<div class="iump-user-page-avatar"><img src="'.$avatar_url.'"/></div>';
					} else {
						$str .= '<div class="iump-user-page-avatar"><img src="'. IHC_URL . 'assets/images/no-avatar.png"/></div>';
					}		
				}
				$first_name = get_user_meta($this->current_user->ID, 'first_name', true);
				$last_name = get_user_meta($this->current_user->ID, 'last_name', true);
				
				if (!empty($this->settings['ihc_ap_welcome_msg'])){
					$this->settings['ihc_ap_welcome_msg'] = ihc_format_str_like_wp($this->settings['ihc_ap_welcome_msg']);
					$this->settings['ihc_ap_welcome_msg'] = htmlspecialchars_decode($this->settings['ihc_ap_welcome_msg']);
					$this->settings['ihc_ap_welcome_msg'] = stripslashes($this->settings['ihc_ap_welcome_msg']);					
					$str .= '<div class="ihc-account-page-top-mess">';
					$str .= ihc_replace_constants($this->settings['ihc_ap_welcome_msg'], $this->current_user->ID);
					$str .= '</div>';
				} else {
					//standard welcome message
					$str .= '<div class="ihc-account-page-top-mess">';
					$str .='<div class="iump-user-page-mess">' . __('Welcome', 'ihc') . ',</div>';
					$str .='<div class="iump-user-page-name">' . $first_name . ' ' . $last_name . '</div>';
					$str .='<div class="iump-user-page-email">' . $this->current_user->user_email . '</div>';	
					$str .= '</div>';			
				}
					$str .= $this->print_sm_icons_for_current_user();
					$str .= '<div class="iump-clear"></div>';
				$str .='</div>';
			return $str;			
		}
		
		private function print_tabs(){
			/*
			 * print the top menu with available tabs
			 * @param none
			 * @return string
			 */
			$available_tabs = array('overview'=>__('Overview', 'ihc'),
									'profile'=>__('Profile', 'ihc'),
									'subscription'=>__('Subscription', 'ihc'),
									'social' => __('Social Plus', 'ihc'),
									'transactions'=>__('Transactions', 'ihc'),									
			);
			$this->show_tabs = explode(',', $this->settings['ihc_ap_tabs']);
			$str = '';
			$str .= '<div class="ihc-mobile-bttn-wrapp"><i class="ihc-mobile-bttn"></i></div>';
			$str .= '<div class="ihc-ap-menu">';
			foreach ($available_tabs as $k=>$v){
				if (in_array($k, $this->show_tabs)){
					$new_url = add_query_arg( 'ihc_ap_menu', $k, $this->url );
					$class = 'ihc-ap-menu-item';
					$class .= ($k==$this->tab) ? ' ihc-ap-menu-item-selected' : '';
					$str .= '<div class="' . $class . '"><i class="fa-ihc fa-'.$k.'-account-ihc"></i><a href="' . $new_url . '">' . $v . '</a></div>';					
				}
			}
			$str .= '</div>';
			return $str;			
		}
		
		public function print_content(){
			$str = '';
			switch ($this->tab){
				case 'profile':
					$str .= $this->account_details_page();
					break;
				case 'transactions':
					$str .= $this->transactions_page();
					break;
				case 'subscription':
					$str .= $this->subscription_page();
					break;
				case 'overview':
					$str .= $this->overview_page();
					break;
				case 'social':
					$str .= $this->social_page();
					break;
				default :
					
						$str .= $this->overview_page();
					
					break;					
			}
			//add the wrapp div
			$str = '<div class="" id="ihc_account_page_tab_content">' . $str . '</div>';
			return $str;
		}
		
		private function overview_page(){
			/*
			 * OVerview Page
			 * @param none
			 * @return string
			 */
			$str = '';
			$post_overview = get_user_meta($this->current_user->ID, 'ihc_overview_post', true);
			if ($post_overview && $post_overview!=-1){
				//print the post for user
				$post = get_post($post_overview);
				$str .= $post->post_content;
			} else {
				//predifined message
				$this->settings['ihc_ap_overview_msg'] = ihc_format_str_like_wp($this->settings['ihc_ap_overview_msg']);
				$this->settings['ihc_ap_overview_msg'] = ihc_correct_text($this->settings['ihc_ap_overview_msg']);
				$str .= $this->settings['ihc_ap_overview_msg'];				
			}
			return $str;
		}

		private function account_details_page(){
			/*
			 * 
			 * @param none
			 * @return string
			 */
			$str = '';
			$template = get_option('ihc_register_template');
			$str .= '<style> ' . get_option('ihc_register_custom_css') . '</style>';
			$current_user = wp_get_current_user();
			
			global $ihc_error_register;
			if (empty($ihc_error_register)){
				$ihc_error_register = array();
			}
			
			require_once IHC_PATH . 'classes/UserAddEdit.class.php';
			$obj_form = new UserAddEdit();
			$args = array(
							'user_id' => $current_user->ID,
							'type' => 'edit',
							'tos' => false,
							'captcha' => false,
							'select_level' => false,
							'action' => '',
							'is_public' => true,
							'register_template' => $template,
							'print_errors' => $ihc_error_register
						);
			$obj_form->setVariable($args);
			$str .='<div class="iump-user-page-wrapper ihc_userpage_template_1">';
				$str .='<div class="iump-user-page-box">';
				$str .='<div class="iump-user-page-box-title">' . __('Update Profile', 'ihc') . '</div>';
					$str .= '<div class="iump-register-form ' . $template . '">' . $obj_form->form() . '</div>';
				$str .='</div>';
			$str .='</div>';
			return $str;
		}
		
		private function transactions_page(){
			/*
			 * transactions
			 * @param none
			 * @return string
			 */
			
			//get transactions for current user
			$str = '<div class="iump-account-content-title">'.__('Transactions History', 'ihc').'</div>';
			global $wpdb;
			$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "indeed_members_payments WHERE `u_id`='" . $this->current_user->ID . "';");
			if ($data){
				$str .= '
					<table class="wp-list-table ihc-account-tranz-list">
						<thead>
							<tr>											  
								<th style="text-align:left;">
									<span>
										' . __('Level', 'ihc') . '
									</span>									  
								</th>
								<th>
									<span>
										' . __('Amount', 'ihc') . '
									</span>									  
								</th>
								<th>
									<span>
										' . __('Payment Type', 'ihc') . '
									</span>
								</th>										
								<th>
									<span>
										' . __('Status', 'ihc') . '
									</span>
								</th>												  								  
								<th class="manage-column" style="text-align:right;">
									<span>
										' . __('Date', 'ihc') . '
									</span>
								</th>											  										  								  								  
							</tr>
						</thead>';
				foreach ($data as $k=>$v){
					$data_payment = json_decode($v->payment_data);
					$str .= '
					<tr>
						<td class="manage-column"  style="text-align:left;">
							<div class="level-type-list">';
				 				if (isset($data_payment->level)){
									//2checkout
									$level_data_arr = ihc_get_level_by_id($data_payment->level);
									$str .= $level_data_arr['label'];
								} else if (isset($data_payment->item_name)){
									$str .= $data_payment->item_name;
								} elseif (isset($data_payment->x_description)){
									$str .= $data_payment->x_description;
								} else {
									$str .= '--';	
								}
					$str .= '
							</div>
						</td>
						<td class="manage-column">
							<span class="level-payment-list">';
							if (isset($data_payment->mc_gross) && isset($data_payment->mc_currency)){
								$str .= $data_payment->mc_gross . ' ' .$data_payment->mc_currency;
							} else if (isset($data_payment->x_amount)){
								$str .= $data_payment->x_amount;
								if (isset($data_payment->x_currency_code)){
									$str .= ' ' . $data_payment->x_currency_code;
								}
							} else if (isset($data_payment->amount ) && isset($data_payment->currency)){
								$str .= $data_payment->amount . ' ' .$data_payment->currency;
							} else if(isset($data_payment->total)){
								$str .= $data_payment->total . ' ' . $data_payment->currency_code ;
							}  else {
								$str .= '--';	
							}
						$str .= '
							</span>
						</td>';
						if (isset($data_payment->ihc_payment_type)){
							$payment_type = $data_payment->ihc_payment_type;
						} else {
							$payment_type = get_option('ihc_payment_selected');
						}
						$str .= '<td style="text-transform:capitalize;">' . $payment_type . '</td>';
						$str .= '<td class="manage-column" style="font-family: Oswald, arial, sans-serif !important;">';
						 
							if (!empty($data_payment->payment_status)){
								$str .= $data_payment->payment_status;
							} else if (isset($data_payment->x_response_code) && ($data_payment->x_response_code == 1)){
								$str .= __("Completed", "ihc");
							} else if (isset($data_payment->code) && ($data_payment->code == 2)){
								$str .= __("Completed", "ihc");
							} else if(isset($data_payment->message) && $data_payment->message=='success'){
								$str .= __("Completed", "ihc");
							}  else {
								$str .= '--';	
							}
						$str .= '
						</td>
						<td class="manage-column" style="text-align:right;">
							<span>
								' . date("F j, Y, g:i a", strtotime($v->paydate))   . '
							</span>
						</td>		
					</tr>';					
				}
				
				$str .= '
										<tfoot>
											<tr>											  
												<th style="text-align:left;">
													<span>
														' . __('Level', 'ihc') . '
													</span>									  
												</th>
												<th>
													<span>
														' . __('Amount', 'ihc') . '
													</span>									  
												</th>	
												<th>
													<span>
														' . __('Payment Type', 'ihc') . '
													</span>
												</th>													
												<th>
													<span>
														' . __('Status', 'ihc') . '
													</span>
												</th>									  								  
												<th class="manage-column" style="text-align:right;">
													<span>
														' . __('Date', 'ihc') . '
													</span>
												</th>											  										  								  								  
											</tr>
										</tfoot>
									</table>
				';				
			} else {
				$str .= __("No transactions available yet!", 'ihc');
			}
			return $str;
		}
		
		private function subscription_page(){
			$str = '<div class="iump-account-content-title">'.__('Subscription Details', 'ihc').'</div>';
			$levels_str = get_user_meta($this->current_user->ID, 'ihc_user_levels', true);
			

			$fields = get_option('ihc_user_fields');				
			////PRINT SELECT PAYMENT
			$key = ihc_array_value_exists($fields, 'payment_select', 'name');
			$print_payment_select = (empty($fields[$key]['display_public_ap'])) ? FALSE : TRUE;
			////PRINT SELECT PAYMENT
			
			///INCLUDE STRIPE JS SCRIPT?
			if (in_array('stripe', ihc_get_active_payments_services(TRUE)) && $print_payment_select){
				$include_stripe = TRUE;
			}
			
			if ($levels_str!=''){
				$levels_arr = explode(',', $levels_str);
				$str .= '<table class="ihc-account-subscr-list">';
				$str .= '<thead><tr>' 
							. '<td style="padding-left: 15px;">' . __("Level Name", 'ihc') . '</td>' 
							. '<td>' . __("Status", 'ihc') . '</td>' 
							. '<td>' . __("Expire Time", 'ihc') . '</td>'
							. '<td>' . __("Access", 'ihc') . '</td>'
							. '<td style="text-align:center;">' . __("Reccurent", 'ihc') . '</td>'
							. '<td style="text-align:right;">' . __("Amount", 'ihc') . '</td>'
						. '</tr></thead>';
				$i = 0;
				foreach ($levels_arr as $level_id){
					$time_data = ihc_get_start_expire_date_for_user_level($this->current_user->ID, $level_id);
					if (strtotime($time_data['expire_time'])>time()){
						$expire = $time_data['expire_time'];
					} else if (strtotime($time_data['expire_time'])<0) {
						$expire = __('--', 'ihc');//not active yet
					} else {
						$expire = __('Expired', 'ihc');
					}
					$show_cancel = ihc_show_cancel_level_link($this->current_user->ID, $level_id);
					$show_renew = ihc_show_renew_level_link($level_id);
					$payment_type = get_option('ihc_payment_selected');
					
					$level_data = ihc_get_level_by_id($level_id);
					$hidden_div = 'ihc_ap_subscription_l_' . $i;
					$str .= '<tr onMouseOver="ihc_dh_selector(\'#' . $hidden_div . '\', 1);" onMouseOut="ihc_dh_selector(\'#' . $hidden_div . '\', 0);">';
					$str .= '<td  class="ihc-level-name-wrapp" style="text-align:left;"><span class="ihc-level-name">' . $level_data['label']. '</span>';
					$str .= '<div style="visibility: hidden;" id="' . $hidden_div . '">';
					
					if ($show_renew){
						$include_stripe_script = TRUE;		
						$renew_label = __('Renew', 'ihc');
						$time_arr = ihc_get_start_expire_date_for_user_level($this->current_user->ID, $level_id);
						if (isset($time_arr['expire_time']) && $time_arr['expire_time']=='0000-00-00 00:00:00'){
							//it's for the first time
							$renew_label = __('Finish payment', 'ihc');
						}				
						$str .= '<span style="cursor: pointer;" onClick="ihc_renew_function(\'#ihc_renew_level\', \'#ihc_form_ap_subscription_page\', ' . $level_id . ', \'' . $level_data['label'] . '\',  \'' . $level_data['price'] . '\');">' . $renew_label . '</span> | ';	
					}					
					if ($show_cancel){
						$str .= '<span style="color: red;cursor: pointer;" onClick="ihc_set_form_i(\'#ihc_cancel_level\', \'#ihc_form_ap_subscription_page\', ' . $level_id . ');">Cancel</span> | ';
					}
					$str .= '<span style="color: red;cursor: pointer;" onClick="ihc_set_form_i(\'#ihc_delete_level\', \'#ihc_form_ap_subscription_page\', ' . $level_id . ');">Delete</span> 
							 </div>';
					$str .= '</td>';
					$status = ihc_get_user_level_status_for_ac($this->current_user->ID, $level_id);
					$str .= '<td class="ihc_account_level_status">' . $status . '</td>';
					
					if ($expire && $expire!='--'){
						$str .= '<td>' . date("F j, Y", strtotime($expire)) . '</td>';
					} else {
						$str .= '<td>--</td>';
					}					
					$paid_type = $level_data['payment_type'];
					if($paid_type = 'payment') $paid_type = 'Paid';
					$str .= '<td style="text-transform: capitalize;">' . $paid_type . '</td>';
					$reccurence = '--';
					$r = array('bl_onetime'=>'No', 'bl_ongoing'=>'Yes', 'bl_limited'=>'Limited');
					if (!empty($level_data['billing_type']) && !empty($r[$level_data['billing_type']])){
						$reccurence = $r[$level_data['billing_type']];
					}
					$str .= '<td style="text-align:center;">' . $reccurence . '</td>';
					if ($level_data['price'] && $level_data['payment_type']=='payment'){
						$currency = get_option('ihc_currency');
						$price = $level_data['price'] . ' ' . $currency;
					} else {
						$price = '--';
					}
					$str .= '<td style="color:#222; text-align:right; padding-right:10px;">' . $price . '</td>';
					$str .= '</tr>';
					$i++;
				}
				$default_payment = get_option('ihc_payment_selected');
				$str .= '</table>';
				$str .= '<form id="ihc_form_ap_subscription_page" name="ihc_ap_subscription_page" method="post" >';				
				$str .= '<input type="hidden" name="ihc_delete_level" value="" id="ihc_delete_level" />';//delete level id
				$str .= '<input type="hidden" name="ihc_cancel_level" value="" id="ihc_cancel_level" />';//cancel level id
				$str .= '<input type="hidden" name="ihc_renew_level" value="" id="ihc_renew_level" />';//renew
				$str .= '<input type="hidden" name="ihcaction" value="renew_cancel_delete_level_ap" />';//cancel level id
				
				$the_payment_type = ( ihc_check_payment_available($default_payment) ) ? $default_payment : '';
				$str .= '<input type="hidden" value="' . $the_payment_type . '" name="ihc_payment_gateway" />';
				$str .= '</form>';	
				
				if (($payment_type=='stripe' || !empty($include_stripe)) && !empty($include_stripe_script)){
					$str .= ihc_stripe_renew_script('#ihc_form_ap_subscription_page');
				}			
			}
			
			$str .= ihc_user_select_level(FALSE, FALSE);
			return $str;
		}
		
		private function social_page(){
			/*
			 * @param none
			 * @return string
			 */
			$str = '';
			if (!empty($this->settings['ihc_ap_social_plus_message'])){
				$str .= '<div class="ihc-ap-sm-message-top">' . $this->settings['ihc_ap_social_plus_message'] . '</div>';
			}
			$str .= ihc_print_social_media_icons('update', $this->users_sm);
			return $str;
		}
		
		private function print_sm_icons_for_current_user(){
			/*
			 * @param none
			 * @return string
			 */
			$arr = array(
					"fb" => "Facebook",
					"tw" => "Twitter",
					"in" => "LinkedIn",
					"goo" => "Google",
					"vk" => "Vkontakte",
					"ig" => "Instagram",
					"tbr" => "Tumblr"
			);
			$str = '';
			foreach ($arr as $k=>$v){
				$data = get_user_meta($this->current_user->ID, 'ihc_' . $k, true);
				if (!empty($data)){
					$this->users_sm[] = $k; 
					$str .= '<div class="ihc-account-page-sm-icon ihc-'.$k.'" style="display: inline-block;"><i class="fa-ihc-sm fa-ihc-'.$k.'"></i></div>';
				}
			}	
			if ($str){
				$str = '<div class="ihc-ap-sm-top-icons-wrap">' . $str . '</div>';
			}
			return $str;		
		}
		
	}//end of class ihcAccountPage
}