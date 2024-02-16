<?php
/**
 * Plugin Name: Hydra Booking
 * Plugin URI: https://themefic.com/hydra-booking
 * Description: Create a booking / Appointment Form using Contact Form 7. You can insert Calendar, Time on the form and manage your booking. User can pay using WooCommerce. 
 * Version: 1.0.0
 * Author: Themefic
 * Author URI: https://themefic.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: hydra-booking
 * Domain Path: /languages
 */

// don't load directly
defined( 'ABSPATH' ) || exit;

class TFHB_HYDRA_INIT {

    public function __construct() { 
        
        // Define thb constants
        $this->tfhb_define_constants();

        // Include required files
        $this->tfhb_include_files();
      
        //  Plugin Loaded
        add_action( 'plugins_loaded', array( $this, 'tfhb_plugin_loaded' ) );
    
    }

    public function tfhb_define_constants(){
            // Other defines
            define( 'TFHB_VERSION', '1.0.0' );
            define( 'TFHB_URL', plugin_dir_url( __FILE__ ) );
            define( 'TFHB_PATH', plugin_dir_path( __FILE__ ) );
            define( 'TFHB_ASSETS_URL', TFHB_URL.'assets/' );
            define( 'TFHB_ADMIN_URL', TFHB_URL.'admin/' );

    }

   
    public function tfhb_include_files(){

        /**
         * Including Plugin file
         * 
         * @since 1.0
         */
      
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        
  
        /**
         * Including Booking Google api Integration File
         *
         * @since 1.0.6
         */

        require_once( 'inc/functions.php' );


    }

    // Plugin Loaded
    public function tfhb_plugin_loaded(){
        if ( class_exists( 'WPCF7' ) ) {
			//Init ultimate addons
			$this->tfhb_init();

		} else {
			//Admin notice
			add_action( 'admin_notices', array( $this, 'tfhb_admin_notice' ) );
		}

        
    }


  	/*
	 * Admin notice- To check the Contact form 7 plugin is installed
	 */
    
	public function tfhb_admin_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php 
                
