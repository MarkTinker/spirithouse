<?
include("databaseconnect.php");
//include("globals.inc.php");
include("includes/class_functions.inc.php");

  $emailarray=array();
  $phonearray=array();

  $sql="SELECT * from waitlist
       where scheduleid=".quote_smart($_GET['scheduleid']);
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no>0) {
    for($i=0;$i<$no;$i++) {
      $waitlistid=mysql_result($rs,$i,"waitlistid");
      $email=mysql_result($rs,$i,"email");
      $phone=mysql_result($rs,$i,"phone");
      $rand=mysql_result($rs,$i,"rand");
      if(strlen($email)>0) {
        $emailarray[$i][0]=$waitlistid;
        $emailarray[$i][1]=$rand;
        $emailarray[$i][2]=$email;
      }
      if(strlen($phone)>0) {
        $phonearray[$i][0]=$waitlistid;
        $phonearray[$i][1]=$rand;
        $phonearray[$i][2]=$phone;
      }
    }
  }

  #print_r($emailarray);
  #print_r($phonearray);

  $sql="SELECT classes.classid, classname, classdescription, classseats, classprice,  scheduleid,
       DATE_FORMAT(scheduledate, '%d/%m/%y') as classdate, full, bookings, scheduleseats
       FROM classes left join schedule
       on classes.classid = schedule.classid
       where scheduleid=".quote_smart($_GET['scheduleid']);
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no>0) {
    $bookings=mysql_result($rs,0,"bookings");
    $classname=mysql_result($rs,0,"classname");
    $classdescription=mysql_result($rs,0,"classdescription");
    $classseats=trim(mysql_result($rs,0,"classseats"));
    $scheduleseats=trim(mysql_result($rs,0,"scheduleseats"));
    $classprice=mysql_result($rs,0,"classprice");
    $classdate=mysql_result($rs,0,"classdate");
    
    #making sure that the correct seats available is shown
    #if the schedule seat has over-ridden the default class seats
    if($scheduleseats>0){
  	$classseats =		$scheduleseats;
  		}else{
  	$classseats = 	$classseats;
  		}
    $spotsleft=$classseats-$bookings;
  
  }

  for($i=0;$i<sizeof($emailarray);$i++) {

    $email="Greetings!<br /><br />You joined the waitlist for our <strong>$classname</strong> class on <strong>$classdate</strong>
    and asked us to notify you if there are any cancellations.<br /><br />We're writing to let you know that there are now
    <strong>$spotsleft</strong> places available in this class as of right now. If you are still interested in joining this
    class, then please head over to the booking page and enter your payment details.<br /><br />
    <a href='http://www.spirithouse.com.au/booking.php?scheduleid=".$_GET['scheduleid']."&from=waitlist&id=".$emailarray[$i][0]."&rand=".$emailarray[$i][1]."'>Book here now!</a><br /><br />
    Thanks for your interest in this class!";

	$from_add = "office@spirithouse.com.au"; 

	$to_add = "acland@spirithouse.com.au"; //<-- put your yahoo/gmail email address here

	$subject = "Test Subject";
	$message = "Test Message";
	
	//$headers = "From: office@spirithouse.com.au \r\n";
	//$headers .= "Reply-To: office@spirithouse.com.au \r\n";
	//$headers .= "Return-Path: office@spirithouse.com.au\r\n";
	//$headers .= "X-Mailer: PHP \r\n";
	//$headers .= "MIME-Version: 1.0\r\n";
	//$headers .= "Content-Type: text/html; charset=utf-8\r\n";


	// removed . phpversion() . "\r\n"  from $headers to get the mail to work on the new server OCT 2011.
    $headers = "From: office@spirithouse.com.au\r\n" .'X-Mailer: PHP/'."MIME-Version: 1.0\r\n" ."Content-Type: text/html; charset=utf-8\r\n" ."Content-Transfer-Encoding: 8bit\r\n\r\n";
    mail($emailarray[$i][2],"Waitlist alert for a Spirit House cooking class", $email, $headers);
  }



  header("location: https://www.spirithouse.com.au/admin/viewbookings.php?scheduleid=".$_GET['scheduleid']);
  exit();