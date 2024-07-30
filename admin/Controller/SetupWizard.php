<?php 
namespace HydraBooking\Admin\Controller;
 
//  Use Namespace
// Use DB 
use HydraBooking\DB\Host;
use HydraBooking\DB\Meeting;
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
       
        $request['host_id'] = $host->id;
        $request['user_id'] = $host->user_id;

        // Checked if Host Already Exist

        $meeting = $this->CreateDemoMeetings($request);
        
        
        

        $data = array(
            'status' => true, 
            'message' => 'General Settings Updated Successfully', 
            'meeting' => $meeting, 
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


    // Create Demo Meetings
    public function CreateDemoMeetings($request){
        
        // Create an array to store the post data for meeting the current row
        $meeting_post_data = array(
            'post_type'    => 'tfhb_meeting',
            'post_title'   => esc_html($request['business_type']),
            'post_status'  => 'publish',
            'post_author'  => $request['user_id']
        );
        $meeting_post_id = wp_insert_post( $meeting_post_data );

        $data = [ 
            'user_id' => $request['user_id'],
            'host_id' => $request['host_id'],
            'meeting_type' => 'one-to-one', 
            'title' => esc_html($request['business_type']),
            'post_id' => $meeting_post_id,
            'availability_type' => 'custom',
            'availability_custom' => isset($request['availabilityDataSingle']) ? json_encode($request['availabilityDataSingle']) : '',
            'created_by' => $request['user_id'],
            'updated_by' => $request['user_id'], 
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
            'status'    => 'draft'
        ];
        
        // Check if user is already a meeting
        $meeting = new Meeting();
        // Insert meeting
        $meetingInsert = $meeting->add($data);
        if(!$meetingInsert['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while creating meeting'));
        }
        $meetings_id = $meetingInsert['insert_id'];

        // Meetings Id into Post Meta
        update_post_meta( $meeting_post_id, '__tfhb_meeting_id', $meetings_id );

        // meetings Lists 
        $meeting = $meeting->get($meetings_id);

        return $meeting;

    }
}
?>