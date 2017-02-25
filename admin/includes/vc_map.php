<?php
//register
vc_map(
	array(
		"name" => 'Membership Pro - ' . __('Register Form', 'ihc'),
		"base" => 'ihc-register',
		"icon" => 'ihc_vc_logo',
		"description" => __('Register Form', 'ihc'),
		"class" => 'ihc-register',
		"category" => __('Content', 'js_composer'),
		"params" => array(
							array(
									"type" => "ihc_print_text_vc",
									"custom_text" => __("Register Form Shortcode", 'ihc'),
									'param_name' => 'param1',
							)		
						),
		'show_settings_on_create' => false,
	)	
);

//Login
vc_map(
		array(
				"name" => 'Membership Pro - ' . __('Login Form', 'ihc'),
				"base" => 'ihc-login-form',
				"icon" => 'ihc_vc_logo',
				"description" => __('Login Form', 'ihc'),
				"class" => 'ihc-login-form',
				"category" => __('Content', 'js_composer'),
				"params" => array(
									array(
											"type" => "ihc_print_text_vc",
											"custom_text" => __("Login Form Shortcode", 'ihc'),
											'param_name' => 'param1',
									)						
								),
				'show_settings_on_create' => false,
		)
);

//Logout
vc_map(
		array(
				"name" => 'Membership Pro - ' . __('Logout Button', 'ihc'),
				"base" => 'ihc-logout-link',
				"icon" => 'ihc_vc_logo',
				"description" => __('Logout Button', 'ihc'),
				"class" => 'ihc-logout-link',
				"category" => __('Content', 'js_composer'),
				"params" => array(
									array(
											"type" => "ihc_print_text_vc",
											"custom_text" => __("Logout Link Shortcode", 'ihc'),
											'param_name' => 'param1',
									)						
								),
				'show_settings_on_create' => false,
		)		
);

//Password Recovery
vc_map(
		array(
				"name" => 'Membership Pro - ' . __('Password Recovery', 'ihc'),
				"base" => 'ihc-pass-reset',
				"icon" => 'ihc_vc_logo',
				"description" => __('Password Recovery', 'ihc'),
				"class" => 'ihc-pass-reset',
				"category" => __('Content', 'js_composer'),
				"params" => array(
									array(
											"type" => "ihc_print_text_vc",
											"custom_text" => __("Password Recovery Shortcode", 'ihc'),
											'param_name' => 'param1',
									)						
								 ),
				'show_settings_on_create' => false,
		)
);

//User Page
vc_map(
		array(
				"name" => 'Membership Pro - ' . __('Account Page', 'ihc'),
				"base" => 'ihc-user-page',
				"icon" => 'ihc_vc_logo',
				"description" => __('Password Recovery', 'ihc'),
				"class" => 'ihc-user-page',
				"category" => __('Content', 'js_composer'),
				"params" => array(
									array(
											"type" => "ihc_print_text_vc",
											"custom_text" => __("User Page Shortcode", 'ihc'),
											'param_name' => 'param1',
									)						
								 ),
				'show_settings_on_create' => false,
		)
);

//Subscription Plan
vc_map(
		array(
				"name" => 'Membership Pro - ' . __('Subscription Plan', 'ihc'),
				"base" => 'ihc-select-level',
				"icon" => 'ihc_vc_logo',
				"description" => __('Password Recovery', 'ihc'),
				"class" => 'ihc-select-level',
				"category" => __('Content', 'js_composer'),
				"params" => array(
									array(
											"type" => "ihc_print_text_vc",
											"custom_text" => __("Subscription Plan Shortcode", 'ihc'),
											'param_name' => 'param1',
									)						
								  ),
				'show_settings_on_create' => false,
		)
);


