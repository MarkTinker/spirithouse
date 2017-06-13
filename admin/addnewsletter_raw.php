<?
include("../databaseconnect.php");
include("includes/auth.inc.php");

if(isset($sect2)) {
				
			
	$sql="INSERT INTO `newsletters` (`newsletterdate`, `newslettersubject`, `newslettertext`, `layoutformat`) VALUES (Now(), '$subject', '$sect2', 2);";
	$result=mysql_query($sql);
	header("location: viewnewsletters.php");
	
	
} else {

	include("head.php");
	head();	

	?>
	<p><h3>Send a newsletter</h3></p>

	<table cellspacing="0" cellpadding="0" border="0">						
		<tr>
			<Td align='left'>
				<form action="addnewsletter_raw.php" method="post" name="testflash">
				<table border="0" cellpadding="2" cellspacing="0" bgcolor="#CCCCFF" width="100%"><tr><td><p><b>&nbsp;Newsletter subject: </b> <input name="subject" size="70" maxlength="100"></p></td></tr></table>
				<p>Enter your newsletter text in the editor below - please be patient as the editor may take a few moments to load...</p>			
			</td>
		</tr>
		<tr>
			<td align='center'>							  
			  <textarea name="sect2" cols=80 rows=25 WRAP></textarea>
		    </td>
		</tr>							  							  							
	</table>
	<input type='submit' value=' save '>				
	</form>

	<?
}

foot();
?>