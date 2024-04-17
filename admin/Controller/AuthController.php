<?php 
namespace HydraBooking\Admin\Controller; 

class AuthController {
  
    // constaract
  public function __construct() {   
    
  }
  // Get Current user auth 
  public function userRole() {   
    $user = wp_get_current_user();
    $roles = ( array ) $user->roles;
    return $roles;
    
  }  
  public function userID() {    
    $user = wp_get_current_user();
    return $user->ID;
    
  }  

  public function userData( ) {    
    
    $user = wp_get_current_user();
    return $user;
    
  }
  public function userAllCaps( ) {    
    
    $user = wp_get_current_user();
    return $user->allcaps;
    
  }
 
}
 