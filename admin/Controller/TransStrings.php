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
            'Statistics' =>  __('Statistics', 'hydra-booking'),
            'This Week' =>  __('This Week', 'hydra-booking'),
            
            // General Settings
            'General' =>  __('General', 'hydra-booking'),
            'Update General Settings' =>  __('Update General Settings', 'hydra-booking'),
            'Update Host Settings' =>  __('Update Host Settings', 'hydra-booking'),
            'General Settings' =>  __('General Settings', 'hydra-booking'),
            'Host Settings' =>  __('Host Settings', 'hydra-booking'),
            'General Information' =>  __('General Information', 'hydra-booking'),
            'Others Information' =>  __('Others Information', 'hydra-booking'),
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
            'Confirmed bookings by default.' =>  __('Confirmed bookings by default.', 'hydra-booking'),
            'Confirmed reschedule by default.' =>  __('Confirmed reschedule by default.', 'hydra-booking'),
            'Minimum time required before Booking/Cancel/Reschedule' =>  __('Minimum time required before Booking/Cancel/Reschedule', 'hydra-booking'),
            'Select Template' =>  __('Select Template', 'hydra-booking'),
            'From' =>  __('From', 'hydra-booking'),
            'Enter From Email' =>  __('Enter From Email', 'hydra-booking'),
            'Enter Mail Subject' =>  __('Enter Mail Subject', 'hydra-booking'),
            'Subject' =>  __('Subject', 'hydra-booking'),
            'Mail Body' =>  __('Mail Body', 'hydra-booking'),
            'Update' =>  __('Update', 'hydra-booking'),
            'Mark to Unavailable' => __('Mark to Unavailable', 'hydra-booking'),
            'Host Can Manage Dashboard' => __('Host Can Manage Dashboard', 'hydra-booking'),
            'Host Can Manage Meetings' => __('Host Can Manage Meetings', 'hydra-booking'),
            'Host Can Manage Booking' => __('Host Can Manage Booking', 'hydra-booking'),
            'Host Can Manage Settings' => __('Host Can Manage Settings', 'hydra-booking'),
            'Host Can Manage Custom Availability' => __('Host Can Manage Custom Availability', 'hydra-booking'),
            'Host Can Manage Integrations' => __('Host Can Manage Integrations', 'hydra-booking'),
            'Appearance' => __('Appearance', 'hydra-booking'),
            'Typography' => __('Typography', 'hydra-booking'),
            'For title' => __('For title', 'hydra-booking'),
            'For paragraph' => __('For paragraph', 'hydra-booking'),
            'Set your own typography for your brand' => __('Set your own typography for your brand', 'hydra-booking'),
            'This only applies to your attendee booking pages' => __('This only applies to your attendee booking pages', 'hydra-booking'),
            'Theme' => __('Theme', 'hydra-booking'),
            'Customize your own brand color into your booking page' => __('Customize your own brand color into your booking page', 'hydra-booking'),
            'Custom brand colors' => __('Custom brand colors', 'hydra-booking'),
            'Admin Email' => __('Admin Email', 'hydra-booking'),
            'Type your Admin Email' => __('Type your Admin Email', 'hydra-booking'),
            'Meeting Category' => __('Meeting Category', 'hydra-booking'),
            'Category Title' => __('Category Title', 'hydra-booking'),
            'Save Category' => __('Save Category', 'hydra-booking'),
            'Update Category' => __('Update Category', 'hydra-booking'),
            'Update Availability' => __('Update Availability', 'hydra-booking'),
            'Save Availability' => __('Save Availability', 'hydra-booking'),
            'Primary Color' => __('Primary Color', 'hydra-booking'),
            'Secondary Color' => __('Secondary Color', 'hydra-booking'),
            'Select Color' => __('Select Color', 'hydra-booking'),
            'Previous' => __('Previous', 'hydra-booking'),
            'Next' => __('Next', 'hydra-booking'),
            'Information Builder' => __('Information Builder', 'hydra-booking'),
            'Permission' => __('Permission', 'hydra-booking'),
            'To Host' => __('To Host', 'hydra-booking'),
            'To Attendee' => __('To Attendee', 'hydra-booking'),
            'Booking Confirmation' => __('Booking Confirmation', 'hydra-booking'),
            'Booking Cancel' => __('Booking Cancel', 'hydra-booking'),
            'Booking Reschedule' => __('Booking Reschedule', 'hydra-booking'),
            'Booking Reminder' => __('Booking Reminder', 'hydra-booking'),
            'Notifications' => __('Notifications', 'hydra-booking'),
            'Paragraph Color' => __('Paragraph Color', 'hydra-booking'),
            'All' => __('All', 'hydra-booking'),
            'Conference' => __('Conference', 'hydra-booking'),
            'Calendars' => __('Calendars', 'hydra-booking'),
            'Payments' => __('Payments', 'hydra-booking'),

            // Availability Settings
            'Availability' =>  __('Availability', 'hydra-booking'),
            'Title' =>  __('Title', 'hydra-booking'),
            'Set up booking times when you are available' =>  __('Set up booking times when you are available', 'hydra-booking'),
            ' Add New Availability' =>  __(' Add New Availability', 'hydra-booking'),
            'Mark unavailable (All day)' => __('Mark unavailable (All day)', 'hydra-booking'),

            // Integrations
            'Integrations' =>  __('Integrations', 'hydra-booking'),
            'One-liner description' =>  __('One-liner description', 'hydra-booking'),
            'Zoom Account ID' =>  __('Zoom Account ID', 'hydra-booking'),
            'Enter Your Account ID' =>  __('Enter Your Account ID', 'hydra-booking'),
            'Zoom App Client ID' =>  __('Zoom App Client ID', 'hydra-booking'),
            'Enter Your App Client ID' =>  __('Enter Your App Client ID', 'hydra-booking'),
            'Zoom App Secret Key' =>  __('Zoom App Secret Key', 'hydra-booking'),
            'Enter Your App Secret Key' =>  __('Enter Your App Secret Key', 'hydra-booking'),
            'Client ID' =>  __('Client ID', 'hydra-booking'),
            'Enter Client ID' =>  __('Enter Client ID', 'hydra-booking'),
            'Secret Key' =>  __('Secret Key', 'hydra-booking'),
            'Enter Secret Key' =>  __('Enter Secret Key', 'hydra-booking'),
            'Redirect Url' =>  __('Redirect Url', 'hydra-booking'),
            'Enter Redirect Url' =>  __('Enter Redirect Url', 'hydra-booking'),
            'Payment Method' =>  __('Enter Redirect Url', 'hydra-booking'),
            'Apple Calendar' =>  __('Apple Calendar', 'hydra-booking'),
            'Mailchimp' => __('Mailchimp','hydra-booking'),
            'Mailchimp API Key' => __('Mailchimp API Key','hydra-booking'),
            'Enter Your API Key' => __('Enter Your API Key','hydra-booking'),
            'Connect Your Mailchimp API' => __('Connect Your Mailchimp API','hydra-booking'),
            'Please read the documentation here for step by step guide to know how you can get api credentials from Mailchimp Account' => __('Please read the documentation here for step by step guide to know how you can get api credentials from Mailchimp Account','hydra-booking'),
            'Webhook' => __('Webhook','hydra-booking'),
            'Integrations Title' => __('Integrations Title','hydra-booking'),
            'Type your Integrations Title' => __('Type your Integrations Title','hydra-booking'),
            'Other Fields' => __('Other Fields','hydra-booking'),
            'Add New Integrations' => __('Add New Integrations','hydra-booking'),
            'Select Audience' => __('Select Audience','hydra-booking'),
            'FluentCRM Lists' => __('FluentCRM Lists','hydra-booking'),
            'Select FluentCRM List' => __('Select FluentCRM List','hydra-booking'),
            'Contact Tags' => __('Contact Tags','hydra-booking'),
            'Select Contact Tag' => __('Select Contact Tag','hydra-booking'),
            'Attendee Data' => __('Attendee Data','hydra-booking'),
            'Host Data' => __('Host Data','hydra-booking'),
            'Booking Data' => __('Booking Data','hydra-booking'),
            'Zoho' => __('Zoho','hydra-booking'),
            'Connect Your Zoho Account' => __('Connect Your Zoho Account','hydra-booking'),
            'Please read the documentation here for step by step guide to know how you can get api credentials from Zoho Account' => __('Please read the documentation here for step by step guide to know how you can get api credentials from Zoho Account','hydra-booking'),
            'Zoho Redirect URL' => __('Zoho Redirect URL','hydra-booking'),
            'Zoho Client Secret' => __('Zoho Client Secret','hydra-booking'),
            'Enter Your Client Secret' => __('Enter Your Client Secret','hydra-booking'),
            'Enter Your Redirect URL' => __('Enter Your Redirect URL','hydra-booking'),
            'Zoho Client ID' => __('Zoho Client ID','hydra-booking'),
            'Enter Your Client ID' => __('Enter Your Client ID','hydra-booking'),
            'Modules' => __('Modules','hydra-booking'),
            'Select Modules' => __('Select Modules','hydra-booking'),
            '' => __('','hydra-booking'),
            '' => __('','hydra-booking'),
            '' => __('','hydra-booking'),

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
            'Use existing availability' => __('Use existing availability', 'hydra-booking'),
            'Custom availability' => __('Custom availability', 'hydra-booking'),
            'Weekly hours' => __('Weekly hours', 'hydra-booking'),
            'Add date overrides' => __('Add date overrides', 'hydra-booking'),
            'Add dates when your availability changes from your daily hours' => __('Add dates when your availability changes from your daily hours', 'hydra-booking'),
            'Change' => __('Change', 'hydra-booking'),
            'Activate' => __('Activate', 'hydra-booking'),
            'Deactivate' => __('Deactivate', 'hydra-booking'),
            'Delete' => __('Delete', 'hydra-booking'),
            'Edit' => __('Edit', 'hydra-booking'),
            'Profile' => __('Profile', 'hydra-booking'),
            'Information' => __('Information', 'hydra-booking'),
            'Stripe' => __('Stripe', 'hydra-booking'),
            'Stripe Secret Key' => __('Stripe Secret Key', 'hydra-booking'),
            'Stripe Public Key' => __('Stripe Public Key', 'hydra-booking'),
            'Enter Your Public Key' => __('Enter Your Public Key', 'hydra-booking'),
            'Enter Your Stripe Secret' => __('Enter Your Stripe Secret', 'hydra-booking'),
            'Connect Your Stripe Account' => __('Connect Your Stripe Account', 'hydra-booking'),
            'Please read the documentation here for step by step guide to know how you can get api credentials from Stripe Account' => __('Please read the documentation here for step by step guide to know how you can get api credentials from Stripe Account', 'hydra-booking'),
            'Apple ID (Email)' => __('Apple ID (Email)', 'hydra-booking'),
            'App Specific Password' => __('App Specific Password', 'hydra-booking'),
            'Enter Apple ID (Email)' => __('Enter Apple ID (Email)', 'hydra-booking'),
            'Enter App Specific Password' => __('Enter App Specific Password', 'hydra-booking'),

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
            'Type Placeholder here' => __('Type Placeholder here', 'hydra-booking'),
            'Placeholder' => __('Placeholder', 'hydra-booking'),
            'Level' => __('Level', 'hydra-booking'),
            'Required' => __('Required', 'hydra-booking'),
            'Add Another Location' => __('Add Another Location', 'hydra-booking'),
            'Availability title'=> __('Availability title', 'hydra-booking'),
            'Attendee can cancel this meeting' => __('Attendee can cancel this meeting', 'hydra-booking'),
            'Attendee can reschedule this meeting' => __('Attendee can reschedule this meeting', 'hydra-booking'),
            'Price' => __('Price', 'hydra-booking'),
            'Save & Preview' => __('Save & Preview', 'hydra-booking'),
            'Availability Range for this Booking' => __('Availability Range for this Booking', 'hydra-booking'),
            'How many days can the invitee schedule?' => __('How many days can the invitee schedule?', 'hydra-booking'),
            'Indefinitely into the future' => __('Indefinitely into the future', 'hydra-booking'),
            'Meeting will be go for indefinitely into the future' => __('Meeting will be go for indefinitely into the future', 'hydra-booking'),
            'Meeting will be only available on specific dates' => __('Meeting will be only available on specific dates', 'hydra-booking'),
            'Specific date range' => __('Specific date range', 'hydra-booking'),
            'Add override' => __('Add override', 'hydra-booking'),
            'Which hours are you free?' => __('Which hours are you free?', 'hydra-booking'),
            'Add an override' => __('Add an override', 'hydra-booking'),
            'Create and manage booking/appointment form' => __('Create and manage booking/appointment form', 'hydra-booking'),
            'Create One-to-One booking type' => __('Create One-to-One booking type', 'hydra-booking'),
            'Details' => __('Details', 'hydra-booking'),
            'Limits' => __('Limits', 'hydra-booking'),
            'Questions' => __('Questions', 'hydra-booking'),
            'Payment' => __('Payment', 'hydra-booking'),
            'Create Category' => __('Create Category', 'hydra-booking'),
            'Filter' => __('Filter', 'hydra-booking'),
            'All Category' => __('All Category', 'hydra-booking'),
            'All Host' => __('All Host', 'hydra-booking'),
            'Reset Filter' => __('Reset Filter', 'hydra-booking'),
            'minutes' => __('minutes', 'hydra-booking'),
            'One to One' => __('One to One', 'hydra-booking'),
            'One to Group' => __('One to Group', 'hydra-booking'),
            'Preview' => __('Preview', 'hydra-booking'),
            'Share' => __('Share', 'hydra-booking'),
            'Short code' => __('Short code', 'hydra-booking'),
            'Share link' => __('Share link', 'hydra-booking'),
            'Copy Code' => __('Copy Code', 'hydra-booking'),
            'Copy link' => __('Copy link', 'hydra-booking'),
            'Payment for this Meeting ' => __('Payment for this Meeting ', 'hydra-booking'),
            'You can enable or disable payment for this meeting by toggle switch' => __('You can enable or disable payment for this meeting by toggle switch', 'hydra-booking'),
            'Meeting Questions for Attendee' => __('Meeting Questions for Attendee', 'hydra-booking'),
            'Create your own booking page questions' => __('Create your own booking page questions', 'hydra-booking'),
            'Add Question for Attendee' => __('Add Question for Attendee', 'hydra-booking'),
            'Create custom form' => __('Create custom form', 'hydra-booking'),
            'Use existing form' => __('Use existing form', 'hydra-booking'),
            'Add more questions' => __('Add more questions', 'hydra-booking'),
            'Add New Availability' => __('Add New Availability', 'hydra-booking'),
            'New standard in online payment' => __('New standard in online payment', 'hydra-booking'),
            'Google Calendar' => __('Google Calendar', 'hydra-booking'),
            'Get Access Token' => __('Get Access Token', 'hydra-booking'),
            'Settings' => __('Settings', 'hydra-booking'),
            'Enable the calendars you want to check for conflicts to prevent double bookings.' => __('Enable the calendars you want to check for conflicts to prevent double bookings.', 'hydra-booking'),
            'Add Google Calendar' => __('Add Google Calendar', 'hydra-booking'),
            'Please read the documentation here for step by step guide to know how you can get api credentials from Google Calendar' => __('Please read the documentation here for step by step guide to know how you can get api credentials from Google Calendar', 'hydra-booking'),
            'Outlook Calendar' => __('Outlook Calendar', 'hydra-booking'), 
            'Add Outlook Calendar' => __('Add Outlook Calendar', 'hydra-booking'),
            'Please read the documentation here for step by step guide to know how you can get api credentials from Outlook Calendar' => __('Please read the documentation here for step by step guide to know how you can get api credentials from Outlook Calendar', 'hydra-booking'),
            'Woo Payment' => __('Woo Payment', 'hydra-booking'),
            'Click to Install & Active' => __('Click to Install & Active', 'hydra-booking'),
            'Connected' => __('Connected', 'hydra-booking'),
            'Zoom' => __('Zoom', 'hydra-booking'),
            'Please read the documentation here for step by step guide to know how you can get api credentials from Zoom Account' => __('Please read the documentation here for step by step guide to know how you can get api credentials from Zoom Account', 'hydra-booking'),
            'Add New Zoom User Account' => __('Add New Zoom User Account', 'hydra-booking'),
            'Create New Meeting Type' => __('Create New Meeting Type', 'hydra-booking'),
            'One host with group of invitee. Good for: webinars, online clasess' => __('One host with group of invitee. Good for: webinars, online clasess', 'hydra-booking'),
            'One host with one invitee. Good for: 1:1 interview, coffee chats' => __('One host with one invitee. Good for: 1:1 interview, coffee chats', 'hydra-booking'),
            'Booking' => __('Booking', 'hydra-booking'),
            'Add Information for Hosts' => __('Add Information for Hosts', 'hydra-booking'),
            'Add more Information' => __('Add more Information', 'hydra-booking'),
            'Options' => __('Options', 'hydra-booking'),
            'Add New Option' => __('Add New Option', 'hydra-booking'),
            'Mark as read' => __('Mark as read', 'hydra-booking'),
            'Webhook Integrations' => __('Webhook Integrations', 'hydra-booking'),
            'Select Time' => __('Select Time', 'hydra-booking'),
            'Your Previous Booking Time:' => __('Your Previous Booking Time:', 'hydra-booking'),
            'Add New Webhook' => __('Add New Webhook', 'hydra-booking'),
            'Select Webhook' => __('Select Webhook', 'hydra-booking'),
            'Webhook URL' => __('Webhook URL', 'hydra-booking'),
            'Type your Webhook URL' => __('Type your Webhook URL', 'hydra-booking'),
            'Save Webhook' => __('Save Webhook', 'hydra-booking'),
            'Enable this Webhook' => __('Enable this Webhook', 'hydra-booking'),
            'Event Triggers' => __('Event Triggers', 'hydra-booking'),
            'Request Method' => __('Request Method', 'hydra-booking'),
            'Request Format' => __('Request Format', 'hydra-booking'),
            'Request Body' => __('Request Body', 'hydra-booking'),
            'Back' => __('Back', 'hydra-booking'),
            'Request Header' => __('Request Header', 'hydra-booking'),
            'Request Headers' => __('Request Headers', 'hydra-booking'),
            'Header Key' => __('Header Key', 'hydra-booking'),
            'Header Value' => __('Header Value', 'hydra-booking'),
            'Request Fields' => __('Request Fields', 'hydra-booking'),
            'Enter Name' => __('Enter Name', 'hydra-booking'),
            'Enter Value' => __('Enter Value', 'hydra-booking'),

            //Booking
            'Add New Booking' => __('Add New Booking', 'hydra-booking'),
            'Date & Time' => __('Date & Time', 'hydra-booking'),
            'Title' => __('Title', 'hydra-booking'),
            'Host' => __('Host', 'hydra-booking'),
            'Attendee' => __('Attendee', 'hydra-booking'), 
            'Custom Duration' => __('Custom Duration', 'hydra-booking'), 
            'Type Custom Duration' => __('Type Custom Duration', 'hydra-booking'), 
            'Status' => __('Status', 'hydra-booking'),
            'Action' => __('Action', 'hydra-booking'),
            'Meeting Name' => __('Meeting Name', 'hydra-booking'),
            'Attendee Phone' => __('Attendee Phone', 'hydra-booking'),
            'Attendee Email' => __('Attendee Email', 'hydra-booking'),
            'Attendee Address' => __('Attendee Address', 'hydra-booking'),
            'Cancel' => __('Cancel', 'hydra-booking'),
            'Search by Meeting title...' => __('Search by Meeting title...', 'hydra-booking'),
            'E-mail' => __('E-mail', 'hydra-booking'),
            'Notes' => __('Notes', 'hydra-booking'),
            'Meeting' => __('Meeting', 'hydra-booking'),
            'Time' => __('Time', 'hydra-booking'),
            'Date' => __('Date', 'hydra-booking'),
            'Add New Booking' => __('Add New Booking', 'hydra-booking'),
            'Approved' => __('Approved', 'hydra-booking'),
            'Pending' => __('Pending', 'hydra-booking'),
            'Re-schedule' => __('Re-schedule', 'hydra-booking'),
            'Canceled' => __('Canceled', 'hydra-booking'),
            'Customer name' => __('Customer name', 'hydra-booking'),
            'Jhon Deo' => __('Jhon Deo', 'hydra-booking'),
            'Customer email' => __('Customer email', 'hydra-booking'),
            'name@yourmail.com' => __('name@yourmail.com', 'hydra-booking'),
            'Client Time zone' => __('Client Time zone', 'hydra-booking'),
            'Select Meeting' => __('Select Meeting', 'hydra-booking'),
            'Create Booking' => __('Create Booking', 'hydra-booking'),
            'Back to Booking' => __('Back to Booking', 'hydra-booking'),
            'Select Location' => __('Select Location', 'hydra-booking'),
            'Select Team Member' => __('Select Team Member', 'hydra-booking'),
            'Select Date' => __('Select Date', 'hydra-booking'),

            //Booking
            'Data' => __('Data', 'hydra-booking'),
            'Today' => __('Today', 'hydra-booking'),
            'Last 3 months' => __('Last 3 months', 'hydra-booking'),
            'Last 30 Days' => __('Last 30 Days', 'hydra-booking'),
            'Last 7 week' => __('Last 7 week', 'hydra-booking'),
            'To' => __('To', 'hydra-booking'),
            'Apply' => __('Apply', 'hydra-booking'),
            'Total Booking' => __('Total Booking', 'hydra-booking'),
            'VS' => __('VS', 'hydra-booking'),
            'Last' => __('Last', 'hydra-booking'),
            'days' => __('days', 'hydra-booking'),
            'Total Earnings' => __('Total Earnings', 'hydra-booking'),
            'Completed Bookings' => __('Completed Bookings', 'hydra-booking'),
            'Canceled Bookings' => __('Canceled Bookings', 'hydra-booking'),
            'Recent Bookings' => __('Recent Bookings', 'hydra-booking'),
            'Upcoming Meetings' => __('Upcoming Meetings', 'hydra-booking'),
            'Last 7 Days' => __('Last 7 Days', 'hydra-booking'),
            'This month' => __('This month', 'hydra-booking'),
            'This Year' => __('This Year', 'hydra-booking'),
           
        );
     } 

}


