<?
include("../databaseconnect.php");
include("includes/auth.inc.php");

for($t=0;$t<count($_POST['stid']);$t++)
{
	$delsql="delete from stockists where stockistid=".quote_smart($_POST['stid'][$t]); 
	$delresult=mysql_query($delsql);	
}

header("Location: viewstockists.php?".date("Ymdhm"));

?>