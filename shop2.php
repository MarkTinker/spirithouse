<? 

include("includes/shipping.inc.php");
include_once('jcart/jcart.php');

include("databaseconnect.php");

$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Online Shop";
$sectionsList = "['#shop_items']";

$bigText_main = "Spirit House Shop";
$bigText_sub = "Buy recipes books and kitchen products online, <em>delivered to your door.</em>";
include("includes/discounts.config.inc.php"); // checks the cookie and sets up any discounts we might want to use - 
include("includes/header.inc.php"); 



$gifts = 'active';

#form processing
while (list($name, $value) = each($HTTP_POST_VARS)) {
  #echo substr($name,0,4)."<br>";
  #echo "this is the value: $value and name is $name <br>";
  if(substr($name,0,4)=="qty_") {
    $itemId=substr($name, -(strlen($name)-(strpos($name,"_")+1)));
    $inscription = $_POST['inscript'];
    add_to_cart($itemId, $cartid, $value, $inscription);
  }
  
  $action=$_GET['action'];
  if($action=="showcart") {
    ?><html><head><title>Spirit House Shop</title><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='REFRESH' content='1; URL=cart.php'></head></html> <html> <?
    exit();
  }
}
                            

?>

<div class="container section" id="shop_items">
<div class="row">
<div class="twelvecol content last">
<p class="info"><? if(is_fan()){echo "<span class='teal'>As a reward for being a fan of Spirit House, we've highlighted some great deals on selected shop items.</span>";} ?></p>
</div>	

<div class="fourcol sidebar">
	<h4>Shopping Cart</h4>
	<div id="jcart"><?php $jcart->display_cart($item);?></div>
	<?php
      #echo '<pre>';
      #var_dump($_SESSION['jcart']);
      #echo '</pre>';
  ?>
</div>
<!-- end.sidebar -->

<div class="eightcol last">


<?
$sql="select shop_items.*, categoryName
from `shop_items`, `shop_categoryxref`, `shop_categorytable`
where shop_items.itemid = shop_categoryxref.itemId
and shop_categoryxref.categoryId = shop_categorytable.categoryId
and shop_categorytable.categoryId > 5         
and shop_items.show=1 ORDER BY categoryName, itemid desc";

$rs = mysql_query($sql);
$num = mysql_num_rows($rs);
$itemcount = 0;

for($i = 0; $i < $num; $i++) 
{
	$categoryName = mysql_result($rs, $i, "categoryName");
	$itemId = mysql_result($rs, $i, "itemId");
	$itemName = mysql_result($rs, $i, "itemName");
	$itemDesc = mysql_result($rs, $i, "itemDesc");
	$itemPrice = mysql_result($rs, $i, "itemPrice");
	$itemWeight = mysql_result($rs, $i, "itemWeight");
	$itemPic = "http://www.spirithouse.com.au/images/products/" . mysql_result($rs, $i, "itemPic");
	
	###############
	#doing a quick test to see if the fanslood button can give a discount in here
	// TODO make a switch to see if the item is FEATURED or not and use that to trigger the discount rather than using the itemName
	###############
	$originalPrice=number_format($itemPrice,2);
	$discounttext="";
	if(($itemName=="Hot Plate") && is_fan()){
	$itemPrice=shop_discount($itemPrice);
	$discounttext="<span class='teal'>$<del>".$originalPrice."</del> for you </span>";
	}
	###############
	// Row Start
	if ($i == 0 || ($i % 3 == 0)) 
	{ 
		echo "<div class='row jcart-row'>";
		//echo "Doing a row and it's #$i";
		$itemcount = 0;
	} 
	
	$itemcount++;
	
	if ($itemcount == 3)
	{
		echo "<div class='fourcol last'>";
	}
	else
	{
		echo "<div class='fourcol'>";
	}
	
	if ($i == 0 || ($categoryName != $categoryNamePrevious))
	{
		//echo "<h2>$categoryName</h2>";
		$categoryNamePrevious = $categoryName;
	}
	
	echo "<form name='theForm$i' action='' method='post' class='jcart jcartitem'>
<fieldset>
	<input type='hidden' name='jcartToken' value='". $_SESSION['jcartToken']."' />
	<input type='hidden' name='itemId' value='$itemId' />
	<input type='hidden' name='itemPrice' value='$itemPrice' />		
	<input type='hidden' name='itemName' value='$itemName' />
	<input type='hidden' name='itemWeight' value='$itemWeight' />

	<ul> 			        			
		<li class='pic'><img src='$itemPic'></li>
		<li><h3>$itemName</h3></li>
		<li class='desc'>$itemDesc</li>
		<li class='price'>".$discounttext."$".number_format($itemPrice,2)."</li>
		<li class='addtocart'><label>Qty: <input type='text' name='itemQty' value='1' size='3' /></label><input type='submit' name='my-add-button' value='Add to Cart' class='button'></li>
	</ul>
</fieldset>
</form>";

	echo "</div>";
	
	// Row End
	if ($itemcount == 3 || $i == $num-1) 
	{ 
		//echo "Closing a row and it's #$i";
		echo "</div>";
	}
}

?>


</div>
<!-- end eightcol -->

</div>
<!-- end.row -->
   			 
</div>
<!-- end#shop_items.container -->

<? include("includes/footer.inc.php");  ?> 
 

<script type="text/javascript" src="jcart/js/jcart.min.js"></script>