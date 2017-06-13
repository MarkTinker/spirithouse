<?php
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");
//include("../includes/mail_pear.php");
require '../includes/PHPMailer/PHPMailerAutoload.php';

if(isset($_GET['bookingid'])) {

  $bookingid=$_GET['bookingid'];

  $sql="select firstname, lastname, email, seats, classname, classprice, classdescription, total, notes, starttime, date_format(`scheduledate`, '%W %D %M %Y') as scheduledate2
        from bookings
        left join schedule on bookings.scheduleid=schedule.scheduleid
        left join classes on schedule.classid=classes.classid
        where bookingid=".quote_smart($bookingid);

  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no>0) {
    $firstname=mysql_result($rs,0,"firstname");
    $lastname=mysql_result($rs,0,"lastname");
    $seats=mysql_result($rs,0,"seats");
    $classname=mysql_result($rs,0,"classname");
    $classdescription=mysql_result($rs,0,"classdescription");
    $classprice=mysql_result($rs,0,"classprice");
    $starttime=mysql_result($rs,0,"starttime");
    $scheduledate=mysql_result($rs,0,"scheduledate2");
    $email=mysql_result($rs,0,"email");
    $havepaid=mysql_result($rs,0,"total");
    $notes=mysql_result($rs,0,"notes");
    
    $totalcost=number_format(($seats*$classprice),2);
    $gst=round(($totalcost/11),2);
  	$paid=substr($notes, 0, 3);
  	
  	$paymentreceived='';
  	//check to see if they've paid before we say THANKS FOR PAYING
  	
     if($havepaid==$totalcost || $paid<>'Not'){
    	$havetheypaid='We received your payment and your booking is <strong>confirmed</strong>. You may wish to print this page as part of your records.';
    	$paymentreceived='Paid';
    		}else{
    			$havetheypaid="We have  <strong>not received payment</strong> for this class but will continue to hold your booking while you organise payment for the outstanding amount. Please see the invoice above -  payment enquiries can be made to our helpful office staff on (07) 5446 8977.";
    			
    }
    
    
    
    if($seats>'1'){
    	$printseats='classes';
    		}else{
    			$printseats='class';
    }
    #$email="murray@harvestmarketing.com";

    $content.="
     <center>
  <img src='http://www.spirithouse.com.au/images/email_logo.gif' alt='email_logo'/>
  <p style='font-size:10px'>Invoice No:".$bookingid."</p>
  <table width='300px'style='margin:5px; border-top:1px solid #ccc;border-bottom:1px solid #ccc;' cellspacing=5>
  
  <tr>
 	<td colspan=2>Invoice To:&nbsp;".$firstname."&nbsp;".$lastname."</td>
  </tr>
  <tr>
  <td>".$seats."</td>
  <td>Cooking ".$printseats."</td>
  <td align='right'> $".$totalcost."</td>
  </tr>
  
  <tr>
  <td colspan=2 align='right' colspan=2>GST:</td>
  <td align='right'>$".$gst."</td>
  </tr>
  
  <tr>
 	<td colspan=3 align='center'> <p style='font-size:12px; color:red' >".$paymentreceived."</p></td>
  </tr>
  
  </table>
  </center>
   
    <p>Hi ".$firstname."</p>
    <p>Thanks for reserving ".$seats." place/s in the <strong>".$classname."</strong> cooking class on <strong>". $scheduledate."</strong>.</p>
    <p>".$havetheypaid."</p>

    <p>Some important information:
    <p>Your class starts at ".$starttime." so please arrive 15 minutes early so we can start on time. If you have any queries regarding billing, refunds, changing classes etc. simply email us at <a href='mailto:office@spirithouse.com.au'>office@spirithouse.com.au</a> or call during business hours on (07) 5446 8977.
   <p>All our classes are 'hands-on'. Wear comfortable shoes because you will be standing for a few hours while preparing recipes. Lunch, wine, aprons, recipes and equipment are included in your class fee so the only thing you need to bring is yourself.
    <p>To help you find Spirit House, follow these simple directions:
    <p><b>For GPS units, our address is 20 Ninderry Rd, BRIDGES.</b> We are in Yandina, but GPS units tend to call this area 'Bridges'.</p>
    <p>From the Bruce Highway, take the exit sign to YANDINA, follow directions to the Ginger Factory, as we are just behind it. At the roundabout, take the Coulson Road Exit... follow this road, which runs behind the Ginger Factory and past the Yandina Primary School. After about 500 metres, the road ends at a T Junction with Ninderry Road. Turn right into Ninderry Road, the Spirit House is about 50 metres along on the right.</p>

    <img src='http://www.spirithouse.com.au/images/map.gif' />
    <p><strong>Questions or cancellations?</strong></p>
    <p>Call us on (07) 5446 8977. Please do remember that if you don't show on the day we cannot refund the booking fee.</p>
    <p>We're looking forward to seeing you on the day!</p>
    ";
  

    

    ######### below is the new mail script using smtp and PEAR ########
    $output=email_template($content);
    $subject="Confirmation of $classname class on $scheduledate at $starttime"; 
    $recipient="$firstname <$email>";
    //escape BCC below if you don't want to get a copy of the customer's email 
 	//$bcc= "Bookings <abrierty@gmail.com>";   
	//send_email($recipient,$bcc, $subject, $content);  
    ######################################################################
    //echo $output;
    
        /** SENDING THROUGH GOOGLE APPS **/

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');



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
$mail->setFrom('cookingschool@spirithouse.com.au', 'Spirit House Cooking School');

//Set an alternative reply-to address
$mail->addReplyTo('office@spirithouse.com.au', 'Spirit House Cooking School');

//Set who the message is to be sent to
$mail->addAddress($email, $firstname);
//$mail->AddCC('acland@aclandbrierty.com', 'Acland brierty');

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
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
    


  }
}

?>
