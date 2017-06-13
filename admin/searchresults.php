<?
include("head.php");
//include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");


// We preform a bit of filtering
$find = strtoupper($_POST['find']);
$find = strip_tags($find);
$find = trim ($find); 



//$find= $_POST['find'];
$field= $_POST['field'];
head();

$sql = "select bookingid, bookings.scheduleid, firstname, lastname, email, classes.classid, classname, scheduledate, date_format(scheduledate, '%d %M %y') as classdate, seats, nametags, phone, `notes`, `credits`
    from bookings
    left join schedule
    on bookings.scheduleid=schedule.scheduleid
    left join classes
    on schedule.classid=classes.classid
    where bookings.$field LIKE \"%".$find."%\" order by scheduledate";
    
    //echo $sql;
    //where bookings.$field LIKE \"%".$find."%\"and scheduledate>now() order by scheduledate"; - for bookings in the future
$result=mysql_query($sql);
$no=mysql_num_rows($result);

?>


<script>
function changeseats(field, scheduleid) {
  var seatstaken = document.getElementById(field).selectedIndex;
  document.location.href="viewclasses.php?action=updateseats&scheduleid="+scheduleid+"&seatstaken="+seatstaken;
}
</script>

<? if($field=='credits') {
?>

<p><h3>Searching <i><? echo $field; ?></i> for: <? echo $find; ?></h3></p>
<table>
    <tr>
      <th>ID</th>
      <th>Class date</th>
      <th>Class name</th>
      <th>Attendee</th>
      <!--<th>Notes</th>--> 
      <th>Credits</th>
     
    </tr>
 <?

    $totalseats=0;
      for($t=0;$t<$no;$t++)
      {
      $bookingid=trim(mysql_result($result,$t,"bookingid"));
      $scheduledate=trim(mysql_result($result,$t,"scheduledate"));
      $classdate=trim(mysql_result($result,$t,"classdate"));
      $scheduleid=trim(mysql_result($result,$t,"bookings.scheduleid"));
      $classname=trim(mysql_result($result,$t,"classname"));
      $firstname=trim(mysql_result($result,$t,"firstname"));
      $lastname=trim(mysql_result($result,$t,"lastname"));
      $email=mysql_result($result,$t,"email");
      $phone=mysql_result($result,$t,"phone");
      $seats=mysql_result($result,$t,"seats");
      $notes=mysql_result($result,$t,"notes");
      $credits=mysql_result($result,$t,"credits");
      $nametags=mysql_result($result,$t,"nametags");
      $totalseats=$seats+$totalseats;
   
   if ($scheduledate < date("Y-m-d") ){
       $wherearewe="old";
   }else{
       $wherearewe="click>>";
   }

    ?>
    <tr align="left">
      <td><? echo $wherearewe; ?></td>
      <td><a href='viewbookings.php?scheduleid=<? echo $scheduleid;?>&bookingid=<?=$bookingid;?>'><?=$classdate;?></a></td>
      <td><?=$classname;?></td>
      <td><a href='editbooking.php?scheduleid=<? echo $scheduleid;?>&bookingid=<?=$bookingid;?>'><?=$firstname." ".$lastname;?></a></td>
      <!-- <td><?=$notes;?></td>-->
      
      <td><?=$credits;?>&nbsp;</td>
     
    </tr>
    <?
      }
    ?>
    <tr>
     
      <td></td>
      
    </tr>
  </table>


<? }else{
?>





<p><h3>Searching <i><? echo $field; ?></i> for: <? echo $find; ?></h3></p>
<form method="post" name='theForm'>
<input type='hidden' name='scheduleid' value='<? $scheduleid; ?>' />

  <table>
    <tr>
      <th>ID</th>
      <th>Class date</th>
      <th>Class name</th>
      <th>Attendee</th>
      <th>Email</th>
      <th>Seats taken</th>
      <th>Nametags</th>
      <th>Notes</th>
      <th>Delete</th>
    </tr>
    <?

    $totalseats=0;
      for($t=0;$t<$no;$t++)
      {
      $bookingid=trim(mysql_result($result,$t,"bookingid"));
      $scheduledate=trim(mysql_result($result,$t,"scheduledate"));
      $classdate=trim(mysql_result($result,$t,"classdate"));
      $scheduleid=trim(mysql_result($result,$t,"bookings.scheduleid"));
      $classname=trim(mysql_result($result,$t,"classname"));
      $firstname=trim(mysql_result($result,$t,"firstname"));
      $lastname=trim(mysql_result($result,$t,"lastname"));
      $email=mysql_result($result,$t,"email");
      $phone=mysql_result($result,$t,"phone");
      $seats=mysql_result($result,$t,"seats");
      $notes=mysql_result($result,$t,"notes");
      $nametags=mysql_result($result,$t,"nametags");
      $totalseats=$seats+$totalseats;
   
   if ($scheduledate < date("Y-m-d") ){
       $wherearewe="old";
   }else{
       $wherearewe="click>>";
   }

    ?>
    <tr align="left">
      <td><? echo $wherearewe; ?></td>
      <td><a href='viewbookings.php?scheduleid=<? echo $scheduleid;?>&bookingid=<?=$bookingid;?>'><?=$classdate;?></a></td>
      <td><?=$classname;?></td>
      <td><a href='editbooking.php?scheduleid=<? echo $scheduleid;?>&bookingid=<?=$bookingid;?>'><?=$firstname." ".$lastname;?></a></td>
      <td><?=$phone." / ".$email;?></td>
      <td><?=$seats;?></td>
      <td><?=$nametags;?>&nbsp;</td>
      <td><?=$notes;?>&nbsp;</td>
      <td align='center'><input type="checkbox" value="<?=$bookingid;?>" name="bookingid[]"></td>
    </tr>
    <?
      }
    ?>
    <tr>
      <td colspan="5"></td><td><?=$totalseats;?></td>
      <td></td>
      <td>&nbsp;</td><td><input type="submit" name="submit" value="Delete"></td>
    </tr>
  </table>
</form>

<? } ?>



<?
foot();
?>