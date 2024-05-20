<?php
namespace HydraBooking\Hooks;
class MailHooks{

    public function __construct( ) { 
        add_action('hydra_booking/after_booking_completed', [$this, 'pushBookingScheduledToCompleted'], 10, 1);
    }

    public function pushBookingScheduledToCompleted($booking){
        $_tfhb_notification_settings = get_option('_tfhb_notification_settings');
        if("approved"==$booking->status){
            var_dump($_tfhb_notification_settings['host']['booking_confirmation']['status']); exit();
        }
    }
}