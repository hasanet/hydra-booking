<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 
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
        register_rest_route('hydra-booking/v1', '/booking/details/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateBooking'),
        ));   
       
    }
    // Booking List
    public function getBookingsData() { 

        // Meeting Lists 
        $meeting = new Meeting();
        $MeetingsList = $meeting->get();

        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'Booking Data Successfully Retrieve!', 
        );
        return rest_ensure_response($data);
    }  

    // Create Booking
    public function CreateBooking() { 
        
        // Check if user is already a meeting
        $meeting = new Meeting();
        // Insert meeting
        $meetingInsert = $meeting->add($data);
        if(!$meetingInsert['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while creating meeting'));
        }
        $meetings_id = $meetingInsert['insert_id'];
    
        // meetings Lists 
        $meetingsList = $meeting->get();

        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $meetingsList,  
            'id' => $meetings_id,  
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

        // Return response
        $data = array(
            'status' => true,  
            'message' => 'Booking Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 