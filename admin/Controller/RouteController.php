<?php 
namespace HydraBooking\Admin\Controller;

use HydraBooking\Admin\Controller\AvailabilityController;
use HydraBooking\Admin\Controller\SettingsController;
use HydraBooking\Admin\Controller\HostsController;
use HydraBooking\Admin\Controller\MeetingController;
use HydraBooking\Admin\Controller\BookingController;
use HydraBooking\Admin\Controller\AuthController; 
use HydraBooking\Admin\Controller\DashboardController;
use HydraBooking\Services\Integrations\GoogleCalendar\GoogleCalendar;
use HydraBooking\Services\Integrations\OutlookCalendar\OutlookCalendar;
use HydraBooking\Services\Integrations\Zoho\Zoho;
use HydraBooking\Admin\Controller\SetupWizard;
use HydraBooking\Admin\Controller\ImportExport;


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
        $this->create(new AuthController(), 'create_endpoint');
        $this->create(new GoogleCalendar(), 'create_endpoint');
        $this->create(new OutlookCalendar(), 'create_endpoint');
        $this->create(new DashboardController(), 'create_endpoint');
        $this->create(new Zoho(), 'create_endpoint');
        $this->create(new SetupWizard(), 'create_endpoint');
        $this->create(new ImportExport(), 'create_endpoint');
    }

    public function create($class, $function){
        add_action('rest_api_init', array($class, $function));
    }
    
    public function permission_callback( \WP_REST_Request $request ) {
        // get header data form request "capability'  
        $capability = $request->get_header('capability');
        // check current user have capability
        return current_user_can($capability);
    }
} 