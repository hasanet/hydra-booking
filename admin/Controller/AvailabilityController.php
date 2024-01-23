<?php 
namespace HydraBooking\Admin\Controller;

// Use DB 
use HydraBooking\DB\Availability;

// exit
if ( ! defined( 'ABSPATH' ) ) { exit; }

  class AvailabilityController {

    // constaract
    public function __construct() { 
        // add_action('admin_init', array($this, 'init'));
        
        add_action('rest_api_init', array($this, 'create_endpoint'));
       
    }

    public function init() {
        
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/availability/create/', array(
            'methods' => 'POST',
            'callback' => array($this, 'create'),
            // 'permission_callback' =>  array($this, 'permission_callback'),
        ));
    }
    // permission_callback
    public function permission_callback() {
        return current_user_can('manage_options');
    }
    public function create(){ 
        $data = json_decode(file_get_contents('php://input'), true);


        $Availability = new Availability(); 
        $insert = $Availability->add($data); 
        
        wp_send_json_success($insert);
        // return $data;
    }
}

?>