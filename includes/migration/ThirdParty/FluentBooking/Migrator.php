<?php 
namespace HydraBooking\Migration\ThirdParty\FluentBooking;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class Migrator {

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
 
	public function __construct(   ) {
        // echo "heloo";
        // exit;
	} 

}

// call the class
