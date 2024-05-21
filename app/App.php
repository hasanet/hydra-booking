<?php 
namespace HydraBooking\App;


// Use Classes
use HydraBooking\App\Shortcode\HydraBookingShortcode;
use HydraBooking\Services\Integrations\Woocommerce\WooBooking;

class App {
    public function __construct() {
        $this->init();

        

    }

    public function init(){ 
        
        // Load Shortcode Class
        new HydraBookingShortcode();

        //  display booking_id  into checkout page
        add_action('woocommerce_get_item_data', array(new WooBooking(), 'tfhb_woocommerce_get_item_data') , 10, 2);

        // Show custom data in order details.
        add_action( 'woocommerce_checkout_create_order_line_item', array(new WooBooking(), 'tfhb_apartment_custom_order_data'), 10, 4 );

        //  add booking_id to order meta
        add_action( 'woocommerce_checkout_order_processed', array(new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed'), 10, 4 );
        
        add_action( 'woocommerce_store_api_checkout_order_processed', array(new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed_block_checkout') );

        
 

        if(file_exists(THB_PATH . '/app/tfhb-public-class.php')) {
            require_once THB_PATH . '/app/tfhb-public-class.php'; 
        }



    }

}

?>