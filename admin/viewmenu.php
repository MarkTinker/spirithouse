<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();

include("addmenu-inline.php");

$sql="select * from menu order by menucat, menuorder, menuprice" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);

?>

<p><h3>View menu</h3></p>

<p>:: <a href="addmenu.php"><b>Add a NEW menu item</b></a> ::</p>

<form action="menudelete.php" method="post">
<div id="status"></div>
	<table cellpadding="0" cellspacing="0" border="1" width="90%">
		<tr>
			<th>Id</th>
			<th>Cat</th>			
			<th>Item</th>
			<th>Order</th>
			<th>Price</th>
			<th>In Bqt?</th>
			<th>Banquet item</th>
			<th>In Lge bqt?</th>
			<th>Group Banquet</th>							
			<th>Delete</th>		
		</tr>
		<?
			$bgcolor="#CCCCCC";
			for($t=0;$t<$no;$t++)
			{			
			$menuid=trim(mysql_result($result,$t,"menuid"));
			$menucat=trim(mysql_result($result,$t,"menucat"));
			$menuorder=trim(mysql_result($result,$t,"menuorder"));
			$menuitem=trim(mysql_result($result,$t,"menuitem"));
			$menuprice=trim(mysql_result($result,$t,"menuprice"));
			
			$menubanquet=trim(mysql_result($result,$t,"banquet"));
			$menulargebanquet=trim(mysql_result($result,$t,"banquet2"));
			
			$inbanquet='';
			if ($menubanquet>0){
			$inbanquet='banquet';
			}
			$inbanquet2='';
			if ($menulargebanquet==1){
			$inbanquet2='large banquet';
			}

				
			if($oldmenucat<>$menucat) { 
				if($bgcolor=="#ffffcc") { $bgcolor="#e4e4e4"; } else { $bgcolor="#ffffcc"; }
				echo "<tr><td bgcolor='lightpink' colspan=6>";
				switch($menucat) {
				case 1: echo "<font size=3><b>Entree</b></font>";break;
				case 2: echo "<font size=3><b>Mains</b></font>";break;
				case 3: echo "<font size=3><b>Dessert</b></font>";break;
				case 4: echo "<font size=3><b>Dessert</b></font>";
				}
				echo "</td></tr>";				
			}
			
			?>
			<tr bgcolor="<?echo $bgcolor;?>" align="left">
				<td>&nbsp;<? echo $menuid;?></td>
				<td>&nbsp;<span id='menucat:<? echo $menuid;?>' contenteditable='true'><? echo $menucat;?></span></td>
					
				<td>&nbsp;<span id='menuitem:<? echo $menuid;?>' contenteditable='true'><? echo $menuitem;?></td>				
				<td>&nbsp;<span id='menuorder:<? echo $menuid;?>' contenteditable='true'><? echo $menuorder;?></td>
				<td>&nbsp;$<span id='menuprice:<? echo $menuid;?>' contenteditable='true'><? echo $menuprice;?></td>
				<td>&nbsp;<span id='banquet:<? echo $menuid;?>' contenteditable='true'><? echo $menubanquet;?></td>
				<td>&nbsp;<? echo $inbanquet;?></td>
				<td>&nbsp;<span id='menulargebanquet:<? echo $menuid;?>' contenteditable='true'><? echo $menulargebanquet;?></td>
				<td>&nbsp;<? echo $inbanquet2;?></td>				
				<td align='center'><input type="checkbox" value="<? echo $menuid;?>" name="stid[]"></td>
			</tr>
			<?
			
			$oldmenucat = $menucat;
			}
		?>
		
		<tr>
			<td colspan="4"><a href="addmenu.php"><b>Add an item to the menu</b></a></td>
			<td width=10 align='center'><input type="submit" name="submit" value="Delete"></td>
		</tr>	
	</table>
</form>

<script>

$(function(){
	//acknowledgement message
    var message_status = $("#status");
    $("span[contenteditable=true]").blur(function(){
        var field_userid = $(this).attr("id") ;
        var value = $(this).text() ;
        $.post('addmenu-inline.php' , field_userid + "=" + value, function(data){
            if(data != '')
			{
				message_status.show();
				message_status.text(data);
				//hide the message
				setTimeout(function(){message_status.hide()},5000);
			}
        });
    });
		
});



</script>


<?

foot();
?>