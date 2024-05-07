<?php
defined( 'ABSPATH' ) || exit;

class Tfhb_APP {

    // constaract
    public function __construct() { 
        add_shortcode('tfhb_meetings', array($this, 'tfhb_meetings_callback'));
    }


    public function tfhb_meetings_callback( ) {
    ob_start();
    ?>
        <div class="tfhb-meeting-box">
            <div class="tfhb-meeting-card">
                <div class="tfhb-meeting-info">

                    <div class="tfhb-host-info" style="background: linear-gradient(181deg, rgba(252, 169, 185, 0.00) 1.18%, rgba(89, 1, 39, 0.70) 98.83%), url(<?php echo THB_URL.'assets/app/images/meeting-cover.png'; ?>) lightgray 50% / cover no-repeat;">
                        <div class="tfhb-host-profile tfhb-flexbox tfhb-gap-8">
                            <img src="<?php echo THB_URL.'assets/app/images/host.png'; ?>" alt="">
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
                                    <img src="<?php echo THB_URL.'assets/app/images/clock.svg'; ?>" alt="Clock">
                                </div>
                                45 minutes
                            </li>
                            <li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="location">   
                                </div> 
                                Google Meet
                            </li>
                            <li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="<?php echo THB_URL.'assets/app/images/payment.svg'; ?>" alt="payment"> 
                                </div> 
                                $200
                            </li>
                            <li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="<?php echo THB_URL.'assets/app/images/refresh-cw.svg'; ?>" alt="refresh"> 
                                </div> 
                                Recurring for <span>8</span> weeks
                            </li>
                        </ul>

                        <div class="tfhb-timezone tfhb-flexbox">
                            <div class="tfhb-timezone-title tfhb-flexbox tfhb-gap-8">
                                <img src="<?php echo THB_URL.'assets/app/images/globe.svg'; ?>" alt="globe"> 
                                Asia/Dhaka(09.00 PM)
                            </div>
                            <img src="<?php echo THB_URL.'assets/app/images/chevrons-up-down.svg'; ?>" alt="chevrons"> 
                        </div>
                    </div>
                </div>

                <div class="tfhb-meeting-calendar">

                </div>

                <div class="tfhb-meeting-times">
                    <div class="tfhb-timezone-tabs">
                        <ul>
                            <li class="active">12h</li>
                            <li>24h</li>
                        </ul>
                    </div>
                    <h3>Saturday, 11 April, 2024</h3>

                    <div class="tfhb-available-times">
                        <ul>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>
                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                            </li>

                            <li class="tfhb-flexbox">
                                <span>09:00 AM</span>
                                <span class="next tfhb-flexbox tfhb-gap-8">
                                    Next
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 10L14 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 4.16666L14.8333 9.99999L9 15.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="tfhb-meeting-box">
            <div class="tfhb-meeting-card">
                <div class="tfhb-meeting-info">

                    <div class="tfhb-host-info" style="background: linear-gradient(181deg, rgba(252, 169, 185, 0.00) 1.18%, rgba(89, 1, 39, 0.70) 98.83%), url(<?php echo THB_URL.'assets/app/images/meeting-cover.png'; ?>) lightgray 50% / cover no-repeat;">
                        <div class="tfhb-host-profile tfhb-flexbox tfhb-gap-8">
                            <img src="<?php echo THB_URL.'assets/app/images/host.png'; ?>" alt="">
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
                                    <img src="<?php echo THB_URL.'assets/app/images/clock.svg'; ?>" alt="Clock">
                                </div>
                                45 minutes
                            </li>
                            <li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="<?php echo THB_URL.'assets/app/images/location.svg'; ?>" alt="location">   
                                </div> 
                                Google Meet
                            </li>
                            <li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="<?php echo THB_URL.'assets/app/images/payment.svg'; ?>" alt="payment"> 
                                </div> 
                                $200
                            </li>
                            <li class="tfhb-flexbox tfhb-gap-8">
                                <div class="tfhb-icon">
                                    <img src="<?php echo THB_URL.'assets/app/images/refresh-cw.svg'; ?>" alt="refresh"> 
                                </div> 
                                Recurring for <span>8</span> weeks
                            </li>
                        </ul>

                        <div class="tfhb-timezone tfhb-flexbox">
                            <div class="tfhb-timezone-title tfhb-flexbox tfhb-gap-8">
                                <img src="<?php echo THB_URL.'assets/app/images/globe.svg'; ?>" alt="globe"> 
                                Asia/Dhaka(09.00 PM)
                            </div>
                            <img src="<?php echo THB_URL.'assets/app/images/chevrons-up-down.svg'; ?>" alt="chevrons"> 
                        </div>
                    </div>
                </div>

                <div class="tfhb-meeting-booking-form">
                    <div class="tfhb-back-btn tfhb-flexbox tfhb-gap-8">
                        <img src="<?php echo THB_URL.'assets/app/images/arrow-left.svg'; ?>" alt="arrow"> 
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