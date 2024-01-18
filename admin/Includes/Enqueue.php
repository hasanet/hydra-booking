<?php 
namespace HydraBooking\Admin\Includes;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class Enqueue {

    // constaract
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    public function admin_enqueue_scripts() { 
        wp_enqueue_script('thb-app-script', THB_URL . 'assets/admin/js/main.js', array('jquery'), null, true);
   
    }

}


