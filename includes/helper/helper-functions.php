<?php 

if(!function_exists('tfhb_print_r')){
    function tfhb_print_r($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }
}

?>