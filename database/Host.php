<?php 
namespace HydraBooking\DB;

class Host {
    
    public  $table = 'tfhb_hosts';
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
                user_id INT(11) NOT NULL, 
                first_name VARCHAR(100) NOT NULL,  
                last_name VARCHAR(100) NOT NULL,  
                email VARCHAR(100) NOT NULL,  
                phone_number VARCHAR(20) NOT NULL,
                about text NOT NULL,
                avatar VARCHAR(255) NOT NULL, 
                featured_image VARCHAR(255) NOT NULL, 
                time_zone VARCHAR(255) NOT NULL, 
                availability_type VARCHAR(11) NULL, 
                availability_id INT(11) NULL, 
                status VARCHAR(11) NOT NULL,
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
    public function get($where = null, $filterData = '') {
        
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table;
        
        if(is_array($where)){
            $sql = "SELECT * FROM $table_name WHERE ";
            $i = 0;
            foreach($where as $k => $v){
                if($i == 0){
                    $sql .= " $k = $v";
                }else{
                    $sql .= " AND $k = $v";
                }
                $i++;
            }
            $data = $wpdb->get_results(
                $wpdb->prepare( $sql )
            );
        }elseif($where != null){ 
            $data = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM $table_name WHERE user_id = $where" )
            ); 
        }elseif(!empty($filterData['name'])){
            // Corrected SQL query for searching by name
            $sql = "SELECT * FROM $table_name WHERE concat(first_name, last_name) LIKE %s";
            $data = $wpdb->get_results($wpdb->prepare($sql, '%' . $filterData['name'] . '%'));

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