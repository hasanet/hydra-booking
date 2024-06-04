<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 use HydraBooking\Admin\Controller\DateTimeController;
 use HydraBooking\Admin\Controller\CountryController;
 use HydraBooking\Admin\Controller\AuthController;
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

        // Get Single Host based on id
        register_rest_route('hydra-booking/v1', '/settings/availability/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetSingleAvailabilitySettings'),
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
         
        // Notification Settings
        register_rest_route('hydra-booking/v1', '/settings/notification', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetNotificationSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
        register_rest_route('hydra-booking/v1', '/settings/notification/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateNotificationSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        )); 


        // Hosts Settings.
        register_rest_route('hydra-booking/v1', '/settings/hosts-settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetHostsSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
        register_rest_route('hydra-booking/v1', '/settings/hosts-settings/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateGetHostsSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));

        // Appearance Settings.
        register_rest_route('hydra-booking/v1', '/settings/appearance-settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetAppearanceSettings'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));
        register_rest_route('hydra-booking/v1', '/settings/appearance-settings/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'UpdateAppearanceSettings'),
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
        $_tfhb_general_settings['reschedule_status'] = sanitize_text_field($request['reschedule_status']);
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
        $general_settings = get_option('_tfhb_general_settings');
        $data = array(
            'status' => true, 
            'time_zone' => $time_zone,
            'availability' => $availability,
            'general_settings' => $general_settings,
        );
        return rest_ensure_response($data);
    }

    // Get Availability Single Settings
    public function GetSingleAvailabilitySettings($request) {
        $id = $request['id']; 
        $availability = get_option('_tfhb_availability_settings');

        $filteredAvailability = array_filter($availability, function($item) use ($id) {
            return $item['id'] == $id;
        });
    
        // If you expect only one result, you can extract the first item from the filtered array
        $singleAvailability = reset($filteredAvailability);
        $data = array(
            'status' => true, 
            'availability' => $singleAvailability,
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

            $availability['date_slots'][$key]['date'] = sanitize_text_field($value['date']);
            $availability['date_slots'][$key]['available'] =  sanitize_text_field($value['available']);

            foreach ($value['times'] as $key2 => $value2) {
                $availability['date_slots'][$key]['times'][$key2]['start'] = sanitize_text_field($value2['start']);
                $availability['date_slots'][$key]['times'][$key2]['end'] = sanitize_text_field($value2['end']);
            }

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
        // Checked woocommerce installed and activated 
        if(!file_exists(WP_PLUGIN_DIR . '/' . 'woocommerce/woocommerce.php')){
            $woo_connection_status =  0;

        } else if(!is_plugin_active( 'woocommerce/woocommerce.php')){
            $woo_connection_status =  0;
        }else{
            $woo_connection_status =  1;
        } 

        if(!isset($_tfhb_integration_settings['woo_payment'])){
            

            $_tfhb_integration_settings['woo_payment']['type'] =  'type';
            $_tfhb_integration_settings['woo_payment']['status'] =  0;
            $_tfhb_integration_settings['woo_payment']['connection_status'] =  $woo_connection_status;
        }else{
            $_tfhb_integration_settings['woo_payment']['connection_status'] =  $woo_connection_status;
        }

        // Checked if woo
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
                'option' => $option,  
                'message' => 'Google Calendar Settings Updated Successfully',
            );
            return rest_ensure_response($data);
        }
    }


    // Install Active Plugins
    public function installActivePlugins(){
        $request = json_decode(file_get_contents('php://input'), true);

        // activate the plugin
        $plugin_slug = sanitize_text_field( wp_unslash($_POST['slug']) );
        $file_name = sanitize_text_field( wp_unslash($_POST['file_name']) );
        $result = activate_plugin($plugin_slug.'/'.$file_name.'.php');

    
        //  install plugins
        // install woocommerce plugins 
        if( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
            //  install woocommerce plugins
            $plugins = array(
                'woocommerce/woocommerce.php',
            );
            $install = activate_plugins($plugins);
            if($install){
                $data = array(
                    'status' => true, 
                    'message' => 'WooCommerce Installed Successfully', 
                );
            }else{
                $data = array(
                    'status' => false, 
                    'message' => 'WooCommerce Not Installed', 
                );
            }
        }
    }

    // Get Notification Settings
    public function GetNotificationSettings(){
        $_tfhb_notification_settings = get_option('_tfhb_notification_settings');
        $data = array(
            'status' => true, 
            'notification_settings' => $_tfhb_notification_settings,
        );
        return rest_ensure_response($data);
    }

    // Update Notification Settings
    public function UpdateNotificationSettings(){
        $request = json_decode(file_get_contents('php://input'), true);  
        $data  = get_option('_tfhb_notification_settings');

        //  sanitize Hosts Notification
        if(isset($request['host'])){
             foreach ($request['host'] as $key => $value) {
                $data['host'][$key]['status'] = sanitize_text_field($value['status']);
                $data['host'][$key]['template'] = sanitize_text_field($value['template']);
                $data['host'][$key]['form'] = sanitize_text_field($value['form']);
                $data['host'][$key]['subject'] = sanitize_text_field($value['subject']);
                $data['host'][$key]['body'] = wp_kses_post($value['body']);
            }
        }

        // sanitize Guest Notification
        if(isset($request['attendee'])){
            foreach ($request['attendee'] as $key => $value) {
                $data['attendee'][$key]['status'] = sanitize_text_field($value['status']);
                $data['attendee'][$key]['template'] = sanitize_text_field($value['template']);
                $data['attendee'][$key]['form'] = sanitize_text_field($value['form']);
                $data['attendee'][$key]['subject'] = sanitize_text_field($value['subject']);
                $data['attendee'][$key]['body'] = wp_kses_post($value['body']);
            }
        }

        // update option
        update_option('_tfhb_notification_settings', $data);

        //  woocommerce payment   
        $data = array(
            'status' => true,  
            'message' => 'Notification Settings Updated Successfully',
        );
        return rest_ensure_response($data);
    }

    /**
     * Get Hosts Settings
     */
    public function GetHostsSettings(){


        $_tfhb_hosts_settings = get_option('_tfhb_hosts_settings');
        
        $data = array(
            'status' => true, 
            'message' => 'Hosts Settings', 
            'hosts_settings' => $_tfhb_hosts_settings,
        );
        return rest_ensure_response($data);
    }

    /**
     * Update Hosts Settings.
     */
    public function UpdateGetHostsSettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        $_tfhb_hosts_settings = !empty(get_option('_tfhb_hosts_settings')) ? get_option('_tfhb_hosts_settings') : array(); 

        // delete option
        // delete_option('_tfhb_hosts_settings');

        if(isset($request['hosts_settings']['others_information']['enable_others_information']) ){ 
            $_tfhb_hosts_settings['others_information']['enable_others_information'] = sanitize_text_field($request['hosts_settings']['others_information']['enable_others_information']);
            foreach ($request['hosts_settings']['others_information']['fields'] as $key => $value) {
                $_tfhb_hosts_settings['others_information']['fields'][$key]['label'] = sanitize_text_field($value['label']); 
                $_tfhb_hosts_settings['others_information']['fields'][$key]['type'] = sanitize_text_field($value['type']);
                $_tfhb_hosts_settings['others_information']['fields'][$key]['placeholder'] = sanitize_text_field($value['placeholder']);
                //  sanitize array
                $_tfhb_hosts_settings['others_information']['fields'][$key]['options'] = array_map('sanitize_text_field', $value['options']);
                $_tfhb_hosts_settings['others_information']['fields'][$key]['required'] = sanitize_text_field($value['required']);
            }
        } 

        if(isset($request['hosts_settings']['permission'])){
            $_tfhb_hosts_settings['permission']['tfhb_manage_dashboard'] = rest_sanitize_boolean($request['hosts_settings']['permission']['tfhb_manage_dashboard']);
            $_tfhb_hosts_settings['permission']['tfhb_manage_meetings'] = rest_sanitize_boolean($request['hosts_settings']['permission']['tfhb_manage_meetings']);
            $_tfhb_hosts_settings['permission']['tfhb_manage_booking'] = rest_sanitize_boolean($request['hosts_settings']['permission']['tfhb_manage_booking']);
            $_tfhb_hosts_settings['permission']['tfhb_manage_settings'] = rest_sanitize_boolean($request['hosts_settings']['permission']['tfhb_manage_settings']);
            $_tfhb_hosts_settings['permission']['tfhb_manage_custom_availability'] = rest_sanitize_boolean($request['hosts_settings']['permission']['tfhb_manage_custom_availability']);
            $_tfhb_hosts_settings['permission']['tfhb_manage_integrations'] = rest_sanitize_boolean($request['hosts_settings']['permission']['tfhb_manage_integrations']); 

            // update role capabilities
            $AuthController = new AuthController();
            $AuthController->updateHostRoleCapabilities('tfhb_host', $_tfhb_hosts_settings['permission']); 
        }

         
        // // update option
        update_option('_tfhb_hosts_settings', $_tfhb_hosts_settings);

        //  woocommerce payment   
        $data = array(
            'status' => true,  
            'message' => 'Hosts Settings Updated Successfully',
            'data' => $_tfhb_hosts_settings
        );
        return rest_ensure_response($data);
    }


    /**
     * Get Appearance Settings
     */
    public function GetAppearanceSettings(){
        $_tfhb_appearance_settings = get_option('_tfhb_appearance_settings');
        $data = array(
            'status' => true, 
            'message' => 'Appearance Settings', 
            'appearance_settings' => $_tfhb_appearance_settings,
        );
        return rest_ensure_response($data);
    }

    /**
     * Update Appearance Settings.
     */
    public function UpdateAppearanceSettings(){
        $request = json_decode(file_get_contents('php://input'), true);
        // update option
        update_option('_tfhb_appearance_settings', $request);

        $data = array(
            'status' => true,  
            'message' => 'Appearance Settings Updated Successfully',
            'data' => $request
        );
        return rest_ensure_response($data);
    }
} 