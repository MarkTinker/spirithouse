<?

include("head.php");
include("../databaseconnect.php");
//include("../globals.inc.php");
include("includes/auth.inc.php");

head(); 
//This is only displayed if they have submitted the form
if(isset($_POST['submit1'])) 
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

  
?>
  <html>
  <body>
<h2>Search</h2>
<form name="search" method="post" action="searchresults.php">
Seach for: <input type="text" name="find"> in
<Select NAME="field">
<Option VALUE="firstname">First Name</option>
<Option VALUE="lastname">Last Name</option>
<Option VALUE="notes">Notes</option>
<Option VALUE="credits">Credits</option>
</Select>
<input type="submit" name="submit1" value="Search">
</form>

</body>
</html> <html> 