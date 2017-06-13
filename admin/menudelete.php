<?
include("../databaseconnect.php");
include("includes/auth.inc.php");
for($t=0;$t<count($_POST['stid']);$t++) {
  $delsql="delete from menu where menuid=".quote_smart($_POST['stid'][$t]);
	$delresult=mysql_query($delsql);
}

header("Location: viewmenu.php?".date("Ymdhm"));

?>