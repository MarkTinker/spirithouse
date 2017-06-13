 <? 
 	
 
 ############# AJAX THIS PART OR SOMETHING ############## 
 

// NEW STUFF FOR MOVE//  
  $yesterday=date("Y-m-d", strtotime("-1 days"));

  
  $sql3="select scheduleid, schedule.classid, date_format(scheduledate, '%W %d %M %y') as date3,
date_format(scheduledate, '%W') as dayname, classname, full, bookings, classseats, scheduleseats, daynight
from schedule, classes where  schedule.classid=classes.classid and (classseats-bookings)>='$seats' and scheduledate > '$yesterday' order by scheduledate, daynight LIMIT 10" ;
//echo $sql3;
//exit();
$result3=mysql_query($sql3);
$no3=mysql_num_rows($result3);

for($t3=0;$t3<$no3;$t3++)
			{
            
			$scheduleid		=mysql_result	($result3,$t3,"scheduleid");
			$classid		=mysql_result	($result3,$t3,"classid");
			$scheduledate3	=mysql_result	($result3,$t3,"date3");
			$classname		=mysql_result	($result3,$t3,"classname");
			$full			=mysql_result	($result3,$t3,"full");
      		$bookings		=mysql_result	($result3,$t3,"bookings");
      		$classseats		=mysql_result	($result3,$t3,"classseats");
      		$dayname=		mysql_result	($result3,$t3,"dayname");
      		$scheduleseats	=mysql_result	($result3,$t3,"scheduleseats");
      		$daynight		=mysql_result	($result3,$t3,"daynight");
      		
      		// work out max seats for the class. If the schedule seats are 0 then maxseats
      		// is the default seating taken from the classes table. Otherwise it's the override in schedule table.
      		
      		if($scheduleseats>$classseats){
      		$maxseats=$scheduleseats;
      		}else {
      		$maxseats=$classseats;
      		}
      		
      		
      		$seat_status=$maxseats-$bookings." seat(s) left";
      		?>
  

  		<tr align="left" class='<?=$rowtype;?>'>
		 <form name="move<?=$scheduleid?>" method="POST" action="bookings_move.php">
      		<input type='hidden' name='scheduleid' value='<?=$scheduleid?>' />
      		 <input type='hidden' name='oldscheduleid' value='<?=$_GET['scheduleid'];?>'>
      		<input type='hidden' name='giftcertificates' value='<?=$giftcertificates?>' />
      		<input type='hidden' name='bookingid' value='<?=$bookingid?>' />
      		<input type='hidden' name='seats' value='<?=$seats?>' />
      		
      
	  		<td><?=$scheduledate3;?></td>
	  		<td width=300><?=$classname;?></td>
      		<td width=10><?=$scheduleid;?></td>
      		<td width=100 align='center' width=250><?=$seat_status;?></td>
       		<td align='center' width=25><input type="submit" name="button<?=$t3;?>" value="MOVE" onClick="javascript:return confirm('Do you want to move this booking?')"></form></td>
      </tr>


  
  <? } ?>