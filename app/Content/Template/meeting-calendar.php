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


?> 
<div class="tfhb-meeting-calendar">
	<?php
		// Hook for before calendar
		do_action( 'hydra_booking/before_meeting_calendar' );

	?>
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
	<?php
		// Hook for After calendar
		do_action( 'hydra_booking/after_meeting_calendar' );

	?>
</div>
