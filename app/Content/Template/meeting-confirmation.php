<?php
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hydrabooking.com
 * @since      1.0.0
 *
 * @Template Template for Meeting Calendar
 *
 * @package    HydraBooking
 * @subpackage HydraBooking/app
 */


$meeting = isset( $args['meeting'] ) ? $args['meeting'] : array();
$booking = isset( $args['booking'] ) ? $args['booking'] : array();
$host    = isset( $args['host'] ) ? $args['host'] : array();


?> 
<div class="tfhb-meeting-confirmation" >
	<?php
		// Hook for before confirmation
		do_action( 'hydra_booking/before_meeting_confirmation' );

	?>
	<div class="tfhb-confirmation-seccess">
		<img src="<?php echo esc_url(THB_URL . 'assets/app/images/sucess.gif'); ?>" alt="Success"> 
		<h3><?php echo esc_html( __( 'Booking', 'hydra-booking' ) ); ?> <?php echo esc_html( $booking['status'] ); ?></h3>
		<!-- <p>Please check your email for more information. Now you can reschedule or cancel booking from here.</p> -->
	</div>

	<div class="tfhb-meeting-hostinfo">
		<h4 class="tfhb-mb-16"><?php echo esc_html($meeting->title); ?></h4>
		<ul>
			<li class="tfhb-flexbox tfhb-gap-8">
				<div class="tfhb-icon">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/user.svg'); ?>" alt="User">
				</div>
				<?php echo ! empty( $host['first_name'] ) ? '' . esc_html( $host['first_name'] ) . '  ' . esc_html( $host['last_name'] ) . '' : ''; ?>
				<span>Host</span>
			</li>
			<?php if ( ! empty( $booking['start_time'] ) ) { ?>
			<li class="tfhb-flexbox tfhb-gap-8">
				<div class="tfhb-icon">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/Meeting.svg'); ?>" alt="User">
				</div>
				<!--date stored in this format  2024-05-24  9:00pm-9:45pm, Saturday, April 25 -->
				<?php

					$meeting_dates = explode( ',', $booking['meeting_dates'] );

					$date_strings = '';
				foreach ( $meeting_dates as $key => $date ) {

					$date_strings .= gmdate( 'l, F j', strtotime( $date ) );
					$date_strings .= ', ';
				}

					echo ! empty( $booking['start_time'] ) ? '' . esc_html( $booking['start_time'] ) . ' - ' . esc_html( $booking['end_time'] ) . ', ' . esc_html( $date_strings ) . '' : ''
				?>
			</li>
			<?php } ?>
			<?php if ( ! empty( $booking['attendee_time_zone'] ) ) { ?>
			<li class="tfhb-flexbox tfhb-gap-8">
				<div class="tfhb-icon">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/globe.svg'); ?>" alt="User">
				</div>
				<!-- Asia/Dhaka  -->
				<?php echo ! empty( $booking['attendee_time_zone'] ) ? '' . esc_html( $booking['attendee_time_zone'] ) . '' : ''; ?>

			</li>
			<?php } ?>
			<!-- Meeting location -->
			<?php
			if ( ! empty( $booking['meeting_locations'] ) ) {
				foreach ( $booking['meeting_locations'] as $key => $location ) {
					echo '<li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="' . esc_url( THB_URL . 'assets/app/images/location.svg' ) . '" alt="location">   
                                </div> 
                                ' . esc_html( $location['location'] ) . '
                            </li>';
				}
			}
			?>
		</ul>
	</div>

	<!-- <div class="tfhb-meeting-confirmation-calender">
		<h3>Add to Calendar</h3>
		<ul class="tfhb-flexbox tfhb-gap-16">
			<li>
				<a href="#">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/g-calendar.svg'); ?>" alt="calendar">
				</a>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/g-calendar.svg'); ?>" alt="calendar">
				</a>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/g-calendar.svg'); ?>" alt="calendar">
				</a>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/g-calendar.svg'); ?>" alt="calendar">
				</a>
			</li>
		</ul>
	</div> -->

	<div class="tfhb-meeting-confirmation-action tfhb-flexbox tfhb-gap-16">
		
		<?php

		if ( true == $meeting->attendee_can_cancel ) {
			$cancel = add_query_arg(
				array(
					'hydra-booking' => 'booking',
					'hash'          => $booking['hash'],
					'meeting-id'    => $booking['meeting_id'],
					'type'          => 'cancel',
				),
				home_url()
			);
			echo '<a href="' . esc_attr( $cancel ) . '">Cancel booking</a>';
		}
		if ( true == $meeting->attendee_can_reschedule ) {

			$reschedule_url = add_query_arg(
				array(
					'hydra-booking' => 'booking',
					'hash'          => $booking['hash'],
					'meeting-id'    => $booking['meeting_id'],
					'type'          => 'reschedule',
				),
				home_url()
			);

			echo '<a href="' . esc_url( $reschedule_url ) . '">Reschedule</a>';
		}
		?>
	</div>

	<?php
		// Hook for After confirmation
		do_action( 'hydra_booking/after_meeting_confirmation' );

	?>

</div>
