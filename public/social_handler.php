<?php 
//first of all remove ihcaction, to prevent instatiate userAddEdit class
if (isset($_POST['ihcaction'])){
	unset($_POST['ihcaction']);
}

require_once '../../../../wp-load.php';
require_once IHC_PATH . 'utilities.php';
if(session_id() == '') {
	session_start();
}

if (!empty($_POST['sm_register'])){
	//===============================REGISTER
	if (!empty($_POST['sm_type'])){
		$_SESSION['sm_type'] = $_POST['sm_type'];
		unset($_POST['sm_type']);
	}
	//previous url
	if (isset($_POST['ihc_current_url'])){
		$_SESSION['ihc_current_url'] = $_POST['ihc_current_url'];
		unset($_POST['ihc_current_url']);
		$url_str = parse_url($_SESSION['ihc_current_url']);
		if (isset($url_str['query']['lid'])){
			$_SESSION['lid'] = $url_str['query']['lid'];
		}
	}
	//remove Submit
	if (isset($_POST['Submit'])){
		unset($_POST['Submit']);
	}
	unset($_POST['sm_register']);
	foreach ($_POST as $k=>$v){
		$_SESSION['ihc_register'][$k] = $v;
	}
	$_SESSION['sm_action'] = 'register';	
} else if (!empty($_GET['sm_login'])){
	//================================LOGIN
	$_SESSION['sm_type'] = $_GET['sm_login'];	
	$_SESSION['ihc_current_url'] = $_GET['ihc_current_url'];
	$_SESSION['sm_action'] = 'login';
	if (!empty($_GET['is_locker'])){
		$_SESSION['is_locker'] = 1;
	}
} else if (!empty($_GET["reg_ext_usr"])){
	//=================================REGISTER SOCIAL FOR EXISTING USER
	global $current_user;
	$_SESSION['sm_action'] = 'update_user';
	$_SESSION['ihc_uid'] = $current_user->ID;
	$_SESSION['sm_type'] = $_GET['reg_ext_usr'];
	$_SESSION['ihc_current_url'] = $_GET['ihc_current_url'];
}

//========================= CONFIG FOR HYBRIDAUTH
$config = array(
		"base_url" => IHC_URL . "classes/hybrid_auth/hybridauth/",
		"providers" => array (
				"OpenID" => array(
						"enabled" => true
				),
		)
);
//getting settings for current provider
$sm_options = ihc_return_meta_arr($_SESSION['sm_type']);
switch ($_SESSION['sm_type']){
	case 'fb':
		$config['providers']['Facebook'] = array(
												   "enabled" => TRUE,
												   "keys" => array(
												   		"id" => $sm_options['ihc_fb_app_id'],
												   		"secret" => $sm_options['ihc_fb_app_secret'],		
												   ),
		);
		break;
	case 'tw':
		$config['providers']['Twitter'] = array(
													"enabled" => TRUE,
												    "keys" => array(
												   		"key" => $sm_options['ihc_tw_app_key'],
												   		"secret" => $sm_options['ihc_tw_app_secret'],	
												    ),
													"includeEmail" => FALSE													
		);		
		break;
	case 'in':
		$config['providers']['LinkedIn'] = array(
				"enabled" => TRUE,
				"keys" => array(
								"key" => $sm_options['ihc_in_app_key'],
								"secret" => $sm_options['ihc_in_app_secret'],
				)
		);		
		break;
	case 'tbr':
			$config['providers']['Tumblr'] = array(
				"enabled" => TRUE,
				"keys" => array(
					"key" => $sm_options['ihc_tbr_app_key'],
					"secret" => $sm_options['ihc_tbr_app_secret'],
				)
			);
		break;		
	case 'ig':
		$config['providers']['Instagram'] = array(
			"enabled" => TRUE,
			"keys" => array(
					"id" => $sm_options['ihc_ig_app_id'],
					"secret" => $sm_options['ihc_ig_app_secret'],
				)
			);
		break;	
	case 'vk':
		$config['providers']['Vkontakte'] = array(
				"enabled" => TRUE,
				"keys" => array(
						"id" => $sm_options['ihc_vk_app_id'],
						"secret" => $sm_options['ihc_vk_app_secret'],
				)
		);		
		break;
	case 'goo':
		$config['providers']['Google'] = array(
				"enabled" => TRUE,
				"keys" => array(
						"id" => $sm_options['ihc_goo_app_id'],
						"secret" => $sm_options['ihc_goo_app_secret'],
				)
		);		
		break;		
}

require_once IHC_PATH . "/classes/hybrid_auth/hybridauth/Hybrid/Auth.php";

