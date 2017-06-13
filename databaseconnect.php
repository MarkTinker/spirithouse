<?

$hostname = "localhost";
$username = "sh_db00jg";
$password = "qudtas2";
$dbName = "sh_db";

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//$connx = mysql_connect($hostname,$username,$password);
//if($connx)
//{
  //echo"success connection";
//}



mysql_connect($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
mysql_select_db($dbName) or DIE("Table unavailable");

//echo "this is the username $hostname";

// the following code is used in processing our vouchers and should be moved to another file that is more relevant to that function
define("voucher_active", 1);
define("voucher_presented", 2);
define("voucher_holding", 3);
define("voucher_expired", 4);


// security stuff
function quote_smart($value) {
   // Stripslashes
   if (get_magic_quotes_gpc()) {
       $value = stripslashes($value);
   }
   // Quote if not integer
   if (!is_numeric($value)) {
       $value = "'" . mysql_real_escape_string($value) . "'";
   }
   return $value;}

?>
