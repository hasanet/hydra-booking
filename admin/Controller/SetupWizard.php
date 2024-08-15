<?php
namespace HydraBooking\Admin\Controller;

// Use Namespace
// Use DB
use HydraBooking\DB\Host;
use HydraBooking\DB\Meeting;
use HydraBooking\Admin\Controller\DateTimeController;
// exit
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

class SetupWizard {


	// constaract
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'create_endpoint' ) );
	}


	public function create_endpoint() {
		register_rest_route(
			'hydra-booking/v1',
			'/setup-wizard/fetch',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'fetchSetupWizard' ),
			// 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
			)
		);

		register_rest_route(
			'hydra-booking/v1',
			'/setup-wizard/import-meeting',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'ImportMeetingDemo' ),
			// 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
			)
		);
	}

	// Fatch Setup Wizard
	public function fetchSetupWizard() {

		$DateTimeZone = new DateTimeController( 'UTC' );
		$time_zone    = $DateTimeZone->TimeZone();
		// Get Current User email FORM wp_get_current_user
		$current_user       = wp_get_current_user();
		$current_user_email = $current_user->data->user_email;

		$data = array(
			'status'     => true,
			'time_zone'  => $time_zone,
			'user_email' => $current_user_email,
		);
		return rest_ensure_response( $data );
	}


	// Import Meeting Demo
	public function ImportMeetingDemo() {
		$request = json_decode( file_get_contents( 'php://input' ), true );

		// GET Current User
		$current_user = wp_get_current_user();
		$host         = $this->CreateHost( $current_user );

		$request['host_id'] = $host->id;
		$request['user_id'] = $host->user_id;

		// collect email form request
		$email_subscribe                         = array();
		$email_subscribe['email']                = $request['email'];
		$email_subscribe['subscribe_status']     = $request['enable_recevie_updates'];
		$email_subscribe['subscribe_date']       = gmdate( 'Y-m-d' );
		$email_subscribe['subscribe_time']       = gmdate( 'H:i:s' );
		$email_subscribe['subscribe_ip']         = $_SERVER['REMOTE_ADDR'];
		$email_subscribe['subscribe_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$email_subscribe['subscribe_referer']    = $_SERVER['HTTP_REFERER'];
		// get subscriber device  from user agent

		// Update Email Subscribe Option
		update_option( 'tfhb_hydra_email_subscribe', $email_subscribe );

		// Update General Settings available
		$availability_settings = get_option( '_tfhb_availability_settings' );
		// get last array data
		$last_data                    = ! empty( $availability_settings ) ? end( $availability_settings ) : array();
		$new_id                       = $last_data['id'] + 1;
		$availabilityDataSingle       = $request['availabilityDataSingle'];
		$availabilityDataSingle['id'] = $new_id;
		$availability_settings[]      = $availabilityDataSingle;
		update_option( '_tfhb_availability_settings', $availability_settings, true );

		// Checked if Host Already Exist

		$meeting = $this->CreateDemoMeetings( $request );

		$data = array(
			'status'                => true,
			'message'               => 'General Settings Updated Successfully',
			'meeting'               => $meeting,
			'email_subscribe'       => $email_subscribe,
			'availability_settings' => $availability_settings,
		);
		return rest_ensure_response( $data );
	}

	// Create New Host
	public function CreateHost( $user ) {
		$user_id   = $user->ID;
		$host      = new Host();
		$host_data = $host->get( $user_id );

		if ( $host_data == null ) {

			$data = array(
				'user_id'        => $user->ID,
				'first_name'     => get_user_meta( $user->ID, 'first_name', true ) != '' ? get_user_meta( $user->ID, 'first_name', true ) : $user->display_name,
				'last_name'      => get_user_meta( $user->ID, 'last_name', true ) != '' ? get_user_meta( $user->ID, 'last_name', true ) : '',
				'email'          => $user->user_email,
				'phone_number'   => '',
				'time_zone'      => '',
				'about'          => '',
				'avatar'         => '',
				'featured_image' => '',
				'status'         => 'activate',
			);

			// Insert Host
			$hostInsert = $host->add( $data );
			if ( ! $hostInsert['status'] ) {
				return rest_ensure_response(
					array(
						'status'  => false,
						'message' => 'Error while creating host',
					)
				);
			}
			$hosts_id = $data['user_id'];
			unset( $data['user_id'] );
			$data['host_id'] = $hostInsert['insert_id'];

			// Update user Option
			update_user_meta( $user_id, '_tfhb_host', $data );

			// Hosts Lists
			$host_data = $host->get( $user_id );

		}

		return $host_data;
	}


	// Create Demo Meetings
	public function CreateDemoMeetings( $request ) {

		// Create an array to store the post data for meeting the current row
		$meeting_post_data = array(
			'post_type'   => 'tfhb_meeting',
			'post_title'  => esc_html( $request['business_type'] ),
			'post_status' => 'publish',
			'post_author' => $request['user_id'],
		);
		$meeting_post_id   = wp_insert_post( $meeting_post_data );
		$meeting_slug      = get_post_field( 'post_name', $meeting_post_id );
		$data              = array(
			'slug'                     => esc_url( $meeting_slug ),
			'host_id'                  => $request['host_id'],
			'user_id'                  => $request['user_id'],
			'post_id'                  => $meeting_post_id,
			'title'                    => esc_html( $request['business_type'] ),
			'description'              => '',
			'meeting_type'             => 'one-to-one',
			'duration'                 => '30',
			'meeting_locations'        => '[{"location":"Attendee Phone Number","address":""}]',
			'availability_range_type'  => 'indefinitely',
			'availability_type'        => 'custom',
			'availability_id'          => '0',
			'availability_custom'      => isset( $request['availabilityDataSingle'] ) ? wp_json_encode( $request['availabilityDataSingle'] ) : '',
			'booking_frequency'        => '[{"limit":1,"times":"Year"}]',
			'recurring_status'         => '0',
			'recurring_repeat'         => '[{"limit":1,"times":"Year"}]',
			'questions_type'           => 'custom',
			'questions'                => '[{"label":"name","type":"Text","placeholder":"Name","options":[],"required":1},{"label":"email","type":"Email","options":[],"placeholder":"Email","required":1},{"label":"address","type":"Text","placeholder":"Address","options":[],"required":1}]',
			'payment_status'           => 0,
			'payment_method'           => '',
			'max_book_per_slot'        => 1,
			'is_display_max_book_slot' => '0',
			'created_by'               => $request['user_id'],
			'updated_by'               => $request['user_id'],
			'created_at'               => gmdate( 'Y-m-d' ),
			'updated_at'               => gmdate( 'Y-m-d' ),
			'status'                   => 'publish',
		);

		// Check if user is already a meeting
		$meeting = new Meeting();
		// Insert meeting
		$meetingInsert = $meeting->add( $data );
		if ( ! $meetingInsert['status'] ) {
			return rest_ensure_response(
				array(
					'status'  => false,
					'message' => 'Error while creating meeting',
				)
			);
		}
		$meetings_id = $meetingInsert['insert_id'];

		// Meetings Id into Post Meta
		update_post_meta( $meeting_post_id, '__tfhb_meeting_id', $meetings_id );

		$data['id']                  = $meetings_id;
		$data['meeting_locations']   = json_decode( $data['meeting_locations'], true );
		$data['availability_custom'] = json_decode( $data['availability_custom'], true );
		$data['questions']           = json_decode( $data['questions'], true );
		// Updated post meta
		update_post_meta( $meeting_post_id, '__tfhb_meeting_opt', $data );

		// meetings Lists
		$meeting = $meeting->get( $meetings_id );

		return $meeting;
	}
}
