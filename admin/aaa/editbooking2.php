<?
include("../head.php");
include("../../databaseconnect.php");
include("../includes/auth.inc.php");
include("../../includes/class_functions.inc.php");



if(sizeof($_POST)>0) {

  if($_POST['seats']==0) {
    $giftcertificates=$_POST['giftcertificates'];
    $vouchers=explode("|", $giftcertificates);
    $notes="UPDATE ON ".date("d/m/Y").": Voucher removed from <a href=\"viewbookings.php?scheduleid=".$_POST['scheduleid']."\">class</a>. ";
    for($i=0;$i<sizeof($vouchers);$i++) {
      if(is_numeric($vouchers[$i])) {
       $update="update vouchers set voucherstatus=1, vouchernotes=CONCAT(`vouchernotes`, '".$notes."') where vouchernumber=".quote_smart($vouchers[$i]);
       mysql_query($update);
       #echo $update."<br />";
      }
    }
  }

  $sql="update bookings set
  nametags=".quote_smart($_POST['nametags']).",
  seats=".quote_smart($_POST['seats']).",
  firstname=".quote_smart($_POST['firstname']).",
  lastname=".quote_smart($_POST['lastname']).",
  email=".quote_smart($_POST['email']).",
  phone=".quote_smart($_POST['phone']).",
  cardname=".quote_smart($_POST['cardname']).",
  cardnumber=".quote_smart($_POST['cardnumber']).",
  expiry_m=".quote_smart($_POST['expiry_m']).",
  expiry_y=".quote_smart($_POST['expiry_y']).",
  `credits`=".quote_smart($_POST['credits']).",
  `notes`=".quote_smart($_POST['notes'])."
  where bookingid=".quote_smart($_POST['bookingid']);
  $result=mysql_query($sql);
 //echo $sql;
  //exit();
  
  update_seats_for_schedule($_POST['scheduleid']);

  header("location: http://www.spirithouse.com.au/admin/viewbookings.php?scheduleid=".$_POST['scheduleid']);
  exit();

}


head();

$sql="select scheduleid, schedule.classid, date_format(scheduledate, '%W %d %M %y') as date2,
date_format(scheduledate, '%W') as dayname, classname, full, bookings, classseats, daynight
from schedule, classes where schedule.classid=classes.classid and scheduledate > NOW() order by scheduledate" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);



##### The booking doesn't show the class or date so I've fixed that with this code below
$sql2="select date_format(scheduledate, '%W %d %M %y') as date2,
date_format(scheduledate, '%W') as dayname, classname from schedule, classes WHERE schedule.classid=classes.classid AND scheduleid=".quote_smart($_GET['scheduleid']);
$result2=mysql_query($sql2);
$classname=mysql_result($result2,0,'classname');
$date2=mysql_result($result2,0,'date2');  
//echo "$classname on $date2"; 




