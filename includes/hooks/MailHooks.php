<?php
namespace HydraBooking\Hooks;
class MailHooks{

    public function __construct( ) { 
        add_action('hydra_booking/after_booking_completed', [$this, 'pushBookingScheduledToCompleted'], 10, 1);
    }

    public function pushBookingScheduledToCompleted($booking){
        $Meeting_meta = get_post_meta( $booking->post_id, '__tfhb_meeting_opt', true );
        $_tfhb_notification_settings = !empty($Meeting_meta['notification']) ? $Meeting_meta['notification'] : '';

        if(!empty($_tfhb_notification_settings) && "approved"==$booking->status){

            // Host Confirmation Email, If Settings Enable for Host Confirmation
            if(!empty($_tfhb_notification_settings['host']['booking_confirmation']['status'])){
                // From Email
                $replyTo = !empty($_tfhb_notification_settings['host']['booking_confirmation']['form']) ? $_tfhb_notification_settings['host']['booking_confirmation']['form'] : get_option('admin_email');

                // Email Subject
                $subject = !empty($_tfhb_notification_settings['host']['booking_confirmation']['subject']) ? $_tfhb_notification_settings['host']['booking_confirmation']['subject'] : 'Booking Confirmation';

                // Setting Body
                $mailbody = !empty($_tfhb_notification_settings['host']['booking_confirmation']['body']) ? $_tfhb_notification_settings['host']['booking_confirmation']['body'] : '';

                // Replace Shortcode to Values
                $finalbody = $this->replace_mail_tags( $mailbody , $booking->id );

                // Result after Shortcode replce
                $body = wp_kses_post($this->email_body_open().$finalbody.$this->email_body_close()) ;

                // Host Email
                $mailto = !empty($booking->host_email) ? $booking->host_email : '';

                $headers = [
                    'Reply-To: ' . $replyTo
                ];

                Mailer::send($mailto, $subject, $body, $headers);
            }


            // Attendee Confirmation Email, If Settings Enable for Attendee Confirmation
            if(!empty($_tfhb_notification_settings['attendee']['booking_confirmation']['status'])){
                // From Email
                $replyTo = !empty($_tfhb_notification_settings['attendee']['booking_confirmation']['form']) ? $_tfhb_notification_settings['attendee']['booking_confirmation']['form'] : get_option('admin_email');

                // Email Subject
                $subject = !empty($_tfhb_notification_settings['attendee']['booking_confirmation']['subject']) ? $_tfhb_notification_settings['attendee']['booking_confirmation']['subject'] : 'Booking Confirmation';

                // Setting Body
                $mailbody = !empty($_tfhb_notification_settings['attendee']['booking_confirmation']['body']) ? $_tfhb_notification_settings['attendee']['booking_confirmation']['body'] : '';

                // Replace Shortcode to Values
                $finalbody = $this->replace_mail_tags( $mailbody , $booking->id );

                // Result after Shortcode replce
                $body = wp_kses_post($this->email_body_open().$finalbody.$this->email_body_close()) ;

                // Attendee Email
                $mailto = !empty($booking->email) ? $booking->email : '';

                $headers = [
                    'Reply-To: ' . $replyTo
                ];

                Mailer::send($mailto, $subject, $body, $headers);
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

    /**
     * Replace all available mail tags
     */
    public function replace_mail_tags( $template, $booking_id ) {

        global $wpdb;
        $tfhb_booking_table = $wpdb->prefix . 'tfhb_bookings';
        $meeting_table = $wpdb->prefix . 'tfhb_meetings';
        $host_table = $wpdb->prefix . 'tfhb_hosts';

        $sql = "
            SELECT $tfhb_booking_table.attendee_name, 
            $tfhb_booking_table.email AS attendee_email,
            $tfhb_booking_table.meeting_dates,
            $tfhb_booking_table.start_time,
            $tfhb_booking_table.end_time,
            $tfhb_booking_table.duration AS meeting_duration,
            $host_table.email AS host_email,
            $host_table.first_name AS host_first_name,
            $host_table.last_name AS host_last_name,
            $meeting_table.title AS meeting_title
            FROM $tfhb_booking_table
            INNER JOIN $host_table ON $tfhb_booking_table.host_id = $host_table.id
            INNER JOIN $meeting_table ON $tfhb_booking_table.meeting_id = $meeting_table.id
            WHERE $tfhb_booking_table.id = %d
        ";
        $booking_data = $wpdb->get_row($wpdb->prepare($sql, $booking_id));

        $replacements = array(
            '{{meeting.title}}'     => !empty($booking_data->meeting_title) ? $booking_data->meeting_title : '',
            '{{meeting.date}}'      => !empty($booking_data->meeting_dates) ? $booking_data->meeting_dates : '',
            '{{meeting.location}}'  => '',
            '{{meeting.duration}}'  => $booking_data->meeting_duration,
            '{{meeting.time}}'      => $booking_data->start_time . '-' . $booking_data->end_time,
            '{{host.name}}'         => $booking_data->host_first_name . ' ' . $booking_data->host_last_name,
            '{{host.email}}'        => !empty($booking_data->host_email) ? $booking_data->host_email : '',
            '{{attendee.name}}'     => !empty($booking_data->attendee_name) ? $booking_data->attendee_name : '',
            '{{attendee.email}}'    => !empty($booking_data->attendee_email) ? $booking_data->attendee_email : ''
        );

        $tags = array_keys($replacements);
        $values = array_values($replacements);

        return str_replace( $tags, $values, $template );
    }
}