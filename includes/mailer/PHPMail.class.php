<?php
// ===================================================================  
// PHPMail.php
// =================================================================== 
// Mail function using the PHPMailer class.
// -> http://code.google.com/a/apache-extras.org/p/phpmailer/ 
// Helps prevent spammed emails.
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

require 'class.phpmailer.php';

function PHPMail($to, $from, $fromName, $subject, $content)
{
	try 
	{
		$mail = new PHPMailer(true); //New instance, with exceptions enabled

		$body = $content;

		$mail->IsSMTP();                           // tell the class to use SMTP
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Port       = 25;                    // set the SMTP server port
		$mail->Host       = "mail.spirithouse.com.au"; // SMTP server
		$mail->Username   = "office@spirithouse.com.au";     // SMTP server username
		$mail->Password   = "hP=76>t4e";            // SMTP server password

		$mail->IsSendmail();  // tell the class to use Sendmail

		$mail->AddReplyTo($from, "Spirit House");

		$mail->From       = $from;
		$mail->FromName   = $fromName;

		$mail->AddAddress($to);

		$mail->Subject  = $subject;

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
		$mail->WordWrap   = 80; // set word wrap

		$mail->MsgHTML($body);

		$mail->IsHTML(true); // send as HTML

		if ($mail->Send())
		{
			return true;
		}
		else
		{
			return false;
		}
	} 
	catch (phpmailerException $e)
	{
		echo $e->errorMessage();
		return false;
	}
}

?>