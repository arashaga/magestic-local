<?php 

require_once('config.php'); 
require_once('utility.php'); 

if(isset($_GET['listener']) && $_GET['listener'] == 'stripe') {
 	echo json_encode ('hi');
		// global $stripe_options;
		//
		//
		//
		// if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		// 	$secret_key = $stripe_options['test_secret_key'];
		// } else {
		// 	$secret_key = $stripe_options['live_secret_key'];
		// }
 
		// Stripe::setApiKey($secret_key);
 
		// retrieve the request's body and parse it as JSON
		$body = @file_get_contents('php://input');
 
		// grab the event information
		$event_json = json_decode($body);
		echo json_encode ($event_json);
 
		// this will be used to retrieve the event from Stripe
		$event_id = $event_json->id;
		
		echo json_encode($event_id);
 
		if(isset($event_id)) {
 
			try {
 
				// to verify this is a real event, we re-retrieve the event from Stripe 
				
				//for live you can use the following code
				//$event = Stripe_Event::retrieve($event_id);
				
				$transaction = $event_json->data->object;
 
				// successful payment
				if($event_json->type == 'charge.succeeded') {
					// send a payment receipt email here
 				   echo json_encode(array('res' => 'suc'));
					// retrieve the payer's information
					//$customer = Stripe_Customer::retrieve($transaction->customer);
					
					$email = $transaction->card->name;
					
					$customer_name = $event_json->data->object->metadata->fullname;
					$guest1 = $event_json->data->object->metadata->guest1;
					$guest2 = $event_json->data->object->metadata->guest2;
					$guest3 = $event_json->data->object->metadata->guest3;
					$guest4 = $event_json->data->object->metadata->guest4;
					$token = $event_json->data->object->card->fingerprint;
					// echo json_encode($token);
// 					exit();
 
					$amount = $transaction->amount / 100; // amount comes in as amount in cents, so we need to convert to dollars

 				   
				   	sendConfirmationEmail($email, $customer_name, $token, $guest1 ,$guest2 ,$guest3 ,$guest4 );
					//mail($email, $subject, $message, $headers);
				}
 			   
				// failed payment
				if($event_json->type == 'charge.failed') {
					// send a failed payment notice email here
 
					// retrieve the payer's information
					$customer = Stripe_Customer::retrieve($invoice->customer);
					$email = $customer->email;
					
					
 
					$subject = 'Failed Payment pippin_stripe';
					$headers = 'From: Magestic Austin <hassan@hassan.com>' . "\r\n";
					$message = "Hello " . $customer_name . "\n\n";
					$message .= "We have failed to process your payment of " . $amount . "\n\n";
					$message .= "Please get in touch with support.\n\n";
					$message .= "Thank you.";
 
					mail($email, $subject, $message, $headers);
				}
 
			} catch (Exception $e) {
				
				echo json_encode($e);
				// something failed, perhaps log a notice or email the site admin
			}
		}
	}
	
?>