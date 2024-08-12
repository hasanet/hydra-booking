<?php

namespace HydraBooking\DB;

class Meta {
	public $table = 'tfhb_meta';


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
                object_id INT(11) NOT NULL, 
                object_type VARCHAR(255) NOT NULL,  
                meta_key VARCHAR(255) NOT NULL,  
                value LONGTEXT NULL,
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
	}

	/**
	 * Create the database availability.
	 */
	public function add( $request ) {

		global $wpdb;
	}
	/**
	 * Update the database availability.
	 */
	public function update( $request ) {
	}
	/**
	 * Get all  availability Data.
	 */
	public function get( $where = null, $filterData = '' ) {

		global $wpdb;
	}

	// delete
	public function delete( $id ) {
		global $wpdb;
	}
}
