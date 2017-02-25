<?php 
function ihc_update_reg_fields($post_data){
	/*
	 * this function will update the order of register fields
	 * @param $_POST
	 * @return none
	 */
	$data = get_option('ihc_user_fields');
	$new_data = array();
	foreach ($data as $k=>$v){
		$num = $post_data['ihc-order-' . $k];
		$new_data[$num] = $v;
		if (isset($post_data['ihc-field-display-admin' . $k])){
			$new_data[$num]['display_admin'] = $post_data['ihc-field-display-admin' . $k];
		}
		if (isset($post_data['ihc-field-display-public-reg' . $k])){
			$new_data[$num]['display_public_reg'] = $post_data['ihc-field-display-public-reg' . $k];
		}	
		if (isset($post_data['ihc-field-display-public-ap' . $k])){
			$new_data[$num]['display_public_ap'] = $post_data['ihc-field-display-public-ap' . $k];
		}			
		if (isset($post_data['ihc-require-' . $k])){
			$new_data[$num]['req'] = $post_data['ihc-require-' . $k];
		}
	}
	update_option('ihc_user_fields', $new_data);
}

function ihc_update_register_fields($post_data){
	/*
	 * this function will update the labels and the name
	 * @param $_POST
	 * @return none
	 */	
	$meta = get_option('ihc_user_fields');
	if (isset($meta[$post_data['id']])){
		$possible_fields = array( 
									'name',
									'label',
									'type',
									'values',
									'sublabel',
									'display_admin',
									'display_public_ap',
									'display_public_reg',
									'target_levels',
									'class',
									'theme',
									'plain_text_value',			
									'conditional_text',
									'error_message',
									'conditional_logic_show',
									'conditional_logic_corresp_field',
									'conditional_logic_corresp_field_value',
									'conditional_logic_cond_type',
		);
		foreach ($possible_fields as $key){
			if (isset($post_data[$key])){
				$meta[$post_data['id']][$key] = $post_data[$key];
			}
		}
		update_option('ihc_user_fields', $meta);			
	}
}


function ihc_save_user_field($post_data){
	/*
	 * save user field
	 * array( 
	 *  'display_admin'=>'', 
	 *	'display_public_reg'=>'', 
	 * 	'display_public_ap'=>'', 
	 * 	'always'=>'', 
	 * 	'name'=>'', 
	 * 	'label'=>'', 
	 * 	'type'=>'', 
	 * 	'native_wp' => '', 
	 * 	'req' => '', 	
	 * 	'values'=>'' 
	 *  'sublebel'=>'');
	 * @param $_POST
	 * @return none
	 */
	if (isset($post_data['name']) && $post_data['name']
			&& isset($post_data['label']) && isset($post_data['type']) ){
		$new = array(
				//'display' => 0,// deprecated
				'display_admin' => 0,
				'display_public_reg' => 0,
				'display_public_ap' => 0,
				'name' => $post_data['name'],
				'label' => $post_data['label'],
				'type' => $post_data['type'],
				'native_wp' => 0,
				'req' => 0,
				'sublabel' => $post_data['sublabel'],
				'target_levels' => @$post_data['target_levels'],
				'class' => @$post_data['class'],
		);
		$optional_metas = array(
									'values',
									'theme',
									'plain_text_value',
									'conditional_text',
									'error_message',
									'conditional_logic_show',
									'conditional_logic_corresp_field',
									'conditional_logic_corresp_field_value',
									'conditional_logic_cond_type',
								);
		foreach ($optional_metas as $optional_meta){
			if (isset($post_data[$optional_meta])){
				$new[$optional_meta] = $post_data[$optional_meta];
			}
		}
		
		$data = get_option('ihc_user_fields');
		if ($data!==FALSE){
			$data[]= $new;			
		} else {
			$data = ihc_native_user_field();
			$data[] = $new;
		}
		update_option('ihc_user_fields', $data);
	}
}

function ihc_delete_user_field($id){
	/*
	 * delete user field
	 * @param field id to delete
	 * @return none
	 */	
	$data = get_option('ihc_user_fields');
	if (isset($data[$id]) ){
		unset($data[$id]);
	}
	update_option('ihc_user_fields', $data);
}