<?php
namespace HydraBooking\App\Shortcode;
// use Classes
use HydraBooking\DB\Meeting;
use HydraBooking\DB\Availability; 
use HydraBooking\Admin\Controller\DateTimeController;
class HydraBookingShortcode {
    public function __construct() { 

        // Add Shortcode
        add_shortcode('hydra_booking', array($this, 'hydra_booking_shortcode'));

        //  Add Action
        add_action('hydra_booking/after_meeting_render', array($this, 'after_meeting_render'));
        add_action('hydra_booking/before_meeting_render', array($this, 'before_meeting_render'));

    }

    public function hydra_booking_shortcode($atts) { 

        
        if(!isset($atts['id']) || $atts['id'] == 0){
            return 'Please provide a valid Meeting id';
        }  

        // Attributes
        $atts = shortcode_atts(
            array( 
                'id' => 0, 
            ),
            $atts,
            'hydra_booking'
        );

        $calendar_id = $atts['id']; 

        // Get Meeting
        $meeting = new Meeting();
        $MeetingData = $meeting->get( $calendar_id ); 

        $meta_data = get_post_meta($MeetingData->post_id, '__tfhb_meeting_opt', true);

        // GetHost meta Data
        $host_id = isset($meta_data['host_id']) ? $meta_data['host_id'] : 0;
        $host_meta = get_user_meta($host_id, '_tfhb_host', true);

        // Time Zone
        $DateTimeZone = new DateTimeController('UTC');
        $time_zone = $DateTimeZone->TimeZone();

     
        // Start Buffer
        ob_start();


        //Before Load the Calendar.
        do_action('hydra_booking/before_meeting_render', $meta_data);

        ?>
        <div class="tfhb-meeting-box tfhb-meeting-<?php echo esc_attr($calendar_id) ?>" data-calendar="<?php echo esc_attr($calendar_id) ?>">

            <form action="">
                <div class="tfhb-meeting-card">
                        <?php  

                            // Load Meeting Info Template  
                            load_template(THB_PATH . '/app/Content/Template/meeting-info.php', false, [
                                'meeting' => $meta_data,
                                'host' => $host_meta, 
                                'time_zone' => $time_zone, 
                            ]); 

                            // Load Meeting Calendar Template
                            load_template(THB_PATH . '/app/Content/Template/meeting-calendar.php', false, $meta_data);

                            // Load Meeting Time Template
                            load_template(THB_PATH . '/app/Content/Template/meeting-times.php', false, $meta_data);
                        ?>
                </div>

            </form>
                
        </div>
        <?php 

        //After Load the Calendar.
        do_action('hydra_booking/after_meeting_render', $meta_data);

        // Return Buffer
        return  ob_get_clean();
    }
 
    // Before Render
    public function before_meeting_render(){
        // Enqueue Styles 
        if(!wp_style_is('tfhb-select2-style', 'enqueued')) {
            wp_enqueue_style('tfhb-select2-style');
        }
    }

    // After Render
    public function after_meeting_render($data) { 
        if(!is_array($data) || empty($data)) {
            return;
        }

        $id = isset($data['id']) ? $data['id'] : 0;
        $host_id = isset($data['host_id']) ? $data['host_id'] : 0;

        // Check if id is not set
        if(0 === $id && 0 === $host_id) {
            return;
        }
        
        if( isset($data['availability_type']) && 'custom' === $data['availability_type']){
            $availability_data = isset($data['availability_custom']) ? $data['availability_custom'] : array(); 

        }else{ 
            $availability = new Availability();
            $availability_data =  isset($data['availability_id']) &&  0 != $data['availability_id'] ? $availability->get($data['availability_id']) : array();
        }

        // Availability Range
        $availability_range = isset($data['availability_range']) ? $data['availability_range'] : array();


        // Enqueue Scripts Register scripts 
        if(!wp_script_is('tfhb-app-script', 'enqueued')) {
            wp_enqueue_script('tfhb-app-script');
        } 

        // Enqueue Select2
        if(!wp_script_is('tfhb-select2-script', 'enqueued')) {
            wp_enqueue_script('tfhb-select2-script');
        }

        // Localize Script
        wp_localize_script('tfhb-app-script', 'tfhb_app_booking_'.$id, array( 
            'meeting_id' => $id,
            'host_id' => $host_id,
            'availability' => $availability_data,
            'availability_range' => $availability_range,
        ));

    }
}

?>