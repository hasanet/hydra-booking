<?php
defined( 'ABSPATH' ) || exit;

class Tfhb_APP {

	// constaract
	public function __construct() {
		add_shortcode( 'tfhb_meetings', array( $this, 'tfhb_meetings_callback' ) );
	}


	public function tfhb_meetings_callback() {
		ob_start();
		?>
		<div class="tfhb-meeting-box">
			<div class="tfhb-meeting-card">
				<div class="tfhb-meeting-info">

					<div class="tfhb-host-info" style="background: linear-gradient(181deg, rgba(252, 169, 185, 0.00) 1.18%, rgba(89, 1, 39, 0.70) 98.83%), url(<?php echo esc_url(THB_URL . 'assets/app/images/meeting-cover.png'); ?>) lightgray 50% / cover no-repeat;">
						<div class="tfhb-host-profile tfhb-flexbox tfhb-gap-8">
							<img src="<?php echo esc_url(THB_URL . 'assets/app/images/host.png'); ?>" alt="">
							<div class="tfhb-host-name">
								<h3>Abdullah Eusuf</h3>
								<p>Design system manager</p>
							</div>
						</div>
					</div>

					<div class="tfhb-meeting-details">
						<h2>Discussion about design system to work faster</h2>
						<p>Hydra reads your availability from all your existing calendars ensuring you never get double booked... <span>See more</span></p>

						<ul>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/clock.svg'); ?>" alt="Clock">
								</div>
								45 minutes
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="location">   
								</div> 
								Google Meet
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/payment.svg'); ?>" alt="payment"> 
								</div> 
								$200
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/refresh-cw.svg'); ?>" alt="refresh"> 
								</div> 
								Recurring for <span>8</span> weeks
							</li>
						</ul>

						<div class="tfhb-timezone tfhb-flexbox">
							<div class="tfhb-timezone-title tfhb-flexbox tfhb-gap-8">
								<img src="<?php echo esc_url(THB_URL . 'assets/app/images/globe.svg'); ?>" alt="globe"> 
								Asia/Dhaka(09.00 PM)
							</div>
							<img src="<?php echo esc_url(THB_URL . 'assets/app/images/chevrons-up-down.svg'); ?>" alt="chevrons"> 
						</div>
					</div>
				</div>

				<div class="tfhb-meeting-calendar">
					<div class="tfhb-calendar-container">
						<header class="tfhb-calendar-header">
							<p class="tfhb-calendar-current-date"></p>
							<div class="tfhb-calendar-navigation">
								<span id="tfhb-calendar-prev">
									<svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M11.5 15L6.5 10L11.5 5" stroke="#F62881" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
								<span id="tfhb-calendar-next">
									<svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M7.5 15L12.5 10L7.5 5" stroke="#F62881" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
							</div>
						</header>
				
						<div class="tfhb-calendar-body">
							<ul class="tfhb-calendar-weekdays">
								<li>Sun</li>
								<li>Mon</li>
								<li>Tue</li>
								<li>Wed</li>
								<li>Thu</li>
								<li>Fri</li>
								<li>Sat</li>
							</ul>
							<ul class="tfhb-calendar-dates"></ul>
						</div>
					</div>
				</div>

				<div class="tfhb-meeting-times">
					<div class="tfhb-timezone-tabs">
						<ul>
							<li class="active">12h</li>
							<li>24h</li>
						</ul>
					</div>
					<h3 class="tfhb-select-date">Saturday, 11 April, 2024</h3>

					<div class="tfhb-available-times">
						<ul>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>
							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
							</li>

							<li class="tfhb-flexbox">
								<span class="time">09:00 AM</span>
								
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<br>

		<div class="tfhb-meeting-box">
			<div class="tfhb-meeting-card">
				<div class="tfhb-meeting-info">

					<div class="tfhb-host-info" style="background: linear-gradient(181deg, rgba(252, 169, 185, 0.00) 1.18%, rgba(89, 1, 39, 0.70) 98.83%), url(<?php echo esc_url(THB_URL . 'assets/app/images/meeting-cover.png'); ?>) lightgray 50% / cover no-repeat;">
						<div class="tfhb-host-profile tfhb-flexbox tfhb-gap-8">
							<img src="<?php echo esc_url(THB_URL . 'assets/app/images/host.png'); ?>" alt="">
							<div class="tfhb-host-name">
								<h3>Abdullah Eusuf</h3>
								<p>Design system manager</p>
							</div>
						</div>
					</div>

					<div class="tfhb-meeting-details">
						<h2>Discussion about design system to work faster</h2>
						<p>Hydra reads your availability from all your existing calendars ensuring you never get double booked... <span>See more</span></p>

						<ul>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/clock.svg'); ?>" alt="Clock">
								</div>
								45 minutes
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="location">   
								</div> 
								Google Meet
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/payment.svg'); ?>" alt="payment"> 
								</div> 
								$200
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/refresh-cw.svg'); ?>" alt="refresh"> 
								</div> 
								Recurring for <span>8</span> weeks
							</li>
						</ul>

						<div class="tfhb-timezone tfhb-flexbox">
							<div class="tfhb-timezone-title tfhb-flexbox tfhb-gap-8">
								<img src="<?php echo esc_url(THB_URL . 'assets/app/images/globe.svg'); ?>" alt="globe"> 
								Asia/Dhaka(09.00 PM)
							</div>
							<img src="<?php echo esc_url(THB_URL . 'assets/app/images/chevrons-up-down.svg'); ?>" alt="chevrons"> 
						</div>
					</div>
				</div>

				<div class="tfhb-meeting-booking-form">
					<div class="tfhb-back-btn tfhb-flexbox tfhb-gap-8">
						<img src="<?php echo esc_url(THB_URL . 'assets/app/images/arrow-left.svg'); ?>" alt="arrow"> 
						<h3>Details</h3>
					</div>

					<div class="tfhb-forms tfhb-flexbox">
						<div class="tfhb-single-form">
							<label for="name">Name *</label>
							<input type="text" id="name">
						</div>
						<div class="tfhb-single-form">
							<label for="email">Email *</label>
							<input type="text" id="email">
						</div>
						<div class="tfhb-single-form">
							<label for="note">Note</label>
							<textarea name="" id="note"></textarea>
						</div>

						<div class="tfhb-confirmation-box tfhb-flexbox">
							<div class="tfhb-swicher-wrap tfhb-flexbox tfhb-gap-8">
								<label class="switch">
									<input type="checkbox">
									<div class="slider"></div>
								</label>
								<label class="swicher-label">Booking Confirmation</label>
							</div>

							<div class="tfhb-checkbox-wrap tfhb-flexbox tfhb-gap-8">
								<label for="attendee_can_cancel">
									<input id="attendee_can_cancel" name="attendee_can_cancel" type="checkbox">
									<span class="checkmark"></span> Attendee can cancel this meeting 
								</label>
							</div>
						</div>

						<div class="tfhb-confirmation-button">
							<button class="tfhb-flexbox tfhb-gap-8">
								Confirm
								<img src="<?php echo esc_url(THB_URL . 'assets/app/images/arrow-right.svg'); ?>" alt="arrow"> 
							</button>
						</div>

					</div>
				</div>
			</div>
		</div>

		<br>
		<div class="tfhb-meeting-box">
			<div class="tfhb-meeting-card">
				<div class="tfhb-meeting-confirmation">
					<div class="tfhb-confirmation-seccess">
						<img src="<?php echo esc_url(THB_URL . 'assets/app/images/sucess.gif'); ?>" alt="Success"> 
						<h3>Booking Confirmed!</h3>
						<p>Please check your email for more information. Now you can reschedule or cancel booking from here.</p>
					</div>

					<div class="tfhb-meeting-hostinfo">
						<h4>Discussion about design system to work faster</h4>
						<ul>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
								</div>
								Abdullah Eusuf 
								<span>Host</span>
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
								</div>
								9:00pm-9:45pm, Saturday, April 25
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
								</div>
								Asia/Dhaka(09.00pm)
							</li>
							<li class="tfhb-flexbox tfhb-gap-8">
								<div class="tfhb-icon">
									<img src="<?php echo esc_url(THB_URL . 'assets/app/images/location.svg'); ?>" alt="User">
								</div>
								Google Meet
							</li>
						</ul>
					</div>

					<div class="tfhb-meeting-confirmation-calender">
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
					</div>

					<div class="tfhb-meeting-confirmation-action tfhb-flexbox tfhb-gap-16">
						<button>Reschedule</button>
						<button>Cancel booking</button>
					</div>

				</div>
			</div>
		</div>


		<?php
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}

new Tfhb_APP();
