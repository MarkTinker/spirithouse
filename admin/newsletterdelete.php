<?
include("../databaseconnect.php");
include("includes/auth.inc.php");

for($t=0;$t<count($cpid);$t++)
{
	$delsql="delete from newsletters where newsletterid=$cpid[$t]";
	$delresult=mysql_query($delsql);	
}

header("Location: viewnewsletters.php");

?>