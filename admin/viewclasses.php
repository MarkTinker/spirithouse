<?
include("head.php");
//include("../globals.inc.php");
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

$yesterday=date("Y-m-d", strtotime("-1 days"));
//$yesterday='2008-12-20';
//$yesterday=date("Y-m-d");
$sql="select scheduleid, schedule.classid, date_format(scheduledate, '%W %d %M %y') as date2,
date_format(scheduledate, '%W') as dayname, classname, full, bookings, classseats, daynight, discount, discount_price, discountclassprice, scheduleseats 
from schedule, classes where schedule.classid=classes.classid and scheduledate > '$yesterday' order by scheduledate, daynight" ;
//echo $sql;
$result=mysql_query($sql);
$no=mysql_num_rows($result);


######### Setting delete paramaters for admins #########
$admin= $_COOKIE['admin'];	



?>
<script>
function changeseats(field, scheduleid) {
  var seatstaken = document.getElementById(field).selectedIndex;
  document.location.href="viewclasses.php?action=updateseats&scheduleid="+scheduleid+"&seatstaken="+seatstaken;
}




function standby_rate(scheduleid) {
  if(confirm("Add/Remove Standby Rates?")) {
    var xmlhttp =  new XMLHttpRequest();
    
    var discount_price = prompt("What last minute rate would you like to charge for this class?", "100");
    
    xmlhttp.open('GET', 'http://www.spirithouse.com.au/admin/make_standby.php?scheduleid='+scheduleid+"&discount_price="+discount_price, true);
    xmlhttp.send(null);
   
    document.getElementById('standby_'+scheduleid).innerHTML='DONE!';
  }
}




</script>

<p><h3>View classes</h3></p>
<? echo $yesterday; ?>
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
		<th>Discount Rate</th>
		<th>Delete</th>
		</tr>
		<?

			for($t=0;$t<$no;$t++)
			{
                //if($t>0) {
			$scheduleid=trim(mysql_result($result,$t,scheduleid));
			$classid=trim(mysql_result($result,$t,classid));
			$scheduledate=trim(mysql_result($result,$t,"date2"));
			$classname=trim(mysql_result($result,$t,"classname"));
			$full=trim(mysql_result($result,$t,"full"));
			$isstandby=trim(mysql_result($result,$t,"discount"));
  		$bookings=mysql_result($result,$t,"bookings");
  		$classseats=mysql_result($result,$t,"classseats");
  		$scheduleseats=mysql_result($result,$t,"scheduleseats");
  		$dayname=mysql_result($result,$t,"dayname");
  		$daynight=mysql_result($result,$t,"daynight");
  		
  		$discount_price=mysql_result($result,$t,"discount_price");
  		$discountclassprice=mysql_result($result,$t,"discountclassprice");
      if($discountclassprice>0 && $discountclassprice>$discount_price) $disc_classprice=$discountclassprice;
			if($discount_price>0 && $discount_price>$discountclassprice) $disc_classprice=$discount_price;  
    	
    	
    	 // checking to see if class seats is being over-ridden by the scheduled amount of seats
  		// we can override the default classseats in the schedule now - this checks to see
  		// if a class has more or less seats than default for today 
  		if($scheduleseats>0){
  		$maxseats=$scheduleseats;
  		$overrideclass = " class='override'";
  		}else{
  		$maxseats=$classseats;
  		$overrideclass = "";
  		} 
       
       #----- adding classes in the schedule to standby rate codes ----#
       if ($isstandby>0){
       
       $standby="<a href='#' onclick='standby_rate(".$scheduleid.")'><font color='red'>CXL Standby?</font></a> <small>{$disc_classprice}</small>";
       }else{
      $standby="<a href='#' onclick='standby_rate(".$scheduleid.")'>Standby Rate?</a>";
		}
      $seat_status=$maxseats-$bookings." seat(s) left";
      
      #----- add up all the bookings ----#
      $totalseatsbooked=$totalseatsbooked+$bookings;
      $totalseatsscheduled=$totalseatsscheduled+$maxseats;
      
      if($bookings<$maxseats) {
        if($bookings==0) {
          $seat_status="Empty";
        }
      } else {
        $seat_status="FULL!";
      }
      if($daynight==0) { $daynight="Day"; } else { $daynight="Night"; }

      if($dayname=="Saturday" || $dayname=="Sunday") {
        $rowtype="we";
      } else {
        $rowtype="bg";
      }
      
      
      $makebooking="<a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid' target='_blank'>add booking</a>";
      $waitlist="<a href='http://www.spirithouse.com.au/school-waitlist.php?scheduleid=$scheduleid'>w/l</a>";

		?>		
	  <tr align="left" class='<?=$rowtype;?>'>
      <input type='hidden' name='action' value='updateseats' />
      <input type='hidden' name='scheduleid' value='<?=$scheduleid?>' />
	  <td><?=$scheduledate;?></td>
	  <td width=300<?=$overrideclass; ?>><? echo"$classname" ;?></td>
      <td width=10><?=$daynight;?></td>
      <td width=100 align='center' width=250><?=$seat_status;?></td>
      <!--<td align='center' width=25><a href='viewbookings.php?maxseats=<?=$maxseats;?>&scheduleid=<?=$scheduleid;?>'>view</a>-->
       <td align='center' width=25><a href='viewbookings.php?scheduleid=<?=$scheduleid;?>'>view</a>
      <td width=50 align='center' width=250><?=$makebooking;?></td>
      <td width=50 align='center'><?=$waitlist;?></td> 
      <td><span id='standby_<?=$scheduleid;?>'><?=$standby;?></span></td>
      
      <td> <? if($admin=='acland'){ ?>
				<a href="javascript:confirmation(<? echo $scheduleid.",'$classname'"; ?>)">delete</a>									
						<?	} ?>
		</td>
	  	   
	  <!--CHECK BOXES FOR MULTIPLE DELETE - NO WARNING <td align='center'><input type="checkbox" value="<?=$scheduleid;?>" name="cpid[]"></td>-->
		</tr>
		<?
            }
			//}
		?>
		<tr>
			<td colspan="9"><a href="addschedule.php"><b>Add class to the schedule</b> </a> - <a href="addclass.php"><b>Add a NEW class</b></a></td>
			<!-- <td><input type="submit" name="submit" value="Delete"></td> -->
		</tr>
		<tr><td colspan="9">total places booked:<? echo $totalseatsbooked; ?> out of <? echo $totalseatsscheduled; ?> scheduled places.
		<? $bookpercent= $totalseatsbooked/$totalseatsscheduled*100;
		echo round($bookpercent,2); ?> % sold</td></tr>
	</table>
</form>
<?

foot();
?>