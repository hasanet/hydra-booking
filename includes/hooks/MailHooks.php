<?php
namespace HydraBooking\Hooks;

// Use
use HydraBooking\DB\Meeting;
use HydraBooking\DB\Host;


class MailHooks {
	// Approved
	// Pending
	// Re-schedule
	// Canceled
	public function __construct() {
		add_action( 'hydra_booking/after_booking_completed', array( $this, 'pushBookingToCompleted' ), 10, 1 );
		add_action( 'hydra_booking/after_booking_canceled', array( $this, 'pushBookingToCanceled' ), 10, 1 );
		add_action( 'hydra_booking/after_booking_schedule', array( $this, 'pushBookingToscheduled' ), 10, 1 );
	}

	// Get Meeting Data
	public function getMeetingData( $meeting_id ) {
		$meeting      = new Meeting();
		$meeting_data = $meeting->get( $meeting_id );
		return get_post_meta( $meeting_data->post_id, '__tfhb_meeting_opt', true );
	}

	// Get Host Data
	public function getHostData( $host_id ) {
		$host      = new Host();
		$host_data = $host->get( $host_id );
		return $host_data;
	}

	// If booking Status is Complted
	public function pushBookingToCompleted( $booking ) {

		$Meeting_meta                = $this->getMeetingData( $booking->meeting_id );
		$_tfhb_notification_settings = ! empty( $Meeting_meta['notification'] ) ? $Meeting_meta['notification'] : '';
		$hostData                    = $this->getHostData( $booking->host_id );

		if ( ! empty( $_tfhb_notification_settings ) ) {

			// Host Confirmation Email, If Settings Enable for Host Confirmation
			if ( ! empty( $_tfhb_notification_settings['host']['booking_confirmation']['status'] ) ) {
				// From Email
				$replyTo = ! empty( $_tfhb_notification_settings['host']['booking_confirmation']['form'] ) ? $_tfhb_notification_settings['host']['booking_confirmation']['form'] : get_option( 'admin_email' );

				// Email Subject
				$subject = ! empty( $_tfhb_notification_settings['host']['booking_confirmation']['subject'] ) ? $_tfhb_notification_settings['host']['booking_confirmation']['subject'] : 'Booking Confirmation';

				// Setting Body
				$mailbody = ! empty( $_tfhb_notification_settings['host']['booking_confirmation']['body'] ) ? $_tfhb_notification_settings['host']['booking_confirmation']['body'] : '';

				// Replace Shortcode to Values
				$finalbody = $this->replace_mail_tags( $mailbody, $booking->id );

				// Result after Shortcode replce
				$body = wp_kses_post( $this->email_body_open() . $finalbody . $this->email_body_close() );

				// Host Email
				$mailto = ! empty( $hostData->email ) ? $hostData->email : '';

				$headers = array(
					'Reply-To: ' . $replyTo,
				);

				Mailer::send( $mailto, $subject, $body, $headers );
			}

			// Attendee Confirmation Email, If Settings Enable for Attendee Confirmation
			if ( ! empty( $_tfhb_notification_settings['attendee']['booking_confirmation']['status'] ) ) {
				// From Email
				$replyTo = ! empty( $_tfhb_notification_settings['attendee']['booking_confirmation']['form'] ) ? $_tfhb_notification_settings['attendee']['booking_confirmation']['form'] : get_option( 'admin_email' );

				// Email Subject
				$subject = ! empty( $_tfhb_notification_settings['attendee']['booking_confirmation']['subject'] ) ? $_tfhb_notification_settings['attendee']['booking_confirmation']['subject'] : 'Booking Confirmation';

				// Setting Body
				$mailbody = ! empty( $_tfhb_notification_settings['attendee']['booking_confirmation']['body'] ) ? $_tfhb_notification_settings['attendee']['booking_confirmation']['body'] : '';

				// Replace Shortcode to Values
				$finalbody = $this->replace_mail_tags( $mailbody, $booking->id );

				// Result after Shortcode replce
				$body = wp_kses_post( $this->email_body_open() . $finalbody . $this->email_body_close() );

				// Attendee Email
				$mailto = ! empty( $booking->email ) ? $booking->email : '';

				$headers = array(
					'Reply-To: ' . $replyTo,
				);

				Mailer::send( $mailto, $subject, $body, $headers );
			}
		}
	}

