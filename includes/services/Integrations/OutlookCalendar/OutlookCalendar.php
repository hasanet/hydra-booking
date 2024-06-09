<?php

namespace HydraBooking\Services\Integrations\OutlookCalendar;
use HydraBooking\DB\Booking;
class OutlookCalendar{

    public $clientId;
    public $clientSecret;
    public $redirectUrl;

    private $accessToken;

    public $revokeUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout';
    public $tokenUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
    private $refreshTokenUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
    public $authUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';
 
    public $calendarEvent = 'https://graph.microsoft.com/v1.0/me/calendars/';

    public $authScope = 'Calendars.ReadWrite offline_access openid';


 
    public function __construct( ) { 

        $this->setClientData();  

        add_action('hydra_booking/after_booking_completed', array($this, 'InsertOutLookCalender'));
        add_action('hydra_booking/after_booking_schedule', array($this, 'InsertOutLookCalender'));
    }

    // Set Client Data
    public function setClientData(){
        // Get the Google Calendar Data
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings'); 
        $outlook_calendar = isset($_tfhb_integration_settings['outlook_calendar']) ? $_tfhb_integration_settings['outlook_calendar'] : array();

        // Set the Client Data
        $this->clientId = isset($outlook_calendar['client_id']) ? $outlook_calendar['client_id'] : '';
        $this->clientSecret = isset($outlook_calendar['secret_key']) ? $outlook_calendar['secret_key'] : '';
        $this->redirectUrl =  isset($outlook_calendar['redirect_url']) ? $outlook_calendar['redirect_url'] : '';
    }
    // Set Access Token
    public function setAccessToken($host_id){

        $_tfhb_host_integration_settings =  is_array(get_user_meta($host_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($host_id, '_tfhb_host_integration_settings', true) : array();
        $accessToken = isset($_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar']['access_token']) ? $_tfhb_host_integration_settings['google_calendar']['tfhb_google_calendar']['access_token'] : '';


        $this->accessToken = $accessToken;
    }

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/integration/outlook-api', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetAccessData'), 
        ));  
        
        
    }

    public function GetAccessData(){


        // Set the Client Data 
        if(isset($_GET['code']) && isset($_GET['state'])) {
       
			try { 

            
				
                $host_id = $_GET['state'];
            
				$data = $this->GetAccessToken( $_GET['code']); 
                $calendarData = $this->getCalendarItemListIdToken($data['access_token']);

                // remove the Id Token
                unset($data['id_token']);
                $data['access_token'] = ''.$data['access_token'].'';

                // merge the data
                $data = array_merge($data, $calendarData);

                $_tfhb_host_integration_settings =  is_array(get_user_meta($host_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($host_id, '_tfhb_host_integration_settings', true) : array();

                $_tfhb_host_integration_settings['outlook_calendar']['tfhb_outlook_calendar'] = $data;

                
               
                // save to user metadata
                update_user_meta($host_id, '_tfhb_host_integration_settings', $_tfhb_host_integration_settings, true);
 

                $redirect_url = get_site_url() . '/wp-admin/admin.php?page=hydra-booking#/hosts/profile/' . $host_id . '/integrations';
                
                wp_redirect($redirect_url);
                wp_die();
				 
			}

			catch(Exception $e) {
				echo $e->getMessage();
				exit(); 
			}
		}
    }

    
    // if Access token is experired
    public function refreshToken($host_id){
        $_tfhb_host_integration_settings =  is_array(get_user_meta($host_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($host_id, '_tfhb_host_integration_settings', true) : array();
        $refreshToken =  isset($_tfhb_host_integration_settings['outlook_calendar']['tfhb_outlook_calendar']['refresh_token']) ? $_tfhb_host_integration_settings['outlook_calendar']['tfhb_outlook_calendar']['refresh_token'] : '';
      

        $url = $this->refreshTokenUrl;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;
        $post_fields = array(
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token'
        ); 
        // use Wp Remote Request
        $response = wp_remote_post($url, array(
            'body' => $post_fields
        ));
        $body = wp_remote_retrieve_body($response); 
        $body = json_decode($body, true);
 
        if($body['access_token']){

            $this->accessToken = $body['access_token'];
        }
        else{
            $this->setAccessToken($host_id);
        } 
        return $body;
    }

    public function GetAccessToken( $code){
        $url = $this->tokenUrl;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;
        $redirectUrl = $this->redirectUrl; 
      
        $post_fields = array(
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $redirectUrl,
        ); 
        // use Wp Remote Request
        $response = wp_remote_post($url, array(
            'body' => $post_fields
        ));

        
        $body = wp_remote_retrieve_body($response);
 
        return json_decode($body, true);
    }

    public function GetAccessTokenUrl($host_id){  
    
        return $this->authUrl . '?client_id='.$this->clientId.'&response_type=code&redirect_uri='. $this->redirectUrl .'&response_mode=query&scope=Calendars.ReadWrite%20offline_access%20openid&state='.$host_id.'&prompt=consent';
    }

 
    /**
     * Get the email by id token
     * @param $token
     * @return mixed
     */
    public function getEmailByIdToken($id_token){
        $tokenParts = explode(".", $id_token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload, true);
        return $jwtPayload['email'];

    }

    /**
     * Get the calendar item list id token
     * @param $id_token
     * @return mixed
     */
    public function getCalendarItemListIdToken($access_token){
        $url = 'https://graph.microsoft.com/v1.0/me/calendars';

        $response = wp_remote_get($url, array(
            'headers' => array( 
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type'  => 'application/json'
            )
        ));

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        // Get Email form $data[@odata.context] value like that 'https://graph.microsoft.com/v1.0/$metadata#users('sydurrahmant1%40outlook.com')/calendars'

        $email = explode('users(', $data['@odata.context']);
        $email = explode(')', $email[1]);
        $email = $email[0];

        $items = array();
        foreach($data['value'] as $item){ 
            $items[] = array(
                'id' => $item['id'],
                'title' => $item['name'],
                'write_status' => $item['canEdit']
            );
        }

        $calendarData = array(
            'email' => $email,
            'items' => $items
        ); 

        return $calendarData;

        
    }
 

    // Insert Booking to Outlook Calendar
    public function InsertOutlookCalender( $data ){ 

        if(!isset($data->id)){
            return;
        }
   
        //  set event data google meet shedule 
        $start_time = strtotime($data->start_time); // 03:45 AM
        $end_time = strtotime($data->end_time); // 04:30 AM
        $meeting_dates = $data->meeting_dates; // 2024-06-12
        // 2024-06-02T10:00:00 
        $start_date = date('Y-m-d', strtotime($meeting_dates)) . 'T' . date('H:i:s', $start_time);
        $end_date = date('Y-m-d', strtotime($meeting_dates)) . 'T' . date('H:i:s', $end_time);

      
 
        $setData = array(
           'subject' => 'Meeting with ' . $data->attendee_name,
            'body' => array(
                'contentType' => 'HTML',
                'content' =>  'Title: ' . $data->meeting_title,
            ),
            'start' => array(
                'dateTime' => $start_date,
                'timeZone' => $data->attendee_time_zone,
            ),
            'end' => array(
                'dateTime' =>  $end_date,
                'timeZone' => $data->attendee_time_zone,
            ),
            'location' => array(
                'displayName' => 'Location: ' . $data->meeting_location,
            ),
            'attendees' => array(
                array('emailAddress' => array('address' => $data->email)),
                array('emailAddress' => array('address' => $data->host_email)),
            ), 
        );  

 

        // Set the Access Token
        $this->refreshToken($data->host_id);
 
 
        $_tfhb_host_integration_settings =  is_array(get_user_meta($data->host_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($data->host_id, '_tfhb_host_integration_settings', true) : array();
        $outlook_calendar = isset($_tfhb_host_integration_settings['outlook_calendar']) ? $_tfhb_host_integration_settings['outlook_calendar'] : array();
        $calendarId = isset($outlook_calendar['selected_calendar_id']) ? $outlook_calendar['selected_calendar_id'] : '';

        
       
        if($calendarId){
            $meeting_calendar = json_decode($data->meeting_calendar, true);
            // $meeting_calendar = '';
            
            if($meeting_calendar != '' && isset($meeting_calendar['outlook_calendar']['id'])){ 
                $url =  $this->calendarEvent . $calendarId . '/events/' . $meeting_calendar['outlook_calendar']['id'];
                $response = wp_remote_post($url, array(
                    'headers' => array( 'Authorization' => 'Bearer ' . $this->accessToken),
                    'body' => json_encode($setData),
                    'method' => 'PUT',
                    'data_format' => 'body',
                )); 
                
            }else{
                $url =   $this->calendarEvent . $calendarId . '/events';
               

                $response = wp_remote_post($url, array(
                    'headers' => array( 
                        'Authorization' => 'Bearer ' . $this->accessToken,
                        'Content-Type' => 'application/json'
                    ),
                    'body' => json_encode($setData), 
                )); 

    

            }

        
            
            $body = wp_remote_retrieve_body($response);   

            // Update the Booking
            $google_calendar_data['outlook_calendar'] = json_decode($body, true);
            $update = array();
            $update['id'] = $data->id;
            $update['meeting_calendar'] = json_encode($google_calendar_data, true);
    
            $booking = new Booking();
    
            $booking->update($update);  
        }
       
    }

}