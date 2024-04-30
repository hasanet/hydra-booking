<?php 
namespace HydraBooking\Admin\Controller; 
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class DateTimeController extends \DateTimeZone {
    public function TimeZone() { 
        $time_zone_data = $this->listIdentifiers();
        $time_zone = [];
        // make array in this format { value: 'New York', name: 'NY' }, 
    // { value: 'New York', name: 'NY' }, 
        foreach ($time_zone_data as $key => $value) { 
            $time_zone[] = array(
                'value' => $value,
                'name' => $value
            );
        }
        return $time_zone;
    }
     
    
}


