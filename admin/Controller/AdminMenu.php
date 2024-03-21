<?php 
namespace HydraBooking\Admin\Controller;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class AdminMenu {

    // constaract
    public function __construct() { 
        add_action('admin_menu', array($this, 'admin_menu'));
    }


    public function admin_menu() {
        add_menu_page(
            esc_html__('Hydra Booking', 'thb-hydra-booking'),
            esc_html__('Hydra Booking', 'thb-hydra-booking'),
            'manage_options',
            'hydra-booking',
            array($this, 'hydra_booking_page'),
            'dashicons-calendar-alt',
            6
        );

        add_submenu_page(
            "hydra-booking",
            esc_html__("Dashboard", "dashboardpress"),
            esc_html__("Dashboard", "dashboardpress"),
            "manage_options",
            "hydra-booking#",
            array($this, "hydra_booking_page")
        );
        // Create a array for sub menu 
        $sub_menu = array( 
            array(
                'id' => 'event',
                'Title' => esc_html__('Events', 'thb-hydra-booking'),
                'capability' => 'manage_options', 
            ), 
            array(
                'id' => 'booking',
                'Title' => esc_html__('Booking', 'thb-hydra-booking'),
                'capability' => 'manage_options', 
            ), 
            array(
                'id' => 'hosts',
                'Title' => esc_html__('Hosts', 'thb-hydra-booking'),
                'capability' => 'manage_options', 
            ), 
            array(
                'id' => 'settings',
                'Title' => esc_html__('Settings', 'thb-hydra-booking'),
                'capability' => 'manage_options', 
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
    

}
 