<?php
namespace HydraBooking\Services\Integrations\FluentCRM;

use HydraBooking\DB\Meeting;
class FluentCRM {

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
				if ( ! empty( $hook['webhook'] ) && 'FluentCRM' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Completed', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_fluentcrm_callback( $booking, $hook, $MeetingData->host_id );
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
				if ( ! empty( $hook['webhook'] ) && 'FluentCRM' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Canceled', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_fluentcrm_callback( $booking, $hook, $MeetingData->host_id );
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
				if ( ! empty( $hook['webhook'] ) && 'FluentCRM' == $hook['webhook'] && ! empty( $hook['events'] ) && in_array( 'Booking Completed', $hook['events'] ) && ! empty( $hook['status'] ) ) {
					$this->tfhb_fluentcrm_callback( $booking, $hook, $MeetingData->host_id );
				}
			}
		}
	}

	// FluentCRM Callback
	function tfhb_fluentcrm_callback( $booking, $hook, $host ) {

		global $wpdb;
		// Check if table exists
		$subscriber_table_name = $wpdb->prefix . 'fc_subscribers';
		$table_exists          = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}fc_subscribers'" ) == $subscriber_table_name;

		$subscriber_pivot_table_name = $wpdb->prefix . 'fc_subscriber_pivot';
		$pivot_table_exists          = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}fc_subscriber_pivot'" ) == $subscriber_pivot_table_name;

		if ( $table_exists ) {
			// Table exists, prepare and insert data
			$first_name = ! empty( $booking->attendee_name ) ? $booking->attendee_name : '';
			$last_name  = ! empty( $booking->attendee_last_name ) ? $booking->attendee_last_name : '';
			$email      = ! empty( $booking->email ) ? $booking->email : '';

			// Prepare the data
			$data = array(
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'email'      => $email,
			);

			// Format for the data (to ensure proper SQL data types)
			$format = array(
				'%s',  // first_name is a string
				'%s',  // last_name is a string
				'%s',  // email is a string
			);
			// Insert data
			$inserted       = $wpdb->insert( $subscriber_table_name, $data, $format );
			$subscripber_id = $wpdb->insert_id;

			if ( $pivot_table_exists && ! empty( $hook['lists'] ) ) {
				// Prepare the data
				$list_data = array(
					'subscriber_id' => $subscripber_id,
					'object_id'     => $hook['lists'],
					'object_type'   => 'FluentCrm\App\Models\Lists',
				);

				// Format for the data (to ensure proper SQL data types)
				$list_format = array(
					'%d',  // subscriber_id is a integer
					'%d',  // object_id is a integer
					'%s',  // email is a string
				);
				// Insert data
				$listinserted = $wpdb->insert( $subscriber_pivot_table_name, $list_data, $format );
			}
			if ( $pivot_table_exists && ! empty( $hook['tags'] ) ) {
				// Prepare the data
				$list_data = array(
					'subscriber_id' => $subscripber_id,
					'object_id'     => $hook['tags'],
					'object_type'   => 'FluentCrm\App\Models\Tag',
				);

				// Format for the data (to ensure proper SQL data types)
				$list_format = array(
					'%d',  // subscriber_id is a integer
					'%d',  // object_id is a integer
					'%s',  // email is a string
				);
				// Insert data
				$listinserted = $wpdb->insert( $subscriber_pivot_table_name, $list_data, $format );
			}
		}
	}
}
