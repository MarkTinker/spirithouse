<?php 

// ===================================================================  
// vouchers_functions.php
// =================================================================== 
// Utility script for processing the voucher purchase form.
// 
// - Creates Voucher(s) in DB
// - Sends notification emails
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

require("Voucher.class.php");
require("mailer/PHPMail.class.php");

// Variable Definitions
// -------------------------------------------------------------------
define ("VOUCHER_NOTIFICATION_EMAIL_TO", "cookingschool@spirithouse.com.au");
define ("VOUCHER_NOTIFICATION_EMAIL_FROM", "office@spirithouse.com.au");
define ("EMAIL_REGEX", "/^[^@]*@[^@]*\.[^@]*$/");
define ("VOUCHER_DOWNLOAD_URL", "http://www.spirithouse.com.au/voucherPDF.php");

// Utility Functions
// -------------------------------------------------------------------
function getLastVoucherNumber()
{
	$sql = "SELECT `evouchernumber` FROM `vouchers` ORDER BY `evouchernumber` DESC LIMIT 1";
	$result = mysql_query($sql);
	if ($result) {
		$last = trim(mysql_result($result, $t ,"evouchernumber"));
		return $last;
	} else {
		return 0;
	}			
}

// Main Functions
// -------------------------------------------------------------------
function generate_vouchers($total_to_process, $transactionid, $authcode)
{
	// Define vars for convenience
	// Note: This function must be called in the context of a voucher submission ($_POST)
	$qty 		= $_POST['qty'];
	$amount 	= $_POST['amount'];
	$name 		= $_POST['firstname'];	// TODO: It's called firstname, but it's actually their fullname. Fix this.
	$phone 		= $_POST['phone'];
	$email 		= $_POST['email']; 		
	$address 	= $_POST['address'];
	$notes 		= $_POST['notes'];
	
	// Validate
	if (!preg_match(EMAIL_REGEX, $email))
	{
		error_log("Email address '$email' was invalid.");
		return false;
	}
	
	if ($_POST["deliverymode"] == "address")
	{		
		// * POST Voucher Mode
		// ---------------------------------------------
		// Send an email to the office so they can print 
		// and send the voucher(s)
		
		$body = <<<EMAIL
		<h3>Gift Voucher Order</h3>
		<p><b>Voucher Info</b></p>
		<ul>
			<li>Vouchers:		$qty</li>
			<li>Value/Each: 	$amount</li>
			<li>Name:			$name</li>
			<li>Address: 		$address</li>
			<li>Phone: 			$phone</li>
			<li>Email: 			$email</li>

			
		</ul>
		<p><b>Transaction Info</b></p>
		<ul>
			<li>Transaction #: 	$transactionid</li>
			<li>Auth Code #: 	$authcode</li>	
			<li>Order Total: 	$total_to_process</li>
		</ul>
		<p><b>Notes</b></p>
		<p>$notes</p>
EMAIL;
				
		$to = VOUCHER_NOTIFICATION_EMAIL_TO; 
	    $from = VOUCHER_NOTIFICATION_EMAIL_FROM; 
		$subject = "Gift Voucher Order for ". $name . " ($".$total_to_process.")";
		
		// Send email to Office
		PHPMail($to, $from, "Spirit House Vouchers", $subject, $body);
		
		// Thank you email
		thanksMail($_POST, $transactionid, false);
		
		// We return an array with the mode
		// since that's all we need.
		return array("mode" => "post");
	
	}
	elseif ($_POST["deliverymode"] == "email")
	{
		// * Email/Download Voucher Mode
		// ---------------------------------------------
		// Links to voucher PDFs will be sent in an email
		// and generated on the fly.
		
		$lastVoucherNumber = getLastVoucherNumber();
		
		// fromname is optional -- fill in if needed
		// TODO: Mark as optional in form (?)
		if (!$_POST['fromname']) { 
			$fromname = $name; 
		} 
		else { $fromname = $_POST['fromname']; }
		
		// Setup some other vars
		$expiry_month = date("m", time());
		$expiry_year = date("Y", time() + 31556926);
		
		// Loop through each voucher recipient and generate a 
		// voucher, then insert it into the database
		$vouchers = array();
		for ($i = 1; $i <= $qty; $i++)
		{
			$recipientname = $_POST['recipient' . $i]; 
			$vouchernumber = $lastVoucherNumber + $i; // Increment
			$securitycode = rand(1000, 9999);			
			
			// Split for first/last name
			$split = explode(" ", trim($name));
			if (count($split) > 1)
			{
				$lastname = array_pop($split);
				$firstname = implode(" ", $split);
			}
			else
			{
				$lastname = '';
				$firstname = $name;
			}
			
			// Create a voucher object & insert() it (see Voucher.class.php)
			$voucher = new Voucher($firstname, $lastname, $fromname, $recipientname, $email, 
				$vouchernumber, $securitycode, $amount, $expiry_month, $expiry_year, 1, '');
			if ($voucher->insert())
			{
				$vouchers[] = $voucher;
			}
			else
			{
				// TODO: Send email to Acland
				// mail("acland@aclandbrierty.com", 'sds-spirithouse', mysql_error());
				error_log("Error Inserting Voucher: " . mysql_error());
				error_log("SQL Used: " . $voucher->getInsertSQL());
			}
		}
		
		// Did all the vouchers get generated?
		if (count($vouchers) == $qty)
		{
			// We need to forumulate a custom URL which will link to a PDF of vouchers
			// http://www.spirithouse.com.au/voucherPDF.php (cont.)
			// ?vkeys=VOUCHERNUM1|CODE1,VOUCHERNUM2|CODE2,...&auth=md5( VOUCHERNUM1 + CODE1 + MD5_SALT)
			
			// Note: We're NOT actually generating a PDF file right now.
			// We're making a URL to a script which will do that on the fly.
			
			$baseURL = VOUCHER_DOWNLOAD_URL;
			$vkeys = array();
			foreach ($vouchers as $v)
			{
				$vkeys[] = $v->number . "|" . $v->security;
			}
			$vkeys = implode(",", $vkeys);			
			$auth = $vouchers[0]->getAuthToken();
			
			$href = $baseURL . "?vkeys=" . $vkeys . "&auth=" . $auth; 
			
			// Send thank you email WITH link to voucher(s)
			thanksMail($_POST, $transactionid, $href);
			
			return array("mode" => "email", "href" => $href);
		}
		else
		{
			error_log("Not all vouchers were generated!");
			return false; // no bueno!
		}			
	} 
}

