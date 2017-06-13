<?

set_time_limit(9000);

if(!isset($newsletterid)) { header("Location: viewnewsletters.php"); }

include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../class.html.mime.mail.inc");

$sql="select newsletterid, newsletterdate, newslettersubject, newslettertext, layoutformat, serverloc from newsletters where newsletterid = $newsletterid" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);
$strSub = mysql_result($result,0,newslettersubject);
$strText = mysql_result($result,0,newslettertext);
$layoutformat = mysql_result($result,0,layoutformat);
$serverloc = mysql_result($result,0,serverloc);

$sql2="select count(subscriberid) as subs from subscribers;";
$result2=mysql_query($sql2);
$subs = mysql_result($result2,0,subs);

$copyright = "<BR><center><font size=1>© ".date("Y")." Spirit House</font></center>";

if($action=='test') {
	
	if(!isset($previewemail) || !isset($newsletterid)) { header("Location: sendnewsletter.php?"); }

	include("head.php");
	head();

	$tmstamp = date("M d, Y");
				
	$strToEmail = $previewemail;
	$strToName = $previewemail;
	$strFromEmail = "admin@spirithouse.com.au";
	$strFromName = "Spirit House";
	
	$mail = new html_mime_mail('X-Mailer: Html Mime Mail Class');  
	$text = "If you are seeing this, your email program can't support HTML. Please click here to view the newsletter: http://www.spirithouse.com.au/newsletters/news05.html";

	$mail->add_html($strText, $text);
	$mail->set_charset('iso-8859-1', TRUE);
	$mail->build_message();

	$mail->send($strToName, $strToEmail, $strFromName, $strFromEmail, $strSub);	
	
	echo "<form action='sendnewsletter.php?' method='post'>";
	echo "<font face='arial' size=4>C O N F I R M&nbsp;&nbsp;&nbsp;O K ?</FONT><br><BR>";	
	echo "<font face='arial' size=2>The test email has been sent. Please review the test email and click one of the buttons below.<BR><BR>";
	echo "<input type='button' onclick='document.location.href=\"viewnewsletters.php?iuy7d=22\"' value=' :: cancel :: '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<input type='submit' value=' :: send newsletter :: '>";
	echo "<input type='hidden' name='action' value='send'>";
	echo "<input type='hidden' name='newsletterid' value=$newsletterid>";	
	echo "<input type='hidden' name='template' value='$template'>";
	
	echo "</form>";

} else {

	if($action=='send') {
	
		include("head.php");
		head();
		
		echo "<font face='arial' size=4>S E N D&nbsp;&nbsp;&nbsp;N E W S L E T T E R S</FONT><br><BR>";	
		echo "<font face='arial' size=2>Newsletters have been sent.<BR><BR><BR><BR><BR><BR><BR><BR>";

		$tmstamp = date("M d, Y");
					
		$strToEmail = 'admin@spirithouse.com.au';
		$strToName = 'admin@spirithouse.com.au';
		$strFromEmail = "admin@spirithouse.com.au";
		$strFromName = "SpiritHouse";
		
		$mail = new html_mime_mail('X-Mailer: Html Mime Mail Class');  
		$text = "If you are seeing this, your email program can't support HTML. Please click here to view the newsletter: $serverloc ";
					 
		$mail->add_html($strText, $text);
		$mail->set_charset('iso-8859-1', TRUE);
		$mail->build_message();
		
		$sql3="select distinct(subscriberemail) from subscribers;";
		$result3=mysql_query($sql3);
		$no3=mysql_num_rows($result3);
		if($no3!=0)
		{			
			for($i=0;$i<$no3;$i++)
			{
				echo "<table>";
				$subscriberemail = mysql_result($result3,$i,subscriberemail);
				if(strlen(trim($subscriberemail))>0) {
					$mail->send($subscriberemail, $subscriberemail, $strFromName, $strFromEmail, $strSub);	
					echo "<tr><td>to mail:</td><td>$subscriberemail</td></tr>";
          flush();
				}
				echo "</table>";
			}
		} else {
			echo "nothing to do!";
		}
	
	} else {

		include("head.php");
		head();

		echo "<form action='sendnewsletter.php?' method='post'>";
		echo "<font face='arial' size=4>P R E V I E W&nbsp;&nbsp;&nbsp;N E W S L E T T E R</FONT><br><BR>";

		echo "<table cellpadding=8>";
		echo "<tr><td><font face='arial' size=2>Sending newsletter:</td><td><font face='arial' size=2>$strSub</td></tr>";
		echo "<tr><td><font face='arial' size=2>Subscribers on database:</td><td><font face='arial' size=2>$subs</td></tr>";
		echo "<tr><td><font face='arial' size=2>Test email address:</td><td><input type='text' size=40 name='previewemail'><input type='submit' value='test'></td>";
		echo "</table>";

		echo "<input type='hidden' name='action' value='test'>";
		echo "<input type='hidden' name='newsletterid' value=$newsletterid>";
	
		echo "</form>";
	}
}



foot();
?>