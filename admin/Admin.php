<?php 
namespace HydraBooking\Admin;


use HydraBooking\Admin\Controller\Enqueue;
use HydraBooking\Admin\Controller\AdminMenu;
use HydraBooking\Admin\Controller\AvailabilityController;

//  Load Migrator
use HydraBooking\DB\Migrator;

  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class Admin {

    // constaract
    public function __construct() {
    
      // run migrator
      new Migrator();
      // enqueue
      new Enqueue();

      // admin menu
      new AdminMenu();

      
      // availability controller
      new AvailabilityController();
      // activation hooks 
      register_activation_hook(THB_URL, array($this, 'activate'));

      

    }

    public function activate() {
        // $Migrator = new Migrator();
        new Migrator();



    }



 

}


