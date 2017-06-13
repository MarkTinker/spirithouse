<?php
# changed by murray 15/6/08

// changed by hamish Aug 20, 2011
// - added log function
function elog($message)
{
	error_log($message);
}

function generate_email_for_student($_POST) {

  if(strlen($_POST['email'])>0) {
    $email=$_POST['email'];

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

    $content=email_template($content);
    $subject= "Confirmation for your cooking class with Spirit House on ".$_POST['classdate']."";
    
    
    /**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'PHPMailer/PHPMailerAutoload.php';

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
$mail->addAddress($email);
//$mail->AddCC('acland@aclandbrierty.com', 'Acland brierty');

//Set the subject line
$mail->Subject = $subject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('PHPMailer/examples/contents.html'), dirname(__FILE__));
$mail->msgHTML($content);
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
//$mail->addAttachment('PHPMailer/examples/images/phpmailer_mini.png');

//send the message, check for errors
$mail->send();
    
	/*
   	// removed . phpversion() . "\r\n"  from $headers to get the mail to work on the new server OCT 2011.
    $headers = "From: office@spirithouse.com.au\r\n" .'X-Mailer: PHP \r\n' ."MIME-Version: 1.0\r\n" ."Content-Type: text/html; charset=utf-8\r\n" ."Content-Transfer-Encoding: 8bit\r\n\r\n";

    mail($email, "Confirmation for your cooking class with Spirit House on ".$_POST['classdate'], $content, $headers);
    
    */
    //confirmation email for spirithouse records
    $headers = "From: office@spirithouse.com.au\r\n" .'X-Mailer: PHP \r\n' ."MIME-Version: 1.0\r\n" ."Content-Type: text/html; charset=utf-8\r\n" ."Content-Transfer-Encoding: 8bit\r\n\r\n";
    mail("cookingschool@spirithouse.com.au", "New booking for ".$_POST['classdate']."- $".$_POST['classprice'], $content, $headers); 


  }

}

function generate_voucher_thankyou_email($_POST, $transactionid) {

  # $transactionid is the eway ID ...
  $qty_vouchers_ordered=$_POST['qty'];
  $value_of_each_voucher_ordered=$_POST['amount'];
  $customer_address=$_POST['address'];  # if you need it?
  $total_value_of_order=number_format($qty_vouchers_ordered*$value_of_each_voucher_ordered,0);

  if(strlen($_POST['email'])>0) {
    $email=$_POST['email'];

    $content="<p>Hi ".$_POST['firstname']."</p>";
    if($qty_vouchers_ordered==1) {
      $content.="<p>Thanks for purchasing ".$qty_vouchers_ordered." voucher at a value of $".$value_of_each_voucher_ordered." each! ";
    } else {
      $content.="<p>Thanks for purchasing ".$qty_vouchers_ordered." vouchers at a value of $".$value_of_each_voucher_ordered." each! ";
    }
    $content.="Your order total is $".$total_value_of_order." and has been charged to your credit card. Your transaction reference number is: ".$transactionid."</p>
    <p>Your voucher will be posted on the next business day. If you have not received your voucher within 5 working days from ordering, please call our office on (07) 5446 8977.</p>
      
      <p>What a Spirit House gift voucher lacks in size and weight, it makes up for in unadulterated gastronomic fun and frivolity. The lucky recipient  can use it in the restaurant, cooking school or the Spirit House shop to buy cook books, cooking gadgets and ingredients.</p>
      
      <p>When you give the voucher be sure to point out that it is valid for one year and that effectively this voucher is like front row tickets to their favourite sport or show ... so if they want to get in to the restaurant or a cooking class it pays to book EARLY. Send them to our website to book cooking classes online, check out the restaurant menu or find maps and places to stay.</p> 
    
    <p>Thanks again for your purchase,<br /><br />The Spirit House team</p>
    ";
    
    $content=email_template($content);
    $subject= "Download your Spirit House Gift Vouchers";

       /**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'PHPMailer/PHPMailerAutoload.php';

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
$mail->addAddress($email);
$mail->AddCC('office@spirithouse.com.au', 'Spirit House Cooking School');

//Set the subject line
$mail->Subject = $subject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('PHPMailer/examples/contents.html'), dirname(__FILE__));
$mail->msgHTML($content);
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
//$mail->addAttachment('PHPMailer/examples/images/phpmailer_mini.png');

//send the message, check for errors
$mail->send();
    
    
    
    
    
/* ------ OLD EMAIL SCRIPT --------
     $headers = "From: office@spirithouse.com.au\r\n" .'X-Mailer: PHP \r\n' ."MIME-Version: 1.0\r\n" ."Content-Type: text/html; charset=utf-8\r\n" ."Content-Transfer-Encoding: 8bit\r\n\r\n";

    mail($email, "Confirmation of your gift voucher purchase with Spirit House Cooking School", $content, $headers);

*/
  }

}


