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

        add_action('init', array($this, 'init'));
 
        
    }

    public function init() {   
        // if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        //     add_action('wp_ajax_woocommerce_ajax_install_plugin', 'wp_ajax_install_plugin'); 
        // }

        add_action('wp_ajax_contact_form_7_ajax_install_plugin', 'wp_ajax_install_plugin');  
        // add_action('wp_ajax_nopriv_woocommerce_ajax_install_plugin', 'custom_data');  

        // Create a New host Role
        $this->tfhb_create_host_role();
        new HydraBooking\Admin\Controller\RouteController(); 
        
        if(is_admin()) {
            
            // Load Admin Class
            new HydraBooking\Admin\Admin(); 
        } else {
            // Load Frontend Class
            // new HydraBooking\Frontend\Frontend(); 
        }

        
    } 
    public function custom_data(){
       echo "Hello World";
         die();
    }

    public function tfhb_create_host_role(){ 
        // checked if role exist
          if( get_role('tfhb_host') ){
            return;
          }
          add_role('tfhb_host', 'Hydra Host', array(
            'read' => true, // true allows this capability
            'edit_posts' => true, // Allows user to edit their own posts
            'edit_pages' => true, // Allows user to edit pages
            'edit_others_posts' => true, // Allows user to edit others posts not just their own
            'create_posts' => true, // Allows user to create new posts
            'manage_categories' => true, // Allows user to manage post categories
            'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
            'edit_themes' => false, // false denies this capability. User can’t edit your theme
            'install_plugins' => false, // User cant add new plugins
            'update_plugin' => false, // User can’t update any plugins
            'update_core' => false // user cant perform core updates
        ));
      }


    
}

new THB_INIT();
 
 