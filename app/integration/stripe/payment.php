<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);


if($_POST['tokenId']) {

    require_once('vendor/autoload.php');

    try {
      //stripe secret key or revoke key
      $stripeSecret = 'sk_test_51Oqv91IsLSX0wXZBfBMh01oIDqdwKmZRMTOoD1V5AyDyseDSKX64vgRmvC0TYZppyZgqOesKihAInmtuoFJDhYdL00pywuCxag';

      \Stripe\Stripe::setVerifySslCerts(false);

      // See your keys here: https://dashboard.stripe.com/account/apikeys
      \Stripe\Stripe::setApiKey($stripeSecret);

      // Get the payment token ID submitted by the form:
      $token = $_POST['tokenId'];

      $customer = \Stripe\Customer::create(array(
        'email' => "test@test.com",
        'source'  => $token,
        "address" => [
          "city" => "Dhaka",
          "country" => "Bangladesh",
          "line1" => "dhaka",
          "line2" => "",
          "postal_code" => "1000",
          "state" => "dhaka"
        ]
      ));

      $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => (int) number_format($_POST['amount'], 2),
        'currency' => "usd",
        'description' => "test stipe payment",
      ));
    

      // after successfull payment, you can store payment related information into your database

      $data = array('success' => true, 'data' => $charge);

      echo json_encode($data);
    } catch (\Throwable $th) {
      $data = array('success' => false, 'data' => $th->getMessage());
      echo json_encode($data);
    }
    
}
