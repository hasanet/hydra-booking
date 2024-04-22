<?php 
namespace HydraBooking\Admin\Controller; 

class AuthController {
  
    // constaract
  public function __construct() {   
    
  }

  public function create_endpoint(){
    register_rest_route('hydra-booking/v1', '/user/auth', array(
        'methods' => 'GET',
        'callback' => array($this, 'getUserAuth'),
    ));     
    
  }

  // Get Current user auth
  public function getUserAuth() {   
    $user_id = $this->userID();
    $user_role = $this->userRole();
    $user_caps = $this->userAllCaps(); 
    
    // Return response
    $data = array(
      'status' => true, 
      'user' => array(
        'id' => $user_id,
        'role' => $user_role,
        'caps' => $user_caps,
      ),  
  );
  return rest_ensure_response($data);
    
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
 