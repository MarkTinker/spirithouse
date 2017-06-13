<?php

    $content.="<p>Hi ".$_POST['firstname']."</p>
    <p>Thanks for reserving ".$_POST['seats']." place/s in the following cooking class:</p>".
    $_POST['emaildescription']."
    <p>Thanks! We received your payment and your cooking class booking is confirmed. You may wish to print this page as part of your records.

    <p>Some important information:
    <p>Your class starts at ".$_POST['starttime']." so please arrive 15 minutes early so we can start on time. If you have any queries regarding billing, refunds, changing classes etc. simply email us at office@spirithouse.com.au or call during business hours on (07) 5446 8977.
    <p>All our classes are 'hands-on'. For safety reasons, please bring covered shoes to wear during class. Lunch, wine, aprons, recipes and equipment are included in your class fee so the only thing you need to bring is yourself.
    <p>To help you find Spirit House, follow these simple directions:
    <p><b>For GPS units, our address is 20 Ninderry Rd, BRIDGES.</b> We are in Yandina, but GPS units tend to call this area 'Bridges'.</p>
    <p>From the Bruce Highway, take the exit sign to YANDINA, follow directions to the Ginger Factory, as we are just behind it. At the roundabout, take the Coulson Road Exit... follow this road, which runs behind the Ginger Factory and past the Yandina Primary School. After about 500 metres, the road ends at a T Junction with Ninderry Road. Turn right into Ninderry Road, the Spirit House is about 50 metres along on the right.</p>
    <img src='http://www.spirithouse.com.au/images/map.gif' />
    <p><strong>Questions or cancellations?</strong></p>
    <p>Call us on (07) 5446 8977. Please do remember that if you don't show on the day we cannot refund the booking fee.</p>
    <p>We're looking forward to welcoming you to Spirit House.</p>
    ";
    $output="<html><head>
    <style>
       body { font-family:arial; font-size:12px; }
      h2 { margin-top:10px; margin-bottom:0px; font-weight:bold; font-size:14px; font-family: georgia, times; }
      th { margin-top:10px; font-weight:bold; font-size:12px; font-family: georgia, times; }
      p  { margin-top:10px; margin-bottom:0px; font-weight:normal; font-size:14px; font-family: Verdana, sans-serif ;}
      .ordersummary { width:430px; background-color:#fefefe;  }
      .ordersummary td { padding:0; margin:0; border-top:1px solid #ccc; font-size: 11px; color: #333; }
      .paymentdetails td { padding:0; margin:0; border-top:1px solid #ccc; font-size: 12px; color: #333; }
      .details { width:100%; margin-top:10px; margin-left:5px; }
      .details td { font-size: 11px; color: #333; }
      .ordersummary_total { font-size:12px; font-weight:bold; }
    </style></head>
    <body bgcolor='#efefef'>
    <center>
    <div style='width:700px; background-color:#fff; border:1px solid #999; text-align:left;'>
  	<center>
    <img src='http://www.spirithouse.com.au/hamish/resources/new-logo.png'>
  	</center>";
    $output.="<div style='padding:10px; '>";
    $output.=$content;
    $output.="</div></div></body></html> ";
    
    
	$email='abrierty@gmail.com';
    $name='Acland Brierty';
    $subject='cooking class on abc';
    
    send_phpmailer2 ($email, $name, $subject, $output);

    function send_phpmailer ($email, $name, $subject, $output){
	    
	 echo "This should fucking work $email and $name on $subject<p>$output";   
	    
    }
    
    
 
function send_phpmailer2 ($email, $name, $subject, $output){
	    
	   
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'includes/PHPMailer/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
//$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
//$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "cookingschool@spirithouse.com.au";

//Password to use for SMTP authentication
$mail->Password = "spirit268";

//Set who the message is to be sent from
$mail->setFrom('cookingschool@spirithouse.com.au', 'Pam Patten');

//Set an alternative reply-to address
$mail->addReplyTo('office@spirithouse.com.au', 'Pam Patten');

//Set who the message is to be sent to
$mail->addAddress($email, $name);
$mail->AddCC('acland@aclandbrierty.com', 'Acland brierty');

//Set the subject line
$mail->Subject = $subject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('PHPMailer/examples/contents.html'), dirname(__FILE__));
$mail->msgHTML($output);
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
//$mail->addAttachment('PHPMailer/examples/images/phpmailer_mini.png');

//send the message, check for errors
$mail->send();

}
    
     
   

   ?>