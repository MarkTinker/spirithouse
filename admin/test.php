<?php
include("head.php");
head();
$this_link = htmlentities( $_SERVER['PHP_SELF'] );
$letters = range('A', 'Z');
$menu = '<ul id="navlist">'."\n";
$t=0;
foreach ( $letters as $letter ) {
$menu .= '<li><a href="'. $this_link .'?var='. $letter .'">'. $letter .'</a></li>'."\n";
$t=$t+1;
        if($t==13){
         $menu .= '</ul><ul id="navlist">'."\n"; }
}
  
$menu .= '</ul>'."\n";

       
if ( isset( $_GET['var'] ) AND in_array( $_GET['var'], $letters ) ) {
 
          // CONNECT TO DATABASE AND QUERY
          include("../databaseconnect.php");
          include("includes/auth.inc.php");
          $letter=$_GET['var'];
          $sql = "SELECT * FROM mail_list WHERE mailSurname LIKE  \"".$letter."%\" ORDER BY mailSurname, mailFirstname"; 
           echo " this is the sql: $sql<br> letter: $letter";
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

    echo"<tr class='bg'><td height='25'>$title</td>";

        echo" <td height='25'>$name</td>";
        echo" <td height='25'>$surname</td>";
        echo" <td height='25'>$add1</td>";
        echo" <td height='25'>$add2</td>";
        echo" <td height='25'>$city</td>";
        echo" <td height='25'>$code</td>";
        echo" <td height='25'>$state</td>";

        echo"<td height='25'><a href='mail_list.php?action=edit&itemId=$mailid'>Edit Item</a></td>";
        echo"<td height='25'><a href='?action=delete&itemId=$mailid'>Remove</a></td>";

    echo "</tr>";

  }
    echo "</table>"; 
         
 
          } else {
 
         

          echo '<p>Please select a letter</p>';

         

          }
 
       // DISPLAY LINKS   
 
      echo $menu;
 
       

      ?> 


