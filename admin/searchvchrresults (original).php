<?php
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");

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
  $sql="update vouchers set voucheramount=$newvoucheramount, voucherexpirymonth=".quote_smart($_POST['month']).", voucherexpiryyear=".quote_smart($_POST['year']).", voucherstatus=$voucherstatus, fromname=".quote_smart($_POST['from']).", recipientname=".quote_smart($_POST['recipient']).", vouchernotes= CONCAT(`vouchernotes`, '".$notes."'), email=$email where voucherid=$voucherid";
  
  
  
  //echo $sql;
  mysql_query($sql) or print ("Can't insert into table.<br />" . $sql . "<br />" . mysql_error());
	$number=quote_smart($_POST['vouchernumber']);
  header("location: searchvchr.php?number=$number");
  
  exit();

}

//****** code to update a change in email then resend voucher *****/////
if(isset($_POST['submitemail'])) {
$email=quote_smart($_POST['email']);
  //****** next two lines grab the date so we can change expiry dates ****//// 
  $voucherid=quote_smart($_POST['voucherid']);
  $sql="update vouchers set email=$email where voucherid=$voucherid";
  //echo $sql;
  mysql_query($sql) or print ("Can't insert into table.<br />" . $sql . "<br />" . mysql_error());
	$number=quote_smart($_POST['vouchernumber']);
	
//****** Send email *******
	
	
			$sql="select * FROM vouchers WHERE vouchernumber = $number ORDER BY vouchernumber DESC LIMIT 1";
			$result=mysql_query($sql) or print ("Can't Select into table.<br />" . $sql . "<br />" . mysql_error());			

while ($row = mysql_fetch_array($result))
	{
		$name = $row["firstname"]." ".$row["lastname"];
		$toname = $row["recipientname"];
		
		if ($row["fromname"]) {
			$fromname = $row["fromname"];			
		} else {
			$fromname = $name;
		};
		
		$email = $row["email"];
		$securitycode = $row["security"];
		$amount = $row["voucheramount"];
		$expiry_month = $row["voucherexpirymonth"];
		$expiry_year = $row["voucherexpiryyear"];
		$expiry = $expiry_month."/".$expiry_year;
		$vouchernum = $row["evouchernumber"];
		$datai = "../voucherbase3.jpg"; 
		
		$fileatt = "/home/sh/public_html/voucherimages/"; // Path to the file 

		$id = "< ".$vouchernum." >";
	};

					// Create image file and send email.			

					  $im = imagecreatefromstring( fread( fopen( $datai, "r" ), filesize( $datai ) ) );  
					
					  $black = imagecolorallocate($im, 0, 0, 0); 
					  $red = imagecolorallocate($im, 255, 0, 0); 
					
					
imagettftext($im, 22, 0, 340, 880, $black,"/home/sh/public_html/arial_italic.ttf",$toname);
imagettftext($im, 22, 0, 340, 942, $black,"/home/sh/public_html/arial_italic.ttf",$fromname);
imagettftext($im, 30, 0, 340, 1005, $black,"/home/sh/public_html/arial_italic.ttf","\$".$amount);
imagettftext($im, 22, 0, 720, 1005, $black,"/home/sh/public_html/arial_italic.ttf",$expiry);

imagettftext($im, 14, 0, 112, 1155, $red,"/home/sh/public_html/arial_italic.ttf",$id);
imagettftext($im, 14, 180, 1120, 70, $red,"/home/sh/public_html/arial_italic.ttf",$id." Security Code: ".$securitycode);				

					  imagejpeg($im, "../voucherimages/temp".$vouchernum.".jpg", 40); 
					
					  imagedestroy($im); 
					
					$email_message ="";
					$fileatt = "/home/sh/public_html/voucherimages/"; // Path to the file 
					$fileatt_type = "image/jpeg"; // File Type 
					$fileatt_name = "temp".$vouchernum.".jpg"; // Filename that will be used for the file as the attachment 
										
					$email_from = "office@spirithouse.com.au"; // Who the email is from 
					$email_subject = "Spirit House email Voucher for $toname"; // The Subject of the email 					
					$email_to = $email; // Who the email is too 
					
					$headers = "From: ".$email_from; 
					
					
					$file = fopen($fileatt.$fileatt_name,'r'); 
					$data = fread($file,filesize($fileatt.$fileatt_name)); 
					fclose($file); 
					unlink ("../voucherimages/temp".$vouchernum.".jpg");
					
					$semi_rand = md5(time()); 
					$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
					
					$headers .= "\nMIME-Version: 1.0\n" . 
					"Content-Type: multipart/related;\n" . 
					" boundary=\"{$mime_boundary}\""; 
					
					$email_message .= "This is a multi-part message in MIME format.\n\n" . 
					"--{$mime_boundary}\n" . 
					"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
					"Content-Transfer-Encoding: 7bit\n\n" . 
					"
					<style>
	body{
		background: #FED;
		font-family: sans-serif;
	}
	div{
		background: white;
		border: 2px solid #DDD;
		padding: 15px;
		width: 500px;
		margin: 0 auto;
	}
	h1{
		font-size: 1em;
	}
	p{
		font-size: 0.75em;
	}
</style>


<div>
<h1>Thanks for purchasing a Spirit House e-mail voucher</h1>

<p>You can print out the attached voucher or forward it to the recipient's e-mail address.<br><br>
Simply print your voucher, fold along the fold marks and trim.
<br>Your voucher can be used at the <a href='http://www.spirithouse.com.au'>Spirit House restaurant or cooking school</a>. You can <a href='http://spirithouse.com.au/school'>book classes online </a>at the cooking school using the voucher number at the bottom left of your voucher.</p>

<img style=\"width: 500px; height: 500px\" src=\"cid:PHP-CID-". $semi_rand ."\" />



</div>
					
					".
					$email_message .="\n\n"; 
					
					$data = chunk_split(base64_encode($data)); 
					
					$email_message .= "--{$mime_boundary}\n" .
					"Content-Type: ".$fileatt_type." ;\n" .
					"Content-Transfer-Encoding: base64\n" .
					"Content-ID: <PHP-CID-". $semi_rand .">\n\n" .
					$data."\n\n" .
					
					"--{$mime_boundary}--\n"; 
					
					$email_to = trim($email_to, "'");		
					
					$ok = @mail($email_to, $email_subject, $email_message, $headers); 
					
					
					
					
// message to cooking school //					
$email_message2 = 
"The voucher is from $fromname care of $firstname $lastname < $email >
The voucher is to $toname
Amount: \$$amount
< $vouchernum - $securitycode >
Expires: $expiry_month/$expiry_year
Notes: $notes
";
					$ok2 = @mail('cookingschool@spirithouse.com.au', 'e-mail Voucher Resent from back end', $email_message2, $headers); 
					#$ok2 = @mail('dan@civicnet.com.au', 'e-mail Voucher Purchase', $email_message2, $headers); 

					// End create image file and send email.	
					
	
  header("location: searchvchr.php?number=$number");
  
  exit();

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
  $sql = "select * from vouchers WHERE vouchers.firstname LIKE \"".$find."%\" OR vouchers.lastname LIKE \"".$find."%\" OR vouchers.fromname LIKE \"%".$find."%\" OR vouchers.email LIKE \"".$find."%\" OR vouchers.recipientname LIKE \"".$find."%\" ";
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
    
  

    echo "<form METHOD='POST' ACTION='searchvchrresults.php' ID='form1' NAME='form1'>
<tr>
   
    <td>$vouchernumber<br/><small>-$security</small><br> $resendemail</td>
    <td>$details</td>
    <td>$fromto</td>
    <!-- <td>$$voucheramount</td> -->
    <td><input type='text' size=6 value='$voucheramount' id='amount' name='amount'></td>
    <td><input type='text' size=6 value='$voucherexpirymonth' id='month' name='month'></td>
    <td><input type='text' size=6 value='$voucherexpiryyear' id='year' name='year'></td>
    <td>".$vouchernotes."&nbsp;</td>
  
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