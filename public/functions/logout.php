<?php 
//////////////LOGOUT
function ihc_do_logout($current_url){
	$url = get_option('ihc_general_logout_redirect');
	if ($url && $url!=-1){
		$link = get_permalink($url);
		if (!$link){
			$link = ihc_get_redirect_link_by_label($url);
		}
		if (!$link){
			$link = $current_url;
		}
	} else {
		//redirect to same page
		global $wp;
		$link = remove_query_arg( 'ihcaction', $current_url);
	}
	wp_clear_auth_cookie();
	do_action( 'wp_logout' );
	nocache_headers();
	wp_redirect( $link );
	exit();
}