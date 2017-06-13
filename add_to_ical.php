<?

include("databaseconnect.php");

//*********** this next block of code increments my counter by one each time someone clicks a link 
// *********** to add a calendar for their booking*******************************

	$sql="select counterId, counterClicks FROM counters where counterPage='calendar'";
			$result=mysql_query($sql);			
			$counterId=trim(mysql_result($result,$t,"counterId"));
			$counterClicks=trim(mysql_result($result,$t,"counterClicks"));
			
	//echo ("this is how many clicks: $counterClicks");

	$clicks=$counterClicks + 1;

	//echo ("<br>this is how many clicks: $clicks");

   //$sql="insert into `counters` (counterClicks) VALUES ($clicks)"; 
   $sql="update `counters` set `counterClicks`= $clicks where `counterId`=$counterId";   
   mysql_query($sql);

//************************************************************************************

  header("Content-Type: text/x-vCalendar");
  header("Content-Disposition: inline; filename=spirithouse.vcs");





$yesterday=date("Y-m-d", strtotime("-1 days"));





$sql2="select scheduleid, schedule.classid, classname,daynight, date_format(scheduledate,'%Y%m%d') as date FROM schedule, classes WHERE schedule.classid=classes.classid and  scheduleid=".quote_smart($_GET['scheduleid']); 
//echo $sql2;
//exit();
$result = mysql_query($sql2); 
$row=mysql_num_rows($result);
//echo "this is how many rows from sql2: $sql2";
//exit();

?>
BEGIN:VCALENDAR
VERSION:1.0



<?
$search = array ('/"/', '/,/', '/\n/', '/\r/', '/:/', '/;/', '/\\//'); // evaluate as php

$replace = array ('\"', '\\,', '\\n', '', '\:', '\\;', '\\\\');

while ( $row= mysql_fetch_assoc($result) ) {

$text = preg_replace($search, $replace, $row["classname"]); 
$text = wordwrap($text); 
$text = str_replace("\n","\n ",$text);

if ($row["daynight"]==1){
$starttime='163000';
$endtime='203000';
}else {
$starttime='093000';
$endtime='140000';
}

?>
BEGIN:VEVENT
SUMMARY:Spirit House:- <?php echo $text. "\n"; ?>
DESCRIPTION;ENCODING=QUOTED-PRINTABLE:Please arrive to the class 15 minutes before the scheduled time. For directions and maps please refer to your confirmation email or visit www.spirithouse.com.au. Everything is provided on the day of your class, including your meal and wine. Sensible footwear is advised because you will be standing for a few hours.
DTSTART:<?=$row["date"]?>T<?php echo $starttime. "\n"; ?>
DTEND:<?=$row["date"]?>T<?php echo $endtime. "\n"; ?>

END:VEVENT


<?
}
?> 






END:VCALENDAR






