$sql="select * from `bookings` where `bookingid`=".quote_smart($_GET['bookingid']);
$result=mysql_query($sql);
$no=mysql_num_rows($result);
if($no>0) {
  $seats=mysql_result($result,$t,"seats");
  $nametags=mysql_result($result,$t,"nametags");
  $firstname=mysql_result($result,$t,"firstname");
  $lastname=mysql_result($result,$t,"lastname");
  $email=mysql_result($result,$t,"email");
  $phone=mysql_result($result,$t,"phone");
  $cardname=mysql_result($result,$t,"cardname");
  $cardnumber=mysql_result($result,$t,"cardnumber");
  $expiry_m=mysql_result($result,$t,"expiry_m");
  $expiry_y=mysql_result($result,$t,"expiry_y");
  $notes=mysql_result($result,$t,"notes");
  $credits=mysql_result($result,$t,"credits");
  $total_by_cc=mysql_result($result,$t,"total_by_cc");
  $total_by_gc=mysql_result($result,$t,"total_by_gc");
  $transactionid=mysql_result($result,$t,"transactionid");
  $authcode=mysql_result($result,$t,"authcode");
  $giftcertificates=mysql_result($result,$t,"giftcertificates");
  $bookingid=mysql_result($result,$t,"bookingid");
  
  ?>

  <p><h3>Edit a booking</h3></p>
  <p> Currently booked in the <?echo " <strong>$classname</strong> class on $date2"; ?></h3>  
  <form  method='POST'>
    <table cellpadding="0" cellspacing="5" border="0">
    <tr>
        <td>Eway Invoice Ref:</td><Td><?=$transactionid;?></td>
      </tr>
      <tr>
        <td>Booked by:</td>
        <td><input type='text' name='firstname' size=15 value='<?=$firstname;?>'> <input type='text' size=15 name='lastname' value='<?=$lastname;?>'></td>
      </tr>
      <tr>
        <td>Phone</td>
        <td><input type='text' name='phone' value='<?=$phone;?>'></td>
      </tr>
      <tr>
        <td>Email</td>
        <td><input type='text' name='email' size=30 value='<?=$email;?>'></td>
      </tr>
      <tr>
        <td>Seats</td>
        <td><input type='text' name='seats' size=3 value='<?=$seats;?>'></td>
      </tr>
      <tr>
        <td>Nametags</td>
        <td><textarea rows=3 name='nametags' cols=80><?=$nametags;?></textarea>
      </tr>
      <?
      if($total_by_cc>0) {
        ?><tr>
        <td>Card details</td>
        <td><br /><strong>Amount charged: $<?=$total_by_cc;?></strong> (authcode: <?=$authcode;?>)
        <br /><?=$cardname;?> / <? echo "CC ends with: $cardnumber"; ?> / Expiry: <?=$expiry_m;?> / <?=$expiry_y;?>
        <br />&nbsp;</td>
      </tr>
      <? }
      if($total_by_gc>0) {
      ?><tr>
        <td>Gift certs</td>
        <td><strong>Amount in certificates: $<?=$total_by_gc;?></strong><br />
        <?=$giftcertificates;?>
        </td>
      </tr><?
      } ?>
      <tr>
        <td>Notes about this booking?</td>
        <td><textarea rows=5 name='notes' cols=80><?=$notes;?></textarea>
      </tr>
       <tr>
        <td>CREDITS:</td>
        <td><textarea rows=5 name='credits' cols=80><?=$credits;?></textarea>
      </tr>
    </table><BR>

    <input type='hidden' name='scheduleid' value='<?=$_GET['scheduleid'];?>'>
    <input type='hidden' name='bookingid' value='<?=$_GET['bookingid'];?>'>
    <input type='hidden' name='giftcertificates' value='<?=$giftcertificates;?>'>
    <input type="submit" name="submit33" value=" update "></td>

  </form>
  
  
<!-- This is the ajax to load the move script -->  
 <a href="#" id="slick-toggle">MOVE TO ANOTHER CLASS</a></p>
<div id="slickbox">


    <h1>Be Careful - Make Sure You Click The Right Class & Date!</h1>
   
     <table> 
     <tr>
		<th>Date</th>
		<th>Class name</th>
      <th>scheduleid</th>
      <th>Seats left</th>
      <th>Move</th>
  
		</tr>
  
  <? 

// NEW STUFF FOR MOVE//  
  $yesterday=date("Y-m-d", strtotime("-1 days"));

//added OR statement to find available seats in classes that had their default seats overridden (scheduleseats)
  $sql3="select scheduleid, schedule.classid, date_format(scheduledate, '%W %d %M %y') as date3,
date_format(scheduledate, '%W') as dayname, classname, full, bookings, classseats, scheduleseats, daynight
from schedule, classes where  schedule.classid=classes.classid and ((classseats-bookings)>='$seats' OR (scheduleseats-bookings)>='$seats') and scheduledate > '$yesterday' order by scheduledate, daynight " ;
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
      		
      		//add code in here to work out maxseats- varies if schedule has more seats than default class  - 
      		
      		if($scheduleseats>$classseats){
      		$maxseats=$scheduleseats;
      		}else {
      		$maxseats=$classseats;
      		}
      		
      		$seat_status=$maxseats-$bookings." seat(s) left";
      		if ($daynight==1){$classtype='Night';}else{$classtype='Day';}
      		
      		?>
  			
  			

  		<tr align="left" class='bg'>
		 <form name="move<?=$scheduleid?>" method="POST" action="bookings_move.php">
      		<input type='hidden' name='scheduleid' value='<?=$scheduleid?>' />
      		 <input type='hidden' name='oldscheduleid' value='<?=$_GET['scheduleid'];?>'>
      		<input type='hidden' name='giftcertificates' value='<?=$giftcertificates?>' />
      		<input type='hidden' name='bookingid' value='<?=$bookingid?>' />
      		<input type='hidden' name='seats' value='<?=$seats?>' />
      		
      
	  		<td><?=$scheduledate3;?></td>
	  		<td width=300><?=$classname;?></td>
      		<td width=10><?=$classtype;?></td>
      		<td width=100 align='center' width=250><?=$seat_status;?></td>
       		<td align='center' width=25><input type="submit" name="button<?=$t3;?>" value="MOVE" onClick="javascript:return confirm('Do you want to move this booking?')"></form></td>
      </tr>


  
  <? } ?>
    </table>
    
    </div>
    
    
  <script type="text/javascript">
  $(document).ready(function() {
 // hides the slickbox as soon as the DOM is ready (a little sooner that page load)
  $('#slickbox').hide();
  
 // shows and hides and toggles the slickbox on click  
  $('#slick-show').click(function() {
    $('#slickbox').show('slow');
    return false;
  });
  $('#slick-hide').click(function() {
    $('#slickbox').hide('fast');
    return false;
  });
  $('#slick-toggle').click(function() {
    $('#slickbox').toggle(400);
    return false;
  });

 // slides down, up, and toggle the slickbox on click    
  $('#slick-down').click(function() {
    $('#slickbox').slideDown('slow');
    return false;
  });
  $('#slick-up').click(function() {
    $('#slickbox').slideUp('fast');
    return false;
  });
  $('#slick-slidetoggle').click(function() {
    $('#slickbox').slideToggle(400);
    return false;
  });
});
  
  
  
  </script>

   
  <?
 
 
}

foot();
?>