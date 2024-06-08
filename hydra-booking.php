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
 * Text Domain: hydra-booking
 * Domain Path: /languages
 */

// don't load directly
defined( 'ABSPATH' ) || exit; 
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

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

        // Mail Hooks
        new HydraBooking\Hooks\MailHooks(); 

    

      
        add_action('init', array($this, 'init'));
        add_filter( 'authenticate', array( new HydraBooking\Admin\Controller\AuthController(), 'tfhb_restrict_unverified_user'), 10, 3 );
        add_action('current_screen', array($this, 'tfhb_get_plugin_screen'));
        add_action( 'wp_enqueue_scripts', array($this, 'tfhb_enqueue_scripts' ));

       


   }

 
    public function init() {   
       new HydraBooking\Admin\Controller\ScheduleController(); 
 
        // Post Type 
        new HydraBooking\PostType\Meeting\Meeting_CPT();
        new HydraBooking\PostType\Booking\Booking_CPT(); 

        // Create a New host Role 
        new HydraBooking\Admin\Controller\RouteController();   
       
        
        if(is_admin()) { 
            // Load Admin Class
            new HydraBooking\Admin\Admin(); 
        } 

        // Load App Class 
        new HydraBooking\App\App();

    }  

   
 
 
    public function tfhb_get_plugin_screen()
    {
        $current_screen = get_current_screen();
        if (isset($_GET['page']) && $_GET['page'] === 'hydra-booking') {
            // remove admin notice
            add_action('in_admin_header', array($this, 'tfhb_hide_notices'), 99);
        }
    }

    public function tfhb_hide_notices()
    {
        remove_all_actions('user_admin_notices');
        remove_all_actions('admin_notices');
    }

    public function tfhb_enqueue_scripts(){
        wp_enqueue_style( 'tfhb-style', THB_URL . 'assets/app/css/style.css', '', THB_VERSION );
        wp_register_style( 'tfhb-select2-style', THB_URL . 'assets/app/css/select2.min.css', array(), THB_VERSION );

        // Global General Settings
        $general_settings = get_option('_tfhb_general_settings', true) ? get_option('_tfhb_general_settings', true) : array();

        $_tfhb_appearance_settings = get_option('_tfhb_appearance_settings');
        // var_dump($_tfhb_appearance_settings);
        $tfhb_primary_color = !empty($_tfhb_appearance_settings['primary_color']) ? $_tfhb_appearance_settings['primary_color'] : '#F62881';
        $tfhb_secondary_color = !empty($_tfhb_appearance_settings['secondary_color']) ? $_tfhb_appearance_settings['secondary_color'] : '#3F2731';
        $tfhb_paragraph_color = !empty($_tfhb_appearance_settings['paragraph_color']) ? $_tfhb_appearance_settings['paragraph_color'] : '#765664';
        $tfhb_theme_css = "
        :root {
            --tfhb-primary-color: $tfhb_primary_color;
            --tfhb-secondary-color: $tfhb_secondary_color;
            --tfhb-paragraph-color: $tfhb_paragraph_color;
          }
        ";
        wp_add_inline_style( 'tfhb-style', $tfhb_theme_css );

        // register script 
        wp_enqueue_script( 'stripe', '//checkout.stripe.com/checkout.js', array( 'jquery' ), '1.0.0', true );
        wp_register_script( 'tfhb-select2-script', THB_URL . 'assets/app/js/select2.min.js', array('jquery', 'tfhb-app-script'), THB_VERSION, true );
        wp_register_script( 'tfhb-app-script', THB_URL . 'assets/app/js/main.js', array('jquery'), THB_VERSION, true ); 

        wp_localize_script( 'tfhb-app-script', 'tfhb_app_booking', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'tfhb_nonce' ),
            'general_settings' => $general_settings,
        ) );

    }
    
}



new THB_INIT();
 
 
 