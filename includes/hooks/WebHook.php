<?php
namespace HydraBooking\Hooks;
use HydraBooking\DB\Meeting;
class WebHook{

    public function __construct( ) { 
        add_action('hydra_booking/after_booking_completed', [$this, 'webhookBookingToCompleted'], 10, 1);
        // add_action('hydra_booking/after_booking_canceled', [$this, 'pushBookingToCanceled'], 10, 1);
        // add_action('hydra_booking/after_booking_schedule', [$this, 'pushBookingToscheduled'], 10, 1);
    }

    public function webhookBookingToCompleted($booking){

        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($booking->meeting_id);

        $webHookdata = !empty($MeetingData->webhook) ? json_decode($MeetingData->webhook, true) : array();
        if(!empty($webHookdata)){
            foreach($webHookdata as $hook){
                // Pabbly
                if( !empty($hook['webhook']) && !empty($hook['events']) && in_array("Booking Confirmed", $hook['events']) && "Pabbly"==$hook['webhook'] && !empty($hook['status']) ){
                    $this->tfhb_pabbly_callback($booking, $hook);
                }
                // Zapier
                if( !empty($hook['webhook']) && !empty($hook['events']) && in_array("Booking Confirmed", $hook['events']) && "Zapier"==$hook['webhook'] && !empty($hook['status']) ){
                    $this->tfhb_zapier_callback($booking, $hook);
                }

                // Webhook
                if( !empty($hook['events']) && in_array("Booking Confirmed", $hook['events']) && !empty($hook['status']) ){
                    $this->tfhb_webhook_callback($booking, $hook);
                }
            }
        }
        
    }

    // Pabbly Callback
    function tfhb_pabbly_callback($booking, $hook){
        $tfhb_allow_unsafe_urls = false;
        $tfhb_http_args = array(
            'method'      => 'POST',
            'timeout'     => MINUTE_IN_SECONDS,
            'redirection' => 0,
            'httpversion' => '1.0',
            'blocking'    => false,
            'user-agent'  => sprintf(  'Trigger (WordPress/%s)', $GLOBALS['wp_version'] ),
            'headers'     => array(
            'Content-Type' => 'application/json; charset=UTF-8',
            ),
            'cookies'     => array(),
        );

        $tfhb_http_args['headers']['X-WP-Webhook-Source'] = home_url( '/' );
        $tfhb_http_args['body'] = trim( wp_json_encode( $booking ) );
        $response = wp_safe_remote_request( $hook['url'], $tfhb_http_args );	
    }

    // Zapier Callback
    function tfhb_zapier_callback($booking, $hook){
        $tfhb_allow_unsafe_urls = false;
        $tfhb_http_args = array(
            'method'      => 'POST',
            'timeout'     => MINUTE_IN_SECONDS,
            'redirection' => 0,
            'httpversion' => '1.0',
            'blocking'    => false,
            'user-agent'  => sprintf(  'Trigger (WordPress/%s)', $GLOBALS['wp_version'] ),
            'headers'     => array(
            'Content-Type' => 'application/json; charset=UTF-8',
            ),
            'cookies'     => array(),
        );

        $tfhb_http_args['headers']['X-WP-Webhook-Source'] = home_url( '/' );
        $tfhb_http_args['body'] = trim( wp_json_encode( $booking ) );
        $response = wp_safe_remote_request( $hook['url'], $tfhb_http_args );	
    }

    // Webhook Callback
    function tfhb_webhook_callback($booking, $hook) {

		//Admin Option
		$request_api = isset( $hook['url'] ) ? $hook['url'] : '';
		$request_method = isset( $hook['request_method'] ) ? $hook['request_method'] : '';
		$request_format = isset( $hook['request_format'] ) ? $hook['request_format'] : '';
		$header_request = isset( $hook['headers'] ) ? $hook['headers'] : '';
        $header_request_type = isset( $hook['request_header'] ) ? $hook['request_header'] : 'no';
		$body_request = isset( $hook['bodys'] ) ? $hook['bodys'] : '';
		$body_request_type = isset( $hook['request_body'] ) ? $hook['request_body'] : 'all';

		$api_endpoint = $request_api;
		$api_request_method = $request_method;

		// Define the data to send in the POST request
		$header_data = array();
		$body_data = array();


		// Check if $header_request is an array
		if ( is_array( $header_request ) && 'with'==$header_request_type ) {
			// Loop through each item in the array
			foreach ( $header_request as $header ) {
				// Access individual values using keys
                $customKey = $header['key'];
                if ( isset( $customKey ) ) {
                    $header_value = $header['value'];
                    $header_parameter = $customKey;

                    // Add data to the $post_data array
				    $header_data[ $header_value ] = $header_parameter;
                }
			}
		}

		// Check if $body_request is an array
		if ( is_array( $body_request ) && 'selected'==$body_request_type ) {
			// Loop through each item in the array
			foreach ( $body_request as $body ) {
				// Access individual values using keys
				$body_value = $body['name'];
				$body_parameter = $booking[ $body['value'] ];

				// Add data to the $body_data array
				$body_data[ $body_value ] = $body_parameter;
			}
		}else{
            $body_data = $booking;
        }

		// Set up the request arguments
		$request_args = array(
			'body' => json_encode( $body_data ),
			'headers' => array_merge(
				//Need loop for additional input
				[ 'Content-Type' => 'application/json' ],
				$header_data,
			),
			'method' => $api_request_method,
		);

		// Make the POST request
		$response = wp_remote_request( $api_endpoint, $request_args );

		// Check if the request was successful
		if ( is_wp_error( $response ) ) {
			// Handle error
			//echo 'Error: ' . $response->get_error_message();
		} else {
			// Request was successful, and $response contains the API response
			//$api_response = wp_remote_retrieve_body( $response );
			//echo 'API Response: ' . $api_response;
		}

    }

}