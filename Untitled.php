<?php
 
 	$from = "acland@spirithouse.com.au";
 	$from_name = 'Acland';
 	$to = "acland@aclandbrierty.com";
 	$subject = "testing gmail";
 	$body = "Hi,\n\n sending from spirit house - hopefully";
 
 /*
 	require_once "Mail.php";
 	include "pear/Mail/mime.php" ;
 	//require_once "pear/Mail.php";



 	$headers = array(
    	'From' => $from,
		'To' => $to,
		'Subject' => $subject
		);

		$smtp = Mail::factory('smtp', array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => '465',
        'auth' => true,
        'username' => 'acland@spirithouse.com.au',
        'password' => 'g0ndr0ng'
    ));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
    	echo('<p>' . $mail->getMessage() . '</p>');
		} else {
			echo('<p>Message successfully sent!</p>');
		}
         
  */       
 require("includes/mailer/class.phpmailer.php");
 define('GUSER', 'acland@spirithouse.com.au'); // GMail username
 define('GPWD', 'g0ndr0ng');
 

 
 
 	$from = "acland@spirithouse.com.au";
 	$from_name = 'Acland';
 	$to = "acland@aclandbrierty.com";
 	$subject = "testing phpmailer class";
 	$body = "Hi,\n\n sending through phpmailer";

 	$mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587; 
    $mail->Username = GUSER;  
    $mail->Password = GPWD;           
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    if(!$mail->Send()) {
        $error = 'Mail error: '.$mail->ErrorInfo; 
        return false;
    } else {
        $error = 'Message sent!';
        return true;
    }
	
 ?>
 
