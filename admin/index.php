<?
include("../databaseconnect.php");

if(isset($_POST['submit']))
{
	$mes=0;
	$sql="select * from `users` where `username`=".quote_smart($_POST['admin'])." and `pass`=".quote_smart($_POST['pass']);
	$result=mysql_query($sql);
	// if the login is good, set the cookies and divert to viewclasses.php
	if(mysql_num_rows($result)!=0)
	{
      setcookie("admin", $_POST['admin'], time()+3600*8, "/", "www.spirithouse.com.au");
      //I turned this cookie off because the new auth.inc doesn't need it
      //setcookie("pass", $_POST['pass'], time()+3600*60);
      
  
	?><html><head><title>SH</title><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='REFRESH' content='1; URL=viewclasses.php'></head></html> <html> <?
      exit();
	}
	else
	{
		$mes=1;
      	setcookie("admin", "", time()-3600*60);
      
      //I turned this cookie off because the new auth.inc doesn't need it
      //setcookie("pass", "", time()-3600*60);
   
	}
}

include("head.php");
?>

<html>
	<head>
	<title>&quot;Spirithouse Admin Page&quot;</title>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="expires" content="0">

	<link rel="stylesheet" type="text/css" href="/style.css">
	</head>
<body bgcolor="#FFFFFF" leftmargin="0" marginheight="0" marginwidth="0">
	<center>
	<table border="0" cellspacing="0" width="100%" cellpadding="0">
		<tr>
	  <td width="100%" height="50" bgcolor="#DE0029" align="left">	  		
	  			<img src="/images/shlogo.gif" WIDTH="130" HEIGHT="50">
	  	</td>
	  </tr>
	</table>
<br>
<?

if($mes==1)
{
?>
<font face="arial" size="2" color="red"><b>Not an authorised username or password!</b></font>
<?	
}
?>

<TABLE  border=1 cellPadding=0 cellSpacing=0 width=300>
	<TR>
		<TD>
			<form action="index.php" method="post">
			<TABLE border=0 cellPadding=0 cellSpacing=2 width="100%">
				<TR>
					<TD bgcolor="silver" colSpan=2 height=20>
						<B><FONT color=#000000 face=Arial,Helvetica size=-1>
								&nbsp;Admin Login</FONT></B>
					</TD></TR>
				<TR>
				<TR>
					<TD align=middle colSpan=2 height=20>&nbsp;</TD></TR>
				<TR>
					<TD align=right width="30%"><FONT face=arial size=2><B>Username:</B></FONT></TD>
					<TD width="70%"><INPUT maxLength=20 name="admin"  size=22></TD></TR>
				<TR>
					<TD align=right width="30%"><FONT face=arial size=2><B>Password:</B></FONT></TD>
					<TD width="70%"><INPUT maxLength=20 name="pass" size=22 type=password>
					</TD></TR>
				<TR>
					<TD colSpan=2 height=50 align="center">
					<INPUT name=submit type=submit value="Logon To The System">
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	</TABLE>
</form>
</td>
</tr>
</table>
<?
foot();
?>