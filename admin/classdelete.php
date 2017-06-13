<?
include("../databaseconnect.php");
include("includes/auth.inc.php");

for($t=0;$t<count($_POST['cpid']);$t++)
{
	$delsql="delete from schedule where scheduleid=".quote_smart($_POST['cpid'][$t]);
	$delresult=mysql_query($delsql);
}

header("Location: viewclasses.php");

?>