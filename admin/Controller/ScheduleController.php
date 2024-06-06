<?php 
namespace HydraBooking\Admin\Controller; 
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }
  use HydraBooking\DB\Booking;
  use HydraBooking\Admin\Controller\DateTimeController;

  class ScheduleController { 

    // constaract
    public function __construct() {   


      


        add_action('tfhb_after_booking_completed_schedule', array($this, 'tfhb_after_booking_completed_schedule_callback'));



 

    }
    public function tfhb_create_cron_job(){ 

        // Make custom Shedule intervel 
        add_filter( 'cron_schedules', array($this, 'tfhb_custom_cron_job_schedule') ); 

        // Add Cron Job Schedule
        $this->tfhb_cron_job_schedule();
    }

    public function tfhb_custom_cron_job_schedule( $schedules ) {
        if( !isset($schedules['tfhb_after_booking_status'])){
            $every_minute = 60;
            // Convert into seconds
            $every_minute = $every_minute * 60;
            $schedules['tfhb_after_booking_status'] = array( 
                'interval' => $every_minute,
                'display'  => __( 'Once Daily' ),
            );
        }
        
        return $schedules;
    }
    // Create Cron Job Schedule
    public function tfhb_cron_job_schedule(){


        // Schedule Cron Job Before Booking Completed specific time 
        if ( ! wp_next_scheduled( 'tfhb_after_booking_completed_schedule' ) ) {  
            // Create custom interverl for 60 minutes
            wp_schedule_event( time(), 'tfhb_after_booking_status', 'tfhb_after_booking_completed_schedule' );
        }
    }

    function tfhb_after_booking_completed_schedule_callback(){ 

        
        $general_settings = get_option('_tfhb_general_settings', true) ? get_option('_tfhb_general_settings', true) : array();
        $after_booking_completed = isset($general_settings['after_booking_completed']) ? $general_settings['after_booking_completed'] : 60; // minutes

        // Get all bookings current date before 60 minutes of Current Time and status is confirmed
        $time = date('H:i:s', strtotime('-'.$after_booking_completed.' minutes', strtotime(date('H:i:s'))));
        // meeting_dates 2024-06-06
        $meeting_dates = date('Y-m-d', strtotime(date('Y-m-d'))); 
        $booking = new Booking(); 
        $bookings = $booking->get(array(
            'status' => 'confirmed', 
            'meeting_dates' => $meeting_dates, 
        ));
        foreach ($bookings as $key => $value) { 
            $DateTime = new DateTimeController($value->attendee_time_zone);
                    // Time format if has AM and PM into start time
            $time_format =strpos($value->start_time, 'AM') || strpos($value->start_time, 'PM') ? '12' : '24'; 
            $before_booking_completed = $DateTime->convert_time_based_on_timezone( date('Y-m-d H:i:s', strtotime('-'.$after_booking_completed.' minutes')), 'UTC', $value->attendee_time_zone,  $time_format);;
 

            if($value->end_time < $before_booking_completed){
                $booking_meta = get_post_meta($value->id, '_tfhb_booking_opt', true);
                $$booking_meta['status'] = 'completed';
                update_post_meta($value->id, '_tfhb_booking_opt', $booking_meta);
                $update = array(
                    'id' =>  $value->id, 
                    'status' => 'completed',
                );  
                $booking->update($update);
            } 
        } 

        return true;
    }
     
    // public function tfhb_after_booking_completed_schedule_update($after_booking_completed){

    //    // Get the current schedules
    //    $schedules = wp_get_schedules();
        
    //     // Check if the desired schedule exists
    //     if ( isset( $schedules['tfhb_after_booking_status'] ) ) {
    //         // Update the interval
    //         $interval_seconds = $interval_minutes * MINUTE_IN_SECONDS;
    //         $schedules['tfhb_after_booking_status']['interval'] = $interval_seconds;
    //         $schedules['tfhb_after_booking_status']['display'] = sprintf( __( 'Every %d Minutes' ), $interval_minutes );

    //         // Deregister the original schedule
    //         unset( $schedules['tfhb_after_booking_status'] );

    //         // Re-register the updated schedule
    //         add_filter( 'cron_schedules', array( $this, 'register_updated_cron_schedule' ) );
    //     }

    //     wp_clear_scheduled_hook( 'tfhb_after_booking_completed_schedule' );
    //     $this->tfhb_cron_job_schedule();

    // }
    
}


