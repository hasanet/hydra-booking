<?php 
namespace HydraBooking\Migration\ThirdParty\FluentBooking;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  use HydraBooking\DB\Availability;
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
		// $this->migrateSettings();
		$this->migrateAvailability();

	}

	// Migrate Settings
	public function migrateSettings(){

		global $wpdb;

		
		// General Settings
		$fluent_settings = get_option('_fluent_booking_settings');
		$hydra_settings = get_option('_tfhb_general_settings');

		$hydra_settings['time_zone'] = isset($fluent_settings['time_zone']) ? $fluent_settings['time_zone'] : $hydra_settings['time_zone'];
		$hydra_settings['time_format'] = isset($fluent_settings['time_format']) ? $fluent_settings['time_format'] : $hydra_settings['time_format'];
		$hydra_settings['week_start_from'] = isset($fluent_settings['administration']['start_day']) ? $fluent_settings['administration']['start_day'] : $hydra_settings['week_start_from']; 
		$hydra_settings['country'] = isset($fluent_settings['administration']['default_country']) ? $fluent_settings['administration']['default_country'] : $hydra_settings['country'];

		$hydra_settings['after_booking_completed'] = isset($fluent_settings['administration']['auto_cancel_timing']) ? $fluent_settings['administration']['auto_cancel_timing'] : $hydra_settings['after_booking_completed'];
		
		update_option('_tfhb_general_settings', $hydra_settings);
		
		// Integration Settings
		$tfhb_integration_settings = get_option('_tfhb_integration_settings');
		$fcal_google = get_option('_fcal_google_calendar_client_details');
		
		// Google Calendar
		$tfhb_integration_settings['google_calendar']['client_id'] = isset($fcal_google['client_id']) ? $fcal_google['client_id'] : $tfhb_integration_settings['google_calendar']['client_id'];
		$tfhb_integration_settings['google_calendar']['secret_key'] = isset($fcal_google['secret_key']) ? $fcal_google['secret_key'] : $tfhb_integration_settings['google_calendar']['secret_key'];
		

		// Zoom Integration
		// Get Wpdb prefix
		$prefix = $wpdb->prefix;
		// custom query with prepare statement 
		$zoom_integration = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$prefix}fcal_meta WHERE object_type = %s AND object_id = %s  AND  `key` = %s", 
				'user_meta', 
				1, 
				'zoom_credentials'
			)
		);
		$zoom_integration_value = maybe_unserialize($zoom_integration->value);
		$tfhb_integration_settings['zoom_meeting']['account_id'] = isset($zoom_integration_value['account_id']) ? $zoom_integration_value['account_id'].'2' : $tfhb_integration_settings['zoom_meeting']['account_id'];
		$tfhb_integration_settings['zoom_meeting']['app_client_id'] = isset($zoom_integration_value['client_id']) ? $zoom_integration_value['client_id'] : $tfhb_integration_settings['zoom_meeting']['app_client_id'];
		$tfhb_integration_settings['zoom_meeting']['app_secret_key'] = isset($zoom_integration_value['client_secret']) ? $zoom_integration_value['client_secret'] : $tfhb_integration_settings['zoom_meeting']['app_secret_key'];
		$tfhb_integration_settings['zoom_meeting']['connection_status'] = !empty($zoom_integration_value['account_id']) ? 1 : $zoom_integration_value['account_id'];

		// update Integration settings
		update_option('_tfhb_integration_settings', $tfhb_integration_settings);
 
	}

	// Migration Availability
	public function migrateAvailability(){
		
		global $wpdb;
		$_tfhb_availability_settings = get_option('_tfhb_availability_settings');
		// Get Wpdb prefix
		$prefix = $wpdb->prefix;

		$days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

		// echo '<pre>';
		// print_r($_tfhb_availability_settings);
		// echo '</pre>';

	
		// Get all availability
		$availabilities = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$prefix}fcal_meta WHERE object_type = %s", 
				'availability',
			)
		);


		foreach($availabilities as $key => $value){
			

			$data = maybe_unserialize($value->value);

			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			
			
			// $availability['id'] = isset($value->id) ? sanitize_text_field($value->id) : '';
			$availability['host'] = isset($value->object_id) ? sanitize_text_field($value->object_id) : '';
			$availability['title'] = isset($data->title) ? sanitize_text_field($data->title) : '';
			$availability['time_zone'] = isset($data->time_zone) ? sanitize_text_field($data->time_zone) : ''; 
			$availability['override'] = '';   
			
			$count = 0;
			foreach($data['weekly_schedules'] as $dkey => $dvalue){ 
				
				$day = $dkey; // mon 
				$day = array_filter($days, function($day_data) use ($day) {
					return substr($day_data, 0, 3) === $day;
				});
				$day = array_values($day);
				$availability['time_slots'][$count]['day'] = $day[0];
				// if day first 3 char is match with any $days array value string then get the days expale: monday dunamicly
				// $availability['time_slots'][$count]['day'] = 
				$availability['time_slots'][$count]['status'] =  $dvalue['enabled'] ? 1 : 0;
				$availability['time_slots'][$count]['times'] = $dvalue['slots'];

				$count++;
			}

			$availability['date_status'] = ''; 

			// // Date slots 
			$date_count = 0;
			foreach ($data['date_overrides'] as $date_key => $date_value) { 
				$availability['date_slots'][$date_count] = array(
					'date' => $date_key,
					'status' => 0,
					'times' => $date_value
				
				); 

				$date_count++;
			}

			// echo '<pre>';
			// print_r($availability);
			// echo '</pre>';

			// die();

			$availability['status'] = 'active';  
			// Insert Availability
			$InsertAvailability = new Availability();
			$insert = $InsertAvailability->add($availability);

		}
	
		
	}

}

// call the class
