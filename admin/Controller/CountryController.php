<?php 
namespace HydraBooking\Admin\Controller; 
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class CountryController {
    public $country = array(  );
    public function __construct() { 
        // add_action('admin_init', array($this, 'init')); 
        $this->country = $this->fetch_country();
       
    }

    public function fetch_country(){ 
        $url = 'https://restcountries.com/v3.1/all';
        $response = wp_remote_get($url);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);
        return $data;
    }

    public function country_list(){
        // get form public api  
        $country_list = [];
        foreach ($this->country as $key => $value) {
            $country_list[$value->cca2] = $value->name->common;
        }
        // sort country list
        asort($country_list);
        
        return $country_list;

    }
    
}


