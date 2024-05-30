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
            <li class="active">
                <label for="tfhb_time_format_12" for="">12h
                    <input id="tfhb_time_format_12" type="radio" checked name="tfhb_time_format" value="12">
                </label> 
            </li>
            <li>
                <label for="tfhb_time_format_24" for="">24h
                    <input id="tfhb_time_format_24" type="radio" name="tfhb_time_format" value="24">
                </label>
            </li>
        </ul>
    </div>
    <h3 class="tfhb-select-date">Saturday, 11 April, 2024</h3>

    <div class="tfhb-available-times">
        <ul>
            <!-- <li class="tfhb-flexbox"> <span class="time">09:00 AM</span> </li> -->
            
        </ul>
    </div>

    <?php 
        // Hook for After Times
        do_action('hydra_booking/after_meeting_time');
    
    ?>
</div>
<?php

?>