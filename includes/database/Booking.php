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
                host_id INT(11) NULL,
                attendee_id INT(11) NULL, 
                order_id INT(11) NULL, 
                attendee_time_zone VARCHAR(20) NULL,
                meeting_dates LONGTEXT NULL, 
                start_time VARCHAR(20) NULL,
                end_time VARCHAR(20) NULL,
                slot_minutes LONGTEXT NULL, 
                duration LONGTEXT NULL, 
                attendee_name VARCHAR(50) NULL, 
                email VARCHAR(100) NOT NULL, 
                address LONGTEXT NULL,
                others_info LONGTEXT NULL,
                country VARCHAR(20) NULL,
                ip_address VARCHAR(50) NULL, 
                device VARCHAR(50) NULL, 
                meeting_locations LONGTEXT NOT NULL,
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

        // json encode meeting locations
        $request['others_info'] = json_encode($request['others_info']);
        $request['meeting_locations'] = json_encode($request['meeting_locations']);

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
    public function get($where = null , $join = false) {
        global $wpdb;
    
        $table_name = $wpdb->prefix . $this->table;
        $meeting_table = $wpdb->prefix . 'tfhb_meetings';
        $host_table = $wpdb->prefix . 'tfhb_hosts';

        if(is_array($where)){
            $sql = "SELECT * FROM $table_name WHERE ";
            $i = 0;
            foreach($where as $k => $v){
                if($i == 0){
                    $sql .= " $k = '$v'";
                }else{
                    $sql .= " AND $k = '$v'";
                }
                $i++;
            }
            $data = $wpdb->get_results(
                $wpdb->prepare( $sql )
            ); 
            // echo $sql;
        }elseif($where != null) {
            $sql = "
                SELECT $table_name.*, 
                $host_table.email AS host_email,
                $meeting_table.post_id,
                $meeting_table.title AS meeting_title,
                $meeting_table.meeting_locations AS meeting_location,
                $meeting_table.duration AS meeting_duration,
                $meeting_table.buffer_time_before,
                $meeting_table.buffer_time_after
                FROM $table_name
                INNER JOIN $host_table ON $table_name.host_id = $host_table.id
                INNER JOIN $meeting_table ON $table_name.meeting_id = $meeting_table.id
                WHERE $table_name.id = %d
            ";
            $data = $wpdb->get_row($wpdb->prepare($sql, $where));
        } else {
            if($join == true){
                $sql = "SELECT 
                $table_name.id,
                $table_name.meeting_id,
                $table_name.attendee_name,
                $table_name.email AS attendee_email,
                $table_name.address,
                $table_name.meeting_dates,
                $table_name.start_time,
                $table_name.end_time,
                $table_name.status AS booking_status,
                $table_name.created_at AS booking_created_at,
                $meeting_table.host_id,
                $meeting_table.title,
                $meeting_table.duration,
                $meeting_table.meeting_locations,
                $host_table.first_name AS host_first_name,
                $host_table.last_name AS host_last_name,
                $host_table.email AS host_email,
                $host_table.time_zone AS host_time_zone
                FROM $table_name 
                INNER JOIN $meeting_table
                ON $table_name.meeting_id=$meeting_table.id
                INNER JOIN $host_table
                ON $meeting_table.host_id=$host_table.id";
            }else{
                $sql = "SELECT * FROM $table_name";
            }
    

    
            $data = $wpdb->get_results($sql);
        }
    
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