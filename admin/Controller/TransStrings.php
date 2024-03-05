<?php 
namespace HydraBooking\Admin\Controller;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class TransStrings {

     public static function getTransStrings(){
        return array(
            //  Common 
            'View Documentation' =>  __('View Documentation', 'hydra-booking'),
            
            // General Settings
            'General' =>  __('General', 'hydra-booking'),
            'General Settings' =>  __('General Settings', 'hydra-booking'),
            'Manage your time zone settings and bookings' =>  __('Manage your time zone settings and bookings', 'hydra-booking'),
            'Date and Time' =>  __('Date and Time', 'hydra-booking'),
            'Date and Time Settings' =>  __('Date and Time Settings', 'hydra-booking'),
            'Time zone' =>  __('Time zone', 'hydra-booking'),
            'Time format' =>  __('Time format', 'hydra-booking'),
            'Date format' =>  __('Date format', 'hydra-booking'),
            'Week start from' =>  __('Week start from', 'hydra-booking'),
            'Select country for phone code' =>  __('Select country for phone code', 'hydra-booking'),
            'Bookings' =>  __('Bookings', 'hydra-booking'), 
            'Manage your bookings and reservations' =>  __('Manage your bookings and reservations', 'hydra-booking'),
            'Bookings will be completed automatically after' =>  __('Bookings will be completed automatically after', 'hydra-booking'),
            'Default status of bookings' =>  __('Default status of bookings', 'hydra-booking'),
            'Minimum time required before Booking/Cancel/Reschedule' =>  __('Minimum time required before Booking/Cancel/Reschedule', 'hydra-booking'),
        );
     } 

}


