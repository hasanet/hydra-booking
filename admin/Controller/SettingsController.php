<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 use HydraBooking\Services\Integrations\Zoom\ZoomServices;
 // Use DB 
use HydraBooking\DB\Availability;
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
        // Availability Routes
        register_rest_route('hydra-booking/v1', '/settings/availability', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetAvailabilitySettings'),
            // 'permission_callback' =>  array($this, 'permission_callback'),
        ));
        register_rest_route('hydra-booking/v1', '/settings/availability/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateAvailabilitySettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
        
        register_rest_route('hydra-booking/v1', '/settings/availability/delete', array( 
            'methods' => 'POST',
            'callback' => array($this, 'DeleteAvailabilitySettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));


        // Intrigation 

        register_rest_route('hydra-booking/v1', '/settings/integration', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetIntegrationSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        register_rest_route('hydra-booking/v1', '/settings/integration/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateIntegrationSettings'),
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

    // Get Availability Settings
    public function GetAvailabilitySettings() {
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();
        $availability = get_option('_tfhb_availability_settings');
        $data = array(
            'status' => true, 
            'time_zone' => $time_zone,
            'availability' => $availability,
        );
        return rest_ensure_response($data);
    }

    // Update Availability Settings
    public function UpdateAvailabilitySettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        $_tfhb_availability_settings = get_option('_tfhb_availability_settings');

        // senitaized
        if(!isset($request['id'])){
            // response
            $data = array(
                'status' => false,  
                'message' => 'Something Went Wrong, Please Try Again Later.',  
            );
            return rest_ensure_response($data);
        }
        $availability['id'] = isset($request['id']) ? sanitize_text_field($request['id']) : 0;
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
            $availability['date_slots'][$key]['start'] = sanitize_text_field($value['start']);
            $availability['date_slots'][$key]['end'] =  sanitize_text_field($value['end']);
 
        }

        if($availability['id'] == 0){

            // Insert into database
            $AvailabilityInsert = new Availability(); 

            $insert = $AvailabilityInsert->add($availability); 
            if($insert['status'] === true){
                $availability['id'] = $insert['insert_id'];
            }else{
                $data = array(
                    'status' => false, 
                    'message' => 'Availability Not Updated', 
                );
                return rest_ensure_response($data);
            } 
                
            $_tfhb_availability_settings[] = $availability;

        }else{
            
            //  update
            $AvailabilityInsert = new Availability();
            $update = $AvailabilityInsert->update($availability);
            if($update['status'] != true){
                $data = array(
                    'status' => false, 
                    'message' => 'Availability Not Updated', 
                );
                return rest_ensure_response($data);
            }  
            foreach ($_tfhb_availability_settings as $key => $value) {
                if($value['id'] == $availability['id']){
                    $_tfhb_availability_settings[$key] = $availability; 
                }
            } 

           
        } 

         // update option
        update_option('_tfhb_availability_settings', $_tfhb_availability_settings);
        $availability = get_option('_tfhb_availability_settings');

        // response
        $data = array(
            'status' => true, 
            'availability' => $availability,    
            'update' => $update,    
            'message' => 'Availability Updated Successfully',  
        );
         return rest_ensure_response($data);

    }

    // Delete Availability Settings
    public function DeleteAvailabilitySettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        $key = sanitize_text_field($request['key']);
        $id = sanitize_text_field($request['id']);

        $_tfhb_availability_settings = get_option('_tfhb_availability_settings');
        unset($_tfhb_availability_settings[$key]); 

        //  delete from database
        if($id != 0){
            $AvailabilityInsert = new Availability();
            $AvailabilityInsert->delete($id);
        }
       

        
        // update option
        update_option('_tfhb_availability_settings', $_tfhb_availability_settings);
        $availability = get_option('_tfhb_availability_settings');
        $data = array(
            'status' => true, 
            'availability' => $availability,  
            'message' => 'Availability Deleted Successfully', 
        );
        return rest_ensure_response($data);
    }

    // Get Integration Settings
    public function GetIntegrationSettings(){
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        $data = array(
            'status' => true, 
            'integration_settings' => $_tfhb_integration_settings,
        );
        return rest_ensure_response($data);
    }

    // Update Integration Settings.
    public function UpdateIntegrationSettings (){
        $request = json_decode(file_get_contents('php://input'), true);
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        $key = sanitize_text_field($request['key']);
        $data = $request['value'];

        if($key == 'zoom_meeting'){ 

            $zoom = new ZoomServices(
                sanitize_text_field($data['account_id']), 
                sanitize_text_field($data['app_client_id']),  
                sanitize_text_field($data['app_secret_key'])
            ); 
            return rest_ensure_response($zoom->updateZoomSettings($data));
            
        }
    }
} 