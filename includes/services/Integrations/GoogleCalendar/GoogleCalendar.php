<?php

namespace HydraBooking\Services\Integrations\GoogleCalendar;

use HydraBooking\DB\Booking;
use HydraBooking\DB\Host;
use HydraBooking\DB\Meeting;
class GoogleCalendar {

	public $clientId;
	public $clientSecret;
	public $redirectUrl;

	private $accessToken;

	public $revokeUrl        = 'https://oauth2.googleapis.com/revoke';
	public $tokenUrl         = 'https://oauth2.googleapis.com/token';
	private $refreshTokenUrl = 'https://www.googleapis.com/oauth2/v3/token';
	public $authUrl          = 'https://accounts.google.com/o/oauth2/auth';

	public $calendarEvent = 'https://www.googleapis.com/calendar/v3/calendars/';

	public $authScope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/calendar.events';



	public function __construct() {

		$this->setClientData();

		// add_action('hydra_booking/after_booking_completed', array($this, 'InsertGoogleCalender'));
		// add_action('hydra_booking/after_booking_schedule', array($this, 'InsertGoogleCalender'));
		add_filter( 'after_booking_completed_calendar_data', array( $this, 'InsertGoogleCalender' ), 10, 2 );
		add_filter( 'hydra_booking_calendar_add_new_attendee', array( $this, 'addAttendeeGoogleCalender' ), 10, 2 );
	}

	// Set Client Data
	public function setClientData() {
		// Get the Google Calendar Data
		$_tfhb_integration_settings = get_option( '_tfhb_integration_settings' );
		$google_calendar            = isset( $_tfhb_integration_settings['google_calendar'] ) ? $_tfhb_integration_settings['google_calendar'] : array();

		// Set the Client Data
		$this->clientId     = isset( $google_calendar['client_id'] ) ? $google_calendar['client_id'] : '';
		$this->clientSecret = isset( $google_calendar['secret_key'] ) ? $google_calendar['secret_key'] : '';
		$this->redirectUrl  = isset( $google_calendar['redirect_url'] ) ? $google_calendar['redirect_url'] : $this->setRredirectUrl();
	}

	public function setRredirectUrl() {
		// example : wp-json/hydra-booking/v1/integration/google-api
		return get_rest_url() . 'hydra-booking/v1/integration/google-api';
	}
	// Set Access Token
	public function setAccessToken( $host_id ) {

		$_tfhb_host_integration_settings = is_array( get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) : array();
		$accessToken                     = isset( $_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar']['access_token'] ) ? $_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar']['access_token'] : '';

		$this->accessToken = $accessToken;
	}