	// If booking Status is Cancel
	public function pushBookingToCanceled( $booking ) {

		$Meeting_meta                = $this->getMeetingData( $booking->meeting_id );
		$_tfhb_notification_settings = ! empty( $Meeting_meta['notification'] ) ? $Meeting_meta['notification'] : '';
		$hostData                    = $this->getHostData( $booking->host_id );

		// tfhb_print_r($hostData);
		if ( ! empty( $_tfhb_notification_settings ) ) {

			// Host Canceled Email, If Settings Enable for Host Canceled
			if ( ! empty( $_tfhb_notification_settings['host']['booking_cancel']['status'] ) ) {

				// From Email
				$replyTo = ! empty( $_tfhb_notification_settings['host']['booking_cancel']['form'] ) ? $_tfhb_notification_settings['host']['booking_cancel']['form'] : get_option( 'admin_email' );

				// Email Subject
				$subject = ! empty( $_tfhb_notification_settings['host']['booking_cancel']['subject'] ) ? $_tfhb_notification_settings['host']['booking_cancel']['subject'] : 'Booking Canceled';

				// Setting Body
				$mailbody = ! empty( $_tfhb_notification_settings['host']['booking_cancel']['body'] ) ? $_tfhb_notification_settings['host']['booking_cancel']['body'] : '';

				// Replace Shortcode to Values
				$finalbody = $this->replace_mail_tags( $mailbody, $booking->id );

				// Result after Shortcode replce
				$body = wp_kses_post( $this->email_body_open() . $finalbody . $this->email_body_close() );

				// Host Email
				$mailto = ! empty( $hostData->email ) ? $hostData->email : '';

				$headers = array(
					'Reply-To: ' . $replyTo,
				);
				Mailer::send( $mailto, $subject, $body, $headers );
			}

			// Attendee Canceled Email, If Settings Enable for Attendee Canceled
			if ( ! empty( $_tfhb_notification_settings['attendee']['booking_cancel']['status'] ) ) {
				// From Email
				$replyTo = ! empty( $_tfhb_notification_settings['attendee']['booking_cancel']['form'] ) ? $_tfhb_notification_settings['attendee']['booking_cancel']['form'] : get_option( 'admin_email' );

				// Email Subject
				$subject = ! empty( $_tfhb_notification_settings['attendee']['booking_cancel']['subject'] ) ? $_tfhb_notification_settings['attendee']['booking_cancel']['subject'] : 'Booking Canceled';

				// Setting Body
				$mailbody = ! empty( $_tfhb_notification_settings['attendee']['booking_cancel']['body'] ) ? $_tfhb_notification_settings['attendee']['booking_cancel']['body'] : '';

				// Replace Shortcode to Values
				$finalbody = $this->replace_mail_tags( $mailbody, $booking->id );

				// Result after Shortcode replce
				$body = wp_kses_post( $this->email_body_open() . $finalbody . $this->email_body_close() );

				// Attendee Email
				$mailto = ! empty( $booking->email ) ? $booking->email : '';

				$headers = array(
					'Reply-To: ' . $replyTo,
				);

				Mailer::send( $mailto, $subject, $body, $headers );
			}
		}
	}

	// If booking Status is ReSchedule
	public function pushBookingToscheduled( $booking ) {

		$Meeting_meta                = $this->getMeetingData( $booking->meeting_id );
		$_tfhb_notification_settings = ! empty( $Meeting_meta['notification'] ) ? $Meeting_meta['notification'] : '';
		$hostData                    = $this->getHostData( $booking->host_id );

		if ( ! empty( $_tfhb_notification_settings ) ) {

			// Host ReSchedule Email, If Settings Enable for Host ReSchedule
			if ( ! empty( $_tfhb_notification_settings['host']['booking_reschedule']['status'] ) ) {
				// From Email
				$replyTo = ! empty( $_tfhb_notification_settings['host']['booking_reschedule']['form'] ) ? $_tfhb_notification_settings['host']['booking_reschedule']['form'] : get_option( 'admin_email' );

				// Email Subject
				$subject = ! empty( $_tfhb_notification_settings['host']['booking_reschedule']['subject'] ) ? $_tfhb_notification_settings['host']['booking_reschedule']['subject'] : 'Booking ReSchedule';

				// Setting Body
				$mailbody = ! empty( $_tfhb_notification_settings['host']['booking_reschedule']['body'] ) ? $_tfhb_notification_settings['host']['booking_reschedule']['body'] : '';

				// Replace Shortcode to Values
				$finalbody = $this->replace_mail_tags( $mailbody, $booking->id );

				// Result after Shortcode replce
				$body = wp_kses_post( $this->email_body_open() . $finalbody . $this->email_body_close() );

				// Host Email
				$mailto = ! empty( $hostData->host_email ) ? $hostData->host_email : '';

				$headers = array(
					'Reply-To: ' . $replyTo,
				);

				Mailer::send( $mailto, $subject, $body, $headers );
			}

			// Attendee ReSchedule Email, If Settings Enable for Attendee ReSchedule
			if ( ! empty( $_tfhb_notification_settings['attendee']['booking_reschedule']['status'] ) ) {
				// From Email
				$replyTo = ! empty( $_tfhb_notification_settings['attendee']['booking_reschedule']['form'] ) ? $_tfhb_notification_settings['attendee']['booking_reschedule']['form'] : get_option( 'admin_email' );

				// Email Subject
				$subject = ! empty( $_tfhb_notification_settings['attendee']['booking_reschedule']['subject'] ) ? $_tfhb_notification_settings['attendee']['booking_reschedule']['subject'] : 'Booking ReSchedule';

				// Setting Body
				$mailbody = ! empty( $_tfhb_notification_settings['attendee']['booking_reschedule']['body'] ) ? $_tfhb_notification_settings['attendee']['booking_reschedule']['body'] : '';

				// Replace Shortcode to Values
				$finalbody = $this->replace_mail_tags( $mailbody, $booking->id );

				// Result after Shortcode replce
				$body = wp_kses_post( $this->email_body_open() . $finalbody . $this->email_body_close() );

				// Attendee Email
				$mailto = ! empty( $booking->email ) ? $booking->email : '';

				$headers = array(
					'Reply-To: ' . $replyTo,
				);

				Mailer::send( $mailto, $subject, $body, $headers );
			}
		}
	}

