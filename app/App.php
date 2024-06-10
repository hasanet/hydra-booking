<?php 
namespace HydraBooking\App;


// Use Classes
use HydraBooking\App\Shortcode\HydraBookingShortcode;
use HydraBooking\Services\Integrations\Woocommerce\WooBooking; 
use HydraBooking\DB\Booking;
use HydraBooking\DB\Transactions;

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
        // Create Rewrite Rule for Reschedule hydra-booking.local/?hydra-booking=booking&hash=2121&type=reschedule
        add_rewrite_rule(
            '^booking/([0-9]+)/?$',
            'index.php?hydra-booking=booking&hash=$matches[1]&meeting-id=$matches[2]&type=$matches[3]',
            'top'
        );

        add_action( 'pre_get_posts', array($this, 'tfhb_remove_posttype_request' ));
        add_filter( 'single_template', array($this, 'tfhb_single_meeting_template' ));

        add_action( 'hydra_booking/stripe_payment_method', array($this, 'tfhb_stripe_payment_callback'), 10, 2 );
    }

    public function tfhb_single_meeting_template($single_template){
        global $post;

		/**
		 * Single Meeting
		 *
		 * single-meeting.php
		 */
		if ( 'tfhb_meeting' === $post->post_type ) {
			return THB_PATH . '/app/Content/Template/single-meeting.php';
		}

        return $single_template;
    }

    public function tfhb_remove_posttype_request($query){
        // Only noop the main query.
        if ( ! $query->is_main_query() ){
        return;
        }

        // Only noop our very specific rewrite rule match.
        if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
        }

        // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match.
        if ( ! empty( $query->query['name'] ) ) {
            $post_types = array(
            'post', // important to  not break your standard posts
            'page', // important to  not break your standard pages
            'tfhb_meeting',
        );

        $query->set( 'post_type', $post_types );

        }
    }

    public function tfhb_single_query_vars( $query_vars ) {
        $query_vars[] = 'hydra-booking';
        $query_vars[] = 'meeting';
        $query_vars[] = 'meeting-id';
        $query_vars[] = 'hash';
        $query_vars[] = 'type';
        return $query_vars;
    }

    public function tfhb_single_page_template( $template ) {
      
        if (get_query_var('hydra-booking') === 'meeting' && get_query_var('meeting')) {
            $custom_template = load_template(THB_PATH . '/app/Content/Template/single-meeting.php', false);
            return $custom_template;
        }

        // Reschedule Page
        if (get_query_var('hydra-booking') === 'booking' && get_query_var('hash') && get_query_var('type') === 'reschedule') { 
            $custom_template = load_template(THB_PATH . '/app/Content/Template/reschedule.php', false);
            return $custom_template;

        }

        // Cenceled Page
        if (get_query_var('hydra-booking') === 'booking' && get_query_var('hash') && get_query_var('type') === 'cancel') { 
            if(!wp_script_is('tfhb-app-script', 'enqueued')) {
                wp_enqueue_script('tfhb-app-script');
            } 
    
            $booking = new Booking();
            $get_booking = $booking->get(
                array('hash' =>  get_query_var('hash')),
                false,
                true 
            );  
            if(!$get_booking){
                return $template;
            }
            $host_meta = get_user_meta($get_booking->host_id, '_tfhb_host', true);
            $custom_template = load_template(THB_PATH . '/app/Content/Template/meeting-cencel.php', false, [ 
                'host' => $host_meta,  
                'booking_data' => $get_booking, 
            ]);
            return $custom_template;

        }
        return $template;
    }

    public function tfhb_stripe_payment_callback($data, $booking_id){

        if(file_exists(THB_PATH . '/app/integration/stripe/vendor/autoload.php')) {
            require_once THB_PATH . '/app/integration/stripe/vendor/autoload.php'; 
        }

        $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
        $stripeSecret = !empty($_tfhb_integration_settings['stripe']['secret_key']) ? $_tfhb_integration_settings['stripe']['secret_key'] : '';

        $_tfhb_host_integration_settings = get_user_meta($data['host_id'], '_tfhb_host_integration_settings');

        $stripeSecret = !empty($_tfhb_host_integration_settings['stripe']['public_key']) ? $_tfhb_host_integration_settings['stripe']['public_key'] : $stripeSecret;

        if(!empty($stripeSecret)){
        try {

            \Stripe\Stripe::setVerifySslCerts(false);
      
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey($stripeSecret);
      
            // Get the payment token ID submitted by the form:
            $token = !empty($data['tokenId']) ? $data['tokenId'] : '';
            $amount = !empty($data['price']) ? $data['price'] : 0;

            $customer = \Stripe\Customer::create(array(
              'email' => !empty($data['email']) ? $data['email'] : '',
              'source'  => $token,
              "address" => [
                "city" => "Dhaka",
                "country" => !empty($data['address']) ? $data['address'] : '',
                "line1" => "dhaka",
                "line2" => "",
                "postal_code" => "1000",
                "state" => "dhaka"
              ]
            ));
      
            $charge = \Stripe\Charge::create(array(
              'customer' => $customer->id,
              'amount'   => $amount * 100,
              'currency' => "usd",
              'description' => "test stipe payment",
            ));

            if($charge->balance_transaction){
                $booking = new Booking();
                $data = [ 
                    'id' => $booking_id,
                    'payment_status' => 'Completed'
                ];
                // Booking Update
                $bookingUpdate = $booking->update($data);

                // Data for Transactions Table
                $tdata = [ 
                    'booking_id' => $booking_id,
                    'transation_history' => wp_json_encode($charge)
                ];
                $Transactions =  new Transactions();
                $Transactions = $Transactions->add($tdata);
            }

            // $data = array('success' => true, 'data' => $charge);
      
            // echo wp_json_encode($data);
        } catch (\Throwable $th) {
            $data = array('success' => false, 'data' => $th->getMessage());
            echo wp_json_encode($data);
        }
    }
    }
}

?>