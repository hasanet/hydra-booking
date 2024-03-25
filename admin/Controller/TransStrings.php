<?php 
namespace HydraBooking\Admin\Controller;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class TransStrings {

     public static function getTransStrings(){
        return array(
            //  Common 
            'View Documentation' =>  __('View Documentation', 'hydra-booking'),
            'Save' =>  __('Save', 'hydra-booking'),
            
            // General Settings
            'General' =>  __('General', 'hydra-booking'),
            'Update General Settings' =>  __('Update General Settings', 'hydra-booking'),
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

            // Availability Settings
            'Availability' =>  __('Availability', 'hydra-booking'),
            'Title' =>  __('Title', 'hydra-booking'),
            'Set up booking times when you are available' =>  __('Set up booking times when you are available', 'hydra-booking'),
            ' Add New Availability' =>  __(' Add New Availability', 'hydra-booking'),

            // Integrations
            'Integrations' =>  __('Integrations', 'hydra-booking'),
            'One-liner description' =>  __('One-liner description', 'hydra-booking'),

            // Hosts
            'Add New Host' =>  __('Add New Host', 'hydra-booking'), 
            'Select Hosts' =>  __('Select Hosts', 'hydra-booking'), 
            'Create Hosts' =>  __('Create Hosts', 'hydra-booking'), 
            'First name' =>  __('First name', 'hydra-booking'), 
            'Type your first name' =>  __('Type your first name', 'hydra-booking'), 
            'Last name' =>  __('Last name', 'hydra-booking'), 
            'Type your last name' =>  __('Type your last name', 'hydra-booking'), 
            'Email' =>  __('Email', 'hydra-booking'), 
            'Type your email' =>  __('Type your email', 'hydra-booking'), 
            'Mobile' =>  __('Mobile', 'hydra-booking'), 
            'Type your mobile no' =>  __('Type your mobile no', 'hydra-booking'), 

            // Meetings
            'Create New Meeting' => __("Create New Meeting", 'hydra-booking'),
        );
     } 

}


