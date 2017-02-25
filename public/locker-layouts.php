<?php 
function ihc_print_locker_template( $id=false, $meta_arr=false, $preview=false ){
	//function that return html template for an id
	$str = '';
	if( $id && $id!=-1 ){
		$meta_arr = ihc_return_meta('ihc_lockers', $id);//gettings metas for id
	}

	if($meta_arr){
		$login = '';
		if ($meta_arr['ihc_locker_login_form']){
			$meta_arr_login = ihc_return_meta_arr('login');//standard login form settings
			
			if ($meta_arr['ihc_locker_additional_links']){
				$meta_arr_login['ihc_login_remember_me'] = 0;
				$meta_arr_login['ihc_login_register'] = 1;
				$meta_arr_login['ihc_login_pass_lost'] = 1;
			} else {
				$meta_arr_login['ihc_login_remember_me'] = 0;
				$meta_arr_login['ihc_login_register'] = 0;
				$meta_arr_login['ihc_login_pass_lost'] = 0;
			}
			$meta_arr_login['ihc_login_template'] = 'ihc-login-template-7';//no template for login
			if (isset($meta_arr['ihc_locker_login_template']) && $meta_arr['ihc_locker_login_template']){
				$meta_arr_login['ihc_login_template'] = $meta_arr['ihc_locker_login_template'];
			}				
			
			if ($preview){
				$meta_arr_login['preview'] = true; 
			}
			
			if (!empty($meta_arr["ihc_locker_display_sm"])){
				$meta_arr_login["ihc_login_show_sm"] = TRUE;
			} else {
				$meta_arr_login["ihc_login_show_sm"] = FALSE;
			}
			
			$meta_arr_login['is_locker'] = TRUE;
			
			$login = ihc_print_form_login($meta_arr_login);
			
		} else if ($meta_arr['ihc_locker_additional_links']){
			$login = ihc_print_links_login();
		}
		$meta_arr['ihc_locker_custom_content'] = ihc_format_str_like_wp($meta_arr['ihc_locker_custom_content']);
		$meta_arr['ihc_locker_custom_content'] = stripslashes($meta_arr['ihc_locker_custom_content']);
		$meta_arr['ihc_locker_custom_content'] = htmlspecialchars_decode($meta_arr['ihc_locker_custom_content']);
		
		$str = ihc_locker_layout($meta_arr['ihc_locker_template'], $login, $meta_arr['ihc_locker_custom_content']);
		$str = '<div class="ihc-locker-wrap">'.$str.'</div>';
		

		if($meta_arr['ihc_locker_custom_css']){
			$str = '<style>' .$meta_arr['ihc_locker_custom_css'] . '</style>' . $str;
		}
	}
	return $str;//if something goes wrong return blank string
}


function ihc_locker_layout($template, $login, $lock_msg){
	$content = '';
	switch($template){
		case 1:
			//Default
			$style = 'max-width:640px; margin:auto; ';
			$content = "<div style='$style' class='ihc_locker_1'>
							<div>"
								. $lock_msg
								. "</div>"
								. $login
						. "</div>";
		break;

		case 2:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_2'>"
			. "<div class='lock_content'>"
			. $lock_msg
			. "</div>"
			. "<div class='lock_buttons'>"
			. $login
			. "</div>"
			. "</div>";
		break;
			
		case 3:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_3'>"
							. "<div  class='lk_wrapper'></div>"
							. "<div class='lock_content'>"
							. $lock_msg
							. "</div>"
							. "<div class='lock_buttons'>"
							. $login
							. "</div>"
						. "</div>";
		break;

		case 4:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_4'>"
							. "<div  class='lk_wrapper'></div>"
							. "<div class='lk_left_side'></div>"
							. "<div class='lock_content'>"
							. $lock_msg
							. "<div class='lock_buttons'>"
							. $login
							. "</div>"
							. "</div>"
					  . "</div>";
		break;

		case 5:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_5'>"
							. "<div class='lk_top_side'></div>"
							. "<div class='lock_content'>"
							. $lock_msg
							. "<div class='lock_buttons'>"
							. $login
							. "</div>"
							. "</div>"
						. "</div>";
		break;

		case 6:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_6'>"
							. "<div class='lk_top_side'></div>"
							. "<div class='lock_content'>"
							. $lock_msg
							. "<div class='lock_buttons'>"
							. $login
							. "</div>"
							. "</div>"
					. "</div>";
		break;

		case 7:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_7'>"
							. "<div class='lk_wrapper'></div>"
							. "<div class='lk_top_side'></div>"
							. "<div class='lock_content'>"
							. $lock_msg
							. "<div class='lock_buttons'>"
							. $login
							. "</div>"
					  		. "</div>"
					 . "</div>";
		break;

		case 8:
			$style = '';
			$content = "<div style='$style' class='ihc_locker_8'>"
							. "<div class='lk_top_side'></div>"
							. "<div class='lk_wrapper_top'></div>"
							. "<div class='lk_wrapper_bottom'></div>"
							. "<div class='lock_content'>"
							. $lock_msg
							. "</div>"
							. "<div class='lock_buttons'>"
							. $login
							. "</div>"
						. "</div>";
		break;
		
		default:
			$content = '';
		break;
	}
	return $content;
}