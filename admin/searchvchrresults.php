<?php
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");
include("../includes/Voucher.class.php");
require '../includes/PHPMailer/PHPMailerAutoload.php';

//require_once "Mail.php";
//require_once 'Mail/mime.php';

//****** code to update a change in voucher details*****/////
if(isset($_POST['submitexpiry'])) {
$field=$_POST['field'];
$email=quote_smart($_POST['email']);
$voucherstatus= quote_smart($_POST['voucherstatus']);
$oldvoucherstatus= quote_smart($_POST['oldvoucherstatus']);

$presentdate= date("d-m-Y", strtotime("now"));

if($voucherstatus<>$oldvoucherstatus) {

  switch ($voucherstatus) {
    case 1:
        $presentnotes="Changed to ACTIVE - " ;
        break;
    case 2:
        $presentnotes="Changed to PRESENTED - " ;
        break;
    case 3:
        $presentnotes="Changed to HOLDING - " ;
        break;
        
    case 4:
        $presentnotes="Changed to EXPIRED - " ;
        break;
        
    case 5:
        $presentnotes="Changed to CANCELLED - " ;
        break;
    
    case 6:
        $presentnotes="Told us they LOST/LEFT BEHIND the voucher - " ;
        break;
	}
}

	$voucherupdatemessage = "Voucher status was successfully changed";
	$notes= " &mdash; $presentnotes $presentdate";

#### code to take care of a change in voucher amount and voucher expiry #####
$newvoucheramount= quote_smart($_POST['amount']);
$oldvoucheramount= quote_smart($_POST['oldvoucheramount']);
$oldvoucherexpirymonth= quote_smart($_POST['oldvoucherexpirymonth']);
$newvoucherexpirymonth= quote_smart($_POST['month']);

if ($oldvoucheramount<>$newvoucheramount){
$presentnotes.="Value changed from &#36;$oldvoucheramount to &#36;$newvoucheramount - ";
$notes= " &mdash; $presentnotes $presentdate.";
//echo"updated text will read: $notes";
//exit();
}

if($oldvoucherexpirymonth<>$newvoucherexpirymonth){
$presentnotes.="Expiry month changed from $oldvoucherexpirymonth to $newvoucherexpirymonth - ";
$notes= " &mdash; $presentnotes $presentdate.";

}

//echo  "here are the amounts $oldvoucheramount and $newvoucheramount";
//exit();
##############################################################




  //****** next two lines grab the date so we can change expiry dates ****//// 
  $voucherid=quote_smart($_POST['voucherid']);
  $sql="update vouchers set voucheramount=$newvoucheramount, voucherexpirymonth=".quote_smart($_POST['month']).", voucherexpiryyear=".quote_smart($_POST['year']).", voucherstatus=$voucherstatus, fromname=".quote_smart($_POST['from']).", vouchernotes=".quote_smart($_POST['notes']).", recipientname=".quote_smart($_POST['recipient']).", vouchernotes= CONCAT(`vouchernotes`, '".$notes."'), email=$email where voucherid=$voucherid";
  
  
  
  //echo $sql;
  mysql_query($sql) or print ("Can't insert into table.<br />" . $sql . "<br />" . mysql_error());
	$number=quote_smart($_POST['vouchernumber']);
  header("location: searchvchr.php?number=$number");
  
  exit();

}

