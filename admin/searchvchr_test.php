<?php

include("head.php");
include("../databaseconnect.php");
//include("../globals.inc.php");
include("includes/auth.inc.php");


head(); 
//This is only displayed if they have submitted the form
if (isset($_POST['submit1'])) 
{
    echo "<h2>Results</h2><p>";
	
	
	//If they did not enter a search term we give them an error
	if ($_POST['find'] == "")
	{
	echo "<p>You forgot to enter a search term";
	exit;
	}
	
	
	//This counts the number or results - and if there wasn't any it gives them a little message explaining that
	$anymatches=mysql_num_rows($data);
	if ($anymatches == 0)
	{
	echo "Sorry, but we can not find an entry to match your query<br><br>";
	}
	
	//And we remind them what they searched for
	echo "<b>Searched For:</b> " .$find;
	}

	$number=$_GET['number'];

  
?>
  <html>
  <body>

<?php if ($number>1){echo "<h2> Voucher:$number expiry updated </h2>";} ?>
<h2>Search</h2>
<form name="search" method="post" action="searchvchrresults_test.php">
Search for: <input type="text" name="find"> in
<Select NAME="field">
<Option VALUE="vouchernumber">Voucher Number</option>
<Option VALUE="contactdetails">Contact Details</option>
</Select>
<input type="submit" name="submit1" value="Search">
</form>


<form name="status" method="post" action="searchstatusresults.php">
Seach for: 
<Select NAME="field">
<Option VALUE="1">active</option>
<Option VALUE="2">presented</option>
<Option VALUE="3">holding</option>
<Option VALUE="4">expired</option>  
<Option VALUE="5">cancelled</option>  
</Select>
Vouchers
<input type="submit" name="submit2" value="Search">
</form>




</body>
</html> <html> 