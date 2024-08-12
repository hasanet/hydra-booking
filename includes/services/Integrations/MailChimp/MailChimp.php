<?php
namespace HydraBooking\Services\Integrations\MailChimp;

use HydraBooking\DB\Meeting;
class MailChimp {

	public function __construct() {
		add_action( 'hydra_booking/after_booking_completed', array( $this, 'integrationsBookingToCompleted' ), 10, 1 );
		add_action( 'hydra_booking/after_booking_canceled', array( $this, 'integrationsBookingToCanceled' ), 10, 1 );
		add_action( 'hydra_booking/after_booking_confirmed', array( $this, 'integrationsBookingToConfirmed' ), 10, 1 );
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
				if ( ! empty( $hook['webhook'] ) && 'Mailchimp' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Completed', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_mailchimp_callback( $booking, $hook, $MeetingData->host_id );
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
				if ( ! empty( $hook['webhook'] ) && 'Mailchimp' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Canceled', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_mailchimp_callback( $booking, $hook, $MeetingData->host_id );
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
				if ( ! empty( $hook['webhook'] ) && 'Mailchimp' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Completed', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_mailchimp_callback( $booking, $hook, $MeetingData->host_id );
				}
			}
		}
	}

	// Mailchimp Callback
	function tfhb_mailchimp_callback( $booking, $hook, $host ) {

		$_tfhb_host_integration_settings = ! empty( $host ) ? get_user_meta( $host, '_tfhb_host_integration_settings', true ) : '';

		$_tfhb_integration_settings = get_option( '_tfhb_integration_settings' );
		$api_key                    = ! empty( $_tfhb_integration_settings['mailchimp']['key'] ) ? $_tfhb_integration_settings['mailchimp']['key'] : '';
		$api_key                    = ! empty( $_tfhb_host_integration_settings['mailchimp']['key'] ) ? $_tfhb_host_integration_settings['mailchimp']['key'] : $api_key;

		if ( $api_key != '' ) {

			$response = $this->set_config( $api_key, 'ping' );
			$response = json_decode( $response );
			if ( isset( $response->health_status ) ) { // Display success message
				$response = $this->add_members( $api_key, $booking, $hook );
			} else {
				$this->mailchimlConnection = false;
			}
		}

		// Admin Option
		$body_request = isset( $hook['bodys'] ) ? $hook['bodys'] : '';

		// Define the data to send in the POST request
		$header_data = array();
		$body_data   = array();
	}

	/* Mailchimp config set */
	private function set_config( $api_key = '', $path = '' ) {

		$server_prefix = explode( '-', $api_key );

		if ( ! isset( $server_prefix[1] ) ) {
			return;
		}
		$server_prefix = $server_prefix[1];

		$url = "https://$server_prefix.api.mailchimp.com/3.0/$path";

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		$headers = array(
			"Authorization: Bearer $api_key",
		);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		// for debug only!
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
			$server_prefix    = explode( '-', $api_key );
			$server_prefix    = $server_prefix[1];
			$subscriber_fname = ! empty( $booking->attendee_name ) ? $booking->attendee_name : '';
			$subscriber_lname = ! empty( $booking->attendee_last_name ) ? $booking->attendee_last_name : '';

			$extra_fields = ! empty( $hook['bodys'] ) ? $hook['bodys'] : array();

			$extra_merge_fields = '';
			foreach ( $extra_fields as $extra_field ) {
				$field_name          = $extra_field['name'];
				$field_value         = isset( $booking->$field_name ) ? $booking->$field_name : ''; // Check if the property exists
				$extra_merge_fields .= '"' . $extra_field['value'] . '": "' . $field_value . '",';
			}
			$extra_merge_fields = trim( $extra_merge_fields, ',' );

			if ( $extra_merge_fields != '' ) {
				$extra_merge_fields = ',' . $extra_merge_fields;
			}
			// var_dump($extra_merge_fields); exit();
			$url = "https://$server_prefix.api.mailchimp.com/3.0/lists/" . $hook['audience'] . '/members';

			$curl = curl_init( $url );
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

			$headers = array(
				"Authorization: Bearer $api_key",
				'Content-Type: application/json',
			);
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

			// Mailchimp data
			$data = '{"email_address":"' . sanitize_email( $subscriber_email ) . '","status":"subscribed","merge_fields":{"FNAME": "' . sanitize_text_field( $subscriber_fname ) . '", "LNAME": "' . sanitize_text_field( $subscriber_lname ) . '"' . $extra_merge_fields . '},"vip":false,"location":{"latitude":0,"longitude":0}}';

			curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

			// for debug only!
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

			$resp = curl_exec( $curl );
			curl_close( $curl );
			// return $url;
		}
	}
}
