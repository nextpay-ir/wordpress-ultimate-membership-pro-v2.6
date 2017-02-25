<?php 
function ihc_filter_content($content){
	//GETTING POST META
	global $post;
	if($post==FALSE || !isset($post->ID)) return do_shortcode($content);
	$meta_arr = ihc_post_metas($post->ID);
	if($meta_arr['ihc_mb_block_type']=='redirect') return do_shortcode($content);///this extra check it's for ihc_list_posts_filter(), 

	///GETTING USER TYPE
	$current_user = ihc_get_user_type();
	if($current_user=='admin') return do_shortcode($content);//show always for admin

	// who can access the content
	if (isset($meta_arr['ihc_mb_who'])){
		if ($meta_arr['ihc_mb_who']!=-1 && $meta_arr['ihc_mb_who']!=''){
			$target_users = explode(',', $meta_arr['ihc_mb_who']);
		} else {
			$target_users = FALSE;
		}		
	}else{
		return do_shortcode($content);
	}
	
	////TESTING USER
	$block = ihc_test_if_must_block($meta_arr['ihc_mb_type'], $current_user, $target_users, @$post->ID);
	
	//IF NOT BLOCKING, RETURN THE CONTENT
	if(!$block){
		return do_shortcode($content);
	}
	
	// REPLACE CONTENT
	if (isset($meta_arr['ihc_replace_content'] )){
		$meta_arr['ihc_replace_content'] = stripslashes($meta_arr['ihc_replace_content']);
		$meta_arr['ihc_replace_content'] = htmlspecialchars_decode($meta_arr['ihc_replace_content']);
		$meta_arr['ihc_replace_content'] = ihc_format_str_like_wp($meta_arr['ihc_replace_content']);
		return do_shortcode($meta_arr['ihc_replace_content'] );
	}
	
	//IF SOMEHOW IT CAME UP HERE, RETURN CONTENT
	return do_shortcode($content);	
}

function ihc_print_message($content){
	/*
	 * print success message after register
	 * print update message on edit user page
	 * print the step 2. of registration (Subscription Plan)
	 * print the bank transfer message
	 */
	$str = '';
	 if (isset($_REQUEST['ihc_register'])){
		 switch ( $_REQUEST['ihc_register'] ){
			case 'create_message':
				$str .= '<div class="ihc-reg-success-msg">' . ihc_correct_text(get_option('ihc_register_success_meg')) . '</div>';
			break;
			case 'update_message':
				$str .= '<div class="ihc-reg-update-msg">' . ihc_correct_text(get_option('ihc_general_update_msg')) . '</div>';
			break;			
			case 'step2':
				$str .= ihc_user_select_level();
			break;
		 }
	 }
	 if (isset($_REQUEST['ihcbt']) && isset($_REQUEST['ihc_lid']) && isset($_REQUEST['ihc_uid']) ){
	 	$str .= ihc_print_bank_transfer_order($_REQUEST['ihc_uid'], $_REQUEST['ihc_lid']);
	 }
	 return do_shortcode($content) . $str;
}

//////////////// MENU FILTER
add_action( 'wp_nav_menu_objects', 'ihc_custom_menu_filter' );
//add_action( 'wp_nav_menu_args', 'ihc_custom_menu_filter' );
function ihc_custom_menu_filter($items){
	global $post;
	$current_user = ihc_get_user_type();
	if ($current_user=='admin'){
		return $items;//show all to admin
	}
	
	$arr = array();
	foreach ($items as $item) {
		$for = $item->ihc_mb_who_menu_type;
		$type = $item->ihc_menu_mb_type;
		if ($for!=-1 && $for!=''){
			$for = explode(',', $for);
		} else {
			$for = FALSE;
		}		
		$block = ihc_test_if_must_block($type, $current_user, $for, @$post->ID);//test user
		if (!$block){
			$arr[] = $item;
		}
	}
	return $arr;
}

////////LIST POSTS FILTER TO BLOCK THE CONTENT
add_filter('the_content', 'ihc_list_posts_filter');
function ihc_list_posts_filter($str){
	if( !is_single() && !is_page() ){
		return ihc_filter_content($str);
	}
	return $str;
}

//////////LIST POSTS - FILTER REMOVE POSTS THAT HAS A REDIRECT BLOCK 
add_filter('pre_get_posts', 'ihc_filter_query_list_posts', 999);
function ihc_filter_query_list_posts($query) {
	if (!$query->is_single && !$query->is_page) {
		$current_user = ihc_get_user_type();		
		if ($current_user=='admin') return $query;//show all to admin
		
		///
		global $wpdb;
		$post_list = $wpdb->get_results('SELECT DISTINCT post_id
											FROM '.$wpdb->prefix.'postmeta
											WHERE meta_key = "ihc_mb_block_type"
											AND meta_value = "redirect"
										;');
		if ($post_list && count($post_list)){
			$exclude = array();
			foreach ($post_list as $obj){
				if (isset($obj->post_id) && $obj->post_id){
					$result = $wpdb->get_results('
														SELECT * FROM '.$wpdb->prefix.'postmeta
														WHERE post_id = '.$obj->post_id.'
														AND ( meta_key = "ihc_mb_type"
														OR meta_key = "ihc_mb_who") LIMIT 2
												;');
					$for = false;
					$type = false;
					foreach ($result as $obj2){
						///
						if ($obj2->meta_key=='ihc_mb_who'){
							$for = $obj2->meta_value;
						} elseif ($obj2->meta_key=='ihc_mb_type'){
							$type = $obj2->meta_value;					
						}
					}
					
					if ($for!='' && $for!=-1 && $type){		
						$for = explode(',', $for);

						$block = ihc_test_if_must_block($type, $current_user, $for, $obj->post_id);
						if ($block){
							$exclude[] = $obj->post_id;
						}
						
					}	
					//				
				}
			}
			if (count($exclude)){
				$query->set('post__not_in', $exclude );
			}
		}
	}
	return $query;
}

function ihc_filter_print_bank_transfer_message($content = ''){
	$str = '';
	if (isset($_GET['ihc_lid'])){
		global $current_user;
		$str = ihc_print_bank_transfer_order($current_user->ID, $_GET['ihc_lid']);
	}
	return do_shortcode ($content) . $str;
}