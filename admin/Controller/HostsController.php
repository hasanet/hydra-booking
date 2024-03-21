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
       
    }
    // permission_callback
    public function getHostsData() { 
        // Get all wp users list with 
        $users = get_users(array('role__in' => array('administrator', 'editor', 'author', 'contributor', 'subscriber')));
        $userData = array();
        foreach ($users as $user) {
            $userData[$user->ID] =  $user->display_name . ' (' . $user->user_email . ')';
        } 
        // Return response
        $data = array(
            'status' => true, 
            'users' => $userData, 
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
            'user_id' => $request['id'],
            'host_name' => $user->display_name,
            'phone_number' => '',
            'host_about' => '',
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
            'HostsList' => $HostsList,  
            'id' => $hosts_id,  
            'message' => 'General Settings Updated Successfully', 
        );
        
        return rest_ensure_response($data);
    }
} 