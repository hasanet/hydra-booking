<?php

namespace HydraBooking\PostType\Meeting;
defined( 'ABSPATH' ) || exit;

class Meeting_CPT extends \HydraBooking\PostType\Post_Type {

	private static $instance;

	/**
	 * @return static
	 */
	public static function instance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize custom post type
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$meeting_args = array(
			'name'          => 'Meeting',
			'singular_name' => 'Meeting',
			'slug'          => 'tfhb_meeting',
			'menu_icon'     => 'dashicons-admin-home',
			'supports'      => apply_filters( 'tfhb_meeting_supports', array( 'title', 'editor', 'thumbnail', 'comments', 'author' ) ),
			'capability'    => 'post',
			'rewrite_slug'  => 'tfhb_meeting',
			'show_ui'  => false,
			'show_in_menu' => false
		);

		$tax_args = array(
			array(
				'name'          => 'Locations',
				'singular_name' => 'Location',
				'taxonomy'      => 'apartment_location',
				'rewrite_slug'  => apply_filters( 'tfhb_meeting_location_slug', 'apartment-location' ),
				'capability'  => array(
					'assign_terms' => 'edit_tfhb_meeting',
					'edit_terms'   => 'edit_tfhb_meeting',
				),
			),
		);

		parent::__construct( $meeting_args, $tax_args );

		// add_action( 'init', array( $this, 'tf_post_type_taxonomy_register' ) );
	}

}