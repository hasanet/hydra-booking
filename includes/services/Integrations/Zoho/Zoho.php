<?php
namespace HydraBooking\Services\Integrations\Zoho;

use HydraBooking\DB\Meeting;
class Zoho {

	public function __construct() {
		add_action( 'hydra_booking/after_booking_completed', array( $this, 'integrationsBookingToCompleted' ), 10, 1 );
		add_action( 'hydra_booking/after_booking_canceled', array( $this, 'integrationsBookingToCanceled' ), 10, 1 );
		add_action( 'hydra_booking/after_booking_confirmed', array( $this, 'integrationsBookingToConfirmed' ), 10, 1 );
	}

	public function create_endpoint() {
		register_rest_route(
			'hydra-booking/v1',
			'/integration/zoho-api',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'GetAccessData' ),
			)
		);
	}

	public function GetAccessData() {
		// Set the Client Data

		if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) {
			$host_id = $_GET['state'];

			$_tfhb_host_integration_settings = is_array( get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) : array();

			$client_id          = ! empty( $_tfhb_host_integration_settings['zoho']['client_id'] ) ? $_tfhb_host_integration_settings['zoho']['client_id'] : '';
			$client_secret      = ! empty( $_tfhb_host_integration_settings['zoho']['client_secret'] ) ? $_tfhb_host_integration_settings['zoho']['client_secret'] : '';
			$redirect_uri       = ! empty( $_tfhb_host_integration_settings['zoho']['redirect_url'] ) ? $_tfhb_host_integration_settings['zoho']['redirect_url'] : '';
			$authorization_code = $_GET['code'];
			try {

				$token_url   = 'https://accounts.zoho.com/oauth/v2/token';
				$post_fields = http_build_query(
					array(
						'grant_type'    => 'authorization_code',
						'client_id'     => $client_id,
						'client_secret' => $client_secret,
						'redirect_uri'  => $redirect_uri,
						'code'          => $authorization_code,
					)
				);

				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $token_url );
				curl_setopt( $ch, CURLOPT_POST, true );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_fields );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

				$response = curl_exec( $ch );
				curl_close( $ch );

				$result = json_decode( $response, true );

				if ( isset( $result['error'] ) ) {
					echo 'Error: ' . esc_attr($result['error']);
				} else {
					$_tfhb_host_integration_settings['zoho']['access_token']  = $result['access_token'];
					$_tfhb_host_integration_settings['zoho']['refresh_token'] = $result['refresh_token'];
				}

				// The Zoho CRM API URL to get all modules
				$api_url = 'https://www.zohoapis.com/crm/v5/settings/modules';

				// Initialize cURL session
				$ch = curl_init();

				// Set the URL and other necessary options
				curl_setopt( $ch, CURLOPT_URL, $api_url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

				// Set the headers, including the authorization token
				$headers = array(
					'Authorization: Zoho-oauthtoken ' . $_tfhb_host_integration_settings['zoho']['access_token'],
					'Content-Type: application/json',
				);
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

				// Execute the cURL session and fetch the response
				$response = curl_exec( $ch );

				// Check for cURL errors
				if ( curl_errno( $ch ) ) {
					echo 'Error:' . esc_html(curl_error( $ch ));
				}

				// Close the cURL session
				curl_close( $ch );

				// Decode the JSON response
				$integration_modules = array();
				$response_data       = json_decode( $response, true );
				if ( isset( $response_data['modules'] ) ) {
					// Loop through each module and print its name
					foreach ( $response_data['modules'] as $module ) {
						$integration_modules[] = array(
							'name'  => $module['module_name'],
							'value' => $module['api_name'],
						);
					}
				}

				$_tfhb_host_integration_settings['zoho']['modules'] = json_encode( $integration_modules );

				// save to user metadata
				update_user_meta( $host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings );

				// $redirect_url = get_site_url() . '/wp-admin/admin.php?page=hydra-booking#/hosts/profile/' . $host_id . '/integrations';
				// var_dump($redirect_url); exit();

				// wp_redirect($redirect_url);
				// wp_die();
			} catch ( Exception $e ) {
				echo esc_html($e->getMessage());
				exit();
			}
		}
	}

	// If booking Completed
	public function integrationsBookingToCompleted( $booking ) {

		// Get Meeting
		$meeting     = new Meeting();
		$MeetingData = $meeting->get( $booking->meeting_id );

		$integrationsdata = ! empty( $MeetingData->integrations ) ? json_decode( $MeetingData->integrations, true ) : array();
		if ( ! empty( $integrationsdata ) ) {
			foreach ( $integrationsdata as $hook ) {

				// integrations
				if ( ! empty( $hook['webhook'] ) && 'ZohoCRM' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Completed', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_zohocrm_callback( $booking, $hook, $MeetingData->host_id );
				}
			}
		}
	}

	// If booking Cancel
	public function integrationsBookingToCanceled( $booking ) {

		// Get Meeting
		$meeting     = new Meeting();
		$MeetingData = $meeting->get( $booking->meeting_id );

		$integrationsdata = ! empty( $MeetingData->integrations ) ? json_decode( $MeetingData->integrations, true ) : array();
		if ( ! empty( $integrationsdata ) ) {
			foreach ( $integrationsdata as $hook ) {
				// integrations
				if ( ! empty( $hook['webhook'] ) && 'ZohoCRM' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Canceled', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_zohocrm_callback( $booking, $hook, $MeetingData->host_id );
				}
			}
		}
	}

	// If booking confirmed
	public function integrationsBookingToConfirmed( $booking ) {

		// Get Meeting
		$meeting     = new Meeting();
		$MeetingData = $meeting->get( $booking->meeting_id );

		$integrationsdata = ! empty( $MeetingData->integrations ) ? json_decode( $MeetingData->integrations, true ) : array();
		if ( ! empty( $integrationsdata ) ) {
			foreach ( $integrationsdata as $hook ) {

				// integrations
				if ( ! empty( $hook['webhook'] ) && 'ZohoCRM' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Completed', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_zohocrm_callback( $booking, $hook, $MeetingData->host_id );
				}
			}
		}
	}

	// ZohoCRM Callback
	function tfhb_zohocrm_callback( $booking, $hook, $host ) {
		$_tfhb_host_integration_settings = is_array( get_user_meta( $host, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $host, '_tfhb_host_integration_settings', true ) : array();
		$access_token                    = ! empty( $_tfhb_host_integration_settings['zoho']['access_token'] ) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
		$refresh_token                   = ! empty( $_tfhb_host_integration_settings['zoho']['refresh_token'] ) ? $_tfhb_host_integration_settings['zoho']['refresh_token'] : '';

		$access_token = $this->refreshToken( $host );

		$extra_fields = ! empty( $hook['bodys'] ) ? $hook['bodys'] : array();
		$data         = array(
			'data' => array(),
		);

		$temp_module_Data = array();

		foreach ( $extra_fields as $extra_field ) {
			$field_name                      = $extra_field['name'];
			$field_value                     = $extra_field['value'];
			$temp_module_Data[ $field_name ] = $field_value;
		}

		$data['data'][] = $temp_module_Data;

		if ( ! empty( $hook['modules'] ) ) {
			$api_url = 'https://www.zohoapis.com/crm/v5/' . $hook['modules'];

			$json_data = json_encode( $data );
			$ch        = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $api_url );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

			$headers = array(
				'Authorization: Zoho-oauthtoken ' . $access_token,
				'Content-Type: application/json',
			);

			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

			$response = curl_exec( $ch );
			if ( curl_errno( $ch ) ) {
				echo 'Error:' . esc_html(curl_error( $ch ));
			}
			curl_close( $ch );

		}
	}

	// Refresh Token
	public function refreshToken( $host ) {
		$_tfhb_host_integration_settings = is_array( get_user_meta( $host, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $host, '_tfhb_host_integration_settings', true ) : array();

		$client_id     = ! empty( $_tfhb_host_integration_settings['zoho']['client_id'] ) ? $_tfhb_host_integration_settings['zoho']['client_id'] : '';
		$client_secret = ! empty( $_tfhb_host_integration_settings['zoho']['client_secret'] ) ? $_tfhb_host_integration_settings['zoho']['client_secret'] : '';
		$access_token  = ! empty( $_tfhb_host_integration_settings['zoho']['access_token'] ) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
		$refresh_token = ! empty( $_tfhb_host_integration_settings['zoho']['refresh_token'] ) ? $_tfhb_host_integration_settings['zoho']['refresh_token'] : '';

		$url  = 'https://accounts.zoho.com/oauth/v2/token';
		$data = array(
			'grant_type'    => 'refresh_token',
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'refresh_token' => $refresh_token,
		);

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded' ) );

		$response = curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			echo 'Error:' . esc_html(curl_error( $ch ));
		}
		curl_close( $ch );

		$response_data = json_decode( $response, true );

		if ( ! empty( $response_data['access_token'] ) ) {
			$_tfhb_host_integration_settings['zoho']['access_token'] = $response_data['access_token'];

			// save to user metadata
			update_user_meta( $host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings );

			return $response_data['access_token'];
		}
	}
}
