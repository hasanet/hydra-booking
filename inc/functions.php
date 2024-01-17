<?php 
  // exit
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }

  // print_r
  function thb_print_r($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
  }
  // var_dump
  function thb_var_dump($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
  }
 ?>