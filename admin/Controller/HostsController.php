<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
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
       
    }
    // permission_callback
    public function getHostsData() { 
        // Get all wp users list with 
        $users = get_users(array('role__in' => array('administrator', 'editor', 'author', 'contributor', 'subscriber')));
        $userData = array();
        foreach ($users as $user) {
            $userData[$user->ID] =  $user->display_name . ' (' . $user->user_email . ')';
        } 

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
        if (empty($request['id']) || $request['id'] == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Select User'));
        }

        // Get user Data 
        $user = get_user_by('id', $request['id']);
        
        

        // Check if user is valid
        if (empty($user)) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid User'));
        }

        // Check if user is already a host
        $host = new Host();

        $hostCheck = $host->get( array('user_id' => $request['id']) ); 
        if (!empty($hostCheck)) {
            return rest_ensure_response(array('status' => false, 'message' => 'This User is already a host'));
        }
        


        $data = [ 
            'user_id' => $user->ID, 
            'first_name' => get_user_meta( $user->ID, 'first_name', true ) != '' ? get_user_meta( $user->ID, 'first_name', true ) : $user->display_name,
            'last_name' => get_user_meta( $user->ID, 'last_name', true ) != '' ? get_user_meta( $user->ID, 'last_name', true ) : '',
            'email' => $user->user_email,
            'phone_number' => '',
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
        update_user_meta($request['id'], '_tfhb_host', $data);

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
        // Return response
        $data = array(
            'status' => true, 
            'host' => $HostData,  
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
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'about' => $request['about'],
            'avatar' => $request['avatar'],
            'featured_image' => $request['featured_image'],
            'status' => $request['status'], 
        ];
        $hostUpdate = $host->update($data, $host_id);
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
            'hosts' => $HostsList,  
            'message' => 'Host Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 