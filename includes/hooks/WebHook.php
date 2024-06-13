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
                // Pabbly Webhook
                if( !empty($hook['webhook']) && !empty($hook['events']) && in_array("Booking Confirmed", $hook['events']) && "Pabbly"==$hook['webhook'] && !empty($hook['status']) ){
                    $this->tfhb_pabbly_callback($booking, $hook);
                }
                // Zapier Webhook
                if( !empty($hook['webhook']) && !empty($hook['events']) && in_array("Booking Confirmed", $hook['events']) && "Zapier"==$hook['webhook'] && !empty($hook['status']) ){
                    $this->tfhb_zapier_callback($booking, $hook);
                }
            }
        }
        
    }

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

}