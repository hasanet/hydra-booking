<?php
namespace HydraBooking\Admin\Controller;

// Use DB
use HydraBooking\DB\Availability;

// exit
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

class AvailabilityController {

	public $time_zone = array();

	// constaract
	public function __construct() {
		// add_action('admin_init', array($this, 'init'));
		$DateTimeZone    = new \DateTimeZone( 'UTC' );
		$this->time_zone = $DateTimeZone->listIdentifiers();
		add_action( 'rest_api_init', array( $this, 'create_endpoint' ) );
	}

	public function init() {
	}

	public function create_endpoint() {
		register_rest_route(
			'hydra-booking/v1',
			'/availability',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_all' ),
			// 'permission_callback' =>  array($this, 'permission_callback'),
			)
		);
		register_rest_route(
			'hydra-booking/v1',
			'/availability/(?P<id>[0-9]+)/',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_items' ),
			// 'permission_callback' =>  array($this, 'permission_callback'),
			)
		);
		register_rest_route(
			'hydra-booking/v1',
			'/availability/create/',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'create' ),
			// 'permission_callback' =>  array($this, 'permission_callback'),
			)
		);
		register_rest_route(
			'hydra-booking/v1',
			'/availability/update/',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'update' ),
			// 'permission_callback' =>  array($this, 'permission_callback'),
			)
		);
		register_rest_route(
			'hydra-booking/v1',
			'/availability/delete/(?P<id>[0-9]+)/',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'delete' ),
			// 'permission_callback' =>  array($this, 'permission_callback'),
			)
		);
	}
	// permission_callback
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}
	public function create() {
		$data = json_decode( file_get_contents( 'php://input' ), true );

		$Availability = new Availability();
		$insert       = $Availability->add( $data );

		return rest_ensure_response( $insert );
		// return $data;
	}

	// update
	public function update() {
		$data         = json_decode( file_get_contents( 'php://input' ), true );
		$Availability = new Availability();
		$update       = $Availability->update( $data );
		return rest_ensure_response( $data );
		// return $data;
	}

	// delete
	public function delete( $request ) {
		$id           = $request['id'];
		$Availability = new Availability();
		$Availability->delete( $id );

		$data = array(
			'status'  => true,
			'message' => 'Availability Deleted',

		);
		return rest_ensure_response( $data );
		// return $data;
	}

	public function get_all() {
		$Availability = new Availability();
		$Availability = $Availability->get();

		$data = array(
			'status'       => true,
			'availability' => $Availability,
			'time_zone'    => $this->time_zone,
		);
		return rest_ensure_response( $data );
	}

	public function get_items( $request ) {
		$id           = $request['id'];
		$Availability = new Availability();
		$Availability = $Availability->get( $id );
		$data         = array(
			'status'       => true,
			'availability' => $Availability,
			'time_zone'    => $this->time_zone,
		);
		return rest_ensure_response( $data );
	}
}
