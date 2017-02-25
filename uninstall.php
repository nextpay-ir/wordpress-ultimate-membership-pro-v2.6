<?php 

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
include plugin_dir_path(__FILE__).'utilities.php';

$values = array( 'payment', 'payment_nextpay', 'payment_bank_transfer',
				 'opt_in', 'login', 'login-messages', 'general-defaults', 'general-msg', 'general-captcha', 'notifications', 'extra_settings',
				 'account_page', 'fb', 'tw', 'in', 'tbr', 'ig', 'vk', 'goo', 'social_media', 'general-subscription', 'register', 'register-msg', 
				 'register-custom-fields' );

foreach ($values as $value){
	$data = ihc_return_meta_arr($value, true);
	foreach ($data as $k=>$v){
		delete_option($k);
	}	
}

delete_option('ihc_levels');//delete the levels
delete_option('ihc_lockers');//delete the lockers

//delete table indeed_members_payments
global $wpdb;
$tables = array( $wpdb->prefix . "indeed_members_payments", 
				 $wpdb->prefix . "ihc_user_levels", 
				 $wpdb->prefix . "ihc_debug_payments", 
				 $wpdb->prefix . "ihc_notifications",
				 $wpdb->prefix . "ihc_coupons");
foreach ($tables as $table){
	$wpdb->query("DROP TABLE IF EXISTS $table;");
}

//delete user levels
$users_obj = new WP_User_Query(array(
		'meta_query' => array(
				'relation' => 'OR',
				array(
						'key' => $wpdb->get_blog_prefix() . 'capabilities',
						'value' => 'subscriber',
						'compare' => 'like'
				),
				array(
						'key' => $wpdb->get_blog_prefix() . 'capabilities',
						'value' => 'pending_user',
						'compare' => 'like'
				)
		)
));
$users = $users_obj->results;
if (!empty($users)){
	foreach ($users as $user){
		delete_user_meta($user->data->ID, 'ihc_user_levels');
	}	
}