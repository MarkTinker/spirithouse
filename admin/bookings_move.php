<?

include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");



// update the scheduleid to the new class schedule
$scheduleid=$_POST['scheduleid'];
$oldscheduleid=$_POST['oldscheduleid'];
$giftcertificates=$_POST['giftcertificates'];
$seats=$_POST['seats'];
$bookingid=$_POST['bookingid'];
/*
head();
echo "<div align='left' style='margin-left:30%;'>";
echo "<h1>Here's what happened</h1>";
echo "<h2>For the ORIGINAL BOOKING (bookingid: $bookingid) </h2>";
echo "<p> Moved <strong>$seats seat/s</strong>  from the <a href='viewbookings.php?scheduleid=$oldscheduleid'>OLD class</a> to the <a href='viewbookings.php?scheduleid=$scheduleid'>NEW class</a> (in the BOOKINGS TABLE - see bookingid)";
echo "<p>VOUCHERS: any vouchers, a note has been added to show the voucher is holding the new booking (in the VOUCHERS TABLE)";
echo "<p>A NOTE has been added to the booking to show which class the booking was moved FROM (in the BOOKINGS TABLE)";
*/


 //We need to ADD the seats to schedule.bookings where scheduleid = the NEW scheduleid
  update_seats_for_schedule($scheduleid, $seats);
  
  //We need to SUBTRACT the seats from schedule.bookings where scheduleid = the OLD scheduleid
  remove_seats_from_schedule($oldscheduleid, $seats);


 ########## UPDATE VOUCHER NOTES ########################
  if($giftcertificates>0) {
    $vouchers=explode("|", $giftcertificates);
    $notes=": UPDATE ON ".date("d/m/Y")."- Moved to this <a href=\"viewbookings.php?scheduleid=".$_POST['scheduleid']."\">class</a>. ";
    for($i=0;$i<sizeof($vouchers);$i++) {
      if(is_numeric($vouchers[$i])) {
       $update="update vouchers set vouchernotes=CONCAT(`vouchernotes`, '".$notes."') where vouchernumber=".quote_smart($vouchers[$i]);
       mysql_query($update);
       //echo $update."IS WORKING<br />";
      }
    }
  }
  
  
  ########## UPDATE BOOKINGS & NOTES ########################
  
  $move_note="-MOVED ON ".date("d/m/Y").": Moved from this <a href=\"viewbookings.php?scheduleid=".$oldscheduleid."\">class</a>. ";
  //add the note to the bookings table in the notes field
  $updatebooking="update bookings set scheduleid=$scheduleid, notes=CONCAT(`notes`, '".$move_note."') where bookingid=".quote_smart($bookingid);
  mysql_query($updatebooking);
  //echo $updatebooking." TEST to see if working<br>";
   
    echo"This is the SEAT COUNT at the end of the script $seats";
    
    
 
 ########## UPDATE SEATS FUNCTION ######################## 
  function update_seats_for_schedule($scheduleid, $seats) {
  $sql="select scheduleid, sum(seats) as totalseats from bookings where scheduleid=".$scheduleid." group by scheduleid";
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no==0) {
    $totalseats=0;
  } else {
    $totalseats=mysql_result($rs,0,"totalseats");
  }
		//add the seats from original class to the seats already booked in the new class
		
		//echo "<h2>for NEW CLASS $scheduleid :</h2>";
		//echo "<br /><br />sql to find totalseats ".$sql;
		//echo "<br>seats at first $sceduleid: $totalseats <br>";	
		$totalseats=$totalseats+$seats;
		//echo "seats now in $scheduleid: $totalseats <br>";	
	

		//function to give the new seat count to the scheudle for the NEW class
  		$updateseats="update schedule set `bookings`=".$totalseats." where `scheduleid`=".$scheduleid."";
  		mysql_query($updateseats);

  //echo "<br>totalseats = $totalseats..<br /><br />WORKING - update seats sql: ".$updateseats;

}
#############################################################  
  
  ########## REMOVE SEATS FUNCTION ######################## 
  
  function remove_seats_from_schedule($oldscheduleid, $seats) {

  $sql2="select scheduleid, sum(seats) as totalseats from bookings where scheduleid=".$oldscheduleid." group by scheduleid";
  $rs2=mysql_query($sql2);
  $no2=mysql_num_rows($rs2);
  if($no2==0) {
    $totalseats=0;
  } else {
    $totalseats=mysql_result($rs2,0,"totalseats");
  }
		//give back to the old class the #seats we're moving 
		
		//echo "<h2>For ORIGINAL CLASS $oldscheduleid :</h2>";
		//echo "<br /><strong>sql to find totalseats:</strong> ".$sql2;
		//echo "<br>seats that were in original class $oldsceduleid: $totalseats <br>";
		$totalseats=$totalseats-$seats;	
		//echo "seats that are now left in $oldscheduleid: $totalseats <br>";	
	
  
		//function to give the new seat count to the schedule table for the OLD class
  		$removeseats="update schedule set `bookings`=".$totalseats." where `scheduleid`=".$oldscheduleid."";
  
  		mysql_query($removeseats);

  		//echo "<br>totalseats = $totalseats...<br /><br /> this is the remove seats sql: ".$removeseats;

}

echo "</div>";


?>
<html><head><title>Spirit House</title><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='REFRESH' content='1; URL=http://www.spirithouse.com.au/admin/viewbookings.php?scheduleid=<? echo $scheduleid; ?>'></head><body>MOVING THE BOOKING ---- Please Stand By</body></html><?
  exit(); ?>