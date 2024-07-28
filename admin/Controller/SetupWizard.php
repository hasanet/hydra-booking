<?php 
namespace HydraBooking\Admin\Controller;
 
//  Use Namespace
// Use DB 
use HydraBooking\DB\Host;
// exit
if ( ! defined( 'ABSPATH' ) ) { exit; }

 class SetupWizard {
   

   // constaract
   public function __construct() {  

       add_action('rest_api_init', array($this, 'create_endpoint'));

   }


   public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/setup-wizard/import-meeting', array(
            'methods' => 'POST',
            'callback' => array($this, 'ImportMeetingDemo'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        )); 

    } 


    // Import Meeting Demo
    public function ImportMeetingDemo(){
        $request = json_decode(file_get_contents('php://input'), true);

        // GET Current User
        $current_user = wp_get_current_user();
        $host = $this->CreateHost($current_user);

        // Checked if Host Already Exist
        
        

        $data = array(
            'status' => true, 
            'message' => 'General Settings Updated Successfully', 
            'data' => $host, 
        );
        return rest_ensure_response($data);
    }

    // Create New Host
    public function CreateHost($user){
        $user_id = $user->ID;
        $host = new Host();
        $host_data = $host->get($user_id);
       

        if($host_data == null){ 

            $data = [ 
                'user_id' => $user->ID, 
                'first_name' => get_user_meta( $user->ID, 'first_name', true ) != '' ? get_user_meta( $user->ID, 'first_name', true ) : $user->display_name,
                'last_name' => get_user_meta( $user->ID, 'last_name', true ) != '' ? get_user_meta( $user->ID, 'last_name', true ) : '',
                'email' => $user->user_email,
                'phone_number' => '',
                'time_zone' => '',
                'about' => '',
                'avatar' => '',
                'featured_image' => '', 
                'status' => 'activate', 
            ];

            // Insert Host
            $hostInsert = $host->add($data);
            if(!$hostInsert['status']) {
                return rest_ensure_response(array('status' => false, 'message' => 'Error while creating host'));
            }
            $hosts_id = $data['user_id'];
            unset($data['user_id']);
            $data['host_id'] = $hostInsert['insert_id'];

            // Update user Option 
            update_user_meta($user_id, '_tfhb_host', $data);

            // Hosts Lists 
            $host_data = $host->get($user_id);


        }

        return $host_data;
    }
}
?>