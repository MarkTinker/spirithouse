<?
include("head.php");
//include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");


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




<p><h3>Searching <i><? echo $field; ?></i> for: <? echo $find; ?></h3></p>
<?
  $sql = "select * from vouchers where vouchers.voucherstatus LIKE \"%".$field."%\" order by vouchernumber ";   
  $result = mysql_query($sql);
  $no = mysql_num_rows($result);
  ?>
  <p><h3>All vouchers</h3></p>
  <center><table><tr><td>Voucher#</td><td>Amount</td><td>Expiry</td><td>Status</td><td>Delete</td></tr>
  <?
  $total=0;
  for($t=0;$t<$no;$t++)
    {
    $voucherid=trim(mysql_result($result,$t,"voucherid"));
    $firstname=trim(mysql_result($result,$t,"firstname"));
    $lastname=trim(mysql_result($result,$t,"lastname"));
    $email=trim(mysql_result($result,$t,"email"));
    $voucheramount=trim(mysql_result($result,$t,"voucheramount"));
    $voucherstatus=trim(mysql_result($result,$t,"voucherstatus"));
    $vouchernumber=trim(mysql_result($result,$t,"vouchernumber"));

    $voucherexpirymonth=trim(mysql_result($result,$t,"voucherexpirymonth"));
    $voucherexpiryyear=trim(mysql_result($result,$t,"voucherexpiryyear"));
    $delete="<a href='javascript:if(confirm(\"Are you sure?\")) { document.location.href=\"viewcerts.php?view=all&action=delete&voucherid=$voucherid\"; }'>delete</a>";

    $voucherstatus_select="<select id='status_$voucherid' onchange='update_status(\"status_$voucherid\", document.getElementById(\"status_$voucherid\").value );' style='font-size:10px' name='voucherstatus'>";

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
    
    $total=$total+$voucheramount;

    echo "<tr><td>$vouchernumber</td><td>$$voucheramount</td><td>$voucherexpirymonth / $voucherexpiryyear</td><td>$voucherstatus_select</td><td>$delete</td></tr>";
    }
  ?>
  </table>
   <p> <strong><? echo $t; ?> vouchers | Total =$<? echo number_format($total,2); ?></strong></p> 
  </center>





<?
foot();
?>