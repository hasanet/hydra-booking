<?php 
namespace HydraBooking\DB;

class Booking {
    
    public  $table = 'tfhb_bookings';
    public function __construct() {   

        
    }

    /**
     * Run the database migration.
     */
    public function migrate() {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        $charset_collate = $wpdb->get_charset_collate();
 
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) { // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $sql = "CREATE TABLE $table_name (
                id INT(11) NOT NULL AUTO_INCREMENT, 
                meeting_id INT(11) NULL,
                host_user_id INT(11) NULL,
                person_user_id INT(11) NULL,
                person_contact_id INT(11) NULL,
                person_time_zone VARCHAR(20) NULL,
                start_time VARCHAR(20) NULL,
                end_time VARCHAR(20) NULL,
                slot_minutes LONGTEXT NULL, 
                first_name VARCHAR(50) NULL,
                last_name VARCHAR(50) NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(100) NOT NULL,
                message LONGTEXT NULL,
                country VARCHAR(20) NULL,
                ip_address VARCHAR(50) NULL, 
                device VARCHAR(50) NULL,
                other_info LONGTEXT NULL,
                location_details LONGTEXT NOT NULL,
                cancelled_by INT(11) NULL,
                status VARCHAR(50) NOT NULL, 
                booking_type VARCHAR(20) NULL,
                payment_method VARCHAR(20) NOT NULL,
                payment_status VARCHAR(20) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                PRIMARY KEY (id)
            ) $charset_collate";
            
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
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
     * Create the database Booking. 
     */
    public function add($request) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        // insert Booking
        $result =  $wpdb->insert(
            $table_name,
            $request
        );


        if($result === false){ 
            return false;
        }else{
            return [
                'status' => true,
                'insert_id' => $wpdb->insert_id
            ];
        } 

    }
     /**
     * Update the database Booking. 
     */ 

    public function update($request) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        $id = $request['id'];
        unset($request['id']);
        // Update Booking
        $result =  $wpdb->update(
            $table_name,
            $request,
            array('id' => $id)
        );


        if($result === false){ 
            return false;
        }else{
            return [
                'status' => true,
                'update_id' => $wpdb->insert_id
            ];
        } 

    }
     /**
     * Get all  Booking Data. 
     */
    public function get($id = null) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;
        $meeting_table = $wpdb->prefix . 'tfhb_meetings';
        
        if($id){
            $sql = "SELECT * FROM $table_name WHERE id = $id";

            $data = $wpdb->get_row(
                $wpdb->prepare( $sql )
            );
        }else{
            $sql = "SELECT meeting_id,first_name,last_name,email,phone,location_details,$table_name.status,$table_name.created_at,host_id,title,duration FROM $table_name INNER JOIN $meeting_table
            ON $table_name.meeting_id=$meeting_table.id";

            $data = $wpdb->get_results( $sql ); 
        }

        
        // Get all data
       
        return $data; 

    }

    // delete
    public function delete($id){ 
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;
        $result = $wpdb->delete( $table_name, array( 'id' => $id ) );
        if($result === false){ 
            return false;
        }else{
            return [
                'status' => true,
                'delete_id' => $id
            ];
        } 
    }

}


?>