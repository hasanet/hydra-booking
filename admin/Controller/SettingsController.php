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
        register_rest_route('hydra-booking/v1', '/settings/general/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateGeneralSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        )); 
       
    }
    // permission_callback
    public function GetGeneralSettings() {
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();
        $country = new CountryController();
        $country_list = $country->country_list();
        $_tfhb_general_settings = get_option('_tfhb_general_settings');
        $data = array(
            'status' => true, 
            'time_zone' => $time_zone,
            'country_list' => $country_list,
            'general_settings' => $_tfhb_general_settings,
        ); 
        return rest_ensure_response($data);
    } 

    // Update General Settings
    public function UpdateGeneralSettings( ) {
        $request = json_decode(file_get_contents('php://input'), true);
        $_tfhb_general_settings = get_option('_tfhb_general_settings');

        // senitaized 
        $_tfhb_general_settings['time_zone'] = sanitize_text_field($request['time_zone']);
        $_tfhb_general_settings['time_format'] = sanitize_text_field($request['time_format']);
        $_tfhb_general_settings['week_start_from'] = sanitize_text_field($request['week_start_from']);
        $_tfhb_general_settings['date_format'] = sanitize_text_field($request['date_format']);
        $_tfhb_general_settings['country'] = sanitize_text_field($request['country']);
        $_tfhb_general_settings['after_booking_completed'] = sanitize_text_field($request['after_booking_completed']);
        $_tfhb_general_settings['booking_status'] = sanitize_text_field($request['booking_status']);
        $_tfhb_general_settings['allowed_reschedule_before_meeting_start'] = sanitize_text_field($request['allowed_reschedule_before_meeting_start']);

        // update option
        update_option('_tfhb_general_settings', $_tfhb_general_settings);

        $data = array(
            'status' => true, 
            'message' => 'General Settings Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 