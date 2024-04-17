<?php
/**
 * Plugin Name: Hydra Booking
 * Plugin URI: https://themefic.com/hydra-booking
 * Description: Create a booking / Appointment Form using Contact Form 7. You can insert Calendar, Time on the form and manage your booking. User can pay using WooCommerce. 
 * Version: 1.0.0
 * Author: Themefic
 * Author URI: https://themefic.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: thb-hydra-booking
 * Domain Path: /languages
 */

// don't load directly
defined( 'ABSPATH' ) || exit;

class THB_INIT{
    // CONSTARACT 
    public function __construct(){
        // DEFINE PATH 
        define('THB_PATH', plugin_dir_path(__FILE__));
        define('THB_URL', plugin_dir_url(__FILE__));
        define('THB_VERSION', '1.0.0');  

        // Load Vendor Auto Load
        if(file_exists(THB_PATH . '/vendor/autoload.php')) {
           
            require_once THB_PATH . '/vendor/autoload.php'; 
        }

        // Activation Hooks
        new HydraBooking\Hooks\ActivationHooks();  

        // Deactivation Hooks
        new HydraBooking\Hooks\DeactivationHooks(); 

        add_action('init', array($this, 'init'));


   }

    public function init() {    
        // Create a New host Role 
        new HydraBooking\Admin\Controller\RouteController();   
        
        if(is_admin()) {
            
            // Load Admin Class
            new HydraBooking\Admin\Admin(); 
        } else {
            // Load Frontend Class
            // new HydraBooking\Frontend\Frontend(); 
        } 
    }  
 
 

    
}

new THB_INIT();
 
 