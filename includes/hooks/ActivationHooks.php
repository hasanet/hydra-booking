<?php
namespace HydraBooking\Hooks;

class ActivationHooks {

	public function __construct() {

		register_activation_hook( THB_PATH . 'hydra-booking.php', array( $this, 'tfhb_activate' ) );
	}

	public function tfhb_activate() {

		// Flash Rewrite Rules
		flush_rewrite_rules();

		// Create a New host Role
		$this->tfhb_create_host_role();

		// Add Capabilities to the role
		$this->tfhb_add_capabilities_to_role();

		// Tfhb Options Activation hooks
		$this->tfhb_options_activation_hooks();
	}

	public function tfhb_create_host_role() {

		if ( get_role( 'tfhb_host' ) ) {
			return;
		}
			add_role(
				'tfhb_host',
				'Hydra Host',
				array(
					'read'                            => true, // true allows this capability
					'edit_posts'                      => true, // Allows user to edit their own posts
					'edit_pages'                      => true, // Allows user to edit pages
					'edit_others_posts'               => true, // Allows user to edit others posts not just their own
					'create_posts'                    => true, // Allows user to create new posts
					'manage_categories'               => true, // Allows user to manage post categories
					'publish_posts'                   => true, // Allows the user to publish, otherwise posts stays in draft mode
					'edit_themes'                     => false, // false denies this capability. User can’t edit your theme
					'install_plugins'                 => false, // User cant add new plugins
					'update_plugin'                   => false, // User can’t update any plugins
					'update_core'                     => false, // user cant perform core updates
					'manage_options'                  => true,

					// Custom Capabilities
					'tfhb_manage_options'             => true, // true allows this capability.
					'tfhb_manage_dashboard'           => true, // true allows this capability.
					'tfhb_manage_meetings'            => true, // true allows this capability.
					'tfhb_manage_booking'             => true, // true allows this capability.
					'tfhb_manage_settings'            => false, // true allows this capability.
					'tfhb_manage_hosts'               => true, // true allows this capability.
					'tfhb_manage_custom_availability' => true, // true allows this capability.
					'tfhb_manage_integrations'        => true, // true allows this capability.
				)
			);
	}

	// Add Capabilities to the role
	public function tfhb_add_capabilities_to_role() {
		// administrator
		$role = get_role( 'administrator' );
		$role->add_cap( 'tfhb_manage_options' );
		$role->add_cap( 'tfhb_manage_dashboard' );
		$role->add_cap( 'tfhb_manage_meetings' );
		$role->add_cap( 'tfhb_manage_booking' );
		$role->add_cap( 'tfhb_manage_settings' );
		$role->add_cap( 'tfhb_manage_hosts' );
		$role->add_cap( 'tfhb_manage_custom_availability' );
		$role->add_cap( 'tfhb_manage_integrations' );
	}


	// Tfhb Options Activation hooks
	public function tfhb_options_activation_hooks() {

		// setup default options

		// Activation date
		update_option( 'tfhb_hydra_activation_date', time() );
	}
}
