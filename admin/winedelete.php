<?
include("../databaseconnect.php");
include("includes/auth.inc.php");
for($t=0;$t<count($_POST['stid']);$t++) {
  $delsql="delete from wines where wineId=".quote_smart($_POST['stid'][$t]);
	$delresult=mysql_query($delsql);
}

header("Location: viewwines.php?".date("Ymdhm"));

?>