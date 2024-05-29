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
        // how to get current date start time 12:00:00 and end time 23:59:59 
        $current_date = date('Y-m-d 23:59:59');

 
        // $current_date  = date('Y-m-d H:i:s'); // exp 2021-09-01
        $previous_date = $days != 1 ? date('Y-m-d 00:00:00', strtotime('-'.$days.' days')) : date('Y-m-d 00:00:00'); // exp 2021-09-01 
        $previous_date_before = $days != 1 ? date('Y-m-d 00:00:00', strtotime('-'.($days*2).' days')) : date('Y-m-d 00:00:00', strtotime('-1 days')); // exp 2021-09-01


 

        
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
        $upcoming_booking = $booking->get(
            "meeting_dates >= '$current_date'",
            true, 
            false,
            true,
            'meeting_dates ASC',
            5
        );
        $recent_booking = $booking->get(
            null,
            true, 
            false,
            false,
            'booking_created_at DESC',
            5
        );
        // count total Booking and collect percentage
        $total_bookings['total'] = count($bookings); 
        $total_bookings_previous = count($previous_date_bookings);  
        $total_bookings['percentage'] = $total_bookings_previous != 0 ? 100 * ($total_bookings['total'] - $total_bookings_previous) / $total_bookings_previous : 100; 
        $total_bookings['growth'] = $total_bookings['percentage'] < 0 ? 'decrease' : 'increase';
         
        
        //  total cancelled Booking and collect percentage
        $cancelled['total'] = count(array_filter($bookings, function($booking){
            return $booking->status == 'cancelled';
        }));
        $cancelled_previous = count(array_filter($previous_date_bookings, function($booking){
            return $booking->status == 'cancelled';
        }));  
        $cancelled['percentage'] = $cancelled_previous != 0 ? 100 * ($cancelled['total'] - $cancelled_previous) / $cancelled_previous : 100;
        $cancelled['growth'] = $cancelled['percentage'] < 0 ? 'decrease' : 'increase';



        // count wich status is completed for Bookings array
        $completed['total'] = count(array_filter($bookings, function($booking){
            return $booking->status == 'completed';
        }));
        $completed_previous = count(array_filter($previous_date_bookings, function($booking){
            return $booking->status == 'completed';
        }));
        $completed['percentage'] = $completed_previous != 0 ? 100 * ($completed['total'] - $completed_previous) / $completed_previous : 100;
        $completed['growth'] = $completed['percentage'] < 0 ? 'decrease' : 'increase';
        

        $data = array (
            'status' => true, 
            'total_bookings' => $total_bookings,
            'total_cancelled_bookings' => $cancelled,
            'total_completed_bookings' => $completed,
            'upcoming_booking' => $upcoming_booking,   
            'recent_booking' => $recent_booking,   
        );
        return rest_ensure_response($data);

    }
}


