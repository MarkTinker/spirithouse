<?php
 require_once "Mail.php";
/*$fp = fsockopen("mail.stickyricecookingschool.com.au", 25, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    echo "I'm connected!<br />\n";
    fclose($fp);
}

*/

 $from = "Spirit House <cookingschool@spirithouse.com.au>";
 $to = "acland <abrierty@gmail.com>";
 $subject = "testing google apps server";
 $body = "Hi,\n\n testing abrierty gmail account?";
 /*
 $host = "mail.spirithouse.com.au";
 $username = "office@spirithouse.com.au";
 $password = "sp1r1t";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }
  */

  
  $host = "smtp.gmail.com";
 $port = "465";
 $username = "cookingschool@spirithouse.com.au";
 $password = "sp1r1t";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'port' => $port,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>SSL message is sent!</p>");
  }
  */
 ?>
 
