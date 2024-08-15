<?php
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hydrabooking.com
 * @since      1.0.0
 *
 * @Template Template for Form
 *
 * @package    HydraBooking
 * @subpackage HydraBooking/app
 */


$meeting             = isset( $args['meeting'] ) ? $args['meeting'] : array();
$questions           = isset( $meeting['questions'] ) ? $meeting['questions'] : array();
$questions_type      = isset( $meeting['questions_type'] ) ? $meeting['questions_type'] : 'custom';
$questions_form_type = isset( $meeting['questions_form_type'] ) ? $meeting['questions_form_type'] : '';
$questions_form      = isset( $meeting['questions_form'] ) ? $meeting['questions_form'] : '';
$booking_data        = isset( $args['booking_data'] ) ? $args['booking_data'] : array();



?> 
<div class="tfhb-meeting-booking-form" style="display:none">
	<?php
		// Hook for Before Form
		do_action( 'hydra_booking/before_meeting_form' );

	?>

	<div class="tfhb-back-btn tfhb-flexbox tfhb-gap-8">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M9.99935 15.8334L4.16602 10L9.99935 4.16669" stroke="#F62881" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		<path d="M15.8327 10H4.16602" stroke="#F62881" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		<h3><?php echo esc_html__( 'Details', 'hydra-booking' ); ?></h3>
	</div>
	<div class="tfhb-notice notice-error" style="display:none;"> 
	</div>

	<div class="tfhb-forms tfhb-flexbox">
		
		<?php

		if ( $questions_type != 'custom' ) {
			if ( $questions_form_type == 'wpcf7' ) {
				echo do_shortcode( '[contact-form-7 id="' . $questions_form . '"]' );

			} elseif ( $questions_form_type == 'fluent-forms' ) {
					echo do_shortcode( '[fluentform id="' . $questions_form . '"]' );
			} elseif ( $questions_form_type == 'forminator' ) {
				echo do_shortcode( '[forminator_form id="' . $questions_form . '"]' );
			} elseif ( $questions_form_type == 'forminator' ) {
					echo do_shortcode( '[forminator_form id="' . $questions_form . '"]' );
			}
					// elseif($questions_form_type == 'gravityforms'){
					// echo do_shortcode('[gravityform id="'.$questions_form.'" title="false" description="false" ajax="true"]');

					// }

		} else {
			echo '<form  method="post" action="" class="tfhb-meeting-form ajax-submit"  enctype="multipart/form-data">';
			if ( is_array( $questions ) && ! empty( $questions ) ) {
				$disable = ! empty( $booking_data ) ? 'disabled' : '';

				foreach ( $questions as $key => $question ) :
					$name = 2 >= $key ? $question['label'] : 'question[' . $question['label'] . ']';
					// $value = !empty($booking_data) ? $booking_data->data[$question['label']] : '';

					if ( $name == 'email' ) {
						$value = ! empty( $booking_data ) ? $booking_data->email : '';
					} elseif ( $name == 'name' ) {
						$value = ! empty( $booking_data ) ? $booking_data->attendee_name : '';
					} elseif ( $name == 'address' ) {
						$value = ! empty( $booking_data ) ? $booking_data->address : '';
					} else {
						$value = '';
					}
					if ( empty( $question['type'] ) ) {
						continue;
					}

					$required_star = $question['required'] == 1 ? '*' : '';
					$required      = $question['required'] == 1 ? 'required' : '';

					echo '<div class="tfhb-single-form">
                                <label for="' . esc_attr($name) . '">' . esc_attr($question['placeholder']) . ' ' . esc_attr($required_star) . '</label>';
					if ( $question['type'] == 'select' ) {

						echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" ' . esc_attr($disable) . ' ' . esc_attr($required) . '>';
						foreach ( $question['options'] as $option ) {
							echo '<option value="' . esc_attr($option['value']) . '">' . esc_attr($option['label']) . '</option>';
						}
						echo '</select>';

					} elseif ( $question['type'] == 'textarea' ) {

						echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" ' . esc_attr($disable) . ' ' . esc_attr($required) . '>' . esc_html($value) . '</textarea>';

					} elseif ( $question['type'] == 'checkbox' ) {

						echo '<label for="' . esc_attr($name) . '">
                                            <input name="' . esc_attr($name) . '" id="' . esc_attr($name) . '"  type="' . esc_attr($question['type']) . '" ' . esc_attr($disable) . ' ' . esc_attr($required) . '>
                                            <span class="checkmark"></span> ' . esc_attr($question['placeholder']) . '
                                        </label>';

					} else {

						echo '<input name="' . esc_attr($name) . '" id="' . esc_attr($name) . '"  value="' . esc_attr($value) . '" type="' . esc_attr($question['type']) . '" ' . esc_attr($required) . ' ' . esc_attr($disable) . ' placeholder="' . esc_attr($question['placeholder']) . '">';
					}
							echo '</div>';

					endforeach;
			}



			?>
			
			<?php if ( ! empty( $booking_data ) ) : ?>
				
				<div class="tfhb-forms">
					<div  class="tfhb-single-form">
						<label for="attendee_name"> <?php echo esc_html__( 'Reason for Reschedule', 'hydra-booking' ); ?> </label>
						<br>

						<textarea name="reason" required id="reason"></textarea>
					</div> 
				</div>
			<?php else : ?>
				<div class="tfhb-confirmation-box tfhb-flexbox">
					<div class="tfhb-swicher-wrap tfhb-flexbox tfhb-gap-8">
						<label class="switch">
							<input required name="tfhb_booking_checkbox" type="checkbox">
							<div class="slider"></div>
						</label>
						<label class="swicher-label"><?php echo esc_html__( 'Booking Confirmation', 'hydra-booking' ); ?></label>
					</div>
	
				</div>
			<?php endif ?> 
			<div class="tfhb-confirmation-button">
				<button class="tfhb-flexbox tfhb-gap-8 tfhb-booking-submit">
				<?php echo ! empty( $booking_data ) ? 'Reschedule' : 'Confirm'; ?>  
					<img src="<?php echo esc_url(THB_URL . 'assets/app/images/arrow-right.svg'); ?>" alt="arrow"> 
				</button>
			</div>
			<?php
			echo '</form>';
		}
		?>
  
	</div>

	<?php
		// Hook for After confirmation
		do_action( 'hydra_booking/after_meeting_form' );

	?>
</div>
