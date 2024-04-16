<?php 
namespace HydraBooking\Admin\Controller;

use HydraBooking\Admin\Controller\AvailabilityController;
use HydraBooking\Admin\Controller\SettingsController;
use HydraBooking\Admin\Controller\HostsController;
use HydraBooking\Admin\Controller\MeetingController;
use HydraBooking\Admin\Controller\BookingController;

// Use DB 
use HydraBooking\DB\Availability;

// exit
if ( ! defined( 'ABSPATH' ) ) { exit; }

  class RouteController {

    // constaract
    public function __construct() {  
        $this->create(new AvailabilityController(), 'create_endpoint');
        $this->create(new SettingsController(), 'create_endpoint');
        $this->create(new HostsController(), 'create_endpoint');
        $this->create(new MeetingController(), 'create_endpoint');
        $this->create(new BookingController(), 'create_endpoint');
    }

    public function create($class, $function){
        add_action('rest_api_init', array($class, $function));
    }
    
    public function permission_callback() {
        return current_user_can('manage_options');
    }
}

?>