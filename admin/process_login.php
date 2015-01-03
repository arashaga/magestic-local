<?php
include_once '../config.php';
include_once '../utility.php';

ob_start();// this is to get rid of the annoying headers warning

secSessionStart(); // Our custom secure way of starting a PHP session.
$dbh = getDBH();
if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // The hashed password.
 
    if (login($email, $password, $dbh) == true) {
        // Login success 
		echo 'loggedin';
        header('Location: ../admin/members.php');
    } else {
        // Login failed 
        header('Location: ../admin.php?err=1');
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}

?>