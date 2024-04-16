<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 use HydraBooking\Services\Integrations\Zoom\ZoomServices;
 // Use DB 
use HydraBooking\DB\Host;
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

        // Intrigation 

        register_rest_route('hydra-booking/v1', '/hosts/integration', array(
            'methods' => 'POST',
            'callback' => array($this, 'GetIntegrationSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/hosts/integration/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateIntegrationSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
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
        $hosts_id = $hostInsert['insert_id'];
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

        if(empty($HostData)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Host'));
        }
        
        $DateTimeZone = new \DateTimeZone('UTC');
        $time_zone = $DateTimeZone->listIdentifiers();
        // Return response
        $data = array(
            'status' => true, 
            'host' => $HostData,  
            'time_zone' => $time_zone,  
            'message' => 'Host Data',
        );
        return rest_ensure_response($data);

    }

    // Update Host Information
    public function updateHostInformation(){
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


    // Get Integration Settings
    public function GetIntegrationSettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        
        // Get Host Data 
        $host_id = $request['id'];  
        $host = new Host();
        $hostData = $host->get( $host_id );
        $user_id = $hostData->user_id;

        $_tfhb_host_integration_settings =  get_user_meta($user_id, '_tfhb_host_integration_settings', true);
        

        // Checked if woo
        $data = array(
            'status' => true,  
            'integration_settings' => $_tfhb_host_integration_settings,
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
        
        $_tfhb_host_integration_settings = get_user_meta($user_id, '_tfhb_host_integration_settings');
        
        if($key == 'zoom_meeting'){ 

            $zoom = new ZoomServices(
                sanitize_text_field($data['account_id']), 
                sanitize_text_field($data['app_client_id']),  
                sanitize_text_field($data['app_secret_key'])
            ); 
            return rest_ensure_response($zoom->updateHostsZoomSettings($data, $user_id));
            
        }elseif($key == 'woo_payment'){
            $_tfhb_integration_settings['woo_payment']['type'] =  sanitize_text_field($data['type']);
            $_tfhb_integration_settings['woo_payment']['status'] =  sanitize_text_field($data['status']);
            $_tfhb_integration_settings['woo_payment']['woo_payment'] =  sanitize_text_field($data['woo_payment']);

            // update option
            update_option('_tfhb_integration_settings', $_tfhb_integration_settings);

            //  woocommerce payment   
            $data = array(
                'status' => true,  
                'message' => 'Integration Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }elseif($key == 'google_calendar'){
            $_tfhb_integration_settings['google_calendar']['type'] =  sanitize_text_field($data['type']);
            $_tfhb_integration_settings['google_calendar']['status'] =  sanitize_text_field($data['status']); 
            $_tfhb_integration_settings['google_calendar']['client_id'] =  sanitize_text_field($data['client_id']); 
            $_tfhb_integration_settings['google_calendar']['secret_key'] =  sanitize_text_field($data['secret_key']); 
            $_tfhb_integration_settings['google_calendar']['redirect_url'] =  sanitize_text_field($data['redirect_url']); 
            $_tfhb_integration_settings['google_calendar']['connection_status'] = isset($data['secret_key']) && !empty($data['secret_key']) ? 1 : sanitize_text_field($data['connection_status']); 

            // update option
            update_option('_tfhb_integration_settings', $_tfhb_integration_settings);
            $option = get_option('_tfhb_integration_settings', $_tfhb_integration_settings);


            //  woocommerce payment   
            $data = array(
                'status' => true,  
                'option' => $user_id,  
                'message' => 'Google Calendar Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }
    }
    
    
} 