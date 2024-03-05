<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
// exit
if ( ! defined( 'ABSPATH' ) ) { exit; }

  class SettingsController {
    

    // constaract
    public function __construct() { 
        // add_action('admin_init', array($this, 'init'));
        
        add_action('rest_api_init', array($this, 'create_endpoint'));
       
    }

    public function init() {
        
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/settings/general', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetGeneralSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        )); 
        // register_rest_route('hydra-booking/v1', '/settings/general/update', array(
        //     'methods' => 'GET',
        //     'callback' => array($this, 'get_all'),
        //     // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        // )); 
       
    }
    // permission_callback
    public function GetGeneralSettings() {
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();
        $country = new CountryController();
        $country_list = $country->country_list();

        $data = array(
            'status' => true, 
            'time_zone' => $time_zone,
            'country_list' => $country_list,
        );

        return rest_ensure_response($data);
    } 
} 