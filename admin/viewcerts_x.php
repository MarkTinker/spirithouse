<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

if($_GET['action']=='add') {
  if(is_numeric($_POST['vouchernumber']) && strlen($_POST['vouchernumber'])>0) {
    $voucherstatus=1;
  } else {
    $voucherstatus=0;
  }
  $sql="insert into `vouchers` (`firstname`, `lastname`, `email`, `vouchernumber`, `voucheramount`, `voucherstatus`, `voucherexpirymonth`, `voucherexpiryyear`)
  VALUES (".quote_smart($_POST['firstname']).", ".quote_smart($_POST['lastname']).", ".quote_smart($_POST['email']).",
  ".quote_smart($_POST['vouchernumber']).", ".quote_smart($_POST['voucheramount']).", $voucherstatus,
  ".quote_smart($_POST['voucherexpirymonth']).", ".quote_smart($_POST['voucherexpiryyear'])."
  )";
  mysql_query($sql);
  $display='Successfully Added Voucher No. ';
}

if($_GET['action']=="delete") {
  $sql="delete from vouchers where voucherid=".quote_smart($_GET['voucherid']);
  mysql_query($sql);
}
if($_GET['action']=="update") {
  $field=$_GET['field'];
  $val=$_GET['val'];
  //****** next two lines grab the date so we can change expiry dates ****////
  $updatemonth=$_GET['month'];
  $updateyear=$_GET['year'];
  
  $fieldarray=explode("_", $field);
  $voucherid=$fieldarray[1];
  $sql="update vouchers set voucherstatus=".quote_smart($val).", voucherexpirymonth=".quote_smart($updatemonth).", voucherexpiryyear=".quote_smart($updateyear)." where voucherid=".quote_smart($voucherid);
  mysql_query($sql);
}


head();

?>
<form action='viewcerts.php?action=add&view=all' method='post'>
<center>
<br>

<strong>Add new gift voucher</strong><br />
<table><tr><td>Voucher number:</td><td><input name='vouchernumber' size=8 /></td>
<td>Amount:</td><td><input name='voucheramount' size=7 /></td>
<td>Expiry:</td><td>
<select name='voucherexpirymonth'>
<option <? if (date('n')==1){echo'selected ';} ?>value=1>Jan</option>
<option <? if (date('n')==2){echo'selected ';} ?> value=2>Feb</option>
<option <? if (date('n')==3){echo'selected ';} ?>value=3>Mar</option>
<option <? if (date('n')==4){echo'selected ';} ?>value=4>Apr</option>
<option <? if (date('n')==5){echo'selected ';} ?>value=5>May</option>
<option <? if (date('n')==6){echo'selected ';} ?>value=6>Jun</option>
<option <? if (date('n')==7){echo'selected ';} ?>value=7>Jul</option>
<option <? if (date('n')==8){echo'selected ';} ?>value=8>Aug</option>
<option <? if (date('n')==9){echo'selected ';} ?>value=9>Sep</option>
<option <? if (date('n')==10){echo'selected ';} ?>value=10>Oct</option>
<option <? if (date('n')==11){echo'selected ';} ?>value=11>Nov</option>
<option <? if (date('n')==12){echo'selected ';} ?>value=12>Dec</option>
</select>

<select name='voucherexpiryyear'>
<option value=2008>2008</option>
<option <? if (date('Y')+1==2009){echo'selected';} ?> value=2009>2009</option>
<option <? if (date('Y')+1==2010){echo'selected';} ?> value=2010>2010</option>
<option <? if (date('Y')+1==2011){echo'selected ';} ?> value=2011>2011</option> 
</select>

</td><td><input type='submit' value='add voucher to database'></td></tr>
</table>
</form>
<br>
<? echo ('<strong>'.$display.$_POST['vouchernumber'].'<br><br></strong>');?> <a href="https://www.spirithouse.com.au/vouchers" target="_blank">Buy Voucher</a> | <a href='?action=show'>show all vouchers</a> | <a href='searchvchr.php'>Find Voucher Number</a>
</center>
<br />

<?
  if($_GET['action']=='show') {
  $sql="select * from vouchers order by vouchernumber" ;
  $result=mysql_query($sql);
  $no=mysql_num_rows($result);
  ?>
  <p><h3>All vouchers</h3></p>
  <center><table><tr><td>Voucher#</td><td>Amount</td><td>Expiry</td><td>Status</td><td>Delete</td></tr>
  <?
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
    $voucherstatus_select.=">Lost</option>";
    $voucherstatus_select.="<option value=4 ";
    if($voucherstatus==4) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Expired</option>";
    $voucherstatus_select.="</select>";

    echo "<tr><td>$vouchernumber</td><td>$$voucheramount</td><td>$voucherexpirymonth / $voucherexpiryyear</td><td>$voucherstatus_select</td><td>$delete</td></tr>";
    }
  ?>
  </table>
  </center>

<br /><br />

<?
  }

foot();
?>