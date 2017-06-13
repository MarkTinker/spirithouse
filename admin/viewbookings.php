<?
include("head.php");
//include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");

if(sizeof($_POST)>0) {
  if(isset($_POST['submit55'])) {
     $sql="insert into bookings (`scheduleid`, `seats`, firstname, lastname, email, phone, nametags, `notes`) values  (
     ".quote_smart($_POST['scheduleid']).", ".quote_smart($_POST['seats']).", ".quote_smart($_POST['firstname']).",
     ".quote_smart($_POST['lastname']).", ".quote_smart($_POST['email']).", '".stripslashes($_POST['phone'])."', 
     ".quote_smart($_POST['nametags']).", ".quote_smart($_POST['notes']).")";
     mysql_query($sql);
     update_seats_for_schedule($_POST['scheduleid']);

  } else {

    for($t=0;$t<count($_POST['bookingid']);$t++)
    {
      $bookingid=$_POST['bookingid'][$t];
      $sql="delete from bookings where `bookingid`=".quote_smart($bookingid);
      mysql_query($sql);
      update_seats_for_schedule($_POST['scheduleid']);
    }
    for($t=0;$t<count($_POST['waitlistid']);$t++)
    {
      $waitlistid=$_POST['waitlistid'][$t];
      $sql="delete from waitlist where `waitlistid`=".quote_smart($waitlistid);
      mysql_query($sql);
    }

  }
}

//echo $_GET['scheduleid'];
//exit();

head();

$sql="select bookingid, firstname, lastname, email, classes.classid, classes.classprice, classname, classseats, schedule.scheduleseats, date_format(scheduledate, '%d %M %y') as date2, seats, nametags, `vip`, phone, `notes`
    from bookings
    left join schedule
    on bookings.scheduleid=schedule.scheduleid
    left join classes
    on schedule.classid=classes.classid
    where bookings.scheduleid=".quote_smart($_GET['scheduleid']);

$result=mysql_query($sql);
$no=mysql_num_rows($result);


//$maxseats=quote_smart($_GET['maxseats']);
?>
<script>
function changeseats(field, scheduleid) {
  var seatstaken = document.getElementById(field).selectedIndex;
  document.location.href="viewclasses.php?action=updateseats&scheduleid="+scheduleid+"&seatstaken="+seatstaken;
}
  
function send_survey(scheduleid) {
  if(confirm("Send Survey Email?")) {
    var xmlhttp =  new XMLHttpRequest();
    xmlhttp.open('GET', 'http://www.spirithouse.com.au/admin/send_survey.php?scheduleid='+scheduleid, true);
    xmlhttp.send(null);
    document.getElementById('survey').innerHTML='Sent!';
  } 
  } 
  
  function resend_confirmation_email(bookingid) {
  if(confirm("Are you sure?")) {
    var xmlhttp =  new XMLHttpRequest();
    xmlhttp.open('GET', 'http://www.spirithouse.com.au/admin/resend_confirmation_email.php?bookingid='+bookingid, true);
    xmlhttp.send(null);
    document.getElementById('conf_'+bookingid).innerHTML='Sent!';
  }
}
</script>


<? $thisclass=trim(mysql_result($result,0,"classname")); 

//$survey="<span id='survey'><a href='#' onclick='send_survey(5071)'>Send Survey Email?</a></span>";
//$survey2="<p> <span id='survey'><a href='#' onclick='send_survey(".$_GET['scheduleid'].")'>Send Survey Email?</a></span></p>";


//$survey="<p> <span><a href='send_survey.php?scheduleid=".$_GET['scheduleid']."'>Send Survey Email?</a></span></p>";

