<?php
namespace HydraBooking\Services\Integrations\Woocommerce;

// don't load directly
defined( 'ABSPATH' ) || exit;


use HydraBooking\DB\Booking;
/**
 *
 *
 * Extend WooCommerce Product data
 *
 * @since 1.0.0
 */
class WooBooking {

	public function __construct() {
	}

	public function add_to_cart( $product_id, $data ) {
		$product                                      = wc_get_product( $product_id );
		$order_meta                                   = array();
		$order_meta['tfhb_order_meta']['booking_id']  = $data['booking_id'];
		$order_meta['tfhb_order_meta']['Appointment'] = $data['meeting_dates'] . ' ' . $data['start_time'] . ' - ' . $data['end_time'] . ' ( ' . $data['attendee_time_zone'] . ' )';
		$cart = WC()->cart;
		$cart->add_to_cart( $product_id, 1, 0, array(), $order_meta );

		return true;
	}

	// display booking_id  into checkout page
	public function tfhb_woocommerce_get_item_data( $item_data, $cart_item ) {
		if ( ! empty( $cart_item['tfhb_order_meta']['booking_id'] ) ) {
			$item_data[] = array(
				'key'   => esc_html( __( 'Appointment ', 'hydra-booking' ) ),
				'value' => $cart_item['tfhb_order_meta']['Appointment'],
			);
		}
		return $item_data;
	}

	// update order meta data
	public function tfhb_apartment_custom_order_data( $item, $cart_item_key, $values, $order ) {

		// Assigning data into variables.
		$booking_id  = ! empty( $values['tfhb_order_meta']['booking_id'] ) ? $values['tfhb_order_meta']['booking_id'] : '';
		$appointment = ! empty( $values['tfhb_order_meta']['Appointment'] ) ? $values['tfhb_order_meta']['Appointment'] : '';

		if ( $booking_id ) {
			$item->update_meta_data( 'tfhb_booking_id', $booking_id, true );
		}

		if ( $appointment ) {
			$item->update_meta_data( 'tfhb_appointment', $appointment, true );
		}
	}

	// Add order id to the hotel room meta field
	public function tfhb_add_apartment_data_checkout_order_processed( $order_id, $posted_data, $order ) {

		$order = wc_get_order( $order_id );
		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) {
			if ( ! empty( $item->get_meta( 'tfhb_order_meta' ) ) ) {
				$booking_id  = $item->get_meta( 'tfhb_order_meta' )['booking_id'];
				$appointment = $item->get_meta( 'tfhb_order_meta' )['Appointment'];
				$order->update_meta_data(
					'tfhb_order_meta',
					array(
						'booking_id'  => $booking_id,
						'Appointment' => $appointment,
					)
				);
			}
		}
	}

	public function tfhb_woocommerce_thankyou( $id ) {
		$order = wc_get_order( $id );

		// check if order is not empty
		if ( ! $order ) {
			return;
		}

		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) {

			if ( ! empty( $item->get_meta( 'tfhb_booking_id' ) ) ) {

				$booking_id = $item->get_meta( 'tfhb_booking_id' );
				$booking    = new Booking();
				$updateData = array(
					'id'             => $booking_id,
					'order_id'       => $order->get_id(),
					'attendee_id'    => $order->get_user_id(),
					'payment_status' => $order->get_status(),
				);
				// update booking

				// update booking
				$booking->update( $updateData );

			}
		}
	}


	public function getAllProductList() {
		$args        = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
		);
		$products    = new \WP_Query( $args );
		$productList = array();
		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) {
				$products->the_post();
				$productList[] = array(
					'value' => get_the_ID(),
					'name'  => '[#' . get_the_ID() . '] - ' . get_the_title() . ' - ' . get_post_meta( get_the_ID(), '_price', true ),
				);
			}
		}
		return $productList;
	}
}