//****** code to update a change in email then resend voucher *****/////
if(isset($_POST['submitemail'])) 
{
  $email=quote_smart($_POST['email']);
  $voucherid=quote_smart($_POST['voucherid']);
  $sql="update vouchers set email=$email where voucherid=$voucherid";
  //echo $sql;
  mysql_query($sql) or print ("Can't insert into table.<br />" . $sql . "<br />" . mysql_error());
	$number=quote_smart($_POST['vouchernumber']);
	

				
  $v = Voucher::loadVoucherByNumber($_POST['vouchernumber']);
  if (!$v)
  {
    die("Voucher #" . $_POST['vouchernumber'] . " does not exist.");
  }
  $auth = $v->getAuthToken();
  $voucherURL = "http://www.spirithouse.com.au/voucherPDF.php" . "?vkeys=" . $v->number . "|" . $v->security . "&auth=" . $auth; 
  
	$email_message .= "<html><title>Spirit House Gift Voucher</title><head>
			
<style>
	body{
		background: #FED;
		font-family: 'Lucida Grande', sans-serif;
		margin:50px 0px; padding:0px;
		text-align:center;	}
	
	div{
		background: white;
		border: 2px solid #DDD;
		padding: 15px;
		margin: 0 auto;
		width:500px;
	}
	h1{
		font-size: 1em;
	}
	p{
		font-size: 0.75em;
		text-align:left;
	}
	
	
</style>
</head>
<body>

<div>
<h1>Thanks for purchasing a Spirit House e-mail voucher</h1>
<p>You download your voucher in <em>.pdf format</em> by clicking the image or link below.</p>
<a href='$voucherURL'><img src='http://www.spirithouse.com.au/resources/smallvoucherpic.jpg' alt='smallvoucherpic' width='160' height='73' /></a><br>
<a href='$voucherURL'>Download Voucher</a>

<p>Simply print your voucher, fold along the fold marks and trim.</p>
<p>Your voucher can be used at the <a href='http://www.spirithouse.com.au'>Spirit House restaurant or cooking school</a>. You can <a href='http://spirithouse.com.au/school'>book classes online </a>at the cooking school using the voucher number at the bottom left of your voucher.</p>

</body>
</html>				
				
				
				";


				
				
					
				
	$email_to = $v->email;		
					
	//$ok = @mail($email_to, $email_subject, $email_message, $headers); 
					
					
	######### below is the new mail script using smtp and PEAR ########
    $content=$email_message;
    $subject="Your SpiritHouse Vouchers"; 
    $recipient= $v->firstname . "<$email_to>";
    $firstname = $v->firstname;		
    		
    		// NEW PHP MAILER TESTING STUFF
			
				
	   
				/**
				 * This example shows settings to use when sending via Google's Gmail servers.
				 */
				
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
				$mail->addAddress($email_to, $firstname);
				$mail->AddCC('acland@aclandbrierty.com', 'Acland brierty');
				
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
				if (!$mail->send()) {
				    echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
						############## SEND CONF TO COOKING SCHOOL ##################
						$basicheaders = "From: office@spirithouse.com.au\r\n" .'X-Mailer: PHP \r\n' ."MIME-Version: 1.0\r\n" ."Content-Type: text/html; charset=utf-8\r\n" ."Content-Transfer-Encoding: 8bit\r\n\r\n";
						$email_message2 = 
						"The voucher is from $v->fromName care of $v->firstName $v->lastName < $email >
						The voucher is to $v->recipientName
						Amount: \$$v->amount
						< $v->number - $v->security >
						Expires: $v->expiryMonth/$v->expiryYear
						Notes: $v->notes --- Vchr: <a href='$voucherURL'>Download Link</a>";
						
											$ok2 = @mail('cookingschool@spirithouse.com.au', 'e-mail Voucher Resent from back end',
											 $email_message2, $basicheaders);
											
										    header("location: searchvchr.php?number=$number");
						  
											exit();
				}


	}


// We preform a bit of filtering
$find = strtoupper($_POST['find']);
$find = strip_tags($find);
$find = trim ($find);

////////////////
$emailfind= quote_smart($_GET['emailfind']);
//echo "this is the find string: $emailfind";
//exit();
//////////////

//$find= $_POST['find'];
$field= $_POST['field'];
head();

//$sql = "select * from vouchers where vouchers.vouchernumber == \"%".$find."%\"";
//$result=mysql_query($sql);
//$no=mysql_num_rows($result);

?>


<p><h3>Searching <i><?php echo $field; ?></i> for: <?php echo $find; ?></h3></p>
<?php


////

if (($field=="vouchernumber")AND(is_numeric ($find))){
  $sql = "select * from vouchers where vouchers.vouchernumber LIKE \"".$find."%\"";
	};

if (($field=="contactdetails")OR(!is_numeric ($find))){
  $sql = "select * from vouchers WHERE vouchers.firstname LIKE \"".$find."%\" OR vouchers.lastname LIKE \"".$find."%\" OR vouchers.fromname LIKE \"%".$find."%\" OR vouchers.email LIKE \"".$find."%\" OR vouchers.recipientname LIKE \"".$find."%\" order by vouchers.voucherexpiryyear DESC ";
	};
