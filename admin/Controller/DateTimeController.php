<?php 
namespace HydraBooking\Admin\Controller; 
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class DateTimeController extends \DateTimeZone {
    public function TimeZone() { 
        $time_zone_data = $this->listIdentifiers();
        $time_zone = [];
        // make array in this format { value: 'New York', name: 'NY' },  
        
        foreach ($time_zone_data as $key => $value) { 
            $time_zone[] = array(
                'value' => $value,
                'name' => $value
            );
        }
        return $time_zone;
    }

    public function convert_time_based_on_timezone($time, $time_zone, $selected_time_zone, $time_format ) { 
        $time = new \DateTime($time, new \DateTimeZone($time_zone));
        $time->setTimezone(new \DateTimeZone($selected_time_zone));
        if ($time_format == '12') {
            return $time->format('h:i A');
        }else{
            return $time->format('H:i');
        } 

        
    }
     
    
}


