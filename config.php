<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'utility.php';

define("HOST","localhost");
define("USER","arashaga");
define("PASSWORD","Biashead12747");
define("DATABASE","magestic");
define ("SMTP",true);
define ("SECURE",false);
define("SMTP_HOST",'p3plcpnl0046.prod.phx3.secureserver.net');
define("SMTP_USER",'arashaga');
define("SMTP_PASSWORD",'Biashead12747');
define("MASTER_EMAIL", 'arashaga@gmail.com');
define("EMAIL_STATUS",  emailStatus());

require_once('stripe/lib/Stripe.php');
require_once('PHPMailer/PHPMailerAutoload.php');





   
function getDBH() {
    static $dbh = null;
    if (is_null($dbh)) {
           try {
			   

        $dbh = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8', USER, PASSWORD);
	   $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	   $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
           	}catch (PDOException $e) {
        die('unable to connect to database ' . $e->getMessage());
        	}
    }
    return $dbh;
}


    
$stripe = array(
  "secret_key"      => "sk_test_68KeJDpBKdCsC5woEmyUk0BK",
  "publishable_key" => "pk_test_A0Iogb7wQbcDbFvufARITXBx"
);

Stripe::setApiKey($stripe['secret_key']);
?>