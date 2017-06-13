<?php
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");

//****** code to update a change in expiry date only *****/////
if(isset($_POST['submitexpiry'])) {
$field=$_POST['field'];
  //****** next two lines grab the date so we can change expiry dates ****//// 
  $voucherid=quote_smart($_POST['voucherid']);
  $sql="update vouchers set voucherexpirymonth=".quote_smart($_POST['month']).", voucherexpiryyear=".quote_smart($_POST['year'])." where voucherid=$voucherid";
  //echo $sql;
  mysql_query($sql);
	$number=quote_smart($_POST['vouchernumber']);
  header("location: searchvchr_test.php?number=$number");
  
  exit();

}


// We preform a bit of filtering
$find = strtoupper($_POST['find']);
$find = strip_tags($find);
$find = trim ($find);



//$find= $_POST['find'];
$field= $_POST['field'];
head();

//$sql = "select * from vouchers where vouchers.vouchernumber == \"%".$find."%\"";
//$result=mysql_query($sql);
//$no=mysql_num_rows($result);

?>


<p><h3>Searching <i><?php echo $field; ?></i> for: <?php echo $find; ?></h3></p>
<?php
if (($field=="vouchernumber")AND(is_numeric ($find))){
  $sql = "select * from vouchers where vouchers.vouchernumber LIKE \"".$find."%\"";
	};

if (($field=="contactdetails")OR(!is_numeric ($find))){
  $sql = "select * from vouchers WHERE vouchers.firstname LIKE \"".$find."%\" OR vouchers.lastname LIKE \"".$find."%\" OR vouchers.email LIKE \"".$find."%\" ";
	};

  $result = mysql_query($sql);
  $no = mysql_num_rows($result);
  ?>
  <p><h3>All vouchers</h3></p>
  <center><table><tr><td>Voucher#</td><td>Cust. Details</td><td>Amount</td><td>Expiry Month</td><td>Expiry Year</td><td>Notes</td><td>Change Expiry ONLY?<td>Status</td><td>Delete</td></tr>
  <?php
  for($t=0;$t<$no;$t++)
    {
    $voucherid=trim(mysql_result($result,$t,"voucherid"));
    $security=trim(mysql_result($result,$t,"security"));
    $security=substr($security,-4);
	if ($security==0) { $security = "post"; }; 
    $firstname=trim(mysql_result($result,$t,"firstname"));
    $lastname=trim(mysql_result($result,$t,"lastname"));
    $email=trim(mysql_result($result,$t,"email"));
    $voucheramount=trim(mysql_result($result,$t,"voucheramount"));
    $voucherstatus=trim(mysql_result($result,$t,"voucherstatus"));
    $vouchernumber=trim(mysql_result($result,$t,"vouchernumber"));
    $vouchernotes=trim(mysql_result($result,$t,"vouchernotes"));

    $voucherexpirymonth=trim(mysql_result($result,$t,"voucherexpirymonth"));
    $voucherexpiryyear=trim(mysql_result($result,$t,"voucherexpiryyear"));
    $delete="<a href='javascript:if(confirm(\"Are you sure?\")) { document.location.href=\"viewcerts_test.php?view=all&action=delete&voucherid=$voucherid\"; }'>delete</a>";

    $newexpirymonth=date("m");
    $newexpiryyear=date("Y");
    $voucherstatus_select="<select id='status_$voucherid' onchange='update_status(\"status_$voucherid\", document.getElementById(\"status_$voucherid\").value, document.getElementById(\"month\").value, document.getElementById(\"year\").value );' style='font-size:10px' name='voucherstatus'>";

    $voucherstatus_select.="<option value=1 ";
    if($voucherstatus==1) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Active</option>";
    $voucherstatus_select.="<option value=2 ";
    if($voucherstatus==2) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Presented</option>";
    $voucherstatus_select.="<option value=3 ";
    if($voucherstatus==3) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Holding</option>";
    $voucherstatus_select.="<option value=4 ";
    if($voucherstatus==4) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Expired</option>";
    $voucherstatus_select.="</select>";

    echo "<form METHOD='POST' ACTION='searchvchrresults_test.php' ID='form1' NAME='form1'>
<tr>
   
    <td>$vouchernumber<br/><small>-$security</small></td><td>$lastname, $firstname<br/><small>$email</small></td><td>$$voucheramount</td>
    <td><input type='text' size=6 value='$voucherexpirymonth' id='month' name='month'></td>
    <td><input type='text' size=6 value='$voucherexpiryyear' id='year' name='year'></td>
    <td>".$vouchernotes."&nbsp;</td>
    <td> <input type='hidden' name='voucherid' value='$voucherid'>
    <input type='hidden' name='vouchernumber' value='$vouchernumber'>
  		<input type='submit' name='submitexpiry' value='Change Expiry'></td>
    <td>$voucherstatus_select</td><td>$delete</td></tr>";
    }
  ?>
  </table>
  </center>





<?php
foot();
?>