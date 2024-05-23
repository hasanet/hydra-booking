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
 * 
 */


 $questions = isset($args['questions']) ? $args['questions'] : array();



?> 
<div class="tfhb-meeting-booking-form" style="display:none">
    <?php 
        // Hook for Before Form
        do_action('hydra_booking/before_meeting_form');
    
    ?>

    <div class="tfhb-back-btn tfhb-flexbox tfhb-gap-8">
        <img src="<?php echo THB_URL.'assets/app/images/arrow-left.svg'; ?>" alt="arrow"> 
        <h3>Details</h3>
    </div>
    <div class="tfhb-notice notice-error" style="display:none;"> 
    </div>

    <?php  
    ?>
    <div class="tfhb-forms tfhb-flexbox">
       
        <?php  
            if(is_array($questions) && !empty($questions)): 
                  
                foreach($questions as $key => $question): 
                    $name = 2  >= $key ? $question['label'] : 'question['.$question['label'].']';
                 
                    if(empty($question['type'])){
                        continue;
                    }

                    $required_star = $question['required'] == 1 ? '*' : '';
                    $required = $question['required'] == 1 ? 'required' : '';
                    
                    echo '<div class="tfhb-single-form">
                            <label for="'.$name.'">'.$question['placeholder'].' '.$required_star.'</label>';
                            if($question['type'] == 'select'){

                                echo '<select name="'.$name.'" id="'.$name.'" '.$required.'>';
                                    foreach($question['options'] as $option){
                                        echo '<option value="'.$option['value'].'">'.$option['label'].'</option>';
                                    }
                                echo '</select>';

                            }elseif($question['type'] == 'textarea'){

                                echo '<textarea name="'.$name.'" id="'.$name.'" '.$required.'></textarea>';

                            }elseif($question['type'] == 'checkbox'){ 

                                echo '<label for="'.$name.'">
                                        <input name="'.$name.'" id="'.$name.'" type="'.$question['type'].'" '.$required.'>
                                        <span class="checkmark"></span> '.$question['placeholder'].'
                                    </label>';

                            }else{
                                
                                echo '<input name="'.$name.'" id="'.$name.'"  type="'.$question['type'].'" '.$required.' placeholder="'.$question['placeholder'].'">';
                            }
                    echo '</div>';
         
                endforeach;
            endif;
        ?> 

        <div class="tfhb-confirmation-box tfhb-flexbox">
            <div class="tfhb-swicher-wrap tfhb-flexbox tfhb-gap-8">
                <label class="switch">
                    <input required name="tfhb_booking_checkbox" type="checkbox">
                    <div class="slider"></div>
                </label>
                <label class="swicher-label">Booking Confirmation</label>
            </div>

            <!-- <div class="tfhb-checkbox-wrap tfhb-flexbox tfhb-gap-8">
                <label for="attendee_can_cancel">
                    <input id="attendee_can_cancel" name="attendee_can_cancel" type="checkbox">
                    <span class="checkmark"></span> Attendee can cancel this meeting 
                </label>
            </div> -->
        </div>

        <div class="tfhb-confirmation-button">
            <button class="tfhb-flexbox tfhb-gap-8">
                Confirm
                <img src="<?php echo THB_URL.'assets/app/images/arrow-right.svg'; ?>" alt="arrow"> 
            </button>
        </div>

    </div>

    <?php 
        // Hook for After confirmation
        do_action('hydra_booking/after_meeting_form');
    
    ?>
</div>
<?php

?>