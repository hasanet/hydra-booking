<?php 
namespace HydraBooking\Admin\Controller;
 
 //  Use Namespace
 use HydraBooking\Admin\Controller\RouteController;
 
 // Use DB 
use HydraBooking\DB\Booking;
use HydraBooking\DB\Host;
use HydraBooking\Admin\Controller\DateTimeController;
use HydraBooking\DB\Meeting;

if ( ! defined( 'ABSPATH' ) ) { exit; }

  class BookingController {
    

    // constaract
    public function __construct() { 
       
    }

    public function init() {
        
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/booking/lists', array(
            'methods' => 'GET',
            'callback' => array($this, 'getBookingsData'),
        ));  
        register_rest_route('hydra-booking/v1', '/booking/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'CreateBooking'),
        ));  
        register_rest_route('hydra-booking/v1', '/booking/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'DeleteBooking'),
        ));  
        // Get Single Booking based on id
        register_rest_route('hydra-booking/v1', '/booking/(?P<id>[0-9]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getBookingData'),
        ));
        register_rest_route('hydra-booking/v1', '/booking/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'updateBooking'),
        ));   

        // Pre Booking Data
        register_rest_route('hydra-booking/v1', '/booking/pre', array(
            'methods' => 'GET',
            'callback' => array($this, 'getPreBookingsData'),
        )); 
        register_rest_route('hydra-booking/v1', '/booking/meeting', array(
            'methods' => 'POST',
            'callback' => array($this, 'getpreMeetingData'),
        ));  
        register_rest_route('hydra-booking/v1', '/booking/availabletime', array(
            'methods' => 'POST',
            'callback' => array($this, 'getAvailableTimeData'),
        ));  
       
    }
    // Booking List
    public function getBookingsData() { 

        $current_user = wp_get_current_user();
		// get user role
		$current_user_role = ! empty( $current_user->roles[0] ) ? $current_user->roles[0] : '';
        $current_user_id = $current_user->ID;

        // Booking Lists 
        $booking = new Booking();

        if(!empty($current_user_role) && "administrator"==$current_user_role){
            $bookingsList = $booking->get(null, true);
        }
        if(!empty($current_user_role) && "tfhb_host"==$current_user_role){
            $host = new Host();
            $HostData = $host->get( $current_user_id  );

            $bookingsList = $booking->get(null, true, false, false, false, false, $HostData->id);
        }
        
        // Return response
        $data = array(
            'status' => true, 
            'bookings' => $bookingsList,  
            'message' => 'Booking Data Successfully Retrieve!', 
        );
        return rest_ensure_response($data);
    }  

    // Pre Booking Data
    public function getPreBookingsData(){
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

        $meeting = new Meeting();
        $MeetingsList = $meeting->get();

        $meeting_array = array();
        foreach($MeetingsList as $single){
            $meeting_array[] = array(
                'name' => $single->title,
                'value' => "".$single->id."",
            );
        }

        $data = array(
            'status' => true, 
            'time_zone' => $time_zone,
            'meetings' => $meeting_array
        ); 
        return rest_ensure_response($data);
    }

    // Pre Meeting Data
    public function getpreMeetingData(){
        $request = json_decode(file_get_contents('php://input'), true);
        $meeting_id = isset($request['meeting_id']) ? $request['meeting_id'] : '';

        // Single Meeting Data
        $meeting = new Meeting();
        $MeetingsData = $meeting->get($meeting_id);
        
        // Meeting Location
        $meeting_locations = !empty($MeetingsData->meeting_locations) ? json_decode($MeetingsData->meeting_locations) : [''];

        $meeting_location_array = array();
        if(!empty($meeting_locations)){
            foreach($meeting_locations as $single){
                if($single->location){
                    $meeting_location_array[] = array(
                        'name' => $single->location,
                        'value' => "".$single->location."",
                    );
                }
            }
        }

        // Host List
        $host = new Host();
        $HostData = $host->get( $MeetingsData->host_id  );

        $meeting_host_array = array();
        if($HostData->first_name){
            $meeting_host_array[] = array(
                'name' => $HostData->first_name.' '.$HostData->last_name,
                'value' => "".$HostData->id."",
            );
        }

        // Meeting Information
        $data = get_post_meta($MeetingsData->post_id, '__tfhb_meeting_opt', true);

        if( isset($data['availability_type']) && 'settings' === $data['availability_type']){  
            $_tfhb_availability_settings = get_user_meta($host_id, '_tfhb_host', true); 
            if(in_array($data['availability_id'], array_keys($_tfhb_availability_settings['availability']))){
                $availability_data = $_tfhb_availability_settings['availability'][$data['availability_id']]; 
            }else{
                $availability_data = isset($data['availability_custom']) ? $data['availability_custom'] : array(); 
            } 
         
        }else{ 
            $availability_data = isset($data['availability_custom']) ? $data['availability_custom'] : array(); 
        }
        
           

        // Availability Range
        $availability_range = isset($data['availability_range']) ? $data['availability_range'] : array();
        $availability_range_type = isset($data['availability_range_type']) ? $data['availability_range_type'] : array();

        // Duration
        $duration = isset($data['duration']) && !empty($data['duration'])? $data['duration'] : 30;

        $duration = isset($data['custom_duration']) && !empty($data['custom_duration']) ? $data['custom_duration'] : $duration;

        // Buffer Time Before
        $buffer_time_before = isset($data['buffer_time_before']) && !empty($data['buffer_time_before']) ? $data['buffer_time_before'] : 0;

        // Buffer Time After
        $buffer_time_after = isset($data['buffer_time_after']) && !empty($data['buffer_time_after']) ? $data['buffer_time_after'] : 0;

        // Meeting Interval
        $meeting_interval = isset($data['meeting_interval']) && !empty($data['meeting_interval']) ? $data['meeting_interval'] : 0;

        // Disable Dates
        $disabled_dates = array();
        if($availability_data['date_slots'] != ''){
            $date_slots = $availability_data['date_slots'];
            foreach($date_slots as $single){
                if($single['available'] == true ){
                    // string to array
                    $dates = explode(',', $single['date']); 
                    foreach($dates as $date){
                        $disabled_dates[] = $date;
                    }

                }

            }
        } 
        // Disable Unavailable days
        $unavailable_days = isset($availability_data['time_slots']) ? $availability_data['time_slots'] : array(); 

        
        // day array based on js date key value
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
 

        $unavailable_days_array = array();
        
        foreach($unavailable_days as $single){ 
            $unavailable_days_array[$single['day']] = $single['status'] == false ?  array_search($single['day'], $days) : 8; 
        }



        // flatpickr configuration only for date not time
        $config = array(
            // 'enableTime' => false,
            'dateFormat' => 'Y-m-d',
            'minDate' => 'today',
            'defaultDate' => 'today',
            'disable' =>  $disabled_dates,
            'disable_days' =>  $unavailable_days_array,
        );
        

       if($availability_range_type != 'indefinitely' ){  
            $config['maxDate'] = $availability_range['end'];
        } 
             

 
        $data = array(
            'status' => true, 
            'locations' => $meeting_location_array,
            'hosts' => $meeting_host_array,
            'available_slot' => $unavailable_days_array,
            'flatpickr_date' => $config, 
        ); 
        return rest_ensure_response($data);

    }

    // Pre Meeting Data
    public function getAvailableTimeData(){
        $request = json_decode(file_get_contents('php://input'), true);
        $meeting_id = isset($request['meeting_id']) ? $request['meeting_id'] : '';

        $meeting = new Meeting();
        $MeetingsData = $meeting->get($meeting_id);

        $selected_date = isset($request['date']) ? sanitize_text_field($request['date']) : '';
      
        $selected_time_zone = isset($request['time_zone']) ? sanitize_text_field($request['time_zone']) : 'UTC';

        $selected_time_format = '12';
         // Meeting Information
        $data = get_post_meta($MeetingsData->post_id, '__tfhb_meeting_opt', true);

        if( isset($data['availability_type']) && 'settings' === $data['availability_type']){  
            $_tfhb_availability_settings = get_user_meta( $MeetingsData-> host_id, '_tfhb_host', true); 
            if(in_array($data['availability_id'], array_keys($_tfhb_availability_settings['availability']))){
                $availability_data = $_tfhb_availability_settings['availability'][$data['availability_id']]; 
            }else{
                $availability_data = isset($data['availability_custom']) ? $data['availability_custom'] : array(); 
            } 
         
        }else{ 
            $availability_data = isset($data['availability_custom']) ? $data['availability_custom'] : array(); 
        }
        

        // Disable Unavailable days
        $time_slots = isset($availability_data['time_slots']) ? $availability_data['time_slots'] : array(); 

         // Duration
        $duration = isset($data['duration']) && !empty($data['duration'])? $data['duration'] : 30;

        $duration = isset($data['custom_duration']) && !empty($data['custom_duration']) ? $data['custom_duration'] : $duration;

        // Buffer Time Before
        $buffer_time_before = isset($data['buffer_time_before']) && !empty($data['buffer_time_before']) ? $data['buffer_time_before'] : 0;

        // Buffer Time After
        $buffer_time_after = isset($data['buffer_time_after']) && !empty($data['buffer_time_after']) ? $data['buffer_time_after'] : 0;

        // Meeting Interval
        $meeting_interval = isset($data['meeting_interval']) && !empty($data['meeting_interval']) ? $data['meeting_interval'] : 0;

        // Disable Dates

        // Get All Booking Data.
        $booking = new Booking();
        $bookings = $booking->get(array('meeting_dates' => $selected_date)); 
        $date_time = new DateTimeController( $selected_time_zone );
 

        $disabled_times = array();
        foreach($bookings as $booking){
            $start_time = $booking->start_time;
            $end_time = $booking->end_time;
            $time_zone = $booking->attendee_time_zone; 
 
            $start_time = $date_time->convert_time_based_on_timezone($start_time, $time_zone, $selected_time_zone, $selected_time_format);
            $end_time = $date_time->convert_time_based_on_timezone($end_time, $time_zone, $selected_time_zone, $selected_time_format);

            $disabled_times[] = array(
                'start_time' => $start_time,
                'end_time' => $end_time,
            );

        }

        // Time Slot
        $time_slots_data = array();
        // get Selected Date day
        $selected_day = date('l', strtotime($selected_date));
        
        // only get selected day time slot in single array using array finter
        $selected_available_time = array();
        $selected_available = array();
        foreach ($time_slots as $single) {
            if($single['day'] == $selected_day){
                $selected_available = $single;
            }
        }
        $times = $selected_available ? $selected_available['times'] : array();
        foreach($times as $key => $value){
            $start_time = $value['start']; 
            $end_time = $value['end'];
            $generatedSlots = $this->generateTimeSlots($start_time, $end_time, $duration, $meeting_interval, $buffer_time_before, $buffer_time_after, $selected_date, $selected_time_format, $selected_time_zone);
            $time_slots_data = array_merge($time_slots_data, $generatedSlots);

        }
        // if date already exists remove that array
        $time_slots_data = array_filter($time_slots_data, function($time_slot) use ($disabled_times) {
            foreach ($disabled_times as $disabled_time) {
                if ($time_slot['start'] === $disabled_time['start_time'] && $time_slot['end'] === $disabled_time['end_time']) {
                    return false;
                }
            }
            return true;
        }); 
        return rest_ensure_response(
            array(
                'status' => true, 
                'time_slots_data' => $time_slots_data,
            )
        );
    }

    public function generateTimeSlots($startTime, $endTime, $duration, $meeting_interval, $buffer_time_before, $buffer_time_after, $selected_date, $time_format, $time_zone) {
        $timeSlots = [];
    
        $skip_before_meeting_start = 100; // Example value, replace with your actual setting
        $start = new \DateTime($selected_date . ' ' . $startTime, new \DateTimeZone($time_zone));
        $end = new \DateTime($selected_date . ' ' . $endTime, new \DateTimeZone($time_zone));
        $current = clone $start;
        $before = clone $start;
        $after = clone $start;
    
        $diff = $duration * 60; // Convert to seconds
        $before_diff = $buffer_time_before * 60; // Convert to seconds
        $after_diff = $buffer_time_after * 60; // Convert to seconds
        $meeting_interval = $meeting_interval * 60; // Convert to seconds
        $total_diff = $diff + $before_diff + $after_diff;
    
        while ($current < $end) {
            $start_time = $this->formatTime($current, $time_format, $time_zone);
            $end_time = $this->formatTime((clone $current)->modify("+$total_diff seconds"), $time_format, $time_zone);
    
            // if current time is passed then skip skip_before_meeting_start
            $current_minus_skip = (clone $current)->modify("-$skip_before_meeting_start minutes");
            if (new \DateTime("now", new \DateTimeZone($time_zone)) > $current_minus_skip) {
                $current->modify("+$total_diff seconds")->modify("+$meeting_interval seconds");
                continue;
            }
    
            $timeSlots[] = [
                'start' => $start_time,
                'end' => $end_time,
            ];
    
            $current->modify("+$total_diff seconds")->modify("+$meeting_interval seconds");
        }
    
        return $timeSlots;
    }

    public function formatTime($dateTime, $timeFormat, $timeZone) {
        $dateTime->setTimezone(new \DateTimeZone($timeZone));
        
        $format = '';
        if ($timeFormat === '12') {
            $format = 'h:i A'; // 12-hour format with AM/PM
        } else {
            $format = 'H:i'; // 24-hour format
        }
        
        return $dateTime->format($format);
    }
    // Create Booking
    public function CreateBooking() { 
        
        $request = json_decode(file_get_contents('php://input'), true);

        $data = [ 
            'meeting_id' => isset($request['meeting']) ? $request['meeting'] : '',
            'attendee_name' => isset($request['name']) ? $request['name'] : '',
            'email' => isset($request['email']) ? $request['email'] : '',
            'attendee_time_zone' => isset($request['time_zone']) ? $request['time_zone'] : '',
            'host_id' => isset($request['host']) ? $request['host'] : '',
            'meeting_dates' => isset($request['date']) ? $request['date'] : '',
            'start_time' => isset($request['time']) ? $request['time'] : '',
            'end_time' => isset($request['time']) ? $request['time'] : '',
            'status' => isset($request['status']) ? $request['status'] : '',
            'payment_method' => 'backend',
            'payment_status' => 'pending',
        ];

        // Check if user is already a booking
        $booking = new Booking();
        // Insert booking
        $bookingInsert = $booking->add($data);
        if(!$bookingInsert['status']) {
            return rest_ensure_response(array('status' => false, 'message' => 'Error while creating Booking'));
        }
        $booking_id = $bookingInsert['insert_id'];

        // booking Lists 
        $booking_List = $booking->get();
        // Return response
        $data = array(
            'status' => true, 
            'booking' => $booking_List,  
            'id' => $booking_id,  
            'message' => 'Booking Created Successfully', 
        );
        
        return rest_ensure_response($data);
    }

    // Delete Booking
    public function DeleteBooking(){
        
        // Meeting Lists
        $MeetingsList = $meeting->get(null, true);
        // Return response
        $data = array(
            'status' => true, 
            'meetings' => $MeetingsList,  
            'message' => 'Booking Deleted Successfully', 
        );
        return rest_ensure_response($data);
    }

    // Get Single Booking
    public function getBookingData($request){
        
        // Return response
        $data = array(
            'status' => true, 
            'meeting' => $MeetingData,  
            'message' => 'Booking Data',
        );
        return rest_ensure_response($data);

    }

    // Update Booking Information
    public function updateBooking(){
        $request = json_decode(file_get_contents('php://input'), true);
        $booking_id = $request['id'];
        if (empty($booking_id) || $booking_id == 0) {
            return rest_ensure_response(array('status' => false, 'message' => 'Invalid Booking'));
        }

        $data = [ 
            'id' => $request['id'],
            'status' => isset($request['status']) ? sanitize_text_field($request['status']) : ''
        ];

        $booking = new Booking();
        // Booking Update
        $bookingUpdate = $booking->update($data);

        // Booking Lists 
        $booking_List = $booking->get(null, true);

        // Single Booking 
        $single_booking_meta = $booking->get($request['id']);

        if("approved"==$request['status']){
            do_action('hydra_booking/after_booking_completed', $single_booking_meta);
        }

        if("canceled"==$request['status']){
            do_action('hydra_booking/after_booking_canceled', $single_booking_meta);
        }

        if("schedule"==$request['status']){
            do_action('hydra_booking/after_booking_schedule', $single_booking_meta);
        }

        // Return response
        $data = array(
            'status' => true,  
            'booking' => $booking_List, 
            'message' => 'Booking Updated Successfully', 
        );
        return rest_ensure_response($data);
    }
} 