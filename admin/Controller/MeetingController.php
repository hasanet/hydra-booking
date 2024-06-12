<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 use HydraBooking\Services\Integrations\Woocommerce\WooBooking;
 
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
        // Get Single Meeting based on id
        register_rest_route('hydra-booking/v1', '/meetings/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getMeetingData'),
        ));
        register_rest_route('hydra-booking/v1', '/meetings/details/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateMeeting'),
        ));   

        register_rest_route('hydra-booking/v1', '/meetings/webhook/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateMeetingWebhook'),
        ));   
        register_rest_route('hydra-booking/v1', '/meetings/webhook/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'deleteMeetingWebhook'),
        ));

        register_rest_route('hydra-booking/v1', '/meetings/filter', array(
            'methods' => 'GET',
            'callback' => array($this, 'filterMeetings'),
            'args' => array(
                'title' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
        ));

        // Meeting Category Endpoint
        register_rest_route('hydra-booking/v1', '/meetings/categories', array(
            'methods' => 'GET',
            'callback' => array($this, 'getMeetingsCategories'),
        )); 
        register_rest_route('hydra-booking/v1', '/meetings/categories/create-update', array(
            'methods' => 'POST',
            'callback' => array($this, 'createupdateMeeting'),
        ));  
        register_rest_route('hydra-booking/v1', '/meetings/categories/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'DeleteCategory'),
        )); 


        // Get Single Host based on id
        register_rest_route('hydra-booking/v1', '/meetings/single-host-availability/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getTheHostAvailabilityData'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
    }
    // Meeting List
    public function getMeetingsData() { 

        $current_user = wp_get_current_user();
		// get user role
		$current_user_role = ! empty( $current_user->roles[0] ) ? $current_user->roles[0] : '';
        $current_user_id = $current_user->ID;

        // Meeting Lists 
        $meeting = new Meeting();

        if(!empty($current_user_role) && "administrator"==$current_user_role){
            $MeetingsList = $meeting->get();
        }

        if(!empty($current_user_role) && "tfhb_host"==$current_user_role){
            $MeetingsList = $meeting->get(null, null, $current_user_id);
        }

        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'Meeting Data Successfully Retrieve!',
        );
        return rest_ensure_response($data);
    }  

    // getMeetingsCategories List
    public function getMeetingsCategories() { 

        $terms = get_terms(array(
            'taxonomy' => 'meeting_category',
            'hide_empty' => false, // Set to true to hide empty terms
        ));
        // Prepare the response data
        $term_array = array();
        foreach ($terms as $term) {
            $term_array[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'description' => $term->description,
                'slug' => $term->slug
            );
        }

        // Return response
        $data = array(
            'status' => true, 
            'category' => $term_array,  
            'message' => 'Meeting Category Data Successfully Retrieve!',
        );
        return rest_ensure_response($data);
    }  

    //createupdateMeeting
    public function createupdateMeeting(){
        $request = json_decode(file_get_contents('php://input'), true);
    
        // Sanitize data
        $title = !empty($request['title']) ? sanitize_text_field($request['title']) : 'No Title';
        $description = !empty($request['description']) ? sanitize_text_field($request['description']) : '';
        
        // Check if taxonomy is registered
        if (!taxonomy_exists('meeting_category')) {
            return rest_ensure_response(array(
                'status' => false,
                'message' => 'Invalid taxonomy.'
            ));
        }
    
        if (empty($request['id'])) {
            // Insert the term
            $term = wp_insert_term(
                $title,   // The term
                'meeting_category', // The taxonomy
                array(
                    'description' => $description,
                    'slug'        => sanitize_title($title)
                )
            );
    
            // Check for errors
            if (is_wp_error($term)) {
                return rest_ensure_response(array(
                    'status' => false,
                    'message' => $term->get_error_message(),
                ));
            }

        } else {
            // Update the term
            $term_id = intval($request['id']);
            $term = wp_update_term(
                $term_id,
                'meeting_category',
                array(
                    'name'        => $title,
                    'description' => $description,
                    'slug'        => sanitize_title($title)
                )
            );
    
            // Check for errors
            if (is_wp_error($term)) {
                return rest_ensure_response(array(
                    'status' => false,
                    'message' => $term->get_error_message(),
                ));
            }
        }

        $terms = get_terms(array(
            'taxonomy' => 'meeting_category',
            'hide_empty' => false, // Set to true to hide empty terms
        ));
        // Prepare the response data
        $term_array = array();
        foreach ($terms as $term) {
            $term_array[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'description' => $term->description,
                'slug' => $term->slug
            );
        }

        // Success response
        return rest_ensure_response(array(
            'status' => true,
            'category' => $term_array,
            'message' => empty($request['id']) ? 'Meeting Category Successfully Added!' : 'Meeting Category Successfully Updated!',
        ));

    }    

    // Webhook Integrations
    public function updateMeetingWebhook() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($request['meeting_id']);
    
        // Decode existing webhook data if it exists
        $webHookdata = !empty($MeetingData->webhook) ? json_decode($MeetingData->webhook, true) : array();
        
        // Update webhook data with new request data
        $newWebHookdata = array(
            'webhook' => !empty($request['webhook']) ? $request['webhook'] : '',
            'url' => !empty($request['url']) ? $request['url'] : '',
            'request_method' => !empty($request['request_method']) ? $request['request_method'] : '',
            'request_format' => !empty($request['request_format']) ? $request['request_format'] : '',
            'events' => !empty($request['events']) ? $request['events'] : '',
            'request_body' => !empty($request['request_body']) ? $request['request_body'] : '',
            'status' => !empty($request['status']) ? $request['status'] : '',
        );
    
        // Merge the new webhook data with the existing one
        $webHookdata[] = $newWebHookdata;

        // Encode the updated webhook data back to JSON
        $encodedWebHookdata = json_encode($webHookdata);

        $data = [
            'id'      => $request['meeting_id'],
            'webhook' => $encodedWebHookdata,
        ];
    
        // Update the meeting with the new webhook data
        $MeetingUpdate = $meeting->update($data);

        $updateMeetingData = $meeting->get($request['meeting_id']);
        
        return rest_ensure_response(array(
            'status' => true,
            'webhook' => $updateMeetingData->webhook,
            'message' => 'Webhook Successfully Updated!',
        ));
    }    

    // Webhook Delete
    public function deleteMeetingWebhook($request){
        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($request['meeting_id']);

        $key = $request['key'];

        // Decode existing webhook data if it exists
        $webHookdata = !empty($MeetingData->webhook) ? json_decode($MeetingData->webhook, true) : array();

        // Check if the key exists in the array
        if (isset($webHookdata[$key])) {
            // Remove the element at the specified key
            unset($webHookdata[$key]);

            // Re-index the array to maintain sequential keys
            $webHookdata = array_values($webHookdata);

            // Encode the updated webhook data back to JSON
            $encodedWebHookdata = json_encode($webHookdata);

            // Update the meeting with the new webhook data
            $data = [
                'id'      => $request['meeting_id'],
                'webhook' => $encodedWebHookdata,
            ];
            $MeetingUpdate = $meeting->update($data);
            $updateMeetingData = $meeting->get($request['meeting_id']);

            return rest_ensure_response(array(
                'status' => true,
                'webhook' => $updateMeetingData->webhook,
                'message' => 'Webhook Successfully Deleted!',
            ));
        } else {
            return rest_ensure_response(array(
                'status' => false,
                'message' => 'Webhook key does not exist!',
            ));
        }
    }

    // Category Delete
    public function DeleteCategory(){
        $request = json_decode(file_get_contents('php://input'), true);

        if (empty($request['id'])) {
            return rest_ensure_response(array(
                'status' => false,
                'message' => 'Term ID is required.'
            ));
        }
        $term_id = intval($request['id']);
        $result = wp_delete_term($term_id, 'meeting_category');

        $terms = get_terms(array(
            'taxonomy' => 'meeting_category',
            'hide_empty' => false, // Set to true to hide empty terms
        ));
        // Prepare the response data
        $term_array = array();
        foreach ($terms as $term) {
            $term_array[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'description' => $term->description,
                'slug' => $term->slug
            );
        }
        
        return rest_ensure_response(array(
            'status' => true,
            'category' => $term_array,
            'message' => 'Meeting Category Successfully Deleted!',
        ));
    }

    // Meeting Filter 
    public function filterMeetings($request) {
        $filterData = $request->get_param('filterData');
        // Meeting Lists 
        $meeting = new Meeting();
        $MeetingsList = $meeting->get('',$filterData);

        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'Meeting Data Successfully Retrieve!',
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

        // Meetings Id into Post Meta
        update_post_meta( $meeting_post_id, '__tfhb_meeting_id', $meetings_id );

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

    // Delete Meeting
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

        // Time Zone 
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

        // WooCommerce Product
        $woo_commerce = new  WooBooking();
        $wc_product =  $woo_commerce->getAllProductList();

        // Meeting Category
        $terms = get_terms(array(
            'taxonomy' => 'meeting_category',
            'hide_empty' => false, // Set to true to hide empty terms
        ));
        // Prepare the response data
        $term_array = array();
        foreach ($terms as $term) {
            $term_array[] = array(
                'name' => $term->name,
                'value' => "".$term->term_id."",
            );
        }

        // Return response
        $data = array(
            'status' => true, 
            'meeting' => $MeetingData,  
            'time_zone' => $time_zone,  
            'wc_product' => $wc_product,  
            'meeting_category' => $term_array,
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
            'id'                        => $request['id'],
            'user_id'                   => $request['user_id'],
            'title'                     => isset($request['title']) ? sanitize_text_field($request['title']) : '',
            'host_id'                   => isset($request['host_id']) ? sanitize_key($request['host_id']) : '',
            'description'               => isset($request['description']) ? sanitize_text_field($request['description']) : '',
            'meeting_type'              => isset($request['meeting_type']) ? sanitize_text_field($request['meeting_type']) : '',
            'duration'                  => isset($request['duration']) ? sanitize_text_field($request['duration']) : '',
            'custom_duration'           => isset($request['custom_duration']) ? sanitize_text_field($request['custom_duration']) : '',
            'meeting_locations'         => isset($request['meeting_locations']) ? $request['meeting_locations'] : '',
            'meeting_category'          => isset($request['meeting_category']) ? sanitize_text_field($request['meeting_category']) : '',
            'availability_type'         => isset($request['availability_type']) ? sanitize_text_field($request['availability_type']) : '',
            'availability_range_type'   => isset($request['availability_range_type']) ? sanitize_text_field($request['availability_range_type']) : '',
            'availability_range'        => isset($request['availability_range']) ? $request['availability_range'] : '',
            'availability_id'           => isset($request['availability_id']) ? sanitize_text_field($request['availability_id']) : '',
            'availability_custom'       => isset($request['availability_custom']) ?$request['availability_custom'] : '',
            'buffer_time_before'        => isset($request['buffer_time_before']) ? sanitize_text_field($request['buffer_time_before']) : '',
            'buffer_time_after'         => isset($request['buffer_time_after']) ? sanitize_text_field($request['buffer_time_after']) : '',
            'booking_frequency'         => isset($request['booking_frequency']) ? $request['booking_frequency'] : '',
            'meeting_interval'          => isset($request['meeting_interval']) ? sanitize_text_field($request['meeting_interval']) : '',
            'recurring_status'          => isset($request['recurring_status']) ? sanitize_text_field($request['recurring_status']) : '',
            'recurring_repeat'          => isset($request['recurring_repeat']) ? $request['recurring_repeat'] : '',
            'recurring_maximum'         => isset($request['recurring_maximum']) ? sanitize_text_field($request['recurring_maximum']) : '',
            'attendee_can_cancel'       => isset($request['attendee_can_cancel']) ? sanitize_text_field($request['attendee_can_cancel']) : '',
            'attendee_can_reschedule'   => isset($request['attendee_can_reschedule']) ? sanitize_text_field($request['attendee_can_reschedule']) : '',
            'questions_status'          => isset($request['questions_status']) ? sanitize_text_field($request['questions_status']) : '',
            'questions'                 => isset($request['questions']) ? $request['questions'] : '',
            'notification'              => isset($request['notification']) ? $request['notification'] : '',
            'payment_status'            => isset($request['payment_status']) ? sanitize_text_field($request['payment_status']) : '',
            'meeting_price'             => isset($request['meeting_price']) ? sanitize_text_field($request['meeting_price']) : '',
            'payment_currency'          => isset($request['payment_currency']) ? sanitize_text_field($request['payment_currency']) : '',
            'payment_method'            => isset($request['payment_method']) ? sanitize_text_field($request['payment_method'])  : '',
            'payment_meta'              => isset($request['payment_meta']) ? $request['payment_meta'] : '',
            'updated_at'                => date('Y-m-d'),
            'updated_by'                => $current_user_id
        ];

        // Meeting Update into 
        $meeting_post_data = array(
            'ID'           => $MeetingData->post_id,
            'post_title'   => isset($request['title']) ? sanitize_text_field($request['title']) : '',
            'post_content' => isset($request['description']) ? sanitize_text_field($request['description']) : '',
            'post_author'  => $current_user_id,
            'post_name'    => isset($request['title']) ? sanitize_title($request['title']) : '',
        );
        wp_update_post( $meeting_post_data ); 

        $data['slug'] = get_post_field( 'post_name', $MeetingData->post_id );

        $meetingUpdate = $meeting->update($data);
        if(!$meetingUpdate['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while updating Meeting'));
        }

        //Updated Meeting post meta
        if( $MeetingData->post_id ){

            //Updated post meta
            update_post_meta( $MeetingData->post_id, '__tfhb_meeting_opt', $data );
 
        }

        // Return response
        $data = array(
            'status' => true,  
            'message' => 'Meeting Updated Successfully', 
            'data' => $data,  
        );
        return rest_ensure_response($data);
    }

    // Host availability
    public function getTheHostAvailabilityData($request){

        $id = $request['id']; 
        // Check if user is selected
        if (empty($id) || $id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        // Get Host
        $host = new Host();
        $HostData = $host->get( $id );
       
        if("settings"==$HostData->availability_type){
            if(!empty($HostData->availability_id)){
                $availability_id = $HostData->availability_id; 
                $availability = get_option('_tfhb_availability_settings');
    
                $filteredAvailability = array_filter($availability, function($item) use ($availability_id) {
                    return $item['id'] == $availability_id;
                });
            
                // If you expect only one result, you can extract the first item from the filtered array
                $HostData->availability = reset($filteredAvailability);
            }else{
                $HostData->availability = '';
            }
            
            
        }else{
            $_tfhb_host_availability_settings =  get_user_meta($HostData->user_id, '_tfhb_host', true);
            if(!empty($_tfhb_host_availability_settings['availability'])){
                $HostData->availability = $_tfhb_host_availability_settings['availability'];
            }
            if(empty($HostData)) {
                return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
            }
        }
        
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();


        // Return response
        $data = array(
            'status' => true, 
            'host' => $HostData,  
            'host_availble' => $HostData->availability_type,
            'time_zone' => $time_zone,
            'message' => 'Host Data',
        );
        return rest_ensure_response($data);

    }
} 