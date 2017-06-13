<?

/*
From http://www.html-form-guide.com 
This is the simplest emailer one can have in PHP.
If this does not work, then the PHP email configuration is bad!
*/

//$path = get_include_path() . PATH_SEPARATOR . '/home/stickyri/php/';
//set_include_path($path);

 require_once "Mail.php";
 require_once'Mail/mime.php';
 

 
 function send_email($recipient,$bcc, $subject, $content){	
 	$host = "127.0.0.1";
 	$username = "office@spirithouse.com.au";
 	$password = "sp1r1t";
  	$from = "Spirit House <office@spirithouse.com.au>";
 	$crlf = "\n";
 
	
	$to=$recipient.",".$bcc;
	  
	 //by not putting the bcc in the headers, it won't appear on the client's email
	$headers = array ('From' => $from, 'To' => $recipient, 'Subject' => $subject);
	
 
         // Creating the Mime message
        $mime = new Mail_mime($crlf);

        // Setting the body of the email
        //$mime->setTXTBody($text);
        $mime->setHTMLBody($content);
        //$mime->addAttachment($file, 'text/plain');
        //$mime->addHTMLImage(file_get_contents($file),mime_content_type($file),basename($file),false);

        $body = $mime->get();
        $headers = $mime->headers($headers);



//echo"$recipient and to: $to and $subject and $content";
//exit();
 

 $smtp = Mail::factory('smtp', array ('host' => $host,'auth' => true,'username' => $username,'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 

 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("Sent Mail successfully");
  }

}

?>