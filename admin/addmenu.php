<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

if(isset($_POST['submit'])) {

	if($_POST['menubanquet']=="on") {
	$menubanquet=1;
	} else {
	$menubanquet=0;
	}
	
	
		if($_POST['menulargebanquet']=="on") {
	$menulargebanquet=1;
	} else {
	$menulargebanquet=0;
	}
	//echo "this is menubanquet $menubanquet";
	//exit();

	$sql="INSERT INTO `menu` (`menucat`, `menuorder`, `menuitem`, `menuprice`, `banquet`, `banquet2`) VALUES (".quote_smart($_POST['menucat']).",".quote_smart($_POST['menuorder']).",".quote_smart($_POST['menuitem']).",".quote_smart($_POST['menuprice']).",$menubanquet, $menulargebanquet)";
	//echo $sql;
	//exit();
	$result=mysql_query($sql);
	header("location: viewmenu.php");

} else {
	
	head();
	?>
	<p><h3>Add menu item</h3></p>	
			
	<form action="addmenu.php" method="post">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Menu category</td>
				<td><select name='menucat'>
					<option value='1'>Entree</option>
					<option value='2'>Mains</option>
					<option value='3'>Sides</option>
					<option value='4'>Dessert</option>					
					</select>				
			</tr>			
			
			<tr>
				<td>Order on menu</td>
				<td><input type='text' size=4 name='menuorder' value='0'>
			</tr>
			
			<tr>
				<td>Menu item</td>
				<td><input type='text' size=30 name='menuitem'>
			</tr>
			
			<tr>
				<td>Menu price</td>
				<td><input type='text' size=6 name='menuprice'>
			</tr>
			
			<tr>
				<td>Banquet Item?</td>
				<td><input type='checkbox' name='menubanquet'>
			</tr>
			<tr>
				<td>Large Group Banquet Item?</td>
				<td><input type='checkbox' name='menulargebanquet'>
			</tr>
									
		</table>
		
		<input type="submit" name="submit" value=" submit "></td>
			
	</form>
	<?
}
foot();
?>