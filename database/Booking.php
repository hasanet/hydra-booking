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
                event_id INT(11) NOT NULL,
                host_user_id INT(11) NOT NULL,
                person_user_id INT(11) NOT NULL,
                person_contact_id INT(11) NOT NULL,
                person_time_zone VARCHAR(20) NOT NULL,
                start_time VARCHAR(20) NOT NULL,
                end_time VARCHAR(20) NOT NULL,
                slot_minutes LONGTEXT NOT NULL, 
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                emaill VARCHAR(100) NOT NULL,
                phone VARCHAR(100) NOT NULL,
                message LONGTEXT NOT NULL,
                country VARCHAR(20) NOT NULL,
                ip_address VARCHAR(50) NOT NULL, 
                device VARCHAR(50) NOT NULL,
                other_info LONGTEXT NOT NULL,
                location_details LONGTEXT NOT NULL,
                cancelled_by INT(11) NOT NULL,
                status INT(11) NOT NULL, 
                booking_type VARCHAR(20) NOT NULL,
                event_type VARCHAR(20) NOT NULL,
                payment_method VARCHAR(20) NOT NULL,
                payment_status VARCHAR(20) NOT NULL,
                created_at DATE NOT NULL,
                updated_at DATE NOT NULL, 
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
     * Create the database availability. 
     */
    public function add($request) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        // insert availability
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
     * Update the database availability. 
     */ 

    public function update($request) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        $id = $request['id'];
        unset($request['id']);
        // Update availability
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
     * Get all  availability Data. 
     */
    public function get($id = null) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;
        
        if($id){
            $sql = "SELECT * FROM $table_name WHERE id = $id";

            $data = $wpdb->get_row(
                $wpdb->prepare( $sql )
            );
        }else{
            $sql = "SELECT * FROM $table_name";

            $data = $wpdb->get_results(
                $wpdb->prepare( $sql )
            ); 
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