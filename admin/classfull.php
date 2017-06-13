<?
include("../databaseconnect.php");
include("includes/auth.inc.php");

if($_GET['action']==0) {
	#make not full
	$sql="update schedule set full=0 where scheduleid=".quote_smart($_GET['scheduleid']);
} else {
	$sql="update schedule set full=1 where scheduleid=".quote_smart($_GET['scheduleid']);
}

$result=mysql_query($sql);

header("Location: viewclasses.php");
exit();
?>