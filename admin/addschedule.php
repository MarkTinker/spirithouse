<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("includes/functions.inc.php");

head();

if(isset($_POST['submit1'])) {

	$scheduleseats=$_POST['maxseats'];
	$strDate=$_POST['date_y']."-".$_POST['date_m']."-".$_POST['date_d'];
	$addsql="insert into schedule (scheduledate, classid, full, daynight, starttime, scheduleseats) values ('$strDate',".quote_smart($_POST['classid']).", 0, ".$_POST['daynight'].",'".$_POST['starttime']. "', '$scheduleseats')";
	$delresult=mysql_query($addsql);
	$mes="<center><b>".$addsql."CLASS ADDED</b></center><BR>";
	
}

$sql="select classid, classname, classseats from classes order by classname";
$result=mysql_query($sql);
$no=mysql_num_rows($result);

if(isset($mes)) { echo "$mes"; }


echo "<center><br><form name='theForm' action='addschedule.php' method='POST'><BR>";
echo "<p>Class to add: <select name='classid'>";

for($t=0;$t<$no;$t++)
{
	$classid=trim(mysql_result($result,$t,"classid"));
	$classname=trim(mysql_result($result,$t,"classname"));
	$classseats=trim(mysql_result($result,$t,"classseats"));
	echo "<option value=$classid>$classname</option>";
}
echo "</select></p>";

echo "<p>Class date:";

echo DateSelectBox("date",date("d"), date("m"), date("Y"), 50, 65, 70);

echo "<p>Day or night class: <select name='daynight'>	<option value=0>Day</option>
														<option value=1>Night</option>
														<option value=2>Wine</option>
														<option value=3>Event</option>
														
														</select>";
echo "<p>Start time for class: <input type='time' size=20 value='9.30am' name='starttime'></p>";
echo "<p>Override max seats for this class: <input type='text' size=20 placeholder='$classseats' name='maxseats'></p>";
echo "<p><input type='submit' name='submit1' value='submit'></p>";
echo "</form></center>";
foot();
?>