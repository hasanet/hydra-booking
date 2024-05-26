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


 $meeting = isset($args['meeting']) ? $args['meeting'] : array();
 $booking = isset($args['booking']) ? $args['booking'] : array();
 $host = isset($args['host']) ? $args['host'] : array();

 
?> 
<div class="tfhb-meeting-confirmation" >
    <?php 
//      echo "<pre>";
//  print_r($booking);
//  echo "</pre>";
//  echo "<pre>";
//  print_r($host);
//  echo "</pre>";
//  echo "<pre>";
//  print_r($meeting);
//  echo "</pre>";
        // Hook for before confirmation
        do_action('hydra_booking/before_meeting_confirmation');
    
    ?>
    <div class="tfhb-confirmation-seccess">
        <img src="<?php echo THB_URL.'assets/app/images/sucess.gif'; ?>" alt="Success"> 
        <h3><?php echo esc_html(__('Booking Confirmed!', 'hydra-booking')) ?></h3>
        <p>Please check your email for more information. Now you can reschedule or cancel booking from here.</p>
    </div>

    <div class="tfhb-meeting-hostinfo">
        <h4>Discussion about design system to work faster</h4>
        <ul>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>
                <?php echo  !empty($host['first_name']) ?  ''.esc_html($host['first_name']).'  '.esc_html($host['last_name']).'' : '' ?>
                <span>Host</span>
            </li>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>
                <!--date stored in this format  2024-05-24  9:00pm-9:45pm, Saturday, April 25 -->
                <?php  
                    
                    echo  !empty($booking['start_time']) ?  ''.esc_html($booking['start_time']).' - '.esc_html($booking['end_time']).', '.esc_html(date('l, F j', strtotime($booking['meeting_dates']))).'' : '' 
                ?>
            </li>
            <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>
                <!-- Asia/Dhaka  -->
                <?php echo  !empty($booking['attendee_time_zone']) ?  ''.esc_html($booking['attendee_time_zone']).'' : '' ?>

            </li>
            <!-- <li class="tfhb-flexbox tfhb-gap-8">
                <div class="tfhb-icon">
                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="User">
                </div>  
            </li> -->
            <!-- Meeting location -->
            <?php 
                if(!empty($booking['meeting_locations'])) {
                    foreach($booking['meeting_locations'] as $key => $location) {
                        echo '<li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="'.esc_url(THB_URL.'assets/app/images/location.svg').'" alt="location">   
                                </div> 
                                '.esc_html($location['location']).'
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
    </div> -->

    <div class="tfhb-meeting-confirmation-action tfhb-flexbox tfhb-gap-16">
        
        <?php  
            
            if(true == $meeting->attendee_can_cancel){ 
                echo '<a href="">Cancel booking</a>';
            }
            if( true == $meeting->attendee_can_reschedule){
                // Create Url using wordpress methood 
                // add_rewrite_rule(
                //     '^booking/([0-9]+)/?$',
                //     'index.php?hydra-booking=booking&hash=$matches[1]&meeting-id=$matches[2]&type=$matches[3]',
                //     'top'
                // );  
                $reschedule_url = add_query_arg( array(
                    'hydra-booking' => 'booking',
                    'hash' => $booking['hash'],
                    'meeting-id' => $booking['meeting_id'],
                    'type' => 'reschedule'
                ), home_url() );
                
                echo '<a href="'.esc_url( $reschedule_url ).'">Reschedule</a>';
            }
        ?>
    </div>

    <?php 
        // Hook for After confirmation
        do_action('hydra_booking/after_meeting_confirmation');
    
    ?>

</div>
<?php

?>