function update_seats_for_schedule($scheduleid) {

  $sql="select scheduleid, sum(seats) as totalseats from bookings where scheduleid=".quote_smart($scheduleid)." group by scheduleid";
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no==0) {
    $totalseats=0;
  } else {
    $totalseats=mysql_result($rs,0,"totalseats");
  }

  #echo "<br /><br />".$sql;

  $update="update schedule set `bookings`=".$totalseats." where `scheduleid`=".quote_smart($scheduleid);
  mysql_query($update);

  #echo "<br /><br />".$update;

}

function seats_still_avail($scheduleid, $seats, $maxseats) {
  $sql="select `bookings` from `schedule` where `scheduleid`=".quote_smart($scheduleid);
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no>0) {
    $bookings=mysql_result($rs,0,"bookings");
    if(($seats+$bookings)>$maxseats) {
      return false;
    } else {
      return true;
    }
  } else {
    return false;
  }
}


function email_template($content) {

    #output email to customer
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

    return $output;
}

// this sets the date drop down list
function select_year($name, $class, $default) {
  $ret="<select name='$name' class='$class'>";
  for($j=date("Y");$j<2022;$j++) {
    $ret.="<option ";
    if($j==$default) $ret.=" selected ";
    $ret.=" value='".substr($j,-2, 2)."'>$j</option>";
  }
  $ret.="</select>";
  return $ret;
}

function select_month($name, $class, $default) {
  $ret="<select name='$name' class='$class'>";
  for($j=1;$j<13;$j++) {
    $ret.="<option ";
    if($j==$default) $ret.=" selected ";
    $ret.=" value='";
    if(strlen($j)==1) $ret.="0";
    $ret.="$j'>".monthname($j)."</option>";
  }
  $ret.="</select>";
  return $ret;
  //echo $ret;
  //exit();
}

function monthname($monthnum) {

  switch($monthnum) {
  case 1: return "01 &mdash; Jan";break;
  case 2: return "02 &mdash; Feb";break;
  case 3: return "03 &mdash; Mar";break;
  case 4: return "04 &mdash; Apr";break;
  case 5: return "05 &mdash; May";break;
  case 6: return "06 &mdash; Jun";break;
  case 7: return "07 &mdash; Jul";break;
  case 8: return "08 &mdash; Aug";break;
  case 9: return "09 &mdash; Sept";break;
  case 10: return "10 &mdash; Oct";break;
  case 11: return "11 &mdash; Nov";break;
  case 12: return "12 &mdash; Dec";
  }
}

function email_murray($_POST, $authcode, $total_to_process, $transactionid, $extranotes) {
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  mail("murray@harvestmarketing.com", $authcode, "$extranotes <br />message: $authcode <br />total: $total_to_process <br />transaction_id: $transactionid<br /><br />".print_r($_POST,true), $headers);
}




function process_cc($_POST, $totalcreditamount=0, $transactionid) {

  $authcode="";

  //process by credit card
  if($totalcreditamount>0) {
    $authcode=process_payment($totalcreditamount, "Spirit House Cooking Class", $transactionid, $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['nameoncard1'], $_POST['cardnumber1'], $_POST['expiry_m1'], $_POST['expiry_y1'], $_POST['ccv1']);
  }
  return $authcode;
  #return "232387";
}

function process_voucher($_POST, $totalvoucheramount=0, $vouchers_to_use) {

  if($totalvoucheramount>0) {
    # put vouchers on hold
    for($i=0;$i<sizeof($vouchers_to_use);$i++) {
      $voucherid=$vouchers_to_use[$i];
      $voucher_update_description="Used on ".date("d/m/Y")." for <a href='http://www.spirithouse.com.au/admin/viewbookings.php?scheduleid=".$_POST['scheduleid']."'>this class</a> by ".$_POST['firstname']." ".$_POST['lastname']." ".$_POST['email'].". Class participants: ".$_POST['nametags'].". ";

      $sql="update vouchers set voucherstatus=".voucher_holding.", vouchernotes=CONCAT(vouchernotes,".quote_smart($voucher_update_description).") where vouchernumber=".quote_smart($voucherid);
      mysql_query($sql);
    }
  }

}

