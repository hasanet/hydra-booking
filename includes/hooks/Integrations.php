<?php
namespace HydraBooking\Hooks;
use HydraBooking\DB\Meeting;
class Integrations{

    public function __construct( ) { 
        add_action('hydra_booking/after_booking_completed', [$this, 'integrationsBookingToCompleted'], 10, 1);
        add_action('hydra_booking/after_booking_canceled', [$this, 'integrationsBookingToCanceled'], 10, 1);
        add_action('hydra_booking/after_booking_confirmed', [$this, 'integrationsBookingToConfirmed'], 10, 1);
    }

	public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/integration/zoho-api', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetAccessData'), 
        ));  
    }

	public function GetAccessData(){
		// Set the Client Data 

        if(isset($_GET['code']) && isset($_GET['state'])) {
			$host_id = $_GET['state'];

			$_tfhb_host_integration_settings =  is_array(get_user_meta($host_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($host_id, '_tfhb_host_integration_settings', true) : array(); 

			$client_id = !empty($_tfhb_host_integration_settings['zoho']['client_id']) ? $_tfhb_host_integration_settings['zoho']['client_id'] : '';
			$client_secret = !empty($_tfhb_host_integration_settings['zoho']['client_secret']) ? $_tfhb_host_integration_settings['zoho']['client_secret'] : '';
			$redirect_uri = !empty($_tfhb_host_integration_settings['zoho']['redirect_url']) ? $_tfhb_host_integration_settings['zoho']['redirect_url'] : '';
			$authorization_code = $_GET['code'];
			try { 
				
				$token_url = "https://accounts.zoho.com/oauth/v2/token";
				$post_fields = http_build_query([
					'grant_type' => 'authorization_code',
					'client_id' => $client_id,
					'client_secret' => $client_secret,
					'redirect_uri' => $redirect_uri,
					'code' => $authorization_code
				]);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $token_url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($ch);
				curl_close($ch);

				$result = json_decode($response, true);

				if (isset($result['error'])) {
					echo 'Error: ' . $result['error'];
				} else {
					$_tfhb_host_integration_settings['zoho']['access_token'] = $result['access_token'];
					$_tfhb_host_integration_settings['zoho']['refresh_token'] = $result['refresh_token'];
				}

				// The Zoho CRM API URL to get all modules
				$api_url = 'https://www.zohoapis.com/crm/v5/settings/modules';

				// Initialize cURL session
				$ch = curl_init();

				// Set the URL and other necessary options
				curl_setopt($ch, CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				// Set the headers, including the authorization token
				$headers = [
					'Authorization: Zoho-oauthtoken ' . $_tfhb_host_integration_settings['zoho']['access_token'],
					'Content-Type: application/json'
				];
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				// Execute the cURL session and fetch the response
				$response = curl_exec($ch);

				// Check for cURL errors
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}

				// Close the cURL session
				curl_close($ch);

				// Decode the JSON response
				$integration_modules = [];
				$response_data = json_decode($response, true);
				if (isset($response_data['modules'])) {
					// Loop through each module and print its name
					foreach ($response_data['modules'] as $module) {
						$integration_modules[] =  array(
							'name' =>  $module['module_name'],
							'value' => $module['api_name']
						);
					}
				}

				$_tfhb_host_integration_settings['zoho']['modules'] = json_encode($integration_modules);
				
				// save to user metadata
                update_user_meta($host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);
				
				// $redirect_url = get_site_url() . '/wp-admin/admin.php?page=hydra-booking#/hosts/profile/' . $host_id . '/integrations';
				// var_dump($redirect_url); exit();
                
                // wp_redirect($redirect_url);
                // wp_die();
			}

			catch(Exception $e) {
				echo $e->getMessage();
				exit(); 
			}
		}
    }

    // If booking Completed
    public function integrationsBookingToCompleted($booking){

        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($booking->meeting_id);

        $integrationsdata = !empty($MeetingData->integrations) ? json_decode($MeetingData->integrations, true) : array();
        if(!empty($integrationsdata)){
            foreach($integrationsdata as $hook){

                // integrations
                if( !empty($hook['webhook']) && 'Mailchimp'==$hook['webhook'] && !empty($hook['events']) && in_array("Booking Completed", $hook['events']) && !empty($hook['status']) ){
                    $this->tfhb_mailchimp_callback($booking, $hook, $MeetingData->host_id);
                }

				if( !empty($hook['webhook']) && 'FluentCRM'==$hook['webhook'] && !empty($hook['events']) && in_array("Booking Completed", $hook['events']) && !empty($hook['status']) ){
                    $this->tfhb_fluentcrm_callback($booking, $hook, $MeetingData->host_id);
                }

				if( !empty($hook['webhook']) && 'ZohoCRM'==$hook['webhook'] && !empty($hook['events']) && in_array("Booking Completed", $hook['events']) && !empty($hook['status']) ){
                    $this->tfhb_zohocrm_callback($booking, $hook, $MeetingData->host_id);
                }

            }
        }
        
    }
	
    // If booking Cancel
    public function integrationsBookingToCanceled($booking){

        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($booking->meeting_id);

        $integrationsdata = !empty($MeetingData->integrations) ? json_decode($MeetingData->integrations, true) : array();
        if(!empty($integrationsdata)){
            foreach($integrationsdata as $hook){
                // integrations
                if( !empty($hook['events']) && in_array("Booking Canceled", $hook['events']) && !empty($hook['status']) ){
                    $this->tfhb_integrations_callback($booking, $hook);
                }
            }
        }
        
    }

    // If booking confirmed
    public function integrationsBookingToConfirmed($booking){

        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($booking->meeting_id);

        $integrationsdata = !empty($MeetingData->integrations) ? json_decode($MeetingData->integrations, true) : array();
        if(!empty($integrationsdata)){
            foreach($integrationsdata as $hook){

                // integrations
                if( !empty($hook['events']) && in_array("Booking Completed", $hook['events']) && !empty($hook['status']) ){
                    $this->tfhb_mailchimp_callback($booking, $hook);
                }
            }
        }
        
    }

    // Mailchimp Callback
    function tfhb_mailchimp_callback($booking, $hook, $host) {

        $_tfhb_host_integration_settings = !empty($host) ? get_user_meta($host, '_tfhb_host_integration_settings', true) : ''; 

        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        $api_key = !empty($_tfhb_integration_settings['mailchimp']['key']) ? $_tfhb_integration_settings['mailchimp']['key'] : '';
        $api_key = !empty($_tfhb_host_integration_settings['mailchimp']['key']) ? $_tfhb_host_integration_settings['mailchimp']['key'] : $api_key;

        if ( $api_key != '' ) {

			$response = $this->set_config( $api_key, 'ping' );
			$response = json_decode( $response );
			if ( isset( $response->health_status ) ) { //Display success message
				$response = $this->add_members( $api_key, $booking, $hook );
			} else {
				$this->mailchimlConnection = false;
			}
		}

        
		//Admin Option
		$body_request = isset( $hook['bodys'] ) ? $hook['bodys'] : '';

		// Define the data to send in the POST request
		$header_data = array();
		$body_data = array();
    }

	// FluentCRM Callback
	function tfhb_fluentcrm_callback($booking, $hook, $host){

		global $wpdb;
		// Check if table exists
		$subscriber_table_name = $wpdb->prefix . 'fc_subscribers';
		$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$subscriber_table_name'") == $subscriber_table_name;

		$subscriber_pivot_table_name = $wpdb->prefix . 'fc_subscriber_pivot';
		$pivot_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$subscriber_pivot_table_name'") == $subscriber_pivot_table_name;

		if ($table_exists) {
			// Table exists, prepare and insert data
			$first_name = ! empty( $booking->attendee_name ) ? $booking->attendee_name : '';
			$last_name = ! empty( $booking->attendee_last_name ) ? $booking->attendee_last_name : '';
			$email = ! empty( $booking->email ) ? $booking->email : '';

			// Prepare the data
			$data = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email
			);

			// Format for the data (to ensure proper SQL data types)
			$format = array(
				'%s',  // first_name is a string
				'%s',  // last_name is a string
				'%s',  // email is a string
			);
			// Insert data
			$inserted = $wpdb->insert($subscriber_table_name, $data, $format);
			$subscripber_id = $wpdb->insert_id;

			if($pivot_table_exists && !empty($hook['lists'])){
				// Prepare the data
				$list_data = array(
					'subscriber_id' => $subscripber_id,
					'object_id' => $hook['lists'],
					'object_type' => 'FluentCrm\App\Models\Lists'
				);

				// Format for the data (to ensure proper SQL data types)
				$list_format = array(
					'%d',  // subscriber_id is a integer
					'%d',  // object_id is a integer
					'%s',  // email is a string
				);
				// Insert data
				$listinserted = $wpdb->insert($subscriber_pivot_table_name, $list_data, $format);
			}
			if($pivot_table_exists && !empty($hook['tags'])){
				// Prepare the data
				$list_data = array(
					'subscriber_id' => $subscripber_id,
					'object_id' => $hook['tags'],
					'object_type' => 'FluentCrm\App\Models\Tag'
				);

				// Format for the data (to ensure proper SQL data types)
				$list_format = array(
					'%d',  // subscriber_id is a integer
					'%d',  // object_id is a integer
					'%s',  // email is a string
				);
				// Insert data
				$listinserted = $wpdb->insert($subscriber_pivot_table_name, $list_data, $format);
			}
		}

		

	}

	// ZohoCRM Callback
	function tfhb_zohocrm_callback($booking, $hook, $host) {
		$_tfhb_host_integration_settings =  is_array(get_user_meta($host, '_tfhb_host_integration_settings', true)) ? get_user_meta($host, '_tfhb_host_integration_settings', true) : array();
		$access_token = !empty($_tfhb_host_integration_settings['zoho']['access_token']) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
		$refresh_token = !empty($_tfhb_host_integration_settings['zoho']['refresh_token']) ? $_tfhb_host_integration_settings['zoho']['refresh_token'] : '';

		$access_token = $this->refreshToken($host);

		$extra_fields = !empty( $hook['bodys'] ) ? $hook['bodys'] : array();
		$data = array(
			"data" => array()
		);

		$temp_module_Data = array();

		foreach ( $extra_fields as $extra_field ) {
			$field_name = $extra_field['name'];
			$field_value = $extra_field['value'];
			$temp_module_Data[$field_name ] = $field_value;
		}

		$data['data'][] = $temp_module_Data;

		if(!empty($hook['modules'])){
			$api_url = 'https://www.zohoapis.com/crm/v5/'.$hook['modules'];

			$json_data = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
			$headers = [
				'Authorization: Zoho-oauthtoken ' . $access_token,
				'Content-Type: application/json'
			];
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
			$response = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);

		}
		
	}

    /* Mailchimp config set */
	private function set_config( $api_key = '', $path = '' ) {


		$server_prefix = explode( "-", $api_key );

		if ( ! isset( $server_prefix[1] ) ) {
			return;
		}
		$server_prefix = $server_prefix[1];

		$url = "https://$server_prefix.api.mailchimp.com/3.0/$path";

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		$headers = array(
			"Authorization: Bearer $api_key"
		);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		//for debug only!
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

		$resp = curl_exec( $curl );
		curl_close( $curl );

		return $resp;
	}

    /* Add members to mailchimp */
	public function add_members( $api_key, $booking, $hook ) {
   
		$subscriber_email = ! empty( $booking->email ) ? $booking->email : '';

		if ( $api_key != '' && $subscriber_email != '' ) {
			$server_prefix = explode( "-", $api_key );
			$server_prefix = $server_prefix[1];
			$subscriber_fname = ! empty( $booking->attendee_name ) ? $booking->attendee_name : '';
			$subscriber_lname = ! empty( $booking->attendee_last_name ) ? $booking->attendee_last_name : '';

			$extra_fields = !empty( $hook['bodys'] ) ? $hook['bodys'] : array();

			$extra_merge_fields = '';
			foreach ( $extra_fields as $extra_field ) {
                $field_name = $extra_field['name'];
                $field_value = isset($booking->$field_name) ? $booking->$field_name : ''; // Check if the property exists
                $extra_merge_fields .= '"' . $extra_field['value'] . '": "' . $field_value . '",';
            }
			$extra_merge_fields = trim( $extra_merge_fields, ',' );

			if ( $extra_merge_fields != '' ) {
				$extra_merge_fields = ',' . $extra_merge_fields;
			}
            // var_dump($extra_merge_fields); exit();
			$url = "https://$server_prefix.api.mailchimp.com/3.0/lists/" . $hook['audience'] . "/members";

			$curl = curl_init( $url );
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

			$headers = array(
				"Authorization: Bearer $api_key",
				"Content-Type: application/json",
			);
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

			//Mailchimp data
			$data = '{"email_address":"' . sanitize_email( $subscriber_email ) . '","status":"subscribed","merge_fields":{"FNAME": "' . sanitize_text_field( $subscriber_fname ) . '", "LNAME": "' . sanitize_text_field( $subscriber_lname ) . '"' . $extra_merge_fields . '},"vip":false,"location":{"latitude":0,"longitude":0}}';


			curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

			//for debug only!
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

			$resp = curl_exec( $curl );
			curl_close( $curl );
			// return $url; 
		}

	}

	// Refresh Token
	public function refreshToken($host){
		$_tfhb_host_integration_settings =  is_array(get_user_meta($host, '_tfhb_host_integration_settings', true)) ? get_user_meta($host, '_tfhb_host_integration_settings', true) : array();

		$client_id = !empty($_tfhb_host_integration_settings['zoho']['client_id']) ? $_tfhb_host_integration_settings['zoho']['client_id'] : '';
		$client_secret = !empty($_tfhb_host_integration_settings['zoho']['client_secret']) ? $_tfhb_host_integration_settings['zoho']['client_secret'] : '';
		$access_token = !empty($_tfhb_host_integration_settings['zoho']['access_token']) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
		$refresh_token = !empty($_tfhb_host_integration_settings['zoho']['refresh_token']) ? $_tfhb_host_integration_settings['zoho']['refresh_token'] : '';

		$url = "https://accounts.zoho.com/oauth/v2/token";
		$data = array(
			'grant_type' => 'refresh_token',
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'refresh_token' => $refresh_token
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$response_data = json_decode($response, true);
		
		if( !empty($response_data['access_token']) ){
			$_tfhb_host_integration_settings['zoho']['access_token'] = $response_data['access_token'];

			// save to user metadata
			update_user_meta($host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);

			return $response_data['access_token'];
		}


	}

}