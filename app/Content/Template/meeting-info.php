<?php
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hydrabooking.com
 * @since      1.0.0
 *
 * @Template Template for Meeting Info
 * 
 * @package    HydraBooking
 * @subpackage HydraBooking/app
 * 
 */

$meeting = isset($args['meeting']) ? $args['meeting'] : array();
$host = isset($args['host']) ? $args['host'] : array();
$time_zone = isset($args['time_zone']) ? $args['time_zone'] : array();


?> 
<div class="tfhb-meeting-info">
  

    <div class="tfhb-host-info" style="background: linear-gradient(181deg, rgba(252, 169, 185, 0.00) 1.18%, rgba(89, 1, 39, 0.70) 98.83%), url(<?php echo THB_URL.'assets/app/images/meeting-cover.png'; ?>) lightgray 50% / cover no-repeat;">
        <div class="tfhb-host-profile tfhb-flexbox tfhb-gap-8">
            <?php echo !empty($host['avatar']) ?  '<img src="'.esc_url($host['avatar']).'" alt="">' : '' ?>
                 
            <div class="tfhb-host-name">
                <?php echo  !empty($host['first_name']) ?  '<h3>'.esc_html($host['first_name']).'  '.esc_html($host['last_name']).'</h3>' : '' ?>
                <?php echo  !empty($host['about']) ?  '<p>'.esc_html($host['about']).'</p>' : '' ?>
                
            </div>
        </div>
    </div>

    <div class="tfhb-meeting-details">
        <?php echo  !empty($meeting['title']) ?  '<h2>'.esc_html($meeting['title']). '</h2>' : '' ?> 
        <?php echo  !empty($meeting['description']) ?  '<p>'.wp_kses_post($meeting['description']).'</p>' : '' ?> 
        

        <ul>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/clock.svg'; ?>" alt="Clock">
                </div>
                <?php echo !empty($meeting['duration']) ? esc_html($meeting['duration'] .' minutes') : '0 minutes' ?>
                
            </li>
            <?php 
                if(!empty($meeting['meeting_locations'])) { 
                    foreach($meeting['meeting_locations'] as $key => $location) {
                        echo '<li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="'.esc_url(THB_URL.'assets/app/images/location.svg').'" alt="location">   
                                </div> 
                                '.esc_html($location['location']).'
                            </li>';
                    }
                }
            
            ?>
            <?php 
                if(!empty($meeting['payment_status']) && true == $meeting['payment_status']) {  
                    $price = !empty($meeting['meeting_price']) ? $meeting['meeting_price'] : 'Free';
                    echo '<li class="tfhb-flexbox tfhb-gap-8">
                            <div class="tfhb-icon">
                                <img src="'.esc_url(THB_URL.'assets/app/images/payment.svg').'" alt="payment">   
                            </div> 
                            '.esc_html($price).'
                        </li>'; 
                }
            ?> 
            <?php 
                if(!empty($meeting['recurring_status']) && true == $meeting['recurring_status']) {  
                    echo '<li class="tfhb-flexbox tfhb-gap-8">
                            <div class="tfhb-icon">
                                <img src="'.esc_url(THB_URL.'assets/app/images/refresh-cw.svg').'" alt="refresh">   
                            </div> 
                            Recurring for <span>8</span> weeks
                        </li>'; 
                }
            ?>
        </ul>

        <div class="tfhb-timezone ">
            <select class="tfhb-time-zone-select" name="tfhb_time_zone" id="">
                <?php 
                    if(!empty($time_zone)) {
                        foreach($time_zone as $key => $zone) {
                            $selected = ($zone['value'] == $meeting['time_zone']) ? 'selected' : '';
                            echo '<option value="'.esc_attr($zone['value']).'" '.esc_attr($selected).'>'.esc_html($zone['name']).'</option>';
                        }
                    }
                
                ?>
                <option value="1">Asia/Dhaka(09.00 PM)</option>
                <option value="2">Asia/Dhaka</option>
                <option value="3">Asia/Dhaka</option>
                <option value="4">Asia/Dhaka</option>
            </select>
            <div class="tfhb-timezone-icon ">
                <img src="<?php echo THB_URL.'assets/app/images/globe.svg'; ?>" alt="globe">  
            </div>
            <!-- <img src="<?php echo THB_URL.'assets/app/images/chevrons-up-down.svg'; ?>" alt="chevrons">  -->
        </div>
    </div>

    <?php 

        // Hooks After Meeting Info
         do_action('hydra_booking/after_meeting_info');
    
    ?>
</div>

<?php

?>