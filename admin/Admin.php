<?php 
namespace HydraBooking\Admin;


use HydraBooking\Admin\Controller\Enqueue;
use HydraBooking\Admin\Controller\AdminMenu; 
use HydraBooking\Admin\Controller\AvailabilityController;
use HydraBooking\Services\Integrations\Zoom\ZoomServices;

use HydraBooking\PostType\Post_Type;
use HydraBooking\PostType\Meeting\Meeting_CPT;

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

      // Post Type 
      new Meeting_CPT();

      // availability controller
      new AvailabilityController();
      // activation hooks 
      register_activation_hook(THB_URL, array($this, 'activate'));

      // $zoom = new ZoomServices( 'air-KbiBSo6vCNHqJFSnfQ', 'RYtrg3MNSZ6nlmeXF1VNxg', 'R83QN4Q4ve6YBvTuJ00f0Tf2TxmXAIp2');
      // echo "<pre>";
      // echo 'Access Token:'; 
      // print_r($zoom->generateAccessToken());
      // echo "</pre>";
      // exit;
 

    }

    public function activate() {
        // $Migrator = new Migrator();
        new Migrator();



    }



 

}