//the locker
vc_map(
		array(
				'admin_enqueue_js' => IHC_URL . 'admin/assets/js/back_end.js',
				"name" => 'Membership Pro - ' . __('Locker', 'ihc'),
				"base" => 'ihc-hide-content',
				"icon" => 'ihc_vc_logo',
				"description" => __('Locker', 'ihc'),
				"class" => 'ihc-hide-content',
				"category" => __('Content', 'js_composer'),
				"params" => array(
									array(
											"type" => 'ihc_custom_dropdown',
											"heading" => __('Type:', 'ihc'),
											"label" => '',
											"param_name" => 'ihc_mb_type',
											"values" => array('show' => __('Show Content Only For', 'ihc'), 'block' => __('Hide Content Only For', 'ihc') ),
											'value' => '',
									),	
									array(
											"type" => 'ihc_select_target_u',
											"heading" => __('Target Users:', 'ihc'),
											"label" => '',
											"param_name" => 'ihc_mb_who',
											'value' => '',
									),
									array(
											"type" => 'ihc_select_locker',
											"heading" => __('Choose Locker:', 'ihc'),
											"label" => '',
											"param_name" => 'ihc_mb_template',
											"value" => '',
									),
									array(
											"type" => "textarea_html",
											"holder" => "div",
											"class" => "",
											"heading" => __( "Content", "js_composer" ),
											"param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
											"value" => __( "<p>I am test text block. Click edit button to change this text.</p>", "js_composer" ),
											"description" => __( "Enter your content.", "js_composer" )
									)
								)				
		)
);


///vc functions

function ihc_print_text_vc_settings_field($settings, $value){
	return $settings['custom_text'];
}
add_shortcode_param('ihc_print_text_vc', 'ihc_print_text_vc_settings_field');

function ihc_select_target_u_settings_field($settings, $value){
	$posible_values = array( 'all'=>__('All', 'ihc'), 'reg'=>__('Registered Users', 'ihc'), 'unreg'=>__('Unregistered Users', 'ihc') );
	$levels = get_option('ihc_levels');
	if ($levels){
		foreach($levels as $id=>$level){
			$posible_values[$id] = $level['name'];
		}
	}
	$str = '';
	$str .= '<select id="ihc-change-target-user-set" onChange="ihc_writeTagValue(this, \'#ihc_mb_who-hidden-vc\', \'#ihc_tags_field_vc\', \'ihc_select_tag_vc_\' );" style="width: auto; min-width:80%;">';
	foreach ($posible_values as $k=>$v){
		$str .= '<option value="'.$k.'" >'.$v.'</option>';
	}
	$str .= '</select>';
	
	$str .= '<div id="ihc_tags_field_vc">';
	
	if ($value){
		if (strpos($value, ',')!==FALSE){
			$values = explode(',', $value);
		} else {
			$values[] = $value;
		}
		if (count($values)){
			foreach ($values as $val){
				if (isset($posible_values[$val])){
					$str .= '<div id="ihc_select_tag_vc_'.$val.'" class="ihc-tag-item">';
					$str .= $posible_values[$val];
					$str .= '<div class="ihc-remove-tag" onclick="ihcremoveTag(\''.$val.'\', \'#ihc_select_tag_vc_\', \'#ihc_mb_who-hidden-vc\');" title="'.__('Removing tag', 'ihc').'">x</div>';
					$str .= '</div>';
				}					
	        }                        	
	    }
		$str .= '<div class="ihc-clear"></div>';
	}
	$str .= '</div>';
	$str .= '<input type="hidden" value="'.$value.'" name="'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field" id="ihc_mb_who-hidden-vc" />';
	return $str;		
}
add_shortcode_param('ihc_select_target_u', 'ihc_select_target_u_settings_field');

function ihc_select_locker_settings_field($settings, $value){
	$str = '';
	$lockers = ihc_return_meta('ihc_lockers');
	if ($lockers){
		$str .= '<select value="'.$value.'" name="'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field" onChange="ihc_locker_preview_wi(this.value, 0);">';
		$str .= '<option value="-1">...</option>';
		foreach ($lockers as $k=>$v){
			$selected = ($k==$value) ? 'selected' : '';
			$str .= '<option value="'.$k.'" '.$selected.'>'.$v['ihc_locker_name'].'</option>';
		}
		$str .= '</select>';							
	} else {
		$str .= __('No Lockers Available.', 'ihc');
	}
	return $str;
}
add_shortcode_param('ihc_select_locker', 'ihc_select_locker_settings_field');

function ihc_custom_dropdown_settings_field($settings, $value){
	$str = '';
	$str .= '<select value="'.$value.'" name="'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field">';
	foreach ($settings['values'] as $k=>$v){
		$selected = ($k==$value) ? 'selected' : '';
		$str .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
	}
	$str .= '</select>';	
	return $str;
}
add_shortcode_param('ihc_custom_dropdown', 'ihc_custom_dropdown_settings_field');