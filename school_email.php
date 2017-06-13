<?
include("databaseconnect.php");
$email = $_POST['email'];

if( is_numeric($email[0])) {$search ='phone';}else{$search='email';} // check to see if it's a phone number to search on or email


$sql="select bookingid, firstname, lastname, phone, email, classes.classid, scheduledate, classname, classseats, date_format(scheduledate, '%d %M %y') as date2, seats, nametags, `vip`, phone, `notes`
    			from bookings
    			left join schedule
    			on bookings.scheduleid=schedule.scheduleid
    			left join classes
    			on schedule.classid=classes.classid
    			where $search= '$email' AND seats>0 order by scheduledate";
    			
    			
    			$result=mysql_query($sql);
				//$no=mysql_num_rows($result);
				
				while($row = mysql_fetch_array($result)){
					echo "<hr><strong>".$row['date2']."</strong><br>";
					echo  $row['classname'];
					echo "<br><small>".$row['lastname']."</small><br>";

					echo "<br />";
					}
				
				

?>