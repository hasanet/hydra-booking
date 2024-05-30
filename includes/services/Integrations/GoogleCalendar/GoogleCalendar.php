<?php

namespace HydraBooking\Services\Integrations\GoogleCalendar;

class GoogleCalendar{

    public $clientId;
    public $clientSecret;
    public $redirectUrl;

    private $accessToken;

    public $revokeUrl = 'https://oauth2.googleapis.com/revoke';
    public $tokenUrl = 'https://oauth2.googleapis.com/token';
    private $refreshTokenUrl = 'https://www.googleapis.com/oauth2/v3/token';
    public $authUrl = 'https://accounts.google.com/o/oauth2/auth';
 
    public $calendarEvent = 'https://www.googleapis.com/calendar/v3/calendars/primary/events/';

    public $authScope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/calendar.events';


 
    public function __construct( ) { 

        $this->setClientData();  
    }

    // Set Client Data
    public function setClientData(){
        // Get the Google Calendar Data
        $_tfhb_integration_settings = get_option('_tfhb_integration_settings'); 
        $google_calendar = isset($_tfhb_integration_settings['google_calendar']) ? $_tfhb_integration_settings['google_calendar'] : array();

        // Set the Client Data
        $this->clientId = isset($google_calendar['client_id']) ? $google_calendar['client_id'] : '';
        $this->clientSecret = isset($google_calendar['client_secret']) ? $google_calendar['client_secret'] : '';
        $this->redirectUrl =  isset($google_calendar['redirect_url']) ? $google_calendar['redirect_url'] : '';
    }
    

    public function create_endpoint(){
        register_rest_route('hydra-booking/v1', '/integration/google-api', array(
            'methods' => 'GET',
            'callback' => array($this, 'GetAccessData'),
            // 'permission_callback' =>  array(new RouteController() , 'permission_callback'),
        ));  
        
        
    }

    public function GetAccessData(){

        if(isset($_GET['code']) && isset($_GET['state'])) {
			try { 
				
                $host_id = $_GET['state'];
				// Get the access token 
				$data = $this->GetAccessToken( $_GET['code']);
                $data = json_decode($data, true);
                $email = $this->getEmailByIdToken($data['id_token']);

                // Get all calendar in the account 
                $url = 'https://www.googleapis.com/calendar/v3/users/me/calendarList';
                $response = wp_remote_get($url, array( 'headers' => array('Authorization' => 'Bearer ' . $data['access_token'])));
                $body = wp_remote_retrieve_body($response);
                $body = json_decode($body, true);

                $data['email'] = $email;

                foreach($body['items'] as $calendar){ 
                    $data['items'][] = array(
                        'id' => $calendar['id'],
                        'title' => $calendar['summary'],
                        'write_status' => 0,
                    );

                }

                // remove the Id Token
                unset($data['id_token']);

                $_tfhb_host_integration_settings =  is_array(get_user_meta($host_id, '_tfhb_host_integration_settings', true)) ? get_user_meta($host_id, '_tfhb_host_integration_settings', true) : array();

                 $_tfhb_host_integration_settings['tfhb_google_calendar'] = $data;

                // save to user metadata
                update_user_meta($host_id, '_tfhb_google_calendar', $_tfhb_host_integration_settings);
 
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


    public function GetAccessToken( $code){
        $url = $this->tokenUrl;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;
        $redirectUrl = $this->redirectUrl;
        $post_fields = array(
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUrl,
            'grant_type' => 'authorization_code'
        ); 
        // use Wp Remote Request
        $response = wp_remote_post($url, array(
            'body' => $post_fields
        ));
        $body = wp_remote_retrieve_body($response);

        return $body;
        exit;

        return json_decode($body, true);
    }

    public function GetAccessTokenUrl($host_id){  
        return $this->authUrl . '?client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUrl . '&scope=' . $this->authScope . '&response_type=code&access_type=offline&prompt=consent&state=' . $host_id;  
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


    public function generateAuthCode($code)
    {
        $body = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUrl,
            'grant_type'    => 'authorization_code',
            'code'          => $code
        ];

        $type = 'GET';
        $url = $this->tokenUrl;
        $headers = [
            'Content-Type'              => 'application/http',
            'Content-Transfer-Encoding' => 'binary',
            'MIME-Version'              => '1.0',
        ];

        $args = [
            'headers' => $headers,
            'method'  => $type,
            'timeout' => 20
        ];

        if ($body) {
            
            $url = add_query_arg($body, $url);
        }

        $request = wp_remote_request($url, $args);
 
    }

}