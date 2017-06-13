<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

if(isset($_POST['submit33'])) {
  $sql="update classes set classname=".quote_smart($_POST['classname']).",classdescription=".quote_smart($_POST['classdescription']).", classprice=".quote_smart($_POST['classprice']).", classseats=".quote_smart($_POST['classseats'])." where classid=".quote_smart($_POST['classid']).";";
  $result=mysql_query($sql);

  header("location: viewclasses.php");
  exit();

}

if(isset($_GET['classid66'])) {
  $sql="delete from classes where classid=".quote_smart($_GET['classid66']);
  mysql_query($sql);
  $sql="delete from schedule where classid=".quote_smart($_GET['classid66']);
  mysql_query($sql);
  header("location: viewclasses.php");
  exit();
}



head();

$sql="select classid, classname from classes order by classname";
$result=mysql_query($sql);
$no=mysql_num_rows($result);
$selectstr="<select name='classid_'>";
for($t=0;$t<$no;$t++) {
  $classid=mysql_result($result,$t,"classid");
  $classname=mysql_result($result,$t,"classname");
  $selectstr.="<option ";
  if($classid_==$classid) { $selectstr.="SELECTED"; }
  $selectstr.=" value='$classid'>$classname</option>";
  #$selectstr.=" value=".$_POST['classid'].">".$_POST['classname']."</option>";
}
$selectstr.="</select>";

$yesterday=date("Y-m-d", strtotime("-1 days"));

?>


<p><h3>Choose a class</h3></p>
<form name='theForm2' action='editclass.php' method='POST'>
<?=$selectstr;?> <input type='submit' value='Go >>'>
</form><Br>
<?
if(isset($_POST['classid_']) && is_numeric($_POST['classid_'])) {
  $sql="select * from classes where classid=".quote_smart($_POST['classid_'])."";
  $result=mysql_query($sql);
  $no=mysql_num_rows($result);
  if($no>0) {
  	$classid = mysql_result($result,0,"classid");
    $classname=mysql_result($result,0,"classname");
    $classdescription=mysql_result($result,0,"classdescription");
    $classprice=mysql_result($result,0,"classprice");
	$classseats=mysql_result($result,0,"classseats");
    $sql4="select count(schedule.classid) as classcount from schedule where schedule.classid='$classid'";
    $result4=mysql_query($sql4);
    $classcount=mysql_result($result4,0,"classcount");

    ?>
  	<form action="editclass.php" method="post">
  		<table cellpadding="0" cellspacing="0" border="0">
  			<tr>
  				<td>Class name</td>
  				<td><input type='text' name='classname' value='<?=$classname;?>'> ( <?=$classcount;?> in schedule )
  			</tr>
  			<tr>
  				<td>Class description</td>
  				<td><textarea name='classdescription' rows=5 cols=40><?=$classdescription;?></textarea>
  			</tr>
  			<tr>
  				<td>Class price</td>
  				<td>$<input type='text' name='classprice' value='<?=$classprice;?>'>
  			</tr>
  			<tr>
  				<td>Class Seats</td>
  				<td><input type='text' name='classseats' value='<?=$classseats;?>'>
  			</tr>
  		</table><BR>

      <input type='hidden' name='classid' value='<?=$_POST['classid_'];?>'>
  		<input type="submit" name="submit33" value=" update "></td>

  	</form>

    <form name='theform99'>
    <input type='hidden' name='classid66' value='<?$_POST['classid_'];?>'>

    <input type='submit' name='delete' value='Delete this class!'>
    </form>
    

  	<?
  }
}




echo "<h3>Classes In Schedule from: JULY</h3><br><table>";// change month etc using $displaydate
$displaydate = '2014-07-01';
// get the classid in the current schedule
$sqlid="select DISTINCT schedule.classid, classname from schedule, classes where schedule.classid=classes.classid and scheduledate > '$displaydate'";
//echo $sqlid;
//exit();
 
$result=mysql_query($sqlid);
$noid=mysql_num_rows($result);
//echo $noid;


for($t=0;$t<$noid;$t++)
			{
               
			$classid=trim(mysql_result($result,$t,classid));
			$classname=trim(mysql_result($result,$t,classname));
			
			$sql4="select count(schedule.classid) as classcount from schedule where schedule.classid='$classid' AND scheduledate >'$displaydate'";
    			$result4=mysql_query($sql4);
    			$classcount=mysql_result($result4,0,"classcount");
    		//echo "$sql4 <br>";
    		echo "<tr>	<td width='200px' >$classname</td><td> $classcount</td><td>$classid</td></tr>";
    		//exit();

}
echo "</table>";

foot();
?>