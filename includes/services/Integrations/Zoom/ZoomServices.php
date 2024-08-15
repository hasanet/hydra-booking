<?php
namespace HydraBooking\Services\Integrations\Zoom;

class ZoomServices {

	public $account_id;
	protected $client_id;

	protected $client_secret;


	protected $access_token;

	public $revokeUrl = 'https://zoom.us/oauth/revoke';

	public $tokenUrl = 'https://zoom.us/oauth/token';



	public function __construct( $account_id, $client_id, $client_secret ) {

		$this->account_id = $account_id;

		$this->client_secret = $client_secret;

		$this->client_id = $client_id;
	}

	// Generate Access Token.

	public function generateAccessToken() {
		// Fetch the access token
		$body    = array(
			'grant_type' => 'account_credentials',
			'account_id' => $this->account_id,
		);
		$headers = array(
			'Authorization' => 'Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret ),

		);

		$response = wp_remote_post(
			$this->tokenUrl,
			array(
				'headers' => $headers,
				'body'    => $body,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		} else {
			$body               = wp_remote_retrieve_body( $response );
			$body               = json_decode( $body, true );
			$this->access_token = $body['access_token'];
			return $body;
		}
	}

	// Update Zoom Settings in the database.
	public function updateZoomSettings( $data = null ) {

		if ( $data == null ) {
			return array(
				'status'  => false,
				'message' => 'Invalid Data',
			);
		}

		$_tfhb_integration_settings = get_option( '_tfhb_integration_settings' );
		// return error message if data is not set
		if ( ! isset( $data['account_id'] ) || ! isset( $data['app_client_id'] ) || ! isset( $data['app_secret_key'] ) ) {
			$data = array(
				'status'  => false,
				'message' => 'Invalid Data',
			);
			return $data;
		}

		$zoom_meeting['type']              = sanitize_text_field( $data['meeting'] );
		$zoom_meeting['status']            = sanitize_text_field( $data['status'] );
		$zoom_meeting['connection_status'] = 1;
		$zoom_meeting['account_id']        = sanitize_text_field( $data['account_id'] );
		$zoom_meeting['app_client_id']     = sanitize_text_field( $data['app_client_id'] );
		$zoom_meeting['app_secret_key']    = sanitize_text_field( $data['app_secret_key'] );

		$response = $this->generateAccessToken();

		if ( isset( $response['error'] ) ) {

			$data = array(
				'status'  => false,
				'message' => $response['reason'],
			);

			return $data;

		} else {

			$_tfhb_integration_settings['zoom_meeting'] = $zoom_meeting;

			// update option
			update_option( '_tfhb_integration_settings', $_tfhb_integration_settings );

			$data = array(
				'status'  => true,
				'message' => 'Zoom Integration Settings Updated Successfully',
			);
			return $data;
		}
	}

	// Update Zoom Settings in the database.
	public function updateHostsZoomSettings( $data = null, $user_id = null ) {

		if ( $data == null || $user_id == null ) {
			return array(
				'status'  => false,
				'message' => 'Invalid Data',
			);
		}

		$_tfhb_host_integration_settings = get_user_meta( $user_id, '_tfhb_host_integration_settings' );

		// return error message if data is not set
		if ( ! isset( $data['account_id'] ) || ! isset( $data['app_client_id'] ) || ! isset( $data['app_secret_key'] ) ) {

			$data = array(
				'status'  => false,
				'message' => 'Invalid Data',
			);
			return $data;
		}

		$zoom_meeting['type']              = sanitize_text_field( $data['meeting'] );
		$zoom_meeting['status']            = sanitize_text_field( $data['status'] );
		$zoom_meeting['connection_status'] = 1;
		$zoom_meeting['account_id']        = sanitize_text_field( $data['account_id'] );
		$zoom_meeting['app_client_id']     = sanitize_text_field( $data['app_client_id'] );
		$zoom_meeting['app_secret_key']    = sanitize_text_field( $data['app_secret_key'] );

		$response = $this->generateAccessToken();

		if ( isset( $response['error'] ) ) {
			$data = array(
				'status'  => false,
				'message' => $response['reason'],
			);
			return $data;
		} else {

			$_tfhb_host_integration_settings['zoom_meeting'] = $zoom_meeting;

			// update user meta
			update_user_meta( $user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings );

			$data = array(
				'status'  => true,
				'message' => 'Zoom Integration Settings Updated Successfully',
			);
			return $data;
		}
	}

	public function create_zoom_meeting( $meeting_data ) {
		$access_response = $this->generateAccessToken();

		$time_in_24_hour_format = gmdate( 'H:i:s', strtotime( $meeting_data->start_time ) );

		$data = array(
			'topic'      => ! empty( $meeting_data->meeting_title ) ? $meeting_data->meeting_title : '',
			'type'       => 2, // Scheduled Meeting
			'start_time' => $meeting_data->meeting_dates . 'T' . $time_in_24_hour_format . 'Z',
			'timezone'   => ! empty( $meeting_data->attendee_time_zone ) ? $meeting_data->attendee_time_zone : '',
			'duration'   => intval( $meeting_data->meeting_duration ) + intval( $meeting_data->buffer_time_before ) + intval( $meeting_data->buffer_time_after ),
			'password'   => '123456',
			'settings'   => array(
				'join_before_host' => true,
				'mute_upon_entry'  => true,
				'waiting_room'     => false,
			),
		);

		$response = wp_remote_post(
			'https://api.zoom.us/v2/users/me/meetings',
			array(
				'body'    => wp_json_encode( $data ),
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_response['access_token'],
					'Content-Type'  => 'application/json',
				),
			)
		);

		// Handle the response
		if ( is_wp_error( $response ) ) {
			return $response; // Return the WP_Error object
		}

		$response_body = wp_remote_retrieve_body( $response );

		return json_decode( $response_body, true );
	}

	public function update_zoom_meeting( $meeting_schedule_id, $meeting_data ) {
		$access_response = $this->generateAccessToken();

		$time_in_24_hour_format = gmdate( 'H:i:s', strtotime( $meeting_data->start_time ) );

		$data = array(
			'topic'      => ! empty( $meeting_data->meeting_title ) ? $meeting_data->meeting_title : '',
			'type'       => 2, // Scheduled Meeting
			'start_time' => $meeting_data->meeting_dates . 'T' . $time_in_24_hour_format . 'Z',
			'timezone'   => ! empty( $meeting_data->attendee_time_zone ) ? $meeting_data->attendee_time_zone : '',
			'duration'   => intval( $meeting_data->meeting_duration ) + intval( $meeting_data->buffer_time_before ) + intval( $meeting_data->buffer_time_after ),
			'password'   => '123456',
			'settings'   => array(
				'join_before_host' => true,
				'mute_upon_entry'  => true,
				'waiting_room'     => false,
			),
		);

		$response = wp_remote_request(
			'https://api.zoom.us/v2/meetings/' . $meeting_schedule_id,
			array(
				'method'  => 'PATCH',
				'body'    => wp_json_encode( $data ),
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_response['access_token'],
					'Content-Type'  => 'application/json',
				),
			)
		);

		// Handle the response
		if ( is_wp_error( $response ) ) {
			return $response; // Return the WP_Error object
		}

		$response_body = wp_remote_retrieve_body( $response );

		return json_decode( $response_body, true );
	}
}
