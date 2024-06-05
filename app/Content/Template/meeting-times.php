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

 $meeting = isset($args['meeting']) ? $args['meeting'] : array();
 $general_settings = isset($args['general_settings']) ? $args['general_settings'] : array(); 
 $time_format = isset($general_settings['time_format']) && !empty($general_settings['time_format']) ? $general_settings['time_format'] : '12';
?> 
<div class="tfhb-meeting-times">

    <?php 
        // Hook for before Times
        do_action('hydra_booking/before_meeting_time');
    
    ?>
    <div class="tfhb-timezone-tabs">
        <ul>
            <li class="<?php echo  $time_format == '12' ? 'active' : ''; ?>">
                <label for="tfhb_time_format_12" for=""><?php echo esc_html(__('12h', 'hydra-booking')) ?>
                    <input id="tfhb_time_format_12" type="radio" <?php echo  $time_format == '12' ? 'checked' : ''; ?>  name="tfhb_time_format" value="12">
                </label> 
            </li>
            <li class="<?php echo  $time_format == '24' ? 'active' : ''; ?>">
                <label for="tfhb_time_format_24" for=""><?php echo esc_html(__('24h', 'hydra-booking')) ?>
                    <input id="tfhb_time_format_24" type="radio" <?php echo  $time_format == '24' ? 'checked' : ''; ?> name="tfhb_time_format" value="24">
                </label>
            </li>
        </ul>
    </div>
    <h3 class="tfhb-select-date"> </h3>

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