	/**
	 * email body open markup
	 */
	public function email_body_open() {
		// email body open
		$email_body_open = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="preconnect" href="https://fonts.googleapis.com"></head><body>';
		return $email_body_open;
	}

	/**
	 * email body close markup
	 */
	public function email_body_close() {
		// email body close
		$email_body_close = '</body></html>';
		return $email_body_close;
	}

	/**
	 * Replace all available mail tags
	 */
	public function replace_mail_tags( $template, $booking_id ) {

		global $wpdb;
		$tfhb_booking_table = $wpdb->prefix . 'tfhb_bookings';
		$meeting_table      = $wpdb->prefix . 'tfhb_meetings';
		$host_table         = $wpdb->prefix . 'tfhb_hosts';

		$sql          = "
            SELECT $tfhb_booking_table.attendee_name, 
            $tfhb_booking_table.email AS attendee_email,
            $tfhb_booking_table.meeting_dates,
            $tfhb_booking_table.start_time,
            $tfhb_booking_table.end_time,
            $tfhb_booking_table.duration AS meeting_duration,
            $host_table.email AS host_email,
            $host_table.first_name AS host_first_name,
            $host_table.last_name AS host_last_name,
            $meeting_table.title AS meeting_title,
            $meeting_table.meeting_locations AS meeting_location
            FROM $tfhb_booking_table
            INNER JOIN $host_table ON $tfhb_booking_table.host_id = $host_table.id
            INNER JOIN $meeting_table ON $tfhb_booking_table.meeting_id = $meeting_table.id
            WHERE $tfhb_booking_table.id = %d
        ";
		$booking_data = $wpdb->get_row( $wpdb->prepare( $sql, $booking_id ) );

		// Meeting Location Check
		$meeting_locations = json_decode( $booking_data->meeting_location );
		$locations         = array();
		if ( is_array( $meeting_locations ) ) {
			foreach ( $meeting_locations as $location ) {
				if ( isset( $location->location ) ) {
					$locations[] = $location->location;
				}
			}
		}

		$replacements = array(
			'{{meeting.title}}'    => ! empty( $booking_data->meeting_title ) ? $booking_data->meeting_title : '',
			'{{meeting.date}}'     => ! empty( $booking_data->meeting_dates ) ? $booking_data->meeting_dates : '',
			'{{meeting.location}}' => implode( ', ', $locations ),
			'{{meeting.duration}}' => $booking_data->meeting_duration,
			'{{meeting.time}}'     => $booking_data->start_time . '-' . $booking_data->end_time,
			'{{host.name}}'        => $booking_data->host_first_name . ' ' . $booking_data->host_last_name,
			'{{host.email}}'       => ! empty( $booking_data->host_email ) ? $booking_data->host_email : '',
			'{{attendee.name}}'    => ! empty( $booking_data->attendee_name ) ? $booking_data->attendee_name : '',
			'{{attendee.email}}'   => ! empty( $booking_data->attendee_email ) ? $booking_data->attendee_email : '',
		);

		$tags   = array_keys( $replacements );
		$values = array_values( $replacements );

		return str_replace( $tags, $values, $template );
	}
}
