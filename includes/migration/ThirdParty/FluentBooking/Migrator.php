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
		// $this->migrateAvailability();
		// $this->migrateMeeting();

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
 
		// Get all availability
		$availabilities = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$prefix}fcal_meta WHERE object_type = %s", 
				'availability',
			)
		);


		foreach($availabilities as $key => $value){ 

			$data = maybe_unserialize($value->value);
 
			// $availability['id'] = isset($value->id) ? sanitize_text_field($value->id) : '';
			$availability['host'] = isset($value->object_id) ? sanitize_text_field($value->object_id) : '';
			$availability['title'] = isset($data->title) ? sanitize_text_field($data->title) : 'No title';
			$availability['time_zone'] = isset($data['timezone']) ? sanitize_text_field($data['timezone']) : ''; 
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

			$availability['status'] = 'active';  
			// Insert Availability
			$InsertAvailability = new Availability();
			$insert = $InsertAvailability->add($availability);


			$_tfhb_host_info = get_user_meta($value->object_id, '_tfhb_host', true);
        	$tfhb_host_availability = !empty($_tfhb_host_info['availability']) ? $_tfhb_host_info['availability'] : [];
			$_tfhb_host_info['availability'][] = $availability;
			update_user_meta($value->object_id, '_tfhb_host', $_tfhb_host_info);

		}
	
		
	}

	// Migrate Meeting
	public function migrateMeeting(){

		global $wpdb;

		// Get Wpdb prefix
		$prefix = $wpdb->prefix;

		// Get all booking 
		$fluent_calender = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$prefix}fcal_calendars"
			)
		);

		// Get all booking 
		$meeting = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$prefix}tfhb_meetings"
			)
		);

		foreach ($fluent_calender as $key => $value){
			// Convert object to array
			$value = (array) $value;

			echo "<pre>";
			print_r($value);
			echo "</pre>";
			// Get all booking 
			$fluent_calender_event = (array) $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$prefix}fcal_calendar_events where calendar_id = %s",
					$value['id']
				)
			);
			$settings = maybe_unserialize($fluent_calender_event['settings']);
			$location_settings = maybe_unserialize($fluent_calender_event['location_settings']);
			$location = [];
			foreach($location_settings as $lkey => $lvalue){
				$location[$lvalue['title']] = array(
					'location' => $lvalue['title'],
					'address' => $lvalue['display_on_booking'], 
				);
			}
			echo "<pre>";
			print_r($fluent_calender_event);
			echo "</pre>"; 
			echo "<pre>";
			print_r($settings);
			echo "</pre>";
			
			// Get Current User
			$current_user = wp_get_current_user();
			// get user id
			$current_user_id = $current_user->ID;
			
			  // Update Meeting
			  $data = [ 
				'id'                        => $value['id'],
				'user_id'                   => $value['user_id'],
				'title'                     => isset($value['title']) ? sanitize_text_field($value['title']) : '',
				'host_id'                   => isset($value['user_id']) ? sanitize_key($value['user_id']) : '',
				'description'               => isset($value['description']) ? sanitize_text_field($value['description']) : '',
				'meeting_type'              => isset($fluent_calender_event['event_type']) ? sanitize_text_field($fluent_calender_event['event_type']) : '',
				'duration'                  => isset($fluent_calender_event['duration']) ? sanitize_text_field($fluent_calender_event['duration']) : '',
				'custom_duration'           => isset($value['custom_duration']) ? sanitize_text_field($value['custom_duration']) : '',
				'meeting_locations'         => isset($location) ? $location : '',
				'meeting_category'          => isset($value['meeting_category']) ? sanitize_text_field($value['meeting_category']) : '',
				'availability_type'         => isset($value['availability_type']) ? sanitize_text_field($value['availability_type']) : '',
				'availability_range_type'   => isset($settings['range_type']) ? sanitize_text_field($settings['range_type']) : '',
				'availability_range'        => isset($settings['range_days']) ? $settings['range_days'] : '',
				'availability_id'           => isset($fluent_calender_event['availability_id']) ? sanitize_text_field($fluent_calender_event['availability_id']) : '',
				'availability_custom'       => isset($value['availability_custom']) ?$value['availability_custom'] : '',
				'buffer_time_before'        => isset($settings['buffer_time_before']) ? sanitize_text_field($settings['buffer_time_before']) : '',
				'buffer_time_after'         => isset($settings['buffer_time_after']) ? sanitize_text_field($settings['buffer_time_after']) : '',
				'booking_frequency'         => isset($settings['booking_frequency']) ? $settings['booking_frequency'] : '',
				'meeting_interval'          => isset($value['meeting_interval']) ? sanitize_text_field($value['meeting_interval']) : '',
				'recurring_status'          => isset($value['recurring_status']) ? sanitize_text_field($value['recurring_status']) : '',
				'recurring_repeat'          => isset($value['recurring_repeat']) ? $value['recurring_repeat'] : '',
				'recurring_maximum'         => isset($value['recurring_maximum']) ? sanitize_text_field($value['recurring_maximum']) : '',
				'attendee_can_cancel'       => isset($settings['can_not_cancel']['enabled']) ? sanitize_text_field($settings['can_not_cancel']['enabled']) : '',
				'attendee_can_reschedule'   => isset($settings['can_not_reschedule']['enabled']) ? sanitize_text_field($settings['can_not_reschedule']['enabled']) : '',
				'questions_type'          => isset($value['questions_type']) ? sanitize_text_field($value['questions_type']) : '',
				'questions'                 => isset($value['questions']) ? $value['questions'] : '',
				'notification'              => isset($value['notification']) ? $value['notification'] : '',
				'payment_status'            => isset($fluent_calender_event['type']) ? sanitize_text_field($fluent_calender_event['type']) : '',
				'meeting_price'             => isset($value['meeting_price']) ? sanitize_text_field($value['meeting_price']) : '',
				'payment_currency'          => isset($value['payment_currency']) ? sanitize_text_field($value['payment_currency']) : '',
				'payment_method'            => isset($value['payment_method']) ? sanitize_text_field($value['payment_method'])  : '',
				'payment_meta'              => isset($value['payment_meta']) ? $value['payment_meta'] : '',
				'updated_at'                => date('Y-m-d'),
				'updated_by'                => $current_user_id
			];
		}


		// echo "<pre>";
		// print_r($meeting);
		// echo "</pre>";
		exit;
	}

}

// call the class
