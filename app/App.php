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
         
        // add_action( 'woocommerce_store_api_checkout_order_processed', array(new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed_block_checkout') );
        add_action( 'woocommerce_thankyou', array(new WooBooking(), 'tfhb_woocommerce_thankyou') );
 
        add_action( 'woocommerce_store_api_checkout_order_processed', array(new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed_block_checkout') );

        add_filter( 'query_vars', array($this, 'tfhb_single_query_vars'));

        add_filter( 'template_include', array($this, 'tfhb_single_page_template')); 

        // add booking_id to order meta
        if(file_exists(THB_PATH . '/app/tfhb-public-class.php')) {
            require_once THB_PATH . '/app/tfhb-public-class.php'; 
        }

        add_rewrite_rule(
            '^meeting/([0-9]+)/?$',
            'index.php?hydra-booking=meeting&meeting=$matches[1]',
            'top'
        );
    }

    public function tfhb_single_query_vars( $query_vars ) {
        $query_vars[] = 'hydra-booking';
        $query_vars[] = 'meeting';
        return $query_vars;
    }

    public function tfhb_single_page_template( $template ) {
        if (get_query_var('hydra-booking') === 'meeting' && get_query_var('meeting')) {
            $custom_template = load_template(THB_PATH . '/app/Content/Template/single-meeting.php', false);
            return $custom_template;
        }
        return $template;
    }
}

?>