//////////////////////////////////////////////////////////////////////////////////////////////	
////////this function tries to get a variable from email vouchers from the viewcerts.php page
if ($emailfind>1) {
$sql = "select * from vouchers where vouchers.vouchernumber =".$emailfind."";
};
////////////////////////////////////////////////////////////////////////////////////////////

  $result = mysql_query($sql);
  $no = mysql_num_rows($result);
  //echo "this is the sql: $sql";
  ?>
  <p><h3>All vouchers </h3></p>
  <center>
  <table width='90%'>
  	<tr>
  		<td>Voucher#</td>
  		<td>Purchaser's Details</td>
  		<td>From / To:</td>
  		<td>Amount</td>
  		<td>Expiry Month</td>
  		<td>Expiry Year</td>
  		<td>Notes</td>
  		<td>Status</td>
  		<td>Update Details</td>
  		<td>Delete</td>
  	</tr>
  <?php
  for($t=0;$t<$no;$t++)
    {
    $voucherid=trim(mysql_result($result,$t,"voucherid"));
    $security=trim(mysql_result($result,$t,"security"));
    $security=substr($security,-4);
	if ($security==0) { $security = "postal"; }; 
    $firstname=trim(mysql_result($result,$t,"firstname"));
    $lastname=trim(mysql_result($result,$t,"lastname"));
    $from=trim(mysql_result($result,$t,"fromname"));
    $recipient=trim(mysql_result($result,$t,"recipientname"));
    $email=trim(mysql_result($result,$t,"email"));
    $voucheramount=trim(mysql_result($result,$t,"voucheramount"));
    $voucherstatus=trim(mysql_result($result,$t,"voucherstatus"));
    $vouchernumber=trim(mysql_result($result,$t,"vouchernumber"));
    $vouchernotes=trim(mysql_result($result,$t,"vouchernotes"));
    $voucherexpirymonth=trim(mysql_result($result,$t,"voucherexpirymonth"));
    $voucherexpiryyear=trim(mysql_result($result,$t,"voucherexpiryyear"));
    
    $delete="<a href='javascript:if(confirm(\"Are you sure?\")) { document.location.href=\"viewcerts.php?view=all&action=delete&voucherid=$voucherid\"; }'>delete</a>";

    $newexpirymonth=date("m");
    $newexpiryyear=date("Y");
    $voucherstatus_select="<select id='status_$voucherid'  style='font-size:10px' name='voucherstatus'>";

    $voucherstatus_select.="<option value=1 ";
    if($voucherstatus==1) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Active</option>";
    $voucherstatus_select.="<option value=2 ";
    if($voucherstatus==2) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Presented</option>";
    $voucherstatus_select.="<option value=3 ";
    if($voucherstatus==3) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Holding</option>";
    $voucherstatus_select.="<option value=4 ";
    if($voucherstatus==4) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Expired</option>";
    $voucherstatus_select.="<option value=5 ";
    if($voucherstatus==5) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Cancelled</option>";    
    $voucherstatus_select.="<option value=6 ";
    if($voucherstatus==6) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Lost</option>";
    $voucherstatus_select.="</select>";
    
    // if the vouchers are not postal then show the email details for updates or resending
    if (is_numeric($security)) {
    $resendemail= "<input type='submit' name='submitemail' value='Resend e-mail'>";
    $details="$firstname, $lastname <br/><small><input type=\"text\" value=\"$email\" name=\"email\" /></small><br/>";
    $fromto="<table border='0'>
    			<tr>
    				<td align='right' style='display:table-cell; vertical-align:middle'><strong>From:</strong> &nbsp;</td>
    				<td>
    				<textarea rows='2' cols='20' id='from' name='from'>$from</textarea></td>
    			</tr>
    			<tr>
    				<td align='right' style='display:table-cell; vertical-align:middle'><strong>To:</strong> &nbsp;</td>
    				<td><textarea rows='2' cols='20' id='recipient' name='recipient'>$recipient</textarea></td>
    			</tr>
    		</table>";
    
    } else {
    $fromto = "<textarea rows='2' cols='20' id='from' name='from'>$from</textarea>";
    $resendemail='';
    $details='';
    
    }
     $v = Voucher::loadVoucherByNumber($vouchernumber);

  $auth = $v->getAuthToken();
  $voucherURL = "http://www.spirithouse.com.au/voucherPDF.php" . "?vkeys=" . $v->number . "|" . $v->security . "&auth=" . $auth; 
  

    echo "<form METHOD='POST' ACTION='searchvchrresults.php' ID='form1' NAME='form1'>
<tr>
   
    <td>$vouchernumber<br/><small>-$security</small><br> $resendemail
    <br><a href='$voucherURL'>Download</a>
    </td>
    
    
    
    
    
    <td>$details</td>
    <td>$fromto</td>
    <!-- <td>$$voucheramount</td> -->
    <td><input type='text' size=6 value='$voucheramount' id='amount' name='amount'></td>
    <td><input type='text' size=6 value='$voucherexpirymonth' id='month' name='month'></td>
    <td><input type='text' size=6 value='$voucherexpiryyear' id='year' name='year'></td>
    <td><textarea rows='4' cols='40' id='notes' name='notes'>$vouchernotes</textarea></td>
  
    <td>$voucherstatus_select</td>
     <td> 
     	<input type='hidden' name='voucherid' value='$voucherid'>
    	<input type='hidden' name='vouchernumber' value='$vouchernumber'>
    	<input type='hidden' name='oldvoucheramount' value='$voucheramount'>
    	<input type='hidden' name='oldvoucherstatus' value='$voucherstatus'>
    	<input type='hidden' name='oldvoucherexpirymonth' value='$voucherexpirymonth'>
    	
    	<input type='submit' name='submitexpiry' value='Update Details'>
  	</td>
    <td>$delete</td>
    </tr></form>";
    }
  ?>
  </table>
  </center>





<?php
foot();
?>