<?php
namespace HydraBooking\Hooks;
class DeactivationHooks{

    public function __construct( ) { 

        register_deactivation_hook( THB_PATH . 'hydra-booking.php', array( $this, 'tfhb_deactivation' ) );
    }

    public function tfhb_deactivation(){
        // Remove Host Role
        $this->tfhb_remove_host_role();

        // Remove Capabilities to the role
        $this->tfhb_add_capabilities_to_role();


    }

    public function tfhb_remove_host_role(){ 
        if( get_role('tfhb_host') ){
            remove_role('tfhb_host');
        } 
    }
    // Remove Capabilities to the role
    public function tfhb_add_capabilities_to_role(){
        // administrator
        $role = get_role( 'administrator' );
        $role->remove_cap( 'tfhb_manage_options' );
        $role->remove_cap( 'tfhb_manage_dashboard' );
        $role->remove_cap( 'tfhb_manage_meetings' );
        $role->remove_cap( 'tfhb_manage_booking' );
        $role->remove_cap( 'tfhb_manage_settings' ); 
        $role->remove_cap( 'tfhb_manage_hosts' );
    }
    
}