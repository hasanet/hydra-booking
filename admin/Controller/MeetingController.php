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

        register_rest_route('hydra-booking/v1', '/meetings/integration/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateMeetingIntegration'),
        ));   
        register_rest_route('hydra-booking/v1', '/meetings/integration/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'deleteMeetingIntegration'),
        ));
        register_rest_route('hydra-booking/v1', '/meetings/integration/fields', array(
            'methods' => 'POST',
            'callback' => array($this, 'getZohoModulsFields'),
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

                
        register_rest_route('hydra-booking/v1', '/meetings/question/forms-list', array(
            'methods' => 'POST',
            'callback' => array($this, 'getQuestionFormsList'),
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
    
        $key = isset($request['key']) ? $request['key'] : '';
    
        // New webhook data to be updated
        $newWebHookdata = array(
            'webhook' => !empty($request['webhook']) ? $request['webhook'] : '',
            'url' => !empty($request['url']) ? $request['url'] : '',
            'request_method' => !empty($request['request_method']) ? $request['request_method'] : '',
            'request_format' => !empty($request['request_format']) ? $request['request_format'] : '',
            'events' => !empty($request['events']) ? $request['events'] : '',
            'request_body' => !empty($request['request_body']) ? $request['request_body'] : '',
            'request_header' => !empty($request['request_header']) ? $request['request_header'] : '',
            'headers' => !empty($request['headers']) ? $request['headers'] : '',
            'bodys' => !empty($request['bodys']) ? $request['bodys'] : '',
            'status' => !empty($request['status']) ? $request['status'] : '',
        );
    
        if ($key !== '' && isset($webHookdata[$key])) {
            // Update the existing webhook data at the specified key
            $webHookdata[$key] = $newWebHookdata;
        } else {
            // Append the new webhook data
            $webHookdata[] = $newWebHookdata;
        }
    
        // Encode the updated webhook data back to JSON
        $encodedWebHookdata = json_encode($webHookdata);
    
        $data = [
            'id'      => $request['meeting_id'],
            'webhook' => $encodedWebHookdata,
        ];
    
        // Update the meeting with the new webhook data
        $MeetingUpdate = $meeting->update($data);
    
        // Retrieve updated meeting data
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

    // Integrations
    public function updateMeetingIntegration() {
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($request['meeting_id']);
    
        // Decode existing integrations data if it exists
        $Integrationsdata = !empty($MeetingData->integrations) ? json_decode($MeetingData->integrations, true) : array();
    
        $key = isset($request['key']) ? $request['key'] : '';
    
        // New webhook data to be updated
        $newIntegrationsdata = array(
            'title' => !empty($request['title']) ? $request['title'] : '',
            'webhook' => !empty($request['webhook']) ? $request['webhook'] : '',
            'bodys' => !empty($request['bodys']) ? $request['bodys'] : '',
            'events' => !empty($request['events']) ? $request['events'] : '',
            'audience' => "Mailchimp"==$request['webhook'] && !empty($request['audience']) ? $request['audience'] : '',
            'tags' => "FluentCRM"==$request['webhook'] && !empty($request['tags']) ? $request['tags'] : '',
            'lists' => "FluentCRM"==$request['webhook'] && !empty($request['lists']) ? $request['lists'] : '',
            'modules' => "ZohoCRM"==$request['webhook'] && !empty($request['modules']) ? $request['modules'] : '',
            'fields' => !empty($request['fields']) ? $request['fields'] : '',
            'status' => !empty($request['status']) ? $request['status'] : '',
        );
    
        if ($key !== '' && isset($Integrationsdata[$key])) {
            // Update the existing webhook data at the specified key
            $Integrationsdata[$key] = $newIntegrationsdata;
        } else {
            // Append the new webhook data
            $Integrationsdata[] = $newIntegrationsdata;
        }
    
        // Encode the updated webhook data back to JSON
        $encodedIntegrationsdata = json_encode($Integrationsdata);
    
        $data = [
            'id'      => $request['meeting_id'],
            'integrations' => $encodedIntegrationsdata,
        ];
    
        // Update the meeting with the new webhook data
        $MeetingUpdate = $meeting->update($data);
    
        // Retrieve updated meeting data
        $updateMeetingData = $meeting->get($request['meeting_id']);
        
        return rest_ensure_response(array(
            'status' => true,
            'integrations' => $updateMeetingData->integrations,
            'message' => 'Integrations Successfully Updated!',
        ));
    }       

    // Integration Delete
    public function deleteMeetingIntegration($request){
        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get($request['meeting_id']);

        $key = $request['key'];

        // Decode existing webhook data if it exists
        $Integrationsdata = !empty($MeetingData->integrations) ? json_decode($MeetingData->integrations, true) : array();

        // Check if the key exists in the array
        if (isset($Integrationsdata[$key])) {
            // Remove the element at the specified key
            unset($Integrationsdata[$key]);

            // Re-index the array to maintain sequential keys
            $Integrationsdata = array_values($Integrationsdata);

            // Encode the updated Integrations data back to JSON
            $encodedIntegrationsdata = json_encode($Integrationsdata);

            // Update the meeting with the new Integrations data
            $data = [
                'id'      => $request['meeting_id'],
                'integrations' => $encodedIntegrationsdata,
            ];
            $MeetingUpdate = $meeting->update($data);
            $updateMeetingData = $meeting->get($request['meeting_id']);

            return rest_ensure_response(array(
                'status' => true,
                'integrations' => $updateMeetingData->integrations,
                'message' => 'Integrations Successfully Deleted!',
            ));
        } else {
            return rest_ensure_response(array(
                'status' => false,
                'message' => 'Integrations key does not exist!',
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

        $current_user = wp_get_current_user();
		// get user role
		$current_user_role = ! empty( $current_user->roles[0] ) ? $current_user->roles[0] : '';
        $current_user_id = $current_user->ID;

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
            'data' => $current_user_id,  
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

        // Integration
        $integrations = array();

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
        // if(empty($MeetingData->payment_meta)){
        //     $MeetingData->payment_meta = $_tfhb_integration_settings;
        // }

        // Time Zone 
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

        // WooCommerce Product
        $woo_commerce = new  WooBooking();
        $wc_product =  $woo_commerce->getAllProductList();

        // google  Meeting
        if($_tfhb_integration_settings['google_calendar']){
            $integrations['google_calendar_status'] = isset($_tfhb_integration_settings['google_calendar']['status']) ? $_tfhb_integration_settings['google_calendar']['status'] : 0; 
        }
        // Zoom Meeting
        if($_tfhb_integration_settings['zoom_meeting']){
            $integrations['zoom_meeting_status'] = isset($_tfhb_integration_settings['zoom_meeting']['status']) ? $_tfhb_integration_settings['zoom_meeting']['status'] : 0; 
        }

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

        $_tfhb_host_integration_settings = !empty($MeetingData->host_id) ? get_user_meta($MeetingData->host_id, '_tfhb_host_integration_settings', true) : ''; 
        $api_key = !empty($_tfhb_integration_settings['mailchimp']['key']) ? $_tfhb_integration_settings['mailchimp']['key'] : '';
        $api_key = !empty($_tfhb_host_integration_settings['mailchimp']['key']) ? $_tfhb_host_integration_settings['mailchimp']['key'] : $api_key;
        $mailchimp_Data = [];
        if ( $api_key != '' ) {
			$response = $this->set_config( $api_key, 'ping' );
			$response = json_decode( $response );
			if ( isset( $response->health_status ) ) { //Display success message
				$mailchimp_Data['status'] = true;
                $aud_lists = $this->get_audiance($api_key);
                $mailchimp_Data['audience'] = $aud_lists;
			} else {
				$mailchimp_Data['status'] = false;
			}
		}else{
            $mailchimp_Data['status'] = false;
        }

        // FluentCRM
        $fluentcrm_Data = [];
        if(!file_exists(WP_PLUGIN_DIR . '/' . 'fluent-crm/fluent-crm.php')){
            $fluentcrm_Data['status'] = false;

        } else if(!is_plugin_active( 'fluent-crm/fluent-crm.php')){
            $fluentcrm_Data['status'] = false;
        }else{
            $fluentcrm_Data['status'] = true;
        } 
        if($fluentcrm_Data['status']){
            global $wpdb;
            // Check if table exists
            $fluent_crm_tags = $wpdb->prefix . 'fc_tags';
            $fluent_crm_lists = $wpdb->prefix . 'fc_lists';
            $tags_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$fluent_crm_tags'") == $fluent_crm_tags;
            $lists_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$fluent_crm_lists'") == $fluent_crm_lists;

            if ($tags_table_exists) {
                // Table exists, retrieve data
                $results = $wpdb->get_results("SELECT id, title FROM $fluent_crm_tags", ARRAY_A);

                // Check if results are not empty
                if (!empty($results)) {
                    // Output the results as an array
                    $tags_array = array();
                    foreach ($results as $row) {
                        $tags_array[] = array(
                            'name' => $row['title'],
                            'value' => $row['id']
                        );
                    }
                    $fluentcrm_Data['tags'] = $tags_array;
                }
            }

            if ($lists_table_exists) {
                // Table exists, retrieve data
                $results = $wpdb->get_results("SELECT id, title FROM $fluent_crm_lists", ARRAY_A);

                // Check if results are not empty
                if (!empty($results)) {
                    // Output the results as an array
                    $lists_array = array();
                    foreach ($results as $row) {
                        $lists_array[] = array(
                            'name' => $row['title'],
                            'value' => $row['id']
                        );
                    }
                    $fluentcrm_Data['lists'] = $lists_array;
                }
            }
        }

        // Zoho
        $client_id = !empty($_tfhb_host_integration_settings['zoho']['client_id']) ? $_tfhb_host_integration_settings['zoho']['client_id'] : '';
        $client_secret = !empty($_tfhb_host_integration_settings['zoho']['client_secret']) ? $_tfhb_host_integration_settings['zoho']['client_secret'] : '';
        $access_token = !empty($_tfhb_host_integration_settings['zoho']['access_token']) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
        $zoho_modules = !empty($_tfhb_host_integration_settings['zoho']['modules']) ? json_decode($_tfhb_host_integration_settings['zoho']['modules']) : '';

        $zohocrm_Data = [];
        if(!empty($access_token)){
            $zohocrm_Data['status'] = true;
            $zohocrm_Data['modules'] = $zoho_modules;
        }else{
            $zohocrm_Data['status'] = false;
        }

        // Fetch Questions Data
        $questions_form_type = !empty($MeetingData->questions_form_type) ? $MeetingData->questions_form_type : '';
        $questions_form = !empty($MeetingData->questions_form) ? $MeetingData->questions_form : 0;
        $formsList = array();
        if( $questions_form_type ){
            $formsList = $this->getQuestionFormsData($questions_form_type);
        }

        // Return response
        $data = array(
            'status' => true, 
            'meeting' => $MeetingData,  
            'time_zone' => $time_zone,  
            'wc_product' => $wc_product,  
            'meeting_category' => $term_array,
            'mailchimp' => $mailchimp_Data,
            'fluentcrm' => $fluentcrm_Data,
            'zohocrm' => $zohocrm_Data,
            'formsList' => $formsList,
            'integrations' => $integrations,
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
            'questions_type'          => isset($request['questions_type']) ? sanitize_text_field($request['questions_type']) : '',
            'questions_form_type'          => isset($request['questions_form_type']) ? sanitize_text_field($request['questions_form_type']) : '',
            'questions_form'          => isset($request['questions_form']) ? sanitize_text_field($request['questions_form']) : '',
            'questions'                 => isset($request['questions']) ? $request['questions'] : '',
            'notification'              => isset($request['notification']) ? $request['notification'] : '',
            'payment_status'            => isset($request['payment_status']) ? sanitize_text_field($request['payment_status']) : '',
            'meeting_price'             => isset($request['meeting_price']) ? sanitize_text_field($request['meeting_price']) : '',
            'payment_currency'          => isset($request['payment_currency']) ? sanitize_text_field($request['payment_currency']) : '',
            'payment_method'            => isset($request['payment_method']) ? sanitize_text_field($request['payment_method'])  : '',
            'max_book_per_slot'            => isset($request['max_book_per_slot']) ? sanitize_text_field($request['max_book_per_slot'])  : '',
            'is_display_max_book_slot'            => isset($request['is_display_max_book_slot']) ? sanitize_text_field($request['is_display_max_book_slot'])  : '',
            'payment_meta'              => isset($request['payment_meta']) ? $request['payment_meta'] : '',
            'updated_at'                => date('Y-m-d'),
            'updated_by'                => $current_user_id
        ];

        // if Payment Methood is woo_payment
        if('woo_payment' == $data['payment_method']){
            $products = wc_get_product($data['payment_meta']['product_id']);
            $data['meeting_price'] = $products->price;

        }

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

    /* Mailchimp audiance List */
    private function get_audiance($api_key){

        $response = $this->set_config( $api_key, 'lists' );
        $audience = array();
        $response = json_decode( $response, true );
        $x = 0;
        if ( isset( $response['lists'] ) && $response != null ) {
            foreach ( $response['lists'] as $list ) {
                $audience[] = array(
                    'name' => $list['name'],
                    'value' => "".$list['id']."",
                );

                $x++;
            }
        }
        return $audience;
    }

    /* Mailchimp config set */
	private function set_config( $api_key = '', $path = '' ) {


		$server_prefix = explode( "-", $api_key );

		if ( ! isset( $server_prefix[1] ) ) {
			return;
		}
		$server_prefix = $server_prefix[1];

		$url = "https://$server_prefix.api.mailchimp.com/3.0/$path";

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		$headers = array(
			"Authorization: Bearer $api_key"
		);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		//for debug only!
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

		$resp = curl_exec( $curl );
		curl_close( $curl );

		return $resp;
	}

    /* Modules Fileds */
    public function getZohoModulsFields($request){
        $host = !empty($request['host_id']) ? $request['host_id'] : '';
        $hook_type = !empty($request['webhook']) ? $request['webhook'] : '';
        
        $_tfhb_host_integration_settings =  is_array(get_user_meta($host, '_tfhb_host_integration_settings', true)) ? get_user_meta($host, '_tfhb_host_integration_settings', true) : array();

        if('ZohoCRM'==$hook_type){
            $access_token = !empty($_tfhb_host_integration_settings['zoho']['access_token']) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
            $access_token = $this->refreshToken($host);
            // The Zoho CRM API URL to get all modules
            $api_url = 'https://www.zohoapis.com/crm/v6/settings/fields?module='.$request['module'];

            // Initialize cURL session
            $ch = curl_init();
            // Set the URL and other necessary options
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Set the headers, including the authorization token
            $headers = [
                'Authorization: Zoho-oauthtoken ' . $access_token,
                'Content-Type: application/json'
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Execute the cURL session and fetch the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            // Close the cURL session
            curl_close($ch);

            // Decode the JSON response
            $response_data = json_decode($response, true);

            $fields = [];
            $response_data = json_decode($response, true);
            if (isset($response_data['fields'])) {
                // Loop through each field and print its name
                foreach ($response_data['fields'] as $field) {
                    $fields[] =  array(
                        'name' =>  $field['field_label'],
                        'value' => $field['api_name']
                    );
                }
            }
        }elseif('Mailchimp'==$hook_type){
            $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
            $api_key = !empty($_tfhb_integration_settings['mailchimp']['key']) ? $_tfhb_integration_settings['mailchimp']['key'] : '';
            $api_key = !empty($_tfhb_host_integration_settings['mailchimp']['key']) ? $_tfhb_host_integration_settings['mailchimp']['key'] : $api_key;

            $mail_fields = $this->get_mailchimp_fields($api_key, $request['module']);
            if(!empty($mail_fields)){
                $fields = array(
                    array(
                        'name' => 'Email Address',
                        'value' => 'EMAIL'
                    )
                );
                $mail_fields = json_decode($mail_fields); 
                if(!empty($mail_fields->merge_fields)){
                    foreach($mail_fields->merge_fields as $field){
                        $fields[] = array(
                            'name' => $field->name,
                            'value' => $field->tag
                        );
                    }
                }
            }
        }else{
            $fields = array(
                array(
                    'name' => 'First Name',
                    'value' => 'first_name'
                ),
                array(
                    'name' => 'Last Name',
                    'value' => 'last_name'
                ),
                array(
                    'name' => 'Email',
                    'value' => 'email'
                ),
                array(
                    'name' => 'Phone',
                    'value' => 'phone'
                ),
                array(
                    'name' => 'Timezone',
                    'value' => 'timezone'
                ),
                array(
                    'name' => 'Address',
                    'value' => 'address_line_1'
                ),
                array(
                    'name' => 'Postal Code',
                    'value' => 'postal_code'
                ),
                array(
                    'name' => 'City',
                    'value' => 'city'
                ),
                array(
                    'name' => 'Country',
                    'value' => 'country'
                )
            );
        }
        
        // Return response
        $data = array(
            'status' => true, 
            'fields' => $fields,  
            'message' => 'Fields Data',
        );
        return rest_ensure_response($data);

    }


    /* Mailchimp Fields */
	private function get_mailchimp_fields( $api_key = '', $module = '' ) {

		$server_prefix = explode( "-", $api_key );

		if ( ! isset( $server_prefix[1] ) ) {
			return;
		}
		$server_prefix = $server_prefix[1];

		$url = "https://$server_prefix.api.mailchimp.com/3.0/lists/$module/merge-fields";
		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		$headers = array(
			"Authorization: Bearer $api_key"
		);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		//for debug only!
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

		$resp = curl_exec( $curl );
		curl_close( $curl );

		return $resp;
	}

    // Refresh Token
	public function refreshToken($host){
		$_tfhb_host_integration_settings =  is_array(get_user_meta($host, '_tfhb_host_integration_settings', true)) ? get_user_meta($host, '_tfhb_host_integration_settings', true) : array();

		$client_id = !empty($_tfhb_host_integration_settings['zoho']['client_id']) ? $_tfhb_host_integration_settings['zoho']['client_id'] : '';
		$client_secret = !empty($_tfhb_host_integration_settings['zoho']['client_secret']) ? $_tfhb_host_integration_settings['zoho']['client_secret'] : '';
		$access_token = !empty($_tfhb_host_integration_settings['zoho']['access_token']) ? $_tfhb_host_integration_settings['zoho']['access_token'] : '';
		$refresh_token = !empty($_tfhb_host_integration_settings['zoho']['refresh_token']) ? $_tfhb_host_integration_settings['zoho']['refresh_token'] : '';

		$url = "https://accounts.zoho.com/oauth/v2/token";
		$data = array(
			'grant_type' => 'refresh_token',
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'refresh_token' => $refresh_token
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$response_data = json_decode($response, true);
		
		if( !empty($response_data['access_token']) ){
			$_tfhb_host_integration_settings['zoho']['access_token'] = $response_data['access_token'];

			// save to user metadata
			update_user_meta($host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);

			return $response_data['access_token'];
		}
	}

    // Meeting Questions Forms List
    public function getQuestionFormsList(){
        $request = json_decode(file_get_contents('php://input'), true);
        $form_type = $request['form_type'];
        $questionForms = $this->getQuestionFormsData($form_type);

        $data = array(
            'status' => true, 
            'questionForms' => $questionForms,  
            'message' => 'Question Forms Data',
        );
        return rest_ensure_response($data);
    }
    // Fetch Forms list based on form Types
    public function getQuestionFormsData($form_type){
        $questionForms = [];
        if($form_type == 'wpcf7'){
            $args = array(
                'post_type' => 'wpcf7_contact_form',
                'posts_per_page' => -1,
            );
            $forms = get_posts($args);
            
            foreach ($forms as $form) {
                $questionForms[] = array(
                    'name' => $form->post_title,
                    'value' => $form->ID
                );
            } 
        }else if($form_type == 'fluent-forms'){
            $args = array(
                'post_type' => 'forminator_forms',
                'posts_per_page' => -1,
            );
            $forms = get_posts($args);
            
            foreach ($forms as $form) {
                $questionForms[] = array(
                    'name' => $form->post_title,
                    'value' => $form->ID
                );
            } 
        }else if($form_type == 'fluent-forms'){
            // Query arguments get custom fluentform_forms data all into custom database table
             global $wpdb;
            $table_name = $wpdb->prefix . 'fluentform_forms';
            $results = $wpdb->get_results("SELECT id, title FROM $table_name");
            foreach ($results as $form) {
                $questionForms[] = array(
                    'name' => $form->title,
                    'value' => $form->id
                );
            }
        }else if($form_type == 'gravityforms'){
            // Query arguments get custom fluentform_forms data all into custom database table
             global $wpdb;
            $table_name = $wpdb->prefix . 'gf_form';
            $results = $wpdb->get_results("SELECT id, title FROM $table_name");
            foreach ($results as $form) {
                $questionForms[] = array(
                    'name' => $form->title,
                    'value' => $form->id
                );
            }
        }
        return $questionForms;
    }

} 