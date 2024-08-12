<?php
namespace HydraBooking\Admin;

use HydraBooking\Admin\Controller\Enqueue;
use HydraBooking\Admin\Controller\AdminMenu;
use HydraBooking\Admin\Controller\AvailabilityController;
use HydraBooking\Services\Integrations\Zoom\ZoomServices;
use HydraBooking\Migration\Migration;

// Load Migrator
use HydraBooking\DB\Migrator;

	// exit
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

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
		register_activation_hook( THB_URL, array( $this, 'activate' ) );

		Migration::instance();

		add_action( 'admin_init', array( $this, 'tfhb_hydra_activation_redirect' ) );
	}

	public function activate() {
		// $Migrator = new Migrator();
		new Migrator();
	}

	public function tfhb_hydra_activation_redirect() {
		if ( ! get_option( 'tfhb_hydra_quick_setup' ) ) {

			update_option( 'tfhb_hydra_quick_setup', 1 );
			wp_redirect( admin_url( 'admin.php?page=hydra-booking#/setup-wizard' ) );

			// exit;
		}
	}
}
