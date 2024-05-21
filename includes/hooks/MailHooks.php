<?php
namespace HydraBooking\Hooks;
class MailHooks{

    public function __construct( ) { 
        add_action('hydra_booking/after_booking_completed', [$this, 'pushBookingScheduledToCompleted'], 10, 1);
    }

    public function pushBookingScheduledToCompleted($booking){
        $_tfhb_notification_settings = get_option('_tfhb_notification_settings');
        if("approved"==$booking->status){
            if(!empty($_tfhb_notification_settings['host']['booking_confirmation']['status'])){
                // var_dump($booking); exit();

                $replyTo = !empty($_tfhb_notification_settings['host']['booking_confirmation']['form']) ? $_tfhb_notification_settings['host']['booking_confirmation']['form'] : get_option('admin_email');

                $subject = !empty($_tfhb_notification_settings['host']['booking_confirmation']['subject']) ? $_tfhb_notification_settings['host']['booking_confirmation']['subject'] : 'Booking Confirmation';

                $body = !empty($_tfhb_notification_settings['host']['booking_confirmation']['body']) ? wp_kses_post($this->email_body_open().$_tfhb_notification_settings['host']['booking_confirmation']['body'].$this->email_body_close()) : '';

                $mailto = !empty($booking->host_email) ? $booking->host_email : '';

                $headers = [
                    'Reply-To: ' . $replyTo
                ];

                return Mailer::send($mailto, $subject, $body, $headers);
            }
        }
    }

    /**
     * email body open markup
     */
    public function email_body_open(){
        //email body open
        $email_body_open = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="preconnect" href="https://fonts.googleapis.com"></head><body>';
        return $email_body_open;
    }

    /**
     * email body close markup
     */
    public function email_body_close(){
        //email body close
        $email_body_close = '</body></html>';
        return $email_body_close;
    }
}