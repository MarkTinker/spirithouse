<?
include("head.php");
include("../databaseconnect.php");
//include("../globals.inc.php");
include("includes/auth.inc.php");

if(isset($_POST['submit'])) {
	
	//$classprice = ereg_replace ('[^0-9]+', '', $classprice); 
	#$sql="insert into classes values (classname, classdescription, classprice) values ('$classname', '$classdescription', $classprice);" ;
	$sql="INSERT INTO `classes` (`classid`, `classname`, `classdescription`, `classprice`, `classseats`) VALUES ('',".quote_smart($_POST['classname']).",".quote_smart($_POST['classdescription']).",".quote_smart($_POST['classprice']).",".quote_smart($_POST['classseats']).");";
	//echo $sql;
	#exit();
	$result=mysql_query($sql);
	header("location: viewclasses.php");
	
} else {
	
	head();
	?>
	<p><h3>Add class</h3></p>

	<form action="addclass.php" method="post">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Class name</td>
				<td><input type='text' name='classname'>
			</tr>
			<tr>
				<td>Class description</td>
				<td><textarea name='classdescription' rows=5 cols=40></textarea>
			</tr>
			<tr>
				<td>Class price</td>
				<td>$<input type='text' name='classprice'>
			</tr>
            <tr>
                <td>Class Seats</td>
                <td><input type='text' name='classseats'>
            </tr>
		</table>
		
		<input type="submit" name="submit" value=" submit "></td>
			
	</form>
	<?
}
foot();
?>