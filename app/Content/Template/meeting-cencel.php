<?php

defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hydrabooking.com
 * @since      1.0.0
 *
 * @Template Template for Meeting Cencel
 *
 * @package    HydraBooking
 * @subpackage HydraBooking/app
 */
wp_head();

global $wp_query;

$host    = isset( $args['host'] ) ? $args['host'] : array();
$booking = isset( $args['booking_data'] ) ? $args['booking_data'] : array();

?>
<div class=" tfhb-booking-cencel tfhb-meeting-<?php echo esc_attr( $booking->meeting_id ); ?>" data-calendar="<?php echo esc_attr( $booking->meeting_id ); ?>">
	<form method="post" action="" class="tfhb-meeting-cencel-form ajax-submit"  enctype="multipart/form-data">
		<div class="tfhb-meeting-card tfhb-p-16">
			<div class="tfhb-meeting-confirmation">  

				<div class="tfhb-confirmation-seccess"> 
					<h3><?php echo esc_html( __( 'Your meeting has been ', 'hydra-booking' ) ); ?> <?php echo esc_html( $booking->status ); ?></h3>
					<p>Please check your email for more information. Now you can reschedule or cancel booking from here.</p>
				</div>

				<div class="tfhb-meeting-hostinfo"> 
					<ul>
						<li class="tfhb-flexbox tfhb-gap-8">
							<div class="tfhb-icon">
								<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
							</div>
							<?php echo ! empty( $host['first_name'] ) ? '' . esc_html( $host['first_name'] ) . '  ' . esc_html( $host['last_name'] ) . '' : ''; ?>
							<span>Host</span>
						</li>
						<li class="tfhb-flexbox tfhb-gap-8">
							<div class="tfhb-icon">
								<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
							</div>
							<!--date stored in this format  2024-05-24  9:00pm-9:45pm, Saturday, April 25 -->
							<?php

							$meeting_dates = explode( ',', $booking->meeting_dates );

							$date_strings = '';
							foreach ( $meeting_dates as $key => $date ) {

								$date_strings .= gmdate( 'l, F j', strtotime( $date ) );
								$date_strings .= ', ';
							}

								echo ! empty( $booking->start_time ) ? '' . esc_html( $booking->start_time ) . ' - ' . esc_html( $booking->end_time ) . ' ' . esc_html( $date_strings ) . '' : ''
							?>
						</li>
						<li class="tfhb-flexbox tfhb-gap-8">
							<div class="tfhb-icon">
								<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
							</div>
							<!-- Asia/Dhaka  -->
							<?php echo ! empty( $booking->attendee_time_zone ) ? '' . esc_html( $booking->attendee_time_zone ) . '' : ''; ?>

						</li>

						<!-- Meeting location -->
						<?php
						if ( ! empty( $booking->meeting_locations ) ) {
							$locations = json_decode( $booking->meeting_locations );
							foreach ( $locations as $key => $location ) {
								if ( empty( $location->location ) ) {
									continue;
								}
								echo '<li class="tfhb-flexbox tfhb-gap-8">
                                            <div class="tfhb-icon">
                                                <img src="' . esc_url( THB_URL . 'assets/app/images/location.svg' ) . '" alt="location">   
                                            </div> 
                                            ' . esc_html( $location->location ) . '
                                        </li>';
							}
						}
						?>
					</ul>
				</div>
				
				<?php if ( $booking->status == 'cancelled' ) : ?>
					<div class="tfhb-notice notice-error" > 
						<span><?php echo esc_html( 'This meeting has been cancelled by the ' ) . esc_attr($booking->cancelled_by) . '.'; ?></span>
					</div>
				<?php else : ?>
				<div class="hidden-field"> 
					<input type="hidden" id="booking_hash" name="booking_hash" value="<?php echo esc_attr($booking->hash); ?>"> 
				</div> 

				<div class="tfhb-forms">
					<div  class="tfhb-single-form">
						<br>
						<label for="attendee_name"> <?php echo esc_html__( 'Reason for Reschedule', 'hydra-booking' ); ?> </label>
						<br>

						<textarea name="reason" required id="reason"></textarea>
						<br>
						<br>
					</div> 

					<div class="tfhb-confirmation-button tfhb-flexbox tfhb-gap-8">
						<button class="tfhb-flexbox tfhb-gap-8">
							<?php echo esc_attr( 'Back' ); ?>
							<img src="<?php echo esc_url(THB_URL . 'assets/app/images/arrow-right.svg'); ?>" alt="arrow"> 
						</button>
						<button class="tfhb-flexbox tfhb-gap-8">
							<?php echo esc_attr( 'Cancel Booking' ); ?>
							<img src="<?php echo esc_url(THB_URL . 'assets/app/images/arrow-right.svg'); ?>" alt="arrow"> 
						</button>
					</div>

				</div>
				<?php endif; ?>
				
			</div>
		</div>
	</form>
	
</div>
<?php



wp_footer();

