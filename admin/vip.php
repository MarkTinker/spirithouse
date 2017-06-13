<?
include("head.php");
//include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");



head();

$sql="SELECT `bookingid`, `lastname`, `firstname`, `email`, `phone`, `vip`,  COUNT(`bookingid`) as 'times_booked', SUM(`seats`)as 'seats-booked'
FROM bookings  WHERE `seats`>0 GROUP BY `lastname`, `firstname` ORDER BY `times_booked`  DESC";


$result=mysql_query($sql);
$no=mysql_num_rows($result);
//$maxseats=quote_smart($_GET['maxseats']);

?>



<p><h3>View bookings for this class</h3></p>


  <table>
    <tr>
      <th>Name</th>
      <th>VIP</th>
      <th>Contact Info</th>
      <th>Times Booked</th>
      <th>Total Seats Booked</th>
      <th>UPDATE</th>
      
      <!--<th>Delete</th>-->
    </tr>
    <?

    $totalseats=0;
      for($t=0;$t<$no;$t++)
      {
      $bookingid=trim(mysql_result($result,$t,"bookingid"));
      $lastname=trim(mysql_result($result,$t,"lastname"));
      $firstname=trim(mysql_result($result,$t,"firstname"));
      $vip=trim(mysql_result($result,$t,"vip"));
      $email=trim(mysql_result($result,$t,"email"));
      $phone=trim(mysql_result($result,$t,"phone"));
      $times=mysql_result($result,$t,"times_booked");
      $seats=mysql_result($result,$t,"seats-booked");
       
       
       
       ?>
       
    <tr align="left">
      <td><?=$firstname." ".$lastname;?></td>
      <td><?=$vip; ?></td>
      <td><?=$phone." / ".$email;?></td>
      <td><?=$times;?></td>
      <td><?=$seats;?>&nbsp;</td>
          
    <? 
      //if times booked is greater then x then add that number to the VIP field in the customers bookings table
            
      if($times>1){
      				$vip=0;
  					//$update="update bookings set `vip`= $times where `bookingid`=$bookingid";
  					$update="update bookings set `vip`= $times where `firstname`= '$firstname' AND `lastname`= '$lastname'";
 	 				echo "<td>bookingid: $bookingid</td>";
 	 				mysql_query($update);
      
      				}
      
      echo "</tr>";
      
      $times=0;
      }
    ?>
    
  </table>



</span>

<?
foot();
?>