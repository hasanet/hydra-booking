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

        // Create a New host Role
        $this->tfhb_create_host_role();

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


