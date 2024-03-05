<?php 
namespace HydraBooking\Admin\Controller; 
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class DateTimeController extends \DateTimeZone {
    public function TimeZone() { 
        $time_zone_data = $this->listIdentifiers();
        $time_zone = [];
        foreach ($time_zone_data as $key => $value) {
            $time_zone[$value] = $value;
        }
        return $time_zone;
    }
     
    
}


