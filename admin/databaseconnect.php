<?

$hostname = "localhost";

$username = "sh_shlogin";

$password = "shl0g1n";

$dbName = "sh_db";


mysql_connect($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");

mysql_select_db($dbName) or DIE("Table unavailable");

?>
