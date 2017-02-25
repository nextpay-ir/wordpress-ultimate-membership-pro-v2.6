<?php

if (!class_exists('DMSubscribe')) 
{
	class DMSubscribe
	{
		public function submitSubscribeForm( $form_id, $email, &$error_msg) {
			$post_data['subscriber_email'] = $email;
			$post_data['form_id'] = $form_id;
			 
			$ch = curl_init( 'http://dm-mailinglist.com/subscribe?format=json' );
			
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data );
			 
			$result = curl_exec($ch);
			
			if ( $result === false ) {
				$error_msg = sprintf( "Connection failed: (%d) %s", curl_errno( $ch ), curl_error( $ch ) );
			}
			else if ( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) != 200 ) {
				$json = json_decode( $result, true );

				if ( $json === null ) {
					$error_msg = "Unable to decode response: $result";
				}
				else {
					$error_msg = $json['Message'];
				}
			}
			else {
				$success = true;
			}

			curl_close( $ch );
			
			return $success;
		}
	}
}

?>