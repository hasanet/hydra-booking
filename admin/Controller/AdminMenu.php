<?php 
namespace HydraBooking\Admin\Controller;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

 //  Use Namespace
 use HydraBooking\Admin\Controller\AuthController;
 

  class AdminMenu {

    private $auth;

    // constaract
    public function __construct() { 

        $this->auth = new AuthController(); 
        add_action('admin_menu', array($this, 'admin_menu'));
        
    }


    public function admin_menu() {

        // Get User Role
        $userRole = $this->auth->userRole();

        add_menu_page(
            esc_html__('Hydra Booking', 'thb-hydra-booking'),
            esc_html__('Hydra Booking', 'thb-hydra-booking'),
            'tfhb_manage_options', 
            // array($this, 'hydra_booking_access'),
            'hydra-booking',
            array($this, 'hydra_booking_page'),
            'dashicons-calendar-alt',
            6
        );

        add_submenu_page(
            "hydra-booking",
            esc_html__("Dashboard", "dashboardpress"),
            esc_html__("Dashboard", "dashboardpress"),
            "tfhb_manage_options",
            "hydra-booking#",
            array($this, "hydra_booking_page")
        );
        // Create a array for sub menu 
        $sub_menu = array(  
            array(
                'id' => 'meetings',
                'Title' => esc_html__('Meetings', 'thb-hydra-booking'),
                'capability' => 'tfhb_manage_options', 
            ), 
            array(
                'id' => 'booking',
                'Title' => esc_html__('Booking', 'thb-hydra-booking'),
                'capability' => 'tfhb_manage_options', 
            ), 
            array(
                'id' => 'hosts',
                'Title' => esc_html__('Hosts', 'thb-hydra-booking'),
                'capability' => 'tfhb_manage_options', 
            ),  
            array(
                'id' => 'settings',
                'Title' => esc_html__('Settings', 'thb-hydra-booking'),
                'capability' => 'tfhb_manage_settings', 
            ),
          
        );
         
        // Loop through array and create sub menu
        foreach ($sub_menu as $menu) {

            $menu_id = $menu['id'];
            add_submenu_page(
                'hydra-booking',
                $menu['Title'],
                $menu['Title'],
                $menu['capability'],
                'hydra-booking#/' . $menu_id,
                array($this, 'hydra_booking_page')
            );
        }


        // remove Sub Menu
        remove_submenu_page('hydra-booking', 'hydra-booking');
    }

    public function hydra_booking_page() {
        echo '<div id="tfhb-admin-app"></div>';
    }
    public function hydra_booking_access(){
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    }

}
 