<?php


// new function that checks if a cookie for admin has been asigned from the login page - if not then goes back to login - a security measure.
if (!isset($_COOKIE['admin'] ))
{

header('location: http://spirithouse.com.au/admin/login.php');
//exit();
} 
 
 
 
 /* 
 // THE OLD AUTH SCRIPT THAT CHESK TO SEE IF THE USER IS LOGGED IN OR NOT THIS ONE ACCESSES THE DATABASE
 // if you reuse this script you need to uncomment the password cookies in the login and index php files
 
  $sql="select * from `users` where `username`='".$_COOKIE['admin']."' and `pass`='".$_COOKIE['pass']."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)==0) {
    setcookie("admin", "", -6800);
    setcookie("pass", "", -6800);
		?><html><head><title>SH</title><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='REFRESH' content='1; URL=login.php'></head></html> <html> <?
    exit();
  }
  */

?>