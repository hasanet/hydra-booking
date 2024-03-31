<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 
 // Use DB 
use HydraBooking\DB\Meeting;

if ( ! defined( 'ABSPATH' ) ) { exit; }

  class MeetingController {
    

    // constaract
    public function __construct() { 
       
    }

    public function init() {
        
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/meetings/lists', array(
            'methods' => 'GET',
            'callback' => array($this, 'getMeetingsData'),
        ));  
        register_rest_route('hydra-booking/v1', '/meetings/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'CreateMeeting'),
        ));  
        register_rest_route('hydra-booking/v1', '/meetings/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'DeleteMeeting'),
        ));  
        // Get Single Host based on id
        register_rest_route('hydra-booking/v1', '/meetings/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getMeetingData'),
        ));
        register_rest_route('hydra-booking/v1', '/meetings/details/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateMeeting'),
        ));   
       
    }
    // Meeting List
    public function getMeetingsData() { 

        // Meeting Lists 
        $meeting = new Meeting();
        $MeetingsList = $meeting->get();

        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'General Settings Updated Successfully', 
        );
        return rest_ensure_response($data);
    }  

    // Create meetings
    public function CreateMeeting() { 
        $request = json_decode(file_get_contents('php://input'), true);
        $request_data = $request['data'];
        // Get Current User
        $current_user = wp_get_current_user();
		// get user id
		$current_user_id = $current_user->ID;
        
        $data = [ 
            'user_id' => $current_user_id,
            'meeting_type' => isset($request_data['meeting_type']) ? sanitize_text_field($request_data['meeting_type']) : '', 
            'created_by' => $current_user_id,
            'updated_by' => $current_user_id,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
            'status'    => 'draft'
        ];
        
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
            'message' => 'Meeting Created Successfully', 
        );
        
        return rest_ensure_response($data);
    }

    // Delete Host
    public function DeleteMeeting(){
        $request = json_decode(file_get_contents('php://input'), true);
        // Check if user is selected
        $host_id = $request['id'];
        $user_id = $request['user_id']; 
        if (empty($host_id) || $host_id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        // Delete Host
        $host = new Host();
        $hostDelete = $host->delete($host_id);
        if(!$hostDelete) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while deleting host'));
        }
        // Update user Option
        delete_user_meta($user_id, '_tfhb_host');
        // Hosts Lists
        $HostsList = $host->get();
        // Return response
        $data = array(
            'status' => true, 
            'hosts' => $HostsList,  
            'message' => 'Host Deleted Successfully', 
        );
        return rest_ensure_response($data);
    }

    // Get Single Host
    public function getMeetingData($request){
        $id = $request['id']; 
        // Check if user is selected
        if (empty($id) || $id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Meeting'));
        }
        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get( $id );

        if(empty($MeetingData)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Meeting'));
        }
        
        // Return response
        $data = array(
            'status' => true, 
            'meeting' => $MeetingData,  
            'message' => 'Meeting Data',
        );
        return rest_ensure_response($data);

    }

    // Update Host Information
    public function updateMeeting(){
        $request = json_decode(file_get_contents('php://input'), true);
        // Check if user is selected
        $host_id = $request['id'];
        $user_id = $request['user_id']; 
        if (empty($host_id) || $host_id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        // Get Host
        $host = new Host();
        $HostData = $host->get( $host_id );

        if(empty($HostData)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        // Update Host
        $data = [ 
            'id' => $request['id'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'about' => $request['about'],
            'avatar' => $request['avatar'],
            'featured_image' => $request['featured_image'],
            'time_zone' => $request['time_zone'],
            'status' => $request['status'], 
        ];
        $hostUpdate = $host->update($data);
        if(!$hostUpdate['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while updating host'));
        }
        // Update user Option
        $data['host_id'] = $host_id;
        update_user_meta($user_id, '_tfhb_host', $data);
        // Hosts Lists
        $HostsList = $host->get();
        // Return response
        $data = array(
            'status' => true,  
            'message' => 'Host Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 