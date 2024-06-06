<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 
 // Use DB 
use HydraBooking\DB\Booking;
use HydraBooking\DB\Host;
use HydraBooking\Admin\Controller\DateTimeController;
use HydraBooking\DB\Meeting;

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
        register_rest_route('hydra-booking/v1', '/booking/pre', array(
            'methods' => 'GET',
            'callback' => array($this, 'getPreBookingsData'),
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

        $current_user = wp_get_current_user();
		// get user role
		$current_user_role = ! empty( $current_user->roles[0] ) ? $current_user->roles[0] : '';
        $current_user_id = $current_user->ID;

        // Booking Lists 
        $booking = new Booking();

        if(!empty($current_user_role) && "administrator"==$current_user_role){
            $bookingsList = $booking->get(null, true);
        }
        if(!empty($current_user_role) && "tfhb_host"==$current_user_role){
            $host = new Host();
            $HostData = $host->get( $current_user_id  );

            $bookingsList = $booking->get(null, true, false, false, false, false, $HostData->id);
        }
        
        // Return response
        $data = array(
            'status' => true, 
            'bookings' => $bookingsList,  
            'message' => 'Booking Data Successfully Retrieve!', 
        );
        return rest_ensure_response($data);
    }  

    // Pre Booking Data
    public function getPreBookingsData(){
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

        $meeting = new Meeting();
        $MeetingsList = $meeting->get();

        $meeting_array = array();
        foreach($MeetingsList as $single){
            $meeting_array[] = array(
                'name' => $single->title,
                'value' => "".$single->id."",
            );
        }

        $data = array(
            'status' => true, 
            'time_zone' => $time_zone,
            'meetings' => $meeting_array
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
        $MeetingsList = $meeting->get(null, true);
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
        $booking_List = $booking->get(null, true);

        // Single Booking 
        $single_booking_meta = $booking->get($request['id']);

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