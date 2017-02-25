<?php
if (!class_exists('IhcMailServices')){
	class IhcMailServices{
		public $dir_path = '';
	
		public function indeed_getResponse($api_key, $token, $e_mail, $full_name=''){
			if (!class_exists('jsonRPCClient')){
				require_once $this->dir_path . '/email_services/getresponse/jsonRPCClient.php';				
			}
			$api = new jsonRPCClient('http://api2.getresponse.com');
			$args = array(
					'campaign'  => $token,
					'email' => $e_mail,
			);
			if(!empty($full_name)) $args['name'] = $full_name;
			$res = $api->add_contact($api_key, $args);
			if($res) return 1;
			else return 0;
		}
	
		public function indeed_mailChimp($mailchimp_api, $mailchimp_id_list, $e_mail, $first_name='', $last_name=''){
			if ($mailchimp_api !='' && $mailchimp_id_list !=''){
				if (!class_exists('MailChimp')){
					require_once $this->dir_path . '/email_services/mailchimp/MailChimp.php';
				}				
	
				$MailChimp = new MailChimp($mailchimp_api);
	
				$result = $MailChimp->call('lists/subscribe', array(
						'id'                => $mailchimp_id_list,
						'email'             => array('email'=>$e_mail),
						'double_optin'      => 0,
						'update_existing'   => true,
						'replace_interests' => false,
						'send_welcome'      => 0,
						'merge_vars'        => array('FNAME'=>$first_name, 'LNAME'=>$last_name),
				));
	
				if(!empty($result['email']) && !empty($result['euid']) && !empty($result['leid'])) {
					return 1;
				} else {
					return 0;
				}
			}
		}
	
		public function indeed_campaignMonitor($listId, $apiID, $e_mail, $full_name=''){
			if (!class_exists('CS_REST_Subscribers')){
				require_once $this->dir_path .'/email_services/campaignmonitor/csrest_subscribers.php';
			}			
			$obj = new CS_REST_Subscribers($listId, $apiID);
			$args = array(
					'EmailAddress' => $e_mail,
					'Resubscribe' => true,
			);
			if(!empty($full_name)) $args['Name'] = $full_name;
			$result = $obj->add($args);
			if ($result->was_successful()) return 1;
			else return 0;
		}
	
		public function indeed_iContact( $apiUser, $appId, $apiPass, $listId ,$e_mail, $first_name='', $last_name=''){
			if (!class_exists('iContactApi')){
				require_once $this->dir_path .'/email_services/icontact/iContactApi.php';
			}			
			iContactApi::getInstance()->setConfig(array(
					'appId' => $appId,
					'apiPassword' => $apiPass,
					'apiUsername' => $apiUser,
			));
			$oiContact = iContactApi::getInstance();
			$res1 = $oiContact->addContact($e_mail, null, null, $first_name, $last_name, null, null, null, null, null, null, null, null, null);
			if ($res1->contactId) {
				if($oiContact->subscribeContactToList($res1->contactId, $listId, 'normal')) return 1;
				else return 0;
			}else return 0;
		}
	
		public function indeed_constantContact($apiUser, $apiPass, $listId, $e_mail, $first_name='', $last_name=''){
			if (!class_exists('cc')){
				require_once $this->dir_path .'/email_services/constantcontact/class.cc.php';
			}			
			$extra_fields['FirstName'] = $first_name;
			$extra_fields['LastName'] = $last_name;
			$cc = new cc($apiUser, $apiPass);
			$contact = $cc->query_contacts($e_mail);
			if ($contact){
				$status = $cc->update_contact($contact['id'], $e_mail, $listId, $extra_fields);
				if($status) return 1;
				else return 0;
			}
			else{
				$new_id = $cc->create_contact($e_mail, $listId, $extra_fields);
				if($new_id) return 1;
				else return 0;
			}
				
		}
	
		public function indeed_wysija_subscribe( $listId, $e_mail, $first_name='', $last_name='' ){
			$user_data = array(
					'email' => $e_mail,
					'firstname' => $first_name,
					'lastname' => $last_name);
			$data = array(
					'user' => $user_data,
					'user_list' => array('list_ids' => array($listId))
			);
			$helper = &WYSIJA::get('user', 'helper');
			if($helper->addSubscriber($data)) return 1;
			else return 0;
		}
	
		public function indeed_returnWysijaList(){
			//returning list from mail poet
			$list = array();
			if(class_exists('WYSIJA')){
				$get_list = &WYSIJA::get('list','model');
				@$lists = $get_list->get(array('name','list_id'),array('is_enabled'=>1));
				if(isset($lists) && count($lists)>0){
					foreach($lists as $value){
						$list_arr[$value['list_id']] = $value['name'];
					}
				}
			}
			if(!isset($list_arr) || count($list_arr) == 0) return FALSE;
			else return $list_arr;
		}
	
		public function indeed_myMailSubscribe( $listId, $e_mail ){
	      	$userdata = array(
					'firstname' => '',
					'lastname' => ''
				    );
	        if (function_exists('mymail_subscribe')){
				$return = mymail_subscribe( $e_mail, $userdata, array($listId), 0);
				if ( !is_wp_error($return) ) return 1;
	            else return 0;
	        }else return 0;
		}
	
		public function indeed_getMyMailLists(){
		        //return mymail lists
	    	if (function_exists('mymail') ){
	    		//my mail >=2
	    		$lists = mymail('lists')->get();
	    		if(isset($lists) && count($lists)>0){
	    			foreach($lists as $v){
	    				if(isset($v->slug) && isset($v->name) ) $list_arr[$v->slug] = $v->name;
	    			}
	    			return $list_arr;
	    		}
	    		return FALSE;
	    	}else{
		    	$args = array(
		    		'orderby'       => 'name',
		    		'order'         => 'ASC',
		    		'hide_empty'    => false,
		    		'exclude'       => array(),
		    		'exclude_tree'  => array(),
		    		'include'       => array(),
		    		'fields'        => 'all',
		    		'hierarchical'  => true,
		    		'child_of'      => 0,
		    		'pad_counts'    => false,
		    		'cache_domain'  => 'core'
		    	);
		    	$lists = get_terms( 'newsletter_lists', $args );
		        if(isset($lists)){
		        	foreach($lists as $v){
		        	    if( isset($v->slug) && isset($v->name) ) $list_arr[$v->slug] = $v->name;
		        	}
		        	if (!isset($list_arr) || count($list_arr) == 0) $list_arr[0] = 'none';
		        	return $list_arr;
		        }else return 0;
	    	}
		}
	
		public function indeed_aWebberSubscribe( $consumer_key, $consumer_secret, $access_key, $access_secret, $aw_list, $e_mail, $full_name='' ){
			if (!class_exists('AWeberAPI')){
				require_once $this->dir_path .'/email_services/aweber/aweber_api.php';
			}			
			try {
				$aweber = new AWeberAPI($consumer_key, $consumer_secret);
				$account = $aweber->getAccount($access_key, $access_secret);
				$list = $account->loadFromUrl('/accounts/' . $account->id . '/lists/' . $aw_list);
				$subscriber = array(
						'email' => $e_mail,
						'ip' => $_SERVER['REMOTE_ADDR'],
				);
				if(!empty($full_name)) $subscriber['name'] = $full_name;
				$list->subscribers->create($subscriber);
				return 1;
			} catch (AWeberException $e){
				return 0;
			}
		}
	
		public function indeed_madMimi($username, $api_key, $listName, $e_mail, $first_name='', $last_name=''){
			if (!class_exists('MadMimi')){
				require_once $this->dir_path .'/email_services/madmimi/MadMimi.class.php';				
			}
			$mailer = new MadMimi( $username, $api_key );
			$user = array( 'email' => $e_mail,
					'firstName' => $first_name,
					'lastName' => $last_name,
					'add_list' => $listName,
			);
			If($mailer){
				$mailer->AddUser($user);
				return 1;
			}else return 0;
		}
	
	}	
}