try {
	//======================== GETTING DATA FROM SOCIAL MEDIA
	switch ($_SESSION['sm_type']){
		case 'fb':
			$provider = "Facebook";
			break;
		case 'tw':
			$provider = "Twitter";
			break;
		case 'in':
			$provider = "LinkedIn";
			break;
		case 'tbr':
			$provider = "Tumblr";
			break;
		case 'ig':
			$provider = "Instagram";
			break;		
		case 'vk':
			$provider = "Vkontakte";
			break;
		case 'goo':
			$provider = "Google";
			break;			
	}	

	$hybridauth = new Hybrid_Auth( $config );
	$data = Hybrid_Auth::authenticate( $provider, $config );
	$user_profile = $data->getUserProfile();

	if ($_SESSION['sm_type']=='tbr'){
		$user_profile->identifier = md5($user_profile->identifier);//identifier for tumblr is the profile url, so we made the md5 of it
	}
	
	if ($_SESSION['sm_action']=='register'){
		//==================================== REGISTER
		//SET COOKIE
		if (!empty($_SESSION['ihc_register'])){
			$data_to_return = $_SESSION['ihc_register'];
		}
		//username
		if (empty($data_to_return['user_login'])){
			if (!empty($user_profile->username)){
				$data_to_return['user_login'] = $user_profile->username;
			} else if (!empty($user_profile->email)){
				$data_to_return['user_login'] = $user_profile->email;
			} else if (!empty($user_profile->displayName)){
				$data_to_return['user_login'] = $user_profile->displayName;
			}
		}
		//first name
		if (!empty($user_profile->firstName)){
			$data_to_return['first_name'] = $user_profile->firstName;
		}
		//last name
		if (!empty($user_profile->lastName)){
			$data_to_return['last_name'] = $user_profile->lastName;
		}
		//email
		if (!empty($user_profile->email)){
			$data_to_return['user_email'] = $user_profile->email;
			$data_to_return['confirm_email'] = $user_profile->email;
		}
		//avatar
		if (!empty($user_profile->photoURL)){
			$data_to_return['ihc_avatar'] = $user_profile->photoURL;
		}
		//city
		if (!empty($user_profile->city)){
			$data_to_return['city'] = $user_profile->city;
		}
		//county
		if (!empty($user_profile->county)){
			$data_to_return['county'] = $user_profile->county;
		}
		//phone
		if (!empty($user_profile->phone)){
			$data_to_return['phone'] = $user_profile->phone;
		}
		//age
		if (!empty($user_profile->age)){
			$data_to_return['age'] = $user_profile->age;
		}
		setcookie('ihc_register', serialize($data_to_return), time()+3600, COOKIEPATH, COOKIE_DOMAIN, false);

		//REDIRECT
		$url = urldecode($_SESSION['ihc_current_url']);
		// social media type = social media id (ex. fb=*************)
		$url = add_query_arg(array("ihc_" . $_SESSION['sm_type'] => $user_profile->identifier), $url);
		//level id
		if (!empty($_SESSION['lid'])){
			$url = add_query_arg(array('lid'=>$_SESSION['lid']), $url);
		}
		wp_redirect($url);
		exit;		
	} else if ($_SESSION['sm_action']=='login'){
		//=========================================== LOGIN
		if (!function_exists('ihc_login_social')){
			require_once IHC_PATH . 'public/functions/login.php';
		}
		$arr['sm_type'] = $_SESSION['sm_type'];
		$arr['sm_uid'] = $user_profile->identifier;
		$arr['url'] = $_SESSION['ihc_current_url'];
		if (!empty($_SESSION['is_locker'])){
			$arr['is_locker'] = 1;
		}		
		ihc_login_social($arr);
	} else if ($_SESSION['sm_action']=='update_user'){
		//=========================================== UPDATE USER
		if (isset($_SESSION['ihc_uid']) && !empty($_SESSION['sm_type']) && !empty($user_profile->identifier)){
			update_user_meta($_SESSION['ihc_uid'], 'ihc_' . $_SESSION['sm_type'], $user_profile->identifier);
		}
		if (!empty($_SESSION['ihc_current_url'])){
			$url = urldecode($_SESSION['ihc_current_url']);
		} else {
			$url = home_url();
		} 
		wp_redirect($url);
		exit;			
	}
} catch ( Exception $e ){

}
///if nothing happens until here, redirect to home
if (!empty($_SESSION['ihc_current_url'])){
	$url = urldecode($_SESSION['ihc_current_url']);
} else {
	$url = home_url();
}
wp_redirect($url);
exit;
