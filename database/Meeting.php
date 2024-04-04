<?php 
namespace HydraBooking\DB;

class Meeting {
    
    public  $table = 'tfhb_meetings';
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
                slug VARCHAR(255) NULL,
                host_id INT(11) NULL,
                user_id INT(11) NOT NULL,
                post_id INT(11) NOT NULL,
                title VARCHAR(255) NULL,
                description LONGTEXT NULL,
                meeting_type VARCHAR(255) NOT NULL,
                duration VARCHAR(20) NULL,
                meeting_locations LONGTEXT NULL,
                meeting_category VARCHAR(20) NULL,
                availability_type VARCHAR(20) NULL,
                availability_id INT(11) NULL,
                availability_custom LONGTEXT NULL, 
                buffer_time_before VARCHAR(20) NULL,
                buffer_time_after VARCHAR(20) NULL,
                booking_frequency LONGTEXT NULL,
                meeting_interval VARCHAR(20) NULL,
                recurring_status VARCHAR(20) NULL,
                recurring_repeat LONGTEXT NULL,
                recurring_maximum VARCHAR(20) NULL,
                attendee_can_cancel VARCHAR(20) NULL,
                attendee_can_reschedule VARCHAR(20) NULL,
                questions_status VARCHAR(20) NULL,
                questions LONGTEXT NULL, 
                notification LONGTEXT NULL, 
                payment_status VARCHAR(20) NULL, 
                payment_method VARCHAR(20) NULL, 
                payment_currency VARCHAR(20) NULL, 
                meeting_price VARCHAR(20) NULL, 
                payment_meta LONGTEXT NULL, 
                status VARCHAR(20) NULL, 
                created_by VARCHAR(20) NOT NULL, 
                updated_by VARCHAR(20) NOT NULL, 
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
     * Create the database meeting. 
     */
    public function add($request) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        // insert meeting
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
     * Update the database meeting. 
     */ 

    public function update($request) {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;

        $id = $request['id'];
        unset($request['id']);
        // Update meeting
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
     * Get all  meeting Data. 
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