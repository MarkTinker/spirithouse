<?php
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

$pag_action=$_GET['action'];
$pag_type=$_GET['type'];

//echo $pag_type;
//exit();

if($_GET['action']=='add') {
  if(is_numeric($_POST['vouchernumber']) && strlen($_POST['vouchernumber'])>0) {
    $voucherstatus=1;
  } else {
    $voucherstatus=0;
  }
  $sql="insert into `vouchers` (`firstname`, `lastname`, `fromname`, `email`, `vouchernumber`, `voucheramount`, `voucherstatus`, `voucherexpirymonth`, `voucherexpiryyear`)
  VALUES (".quote_smart($_POST['firstname']).", ".quote_smart($_POST['lastname']).", ".quote_smart($_POST['fromname']).",
  ".quote_smart($_POST['email']).",
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
 
  $updatemonth=$_GET['month'];
  $updateyear=$_GET['year'];
   
  $fieldarray=explode("_", $field);
  $voucherid=$fieldarray[1];
  $sql="update vouchers set voucherstatus=".quote_smart($val).", voucherexpirymonth=".quote_smart($updatemonth).", voucherexpiryyear=".quote_smart($updateyear)." where voucherid=".quote_smart($voucherid);
  echo $sql;
  exit();
  
  mysql_query($sql);
}

if($_GET['action']=="update_status") {
  $field=$_GET['field'];
  $val=$_GET['val'];
  $fieldarray=explode("_", $field);
  $voucherid=$fieldarray[1];
  $sql="update vouchers set voucherstatus=".quote_smart($val)." where voucherid=".quote_smart($voucherid);
  mysql_query($sql);
}


head();

?>
<form action='viewcerts.php?action=add&view=all' method='post'>
<center>
<br>
<h2> <?=$voucherupdatemessage ?></h2>
<strong>Add new gift voucher</strong><br />
<table><tr><td>Voucher number:</td><td><input name='vouchernumber' size=8 autofocus /></td>
<td>Amount:</td><td><input name='voucheramount' size=7 /></td>
<td>From:</td><td><input name='fromname' size=7 /></td>
<td>Expiry:</td><td>
<select name='voucherexpirymonth'>
<option <?php if (date('n')==1){echo'selected ';} ?>value=1>Jan</option>
<option <?php if (date('n')==2){echo'selected ';} ?> value=2>Feb</option>
<option <?php if (date('n')==3){echo'selected ';} ?>value=3>Mar</option>
<option <?php if (date('n')==4){echo'selected ';} ?>value=4>Apr</option>
<option <?php if (date('n')==5){echo'selected ';} ?>value=5>May</option>
<option <?php if (date('n')==6){echo'selected ';} ?>value=6>Jun</option>
<option <?php if (date('n')==7){echo'selected ';} ?>value=7>Jul</option>
<option <?php if (date('n')==8){echo'selected ';} ?>value=8>Aug</option>
<option <?php if (date('n')==9){echo'selected ';} ?>value=9>Sep</option>
<option <?php if (date('n')==10){echo'selected ';} ?>value=10>Oct</option>
<option <?php if (date('n')==11){echo'selected ';} ?>value=11>Nov</option>
<option <?php if (date('n')==12){echo'selected ';} ?>value=12>Dec</option>
</select>

<select name='voucherexpiryyear'>
<option <?php if (date('Y')+1==2016){echo'selected="selected"';} ?> value=2016>2016</option>
<option <?php if (date('Y')+1==2017){echo'selected="selected"';} ?> value=2017>2017</option>
<option <?php if (date('Y')+1==2018){echo'selected="selected"';} ?> value=2018>2018</option> 
</select>

</td><td><input type='submit' value='add voucher to database'></td></tr>
</table>
</form>
<br>
<?php echo ('<strong>'.$display.$_POST['vouchernumber'].'<br><br></strong>');?> <a href="https://www.spirithouse.com.au/vouchers" target="_blank">Buy Voucher</a> | show <a href='?action=show'>all</a> / <a href='?action=show&type=postal'>postal</a> / <a href='?action=show&type=email'>email</a> vouchers | <a href='searchvchr.php'>Find Voucher</a>
</center>
<br />

<?php

  
  
  
  if($_GET['action']=='show') {
	if (isset($_GET['type'])) {
		if ($_GET['type']=='postal') {
  $sql="select * from vouchers WHERE security='' order by vouchernumber DESC";
		};
		if ($_GET['type']=='email') {
  $sql="select * from vouchers WHERE security!='' order by vouchernumber DESC ";
  
		};				
	} else {
  $sql="select * from vouchers order by vouchernumber DESC" ;
  
	};
	
  $result=mysql_query($sql);
  $numrows=mysql_num_rows($result);
  
  	$rowsperpage = 30;
	// find out total pages
	$totalpages = ceil($numrows / $rowsperpage);
	
	// get the current page or set a default
		if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_GET['currentpage'];
		} else {
   // default page num
   $currentpage = 1;
	} // end if
	
	// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end ifx_affected_rows(


	
// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

if (isset($_GET['type'])) {
		if ($pag_type=='postal') {
  $sql2="select * from vouchers WHERE security=''  order by vouchernumber DESC LIMIT $offset, $rowsperpage";
		};
		if ($pag_type=='email') {
  $sql2="select * from vouchers WHERE security!='' order by vouchernumber DESC LIMIT $offset, $rowsperpage";
  
		};				
	} else {
  $sql2="select * from vouchers  order by vouchernumber DESC LIMIT $offset, $rowsperpage " ;
  };


	 	$result2=mysql_query($sql2);
  		$no2=mysql_num_rows($result2);
	//echo "<h1>OFFSET: $offset - rows $rowsperpage</h1>";
	//echo $pag_type;
	//echo $sql2;
	//exit();
	
  
  ?>
  <p><h3>All vouchers </h3></p>
  <center><table><tr><td>Voucher#</td><td>Sec code</td><td>Amount</td><td>Expiry</td><td>e-mail</td><td>Status</td><td>Delete</td></tr>
  <?php
  
  for($t=0;$t<$no2;$t++)
    {
    $voucherid=trim(mysql_result($result2,$t,"voucherid"));
    $firstname=trim(mysql_result($result2,$t,"firstname"));
    $lastname=trim(mysql_result($result2,$t,"lastname"));
    $email=trim(mysql_result($result2,$t,"email"));
    $security=trim(mysql_result($result2,$t,"security"));
    $security=substr($security,-4);
	if ($security==0) { $security = "post"; }; 	
    $voucheramount=trim(mysql_result($result2,$t,"voucheramount"));
    $voucherstatus=trim(mysql_result($result2,$t,"voucherstatus"));
    $vouchernumber=trim(mysql_result($result2,$t,"vouchernumber"));

    $voucherexpirymonth=trim(mysql_result($result2,$t,"voucherexpirymonth"));
    $voucherexpiryyear=trim(mysql_result($result2,$t,"voucherexpiryyear"));
    $delete="<a href='javascript:if(confirm(\"Are you sure?\")) { document.location.href=\"viewcerts.php?view=all&action=delete&voucherid=$voucherid\"; }'>delete</a>";

    $voucherstatus_select="<select id='status_$voucherid' onchange='update_vchr_stat(\"status_$voucherid\", document.getElementById(\"status_$voucherid\").value );' style='font-size:10px' name='voucherstatus'>";

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
    
    $voucherstatus_select.="<option value=5";
    if($voucherstatus==5) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Cancelled</option>";
    
     $voucherstatus_select.="<option value=6 ";
    if($voucherstatus==6) { $voucherstatus_select.=" selected "; }
    $voucherstatus_select.=">Lost</option>";
    
    $voucherstatus_select.="</select>";

    echo "<tr><td><a href='./searchvchrresults.php?emailfind=".$vouchernumber."'>".$vouchernumber."</a></td><td>$security</td><td>$$voucheramount</td><td>$voucherexpirymonth / $voucherexpiryyear</td><td>$email</td><td>$voucherstatus_select</td><td>$delete</td></tr>";
    }
  ?>
  </table>
  </center>

<br /><br />

<?php
  /******  build the pagination links ******/
// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?action=$pag_action&type=$pag_type&currentpage=1'><<</a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='{$_SERVER['PHP_SELF']}?action=$pag_action&type=$pag_type&currentpage=$prevpage'><</a> ";
} // end if


// range of num links to show
$range = 3;

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range)  + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?action=$pag_action&type=$pag_type&currentpage=$x'>$x</a> ";
      } // end else
   } // end if 
} // end for  
  
  }

foot();
?>