<?php 
namespace HydraBooking\Migration\ThirdParty\FluentBooking;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class Migrator {

    private static $instance;

	/**
	 * @return static
	 */
	public static function instance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}
 
	public function __construct(   ) {
		$this->migrate();
	} 

	public function migrate() {
		$this->migrateSettings();
	}

	public function migrateSettings(){

		
		// General Settings
		$fluent_settings = get_option('_fluent_booking_settings');
		$hydra_settings = get_option('_tfhb_general_settings');

		$hydra_settings['time_zone'] = isset($fluent_settings['time_zone']) ? $fluent_settings['time_zone'] : $hydra_settings['time_zone'];
		$hydra_settings['time_format'] = isset($fluent_settings['time_format']) ? $fluent_settings['time_format'] : $hydra_settings['time_format'];
		$hydra_settings['week_start_from'] = isset($fluent_settings['administration']['start_day']) ? $fluent_settings['administration']['start_day'] : $hydra_settings['week_start_from']; 
		$hydra_settings['country'] = isset($fluent_settings['administration']['default_country']) ? $fluent_settings['administration']['default_country'] : $hydra_settings['country'];

		$hydra_settings['after_booking_completed'] = isset($fluent_settings['administration']['auto_cancel_timing']) ? $fluent_settings['administration']['auto_cancel_timing'] : $hydra_settings['after_booking_completed'];
		
		// update_option('_tfhb_general_settings', $hydra_settings);
		
		// Integration Settings
		$fcal_google = get_option('_fcal_google_calendar_client_details');
	 
  

		// echo "<pre>";
		// echo print_r($fcal_google);
		// echo "</pre>";
		// exit;
		// if($settings){
		// 	$settings = json_decode($settings, true);
		// 	if($settings){
		// 		$settings = $this->migrateSettingsData($settings);
		// 		update_option('hydra_booking_settings', json_encode($settings));
		// 	}
		// }
	}

}

// call the class