                printf(
                    /* translators: %s is replaced with "Plugin Name & link" */ 
                    esc_html__( ' %1$s srequires %2$s to be installed and active. You can install and activate it from  %3$s .', 'hydra-booking' ),
                    '<strong>Hydra Booking </strong> ', 
                    '<strong>Contact form 7</strong> ', 
                    '<a href="' . esc_url(admin_url( 'plugin-install.php?tab=search&s=contact+form+7' )) . '">here</a>'
                ); 
                ?>
			</p>
		</div>
		<?php
	}
    public function tfhb_init(){
        
        //  Add form tag
        add_action( 'admin_init', array( $this, 'tag_generator' ) );
          
        // Load Text Domain
        $this->tfhb_load_textdomain();

        // Enqueue Admin scripts
        add_action( 'admin_enqueue_scripts', array($this, 'tfhb_enqueue_admin_scripts') ); 

        // Dequeue Scripts
        add_filter( 'wp_enqueue_scripts', array($this, 'tfhb_dequeue_scripts'), 9999 );

        // Enqueue frontend scripts
        add_filter( 'wp_enqueue_scripts', array($this, 'tfhb_enqueue_scripts'), 99999 );

        // Add admin menu
        add_action( 'wpcf7_init', array($this, 'tfhb_form_tag' ));

        // Validation filter for date
        add_filter( 'wpcf7_validate_tfhb_booking_form_date', array($this, 'tfhb_date_validation_filter'), 10, 2 );
        add_filter( 'wpcf7_validate_tfhb_booking_form_date*', array($this, 'tfhb_date_validation_filter'), 10, 2 );

        // Validation filter for time
        add_filter( 'wpcf7_validate_tfhb_booking_form_time', array($this, 'tfhb_time_validation_filter'), 10, 2 );
        add_filter( 'wpcf7_validate_tfhb_booking_form_time*', array($this, 'tfhb_time_validation_filter'), 10, 2 );


        // Validation message for time
        add_filter( 'wpcf7_messages', array($this, 'tfhb_time_messages'), 10, 1 );

        // Editor Panels
        add_action( 'wpcf7_editor_panels', array($this, 'tfhb_hydra_add_panel') );
 
        // Save After Form Submission
        add_action( 'wpcf7_after_save', array($this, 'tfhb_hydra_save_contact_form') );

        // Save Properties
        add_filter( 'wpcf7_contact_form_properties', array($this,  'tfhb_hydra_properties'), 10, 2 );

        // Ajax Add To Cart
        add_action( 'wp_ajax_tfhb_hydra_ajax_add_to_cart_product', array($this, 'tfhb_hydra_ajax_add_to_cart_product') );

        add_action( 'wp_ajax_nopriv_tfhb_hydra_ajax_add_to_cart_product', array($this, 'tfhb_hydra_ajax_add_to_cart_product') );

        // Display Cart Item Custom Meta Data
        add_filter( 'woocommerce_get_item_data', array($this, 'tfhb_hydra_display_cart_item_custom_meta_data'), 10, 2 );

        // Save Cart Item Custom Meta Data
        add_action( 'woocommerce_checkout_create_order_line_item', array($this, 'tfhb_hydra_save_cart_item_custom_meta_as_order_item_meta'), 10, 4 );
 
    }

    // Load Text Domain
    public function tfhb_load_textdomain() {
        load_plugin_textdomain( 'hydra-booking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    // Enqueue Admin scripts
    public function tfhb_enqueue_admin_scripts(){

        // flatpickr
        wp_enqueue_script( 'flatpickr', TFHB_ASSETS_URL . 'flatpickr/flatpickr.min.js', array('jquery'), '4.6.9', true );
        wp_enqueue_script( 'flatpickr-range', TFHB_ASSETS_URL . 'flatpickr/rangePlugin.min.js', array('jquery'), '4.6.9', true );

        // jquery-timepicker
        wp_enqueue_script( 'jquery.timepicker', TFHB_ADMIN_URL . 'js/jquery.timepicker.min.js', array('jquery'), '1.13.18', true );

        // Custom
        wp_enqueue_style('thb', TFHB_ADMIN_URL . 'css/admin.css','', TFHB_VERSION );
        wp_enqueue_script( 'thb', TFHB_ADMIN_URL . 'js/admin.js', array('jquery'), TFHB_VERSION, true );   
        wp_localize_script( 'thb', 'tfhb_params',
            array(
                'tfhb_nonce' => wp_create_nonce( 'updates' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }

    public function tfhb_dequeue_scripts(){

        wp_deregister_script( 'flatpickr' );
		wp_dequeue_script( 'flatpickr' );
		wp_deregister_script( 'jquery.timepicker' );
		wp_dequeue_script( 'jquery.timepicker' );
		
    }

    // Enqueue frontend scripts
    public function tfhb_enqueue_scripts(){

        // flatpickr
        wp_enqueue_script( 'flatpickr', TFHB_ASSETS_URL . 'flatpickr/flatpickr.min.js', array('jquery'), '4.6.9', true );

        // jquery-timepicker
        wp_enqueue_script( 'jquery.timepicker', TFHB_ASSETS_URL . 'jquery-timepicker/jquery.timepicker.min.js', array('jquery'), '1.13.18', true );

        // Custom
        wp_enqueue_style('thb-style', TFHB_ASSETS_URL . 'css/custom.css', '', TFHB_VERSION );
        wp_enqueue_script( 'thb-script', TFHB_ASSETS_URL . 'js/custom.js', array('jquery'), TFHB_VERSION, true );
		
		$checkout_url = '';
		if(function_exists('wc_get_checkout_url')){ 
			$checkout_url = wc_get_checkout_url();
		}

		$cart_url = '';
		if(function_exists('wc_get_cart_url')){ 
			$cart_url = wc_get_cart_url();
		} 
		wp_localize_script( 'thb-script', 'tfhb_pro_object',
			array(
				'checkout_page' => $checkout_url,
				'cart_page' => $cart_url,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'tfhb_nonce' => wp_create_nonce( 'updates' ),
			)
		);
		
    }
    

    public function tfhb_form_tag() {

        wpcf7_add_form_tag( array( 'tfhb_booking_form_date', 'tfhb_booking_form_date*' ), array($this, 'tfhb_booking_form_date_tag_handler'), array( 'name-attr' => true ) );

        wpcf7_add_form_tag( array( 'tfhb_booking_form_time', 'tfhb_booking_form_time*' ), array($this, 'tfhb_booking_form_time_tag_handler'), array( 'name-attr' => true ) );
    }


     
    /**
     * Form Tag handler
     * tfhb_booking_form_date
     * 
     * @since 1.0
     */
    public function tfhb_booking_form_date_tag_handler( $tag ) {


        $wpcf7 = WPCF7_ContactForm::get_current(); 
       

        $form_id = $wpcf7->id();

        // get saved value      
        $hydra_enable = !empty(get_post_meta( $form_id, 'hydra_enable', true )) ? get_post_meta( $form_id, 'hydra_enable', true ) : '';
        $hydra_duplicate_status = !empty(get_post_meta( $form_id, 'hydra_duplicate_status', true )) ? get_post_meta( $form_id, 'hydra_duplicate_status', true ) : '';
        
        
        $event_date = !empty(get_post_meta( $form_id, 'event_date', true )) ? get_post_meta( $form_id, 'event_date', true ) : '';
        $event_time = !empty(get_post_meta( $form_id, 'event_time', true )) ? get_post_meta( $form_id, 'event_time', true ) : '';
        
        // Frontend values
        $date_mode_front = !empty(get_post_meta( $form_id, 'date_mode_front', true )) ? get_post_meta( $form_id, 'date_mode_front', true ) : 'single';
        $hydra_date_theme = !empty(get_post_meta( $form_id, 'hydra_date_theme', true )) ? get_post_meta( $form_id, 'hydra_date_theme', true ) : '';
        
        // Allowed dates
        $hydra_allowed_date = !empty(get_post_meta( $form_id, 'hydra_allowed_date', true )) ? get_post_meta( $form_id, 'hydra_allowed_date', true ) : 'always'; 
        $allowed_start_date = !empty(get_post_meta( $form_id, 'allowed_start_date', true )) ? get_post_meta( $form_id, 'allowed_start_date', true ) : '';
        $allowed_end_date = !empty(get_post_meta( $form_id, 'allowed_end_date', true )) ? get_post_meta( $form_id, 'allowed_end_date', true ) : '';
        $allowed_specific_date = !empty(get_post_meta( $form_id, 'allowed_specific_date', true )) ? get_post_meta( $form_id, 'allowed_specific_date', true ) : '';
        $allowed_specific_date = explode(',', esc_html($allowed_specific_date));

        // $allowed_specific_date = str_replace(', ', '", "', $allowed_specific_date);
        $min_date = !empty(get_post_meta( $form_id, 'min_date', true )) ? get_post_meta( $form_id, 'min_date', true ) : '';
        $max_date = !empty(get_post_meta( $form_id, 'max_date', true )) ? get_post_meta( $form_id, 'max_date', true ) : '';
        
        // Disabled dates
        $disable_day_1 = !empty(get_post_meta( $form_id, 'disable_day_1', true )) ? get_post_meta( $form_id, 'disable_day_1', true ) : 8;
        $disable_day_2 = !empty(get_post_meta( $form_id, 'disable_day_2', true )) ? get_post_meta( $form_id, 'disable_day_2', true ) : 8;
        $disable_day_3 = !empty(get_post_meta( $form_id, 'disable_day_3', true )) ? get_post_meta( $form_id, 'disable_day_3', true ) : 8;
        $disable_day_4 = !empty(get_post_meta( $form_id, 'disable_day_4', true )) ? get_post_meta( $form_id, 'disable_day_4', true ) : 8;
        $disable_day_5 = !empty(get_post_meta( $form_id, 'disable_day_5', true )) ? get_post_meta( $form_id, 'disable_day_5', true ) : 8;
        $disable_day_6 = !empty(get_post_meta( $form_id, 'disable_day_6', true )) ? get_post_meta( $form_id, 'disable_day_6', true ) : 8;
        $disable_day_0 = get_post_meta( $form_id, 'disable_day_0', true ) != '' ? get_post_meta( $form_id, 'disable_day_0', true ) : 8;
        $disabled_start_date = !empty(get_post_meta( $form_id, 'disabled_start_date', true )) ? get_post_meta( $form_id, 'disabled_start_date', true ) : '';
        $disabled_end_date = !empty(get_post_meta( $form_id, 'disabled_end_date', true )) ? get_post_meta( $form_id, 'disabled_end_date', true ) : '';
        $disabled_specific_date = !empty(get_post_meta( $form_id, 'disabled_specific_date', true )) ? get_post_meta( $form_id, 'disabled_specific_date', true ) : '';
        // $disabled_specific_date = str_replace(', ', '", "', $disabled_specific_date);
        $disabled_specific_date = explode(',', esc_html($disabled_specific_date));
        $time_one_step = !empty(get_post_meta( $form_id, 'time_one_step', true )) ? get_post_meta( $form_id, 'time_one_step', true ) : '30';
        $time_two_step = !empty(get_post_meta( $form_id, 'time_two_step', true )) ? get_post_meta( $form_id, 'time_two_step', true ) : '';

        
        // WooCommerce
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) { 
            $hydra_woo = 'off';
        } else{
            
            $hydra_woo = !empty(get_post_meta( $form_id, 'hydra_woo', true )) ? get_post_meta( $form_id, 'hydra_woo', true ) : '';
        }
        
        $hydra_product = !empty(get_post_meta( $form_id, 'hydra_product', true )) ? get_post_meta( $form_id, 'hydra_product', true ) : '';
        
        $hydra_product_id = !empty(get_post_meta( $form_id, 'hydra_product_id', true )) ? get_post_meta( $form_id, 'hydra_product_id', true ) : '';
        
       
        $hydra_product_name = !empty(get_post_meta( $form_id, 'hydra_product_name', true )) ? get_post_meta( $form_id, 'hydra_product_name', true ) : '';
       
        $hydra_product_price = !empty(get_post_meta( $form_id, 'hydra_product_price', true )) ? get_post_meta( $form_id, 'hydra_product_price', true ) : '';  

        $store_data = '';
     
     
        $booking_date = array(
            'hydra_enable' => esc_html($hydra_enable),
            'date_mode_front' => esc_html($date_mode_front),
            'hydra_date_theme' => esc_html($hydra_date_theme),
            'hydra_allowed_date' => esc_html($hydra_allowed_date),
            'allowed_start_date' => esc_html($allowed_start_date),
            'allowed_end_date' => esc_html($allowed_end_date),
            'allowed_specific_date' => $allowed_specific_date,
            'min_date' => esc_html($min_date),
            'max_date' => esc_html($max_date),
            'disable_day_1' => esc_html($disable_day_1),
            'disable_day_2' => esc_html($disable_day_2),
            'disable_day_3' => esc_html($disable_day_3),
            'disable_day_4' => esc_html($disable_day_4),
            'disable_day_5' => esc_html($disable_day_5),
            'disable_day_6' => esc_html($disable_day_6),
            'disable_day_0' => esc_html($disable_day_0),
            'disabled_start_date' => esc_html($disabled_start_date),
            'disabled_end_date' => esc_html($disabled_end_date),
            'disabled_specific_date' => $disabled_specific_date,
            'hydra_woo' => esc_html($hydra_woo),
            'hydra_product' => esc_html($hydra_product),
            'hydra_product_id' => esc_html($hydra_product_id),
            'hydra_product_name' => esc_html($hydra_product_name),
            'hydra_product_price' => esc_html($hydra_product_price), 
            'store_data' => $store_data,
        ); 

        if ( empty( $tag->name ) ) {
            return '';
        } 
        $validation_error = wpcf7_get_validation_error( $tag->name );
    
        $class = wpcf7_form_controls_class( $tag->type );
    
        $class .= ' wpcf7-validates-as-date';
    
        if ( $validation_error ) {
            $class .= ' wpcf7-not-valid';
        }
    
        $atts = array();
    
        $atts['class'] = $tag->get_class_option( $class );
        $atts['class'] .= ' hydra-form-input-date';
        $atts['date-data'] = wp_json_encode($booking_date);
        $atts['id'] = $tag->name; 
        $atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );
        $atts['min'] = $tag->get_date_option( 'min' );
        $atts['max'] = $tag->get_date_option( 'max' );
        $atts['step'] = $tag->get_option( 'step', 'int', true );
    
        if ( $tag->is_required() ) {
            $atts['aria-required'] = 'true';
        }
    
        if ( $validation_error ) {
            $atts['aria-invalid'] = 'true';
            $atts['aria-describedby'] = wpcf7_get_validation_error_reference(
                $tag->name
            );
        } else {
            $atts['aria-invalid'] = 'false';
        }
    
        $value = (string) reset( $tag->values );
    
        $value = $tag->get_default_option( $value );
    
        if ( $value ) {
            $datetime_obj = date_create_immutable(
                preg_replace( '/[_]+/', ' ', $value ),
                wp_timezone()
            );
    
            if ( $datetime_obj ) {
                $value = $datetime_obj->format( 'Y-m-d' );
            }
        }
    
        $value = wpcf7_get_hangover( $tag->name, $value );
    
        $atts['value'] = $value;
    
        if ( wpcf7_support_html5() ) {
            $atts['type'] = $tag->basetype;
        } else {
            $atts['type'] = 'text';
        }
    
        $atts['name'] = $tag->name;
    
        $atts = wpcf7_format_atts( $atts );
    
        $html = sprintf(
            '<span class="wpcf7-form-control-wrap %1$s"><input %2$s autocomplete="off" />%3$s</span>',
            sanitize_html_class( $tag->name ), $atts, $validation_error
        );
    
        return $html;
    }

    /**
     * Form Tag handler
     * tfhb_booking_form_time
     * 
     * @since 1.0
     */
    public function tfhb_booking_form_time_tag_handler( $tag ) {
        if ( empty( $tag->name ) ) {
            return '';
        }
        
        $wpcf7 = WPCF7_ContactForm::get_current();

        $form_id = $wpcf7->id();
    

        // Allowed Times 
        $hydra_allowed_time = !empty(get_post_meta( $form_id, 'hydra_allowed_time', true )) ? get_post_meta( $form_id, 'hydra_allowed_time', true ) : 'always';
        $time_day_1 = !empty(get_post_meta( $form_id, 'time_day_1', true )) ? get_post_meta( $form_id, 'time_day_1', true ) : '';
        $time_day_2 = !empty(get_post_meta( $form_id, 'time_day_2', true )) ? get_post_meta( $form_id, 'time_day_2', true ) : '';
        $time_day_3 = !empty(get_post_meta( $form_id, 'time_day_3', true )) ? get_post_meta( $form_id, 'time_day_3', true ) : '';
        $time_day_4 = !empty(get_post_meta( $form_id, 'time_day_4', true )) ? get_post_meta( $form_id, 'time_day_4', true ) : '';
        $time_day_5 = !empty(get_post_meta( $form_id, 'time_day_5', true )) ? get_post_meta( $form_id, 'time_day_5', true ) : '';
        $time_day_6 = !empty(get_post_meta( $form_id, 'time_day_6', true )) ? get_post_meta( $form_id, 'time_day_6', true ) : '';
        $time_day_0 = get_post_meta( $form_id, 'time_day_0', true ) != '' ? get_post_meta( $form_id, 'time_day_0', true ) : ''; 
        $min_day_time = !empty(get_post_meta( $form_id, 'min_day_time', true )) ? get_post_meta( $form_id, 'min_day_time', true ) : '';
        $max_day_time = !empty(get_post_meta( $form_id, 'max_day_time', true )) ? get_post_meta( $form_id, 'max_day_time', true ) : '';
        $specific_date_time = !empty(get_post_meta( $form_id, 'specific_date_time', true )) ? get_post_meta( $form_id, 'specific_date_time', true ) : '';

        //Time
        $time_format_front = !empty(get_post_meta( $form_id, 'time_format_front', true )) ? get_post_meta( $form_id, 'time_format_front', true ) : 'g:ia';
        $min_time = !empty(get_post_meta( $form_id, 'min_time', true )) ? get_post_meta( $form_id, 'min_time', true ) : '';
        $max_time = !empty(get_post_meta( $form_id, 'max_time', true )) ? get_post_meta( $form_id, 'max_time', true ) : '';
        $from_dis_time = !empty(get_post_meta( $form_id, 'from_dis_time', true )) ? get_post_meta( $form_id, 'from_dis_time', true ) : '';
        $to_dis_time = !empty(get_post_meta( $form_id, 'to_dis_time', true )) ? get_post_meta( $form_id, 'to_dis_time', true ) : '';
        $time_one_step = !empty(get_post_meta( $form_id, 'time_one_step', true )) ? get_post_meta( $form_id, 'time_one_step', true ) : '30';
        $time_two_step = !empty(get_post_meta( $form_id, 'time_two_step', true )) ? get_post_meta( $form_id, 'time_two_step', true ) : '';

      
        $booking_time = array(
            'hydra_allowed_time' => esc_html($hydra_allowed_time),
            'time_day_1' => esc_html($time_day_1),
            'time_day_2' => esc_html($time_day_2),
            'time_day_3' => esc_html($time_day_3),
            'time_day_4' => esc_html($time_day_4),
            'time_day_5' => esc_html($time_day_5),
            'time_day_6' => esc_html($time_day_6),
            'time_day_0' => esc_html($time_day_0),
            'min_day_time' => esc_html($min_day_time),
            'max_day_time' => esc_html($max_day_time),
            'specific_date_time' => esc_html($specific_date_time),
            'time_format_front' => esc_html($time_format_front),
            'min_time' => esc_html($min_time),
            'max_time' => esc_html($max_time),
            'from_dis_time' => esc_html($from_dis_time),
            'to_dis_time' => esc_html($to_dis_time),
            'time_one_step' => esc_html($time_one_step),
            'time_two_step' => esc_html($time_two_step),
            
        );


        $validation_error = wpcf7_get_validation_error( $tag->name );
    
        $class = wpcf7_form_controls_class( $tag->type );
    
        $class .= ' wpcf7-validates-as-date';
    
        if ( $validation_error ) {
            $class .= ' wpcf7-not-valid';
        }
    
        $atts = array();
    
        $atts['class'] = $tag->get_class_option( $class );
        $atts['class'] .= ' hydra-form-input-time';
        $atts['time-data'] =  wp_json_encode($booking_time);
        $atts['id'] = $tag->name; 
        $atts['data-date'] = '0';
        $atts['data-time-min'] = '0';
        $atts['data-time-max'] = '0';
        $atts['min'] = $tag->get_date_option( 'min' );
        $atts['max'] = $tag->get_date_option( 'max' );
    
        if ( $tag->is_required() ) {
            $atts['aria-required'] = 'true';
        }
    
        if ( $validation_error ) {
            $atts['aria-invalid'] = 'true';
            $atts['aria-describedby'] = wpcf7_get_validation_error_reference(
                $tag->name
            );
        } else {
            $atts['aria-invalid'] = 'false';
        }
    
        $value = (string) reset( $tag->values );
    
        $value = $tag->get_default_option( $value );
    
        if ( $value ) {
            $datetime_obj = date_create_immutable(
                preg_replace( '/[_]+/', ' ', $value ),
                wp_timezone()
            );
    
            if ( $datetime_obj ) {
                $value = $datetime_obj->format( 'g:ia' );
            }
        }
    
        $value = wpcf7_get_hangover( $tag->name, $value );
    
        $atts['value'] = $value;
    
        if ( wpcf7_support_html5() ) {
            $atts['type'] = $tag->basetype;
        } else {
            $atts['type'] = 'text';
        }
    
        $atts['name'] = $tag->name;
    
        $atts = wpcf7_format_atts( $atts );

        // Showing time zone
        $timezone_string = get_option( 'timezone_string' );
        $offset  = (float) get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = ( $offset - $hours );   
        $sign      = ( $offset < 0 ) ? '-' : '+';
        $abs_hour  = abs( $hours );
        $abs_mins  = abs( $minutes * 60 );
        $tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
        $timezone = $timezone_string ? $timezone_string. ' [' .$tz_offset. ']' : $tz_offset;
    
        $html = sprintf(
            '<span class="wpcf7-form-control-wrap %1$s"><input %2$s autocomplete="off" />%3$s</span>',
            sanitize_html_class( $tag->name ), $atts, $validation_error
        );

        $html .= __( "Timezone: ", "hydra-booking" ). '' .$timezone;
    
        return $html;
    }
 

    /**
     * Custom Validation Filter
     * modified from contact form 7
     * 
     * @since 1.0
     */

    public function tfhb_date_validation_filter( $result, $tag ) {  
        if( isset($_POST['_tfhb_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_tfhb_nonce'] ) ), 'update' ) ){
            $name = $tag->name;

            $value = isset( $_POST[$name] )
                ? trim( strtr( (string) sanitize_text_field($_POST[$name]), "\n", " " ) )
                : '';

            if ( $tag->is_required() and '' === $value ) {
                $result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
            } 

        } 
        return $result;
        
    }

    // Custom Validation Filter
    public function tfhb_time_validation_filter( $result, $tag ) {
        if( isset($_POST['_tfhb_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_tfhb_nonce'] ) ), '_tfhb_nonce' ) ){
            $name = $tag->name;
    
            $value = isset( $_POST[$name] )
                ? trim( strtr( (string) esc_html($_POST[$name]), "\n", " " ) )
                : '';
        
            if ( $value ) {
                $datetime_obj = date_create_immutable(
                    preg_replace( '/[_]+/', ' ', $value ),
                    wp_timezone()
                );
        
                if ( $datetime_obj ) {
                    $value = $datetime_obj->format( 'g:ia' );
                }
            }
        
            if ( $tag->is_required() and '' === $value ) {
                $result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
            } elseif ( '' !== $value and ! $this->tfhb_is_time( $value, 'g:ia' ) ) {
                $result->invalidate( $tag, wpcf7_get_message( 'invalid_time' ) );
            }
        }
       
    
        return $result;
    }

    // Validation message for time
    public function tfhb_time_messages( $messages ) {
        return array_merge( $messages, array(
            'invalid_time' => array(
                'description' => __( "Time format that the sender entered is invalid", 'hydra-booking' ),
                'default' => __( "The time format is incorrect.", 'hydra-booking' ),
            ),
        ) );
    }
    
    public function tfhb_is_time(string $date, string $format = 'Y-m-d') {
        $dateObj = DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
    }

    /**
     * Add form tag generator
     * 
     * @since 1.0
     */
    public function tag_generator() {
        wpcf7_add_tag_generator( 'tfhb_booking_form_date', __( 'Hydra Booking Date', 'hydra-booking' ), 'thb-tg-pane-booking_form_date', array($this, 'tg_panel_booking_form_date') );
        wpcf7_add_tag_generator( 'tfhb_booking_form_time', __( 'Hydra Booking Time', 'hydra-booking' ), 'thb-tg-pane-booking_form_time', array($this, 'tg_panel_booking_form_time') );
    }

    /**
     * Form tag generator handler
     * tfhb_booking_form_date
     * 
     * @since 1.0
     */
    public function tg_panel_booking_form_date( $cf, $args = '' ) {
        $args = wp_parse_args( $args, array() );
        $tfhb_field_type = 'tfhb_booking_form_date';
        ?>
        <div class="control-box">
            <fieldset>
                <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Field Type', 'hydra-booking' );?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php esc_html_e( 'Field Type', 'hydra-booking' );?></legend>
                                <label><input type="checkbox" name="required" value="on"><?php esc_html_e( 'Required Field', 'hydra-booking' );?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php esc_html_e( 'Name', 'hydra-booking' ); ?></label></th>
                        <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                    </tr>           
                    
                </tbody>
                </table>
                 
                
            </fieldset>
        </div>
        <div class="insert-box">
            <input type="text" name="<?php echo esc_attr( $tfhb_field_type ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
            <div class="submitbox">
                <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr_e( 'Insert Tag', 'hydra-booking' ); ?>" />
            </div>
        </div>
        <?php
    }

    /**
     * Form tag generator handler
     * tfhb_booking_form_time
     * 
     * @since 1.0
     */
    public function tg_panel_booking_form_time( $cf, $args = '' ) {
        $args = wp_parse_args( $args, array() );
        $tfhb_field_type = 'tfhb_booking_form_time';
        ?>
        <div class="control-box">
            <fieldset>
                <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Field Type', 'hydra-booking' );?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php esc_html_e( 'Field Type', 'hydra-booking' );?></legend>
                                <label><input type="checkbox" name="required" value="on"><?php esc_html_e( 'Required Field', 'hydra-booking' );?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html_e( 'Name', 'hydra-booking' ) ; ?></label></th>
                        <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                    </tr>           
                    
                </tbody>
                </table> 
                 
            </fieldset>
        </div>
        <div class="insert-box">
            <input type="text" name="<?php echo esc_attr( $tfhb_field_type ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
            <div class="submitbox">
                <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr_e( 'Insert Tag', 'hydra-booking' ); ?>" />
            </div>
        </div>
        <?php
    }

    /**
     * Create tab panel
     * 
     * @since 1.0
     */

    public function tfhb_hydra_add_panel( $panels ) {
        $panels['thb-hydra-panel'] = array(
            'title'    => __( 'Hydra Booking Option', 'hydra-booking' ),
            'callback' => array($this, 'tfhb_create_hydra_panel_fields'),
        );
        return $panels;
    }

    /**
     * Tab panel fields
     * 
     * @since 1.0
     */
    public  function tfhb_create_hydra_panel_fields( $post ) {

    // get saved value      
    $hydra_enable = !empty(get_post_meta( $post->id(), 'hydra_enable', true )) ? get_post_meta( $post->id(), 'hydra_enable', true ) : '';
    $hydra_duplicate_status = !empty(get_post_meta( $post->id(), 'hydra_duplicate_status', true )) ? get_post_meta( $post->id(), 'hydra_duplicate_status', true ) : '';

    // Frontend values
    $date_mode_front = !empty(get_post_meta( $post->id(), 'date_mode_front', true )) ? get_post_meta( $post->id(), 'date_mode_front', true ) : 'single';
    $hydra_date_theme = !empty(get_post_meta( $post->id(), 'hydra_date_theme', true )) ? get_post_meta( $post->id(), 'hydra_date_theme', true ) : '';

    // Allowed dates
    $hydra_allowed_date = !empty(get_post_meta( $post->id(), 'hydra_allowed_date', true )) ? get_post_meta( $post->id(), 'hydra_allowed_date', true ) : 'always';
    $allowed_start_date = !empty(get_post_meta( $post->id(), 'allowed_start_date', true )) ? get_post_meta( $post->id(), 'allowed_start_date', true ) : '';
    $allowed_end_date = !empty(get_post_meta( $post->id(), 'allowed_end_date', true )) ? get_post_meta( $post->id(), 'allowed_end_date', true ) : '';
    $allowed_specific_date = !empty(get_post_meta( $post->id(), 'allowed_specific_date', true )) ? get_post_meta( $post->id(), 'allowed_specific_date', true ) : '';
    $min_date = !empty(get_post_meta( $post->id(), 'min_date', true )) ? get_post_meta( $post->id(), 'min_date', true ) : '';
    $max_date = !empty(get_post_meta( $post->id(), 'max_date', true )) ? get_post_meta( $post->id(), 'max_date', true ) : '';
    // Disabled dates
    $disable_day_1 = !empty(get_post_meta( $post->id(), 'disable_day_1', true )) ? get_post_meta( $post->id(), 'disable_day_1', true ) : '';
    $disable_day_2 = !empty(get_post_meta( $post->id(), 'disable_day_2', true )) ? get_post_meta( $post->id(), 'disable_day_2', true ) : '';
    $disable_day_3 = !empty(get_post_meta( $post->id(), 'disable_day_3', true )) ? get_post_meta( $post->id(), 'disable_day_3', true ) : '';
    $disable_day_4 = !empty(get_post_meta( $post->id(), 'disable_day_4', true )) ? get_post_meta( $post->id(), 'disable_day_4', true ) : '';
    $disable_day_5 = !empty(get_post_meta( $post->id(), 'disable_day_5', true )) ? get_post_meta( $post->id(), 'disable_day_5', true ) : '';
    $disable_day_6 = !empty(get_post_meta( $post->id(), 'disable_day_6', true )) ? get_post_meta( $post->id(), 'disable_day_6', true ) : '';
    $disable_day_0 =  get_post_meta( $post->id(), 'disable_day_0', true ) != '' ? get_post_meta( $post->id(), 'disable_day_0', true ) : '';
    $disabled_start_date = !empty(get_post_meta( $post->id(), 'disabled_start_date', true )) ? get_post_meta( $post->id(), 'disabled_start_date', true ) : '';
    $disabled_end_date = !empty(get_post_meta( $post->id(), 'disabled_end_date', true )) ? get_post_meta( $post->id(), 'disabled_end_date', true ) : '';
    $disabled_specific_date = !empty(get_post_meta( $post->id(), 'disabled_specific_date', true )) ? get_post_meta( $post->id(), 'disabled_specific_date', true ) : '';

    // // Allowed Times 
    $hydra_allowed_time = !empty(get_post_meta( $post->id(), 'hydra_allowed_time', true )) ? get_post_meta( $post->id(), 'hydra_allowed_time', true ) : 'always';
    $time_day_1 = !empty(get_post_meta( $post->id(), 'time_day_1', true )) ? get_post_meta( $post->id(), 'time_day_1', true ) : '';
    $time_day_2 = !empty(get_post_meta( $post->id(), 'time_day_2', true )) ? get_post_meta( $post->id(), 'time_day_2', true ) : '';
    $time_day_3 = !empty(get_post_meta( $post->id(), 'time_day_3', true )) ? get_post_meta( $post->id(), 'time_day_3', true ) : '';
    $time_day_4 = !empty(get_post_meta( $post->id(), 'time_day_4', true )) ? get_post_meta( $post->id(), 'time_day_4', true ) : '';
    $time_day_5 = !empty(get_post_meta( $post->id(), 'time_day_5', true )) ? get_post_meta( $post->id(), 'time_day_5', true ) : '';
    $time_day_6 = !empty(get_post_meta( $post->id(), 'time_day_6', true )) ? get_post_meta( $post->id(), 'time_day_6', true ) : ''; 
    $time_day_0 =  get_post_meta( $post->id(), 'time_day_0', true ) != '' ? get_post_meta( $post->id(), 'time_day_0', true ) : '';

    $min_day_time = !empty(get_post_meta( $post->id(), 'min_day_time', true )) ? get_post_meta( $post->id(), 'min_day_time', true ) : ''; 
    $max_day_time = !empty(get_post_meta( $post->id(), 'max_day_time', true )) ? get_post_meta( $post->id(), 'max_day_time', true ) : ''; 
    $specific_date_time = !empty(get_post_meta( $post->id(), 'specific_date_time', true )) ? get_post_meta( $post->id(), 'specific_date_time', true ) : ''; 
    // Time
    $time_format_front = !empty(get_post_meta( $post->id(), 'time_format_front', true )) ? get_post_meta( $post->id(), 'time_format_front', true ) : '';
    $min_time = !empty(get_post_meta( $post->id(), 'min_time', true )) ? get_post_meta( $post->id(), 'min_time', true ) : '';
    $max_time = !empty(get_post_meta( $post->id(), 'max_time', true )) ? get_post_meta( $post->id(), 'max_time', true ) : '';
    $from_dis_time = !empty(get_post_meta( $post->id(), 'from_dis_time', true )) ? get_post_meta( $post->id(), 'from_dis_time', true ) : '';
    $to_dis_time = !empty(get_post_meta( $post->id(), 'to_dis_time', true )) ? get_post_meta( $post->id(), 'to_dis_time', true ) : '';
    $time_one_step = !empty(get_post_meta( $post->id(), 'time_one_step', true )) ? get_post_meta( $post->id(), 'time_one_step', true ) : '';
    $time_two_step = !empty(get_post_meta( $post->id(), 'time_two_step', true )) ? get_post_meta( $post->id(), 'time_two_step', true ) : '';
     

    // WooCommerce
    $hydra_woo = !empty(get_post_meta( $post->id(), 'hydra_woo', true )) ? get_post_meta( $post->id(), 'hydra_woo', true ) : '';
    $hydra_product = !empty(get_post_meta( $post->id(), 'hydra_product', true )) ? get_post_meta( $post->id(), 'hydra_product', true ) : '';
    $hydra_product_id = !empty(get_post_meta( $post->id(), 'hydra_product_id', true )) ? get_post_meta( $post->id(), 'hydra_product_id', true ) : '';
    $hydra_product_name = !empty(get_post_meta( $post->id(), 'hydra_product_name', true )) ? get_post_meta( $post->id(), 'hydra_product_name', true ) : '';
    $hydra_product_price = !empty(get_post_meta( $post->id(), 'hydra_product_price', true )) ? get_post_meta( $post->id(), 'hydra_product_price', true ) : ''; 
    // Event Calendar Issue : 
    $calendar_event_enable = !empty(get_post_meta( $post->id(), 'calendar_event_enable', true )) ? get_post_meta( $post->id(), 'calendar_event_enable', true ) : '';
    $event_email = !empty(get_post_meta( $post->id(), 'event_email', true )) ? get_post_meta( $post->id(), 'event_email', true ) : '';
    $event_date = !empty(get_post_meta( $post->id(), 'event_date', true )) ? get_post_meta( $post->id(), 'event_date', true ) : '';
    $event_time = !empty(get_post_meta( $post->id(), 'event_time', true )) ? get_post_meta( $post->id(), 'event_time', true ) : '';
    $event_summary = !empty(get_post_meta( $post->id(), 'event_summary', true )) ? get_post_meta( $post->id(), 'event_summary', true ) : '';

    ?>
    <div class="ultimate-hydra-admin">
        <h1><?php esc_html_e( 'Hydra Booking Option', 'hydra-booking' ); ?></h1>
      
         
        <fieldset> 
            <div class="main-block">
                <div class="sub-block">
                    <h3><?php esc_html_e( 'Enable/Disable Booking Form', 'hydra-booking' ); ?></h3>
                    <label for="hydra-enable">
                        <input class="hydra-enable" id="hydra_enable" name="hydra_enable" type="checkbox" value="1" <?php checked( '1', $hydra_enable, true ); ?>> <?php esc_html_e( 'Enable Booking Form', 'hydra-booking' ); ?>
                    </label>
                </div>
            </div> 
            <div class="main-block">
                <div class="sub-block">
                    <h3><?php esc_html_e( 'Enable/Disable Calendar Event', 'hydra-booking' ); ?></h3>
                    <label for="hydra-enable">
                        <input class="calendar-enable" id="calendar_event_enable" name="calendar_event_enable" type="checkbox" value="1" <?php checked( '1', $calendar_event_enable, true ); ?>> <?php esc_html_e( 'Enable Calendar Event', 'hydra-booking' ); ?>
                    </label>
                    <br>
                    <br>
                    <?php  $all_fields = $post->scan_form_tags(); ?>
                    <table>
                        <tr> 
                            <td>
                                <div class="sub-block"> 
                                    <label><?php esc_html_e( 'Event Email', 'hydra-booking' ); ?></label> 
                                    <select name="event_email" id="event_email">
                                        <?php
                                        $all_tags = $post->scan_form_tags(array('type' => 'email', 'type' => 'email*'));
                                        foreach ($all_tags as $tag) {
                                            echo '<option value="' . esc_attr($tag['name']) . '" ' . selected( esc_attr( $event_email ), esc_attr( $tag['name'] ) ) . '>' . esc_attr($tag['name']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </td> 
                            <td>
                                <div class="sub-block"> 
                                    <label><?php esc_html_e( 'Event Summary', 'hydra-booking' ); ?></label> 
                                    <select name="event_summary" id="event_summary">
                                        <?php
                                        foreach ($all_fields as $tag) {
                                            if ($tag['type'] != 'submit') {
                                                echo '<option value="' . esc_attr($tag['name']) . '" ' . selected( esc_attr( $event_summary ), esc_attr( $tag['name'] ) ) . '>' . esc_attr($tag['name']) . '</option>';
                                            } 
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </td> 
                            <td>
                                <div class="sub-block"> 
                                    <label><?php esc_html_e( 'Event Date', 'hydra-booking' ); ?></label> 
                                    <select name="event_date" id="event_date">
                                        <?php
                                        foreach ($all_fields as $tag) {
                                            if ($tag['type'] != 'submit') {
                                                echo '<option value="' . esc_attr($tag['name']) . '" ' . selected( esc_attr( $event_date ), esc_attr ( $tag['name'] ) ) . '>' . esc_attr($tag['name']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </td> 
                            <td>
                                <div class="sub-block"> 
                                    <label><?php esc_html_e( 'Event Time', 'hydra-booking' ); ?></label> 
                                    <select name="event_time" id="event_time">
                                        <?php
                                        foreach ($all_fields as $tag) {
                                            if ($tag['type'] != 'submit') {
                                            echo '<option value="' . esc_attr($tag['name']) . '" ' . selected( esc_attr( $event_time ), esc_attr( $tag['name'] ) ) . '>' . esc_attr($tag['name']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </td> 
                        </tr>
                    </table>
                </div>
            </div>
                        
            
            
            <h2><?php esc_html_e( 'Date Configuration', 'hydra-booking' ); ?></h2>

            <div class="main-block">
                <h3><?php esc_html_e( 'Date Settings for Frontend', 'hydra-booking' ); ?></h3>
                <div class="sub-block">
                    <h4><?php esc_html_e( 'Date Selection Mode', 'hydra-booking' ); ?></h4>
                    <label><input type="radio" name="date_mode_front" class="" id="" value="single" <?php if ($date_mode_front == 'single'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Single date', 'hydra-booking' ); ?></label> 
                    <label><input type="radio" name="date_mode_front" class="" id="" value="range" <?php if ($date_mode_front == 'range'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Range of date', 'hydra-booking' ); ?></label>
                </div>

                <div class="sub-block">
                    <h4><?php esc_html_e( 'Calendar Theme', 'hydra-booking' ); ?></h4>
                    <label><?php esc_html_e( 'Select Theme', 'hydra-booking' ); ?></label> 
                    <select name="hydra_date_theme" id="">
                        <option value="default" <?php selected("default", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Default', 'hydra-booking' ); ?></option>
                        <option value="dark" <?php selected("dark", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Dark', 'hydra-booking' ); ?></option>
                        <option value="material_blue" <?php selected("material_blue", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Material Blue', 'hydra-booking' ); ?></option>
                        <option value="material_green" <?php selected("material_green", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Material Green', 'hydra-booking' ); ?></option>
                        <option value="material_red" <?php selected("material_red", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Material Red', 'hydra-booking' ); ?></option>
                        <option value="material_orange" <?php selected("material_orange", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Material Orange', 'hydra-booking' ); ?></option>
                        <option value="airbnb" <?php selected("airbnb", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Airbnb', 'hydra-booking' ); ?></option>
                        <option value="confetti" <?php selected("confetti", esc_attr($hydra_date_theme)); ?>><?php esc_html_e( 'Confetti', 'hydra-booking' ); ?></option>
                    </select>
                </div>
            </div>

            <div class="main-block">
                <h3><?php esc_html_e( 'Allowed Dates', 'hydra-booking' ); ?></h3> 
                <div class="sub-block">
                    <h4><?php esc_html_e( 'Allowed Dates', 'hydra-booking' ); ?></h4>
                    <label for=""><input type="radio" name="hydra_allowed_date" class="" id="hydra-date-always" value="always" <?php if ($hydra_allowed_date == 'always'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Always', 'hydra-booking' ); ?></label>
                    <label for=""><input type="radio" name="hydra_allowed_date" class="" id="hydra-date-range" value="range" <?php if ($hydra_allowed_date == 'range'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Ranges', 'hydra-booking' ); ?></label>
                    <label for=""><input type="radio" name="hydra_allowed_date" class="" id="hydra-date-specific" value="specific" <?php if ($hydra_allowed_date == 'specific'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Specific', 'hydra-booking' ); ?></label>
                </div>

                <div class="sub-block allowed-date-range">
                    <h4><?php esc_html_e( 'Select Minimum & Maximum Date', 'hydra-booking' ); ?></h4>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="min-date"><?php esc_html_e( 'From', 'hydra-booking' ); ?><br>
                            <input type="text" name="min_date" class="min-date" id="min-date" placeholder="" autocomplete="off"  value="<?php echo esc_html($min_date) ?>"/>
                            </label>
                        </p>
                    </div>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="max-date"><?php esc_html_e( 'To', 'hydra-booking' ); ?><br>
                            <input type="text" name="max_date" class="max-date" id="max-date" placeholder="" autocomplete="off" value="<?php echo esc_html($max_date) ?>" />
                            </label>
                        </p>
                    </div>
                </div>

                <div class="sub-block allowed-specific-date">
                    <h4><?php esc_html_e( 'Specific Date', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="allowed-specific-date">
                        <input type="text" name="allowed_specific_date" class="allowed-specific-date large-text" id="allowed-specific-date" placeholder="" autocomplete="off" value="<?php echo esc_html( $allowed_specific_date ); ?>"/>
                        </label>
                    </p>
                </div>                    
            </div>

            <div class="main-block cond-disabled-date">
                <h3><?php esc_html_e( 'Disabled Dates', 'hydra-booking' ); ?></h3> 
                <div class="sub-block">
                    <h4><?php esc_html_e( 'Select day to disable', 'hydra-booking' );?></h4>
                    <label for="disable-day-1"><input class="" id="disable-day-1" name="disable_day_1" type="checkbox" value="1" <?php checked( '1', esc_attr($disable_day_1), true ); ?>> <?php esc_html_e( 'Monday', 'hydra-booking' ); ?></label>
                    <label for="disable-day-2"><input class="" id="disable-day-2" name="disable_day_2" type="checkbox" value="2" <?php checked( '2', esc_attr($disable_day_2), true ); ?>> <?php esc_html_e( 'Tuesday', 'hydra-booking' ); ?></label>
                    <label for="disable-day-3"><input class="" id="disable-day-3" name="disable_day_3" type="checkbox" value="3" <?php checked( '3', esc_attr($disable_day_3), true ); ?>> <?php esc_html_e( 'Wednesday', 'hydra-booking' ); ?></label>
                    <label for="disable-day-4"><input class="" id="disable-day-4" name="disable_day_4" type="checkbox" value="4" <?php checked( '4', esc_attr($disable_day_4), true ); ?>> <?php esc_html_e( 'Thursday', 'hydra-booking' ); ?></label>
                    <label for="disable-day-5"><input class="" id="disable-day-5" name="disable_day_5" type="checkbox" value="5" <?php checked( '5', esc_attr($disable_day_5), true ); ?>> <?php esc_html_e( 'Friday', 'hydra-booking' ); ?></label>
                    <label for="disable-day-6"><input class="" id="disable-day-6" name="disable_day_6" type="checkbox" value="6" <?php checked( '6', esc_attr($disable_day_6), true ); ?>> <?php esc_html_e( 'Saturday', 'hydra-booking' ); ?></label>
                    <label for="disable-day-0"><input class="" id="disable-day-0" name="disable_day_0" type="checkbox" value="0" <?php checked( '0', esc_attr($disable_day_0), true ); ?>> <?php esc_html_e( 'Sunday', 'hydra-booking' ); ?></label>
                </div>

                <div class="sub-block ">
                    <h4><?php esc_html_e( 'Select a date range to disable', 'hydra-booking' ); ?></h4>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="disabled-start-date"><?php esc_html_e( 'From', 'hydra-booking' ); ?><br>
                            <input type="text" name="disabled_start_date" class="disabled-start-date" id="disabled-start-date" placeholder="" autocomplete="off"  value="<?php echo esc_html($disabled_start_date); ?>" />
                            </label>
                        </p>
                    </div>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="disabled-end-date"><?php esc_html_e( 'To', 'hydra-booking' ); ?><br>
                            <input type="text" name="disabled_end_date" class="disabled-end-date" id="disabled-end-date" placeholder="" autocomplete="off" value = "<?php echo esc_html($disabled_end_date) ?>"/>
                            </label>
                        </p>
                    </div>
                </div>

                <div class="sub-block">
                    <h4><?php esc_html_e( 'Disable Specific Dates', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="disabled-specific-date">
                        <input type="text" name="disabled_specific_date" class="disabled-specific-date large-text" id="disabled-specific-date" placeholder="" autocomplete="off" value="<?php echo esc_html($disabled_specific_date); ?>"/>
                        </label>
                    </p>
                </div>
            </div>

            <h2><?php esc_html_e( 'Time Configuration', 'hydra-booking' ); ?></h2>
            
            <div class="main-block">
                <h3><?php esc_html_e( 'Time Settings', 'hydra-booking' ); ?></h3>
                <div class="sub-block">
                    <h4><?php esc_html_e( 'Time Format for Frontend', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="time-format-front">
                        <input type="text" name="time_format_front" class="" id="time-format-front" placeholder="g:ia" autocomplete="off" data="<?php echo esc_html($time_format_front); ?>" /><br>
                    
                        <div class="thb-doc-notice">
                            <?php 
                                  printf( 
                                    /* translators: %s: Link */
                                    esc_html__( 'Default: g:ia . For 24 hours format use H:i . You can find more format %s.', 'hydra-booking' ),
                                    '<a href="'.esc_url ('https://www.php.net/manual/en/function.date.php').'" target="_blank">here</a>'
                                  );
                            ?> 
                        </div>
                        
                        </label>
                    </p>
                </div>
                <div class="sub-block">                    
                    <h4><?php esc_html_e( 'Select start & end time limit', 'hydra-booking' ); ?></h4>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="min-time"><?php esc_html_e('Min', 'hydra-booking') ?><br>
                            <input type="text" name="min_time" class="" id="min-time" placeholder="" autocomplete="off" value="<?php echo esc_html($min_time); ?>"/>
                            </label>
                        </p>
                    </div>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="max-time"><?php esc_html_e('Max', 'hydra-booking') ?><br>
                            <input type="text" name="max_time" class="" id="max-time" placeholder="" autocomplete="off" value="<?php echo esc_html($max_time); ?>"/>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="sub-block">                    
                    <h4><?php esc_html_e( 'Disable Time Range', 'hydra-booking' ); ?></h4>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="from-dis-time"><?php esc_html_e('From', 'hydra-booking') ?><br>
                            <input type="text" name="from_dis_time" class="" id="from-dis-time" placeholder="" autocomplete="off" <?php echo !empty($from_dis_time) ? 'value="' . esc_html($from_dis_time) . '"' : ''; ?>/>
                            </label>
                        </p>
                    </div>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="to-dis-time"><?php  esc_html_e('To', 'hydra-booking') ?><br>
                            <input type="text" name="to_dis_time" class="" id="to-dis-time" placeholder="" autocomplete="off" <?php echo !empty($to_dis_time) ? 'value="' . esc_html($to_dis_time) . '"' : ''; ?>/>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="sub-block">                    
                    <h4><?php esc_html_e( 'Time Interval', 'hydra-booking' ); ?></h4>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="time-one-step"><?php  esc_html_e('Time Duration', 'hydra-booking') ?><br>
                            <input type="text" name="time_one_step" class="" id="time-one-step" placeholder="" autocomplete="off" value="<?php echo esc_html($time_one_step); ?>"/><br>
                           <?php esc_html_e(' Default: 30', 'hydra-booking') ?>
                            </label>
                        </p>
                    </div>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="time-two-step"><?php  esc_html_e('Time Break', 'hydra-booking') ?><br>
                            <input type="text" name="time_two_step" class="" id="time-two-step" placeholder="" autocomplete="off" value="<?php echo esc_html($time_two_step); ?>"/><br>
                            <?php  esc_html_e('Default: Blank', 'hydra-booking') ?>
                            </label>
                        </p>
                    </div>
                </div>
            </div>
            <div class="main-block">
                <h3><?php esc_html_e( 'Allowed Time', 'hydra-booking' ); ?></h3> 
                <div class="sub-block">
                    <h4><?php esc_html_e( 'Allowed Dates', 'hydra-booking' ); ?></h4>
                    <label for=""><input type="radio" name="hydra_allowed_time" class="" id="hydra-time-always" value="always" <?php if ($hydra_allowed_time == 'always'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Always', 'hydra-booking' ); ?></label>
                    <label for=""><input type="radio" name="hydra_allowed_time" class="" id="hydra-time-day" value="day" <?php if ($hydra_allowed_time == 'day'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Day', 'hydra-booking' ); ?></label>
                    <label for=""><input type="radio" name="hydra_allowed_time" class="" id="hydra-time-specific" value="specific" <?php if ($hydra_allowed_time == 'specific'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Specific', 'hydra-booking' ); ?></label>
                </div>
                <div class="sub-block allowed-day-time-date">                    
                        <h4><?php esc_html_e( 'Select day', 'hydra-booking' ); ?></h4>
                        <label for="time-day-1"><input class="" id="time-day-1" name="time_day_1" type="checkbox" value="1" <?php checked( '1', esc_attr($time_day_1), true ); ?>> <?php esc_html_e( 'Monday', 'hydra-booking' ); ?></label>
                        <label for="time-day-2"><input class="" id="time-day-2" name="time_day_2" type="checkbox" value="2" <?php checked( '2', esc_attr($time_day_2), true ); ?>> <?php esc_html_e( 'Tuesday', 'hydra-booking' ); ?></label>
                        <label for="time-day-3"><input class="" id="time-day-3" name="time_day_3" type="checkbox" value="3" <?php checked( '3', esc_attr($time_day_3), true ); ?>> <?php esc_html_e( 'Wednesday', 'hydra-booking' ); ?></label>
                        <label for="time-day-4"><input class="" id="time-day-4" name="time_day_4" type="checkbox" value="4" <?php checked( '4', esc_attr($time_day_4), true ); ?>> <?php esc_html_e( 'Thursday', 'hydra-booking' ); ?></label>
                        <label for="time-day-5"><input class="" id="time-day-5" name="time_day_5" type="checkbox" value="5" <?php checked( '5', esc_attr($time_day_5), true ); ?>> <?php esc_html_e( 'Friday', 'hydra-booking' ); ?></label>
                        <label for="time-day-6"><input class="" id="time-day-6" name="time_day_6" type="checkbox" value="6" <?php checked( '6', esc_attr($time_day_6), true ); ?>> <?php esc_html_e( 'Saturday', 'hydra-booking' ); ?></label>
                        <label for="time-day-0"><input class="" id="time-day-0" name="time_day_0" type="checkbox" value="0" <?php checked( '0', esc_attr($time_day_0), true ); ?>> <?php esc_html_e( 'Sunday', 'hydra-booking' ); ?></label> 
                </div>

                <div class="sub-block allowed-specific-time-date">
                    <h4><?php esc_html_e( 'Specific Dates', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="specific-date-time">
                        <input type="text" name="specific_date_time" class="specific-date-time large-text" id="specific-date-time" placeholder="" autocomplete="off" value="<?php echo esc_html($specific_date_time); ?>"/>
                        </label>
                    </p>
                </div>  
                <div class="sub-block allowed-time-date">
                <h4><?php esc_html_e( 'Select start & end time limit', 'hydra-booking' ); ?></h4>
                    
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="min-day-time"><?php echo esc_html_e('Min', 'hydra-booking') ?><br>
                            <input type="text" name="min_day_time" class="" id="min-day-time" placeholder="" autocomplete="off" value="<?php esc_html($min_day_time); ?>"/>
                            </label>
                        </p>
                    </div>
                    <div class="hydra-inline">
                        <p class="description">
                            <label for="max-day-time"><?php echo esc_html_e('Max', 'hydra-booking') ?><br>
                            <input type="text" name="max_day_time" class="" id="max-day-time" placeholder="" autocomplete="off" value ="<?php echo esc_html($max_day_time) ?>"/>
                            </label>
                        </p>
                    </div>
                </div>  
            </div>
            <!-- Completed For To Day -->
            <h2><?php esc_html_e( 'WooCommerce Configuration', 'hydra-booking' ); ?></h2>
            <?php 
                if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
                    $woo_activation = false;
                } else{
                    $woo_activation = true;
                }
                
            ?>
            
            <div class="main-block">
                <div class="sub-block">
                    <h3><?php esc_html_e( 'Enable/Disable WooCommerce Integration', 'hydra-booking' ); ?></h3>
                    <label for="hydra-woo">
                        <input class="" <?php if($woo_activation == false){echo "disabled"; } ?>  id="hydra-woo" name="hydra_woo" type="checkbox" value="1" <?php checked( '1', $hydra_woo, true ); ?>> <?php esc_html_e( 'Enable WooCommerce', 'hydra-booking' ); ?> 
                        <?php  if($woo_activation == false){
                            echo ' <a style="color:red" target="_blank" href="'. esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) .'">
                            '.esc_html( __("( WooCommerce need to active )", "hydra-booking") ).'
                            </a>';
                        } ?> 
                    </label>
                </div>

                <div class="sub-block cond-product-conf">
                    <h4><?php esc_html_e( 'Select Product', 'hydra-booking' ); ?></h4>
                    <label for="hydra-product-exist"><input type="radio" name="hydra_product" class="" id="hydra-product-exist" value="exist" <?php if ($hydra_product == 'exist'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Existing Product', 'hydra-booking' ); ?></label>
                    <label for="hydra-product-custom"><input type="radio" name="hydra_product" class="" id="hydra-product-custom" value="custom" <?php if ($hydra_product == 'custom'){ esc_attr_e('checked'); } ?>/> <?php esc_html_e( 'Custom Product', 'hydra-booking' ); ?></label>
                </div>
                <div class="sub-block product-exist">
                    <h4><?php esc_html_e( 'Product ID', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="hydra-product-id">
                        <input type="text" name="hydra_product_id" class="" id="hydra-product-id" placeholder="" autocomplete="off" <?php echo !empty($hydra_product_id) ? 'value="' . esc_html($hydra_product_id) . '"' : ''; ?>/><br>
                        <?php echo esc_html_e('Only one product id is allowed', 'hydra-booking') ?>
                        </label>
                    </p>
                </div>
                <div class="sub-block product-custom">
                    <h4><?php esc_html_e( 'Product Name', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="hydra-product-name">
                        <input type="text" name="hydra_product_name" class="large-text" id="hydra-product-name" placeholder="" autocomplete="off" <?php echo !empty($hydra_product_name) ? 'value="' . esc_html($hydra_product_name) . '"' : ''; ?>/>
                        </label>
                    </p>
                </div>
                <div class="sub-block product-custom">
                    <h4><?php esc_html_e( 'Product Price', 'hydra-booking' ); ?></h4>
                    <p class="description">
                        <label for="hydra-product-price">
                        <input type="text" name="hydra_product_price" class="" id="hydra-product-price" placeholder="" autocomplete="off" <?php echo !empty($hydra_product_price) ? 'value="' . esc_html($hydra_product_price) . '"' : ''; ?>/>
                        </label>
                    </p>
                </div>
            </div>                                          
        </fieldset>
    </div>
    <script>
        <?php 
        // Date conditional
        
            if ($hydra_allowed_date == 'always') {
                echo 'var hydra_allowed_date = "always";';
            } elseif ($hydra_allowed_date == 'range') {
                echo 'var hydra_allowed_date = "range";';
            } elseif ($hydra_allowed_date == 'specific') {
                echo 'var hydra_allowed_date = "specific";';
            } else {
                echo 'var hydra_allowed_date = "";';
            }
        
            // Time conditional
            if ($hydra_allowed_time == 'always') {
                echo 'var hydra_allowed_time = "always";';
            } elseif ($hydra_allowed_time == 'day') {
                echo 'var hydra_allowed_time = "day";';
            } elseif ($hydra_allowed_time == 'specific') {
                echo 'var hydra_allowed_time = "specific";';
            } else {
                echo 'var hydra_allowed_time = "";';
            }
        
            // WooCommerce conditional
            echo $hydra_woo ? 'var hydra_woo = "1";' : 'var hydra_woo = "0";';

            if ($hydra_product == 'exist') {
                echo 'var hydra_product = "exist";';
            } elseif ($hydra_product == 'custom') {
                echo 'var hydra_product = "custom";';
            } else {
                echo 'var hydra_product = "";';
            }
        ?> 
    </script>
    <?php

        wp_nonce_field( 'tfhb_hydra_nonce_action', 'tfhb_hydra_nonce' );
    }

    /**
     * Tab panel save values
     * 
     * @since 1.0
     */
    public function tfhb_hydra_save_contact_form( $post ) {
                
        if ( ! isset( $_POST ) || empty( $_POST ) ) {
            return;
        }
        
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tfhb_hydra_nonce'] ) ), 'tfhb_hydra_nonce_action' ) ) {
            return;
        }

        // Save values
        if( isset( $_POST['hydra_enable'] ) ) {
            update_post_meta( $post->id(), 'hydra_enable', sanitize_text_field( $_POST['hydra_enable'] ) );
        } else {
            update_post_meta( $post->id(), 'hydra_enable', '0' );
        }

        if( isset( $_POST['hydra_duplicate_status'] ) ) { 
            update_post_meta( $post->id(), 'hydra_duplicate_status', sanitize_text_field( $_POST['hydra_duplicate_status'] )  );
        }else{
            update_post_meta( $post->id(), 'hydra_duplicate_status', '0' );
        }
        
        
        // Frontend value
        if( isset( $_POST['date_mode_front'] ) ) { 
            update_post_meta( $post->id(), 'date_mode_front', sanitize_text_field( $_POST['date_mode_front'] ) );
        }else{
            update_post_meta( $post->id(), 'date_mode_front', '0' );
        }
        if( isset( $_POST['hydra_date_theme'] ) ) {  
            update_post_meta( $post->id(), 'hydra_date_theme', sanitize_text_field( $_POST['hydra_date_theme'] ) );
        }

        // Allowed dates
        if( isset( $_POST['hydra_allowed_date'] ) ) { 
            update_post_meta( $post->id(), 'hydra_allowed_date', sanitize_text_field( $_POST['hydra_allowed_date'] ) );
        }else{
            update_post_meta( $post->id(), 'hydra_allowed_date', '0' );
        }
        if( isset( $_POST['allowed_start_date'] ) ) { 
            update_post_meta( $post->id(), 'allowed_start_date', sanitize_text_field( $_POST['allowed_start_date'] ) );
        } 
        if( isset( $_POST['allowed_end_date'] ) ) { 
            update_post_meta( $post->id(), 'allowed_end_date', sanitize_text_field( $_POST['allowed_end_date'] ) );
        } 
        if( isset( $_POST['allowed_specific_date'] ) ) { 
            update_post_meta( $post->id(), 'allowed_specific_date', sanitize_text_field( $_POST['allowed_specific_date'] ) );
        } 
        if( isset( $_POST['min_date'] ) ) { 
            update_post_meta( $post->id(), 'min_date', sanitize_text_field( $_POST['min_date'] ) );
        }
        if( isset( $_POST['max_date'] ) ) { 
            update_post_meta( $post->id(), 'max_date', sanitize_text_field( $_POST['max_date'] ) );
        } 
        // Disabled dates


        if( isset( $_POST['disable_day_1'] ) ) { 
            update_post_meta( $post->id(), 'disable_day_1', sanitize_text_field( $_POST['disable_day_1'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_1', '8' );
        }
        if( isset( $_POST['disable_day_2'] ) ) { 
            update_post_meta( $post->id(), 'disable_day_2', sanitize_text_field( $_POST['disable_day_2'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_2', '8' );
        }
        if( isset( $_POST['disable_day_3'] ) ) {  
            update_post_meta( $post->id(), 'disable_day_3', sanitize_text_field( $_POST['disable_day_3'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_3', '8' );
        }
        if( isset( $_POST['disable_day_4'] ) ) { 
            update_post_meta( $post->id(), 'disable_day_4', sanitize_text_field( $_POST['disable_day_4'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_4', '8' );
        }
        if( isset( $_POST['disable_day_5'] ) ) { 
            update_post_meta( $post->id(), 'disable_day_5', sanitize_text_field( $_POST['disable_day_5'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_5', '8' );
        }
        if( isset( $_POST['disable_day_6'] ) ) { 
            update_post_meta( $post->id(), 'disable_day_6', sanitize_text_field( $_POST['disable_day_6'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_6', '8' );
        }
        if( isset( $_POST['disable_day_0'] ) ) { 
            update_post_meta( $post->id(), 'disable_day_0', sanitize_text_field( $_POST['disable_day_0'] ) );
        }else{
            update_post_meta( $post->id(), 'disable_day_0', '8' );
        }
        if( isset( $_POST['disabled_start_date'] ) ) { 
            update_post_meta( $post->id(), 'disabled_start_date', sanitize_text_field( $_POST['disabled_start_date'] ) );
        }
        if( isset( $_POST['disabled_end_date'] ) ) { 
            update_post_meta( $post->id(), 'disabled_end_date', sanitize_text_field( $_POST['disabled_end_date'] ) );
        }
        if( isset( $_POST['disabled_specific_date'] ) ) { 
            update_post_meta( $post->id(), 'disabled_specific_date', sanitize_text_field( $_POST['disabled_specific_date'] ) );
        }
        
        
        // Allowed Time  
        if( isset( $_POST['hydra_allowed_time'] ) ) { 
            update_post_meta( $post->id(), 'hydra_allowed_time', sanitize_text_field( $_POST['hydra_allowed_time'] ) );
        } 
        if( isset( $_POST['time_day_1'] ) ) { 
            update_post_meta( $post->id(), 'time_day_1', sanitize_text_field( $_POST['time_day_1'] ) );
        }else{
            update_post_meta( $post->id(), 'time_day_1', '' );
        }
        if( isset( $_POST['time_day_2'] ) ) { 
            update_post_meta( $post->id(), 'time_day_2', sanitize_text_field( $_POST['time_day_2'] ) );
        }else{
            update_post_meta( $post->id(), 'time_day_2', '' );
        }
        if( isset( $_POST['time_day_3'] ) ) { 
            update_post_meta( $post->id(), 'time_day_3', sanitize_text_field( $_POST['time_day_3'] ) );
        }else{
            update_post_meta( $post->id(), 'time_day_3', '' );
        }
        if( isset( $_POST['time_day_4'] ) ) { 
            update_post_meta( $post->id(), 'time_day_4',  sanitize_text_field( $_POST['time_day_4'] ) );
        }else{
            update_post_meta( $post->id(), 'time_day_4', '' );
        }
        if( isset( $_POST['time_day_5'] ) ) { 
            update_post_meta( $post->id(), 'time_day_5', sanitize_text_field( $_POST['time_day_5'] ) );
        } else{
            update_post_meta( $post->id(), 'time_day_5', '' );
        }
        if( isset( $_POST['time_day_6'] ) ) { 
            update_post_meta( $post->id(), 'time_day_6', sanitize_text_field( $_POST['time_day_6'] ) );
        }else{
            update_post_meta( $post->id(), 'time_day_6', '' );
        }
        if( isset( $_POST['time_day_0'] ) ) { 
            update_post_meta( $post->id(), 'time_day_0', sanitize_text_field( $_POST['time_day_0'] ) );
        } else{
            update_post_meta( $post->id(), 'time_day_0', '' );
        }
        if( isset( $_POST['min_day_time'] ) ) { 
            update_post_meta( $post->id(), 'min_day_time',sanitize_text_field(  $_POST['min_day_time'] ) );
        } else{
            update_post_meta( $post->id(), 'min_day_time', '' );
        }
        if( isset( $_POST['max_day_time'] ) ) { 
            update_post_meta( $post->id(), 'max_day_time', sanitize_text_field( $_POST['max_day_time'] ) );
        } else{
            update_post_meta( $post->id(), 'max_day_time', '' );
        }
        if( isset( $_POST['specific_date_time'] ) ) { 
            update_post_meta( $post->id(), 'specific_date_time', sanitize_text_field(  $_POST['specific_date_time'] ) );
        }else{
            update_post_meta( $post->id(), 'specific_date_time', '' );
        }

        // Time
        if( isset( $_POST['time_format_front'] ) ) { 
            update_post_meta( $post->id(), 'time_format_front', sanitize_text_field(  $_POST['time_format_front'] ) );
        }
        if( isset( $_POST['min_time'] ) ) { 
            update_post_meta( $post->id(), 'min_time', sanitize_text_field(  $_POST['min_time'] ) );
        }  
        if( isset( $_POST['max_time'] ) ) { 
            update_post_meta( $post->id(), 'max_time', sanitize_text_field( $_POST['max_time'] ) );
        } 
        if( isset( $_POST['from_dis_time'] ) ) { 
            update_post_meta( $post->id(), 'from_dis_time', sanitize_text_field( $_POST['from_dis_time'] ) );
        }
        if( isset( $_POST['to_dis_time'] ) ) { 
            update_post_meta( $post->id(), 'to_dis_time', sanitize_text_field( $_POST['to_dis_time']  ));
        } 
        if( isset( $_POST['time_one_step'] ) ) { 
            update_post_meta( $post->id(), 'time_one_step', sanitize_text_field( $_POST['time_one_step'] ));
        } 
        if( isset( $_POST['time_two_step'] ) ) { 
            update_post_meta( $post->id(), 'time_two_step', sanitize_text_field( $_POST['time_two_step'] ) );
        }


        // WooCommerce
        if( isset( $_POST['hydra_woo'] ) ) { 
            update_post_meta( $post->id(), 'hydra_woo', sanitize_text_field( $_POST['hydra_woo'] ) );
        }else{
            update_post_meta( $post->id(), 'hydra_woo', 'off' );
        }
        if( isset( $_POST['hydra_product'] ) ) { 
            update_post_meta( $post->id(), 'hydra_product', sanitize_text_field( $_POST['hydra_product'] ) );
        } 
        if( isset( $_POST['hydra_product_id'] ) ) { 
            update_post_meta( $post->id(), 'hydra_product_id', sanitize_text_field( $_POST['hydra_product_id'] ) );
        } 
        if( isset( $_POST['hydra_product_name'] ) ) { 
            update_post_meta( $post->id(), 'hydra_product_name', sanitize_text_field( $_POST['hydra_product_name'] ) );
        } 
        if( isset( $_POST['hydra_product_price'] ) ) { 
            update_post_meta( $post->id(), 'hydra_product_price', sanitize_text_field( $_POST['hydra_product_price'] ) );
        } 

        // Event Calendar
        if( isset( $_POST['calendar_event_enable'] ) ) { 
            update_post_meta( $post->id(), 'calendar_event_enable', sanitize_text_field( $_POST['calendar_event_enable'] ));
        } else{
            update_post_meta( $post->id(), 'calendar_event_enable', '' );
        }
        if( isset( $_POST['event_email'] ) ) { 
            update_post_meta( $post->id(), 'event_email', sanitize_text_field( $_POST['event_email'] ) );
        }
        if( isset( $_POST['event_date'] ) ) { 
            update_post_meta( $post->id(), 'event_date', sanitize_text_field( $_POST['event_date'] ) );
        } 
        if( isset( $_POST['event_time'] ) ) { 
            update_post_meta( $post->id(), 'event_time', sanitize_text_field( $_POST['event_time'] ) );
        } 
        if( isset( $_POST['event_summary'] ) ) { 
            update_post_meta( $post->id(), 'event_summary', sanitize_text_field( $_POST['event_summary'] ) );
        } 

    }

    /**
     * Show some properties in the frontend of Contact Form 7 form
     * 
     * Initiate calendar with time in the frontend form
     * 
     * @since 1.0
     */
    public function tfhb_hydra_properties($properties, $post) {

       
        if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) { 
            // Generate a custom nonce value
            $tfhb_nonce = wp_create_nonce( '_tfhb_nonce' );
            
      
            $form = $properties['form']; 
            // get saved value      
            $hydra_enable = !empty(get_post_meta( $post->id(), 'hydra_enable', true )) ? get_post_meta( $post->id(), 'hydra_enable', true ) : '';
            
            $hydra_date_theme = !empty(get_post_meta( $post->id(), 'hydra_date_theme', true )) ? get_post_meta( $post->id(), 'hydra_date_theme', true ) : ''; 
            
            ob_start(); 
            echo wp_kses_post($form);    
             // Add the custom nonce value to the CF7 form
            echo '<input type="hidden" readonly name="_tfhb_nonce" value="' . esc_attr( $tfhb_nonce ) . '" />';
            if ($hydra_enable) {
                if ($hydra_date_theme != 'default') { 
                    echo '<link rel="stylesheet" type="text/css" href="' . esc_url(plugin_dir_url( __FILE__ ). 'assets/flatpickr/themes/' . esc_html($hydra_date_theme) . '.css' ) .'">'; 
                } 
            } 
            
            $properties['form'] = ob_get_clean();   
        
        }
        
    
        return $properties;
    }

    /**
     * Product add to cart after submiting form by ajax
     * 
     * Create custom WooCommerce product if no product is provided
     * 
     * Add booking date & time as cart item data
     * 
     * @since 1.0
     */
    public function tfhb_hydra_ajax_add_to_cart_product() {
       
        if( isset($_POST['tfhb_nonce']) && ! wp_verify_nonce( sanitize_text_field($_POST['tfhb_nonce']), 'updates' ) ){
            return; 
        } 
        $hydra_product = sanitize_text_field($_POST['hydra_product']);

        if ($hydra_product == 'exist'){
            $product_id = sanitize_text_field($_POST['product_id']); 
        } elseif ($hydra_product == 'custom'){
         
            $product_name = sanitize_text_field($_POST['product_name']);
            $product_price = sanitize_text_field($_POST['product_price']);

            // Add Product
            $product_arr = array(
                'post_title' => $product_name,
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_password' => '1111114455',
                'meta_input'   => array(
                    '_price' => $product_price,
                    '_regular_price' => $product_price,
                    '_visibility' => 'visible',
                    '_virtual' => 'yes',
                    '_sold_individually' => 'yes',
                )
            );

            $product_id = post_exists( $product_name,'','','product');
            if ( $product_id ) {} else {
                $product_id = wp_insert_post( $product_arr );        
            }

        }
        
        // Get booking date from form
        if( isset($_POST['booking_date']) && !empty($_POST['booking_date']) ) {
            $booking_date = sanitize_text_field($_POST['booking_date'] );       
        } else {
            $booking_date = 'N/A';
        }

        // Get booking time from form
        if( isset($_POST['booking_time']) && !empty($_POST['booking_time']) ) {
            $booking_time = sanitize_text_field($_POST['booking_time'] );       
        } else {
            $booking_time = 'N/A';
        }

        /*
        * Custom cart item data
        */
        $cart_item_data = array();
        $cart_item_data['booking_date'] = $booking_date;
        $cart_item_data['booking_time'] = $booking_time;         

        /*
        * Add to cart
        */ 
        $product_cart_id = WC()->cart->generate_cart_id( $product_id );
        if( ! WC()->cart->find_product_in_cart( $product_cart_id ) ){
            WC()->cart->add_to_cart( $product_id, '1', '0', array(), $cart_item_data);
        }   
        die();

    }

    /**
     * Display booking date & time in cart and checkout
     * 
     * @since 1.0
     */
    public function tfhb_hydra_display_cart_item_custom_meta_data( $item_data, $cart_item ) {
        if ( isset($cart_item['booking_date']) && !empty($cart_item['booking_date']) ) {
            $item_data[] = array(
                'key'       => 'Booking Date',
                'value'     => $cart_item['booking_date'],
            );
        }
        if ( isset($cart_item['booking_time']) && !empty($cart_item['booking_time']) ) {
            $item_data[] = array(
                'key'       => 'Booking Time',
                'value'     => $cart_item['booking_time'],
            );
        }
        return $item_data;
    }

    /**
     * Add booking date & time as order item meta
     * 
     * @since 1.0
     */
    public function tfhb_hydra_save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {

        if ( isset($values['booking_date']) && !empty($values['booking_date']) ) {
            $item->update_meta_data( 'Booking Date', $values['booking_date'] );
        }
        if ( isset($values['booking_time']) && !empty($values['booking_time']) ) {
            $item->update_meta_data( 'Booking Time', $values['booking_time'] );
        }
    }


    /**
     * Called when WooCommerce is inactive to display an inactive notice.
     *
     * @since 1.0
     */
    public function tfhb_woocommerce_inactive_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) && !file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
            ?>

                <div id="message" class="error">
                    <p>
                        <?php 
                        printf( 
                            /* translators: %s: Link */
                            esc_html__(  'Hydra Booking Form requires %1$s WooCommerce %2$s to be activated.', 'hydra-booking' ), 
                            '<strong><a href="'.esc_url('https://wordpress.org/plugins/woocommerce/').'" target="_blank">', 
                            '</a></strong>' 
                        ); 
                        ?>
                    </p>
                    <p>
                        <a class="install-now button tf-install" data-plugin-slug="woocommerce">
                            <?php esc_html_e( 'Install Now', 'hydra-booking' ); ?> 
                        </a>
                    </p>
                </div>

            <?php 
            } elseif ( version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
            ?>

                <div id="message" class="error">
                    <p>
                        <?php 
                            printf( 
                                 /* translators: %1$: <strong>,  %2$: </strong>,  %3$: link,  %4$: link end, */
                                esc_html__( '%1$ Hydra Booking Form is inactive.  %2$: This plugin requires WooCommerce 2.5 or newer. Please %3$ supdate WooCommerce to version 2.5 or newer %4$', 'hydra-booking' ), 
                                '<strong>', 
                                '</strong>', 
                                '<a href="' . esc_url(admin_url( 'plugins.php' )) . '">', 
                                '</a>' 
                            ); 
                        ?>
                    </p>
                </div>

            <?php 
            }
        }
    }


}

new TFHB_HYDRA_INIT();
 
 