<?php  
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController; 
 // Use DB 
use HydraBooking\DB\Host;
use HydraBooking\DB\Availability;
use HydraBooking\DB\Meeting;
use HydraBooking\DB\Booking;

// exit
if ( ! defined( 'ABSPATH' ) ) { exit; } 

  class DashboardController {
   
    public function __construct() { 
        // add_action('admin_init', array($this, 'init'));
        
        // add_action('rest_api_init', array($this, 'create_endpoint'));
       
    }
    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/dashboard', array(
            'methods' => 'POST',
            'callback' => array($this, 'getDashboardsData'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
         
        
    }
    

    // get dashboard data
    public function getDashboardsData(){ 

        $request = json_decode(file_get_contents('php://input'), true);
        $days = $request['days']; // exp 1  

        // database data like 2024-05-26 18:02:02	
        $current_date  = date('Y-m-d H:i:s'); // exp 2021-09-01
        $previous_date = $days != 1 ? date('Y-m-d H:i:s', strtotime('-'.$days.' days')) : date('Y-m-d H:i:s'); // exp 2021-09-01 
        $previous_date_before = $days != 1 ? date('Y-m-d H:i:s', strtotime('-'.($days*2).' days')) : date('Y-m-d H:i:s', strtotime('-1 days')); // exp 2021-09-01
 

        
        // Get booking
        $booking = new Booking();



        $bookings = $booking->get(
            "created_at BETWEEN '$previous_date' AND '$current_date'", 
            false, 
            false,
            true
        );
        $previous_date_bookings = $booking->get(
            "created_at BETWEEN '$previous_date_before' AND '$previous_date'", 
            false, 
            false,
            true
        );
        // count wich status is cancelled for Bookings array
        $total_bookings['total'] = count($bookings);
        // Calculation parent increase or decrese bettwen two dates
        $total_bookings_previous = count($previous_date_bookings);
        // Calculation increase or decrese bettwen two dates
        $total_bookings_increase = $total_bookings['total'] - $total_bookings_previous;
        // Calculation percentage increase or decrese bettwen two dates
        $total_bookings['percentage'] = $total_bookings_previous != 0 ? ($total_bookings['increase'] / $total_bookings_previous) * 100 : 100; 
        // If percent is is decreesae then make it negative value "increase" or "decrease"
        $total_bookings['growth'] = $total_bookings['increase'] < 0 ? 'decrease' : 'increase';
         
        
        $cancelled = count(array_filter($bookings, function($booking){
            return $booking->status == 'cancelled';
        }));

        // count wich status is completed for Bookings array
        $completed = count(array_filter($bookings, function($booking){
            return $booking->status == 'completed';
        }));

        $data = array (
            'status' => true, 
            'total_bookings' => $total_bookings,
            'total_cancelled_bookings' => $cancelled,
            'total_completed_bookings' => $completed,  
            'data' => $bookings,  
        );
        return rest_ensure_response($data);

    }
}


