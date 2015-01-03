<?php

// Version 1.0
require_once('config.php');
require_once('phpqrcode/qrlib.php');

function get_customer_id($dbh, $fname, $lname) {

    try {

        $stmt = $dbh->prepare('SELECT id from customers where fname= :fname and lname=:lname');
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->execute();

        $results = $stmt->fetchAll(); // Get the results

        foreach ($results as $row) {
            print_r($row);
        }

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function check_service_is_soldout($dbh, $date) {


    try {
        //echo date(strtotime($date));
        //$stmt =$dbh->prepare('SELECT count(*) from customers WHERE timestamp >= "'. strtotime($date) .'" AND timestamp < "'. strtotime($date, '+1 day'). '"');
        $stmt = $dbh->prepare('SELECT count(*) from customers WHERE DATE(timestamp) = :date');
        //$stmt->bindParam(':fname',$fname);
        //$stmt->bindParam(':lname',$lname);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        //echo $stmt->fetchColumn();
        // echo "\n";
        if ($stmt->fetchColumn() < 12) {
            $dbh = null;
            return false;
        }


        $dbh = null;
        return true;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function check_customer_allowance($dbh, $date, $email) {
    if (check_service_is_soldout($dbh, $date)) {
        //print_r("sold out!");
        //echo "service cheked!";
        return false;
    }
    //echo "service is ok!";

    try {
        $stmt = $dbh->prepare('SELECT count(*) from customers where email= :email and DATE(timestamp)= :date');

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        //echo $stmt->fetchColumn();
        if ((int) $stmt->fetchColumn() < 2) {
            //echo "less than two";
            $dbh = null;
            return true;
        }
        //echo "more than two";
        $dbh = null;
        return false;
        //print_r($stmt->fetchColumn());
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function createQRCode($email, $fullname, $token, $guest1, $guest2, $guest3, $guest4) {

    $tempDir = "tmp/";


    // we building raw data
    $codeContents = 'email:' . $email . "\n";
    $codeContents .= 'Full Name: ' . $fullname . "\n";
    $codeContents .= 'Token: ' . $token . "\n";
    $codeContents .= 'Guest 1: ' . $guest1 . "\n";
    $codeContents .= 'Guest 2: ' . $guest2 . "\n";
    $codeContents .= 'Guest 3: ' . $guest3 . "\n";
    $codeContents .= 'Guest 4: ' . $guest4 . "\n";

    $rand = rand(0, 5000);
    $filename = md5($rand . $email);
    // generating
    QRcode::png($codeContents, $tempDir . $filename . '.png', QR_ECLEVEL_L, 3);

    // displaying

    return '<img src="http://www.tvoop.us/magestic/' . $tempDir . $filename . '.png" />';
    // echo '<img src="'.$tempDir.'023.png" />';
}

function sendConfirmationEmail($email, $fullname, $token, $guest1, $guest2, $guest3, $guest4) {

    $email = $email;
    $token = $token;
    $emailsubject = "From Magestic";
    $guest1 = (isset($guest1)) ? $guest1 : 'Not Listed';
    $guest2 = (isset($guest2)) ? $guest2 : 'Not Listed';
    $guest3 = (isset($guest3)) ? $guest3 : 'Not Listed';
    $guest4 = (isset($guest4)) ? $guest4 : 'Not Listed';

    $img = createQRCode($email, $fullname, $token, $guest1, $guest2, $guest3, $guest4);

    $subject = file_get_contents('email.html');
    $search = array('%fullname%', '%img%');

    $replace = array($fullname, $img);
    $msg = str_replace($search, $replace, $subject);
    $parts = explode(" ", $fullname);
    $lname = array_pop($parts);
    $fname = implode(" ", $parts);


    createUserRecord(getDBH(), $fname, $lname, $email, $token, $guest1, $guest2, $guest3, $guest4);




    if (SMTP) {

        //	date_default_timezone_set('America/Chicago');
        $mail = new PHPMailer;
        $mail->SMTPDebug = 0;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = SMTP_HOST;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = SMTP_USER;                 // SMTP username
        $mail->Password = SMTP_PASSWORD;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;
        //add these to config as global
        $mail->From = 'arashaga@gmail.com';
        $mail->FromName = 'Magestic Music Hall';
        $mail->addAddress($email);     // Add a recipient
//		$mail->addReplyTo('info@example.com', 'Information');
//		$mail->addCC('cc@example.com');
//		$mail->addBCC('bcc@example.com');
//		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $emailsubject;
        $mail->Body = $msg;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return false;
        }
//		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    } else {

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Magestic Austin <hassan@hassan.com>' . "\r\n";


        mail($email, $emailsubject, $msg, $headers);
    }

   // sms($fullname);
}

function sms($fullname) {

    if (SMTP) {

        //	date_default_timezone_set('America/Chicago');
        $mail = new PHPMailer;
        $mail->SMTPDebug = 0;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = SMTP_HOST;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = SMTP_USER;                 // SMTP username
        $mail->Password = SMTP_PASSWORD;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;
        //add these to config as global
        $mail->From = 'arashaga@gmail.com';
        $mail->FromName = 'Magestic Music Hall';
        $mail->addAddress(MASTER_EMAIL);     // Add a recipient
//		$mail->addReplyTo('info@example.com', 'Information');
//		$mail->addCC('cc@example.com');
//		$mail->addBCC('bcc@example.com');
//		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $subject = 'www.magestic.com';
        $msg = $fullname . ' just purchased bottle service!';
        $mail->Subject = $emailsubject;
        $mail->Body = $msg;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return false;
        }
    } else {
        $date = now();
        $email = MASTER_EMAIL;
        $subject = 'www.magestic.com';
        $msg = 'At' . $date . ' ' . $fullname . ' just purchased bottle service!';
        mail($email, $subject, $msg);
    }
}

function createUserRecord($dbh, $fname, $lname, $email, $token, $guest1, $guest2, $guest3, $guest4) {

    try {



        $stmt = $dbh->prepare("INSERT INTO customers (`fname`, `lname`, `email`, `guest1`, `guest2`, `guest3`,`guest4`, `token`) 
			VALUES ( :fname, :lname, :email, :guest1, :guest2, :guest3, :guest4, :token )");
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':guest1', $guest1);
        $stmt->bindParam(':guest2', $guest2);
        $stmt->bindParam(':guest3', $guest3);
        $stmt->bindParam(':guest4', $guest4);
        $stmt->bindParam(':token', $token);

        $stmt->execute();



        $dbh = null;
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function getOrderList($date) {

    $dbh = getDBH();

    try {
        $stmt = $dbh->prepare('SELECT * from customers where and DATE(timestamp)= :date');
        $stmt->bindParam(':date', $date);
        $stmt->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function secSessionStart() {

    $sessionName = 'secSessionId';
    $secure = SECURE;

    $httponly = true;

    if (ini_set('session.use_only_cookies', 1) === False) {
        header("location: /admin/admin.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($sessionName);
    session_start();            // Start the PHP session 
    session_regenerate_id(true);
    //
}

function login($email, $password, $dbh) {

    //echo $email;
    //$password = md5($password);
    //echo $password;
    //$dbh = getDBH();
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $dbh->prepare("SELECT id, email, password
        FROM signin
       WHERE email= :email
        LIMIT 1")) {

        $stmt->bindParam(':email', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        // $stmt->storeResult();
        //
      //   // get variables from result.
        //   $stmt->bindResult($email, $db_password);
        $result = $stmt->fetch();
        print_r($result);
    }


    // hash the password with the unique salt.
    $password = md5($password);
    if ($stmt->rowCount() == 1) {
        // If the user exists we check if the account is locked
        // from too many login attempts 

        if (checkbrute($email) == true) {
            // Account is locked 
            echo "brute";
            // Send an email to user saying their account is locked
            return false;
        } else {
            // Check if the password in the database matches
            // the password the user submitted.
            if ($result['password'] == $password) {

                echo 'it matches!';
                // Password is correct!
                // Get the user-agent string of the user.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $user_id = preg_replace("/[^0-9]+/", "", $result['id']);
                $_SESSION['user_id'] = $result['id'];
                // XSS protection as we might print this value
                $email = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $email);
                $_SESSION['email'] = $email;
                $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                // Login successful.
                return true;
            } else {

                echo 'it does not match';
                // Password is not correct
                // We record this attempt in the database
                $id = $result['id'];
                $now = time();
                $dbh->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$id', '$now')");
                return false;
            }
        }
    } else {
        // No user exists.
        return false;
    }
}

//brute force function
function checkbrute($email) {

    $dbh = getDBH();
    // Get timestamp of current time 
    $now = time();

    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $dbh->prepare("SELECT time 
                             FROM login_attempts 
                             WHERE user_id = :user_id 
                            AND time > '$valid_attempts'")) {
        $stmt->bindParam(':user_id', $email);

        // Execute the prepared query. 
        $stmt->execute();
        // $stmt->store_result();
        // If there have been more than 5 failed logins 
        if ($stmt->rowCount() > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function is_loggedin() {

    $dbh = getDBH();
    // Check if all session variables are set 
    if (isset($_SESSION['email'], $_SESSION['username'], $_SESSION['login_string'])) {

        $email = $_SESSION['email'];
        $login_string = $_SESSION['login_string'];
        $user_id = $_SESSION['user_id'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $dbh->prepare("SELECT password 
                                      FROM signin 
                                      WHERE id = :id LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bindParam(':id', $user_id);
            $result = $stmt->execute();   // Execute the prepared query.

            if ($stmt->rowCount() == 1) {
                // If the user exists get variables from result.
                //$stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $result['password'] . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

// sanitize self variable from the server

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

//logout 

function logout() {

    $_SESSION = array();

    // get session parameters 
    $params = session_get_cookie_params();

    // Delete the actual cookie. 
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

    // Destroy session 
    session_destroy();
    header('Location: admin/admin.html');
}

function swithEmailOnOff() {
    $dbh = getDBH();
    try {
        $stmt = $dbh->prepare('SELECT email_status from configurations');
        $stmt->execute();
        $result = $stmt->fetch();


//        echo json_encode($result);
//        exit();

        if ($result['email_status'] == 'On') {

            $update = $dbh->prepare('UPDATE configurations set email_status=:status');
            $status = 'Off';
            $update->bindParam(':status', $status);
            $update->execute();
        } else {
            $status = 'On';
            $update = $dbh->prepare('UPDATE configurations set email_status=:status');
            $update->bindParam(':status', $status);
            $update->execute();
        }
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    $dbh = null;
}

function emailStatus() {

    $dbh = getDBH();
    try {
        $stmt = $dbh->prepare('SELECT email_status from configurations');
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result['email_status'] == 'On') {
            return 'checked';
        } else {

            return "";
        }
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

?>