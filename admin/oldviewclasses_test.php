<?
include("head.php");
include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();

if(isset($_GET['action']) && $_GET['action']=="updateseats") {
  $sql="update schedule set bookings=".quote_smart($_GET['seatstaken'])." where scheduleid=".quote_smart($_GET['scheduleid']);
  mysql_query($sql);
  echo "Seats adjusted. <a href='notify_waitlist.php?scheduleid=".$_GET['scheduleid']."'>Notify waitlist?</a>";
}

if(isset($_GET['action']) && $_GET['action']=="makebooking") {
  $scheduleid=$_GET['scheduleid'];
  $seatsbooked=$_GET['seatsbooked'];

  $sql="update `schedule` set bookings=".$seatsbooked." where scheduleid=".$scheduleid;
  mysql_query($sql);
  echo $sql."<br />";
  $sql="insert into `bookings` (scheduleid, seats, firstname, lastname) VALUES ($scheduleid, $seatsbooked, 'spirit house', 'booking')";
  mysql_query($sql);
  echo $sql."<br />";
}

$sql="select scheduleid, schedule.classid, date_format(scheduledate, '%W %d %M %y') as date2,
date_format(scheduledate, '%W') as dayname, classname, full, bookings, daynight
from schedule, classes where schedule.classid=classes.classid and scheduledate > NOW() order by scheduledate" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);

?>
<script>
function changeseats(field, scheduleid) {
  var seatstaken = document.getElementById(field).selectedIndex;
  document.location.href="viewclasses.php?action=updateseats&scheduleid="+scheduleid+"&seatstaken="+seatstaken;
}
</script>

<p><h3>View classes</h3></p>

<p><a href="addschedule.php"><b>Add class to the schedule</b></a> | <a href="addclass.php"><b>Add a NEW class</b></a> | <a href="editclass.php"><b>Edit classes</b></a> | <a href="search.php"><b>Search classes</b>
</a>  | <a href='http://www.eway.com.au/login'>eWay admin</a> | <a href='report.php'>Save bookings</a></p>

<form action="classdelete.php?pagecount=<? echo $pagecount;?>&sort=<? echo $sort;?>" method="post">
    <table>
        <tr>
        <th>Date</th>
        <th>Class name</th>
      <th>Day/night</th>
      <th>Seats left</th>
      <th>Bookings</th>
      <th>Add booking</th>
      <th>Waitlist</th>
            <th>Delete</th>
        </tr>
        <?

            for($t=0;$t<$no;$t++)
            {
            $scheduleid=trim(mysql_result($result,$t,scheduleid));
            $classid=trim(mysql_result($result,$t,classid));
            $scheduledate=trim(mysql_result($result,$t,"date2"));
            $classname=trim(mysql_result($result,$t,"classname"));
            $full=trim(mysql_result($result,$t,"full"));
      $bookings=mysql_result($result,$t,"bookings");
      $dayname=mysql_result($result,$t,"dayname");
      $daynight=mysql_result($result,$t,"daynight");

      $seat_status=maxseats-$bookings." seat(s) left";
      if($bookings<maxseats) {
        if($bookings==0) {
          $seat_status="Empty";
        }
      } else {
        $seat_status="FULL!";
      }
      if($daynight==0) { $daynight="Day"; } else { $daynight="Night"; }

      //if($dayname=="Saturday" || $dayname=="Sunday") {
        //$bgcolor="#dddddd";
      //} else {
        //$bgcolor="#ffffff";
      //}
      $makebooking="<a href='http://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid' target='_blank'>add booking</a>";
      $waitlist="<a href='http://www.spirithouse.com.au/waitlist.php?scheduleid=$scheduleid'>w/l</a>";

        ?>
      <tr align="left" class='bg'>
      <input type='hidden' name='action' value='updateseats' />
      <input type='hidden' name='scheduleid' value='<?=$scheduleid?>' />
      <td bgcolor='<?=$bgcolor;?>'><?=$scheduledate;?></td>
      <td width=300 bgcolor='<?=$bgcolor;?>'><?=$classname;?></td>
      <td width=10 bgcolor='<?=$bgcolor;?>'><?=$daynight;?></td>
      <td width=100 bgcolor='<?=$bgcolor;?>' align='center' width=250><?=$seat_status;?></td>
      <td bgcolor='<?=$bgcolor;?>' align='center' width=25><a href='viewbookings.php?action=makebooking&scheduleid=<?=$scheduleid;?>'>view</a>
      <td width=50 bgcolor='<?=$bgcolor;?>' align='center' width=250><?=$makebooking;?></td>
      <td width=50 bgcolor='<?=$bgcolor;?>' align='center'><?=$waitlist;?></td>
      <td bgcolor='<?=$bgcolor;?>' align='center'><input type="checkbox" value="<?=$scheduleid;?>" name="cpid[]"></td>
        </tr>
        <?
            }
        ?>
        <tr>
            <td colspan="4"><a href="addschedule.php"><b>Add class to the schedule</b></a> - <a href="addclass.php"><b>Add a NEW class</b></a></td>
            <td><input type="submit" name="submit" value="Delete"></td>
        </tr>
    </table>
</form>
<?

foot();
?>