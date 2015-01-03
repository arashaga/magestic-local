<?php

  require_once('config.php');

  $token  = $_POST['stripeToken'];
  $fullname = $_POST['fname']." ".$_POST['lname'];
 

  $guest1= isset($_POST['guest1']) ? $_POST['guest1'] : null;
  $guest2= isset($_POST['guest2']) ? $_POST['guest2'] : null;
  $guest3= isset($_POST['guest3']) ? $_POST['guest3'] : null;
  $guest4= isset($_POST['guest4']) ? $_POST['guest4'] : null;
  

  $customer = Stripe_Customer::create(array(
      'email' => 'arashaga@gmail.com',
      'card'  => $token
  ));
  
  $amount = (isset($_POST['bottles'])) ? (int)$_POST['bottles']* 5000 : 5000;
	 
  $charge = Stripe_Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $amount,
      'currency' => 'usd',
  	  'metadata' => array('fullname' => $fullname,
  						'guest1' => $guest1,
  						'guest2' => $guest2,
  						'guest3' => $guest3,
  						'guest4' => $guest4)
  ));

	
  echo '<h1>Thank you'.$fullname.' Successfully charged $50.00!</h1>';
?>