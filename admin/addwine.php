<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

if(isset($_POST['submit'])) {

	$sql="INSERT INTO `wines` (`wineCat`, `wineYear`, `wineName`, `wineRegion`, `wineState`, `wineGls`, `wineBtl`, `wineDesc`) VALUES (".quote_smart($_POST['wineCat']).",'".$_POST['wineYear']."',".quote_smart($_POST['wineName']).",".quote_smart($_POST['wineRegion']).",".quote_smart($_POST['wineState']).",".quote_smart($_POST['wineGls']).",".quote_smart($_POST['wineBtl']).",".quote_smart($_POST['wineDesc']).");";
	#echo $sql;
	#exit();
	$result=mysql_query($sql);
	header("location: viewwines.php");

} else {
	
	head();
	?>
	<p><h3>Add wine to list</h3></p>	
			
	<form action="addwine.php" method="post">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Wine category</td>
				<td><select name='wineCat'>
					<option value='1'>Sparkling</option>
					<option value='2'>Riesling</option>
					<option value='3'>Sauviginon Blanc</option>
					<option value='4'>Aromatics &amp; Varietals</option>
					<option value='5'>Pinot Gris</option>
					<option value='6'>Chardonnay</option>
					<option value='7'>Ros&eacute;</option>
					<option value='8'>Pinot Noir</option>
					<option value='9'>Merlot</option>
					<option value='10'>Red Varietals &amp; Blends</option>
					<option value='11'>Shiraz/Syrah</option>	
					<option value='12'>Ciders</option>
					<option value='13'>Beers</option>							
					</select>
					
					
					
								
												
			</tr>			
			
			<tr>
				<td>Vintage</td>
				<td><input type='text' size=6 name='wineYear'>
			</tr>
			
			<tr>
				<td>Wine Name</td>
				<td><input type='text' size=30 name='wineName'>
			</tr>
			<tr>
				<td>Region</td>
				<td><input type='text' size=30 name='wineRegion'>
			</tr>
			<tr>
				<td>State/Country</td>
				<td><input type='text' size=6 name='wineState'>
			</tr>
			<tr>
				<td>Glass Price</td>
				<td><input type='text' size=6 name='wineGls'>
			</tr>
			<tr>
				<td>Bottle Price</td>
				<td><input type='text' size=6 name='wineBtl'>
			</tr>
			<tr>
				<td>Description</td>
				<td><input type='text' size=30 name='wineDesc'>
			</tr>						
		</table>
		
		<input type="submit" name="submit" value=" submit "></td>
			
	</form>
	<?
}
foot();
?>