	public function create_endpoint() {
		register_rest_route(
			'hydra-booking/v1',
			'/integration/google-api',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'GetAccessData' ),
			)
		);
	}

	public function GetAccessData() {

		// Set the Client Data
		if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) {

			try {

				$host_id = $_GET['state'];

				$data  = $this->GetAccessToken( $_GET['code'] );
				$data  = json_decode( $data, true );
				$email = $this->getEmailByIdToken( $data['id_token'] );

				// Get all calendar in the account
				$url      = 'https://www.googleapis.com/calendar/v3/users/me/calendarList';
				$response = wp_remote_get( $url, array( 'headers' => array( 'Authorization' => 'Bearer ' . $data['access_token'] ) ) );
				$body     = wp_remote_retrieve_body( $response );
				$body     = json_decode( $body, true );

				$data['email'] = $email;

				foreach ( $body['items'] as $calendar ) {
					if ( $calendar['accessRole'] == 'owner' || $calendar['accessRole'] == 'writer' ) {
						$data['items'][] = array(
							'id'           => $calendar['id'],
							'title'        => $calendar['summary'],
							'write_status' => 0,
						);
					}
				}

				// remove the Id Token
				unset( $data['id_token'] );

				$_tfhb_host_integration_settings = is_array( get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) : array();

				$_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar'] = $data;

				// save to user metadata
				update_user_meta( $host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings, true );

				$redirect_url = get_site_url() . '/wp-admin/admin.php?page=hydra-booking#/hosts/profile/' . $host_id . '/integrations';

				wp_redirect( $redirect_url );
				wp_die();

			} catch ( Exception $e ) {
				echo esc_html($e->getMessage());
				exit();
			}
		}
	}


	// if Access token is experired
	public function refreshToken( $host_id ) {
		$_tfhb_host_integration_settings = is_array( get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $host_id, '_tfhb_host_integration_settings', true ) : array();
		$refreshToken                    = isset( $_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar']['refresh_token'] ) ? $_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar']['refresh_token'] : '';

		$url          = $this->refreshTokenUrl;
		$clientId     = $this->clientId;
		$clientSecret = $this->clientSecret;
		$post_fields  = array(
			'client_id'     => $clientId,
			'client_secret' => $clientSecret,
			'refresh_token' => $refreshToken,
			'grant_type'    => 'refresh_token',
		);
		// use Wp Remote Request
		$response = wp_remote_post(
			$url,
			array(
				'body' => $post_fields,
			)
		);
		$body     = wp_remote_retrieve_body( $response );
		$body     = json_decode( $body, true );

		if ( $body['access_token'] ) {

			$this->accessToken = $body['access_token'];
		} else {
			$this->setAccessToken( $host_id );
		}

		return $body;
	}

	public function GetAccessToken( $code ) {
		$url          = $this->tokenUrl;
		$clientId     = $this->clientId;
		$clientSecret = $this->clientSecret;
		$redirectUrl  = $this->redirectUrl;
		$post_fields  = array(
			'code'          => $code,
			'client_id'     => $clientId,
			'client_secret' => $clientSecret,
			'redirect_uri'  => $redirectUrl,
			'grant_type'    => 'authorization_code',
		);
		// use Wp Remote Request
		$response = wp_remote_post(
			$url,
			array(
				'body' => $post_fields,
			)
		);
		$body     = wp_remote_retrieve_body( $response );

		return json_decode( $body, true );
	}

	public function GetAccessTokenUrl( $host_id ) {
		return $this->authUrl . '?client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUrl . '&scope=' . $this->authScope . '&response_type=code&access_type=offline&prompt=consent&state=' . $host_id;
	}


	/**
	 * Get the email by id token
	 *
	 * @param $token
	 * @return mixed
	 */
	public function getEmailByIdToken( $id_token ) {
		$tokenParts   = explode( '.', $id_token );
		$tokenPayload = base64_decode( $tokenParts[1] );
		$jwtPayload   = json_decode( $tokenPayload, true );
		return $jwtPayload['email'];
	}


	public function generateAuthCode( $code ) {
		$body = array(
			'client_id'     => $this->clientId,
			'client_secret' => $this->clientSecret,
			'redirect_uri'  => $this->redirectUrl,
			'grant_type'    => 'authorization_code',
			'code'          => $code,
		);

		$type    = 'GET';
		$url     = $this->tokenUrl;
		$headers = array(
			'Content-Type'              => 'application/http',
			'Content-Transfer-Encoding' => 'binary',
			'MIME-Version'              => '1.0',
		);

		$args = array(
			'headers' => $headers,
			'method'  => $type,
			'timeout' => 20,
		);

		if ( $body ) {

			$url = add_query_arg( $body, $url );
		}

		$request = wp_remote_request( $url, $args );
	}



	// Insert Booking to Google Calendar
	public function InsertGoogleCalender( $value, $data ) {

		if ( ! isset( $data->id ) ) {
			return;
		}

		$settings        = get_option( '_tfhb_integration_settings' );
		$google_calender = isset( $settings['google_calendar'] ) ? $settings['google_calendar'] : array();
		if ( isset( $google_calender['status'] ) && $google_calender['status'] == 0 ) {
			return $value;
		}
		if ( isset( $google_calender['connection_status'] ) && $google_calender['connection_status'] == 0 ) {
			return $value;
		}

		// set event data google meet shedule
		$start_time    = strtotime( $data->start_time ); // 03:45 AM
		$end_time      = strtotime( $data->end_time ); // 04:30 AM
		$meeting_dates = $data->meeting_dates; // 2024-07-10,2024-07-17,2024-07-24,2024-07-31

		// Set the Access Token
		$this->refreshToken( $data->host_id );

		$meeting_dates = explode( ',', $meeting_dates );

		$host     = new Host();
		$hostData = $host->get( $data->host_id );

		$meeting           = new Meeting();
		$meetingData       = $meeting->get( $data->meeting_id );
		$meeting_locations = json_decode( $meetingData->meeting_locations, true );

		$enable_meeting_location = false;
		// if in array location value is meet then set google meet using array filter
		$meeting_location        = array_filter(
			$meeting_locations,
			function ( $location ) {
				return $location['location'] == 'meet';
			}
		);
		$enable_meeting_location = count( $meeting_location ) > 0 ? true : false;

		$google_calendar_data = array();
		foreach ( $meeting_dates as $meeting_date ) {
			$start_date = gmdate( 'Y-m-d', strtotime( $meeting_date ) ) . 'T' . gmdate( 'H:i:s', $start_time );
			$end_date   = gmdate( 'Y-m-d', strtotime( $meeting_date ) ) . 'T' . gmdate( 'H:i:s', $end_time );

			// Meeting location google meeting
			$setData = array(
				'title'          => 'Meeting with ' . $data->attendee_name,
				'summary'        => 'Title: ' . $meetingData->title,
				// 'location' => 'Location: ' . $data->meeting_location,
				'description'    => 'Description: ',
				'start'          => array(
					'dateTime' => $start_date,
					'timeZone' => $data->attendee_time_zone,
				),
				'end'            => array(
					'dateTime' => $end_date,
					'timeZone' => $data->attendee_time_zone,
				),
				'attendees'      => array(
					array( 'email' => $data->email ),
					array( 'email' => $hostData->email ),
				),
				'reminders'      => array(
					'useDefault' => false,
					'overrides'  => array(
						array(
							'method'  => 'email',
							'minutes' => 24 * 60,
						),
						array(
							'method'  => 'popup',
							'minutes' => 10,
						),
					),
				),
				'conferenceData' => array(
					'createRequest' => array(
						'requestId'             => 'sample123', // Provide a unique ID for the request
						'conferenceSolutionKey' => array(
							'type' => 'hangoutsMeet',
						),
					),
				),
			);
			if ( $enable_meeting_location == true ) {
				$setData['conferenceData'] = array(
					'createRequest' => array(
						'requestId'             => 'sample123', // Provide a unique ID for the request
						'conferenceSolutionKey' => array(
							'type' => 'hangoutsMeet',
						),
					),
				);
			}

			$_tfhb_host_integration_settings = is_array( get_user_meta( $data->host_id, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $data->host_id, '_tfhb_host_integration_settings', true ) : array();
			$google_calendar                 = isset( $_tfhb_host_integration_settings['google_calendar'] ) ? $_tfhb_host_integration_settings['google_calendar'] : array();
			$calendarId                      = isset( $google_calendar['selected_calendar_id'] ) ? $google_calendar['selected_calendar_id'] : '';

			if ( $calendarId ) {

				$meeting_calendar = json_decode( $data->meeting_calendar, true );

				if ( $meeting_calendar != '' && isset( $meeting_calendar['google_calendar']['id'] ) ) {
					$url      = $this->calendarEvent . $calendarId . '/events/' . $meeting_calendar['google_calendar']['id'];
					$response = wp_remote_post(
						$url,
						array(
							'headers'     => array( 'Authorization' => 'Bearer ' . $this->accessToken ),
							'body'        => wp_json_encode( $setData ),
							'method'      => 'PUT',
							'data_format' => 'body',
						)
					);

				} else {
					$url = $this->calendarEvent . $calendarId . '/events';
					if ( $enable_meeting_location == true ) {
						$url .= '?conferenceDataVersion=1';

					}
					// set all events
					$response = wp_remote_post(
						$url,
						array(
							'headers'     => array(
								'Authorization' => 'Bearer ' . $this->accessToken,
							),
							'body'        => wp_json_encode( $setData ),
							'method'      => 'POST',
							'data_format' => 'body',
						)
					);

				}
				$body = wp_remote_retrieve_body( $response );

				$google_calendar_body[] = json_decode( $body, true );
			}
		}

		// Update the Booking
		$value['google_calendar'] = $google_calendar_body;

		return $value;
		// $update = array();
		// $update['id'] = $data->id;
		// $update['meeting_calendar'] = json_encode($google_calendar_data, true);

		// $booking = new Booking();

		// $booking->update($update);
	}

	// add new attendee existing Booking to Google Calendar
	public function addAttendeeGoogleCalender( $data, $booking ) {

		// Set the Access Token
		$this->refreshToken( $booking->host_id );
		$events = $data->google_calendar;

		$google_calendar_body = array();
		foreach ( $events as $event ) {
			$event_id = $event->id;

			$new_attendees = array( 'email' => $booking->email );
			// add new attendee also remaing existing attendee
			$event->attendees[] = $new_attendees;

			$_tfhb_host_integration_settings = is_array( get_user_meta( $booking->host_id, '_tfhb_host_integration_settings', true ) ) ? get_user_meta( $booking->host_id, '_tfhb_host_integration_settings', true ) : array();
			$google_calendar                 = isset( $_tfhb_host_integration_settings['google_calendar'] ) ? $_tfhb_host_integration_settings['google_calendar'] : array();
			$calendarId                      = isset( $google_calendar['selected_calendar_id'] ) ? $google_calendar['selected_calendar_id'] : '';

			if ( $calendarId ) {

				$url      = $this->calendarEvent . $calendarId . '/events/' . $event_id;
				$response = wp_remote_post(
					$url,
					array(
						'headers'     => array( 'Authorization' => 'Bearer ' . $this->accessToken ),
						'body'        => wp_json_encode( $event ),
						'method'      => 'PUT',
						'data_format' => 'body',
					)
				);
				$body     = wp_remote_retrieve_body( $response );

				$google_calendar_body[] = json_decode( $body, true );

			}
		}

		// Update the Booking
		$data->google_calendar = $google_calendar_body;

		return $data;
	}
}
