<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 use HydraBooking\Services\Integrations\Zoom\ZoomServices;
 use HydraBooking\Services\Integrations\GoogleCalendar\GoogleCalendar;
 use HydraBooking\Services\Integrations\OutlookCalendar\OutlookCalendar;
 // Use DB 
use HydraBooking\DB\Host;
use HydraBooking\DB\Availability;
// exit
if ( ! defined( 'ABSPATH' ) ) { exit; }

  class HostsController {
    

    // constaract
    public function __construct() { 
        // add_action('admin_init', array($this, 'init'));
        
        // add_action('rest_api_init', array($this, 'create_endpoint'));
       
    }

    public function init() {
        
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/hosts/lists', array(
            'methods' => 'GET',
            'callback' => array($this, 'getHostsData'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
        register_rest_route('hydra-booking/v1', '/hosts/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'CreateHosts'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
        register_rest_route('hydra-booking/v1', '/hosts/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'DeleteHosts'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
        register_rest_route('hydra-booking/v1', '/hosts/update-status', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateHostsStatus'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
        // Get Single Host based on id
        register_rest_route('hydra-booking/v1', '/hosts/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getTheHostData'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
        register_rest_route('hydra-booking/v1', '/hosts/information/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateHostInformation'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));    

        // Availability 

        register_rest_route('hydra-booking/v1', '/hosts/availability/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateAvailabilitySettings'), 
            'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/hosts/availability', array(
            'methods' => 'POST',
            'callback' => array($this, 'GetAvailabilitySettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/hosts/availability/single', array(
            'methods' => 'POST',
            'callback' => array($this, 'GetSingleAvailabilitySettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/hosts/availability/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'DeleteAvailabilitySettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        // Integration 

        register_rest_route('hydra-booking/v1', '/hosts/integration', array(
            'methods' => 'POST',
            'callback' => array($this, 'GetIntegrationSettings'),
            'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/hosts/integration/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateIntegrationSettings'),
            'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/hosts/integration/fetch', array(
            'methods' => 'GET',
            'callback' => array($this, 'FetchIntegrationSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
        
        register_rest_route('hydra-booking/v1', '/hosts/filter', array(
            'methods' => 'GET',
            'callback' => array($this, 'filterHosts'),
            'args' => array(
                'title' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                )
            ),
        ));
       
        
    }
    // permission_callback
    public function getHostsData() { 
        // Get all wp users list with 
        $users = get_users(array('role__in' => array('administrator', 'editor', 'tfhb_host')));
        $userData = array();
        foreach ($users as $user) {
            $userData[$user->ID] =  $user->display_name . ' ( ' . $user->user_email . ' )'. ' - ( ' . $user->roles[0] . ' )';
        } 
        $userData[0] = __('Create new user', 'hydra-booking');

        // Hosts Lists 
        $host = new Host();
        $HostsList = $host->get();

        // Return response
        $data = array(
            'status' => true, 
            'users' => $userData, 
            'hosts' => $HostsList,  
            'message' => 'General Settings Updated Successfully', 
        );
        return rest_ensure_response($data);
    }  

    // Host Filter 
    public function filterHosts($request) {
        $filterData = $request->get_param('filterData');
        // Hosts Lists 
        $host = new Host();
        $HostsList = $host->get('', $filterData);

        // Return response
        $data = array(
            'status' => true, 
            'hosts' => $HostsList,  
            'message' => 'Host Data Successfully Retrieve!',
        );
        return rest_ensure_response($data);
    }

    // Create Hosts
    public function CreateHosts() { 
        $request = json_decode(file_get_contents('php://input'), true);

        // Check if user is selected
        if ( !isset($request['id']) && $request['id']  == '') {
            return rest_ensure_response(array('status' => false, 'message' => 'Select User'));
        }
        $user_id = $request['id'];

        if($user_id == 0){
            if(empty($request['username']) || empty($request['email']) || empty($request['password'])){
                return rest_ensure_response(array('status' => false, 'message' => 'Please fill all fields'));
            }

            // Create User with set user role  
            $user_id = wp_create_user(sanitize_text_field($request['username']), sanitize_text_field($request['password']), sanitize_text_field($request['email']));
            if(is_wp_error($user_id)){
                return rest_ensure_response(array('status' => false, 'message' => $user_id->get_error_message()));
            }

            // Set User Role
            $user = new \WP_User($user_id);
            $user->set_role('tfhb_host'); 

        
        }
        // Get user Data 
        $user = get_user_by('id', $user_id);
        
    

        // Check if user is valid
        if (empty($user)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid User'));
        }

        // Check if user is already a host
        $host = new Host();

        $hostCheck = $host->get( array('user_id' => $user_id) ); 
        if (!empty($hostCheck)) {
            return rest_ensure_response(array('status' => false, 'message' => 'This User is already a host'));
        }
        

        

        $data = [ 
            'user_id' => $user->ID, 
            'first_name' => get_user_meta( $user->ID, 'first_name', true ) != '' ? get_user_meta( $user->ID, 'first_name', true ) : $user->display_name,
            'last_name' => get_user_meta( $user->ID, 'last_name', true ) != '' ? get_user_meta( $user->ID, 'last_name', true ) : '',
            'email' => $user->user_email,
            'phone_number' => '',
            'time_zone' => '',
            'about' => '',
            'avatar' => '',
            'featured_image' => '', 
            'status' => 1, 
        ];

        // Insert Host
        $hostInsert = $host->add($data);
        if(!$hostInsert['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while creating host'));
        }
        $hosts_id = $data['user_id'];
        unset($data['user_id']);
        $data['host_id'] = $hostInsert['insert_id'];

        // Update user Option 
        update_user_meta($user_id, '_tfhb_host', $data);

        // Hosts Lists 
        $HostsList = $host->get();

        // Return response
        $data = array(
            'status' => true, 
            'hosts' => $HostsList,  
            'id' => $hosts_id,  
            'message' => 'General Settings Updated Successfully', 
        );
        
        return rest_ensure_response($data);
    }

    // Delete Host
    public function DeleteHosts(){
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

        // Delete the user
        require_once(ABSPATH.'wp-admin/includes/user.php' );
        $user_meta = get_userdata( $user_id );
        $user_roles = !empty($user_meta->roles[0]) ? $user_meta->roles[0] : '';
        if(!empty($user_roles) && "administrator"!=$user_roles){
            $deleted = wp_delete_user($user_id);
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

    // Delete Host
    public function getTheHostData($request){
        $id = $request['id']; 
        // Check if user is selected
        if (empty($id) || $id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        // Get Host
        $host = new Host();
        $HostData = $host->get( $id );
       

        $_tfhb_host_availability_settings =  get_user_meta($HostData->user_id, '_tfhb_host', true);
        if(!empty($_tfhb_host_availability_settings['availability'])){
            $HostData->availability = $_tfhb_host_availability_settings['availability'];
        }
        if(empty($HostData)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        if(!empty($HostData->others_information)){
            $HostData->others_information = json_decode($HostData->others_information);
        }
        
        
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

        // Hosts Global Settings.
        $_tfhb_hosts_settings = get_option('_tfhb_hosts_settings'); 

        // Return response
        $data = array(
            'status' => true, 
            'host' => $HostData,  
            'time_zone' => $time_zone,
            'hosts_settings' => $_tfhb_hosts_settings,
            'message' => 'Host Data',
        );
        return rest_ensure_response($data);

    }

    // Update Host Information
    public function updateHostInformation(){
        $request = json_decode(file_get_contents('php://input'), true);

        // return rest_ensure_response($request['others_information']);
        // Check if user is selected
        // $host_id = $request['id'];
        $host_id = $request['user_id']; 
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
            'availability_type' => $request['availability_type'],
            'others_information' => $request['others_information'],
            'availability_id' => $request['availability_id'],
            'time_zone' => $request['time_zone'],
            'status' => $request['status'], 
        ];
        $hostUpdate = $host->update($data);
        if(!$hostUpdate['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while updating host'));
        }
        // Update user Option
        $data['host_id'] = $host_id;
        $data['availability'] = $request['availability'];
        update_user_meta($host_id, '_tfhb_host', $data);
        // Hosts Lists
        $HostsList = $host->get();
        // Return response
        $data = array(
            'status' => true,  
            'message' => 'Host Updated Successfully', 
        );
        return rest_ensure_response($data);
    }

    public function UpdateHostsStatus(){
        $request = json_decode(file_get_contents('php://input'), true);
        

        $host_id = $request['user_id']; 
        if($request['status'] == 1 || 'deactivate' == $request['status']){
            $status = 'activate';
        }
        if('activate' == $request['status']){
            $status = 'deactivate';
        }
        
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
            'status' => $status, 
        ];
        $hostUpdate = $host->update($data);
        if(!$hostUpdate['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while updating host'));
        }

        //  Get User MEta
        $_tfhb_host = get_user_meta($host_id, '_tfhb_host', true);
        $_tfhb_host['status'] =  $status;
        
        // Update user Option 
        update_user_meta($host_id, '_tfhb_host', $_tfhb_host); 

        // Hosts Lists
        $HostsList = $host->get();

        // Return response
        $data = array(
            'status' => true,  
            'hosts' => $HostsList,  
            'message' => 'Host Status Updated Successfully',  
        );

        return rest_ensure_response($data);
    }

    // Get Integration Settings
    public function GetIntegrationSettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Get Host Data 
        $host_id = $request['id'];  
        $host = new Host();
        $hostData = $host->get( $host_id );
        $user_id = $hostData->user_id;


        $_tfhb_host_integration_settings =  is_array(get_user_meta($user_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($user_id, '_tfhb_host_integration_settings', true) : array(); 
 
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
 
        // Google Calendar API
        $google_calendar = isset($_tfhb_host_integration_settings['google_calendar']) ? $_tfhb_host_integration_settings['google_calendar'] : array();
       
        if($_tfhb_integration_settings['google_calendar']['status'] == true){  

            $google_calendar['type'] = 'google_calendar';
            $GoogleCalendar = new GoogleCalendar();  
            $google_calendar['access_url'] = $GoogleCalendar->GetAccessTokenUrl($user_id, ); 
            $google_calendar['status'] = $_tfhb_integration_settings['google_calendar']['status']; 
            $google_calendar['connection_status'] = $_tfhb_integration_settings['google_calendar']['connection_status'];  
          
            
            
        } 

        // Outlook Calendar API
        $outlook_calendar = isset($_tfhb_host_integration_settings['outlook_calendar']) ? $_tfhb_host_integration_settings['outlook_calendar'] : array();
 

        if($_tfhb_integration_settings['outlook_calendar']['status'] == true){  

            $outlook_calendar['type'] = 'outlook_calendar';
            $OutlookCalendar = new OutlookCalendar();  
            $outlook_calendar['access_url'] = $OutlookCalendar->GetAccessTokenUrl($user_id ); 
            $outlook_calendar['status'] = $_tfhb_integration_settings['outlook_calendar']['status']; 
            $outlook_calendar['connection_status'] = $_tfhb_integration_settings['outlook_calendar']['connection_status'];  
          
            
            
        } 

        // Apple Calendar  
        $apple_calendar = isset($_tfhb_host_integration_settings['apple_calendar']) ? $_tfhb_host_integration_settings['apple_calendar'] : array();
 

        if($_tfhb_integration_settings['apple_calendar']['connection_status'] == true){  

            $apple_calendar['type'] = 'calendar';  
            $apple_calendar['status'] = $_tfhb_integration_settings['apple_calendar']['status']; 
            $apple_calendar['connection_status'] = $_tfhb_integration_settings['apple_calendar']['connection_status'];  
            $apple_calendar['apple_id'] = isset($apple_calendar['apple_id']) ? $apple_calendar['apple_id'] : '';
            $apple_calendar['app_password'] = isset($apple_calendar['app_password']) ? $apple_calendar['app_password'] : ''; 
        } 
        
        // Stripe API
        $stripe = isset($_tfhb_host_integration_settings['stripe']) ? $_tfhb_host_integration_settings['stripe'] : array();
        if($_tfhb_integration_settings['stripe']['status'] == true){  

        $stripe['type'] = 'stripe';
        $stripe['status'] = $_tfhb_host_integration_settings['stripe']['status']; 
        $stripe['public_key'] = $_tfhb_host_integration_settings['stripe']['public_key'];  
        $stripe['secret_key'] = $_tfhb_host_integration_settings['stripe']['secret_key']; 
        
        } 

        // Mailchimp API
        $mailchimp = isset($_tfhb_host_integration_settings['mailchimp']) ? $_tfhb_host_integration_settings['mailchimp'] : array();
        if($_tfhb_integration_settings['mailchimp']['status'] == true){  

            $mailchimp['type'] = 'mailchimp';
            $mailchimp['status'] = $_tfhb_host_integration_settings['mailchimp']['status']; 
            $mailchimp['key'] = $_tfhb_host_integration_settings['mailchimp']['key'];  
          
        } 

        // Zoho
        $zoho = isset($_tfhb_host_integration_settings['zoho']) ? $_tfhb_host_integration_settings['zoho'] : array();
        if($_tfhb_integration_settings['zoho']['status'] == true){  

            $zoho['type'] = 'zoho';
            $zoho['status'] = $_tfhb_host_integration_settings['zoho']['status']; 
            $zoho['client_id'] = $_tfhb_host_integration_settings['zoho']['client_id'];  
            $zoho['client_secret'] = $_tfhb_host_integration_settings['zoho']['client_secret'];  
            $zoho['redirect_url'] = $_tfhb_host_integration_settings['zoho']['redirect_url'];  
            $zoho['access_token'] = $_tfhb_host_integration_settings['zoho']['access_token'];  
          
        } 


        // Checked if woo
        $data = array(
            'status' => true,  
            'integration_settings' => $_tfhb_host_integration_settings,
            'google_calendar' => $google_calendar,  
            'outlook_calendar' => $outlook_calendar,  
            'apple_calendar' => $apple_calendar,  
            'mailchimp' => $mailchimp,  
            'stripe' => $stripe,  
            'zoho' => $zoho,  
        );
        return rest_ensure_response($data);
    }

       // Update Integration Settings.
       public function UpdateIntegrationSettings (){
        
        $request = json_decode(file_get_contents('php://input'), true);
        $key = sanitize_text_field($request['key']);
        $data = $request['value'];
        $host_id = $request['id'];
        $user_id = $request['user_id'];
        
        $_tfhb_host_integration_settings = is_array(get_user_meta($user_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($user_id, '_tfhb_host_integration_settings', true) : array();

        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        if($key == 'zoom_meeting'){ 

            $zoom = new ZoomServices(
                sanitize_text_field($data['account_id']), 
                sanitize_text_field($data['app_client_id']),  
                sanitize_text_field($data['app_secret_key'])
            ); 
            return rest_ensure_response($zoom->updateHostsZoomSettings($data, $user_id));
            
        }elseif($key == 'woo_payment'){
            $_tfhb_host_integration_settings['woo_payment']['type'] =  sanitize_text_field($data['type']);
            $_tfhb_host_integration_settings['woo_payment']['status'] =  sanitize_text_field($data['status']);
            $_tfhb_host_integration_settings['woo_payment']['woo_payment'] =  sanitize_text_field($data['woo_payment']);

            // update User Meta 
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);

            //  woocommerce payment   
            $data = array(
                'status' => true,  
                'message' => 'Integration Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'google_calendar'){
            // Get Global Settings 
            $_tfhb_host_integration_settings['google_calendar']['type'] =  sanitize_text_field($data['type']);
            $_tfhb_host_integration_settings['google_calendar']['status'] =  sanitize_text_field($data['status']);     
            $_tfhb_host_integration_settings['google_calendar']['connection_status'] = isset($data['secret_key']) && !empty($data['secret_key']) ? 1 : sanitize_text_field($data['connection_status']);  
            $_tfhb_host_integration_settings['google_calendar']['selected_calendar_id'] =  $data['selected_calendar_id'];
            $_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar'] =  $data['tfhb_google_calendar'];

            // update User Meta  
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);
            


            //  woocommerce payment   
            $data = array(
                'status' => true,   
                'message' => 'Google Calendar Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'outlook_calendar'){
            // Get Global Settings 
            $_tfhb_host_integration_settings['outlook_calendar']['type'] =  sanitize_text_field($data['type']);
            $_tfhb_host_integration_settings['outlook_calendar']['status'] =  sanitize_text_field($data['status']);     
            $_tfhb_host_integration_settings['outlook_calendar']['connection_status'] = isset($data['secret_key']) && !empty($data['secret_key']) ? 1 : sanitize_text_field($data['connection_status']);  
            $_tfhb_host_integration_settings['outlook_calendar']['selected_calendar_id'] =  $data['selected_calendar_id'];
            $_tfhb_host_integration_settings['outlook_calendar']['tfhb_outlook_calendar'] =  $data['tfhb_outlook_calendar'];

            // update User Meta  
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);
            


            //  woocommerce payment   
            $data = array(
                'status' => true,   
                'message' => 'Outlook Calendar Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'apple_calendar'){
            // Get Global Settings 
            $_tfhb_host_integration_settings['apple_calendar']['type'] =  sanitize_text_field($data['type']);
            $_tfhb_host_integration_settings['apple_calendar']['status'] =  sanitize_text_field($data['status']);     
            $_tfhb_host_integration_settings['apple_calendar']['connection_status'] = isset($data['secret_key']) && !empty($data['secret_key']) ? 1 : sanitize_text_field($data['connection_status']);  
            $_tfhb_host_integration_settings['apple_calendar']['apple_id'] =  $data['apple_id'];
            $_tfhb_host_integration_settings['apple_calendar']['app_password'] =  $data['app_password'];
 

            // update User Meta  
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);
            


            //  woocommerce payment   
            $data = array(
                'status' => true,   
                'message' => 'Apple Calendar Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'stripe'){
            $_tfhb_host_integration_settings['stripe']['type'] =  'stripe';
            $_tfhb_host_integration_settings['stripe']['status'] =  sanitize_text_field($data['status']);
            $_tfhb_host_integration_settings['stripe']['public_key'] =  sanitize_text_field($data['public_key']);
            $_tfhb_host_integration_settings['stripe']['secret_key'] =  sanitize_text_field($data['secret_key']);

            // update User Meta  
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);

            //  stripe payment   
            $data = array(
                'status' => true,   
                'message' => 'Stripe Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'mailchimp'){
            $_tfhb_host_integration_settings['mailchimp']['type'] =  'mailchimp';
            $_tfhb_host_integration_settings['mailchimp']['status'] =  sanitize_text_field($data['status']);
            $_tfhb_host_integration_settings['mailchimp']['key'] =  sanitize_text_field($data['key']);

            // update User Meta  
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);

            $data = array(
                'status' => true,  
                'message' => 'Mailchimp Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'zoho'){
            $_tfhb_host_integration_settings['zoho']['type'] =  'zoho';
            $_tfhb_host_integration_settings['zoho']['status'] =  sanitize_text_field($data['status']);
            $_tfhb_host_integration_settings['zoho']['client_id'] =  sanitize_text_field($data['client_id']);
            $_tfhb_host_integration_settings['zoho']['client_secret'] =  sanitize_text_field($data['client_secret']);
            $_tfhb_host_integration_settings['zoho']['redirect_url'] =  sanitize_url($data['redirect_url']);
            $_tfhb_host_integration_settings['zoho']['access_token'] =  sanitize_text_field($data['access_token']);

            // update User Meta  
            update_user_meta($user_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings);

            $data = array(
                'status' => true,  
                'message' => 'Zoho Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }
    }

    // Get Availability Settings
    public function GetAvailabilitySettings(){
        $request = json_decode(file_get_contents('php://input'), true);

        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

        // Get Host Data 
        $_tfhb_host_availability_settings =  get_user_meta($request['id'], '_tfhb_host', true);
        // var_dump($_tfhb_host_availability_settings); exit();
        $data = array(
            'status' => true,  
            'time_zone' => $time_zone,
            'availability' => $_tfhb_host_availability_settings['availability'],
        );
        return rest_ensure_response($data);
    }

    // Get Single Availability Settings
    public function GetSingleAvailabilitySettings() {
        $request = json_decode(file_get_contents('php://input'), true);

        // Check if user is selected
        if (empty($request['host_id'])) {
            return rest_ensure_response(array('status' => false, 'message' => 'Host id is Empty'));
        }

        $host = new Host();
        $HostData = $host->get( $request['host_id'] );
        
        // If Host Use existing availability
        if(!empty($HostData->availability_type) && "settings"==$HostData->availability_type){
            if(!empty($HostData->availability_id)){
                $availability_id = $HostData->availability_id; 
                $availability = get_option('_tfhb_availability_settings');
    
                $filteredAvailability = array_filter($availability, function($item) use ($availability_id) {
                    return $item['id'] == $availability_id;
                });
            
                // If you expect only one result, you can extract the first item from the filtered array
                $defult_availability = reset($filteredAvailability);
            }else{
                $defult_availability = [];
            }
        }else{
            // Get Single Host Data 
            $_tfhb_host_availability_settings =  get_user_meta($request['host_id'], '_tfhb_host', true);
            $defult_availability = !empty($_tfhb_host_availability_settings['availability']) ? $_tfhb_host_availability_settings['availability'][$request['availability_id']] : [];
        }

        $data = array(
            'status' => true,  
            'availability' => $defult_availability,
        );
        return rest_ensure_response($data);
    }

    // Delete Availability Settings
    public function DeleteAvailabilitySettings(){
        $request = json_decode(file_get_contents('php://input'), true);

        // Get Host Data 
        $_tfhb_host_availability_settings =  get_user_meta($request['user_id'], '_tfhb_host', true);
        // Delete Key
        unset($_tfhb_host_availability_settings['availability'][$request['key']]); 
        // Data Update
        $_tfhb_availability_settings = update_user_meta($request['user_id'], '_tfhb_host', $_tfhb_host_availability_settings);
        // Response
        $data = array(
            'status' => true,  
            'availability' => $_tfhb_host_availability_settings['availability'],
        );
        return rest_ensure_response($data);
    }
    

    // Update Availability Settings.
    public function UpdateAvailabilitySettings(){
        $request = json_decode(file_get_contents('php://input'), true);
 
        // senitaized
        if(!isset($request['host'])){
            // response
            $data = array(
                'status' => false,  
                'message' => 'Something Went Wrong, Please Try Again Later.',  
            );
            return rest_ensure_response($data);
        }

        $_tfhb_host_info = get_user_meta($request['user_id'], '_tfhb_host', true);
        $tfhb_host_availability = !empty($_tfhb_host_info['availability']) ? $_tfhb_host_info['availability'] : [];

        $availability['id'] = isset($request['id']) ? sanitize_text_field($request['id']) : '';
        $availability['user_id'] = isset($request['user_id']) ? sanitize_text_field($request['user_id']) : '';
        $availability['title'] = sanitize_text_field($request['title']);
        $availability['time_zone'] = sanitize_text_field($request['time_zone']); 
        $availability['date_status'] = sanitize_text_field($request['date_status']); 
        $availability['override'] = ''; 
        $availability['status'] = 'active'; 

        // time slots 
        foreach ($request['time_slots'] as $key => $value) {

            $availability['time_slots'][$key]['day'] = sanitize_text_field($value['day']);
            $availability['time_slots'][$key]['status'] =  sanitize_text_field($value['status']);

             foreach ($value['times'] as $key2 => $value2) {
                $availability['time_slots'][$key]['times'][$key2]['start'] = sanitize_text_field($value2['start']);
                $availability['time_slots'][$key]['times'][$key2]['end'] = sanitize_text_field($value2['end']);
             }
 
        }

        // Date Slots
        foreach ($request['date_slots'] as $key => $value) {

            $availability['date_slots'][$key]['date'] = sanitize_text_field($value['date']);
            $availability['date_slots'][$key]['available'] =  sanitize_text_field($value['available']);

             foreach ($value['times'] as $key2 => $value2) {
                $availability['date_slots'][$key]['times'][$key2]['start'] = sanitize_text_field($value2['start']);
                $availability['date_slots'][$key]['times'][$key2]['end'] = sanitize_text_field($value2['end']);
             }
 
        }
        
        if($availability['id'] == ''){
                
            $_tfhb_host_info['availability'][] = $availability;

        }else{
            
            foreach ($tfhb_host_availability as $key => $value) {
                
                if($key == $availability['id']){
                    $_tfhb_host_info['availability'][$key] = $availability; 
                }
            } 

           
        } 
        if($availability['id'] == ''){
            // Host Availability DB
            $availbility_data = [ 
                'host' => isset($request['host']) ? sanitize_text_field($request['host']) : '', 
                'title' => $availability['title'],
                'time_zone' => $availability['time_zone'],
                'override' => '',
                'time_slots' => $request['time_slots'],
                'date_status' => $availability['date_status'],
                'date_slots' => $request['date_slots'],
                'status' => 'active',
                'created_at' => date('y-m-d'), 
                'updated_at' => date('y-m-d'), 
            ];

            $hostAvailability = new Availability(); 
            $insert = $hostAvailability->add($availbility_data);

            if(!$insert['status']) {
                return rest_ensure_response(array('status' => false, 'message' => 'Error while creating host'));
            }
            $host_insert_availablekey = count($tfhb_host_availability);
            $_tfhb_host_info['availability'][$host_insert_availablekey]['available_id'] = $insert['insert_id'];
        }else{
            // Host Availability DB
            $availbility_data = [ 
                'id' => $request['available_id'],
                'host' => isset($request['host']) ? sanitize_text_field($request['host']) : '', 
                'title' => $availability['title'],
                'time_zone' => $availability['time_zone'],
                'override' => '',
                'time_slots' => serialize($request['time_slots']),
                'date_status' => $availability['date_status'],
                'date_slots' => serialize($request['date_slots']),
                'status' => 'active',
                'created_at' => date('y-m-d'), 
                'updated_at' => date('y-m-d'), 
            ];

            $hostAvailability = new Availability(); 
            $hostAvailability->update($availbility_data);

            $_tfhb_host_info['availability'][$request['id']]['available_id'] = $request['available_id']; 
        }
        
         // update user meta
         $_tfhb_availability_settings = update_user_meta($request['user_id'], '_tfhb_host', $_tfhb_host_info);

         $_tfhb_host_info = get_user_meta($request['user_id'], '_tfhb_host', true);
        // response
        $data = array(
            'status' => true, 
            'availability' => $_tfhb_host_info['availability'],    
            'message' => 'Availability Updated Successfully',  
        );
        return rest_ensure_response($data);
    }
 
    

    /**
     * Fetch Integration Settings
     */

     public function FetchIntegrationSettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        echo 'fetch';
        
     }
    
} 