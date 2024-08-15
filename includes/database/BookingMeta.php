<?php

namespace HydraBooking\DB;

class BookingMeta {
	public $table = 'tfhb_booking_meta';


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
                booking_id INT(11) NULL,  
                meta_key VARCHAR(255) NULL,  
                value LONGTEXT NULL,
                created_at DATE NULL,
                updated_at DATE NULL, 
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
	}

	/**
	 * Create the database availability.
	 */
	public function add( $request ) {

		global $wpdb;
		$table_name = $wpdb->prefix . $this->table;

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
	public function get( $where = null, $filterData = '' ) {

		global $wpdb;
	}

		/**
		 * Get all  availability Data.
		 */
	public function getFirstOrFail( $where = null ) {

		global $wpdb;

		$table_name = $wpdb->prefix . $this->table;

		if ( $where != null && is_array( $where ) ) {
			$sql = "SELECT * FROM $table_name WHERE ";
			$i   = 0;
			foreach ( $where as $key => $value ) {
				if ( $i == 0 ) {
					$sql .= " $key = $value";
				} else {
					$sql .= " AND $key = $value";
				}
				++$i;
			}
			$data = $wpdb->get_row(
				$wpdb->prepare( $sql )
			);
		} else {
			$data = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM {$wpdb->prefix}tfhb_booking_meta WHERE id = %d", $where)
			);

		}

		return $data;
	}




	// delete
	public function delete( $id ) {
		global $wpdb;
	}
}
