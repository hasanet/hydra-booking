<?php 
namespace HydraBooking\Admin\Includes;
  // exit
  if ( ! defined( 'ABSPATH' ) ) { exit; }

  class Enqueue {

    // constaract
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_filter('script_loader_tag', array($this, 'thb_loadScriptAsModule'), 10, 3);
    }
    public function thb_loadScriptAsModule($tag, $handle, $src) {
        if ('thb-vue-core' !== $handle) {
            return $tag;
        }
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }
    public function admin_enqueue_scripts() { 
        wp_enqueue_script('thb-app-script', THB_URL . 'assets/admin/js/main.js', array('jquery'), null, true);
        // wp_enqueue_script('thb-app-script', THB_URL . 'assets/admin/js/main.js', array('jquery'), null, true);
        wp_enqueue_script('thb-vue-core', 'http://localhost:5173/src/main.js', [], time(), true);
        wp_localize_script('thb-vue-core', 'thb_wpvue', [
            'url' => THB_URL,
        ]);
   
    }

}


