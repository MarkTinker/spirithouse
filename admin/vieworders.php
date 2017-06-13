<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();

  ?><p><h3>Viewing unprocessed orders</h3></p><?

  $sql="select shop_orderid, austdate, billfirstname, billlastname, total
        from `shop_orders`, `shop_users`
        where shop_orders.userid=shop_users.userid
        and `processed`=0
        ";

  $result=mysql_query($sql);
  $no=mysql_num_rows($result);

  echo"<table cellpadding=4 cellspacing=1>";

  for($t=0;$t<$no;$t++)	{
		$shop_orderid=(mysql_result($result,$t,shop_orderid));
		$firstname=(mysql_result($result,$t,billfirstname));
		$lastname=(mysql_result($result,$t,billlastname));
		$total=(mysql_result($result,$t,total));
    $austdate=(mysql_result($result,$t,austdate));

    if($bgcolor=="#ffffff") { $bgcolor="#efefef"; } else { $bgcolor="#ffffff"; }

		echo "<tr bgcolor='$bgcolor'>
    <td>$austdate</td>
    <td>$firstname $lastname</td>
    <td>".get_items($shop_orderid)."</td>
		<td height='25'>$".number_format($total,2)."</td>
    </tr>";

  }
	echo "</table><br />";



foot();

function get_items($orderid) {
  $output="";
  $sql2="select itemName, qty, itemPrice
        from `shop_orderdetails`, shop_items
        where shop_orderdetails.itemid=shop_items.itemId
        and `shop_orderid`='$orderid'";

  $result2=mysql_query($sql2);
	$no2=mysql_num_rows($result2);
  for($t=0;$t<$no2;$t++)	{
		$itemName=(mysql_result($result2,$t,itemName));
		$qty=(mysql_result($result2,$t,qty));
    $itemPrice=(mysql_result($result2,$t,itemPrice));

    $output.="$itemName x $qty (".($itemPrice*$qty).")&nbsp;&nbsp;";
  }
  return $output;
}