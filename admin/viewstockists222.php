<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();

$sql="select * from stockists order by stockiststate, stockistname" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);




  #UPDATING STOCKISTS WORKS
  if(isset($_POST['submit11'])) {
    $sql="update `stockists` set `stockistname`='".$_POST['name']."', `stockistaddress1`='".$_POST['add1']."', `stockistsuburb`='".$_POST['suburb']."', `stockistpostcode`='".$_POST['postcode']."', `stockiststate`='".$_POST['state']."', `stockistphone`='".$_POST['phone']."', `stockistfax`='".$_POST['fax']."', `stockistemail`='".$_POST['email']."', `stockistnotes`='".$_POST['notes']."' where `stockistid`='".$_POST['itemid']."'";  
    mysql_query($sql);
    //echo $sql."<br>";
   
    $action="";
  }


switch($_GET['action'])
	{
	case "add_person":
	{
		addperson(title, $name, $surname, $add1, $add2, $citt, $state, $code);
		break;
	}
  case "edit":
	{
		edititem($_GET['itemId']);
		break;
	}
  case "delete": deleteitem($_GET['itemId']); Showpage(); break;

	

  }




 // this function actually edits the stockists
  function edititem($itemid) {
    $sql="select * from `stockists` where `stockistid`='$itemid'";

    $result=mysql_query($sql);
    $no=mysql_num_rows($result);
    if($no>0) {
       		$stockistid=trim(mysql_result($result,$t,"stockistid"));
			$stockistname=trim(mysql_result($result,$t,"stockistname"));
			$stockistaddress1=trim(mysql_result($result,$t,"stockistaddress1"));
			$stockistsuburb=trim(mysql_result($result,$t,"stockistsuburb"));
			$stockiststate=trim(mysql_result($result,$t,"stockiststate"));	
			$stockistpostcode=trim(mysql_result($result,$t,"stockistpostcode"));
			$stockistphone=trim(mysql_result($result,$t,"stockistphone"));
			$stockistfax=trim(mysql_result($result,$t,"stockistfax"));						
			$stockistemail=trim(mysql_result($result,$t,"stockistemail"));
			$stockistnotes=trim(mysql_result($result,$t,"stockistnotes"));  
    
      //echo $add1;
      $form="<h2>Update CONTACT details</h2>";
      $form.="<form name='theForm987' action='' method='post'>";
      
      $form.="<table><tr><td>Title</td><td><input type='text' name='name' value='".$stockistname."' size='50'></td>";                
          
                $form.="<tr><td>Address</td><td> <textarea name='add1' cols='30' rows='2'>".$stockistaddress1."</textarea></td>";
               
                $form.="<tr><td>Town/Suburb:</td><td> <input type='text' name='suburb'  value='".$stockistsuburb."' size='25'> </td></tr>";
                $form.="<tr><td>Postcode:</td><td> <input type='text' name='postcode' value='".$stockistpostcode."' size='10'> </td></tr>";
                $form.="<tr><td>State:</td><td> <input type='text' name='state'  value='".$stockiststate."' size' size='10'> </td></tr>";
                $form.="<tr><td>Phone:</td><td> <input type='text' name='phone'  value='".$stockistphone."' size='25'> </td></tr>";
                $form.="<tr><td>fax:</td><td> <input type='text' name='fax'  value='".$stockistfax."' size='25'> </td></tr>";
                $form.="<tr><td>Email:</td><td> <input type='text' name='email'  value='".$stockistemail."' size='25'> </td></tr>";
                $form.="<tr><td>Comments</td><td> <textarea name='notes' cols='30' rows='2'>".$stockistnotes."</textarea></td>";
                $form.="<input type='hidden' name='itemid' value='$stockistid'>";
			$form.="<tr><td></td><td><input type='submit' name='submit11' value='update item'></td></tr>";
			$form.="</table></form>";
			echo($form);
    }

  }







?>







<p><h3>View stockists</h3></p>

<p>:: <a href="addstockist.php"><b>Add a NEW stockist</b></a> ::</p>

<form action="stockistdelete.php" method="post">
	<table cellpadding="0" cellspacing="0" border="1" width="90%">
		<tr>			
			<th>Name</th>
			<th>Address</th>
			<th>Phone</th>				
			<th>Fax</th>		
			<th>Email</th>
			<th>EDIT</th>
					
			<th>&nbsp;</th>	
		</tr>
		<?
			$bgcolor="lightgrey";
			for($t=0;$t<$no;$t++)
			{			
			$stockistid=trim(mysql_result($result,$t,"stockistid"));
			$stockistname=trim(mysql_result($result,$t,"stockistname"));
			$stockistaddress1=trim(mysql_result($result,$t,"stockistaddress1"));
			$stockistsuburb=trim(mysql_result($result,$t,"stockistsuburb"));
			$newstockiststate=trim(mysql_result($result,$t,"stockiststate"));	
			$stockistpostcode=trim(mysql_result($result,$t,"stockistpostcode"));
			$stockistphone=trim(mysql_result($result,$t,"stockistphone"));
			$stockistfax=trim(mysql_result($result,$t,"stockistfax"));						
			$stockistemail=trim(mysql_result($result,$t,"stockistemail"));
			$stockistnotes=trim(mysql_result($result,$t,"stockistnotes"));														
			if($newstockiststate<>$oldstate) { 
				if($bgcolor=="lightgrey") { $bgcolor="lightpink"; } else { $bgcolor="lightgrey"; }
			}
			
			?>
			<tr bgcolor="<?echo $bgcolor;?>" align="left">	
				<td>&nbsp;<? echo $stockistname;?></td>
				<td>&nbsp;<? echo $stockistaddress1." ".$stockistsuburb." ".$newstockiststate." ".$stockistpostcode;?></td>
				<td>&nbsp;<? echo $stockistphone;?></td>
				<td>&nbsp;<? echo $stockistfax;?></a></td>
				<td>&nbsp;<? echo "<a href='mailto:$stockistemail'>$stockistemail</a>" ?></td>
				<td>&nbsp;<? echo $stockistnotes;?></a></td>
				<td>&nbsp;<? echo "<a href='viewstockists.php?action=edit&itemId=$stockistid'>Edit stockist</a>" ?></td>							
				<td align='center'><input type="checkbox" value="<? echo $stockistid;?>" name="stid[]"></td>			
			</tr>
			<?
			
			$oldstate = $newstockiststate;
			}
		?>
		
		<tr>
			<td colspan="5"><a href="addstockist.php"><b>Add a stockist to the database</b></a></td>
			<td width=10 align='center'><input type="submit" name="submit" value="Delete"></td>
		</tr>	
	</table>
</form>
<?

foot();
?>