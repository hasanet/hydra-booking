<?php
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hydrabooking.com
 * @since      1.0.0
 *
 * @Template Template for Meeting Times  
 * 
 * @package    HydraBooking
 * @subpackage HydraBooking/app
 * 
 */


?> 
<div class="tfhb-meeting-times">

    <?php 
        // Hook for before Times
        do_action('hydra_booking/before_meeting_time');
    
    ?>
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

    <?php 
        // Hook for After Times
        do_action('hydra_booking/after_meeting_time');
    
    ?>
</div>
<?php

?>