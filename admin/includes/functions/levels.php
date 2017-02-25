<?php 
//////////////////////////////LEVELS
function ihc_save_level(){
	if (isset($_REQUEST['ihc_save_level'])){
		if (isset($_REQUEST['name']) && $_REQUEST['name']!=''){
			$option_name = 'ihc_levels';
			$data = get_option($option_name);
			$arr = array(   
							'name'=>'', 
							'payment_type'=>'',
							'price'=>'',					
						    'label'=>'',
							'description'=>'',
							'price_text' => '',
							'order' => '',
							'access_type' => 'unlimited',
							'access_limited_time_type' => 'D',
							'access_limited_time_value' => '',
							'access_interval_start' => '',
							'access_interval_end' => '',
							'access_regular_time_type' => 'D',
							'access_regular_time_value' => '',
							'billing_type' => '',
							'billing_limit_num' => '2',
							'show_on' => '1',
							'afterexpire_level' => -1,
							'custom_role_level' => '-1',
							'start_date_content' => '0',
							'special_weekdays' => '',
							//trial
							'access_trial_time_value' => '',
							'access_trial_time_type' => 'D',
							'access_trial_price' => '',
							'access_trial_couple_cycles' => '',			
							'access_trial_type' => 1,
						);
			foreach ($arr as $k=>$v){
				if (isset($_REQUEST[$k])) $arr[$k] = $_REQUEST[$k];
			}
			
			//if it's not regular period type of level ... force billing_type to be bl_onetime
			if (isset($arr['access_type']) && $arr['access_type']!='regular_period'){				
				$arr['billing_type'] = 'bl_onetime';
			}
			
			if ($data!==FALSE){
				if (isset($_REQUEST['level_id']) && $_REQUEST['level_id']!=''){
					//update level
					$id = $_REQUEST['level_id'];
				} else {
					end($data);
					$id = key($data);
					$id++;
				}
				$check = ihc_array_value_exists($data, $_REQUEST['name'], 'name');
				if ($check && $check!=$id) return; 
				$data[$id] = $arr;				
				update_option($option_name, $data);
			} else {
				//create the first level		
				$data[1] = $arr;
				update_option($option_name, $data);
			}
		}
	}
}

function ihc_delete_level(){
	/*
	 * delete LEVEL from wp_options, ihc_user_levels and user_meta 
	 * @param none
	 * @return none
	 */
	if(isset($_REQUEST['ihc_level_delete-id']) && $_REQUEST['ihc_level_delete-id']!=''){
		//delete level wp option
		$data = get_option('ihc_levels');
		foreach ($data as $k=>$v){
			if ($k==$_REQUEST['ihc_level_delete-id']){
				unset($data[$k]);
			}
		}
		update_option('ihc_levels', $data);
		
		//delete transactions 
		global $wpdb;
		$wpdb->query("DELETE FROM ".$wpdb->prefix."ihc_user_levels WHERE level_id=" . $_REQUEST['ihc_level_delete-id']);
		
		//delete user-level
		$users = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."users;");
		foreach ($users as $u){
			$u_levels = get_user_meta($u->ID, 'ihc_user_levels', TRUE);
			if ($u_levels!==FALSE && $u_levels!=''){
				$u_levels_arr = explode(",", $u_levels);
				if ($u_levels_arr){
					foreach ($u_levels_arr as $k=>$u_lid){
						if ($u_lid==$_REQUEST['ihc_level_delete-id']){
							unset($u_levels_arr[$k]);
							$level_str = implode(',', $u_levels_arr);
							update_user_option($u->ID, 'ihc_user_levels', $level_str);
							continue 2;
						}
					}
				}
			}
		}
	}
}