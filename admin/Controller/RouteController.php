<?php 
namespace HydraBooking\Admin\Controller;

use HydraBooking\Admin\Controller\AvailabilityController;

// Use DB 
use HydraBooking\DB\Availability;

// exit
if ( ! defined( 'ABSPATH' ) ) { exit; }

  class RouteController {

    // constaract
    public function __construct() {  
        
        // availability controller
        $this->create(new AvailabilityController(), 'create_endpoint');
    }

    public function create($class, $function){
        add_action('rest_api_init', array($class, $function));
    }
 
}

?>