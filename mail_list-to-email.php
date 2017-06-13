<?
//include("head.php");
include("databaseconnect.php");


$sql="select * from mail_list order by mailSurname" ;


?>

<p><h3>DO NOT RUN THIS - unless you're want add all the snailmail people and give them evouchers!</h3></p>

<?


          $sql = "SELECT * FROM mail_list WHERE mailEmail ='' ORDER BY mailid "; 
           //echo " this is the sql: $sql<br> letter: $letter";
            $result = mysql_query($sql);
            $no = mysql_num_rows($result);
   echo $menu; 
  echo"<table>";
          // DO QUERY AND CHURN OUT RESULTS
  for($t=0;$t<$no;$t++)    {
      $mailid=(mysql_result($result,$t,mailid)); 
      $title=(mysql_result($result,$t,mailTitle));
      $name=(mysql_result($result,$t,mailFirstname));
      $surname=(mysql_result($result,$t,mailSurname));
      $add1=(mysql_result($result,$t,mailAddress1));
      $add2=(mysql_result($result,$t,mailAddress2)); 
      $city=(mysql_result($result,$t,mailCity));  
      $code=(mysql_result($result,$t,mailPostcode));
      $state=(mysql_result($result,$t,mailState));
      $email=(mysql_result($result,$t,mailEmail));
      $voucher = $mailid + 30000;
      $recipientname = $name.' ' .$surname;
      
      $sql2 = "INSERT INTO `vouchers` (`recipientname`, `voucheramount`, `voucherexpirymonth`, `voucherexpiryyear`, `voucherstatus`, `vouchernumber`, `evouchernumber`, `vouchernotes`) VALUES ('$recipientname', 25, 12, 2014, 1, '$voucher', '$voucher', 'voucher for changing from snail mail to email')";
   
     /* if (!mysql_query($sql2))
 		{
   		echo "<tr class='bg'><td height='25'>$sql2 &nbsp; FAILED</td></tr>";
    		}
*/
       	
    	//echo "<tr class='bg'><td height='25'>$sql2 &nbsp;</td>";

    	//echo"<td height='25'>$title</td>";

       // echo" <td height='25'>$recipientname ADDED</td>";
        
        //echo" <td height='25'> no email: $email</td>";


   // echo "</tr>";

  }
    echo "</table>"; 
         
 echo"<h1> $t records ALL DONE</H1>";
          
         




?>