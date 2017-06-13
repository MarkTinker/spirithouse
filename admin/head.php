<?
function head()
{
?>
<!DOCTYPE HTML>
<html>
	<head>
	<title>:: SpiritHouse Admin ::</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="widgEditor/css/widgEditor.css">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="widgEditor/scripts/widgEditor.js" type="text/javascript"></script>
	           

  
  <script type="text/javascript">
<!--
// creates an alert when you try to delete a class 
function confirmation(ID,Name) {
	var answer = confirm("Delete Class: "+Name+" ?")
	if (answer){
		alert("Entry Deleted")
		window.location = "classdelete2.php?scheduleid="+ID;
	}
	else{
		alert("No action taken")
	}
}
//-->
</script>
  
  
  <script>
     function update_status(field, val, month, year) {
      document.location.href="viewcerts.php?action=update&field="+field+"&val="+val+"&month="+month+"&year="+year;
    }
    
      function update_vchr_stat(field, val ) {
      document.location.href="viewcerts.php?action=update_status&field="+field+"&val="+val;
    }
  </script>
  
	<style>
	
	#status { padding:10px; background:#88C4FF; color:#000; font-weight:bold; font-size:12px; margin-bottom:10px; display:none; width:90%; }

</style>				


  
  
	</head>
	<body bgcolor="#FFFFFF" leftmargin="0" marginheight="0" marginwidth="0">
	<center>
	<table border="0" cellspacing="0" width="100%" cellpadding="0">
		<tr>
	  	<td width="100%" height="50" bgcolor="#DE0029" align="left">	  		
	  			<img src="/images/shlogo.gif" WIDTH="130" HEIGHT="50">
	  			<!-- adding the user name to the header-->
	  			<? echo $_COOKIE['admin']; ?>	  	
	  	</td>
	  </tr>
	</table>
	<table border="0" cellspacing="0" width="100%" cellpadding="0">
		<tr>
          <td width="100%" height="23" bgcolor="#FFFFF" align="center">
            <a href="viewclasses.php">Classes</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="viewcerts.php">Gift Certificates</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="search_credits.php">Credits</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="vip.php">VIP process</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="mail_list.php">Mail Lists</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="viewmenu.php">Menu</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="viewstockists.php">Stockists</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="viewwines.php">Wines</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="viewnewsletters.php">Newsletters</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="viewproducts.php">Products</a>

          </td>
	  </tr>
	</table>
<br>
<?
}
function foot() {
?><center><br />Copyright © <?=date("Y");?> Spirit House. All rights reserved.<br>
<br>&nbsp;</center>
</body>
</html> <html> 
<?
}
?>