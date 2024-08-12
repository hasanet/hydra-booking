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

	public function init() {

		// Load Shortcode Class
		new HydraBookingShortcode();

		// display booking_id  into checkout page
		add_action( 'woocommerce_get_item_data', array( new WooBooking(), 'tfhb_woocommerce_get_item_data' ), 10, 2 );

		// Show custom data in order details.
		add_action( 'woocommerce_checkout_create_order_line_item', array( new WooBooking(), 'tfhb_apartment_custom_order_data' ), 10, 4 );

		// add booking_id to order meta
		add_action( 'woocommerce_checkout_order_processed', array( new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed' ), 10, 4 );

		// add_action( 'woocommerce_store_api_checkout_order_processed', array(new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed_block_checkout') );
		add_action( 'woocommerce_thankyou', array( new WooBooking(), 'tfhb_woocommerce_thankyou' ) );

		add_action( 'woocommerce_store_api_checkout_order_processed', array( new WooBooking(), 'tfhb_add_apartment_data_checkout_order_processed_block_checkout' ) );

		add_filter( 'query_vars', array( $this, 'tfhb_single_query_vars' ) );

		add_filter( 'template_include', array( $this, 'tfhb_single_page_template' ) );

		// add booking_id to order meta
		if ( file_exists( THB_PATH . '/app/tfhb-public-class.php' ) ) {
			require_once THB_PATH . '/app/tfhb-public-class.php';
		}

		add_rewrite_rule(
			'^meeting/([0-9]+)/?$',
			'index.php?hydra-booking=meeting&meeting-id=$matches[1]&type=$matches[2]',
			'top'
		);
		// Create Rewrite Rule for Reschedule hydra-booking.local/?hydra-booking=booking&hash=2121&type=reschedule
		add_rewrite_rule(
			'^booking/([0-9]+)/?$',
			'index.php?hydra-booking=booking&hash=$matches[1]&meeting-id=$matches[2]&type=$matches[3]',
			'top'
		);

		add_action( 'pre_get_posts', array( $this, 'tfhb_remove_posttype_request' ) );
		add_filter( 'single_template', array( $this, 'tfhb_single_meeting_template' ) );

		add_action( 'hydra_booking/stripe_payment_method', array( $this, 'tfhb_stripe_payment_callback' ), 10, 2 );
		add_action( 'hydra_booking/paypal_payment_method', array( $this, 'tfhb_paypal_payment_callback' ), 10, 2 );
	}

	public function tfhb_single_meeting_template( $single_template ) {
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

	public function tfhb_remove_posttype_request( $query ) {
		// Only noop the main query.
		if ( ! $query->is_main_query() ) {
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

		if ( get_query_var( 'hydra-booking' ) === 'meeting' && get_query_var( 'meeting' ) ) {
			// echo "hello";
			// exit;
			$custom_template = load_template( THB_PATH . '/app/Content/Template/single-meeting.php', false );
			return $custom_template;
		}
		if ( get_query_var( 'hydra-booking' ) === 'meeting' && get_query_var( 'meeting-id' ) && get_query_var( 'type' ) ) {

			$custom_template = load_template( THB_PATH . '/app/Content/Template/Iframe.php', true );
			return $custom_template;
		}

		// Reschedule Page
		if ( get_query_var( 'hydra-booking' ) === 'booking' && get_query_var( 'hash' ) && get_query_var( 'type' ) === 'reschedule' ) {
			$custom_template = load_template( THB_PATH . '/app/Content/Template/reschedule.php', false );
			return $custom_template;

		}

		// Cenceled Page
		if ( get_query_var( 'hydra-booking' ) === 'booking' && get_query_var( 'hash' ) && get_query_var( 'type' ) === 'cancel' ) {
			if ( ! wp_script_is( 'tfhb-app-script', 'enqueued' ) ) {
				wp_enqueue_script( 'tfhb-app-script' );
			}

			$booking     = new Booking();
			$get_booking = $booking->get(
				array( 'hash' => get_query_var( 'hash' ) ),
				false,
				true
			);
			if ( ! $get_booking ) {
				return $template;
			}
			$host_meta       = get_user_meta( $get_booking->host_id, '_tfhb_host', true );
			$custom_template = load_template(
				THB_PATH . '/app/Content/Template/meeting-cencel.php',
				false,
				array(
					'host'         => $host_meta,
					'booking_data' => $get_booking,
				)
			);
			return $custom_template;

		}
		return $template;
	}

	// Stripe Callback
	public function tfhb_stripe_payment_callback( $data, $booking_id ) {

		if ( file_exists( THB_PATH . '/app/integration/stripe/vendor/autoload.php' ) ) {
			require_once THB_PATH . '/app/integration/stripe/vendor/autoload.php';
		}

		$_tfhb_integration_settings = get_option( '_tfhb_integration_settings' );
		$stripeSecret               = ! empty( $_tfhb_integration_settings['stripe']['secret_key'] ) ? $_tfhb_integration_settings['stripe']['secret_key'] : '';

		$_tfhb_host_integration_settings = get_user_meta( $data['host_id'], '_tfhb_host_integration_settings' );

		$stripeSecret = ! empty( $_tfhb_host_integration_settings['stripe']['secret_key'] ) ? $_tfhb_host_integration_settings['stripe']['secret_key'] : $stripeSecret;

		if ( ! empty( $stripeSecret ) ) {
			try {

				\Stripe\Stripe::setVerifySslCerts( false );

				// See your keys here: https://dashboard.stripe.com/account/apikeys
				\Stripe\Stripe::setApiKey( $stripeSecret );

				// Get the payment token ID submitted by the form:
				$token  = ! empty( $data['tokenId'] ) ? $data['tokenId'] : '';
				$amount = ! empty( $data['price'] ) ? $data['price'] : 0;

				$customer = \Stripe\Customer::create(
					array(
						'email'   => ! empty( $data['email'] ) ? $data['email'] : '',
						'source'  => $token,
						'address' => array(
							'city'        => 'Dhaka',
							'country'     => ! empty( $data['address'] ) ? $data['address'] : '',
							'line1'       => 'dhaka',
							'line2'       => '',
							'postal_code' => '1000',
							'state'       => 'dhaka',
						),
					)
				);

				$charge = \Stripe\Charge::create(
					array(
						'customer'    => $customer->id,
						'amount'      => $amount * 100,
						'currency'    => $data['currency'],
						'description' => 'test stipe payment',
					)
				);

				if ( $charge->balance_transaction ) {
					$booking = new Booking();
					$data    = array(
						'id'             => $booking_id,
						'payment_status' => 'Completed',
					);
					// Booking Update
					$bookingUpdate = $booking->update( $data );

					// Data for Transactions Table
					$tdata        = array(
						'booking_id'         => $booking_id,
						'transation_history' => wp_json_encode( $charge ),
					);
					$Transactions = new Transactions();
					$Transactions = $Transactions->add( $tdata );
				}

				// $data = array('success' => true, 'data' => $charge);

				// echo wp_json_encode($data);
			} catch ( \Throwable $th ) {
				$data = array(
					'success' => false,
					'data'    => $th->getMessage(),
				);
				echo wp_json_encode( $data );
			}
		}
	}

	// Paypal Callback
	public function tfhb_paypal_payment_callback( $data, $booking_id ) {

		if ( ! empty( $data['paymentID'] ) && ! empty( $data['paymentToken'] ) && ! empty( $data['payerID'] ) ) {
			$booking     = new Booking();
			$bookingdata = array(
				'id'             => $booking_id,
				'payment_status' => 'Completed',
			);
			// Booking Update
			$bookingUpdate = $booking->update( $bookingdata );

			$charge = array(
				'paymentID'    => ! empty( $data['paymentID'] ) ? $data['paymentID'] : '',
				'paymentToken' => ! empty( $data['paymentToken'] ) ? $data['paymentToken'] : '',
				'payerID'      => ! empty( $data['payerID'] ) ? $data['payerID'] : '',
			);
			// Data for Transactions Table
			$tdata        = array(
				'booking_id'         => $booking_id,
				'transation_history' => wp_json_encode( $charge ),
			);
			$Transactions = new Transactions();
			$Transactions = $Transactions->add( $tdata );
		}

		// $_tfhb_integration_settings = get_option('_tfhb_integration_settings');
		// if($_tfhb_integration_settings['paypal']){
		// $client_id = $_tfhb_integration_settings['paypal']['client_id'];
		// $client_secret = $_tfhb_integration_settings['paypal']['secret_key'];

		// $ch = curl_init();

		// curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
		// curl_setopt($ch, CURLOPT_HEADER, false);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

		// $response = curl_exec($ch);
		// if (empty($response)) die("Error: No response.");
		// $jsonResponse = json_decode($response);
		// curl_close($ch);

		// var_dump($jsonResponse); exit();
		// $accessToken = $jsonResponse->access_token;

		// }
	}
}