echo "<p><h3>View bookings for $thisclass</h3></p>";
?>
<form method="post" name='theForm'>
<input type='hidden' name='scheduleid' value='<?=$_GET['scheduleid'];?>' />

  <table>
    <tr>
      <th>ID</th>
      <th>Class date</th>
      <th>Class name</th>
      <th>Attendee</th>
      <th>V.I.P.</th>
      <th>Email</th>
      <th>Seats taken</th>
      <th>Nametags</th>
      <th>Notes</th>
      <th>Conf</th>
      <th>Delete</th>
    </tr>
    <?
       $totalseats=0;
      for($t=0;$t<$no;$t++)
      {
      
     $bookingid=trim(mysql_result($result,$t,"bookingid"));
      $scheduledate=trim(mysql_result($result,$t,"date2"));
      $classname=trim(mysql_result($result,$t,"classname"));
      $firstname=trim(mysql_result($result,$t,"firstname"));
      $lastname=trim(mysql_result($result,$t,"lastname"));
      $email=mysql_result($result,$t,"email");
      $phone=mysql_result($result,$t,"phone");
      $vip=mysql_result($result,$t,"vip");
      $seats=mysql_result($result,$t,"seats");
      $classseats=mysql_result($result,$t,"classseats"); 
      $scheduleseats=mysql_result($result,$t,"scheduleseats");
      $notes=mysql_result($result,$t,"notes");
      $nametags=mysql_result($result,$t,"nametags");
      $totalseats=$seats+$totalseats;
      $conf="<a href='#' onclick='resend_confirmation_email(".$bookingid.")'>resend</a>";
 
    
    
    
    
    ?>
    <tr align="left">
      <td><a href='editbooking.php?scheduleid=<?=$_GET['scheduleid'];?>&bookingid=<?=$bookingid;?>'><?=$bookingid;?></a></td>
      <td><?=$scheduledate;?></td>
      <td><?=$classname;?></td>
      <td><?=$firstname." ".$lastname;?></td>
      <td> <font color="red"><center><? if($vip>1){ echo"[$vip]";} ?></center> </font> </td>      
      <td><?=$phone." / ".$email;?></td>
      <td><?=$seats;?></td>
      <td><?=$nametags;?>&nbsp;</td>
      <td><?=$notes;?>&nbsp;</td>
      <td><span id='conf_<?=$bookingid;?>'><?=$conf;?></span></td>
      <td align='center'><!--<input type="checkbox" value="<?=$bookingid;?>" name="bookingid[]">--></td>
    </tr>
    <?
      
      }
    ?>
    <tr>
      <td colspan="6"></td><td><?=$totalseats;?></td>
      <td></td>
      <td>&nbsp;</td><td>&nbsp;</td><td><!--<input type="submit" name="submit" value="Delete">--></td>
    </tr>
  </table>
</form>

<? 
   // checking to see if class seats is being over-ridden by the scheduled amount of seats
  	// we can override the default classseats in the schedule now - this checks to see
  	// if a class has more or less seats than default for today 
  if($scheduleseats>0){
  		$maxseats=$scheduleseats;
  		}else{
  		
  		$maxseats=$classseats;
  		} 
  if($totalseats>1 AND $totalseats>=$maxseats) { echo"<h2><font color='red'>Class is FULL</h2></font>";} ?>
<span style='background-color:#eee'>
<form method="post" name='theForm'>
<input type='hidden' name='scheduleid' value='<?=$_GET['scheduleid'];?>' />
<table width="90%">
  <tr>
    <td>Booking for?</td><td><input name='firstname' size=8> <input name='lastname' size=8></td>
    <td>Seats</td><td><input name='seats' size=2></td>
    <td>Email</td><td><input name='email' size=10></td>
    <td>Phone</td><td><input name='phone' size=10></td>
    <td>Nametags</td><td><input name='nametags' size=20></td>
    <td><select name='notes'><option value='Paid manual CC'>Paid CC</option><option value='Paid manual GV'>Paid GV</option><option value='Paid manual CC and GV'>CC & GV</option><option value='Not paid yet'>Not paid yet</option></select></td>

    <td><input value='add' name='submit55' type='submit'></td>
  </tr>
</table>
<br />
</form>
</span>



<?
$sql="select waitlistid, email, phone
    from waitlist
    where scheduleid=".quote_smart($_GET['scheduleid']);

$result=mysql_query($sql);
$no=mysql_num_rows($result);

if($no>0) {
?><form method="post" name='theForm2'>
<input type='hidden' name='scheduleid' value='<?=$_GET['scheduleid'];?>' />

  <table>
    <tr>
      <th colspan=2>Waitlist <a href='../waitlist_notify.php?scheduleid=<?=$_GET['scheduleid'];?>'>notify</a></th>
      <th>Delete</th>
    </tr>
    <?

      for($t=0;$t<$no;$t++)
      {
      $waitlistid=mysql_result($result,$t,"waitlistid");
      $email=mysql_result($result,$t,"email");
      $phone=mysql_result($result,$t,"phone");

    ?>
    <tr align="left">
      <td><?=$email;?></td>
      <td><?=$phone;?></td>
      <td align='center'><input type="checkbox" value="<?=$waitlistid;?>" name="waitlistid[]"></td>
    </tr>
    <?
      }
    ?>
    <tr>
      <td colspan="2"></td>
      <td> <input type="submit" name="submit" value="Delete"></td>
    </tr>
  </table>
</form>
<?
}

foot();
?>