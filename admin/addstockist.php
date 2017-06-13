<?
include("head.php");
include("../databaseconnect.php");
//include("../globals.inc.php"); 
include("includes/auth.inc.php");

if(isset($_POST['submit'])) {

    $sql="INSERT INTO `stockists` (`stockistname`, `stockistaddress1`, `stockistsuburb`, `stockiststate`, `stockistpostcode`, `stockistphone`, `stockistfax`, `stockistemail`) VALUES (".quote_smart($_POST['stockistname']).",".quote_smart($_POST['stockistaddress1']).",".quote_smart($_POST['stockistsuburb']).",".quote_smart($_POST['stockiststate']).",".quote_smart($_POST['stockistpostcode']).",".quote_smart($_POST['stockistphone']).",".quote_smart($_POST['stockistfax']).",".quote_smart($_POST['stockistemail']).");";
	#echo $sql;
	#exit();
	$result=mysql_query($sql);
	header("location: viewstockists.php");
	
} else {
	
	head();
	?>
	<p><h3>Add stockist</h3></p>	
			
	<form action="addstockist.php" method="post">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Stockist name</td>
				<td><input type='text' size=30 name='stockistname'>
			</tr>
				<tr>
				<td>Stockist address</td>
				<td><input type='text' size=30 name='stockistaddress1'>
			</tr>
				<tr>
				<td>Stockist suburb</td>
				<td><input type='text' size=30 name='stockistsuburb'>
			</tr>
				<tr>
				<td>Stockist state</td>
				<td><select name='stockiststate'>
					<option value='QLD'>QLD</option>
					<option value='NSW'>NSW</option>
					<option value='VIC'>VIC</option>
					<option value='SA'>SA</option> 
					<option value='WA'>WA</option>  
					<option value='TAS'>TAS</option> 
					<option value='NT'>NT</option> 
					<option value='ACT'>ACT</option> 
					</select>				
			</tr>
				<tr>
				<td>Stockist postcode</td>
				<td><input type='text' size=6 name='stockistpostcode'>
			</tr>
				<tr>
				<td>Stockist phone</td>
				<td><input type='text' size=30 name='stockistphone'>
			</tr>
				<tr>
				<td>Stockist fax</td>
				<td><input type='text' size=30 name='stockistfax'>
			</tr>
			<tr>
				<td>Stockist email</td>
				<td><input type='text' size=30 name='stockistemail'>
			</tr>
			
		</table>
		
		<input type="submit" name="submit" value=" submit "></td>
			
	</form>
	<?
}
foot();
?>