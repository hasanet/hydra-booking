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
         
        register_rest_route('hydra-booking/v1', '/dashboard/statistics', array(
            'methods' => 'POST',
            'callback' => array($this, 'getDashboardsStatisticsData'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
         
        
    }
    

    // get dashboard data
    public function getDashboardsData(){ 

        $request = json_decode(file_get_contents('php://input'), true);
        $days = $request['days']; // exp 1
        $from_date = $request['from_date']; // exp 2021-09-01  
        $to_date = $request['to_date']; // exp 2021-09-01

        // calculate Days based on form day and to day
        $days = $from_date != null && $to_date != null ? (strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24) : $days; // exp 1


        // how to get current date start time 12:00:00 and end time 23:59:59 
        $current_date = $to_date != null ? date('Y-m-d 23:59:59', strtotime($to_date)) :  date('Y-m-d 23:59:59'); // exp 2021-09-01


 
        // $current_date  = date('Y-m-d H:i:s'); // exp 2021-09-01
        $previous_date = $days != 1 ? date('Y-m-d 00:00:00', strtotime('-'.$days.' days')) : date('Y-m-d 00:00:00'); // exp 2021-09-01 
        $previous_date = $from_date != null ? date('Y-m-d 00:00:00', strtotime($from_date)) : $previous_date; // exp 2021-09-01
        $previous_date_before = $days != 1 ? date('Y-m-d 00:00:00', strtotime('-'.($days*2).' days')) : date('Y-m-d 00:00:00', strtotime('-1 days')); // exp 2021-09-01 
 
        
        // Get booking
        $booking = new Booking();
        
        $current_user = wp_get_current_user();
		// get user role
		$current_user_role = ! empty( $current_user->roles[0] ) ? $current_user->roles[0] : '';
        $current_user_id = $current_user->ID;
        $host = new Host();
        $HostData = $host->get( $current_user_id  );

        $bookings = $booking->get(
            "created_at BETWEEN '$previous_date' AND '$current_date'", 
            false, 
            false,
            true,
            false,
            false,
            !empty($current_user_role) && "tfhb_host"==$current_user_role ? $HostData->id : false
        );
        $previous_date_bookings = $booking->get(
            "created_at BETWEEN '$previous_date_before' AND '$previous_date'", 
            false, 
            false,
            true,
            false,
            false,
            !empty($current_user_role) && "tfhb_host"==$current_user_role ? $HostData->id : false
        );
        $upcoming_booking = $booking->get(
            "meeting_dates >= '$current_date'",
            true, 
            false,
            true,
            'meeting_dates ASC',
            5,
            !empty($current_user_role) && "tfhb_host"==$current_user_role ? $HostData->id : false
        );
        $recent_booking = $booking->get(
            null,
            true, 
            false,
            false,
            'booking_created_at DESC',
            5,
            !empty($current_user_role) && "tfhb_host"==$current_user_role ? $HostData->id : false
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
            'days' => $days,   
        );

        return rest_ensure_response($data);

    }
    // get dashboard data
    public function getDashboardsStatisticsData(){ 

        $request = json_decode(file_get_contents('php://input'), true); 
        $days = $request['statistics_days']; // exp 2021-09-01
        $current_date = date('Y-m-d 23:59:59'); // exp 2021-09-01
        $previous_date = date('Y-m-d 00:00:00',  strtotime('-'.$days.' days')); // exp 2021-09-01

        $booking = new Booking();
        $statistics['total_bookings'] = array();
        $statistics['cancelled_bookings'] = array();
        $statistics['completed_bookings'] = array();

        $statistics = array();
        if( $days == 7){ 
            // store label as date 
            for ($i=0; $i < 7; $i++) { 
                $statistics['label'][] = date('Y-m-d', strtotime('-'.$i.' days'));
            }
             $statistics['label'] = array_reverse($statistics['label']); 
        } 
        if( $days == 30){ // This Month every Days
            // store label as date  
            // Count First how many days in this month
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $currentMonth = date('m');
            $currentYear = date('Y');   
            // Get Current month 
             
            for ($day = 1; $day <= $days_in_month; $day++) {
                $statistics['label'][] = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));
            }
            


            
        }
        if( $days == 3){  // last 3 Months
            // store label as Month Name  
            for ($i=0; $i <= 2; $i++) { 
                $statistics['label'][] = date('F', strtotime('-'.$i.' months'));
            }
             $statistics['label'] = array_reverse($statistics['label']);
            
             
            
        }
        if( $days == 12){  // This year as month 
            // store label as Month Name 
            $statistics['label'] = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); 
        } else{
            // $statistics['label'] = array_reverse($statistics['label']);
        }


        
        foreach ($statistics['label'] as $key => $value) {
            // $date = $value;
            // $next_date = $key != 0 ? $statistics['label'][$key - 1] : $current_date;
            if($days == 12 || $days == 3){
                $date = date('Y-m-d', strtotime('first day of '.$value));
                $next_date = date('Y-m-d', strtotime('last day of '.$value));
            }
            if($days == 30 || $days == 7){ // value is a date exp 2021-09-01 
                $date = $value;
                $next_date = $value;
            }
           
            $bookings = $booking->get(
                "created_at BETWEEN '$date 00:00:00' AND '$next_date 23:59:59'", 
                false, 
                false,
                true
            );
            $statistics['total_bookings'][] = count($bookings);
            $statistics['cancelled_bookings'][] = count(array_filter($bookings, function($booking){
                return $booking->status == 'cancelled';
            }));
            $statistics['completed_bookings'][] = count(array_filter($bookings, function($booking){
                return $booking->status == 'completed';
            }));
        }



 

        $data = array (
            'status' => true, 
            'statistics' => $statistics,  
            'data' => $days_in_month,  
        );

        return rest_ensure_response($data);

    }
}


