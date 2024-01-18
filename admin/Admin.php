<?php 
namespace HydraBooking\Admin;


use HydraBooking\Admin\Includes\Enqueue;
use HydraBooking\Admin\Includes\AdminMenu;


  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class Admin {

    // constaract
    public function __construct() {
    
      // enqueue
      new Enqueue();

      // admin menu
      new AdminMenu();

    }

}


