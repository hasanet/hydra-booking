<?php
namespace HydraBooking\DB;

class Availability {

	public $table = 'tfhb_availability';
	public function __construct() {
	}

	/**
	 * Run the database migration.
	 */
	public function migrate() {

		global $wpdb;

		$table_name = $wpdb->prefix . $this->table;

		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) { // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$sql = "CREATE TABLE $table_name (
                id INT(11) NOT NULL AUTO_INCREMENT,
                host INT(11) NULL,
                title VARCHAR(100) NOT NULL,
                time_zone VARCHAR(50) NULL,
                override VARCHAR(255) NULL,
                time_slots LONGTEXT NULL, 
                date_status LONGTEXT NULL, 
                date_slots LONGTEXT NULL, 
                status VARCHAR(20) NULL,
                created_at DATE NOT NULL,
                updated_at DATE NOT NULL, 
                PRIMARY KEY (id)
            ) $charset_collate";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}
	}

	/**
	 * Rollback the database migration.
	 */
	public function rollback() {
			global $wpdb;

		$table_name = $wpdb->prefix . $this->table;

		$sql = "DROP TABLE IF EXISTS $table_name;";

		$wpdb->query( $sql );
	}

	/**
	 * Create the database availability.
	 */
	public function add( $request ) {

		global $wpdb;

		$table_name            = $wpdb->prefix . $this->table;
		$request['time_slots'] = maybe_serialize( $request['time_slots'] );
		$request['date_slots'] = maybe_serialize( $request['date_slots'] );

		// insert availability
		$result = $wpdb->insert(
			$table_name,
			$request
		);

		if ( $result === false ) {
			return false;
		} else {
			return array(
				'status'    => true,
				'insert_id' => $wpdb->insert_id,
			);
		}
	}
	/**
	 * Update the database availability.
	 */
	public function update( $request ) {

		global $wpdb;

		$table_name = $wpdb->prefix . $this->table;

		$id = $request['id'];
		unset( $request['id'] );
		// Update availability
		$result = $wpdb->update(
			$table_name,
			$request,
			array( 'id' => $id )
		);

		if ( $result === false ) {
			return false;
		} else {
			return array(
				'status' => true,
			);
		}
	}
	/**
	 * Get all  availability Data.
	 */
	public function get( $id = null ) {

		global $wpdb;

		$table_name = $wpdb->prefix . $this->table;

		if ( $id ) {
			$sql = "SELECT * FROM $table_name WHERE id = $id";

			$data = $wpdb->get_row(
				$wpdb->prepare( $sql )
			);
		} else {
			$sql = "SELECT * FROM $table_name";

			$data = $wpdb->get_results(
				$wpdb->prepare( $sql )
			);
		}

		// Get all data

		return $data;
	}

	// delete
	public function delete( $id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $this->table;
		$result     = $wpdb->delete( $table_name, array( 'id' => $id ) );
		if ( $result === false ) {
			return false;
		} else {
			return array(
				'status'    => true,
				'delete_id' => $id,
			);
		}
	}
}
