<?php
namespace HydraBooking\Admin\Controller;

// Use DB
use HydraBooking\DB\Booking;
use HydraBooking\DB\Host;
use HydraBooking\Admin\Controller\DateTimeController;
use HydraBooking\DB\Meeting;

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

class ImportExport {


	// constaract
	public function __construct() {

		// add_action('rest_api_init', array($this, 'create_endpoint'));
	}

	public function create_endpoint() {
		register_rest_route(
			'hydra-booking/v1',
			'/settings/import-export',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'GetImportExportData' ),
			// 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
			)
		);
		register_rest_route(
			'hydra-booking/v1',
			'/settings/import-export/export-meeting-csv',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'ExportMeeting' ),
			// 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
			)
		);

		register_rest_route(
			'hydra-booking/v1',
			'/import-export/import-meeting',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'ImportMeeting' ),
			// 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
			)
		);
		// Import Booking
		register_rest_route(
			'hydra-booking/v1',
			'/settings/import-export/import-booking',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'ImportBooking' ),
			// 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
			)
		);
	}

	public function GetImportExportData() {
		// Get booking Data
		$booking  = new Booking();
		$bookings = $booking->getColumns();

		$data = array(
			'status'         => true,
			'booking_column' => $bookings,
		);
		return rest_ensure_response( $data );
	}

	// Export booking Data
	public function ImportBooking() {
		$request = json_decode( file_get_contents( 'php://input' ), true );
		$data    = isset( $request['data'] ) ? $request['data'] : array();
		$columns = isset( $request['columns'] ) ? $request['columns'] : array();

		// rearrange data first array value based on columns
		$firstData = $data[0];
		$newData   = array();
		foreach ( $columns as $key => $column ) {
			// if column name is match with first data value update that frist data value form column value
			// get the first data key
			$firstDataKey = array_search( $column, $firstData );
			if ( $firstDataKey !== false ) {
				$firstData[ $firstDataKey ] = $data[0][ $key ];
			}
		}
		$data[0] = $newData;
		tfhb_print_r( $firstData );

		$booking = new Booking();
		$booking->importBooking( $data );
		tfhb_print_r( $data );
		$data = array(
			'status'  => true,
			'data'    => true,
			'message' => 'Booking Imported Successfully',
		);
		return rest_ensure_response( $data );
	}
}
