<?php 
namespace HydraBooking\Admin\Includes;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class AdminMenu {

    // constaract
    public function __construct() { 
        add_action('admin_menu', array($this, 'admin_menu'));
    }


    public function admin_menu() {
        add_menu_page(
            'Hydra Booking',
            'Hydra Booking',
            'manage_options',
            'hydra-booking',
            array($this, 'hydra_booking_page'),
            'dashicons-calendar-alt',
            6
        );
    }

    public function hydra_booking_page() {
        echo '<div id="app">{{ message }}</div>';
    }
    

}
 