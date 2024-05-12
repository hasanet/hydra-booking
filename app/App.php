<?php 
namespace HydraBooking\App;


// Use Classes
use HydraBooking\App\Shortcode\HydraBookingShortcode;

class App {
    public function __construct() {
        $this->init();

    }

    public function init(){ 
        
        // Load Shortcode Class
        new HydraBookingShortcode();

        if(file_exists(THB_PATH . '/app/tfhb-public-class.php')) {
            require_once THB_PATH . '/app/tfhb-public-class.php'; 
        }


    }
}

?>