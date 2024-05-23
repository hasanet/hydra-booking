<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Services\Integrations\Zoom\ZoomServices;
 
 // Use DB 
use HydraBooking\DB\Booking;

if ( ! defined( 'ABSPATH' ) ) { exit; }

  class BookingController {
    

    // constaract
    public function __construct() { 
       
    }

    public function init() {
        
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/booking/lists', array(
            'methods' => 'GET',
            'callback' => array($this, 'getBookingsData'),
        ));  
        register_rest_route('hydra-booking/v1', '/booking/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'CreateBooking'),
        ));  
        register_rest_route('hydra-booking/v1', '/booking/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'DeleteBooking'),
        ));  
        // Get Single Booking based on id
        register_rest_route('hydra-booking/v1', '/booking/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getBookingData'),
        ));
        register_rest_route('hydra-booking/v1', '/booking/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateBooking'),
        ));   
       
    }
    // Booking List
    public function getBookingsData() { 

        // Booking Lists 
        $booking = new Booking();
        $bookingsList = $booking->get(null, true);
        
        // Return response
        $data = array(
            'status' => true, 
            'bookings' => $bookingsList,  
            'message' => 'Booking Data Successfully Retrieve!', 
        );
        return rest_ensure_response($data);
    }  

    // Create Booking
    public function CreateBooking() { 
        
        $request = json_decode(file_get_contents('php://input'), true);

        $data = [ 
            'meeting_id' => isset($request['meeting']) ? $request['meeting'] : '',
            'first_name' => isset($request['name']) ? $request['name'] : '',
            'email' => isset($request['email']) ? $request['email'] : '',
            'phone' => isset($request['phone']) ? $request['phone'] : '',
            'location_details' => isset($request['address']) ? $request['address'] : '',
            'payment_method' => 'backend',
            'payment_status' => 'pending',
            'status'    => 'pending'
        ];

        // Check if user is already a booking
        $booking = new Booking();
        // Insert booking
        $bookingInsert = $booking->add($data);
        if(!$bookingInsert['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while creating Booking'));
        }
        $booking_id = $bookingInsert['insert_id'];

        // booking Lists 
        $booking_List = $booking->get();
        // Return response
        $data = array(
            'status' => true, 
            'booking' => $booking_List,  
            'id' => $booking_id,  
            'message' => 'Booking Created Successfully', 
        );
        
        return rest_ensure_response($data);
    }

    // Delete Booking
    public function DeleteBooking(){
        
        // Meeting Lists
        $MeetingsList = $meeting->get();
        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'Booking Deleted Successfully', 
        );
        return rest_ensure_response($data);
    }

    // Get Single Booking
    public function getBookingData($request){
        
        // Return response
        $data = array(
            'status' => true, 
            'meeting' => $MeetingData,  
            'message' => 'Booking Data',
        );
        return rest_ensure_response($data);

    }

    // Update Booking Information
    public function updateBooking(){
        $request = json_decode(file_get_contents('php://input'), true);
        $booking_id = $request['id'];
        if (empty($booking_id) || $booking_id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Booking'));
        }

        $data = [ 
            'id' => $request['id'],
            'status' => isset($request['status']) ? sanitize_text_field($request['status']) : ''
        ];

        $booking = new Booking();
        // Booking Update
        $bookingUpdate = $booking->update($data);

        // Booking Lists 
        $booking_List = $booking->get();

        // Single Booking 
        $single_booking_meta = $booking->get($request['id']);
        $_tfhb_host_integration_settings = get_user_meta($single_booking_meta->host_id, '_tfhb_host_integration_settings', true);

        // Global Integration
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        if( !empty($_tfhb_integration_settings['zoom_meeting']) && !empty($_tfhb_integration_settings['zoom_meeting']['connection_status'])){
            $account_id = $_tfhb_integration_settings['zoom_meeting']['account_id'];
            $app_client_id = $_tfhb_integration_settings['zoom_meeting']['app_client_id'];
            $app_secret_key = $_tfhb_integration_settings['zoom_meeting']['app_secret_key'];
        }

        // Host Integration
        if( !empty($_tfhb_host_integration_settings['zoom_meeting']) && !empty($_tfhb_host_integration_settings['zoom_meeting']['connection_status'])){
            $account_id = $_tfhb_host_integration_settings['zoom_meeting']['account_id'];
            $app_client_id = $_tfhb_host_integration_settings['zoom_meeting']['app_client_id'];
            $app_secret_key = $_tfhb_host_integration_settings['zoom_meeting']['app_secret_key'];
        }
        
        if( !empty($account_id) && !empty($app_client_id) && !empty($app_secret_key) ){

            $zoom = new ZoomServices(
                sanitize_text_field($account_id), 
                sanitize_text_field($app_client_id),  
                sanitize_text_field($app_secret_key)
            ); 

            $meeting_creation = $zoom->create_zoom_meeting();
        }

        var_dump($meeting_creation); exit();
        
        if("approved"==$request['status']){
            do_action('hydra_booking/after_booking_completed', $single_booking_meta);
        }

        if("canceled"==$request['status']){
            do_action('hydra_booking/after_booking_canceled', $single_booking_meta);
        }

        if("schedule"==$request['status']){
            do_action('hydra_booking/after_booking_schedule', $single_booking_meta);
        }

        // Return response
        $data = array(
            'status' => true,  
            'booking' => $booking_List, 
            'message' => 'Booking Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 