function thanksMail($_POST, $transactionid, $href) 
{
	$qty_vouchers_ordered = $_POST['qty'];
	$value_of_each_voucher_ordered = $_POST['amount'];
	$customer_address = $_POST['address'];

	$total_value_of_order = number_format($qty_vouchers_ordered * $value_of_each_voucher_ordered, 0);

	$email = $_POST['email'];
	$content = "<p>Hi " . $_POST['firstname'] . "</p>";
	
	if($qty_vouchers_ordered > 1) 
	{
		$content.="<p>Thanks for purchasing ".$qty_vouchers_ordered." vouchers at a value of $".$value_of_each_voucher_ordered." each! ";
	} 
	else 
	{
		$content.="<p>Thanks for purchasing ".$qty_vouchers_ordered." voucher at a value of $".$value_of_each_voucher_ordered.". ";
	}
	
	$content .= "Your credit card has been charged $".$total_value_of_order.". Your transaction reference number is: ".$transactionid."</p>";
	
	// Download
	if ($href)
	{
		$subject = "Your Spirit House Gift Vouchers";
		$content .= "<p style='font-size: 20px;'><a href='$href'>Download Voucher(s)</a></p>";
		
	}
	// By Post
	else
	{
		$subject = "Spirit House Gift Voucher";
		$content .= "<p>Your voucher will be posted on the next business day!</p>";
	}

	$content .= "<p>If you have any questions about your purchase please feel free to give us a call at the office: (07) 5446 8977 &mdash; we'll be happy to assist you however we can.</p>
	<p>Thanks again for your purchase,<br /><br />The Spirit House Team</p>";

	/* 
		Wrapping this with HTML (below)	 triggers the spam filter in my gmail
		Disabling it for now... 
	*/
	$content = email_template($content);
	
	$to = $email; 
	$from = VOUCHER_NOTIFICATION_EMAIL_FROM;
	
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
    

	
	
	
		
	
	//PHPMail($to, $from, "Spirit House Vouchers", $subject, $content);
}

function email_template($content) 
{

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
    <img src='http://www.spirithouse.com.au/resources/new-logo.png'>
  	</center>";
    $output.="<div style='padding:10px; '>";
    $output.=$content;
    $output.="</div></div></body></html> <html> ";

    return $output;
}

function select_year($name, $class) {
  $ret="<select name='$name' class='$class'>";
  for($j=date("Y");$j<2024;$j++) {
    $ret.="<option value='".substr($j,-2, 2)."'>$j</option>";
  }
  $ret.="</select>";
  return $ret;
}

function select_month($name, $class) {
  $ret="<select name='$name' class='$class'>";
  for($j=1;$j<13;$j++) {
    $ret.="<option value='";
    if(strlen($j)==1) $ret.="0";
    $ret.="$j'>".monthname($j)."</option>";
  }
  $ret.="</select>";
  return $ret;
}

function monthname($monthnum) {

  switch($monthnum) {
  case 1: return "January";break;
  case 2: return "February";break;
  case 3: return "March";break;
  case 4: return "April";break;
  case 5: return "May";break;
  case 6: return "June";break;
  case 7: return "July";break;
  case 8: return "August";break;
  case 9: return "September";break;
  case 10: return "October";break;
  case 11: return "November";break;
  case 12: return "December";
  }
}

?>