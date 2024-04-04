<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 
 // Use DB 
use HydraBooking\DB\Meeting;
use HydraBooking\DB\Host;

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
        
        // Create an array to store the post data for meeting the current row
        $meeting_post_data = array(
            'post_type'    => 'tfhb_meeting',
            'post_title'   => esc_html('No Title'),
            'post_status'  => 'publish',
            'post_author'  => $current_user_id
        );
        $meeting_post_id = wp_insert_post( $meeting_post_data );

        $data = [ 
            'user_id' => $current_user_id,
            'meeting_type' => isset($request_data['meeting_type']) ? sanitize_text_field($request_data['meeting_type']) : '', 
            'post_id' => $meeting_post_id,
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
        $meeting_id = $request['id'];
        $post_id = $request['post_id']; 
        if (empty($meeting_id) || $meeting_id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Meeting'));
        }
        // Delete Meeting
        $meeting = new Meeting();
        $meetingDelete = $meeting->delete($meeting_id);
        if(!$meetingDelete) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while deleting meeting'));
        }

        // Delete Post and Post Meta
        if ( !empty($post_id) ) {
            //Delete Post
            wp_delete_post($post_id, true);
            //Delete Post Meta
            delete_post_meta( $post_id, '__tfhb_meeting_opt' ); 
        }

        // Meeting Lists
        $MeetingsList = $meeting->get();
        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'Meeting Deleted Successfully', 
        );
        return rest_ensure_response($data);
    }

    // Get Single Meeting
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
        
        // Host List 
        $host = new Host();
        $HostsList = $host->get();
        $MeetingData->hosts = wp_json_encode($HostsList);

        // Availability
        $availability = get_option('_tfhb_availability_settings');
        $MeetingData->availability_seetings = $availability;

        // Notification
        if(empty($MeetingData->notification)){
            $_tfhb_notification_settings = get_option('_tfhb_notification_settings');
            $MeetingData->notification = $_tfhb_notification_settings;
        }

        // Integration
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        if(!file_exists(WP_PLUGIN_DIR . '/' . 'woocommerce/woocommerce.php')){
            $woo_connection_status =  0;

        } else if(!is_plugin_active( 'woocommerce/woocommerce.php')){
            $woo_connection_status =  0;
        }else{
            $woo_connection_status =  1;
        } 

        if(!isset($_tfhb_integration_settings['woo_payment'])){
            $_tfhb_integration_settings['woo_payment']['type'] =  'type';
            $_tfhb_integration_settings['woo_payment']['status'] =  0;
            $_tfhb_integration_settings['woo_payment']['connection_status'] =  $woo_connection_status;
        }else{
            $_tfhb_integration_settings['woo_payment']['connection_status'] =  $woo_connection_status;
        }
        if(empty($MeetingData->payment_meta)){
            $MeetingData->payment_meta = $_tfhb_integration_settings;
        }
        
        // Return response
        $data = array(
            'status' => true, 
            'meeting' => $MeetingData,  
            'message' => 'Meeting Data',
        );
        return rest_ensure_response($data);

    }

    // Update Meeting Information
    public function updateMeeting(){
        $request = json_decode(file_get_contents('php://input'), true);
        // Check if user is selected
        $meeting_id = $request['id'];
        $user_id = $request['user_id']; 
        if (empty($meeting_id) || $meeting_id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Meeting'));
        }
        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get( $meeting_id );

        if(empty($MeetingData)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Meeting'));
        }

        // Get Current User
        $current_user = wp_get_current_user();
		// get user id
		$current_user_id = $current_user->ID;
        
        // Update Meeting
        $data = [ 
            'id' => $request['id'],
            'user_id' => $request['user_id'],
            'title' => isset($request['title']) ? sanitize_text_field($request['title']) : '',
            'description' => isset($request['description']) ? sanitize_text_field($request['description']) : '',
            'meeting_type' => isset($request['meeting_type']) ? sanitize_text_field($request['meeting_type']) : '',
            'duration' => isset($request['duration']) ? sanitize_text_field($request['duration']) : '',
            'meeting_locations' => isset($request['meeting_locations']) ? wp_json_encode($request['meeting_locations']) : '',
            'meeting_category' => isset($request['meeting_category']) ? sanitize_text_field($request['meeting_category']) : '',
            'availability_type' => isset($request['availability_type']) ? sanitize_text_field($request['availability_type']) : '',
            'availability_id' => isset($request['availability_id']) ? sanitize_text_field($request['availability_id']) : '',
            'availability_custom' => isset($request['availability_custom']) ? wp_json_encode($request['availability_custom']) : '',
            'buffer_time_before' => isset($request['buffer_time_before']) ? sanitize_text_field($request['buffer_time_before']) : '',
            'buffer_time_after' => isset($request['buffer_time_after']) ? sanitize_text_field($request['buffer_time_after']) : '',
            'booking_frequency' => isset($request['booking_frequency']) ? wp_json_encode($request['booking_frequency']) : '',
            'meeting_interval' => isset($request['meeting_interval']) ? sanitize_text_field($request['meeting_interval']) : '',
            'recurring_status' => isset($request['recurring_status']) ? sanitize_text_field($request['recurring_status']) : '',
            'recurring_repeat' => isset($request['recurring_repeat']) ? wp_json_encode($request['recurring_repeat']) : '',
            'recurring_maximum' => isset($request['recurring_maximum']) ? sanitize_text_field($request['recurring_maximum']) : '',
            'attendee_can_cancel' => isset($request['attendee_can_cancel']) ? sanitize_text_field($request['attendee_can_cancel']) : '',
            'attendee_can_reschedule' => isset($request['attendee_can_reschedule']) ? sanitize_text_field($request['attendee_can_reschedule']) : '',
            'questions_status' => isset($request['questions_status']) ? sanitize_text_field($request['questions_status']) : '',
            'questions' => isset($request['questions']) ? wp_json_encode($request['questions']) : '',
            'notification' => isset($request['notification']) ? wp_json_encode($request['notification']) : '',
            'payment_status' => isset($request['payment_status']) ? sanitize_text_field($request['payment_status']) : '',
            'meeting_price' => isset($request['meeting_price']) ? sanitize_text_field($request['meeting_price']) : '',
            'payment_currency' => isset($request['payment_currency']) ? sanitize_text_field($request['payment_currency']) : '',
            'payment_meta' => isset($request['payment_meta']) ? wp_json_encode($request['payment_meta']) : '',
            'updated_at' => date('Y-m-d'),
            'updated_by' => $current_user_id
        ];

        $meetingUpdate = $meeting->update($data);
        if(!$meetingUpdate['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while updating Meeting'));
        }

        //Updated Meeting post meta
        if( $MeetingData->post_id ){
            $meeting_post_data = array(
                'ID'           => $MeetingData->post_id,
                'post_title'   => isset($request['title']) ? sanitize_text_field($request['title']) : '',
                'post_content' => isset($request['description']) ? sanitize_text_field($request['description']) : '',
                'post_author'  => $current_user_id,
                'post_name'    => isset($request['title']) ? sanitize_title($request['title']) : '',
            );
            wp_update_post( $meeting_post_data ); 

            //Updated post meta
            update_post_meta( $MeetingData->post_id, '__tfhb_meeting_opt', $data );
        }

        // Return response
        $data = array(
            'status' => true,  
            'message' => 'Meeting Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 