function insert_booking($_POST, $authcode, $transactionid, $total_to_process, $totalvoucheramount, $totalcreditamount, $vouchers_to_use) {

		if (empty($_POST['cardnumber1']))
			{
				$cardnumber = "no card";
			} else {
				$cardnumber= substr($_POST['cardnumber1'],-4);  // adding the last four digits into the database instead of the whole number which we never needed 
			}



  	$vouchers="";
  	$classnotes="";
  if(sizeof($vouchers_to_use)>0) {
    for($i=0;$i<sizeof($vouchers_to_use);$i++) {
      $vouchers.=$vouchers_to_use[$i]."|";
    }
  }
  ###### added notes field to enter the voucher numbers and credit card amount so we can see the payment method and vouchers we need to collext on the day.   
  $classnotes=$vouchers;

  if($totalcreditamount>0){
      $classnotes.="&#36;".$totalcreditamount." Paid CC";
  }


  $sql="insert into `bookings` (`scheduleid`, `seats`, `firstname`, `lastname`, `email`, `phone`,
  `nametags`, `cardname`, `cardnumber`, `expiry_m`, `expiry_y`, `ccv`, `giftcertificates`, `giftcertificatevalue`,
  `total`, `total_by_cc`, `total_by_gc`, authcode, transactionid, notes
  )
  VALUES (".quote_smart($_POST['scheduleid']).", ".quote_smart($_POST['seats']).", ".quote_smart($_POST['firstname']).",
  ".quote_smart($_POST['lastname']).", ".quote_smart($_POST['email']).", '".stripslashes($_POST['phone'])."', ".quote_smart($_POST['nametags']).",
  ".quote_smart($_POST['nameoncard1']).",'$cardnumber', ".quote_smart($_POST['expiry_m1']).",
  ".quote_smart($_POST['expiry_y1']).", ".quote_smart($_POST['ccv1']).",
  ".quote_smart($vouchers).", ".quote_smart($totalvoucheramount).", ".quote_smart($total_to_process).", ".quote_smart($totalcreditamount).", ".quote_smart($totalvoucheramount).",
  ".quote_smart($authcode).", ".quote_smart($transactionid).",".quote_smart($classnotes)."
  )";
  #mysql_query($sql);
  
 //the code below performs the query (inserting into database of the booking) 
 //if the sql is in error it sends the email to acland notifying the error 
 if (!mysql_query($sql))
 {
   mail("acland@aclandbrierty.com", 'sds-spirithouse', $sql.mysql_error());
   //mail("hamstu@gmail.com", 'sds-spirithouse', $sql.mysql_error() . 'SQLUSED=' . $sql);
 }

}

function last_booking_id(){
$sql="SELECT `bookingid` FROM `bookings`  ORDER BY `bookingid` DESC LIMIT 1";

$result=mysql_query($sql);			
$lastbookingid=trim(mysql_result($result,$t,"bookingid"));
//echo "this is the last bookingid: $lastbookingid";
return $lastbookingid;
}


function strip_classname($classname){
if ($classname[0]=='*'){$classname= substr($classname,1);} //strip the * off the front of the classnames
return $classname;
}

function split_classdescription($classdescription) {

	$offset= strpos($classdescription,":");
	$descriptionlength = strlen($classdescription);
	$description = substr($classdescription,0, $offset);// the first part of the description
	$recipes = substr($classdescription,(($descriptionlength-($offset+1))*-1));// gets the recipe part AFTER the colon, hence the +1
	$printrecipes= str_replace("*", "<li class='wide'>", $recipes); // replace asterisk with LI
	
	$result = array($description, $recipes);
	return $result;
	
	
	}

class description_print {
function split_classdescription($classdescription) {

	$offset= strpos($classdescription,":");
	$descriptionlength = strlen($classdescription);
	$description = substr($classdescription,0, $offset);// the first part of the description
	$recipes = substr($classdescription,(($descriptionlength-($offset+1))*-1));// gets the recipe part AFTER the colon, hence the +1
	$printrecipes= str_replace("*", "<li class='wide'>", $recipes); // replace asterisk with LI
	
	return $description;
	return $recipes;
	
  }

}

// FREQUENTLY ASKED QUESTIONS FOR THE COOKING SCHOOL CALENDAR //
function faq_section() {
	$faq=		"<ul>
				<li><strong>Cost:</strong>&#36; 150</li>
				<li><strong>Duration:</strong>&#126; 4 Hours</li>
				<li class='wide'><strong>Start Times:</strong> Day classes start at 9.30am.<br>Evening classes start at 4.30pm</li>
				<li class='wide'><strong>How to Book:</strong> Click the <span class='teal'>book now</span> button beside the class/date you like. If you want to speak to someone, call us during office hours on (07) 5446 8977.</li>
				<li class='wide'><strong>What Do I Bring</strong>Nothing. We provide all aprons, knives, utensils etc. for you to use during the duration of the class. 
				Because you will be handling knives and hot liquids <span class='teal'>please wear closed-in shoes</span>.</li>
				<li class='wide'><strong>Dietary Needs?</strong>We have specific gluten free and non-seafood classes however we don't cater for vegetarians. Browse the calendar for the dates. </li>
				<li class='wide'><strong>Is lunch or dinner included?</strong>Yes, you will dine on the recipes prepared during the class - plus enjoy it all with complementary wine and beer.</li>
				<li class='wide'><strong>Children?</strong>Children over 12 who are keen cooks are welcome. Sorry, no babies or strollers. </li>
			
			</ul>
			<div class='clear'></div>";
			return $faq;


}




?>