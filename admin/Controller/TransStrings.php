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
            'Save & Validate' =>  __('Save & Validate', 'hydra-booking'),
            
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
            'Select Template' =>  __('Select Template', 'hydra-booking'),
            'From' =>  __('From', 'hydra-booking'),
            'Enter From Email' =>  __('Enter From Email', 'hydra-booking'),
            'Enter Mail Subject' =>  __('Enter Mail Subject', 'hydra-booking'),
            'Subject' =>  __('Subject', 'hydra-booking'),
            'Mail Body' =>  __('Mail Body', 'hydra-booking'),
            'Update' =>  __('Update', 'hydra-booking'),

            // Availability Settings
            'Availability' =>  __('Availability', 'hydra-booking'),
            'Title' =>  __('Title', 'hydra-booking'),
            'Set up booking times when you are available' =>  __('Set up booking times when you are available', 'hydra-booking'),
            ' Add New Availability' =>  __(' Add New Availability', 'hydra-booking'),

            // Integrations
            'Integrations' =>  __('Integrations', 'hydra-booking'),
            'One-liner description' =>  __('One-liner description', 'hydra-booking'),
            'Zoom Account ID' =>  __('Zoom Account ID', 'hydra-booking'),
            'Enter Your Account ID' =>  __('Enter Your Account ID', 'hydra-booking'),
            'Zoom App Client ID' =>  __('Zoom App Client ID', 'hydra-booking'),
            'Enter Your App Client ID' =>  __('Enter Your App Client ID', 'hydra-booking'),
            'Zoom App Secret Key' =>  __('Zoom App Secret Key', 'hydra-booking'),
            'Enter Your App Secret Key' =>  __('Enter Your App Secret Key', 'hydra-booking'),

            // Hosts
            'Add New Host' =>  __('Add New Host', 'hydra-booking'), 
            'Select User' =>  __('Select User', 'hydra-booking'), 
            'Create Hosts' =>  __('Create Hosts', 'hydra-booking'), 
            'First name' =>  __('First name', 'hydra-booking'), 
            'Type your first name' =>  __('Type your first name', 'hydra-booking'), 
            'Last name' =>  __('Last name', 'hydra-booking'), 
            'Type your last name' =>  __('Type your last name', 'hydra-booking'), 
            'Email' =>  __('Email', 'hydra-booking'), 
            'Type your email' =>  __('Type your email', 'hydra-booking'), 
            'Mobile' =>  __('Mobile', 'hydra-booking'), 
            'Type your mobile no' =>  __('Type your mobile no', 'hydra-booking'), 
            'Profile image' =>  __('Profile image', 'hydra-booking'), 
            'Recommended Image Size: 400x400px' =>  __('Recommended Image Size: 400x400px', 'hydra-booking'), 
            'Username' =>  __('Username', 'hydra-booking'), 
            'Type Username' =>  __('Type Username', 'hydra-booking'), 
            'Type User Email' =>  __('Type User Email', 'hydra-booking'), 
            'Password' =>  __('Password', 'hydra-booking'), 
            'Type User Password' =>  __('Type User Password', 'hydra-booking'), 

            // Meetings
            'Create New Meeting' => __("Create New Meeting", 'hydra-booking'),
            'Meeting title' => __("Meeting title", 'hydra-booking'),
            'Type meeting title' => __("Type meeting title", 'hydra-booking'),
            'Description' => __("Description", 'hydra-booking'),
            'Describe about meeting' => __("Describe about meeting", 'hydra-booking'),
            'Duration' => __("Duration", 'hydra-booking'),
            'Allow attendee to select duration' => __('Allow attendee to select duration', 'hydra-booking'),
            'Select Category' => __('Select Category', 'hydra-booking'),
            'Location' => __('Location', 'hydra-booking'),
            'Address' => __('Address', 'hydra-booking'),
            'Type Address' => __('Type Address', 'hydra-booking'),
            'Save & Continue' => __('Save & Continue', 'hydra-booking'),
            'Select Host' => __('Select Host', 'hydra-booking'),
            'Name' => __('Name', 'hydra-booking'),
            'Choose Schedule' => __('Choose Schedule', 'hydra-booking'),
            'Type your name' => __('Type your name', 'hydra-booking'),
            'Email' => __('Email', 'hydra-booking'),
            'Type your email' => __('Type your email', 'hydra-booking'),
            'Buffer time before meeting' => __('Buffer time before meeting', 'hydra-booking'),
            'Buffer time after meeting' => __('Buffer time after meeting', 'hydra-booking'),
            'Meeting interval' => __('Meeting interval', 'hydra-booking'),
            'Use meeting length (default)' => __('Use meeting length (default)', 'hydra-booking'),
            'Booking frequency' => __('Booking frequency', 'hydra-booking'),
            'Limit how many times this meeting can be booked' => __("Limit how many times this meeting can be booked", 'hydra-booking'),
            'Repeats every' => __("Repeats every", 'hydra-booking'),
            'For a maximum of' => __("For a maximum of", 'hydra-booking'),
            'Field type' => __("Field type", 'hydra-booking'),
            'Type level here' => __('Type level here', 'hydra-booking'),
            'Level' => __('Level', 'hydra-booking'),
            'Required' => __('Required', 'hydra-booking'),
            'Add Another Location' => __('Add Another Location', 'hydra-booking'),
            'Availability title'=> __('Availability title', 'hydra-booking'),
            'Attendee can cancel this meeting' => __('Attendee can cancel this meeting', 'hydra-booking'),
            'Attendee can reschedule this meeting' => __('Attendee can reschedule this meeting', 'hydra-booking'),
            'Price' => __('Price', 'hydra-booking'),
            'Save & Preview' => __('Save & Preview', 'hydra-booking'),

            //Booking
            'Add New Booking' => __('Add New Booking', 'hydra-booking'),
            'Date & Time' => __('Date & Time', 'hydra-booking'),
            'Title' => __('Title', 'hydra-booking'),
            'Host' => __('Host', 'hydra-booking'),
            'Attendee' => __('Attendee', 'hydra-booking'),
            'Duration' => __('Duration', 'hydra-booking'),
            'Status' => __('Status', 'hydra-booking'),
            'Action' => __('Action', 'hydra-booking'),
        );
     } 

}


