<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");


head();

include("addwine-inline.php");
$sql="select * from wines order by wineCat, wineBtl" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);

?>
<p><h3>View Wine List</h3>
Click on text to EDIT and then DOUBLE CLICK to record the edit!
</p>

<p>:: <a href="addwine.php"><b>Add a NEW wine to list</b></a> ::</p>

<form action="winedelete.php" method="post">
<div id="status"></div>

	<table cellpadding="0" cellspacing="0" border="1" width="90%">
		<tr>			
			<th>Item</th>
			<th>Cat</th>
			<th>Year</th>							
			<th>Name</th>
			<th>Region</th>
			<th>State</th>
			<th>$Gls</th>
			<th>$btl</th>
			<th>Desc.</th>							
			<th>Delete</th>		
		</tr>
		<?
			$bgcolor="#CCCCCC";
			for($t=0;$t<$no;$t++)
			{			
			$wineId=trim(mysql_result($result,$t,"wineId"));
			$wineCat=trim(mysql_result($result,$t,"wineCat"));
			$wineYear=trim(mysql_result($result,$t,"wineYear"));
			$wineName=trim(mysql_result($result,$t,"wineName"));
			$wineRegion=trim(mysql_result($result,$t,"wineRegion"));
			$wineState=trim(mysql_result($result,$t,"wineState"));
			$wineGls=trim(mysql_result($result,$t,"wineGls"));
			$wineBtl=trim(mysql_result($result,$t,"wineBtl"));
			$wineDesc=trim(mysql_result($result,$t,"wineDesc"));
				
			if($oldwineCat<>$wineCat) { 
				if($bgcolor=="#CCCCCC") { $bgcolor="#e4e4e4"; } else { $bgcolor="#CCCCCC"; }
				echo "<tr><td bgcolor='lightpink' colspan=10>";
				switch($wineCat) {
				
					case 1: echo "<font size=3><b>Sparkling</b></font>";break;
					case 2: echo "<font size=3><b>Riesling</b></font>";break;
					case 3: echo "<font size=3><b>Sauvigion Blanc</b></font>";break;
					case 4: echo "<font size=3><b>Aromatics &amp; Varietals</b></font>";break;
					case 5: echo "<font size=3><b>Pinot Gris</b></font>";break;
					case 6: echo "<font size=3><b>Chardonnay</b></font>";break;
					case 7: echo "<font size=3><b>Ros&eacute;</b></font>";break;
					case 8: echo "<font size=3><b>Pinot Noir</b></font>";break;
					case 9: echo "<font size=3><b>Merlot</b></font>";break;
					case 10: echo "<font size=3><b>Red Varietals &amp; Blends</b></font>";break;
					case 11: echo "<font size=3><b>Shiraz</</b></font>";break;
					case 12: echo "<font size=3><b>Ciders</</b></font>";break;
					case 13: echo "<font size=3><b>Beers</</b></font>";break;
				
				}
			
				echo "</td></tr>";				
			}
			
			?>
			<tr bgcolor="<?echo $bgcolor;?>" align="left">					
					
			<td>&nbsp;<? echo $wineId;?></td>
			<td>&nbsp;<span id='wineCat:<? echo $wineId;?>' contenteditable='true'><? echo $wineCat;?></span></td>
			<td>&nbsp;<span id='wineYear:<? echo $wineId;?>'contenteditable='true'><? echo $wineYear;?></span></td>
			<td>&nbsp;<span id='wineName:<? echo $wineId;?>'contenteditable='true'><? echo $wineName;?></span></td>
			<td>&nbsp;<span id='wineRegion:<? echo $wineId;?>'contenteditable='true'><? echo $wineRegion;?></span></td>
			<td>&nbsp;<span id='wineState:<? echo $wineId;?>'contenteditable='true'><? echo $wineState;?></span></td>
			<td>&nbsp;$<span id='wineGls:<? echo $wineId;?>' contenteditable='true'><? echo $wineGls;?></span></td>
			<td>&nbsp;$<span id='wineBtl:<? echo $wineId;?>' contenteditable='true'><? echo $wineBtl;?></span></td>
			<td>&nbsp;<span id='wineDesc:<? echo $wineId;?>' contenteditable='true'><? echo $wineDesc;?></span></td>
				
				
				
							
				<td align='center'><input type="checkbox" value="<? echo $wineId;?>" name="stid[]"></td>
			</tr>
			<?
			
			$oldwineCat = $wineCat;
			}
		?>
		
		<tr>
			<td colspan="9"><a href="addwine.php"><b>Add wine to List</b></a></td>
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
        $.post('addwine-inline.php' , field_userid + "=" + value, function(data){
            if(data != '')
			{
				message_status.show();
				message_status.text(data);
				//hide the message
				setTimeout(function(){message_status.hide()},8000);
			}
        });
    });
		
});
</script>

<?

foot();
?>