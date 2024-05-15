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
 * 
 */


?> 
<div class="tfhb-meeting-confirmation" style="display: none;">
    <?php 
        // Hook for before confirmation
        do_action('hydra_booking/before_meeting_confirmation');
    
    ?>
    <div class="tfhb-confirmation-seccess">
        <img src="<?php echo THB_URL.'assets/app/images/sucess.gif'; ?>" alt="Success"> 
        <h3>Booking Confirmed!</h3>
        <p>Please check your email for more information. Now you can reschedule or cancel booking from here.</p>
    </div>

    <div class="tfhb-meeting-hostinfo">
        <h4>Discussion about design system to work faster</h4>
        <ul>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>
                Abdullah Eusuf 
                <span>Host</span>
            </li>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>
                9:00pm-9:45pm, Saturday, April 25
            </li>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>
                Asia/Dhaka(09.00pm)
            </li>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
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
                    <img src="<?php echo THB_URL.'assets/app/images/g-calendar.svg'; ?>" alt="calendar">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo THB_URL.'assets/app/images/g-calendar.svg'; ?>" alt="calendar">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo THB_URL.'assets/app/images/g-calendar.svg'; ?>" alt="calendar">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo THB_URL.'assets/app/images/g-calendar.svg'; ?>" alt="calendar">
                </a>
            </li>
        </ul>
    </div>

    <div class="tfhb-meeting-confirmation-action tfhb-flexbox tfhb-gap-16">
        <button>Reschedule</button>
        <button>Cancel booking</button>
    </div>

    <?php 
        // Hook for After confirmation
        do_action('hydra_booking/after_meeting_confirmation');
    
    ?>

</div>
<?php

?>