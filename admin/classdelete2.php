<?
include("../databaseconnect.php");
include("includes/auth.inc.php");


	$delsql="delete from schedule where scheduleid=".quote_smart($_GET['scheduleid']);
	$delresult=mysql_query($delsql);
	#echo $delsql;
	#exit();


header("Location: viewclasses.php");

?>
