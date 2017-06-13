<? 
#session_start();

include("includes/shipping.inc.php");

include("includes/voucher_functions.inc.php");

include("databaseconnect.php"); 

include_once('jcart/jcart.php');

$totalweight=0;
$cartcontents=$jcart->get_cart_contents();
for($i=0;$i<sizeof($cartcontents);$i++) {
	$totalweight=$totalweight+$cartcontents[$i]['subtotalweight'];
}

$postcode="";
$handling=5;
$shipping=0;
$state="";

if(isset($_SESSION['postcode']) && strlen($_SESSION['postcode'])==4) $postcode=$_SESSION['postcode'];

if(isset($_GET['postcode']) && strlen($_GET['postcode'])==4) {	
	$postcode=$_GET['postcode'];
	$_SESSION['postcode']=$postcode;
	$handling=5;
}

if($postcode!=0 && strlen($postcode)==4) $shipping=$total_shipping=calc_shipping($totalweight,$postcode);


$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Shopping Checkout";
$sectionsList = "['#shop_checkout']";

$hideBigText = true;

include("includes/header.inc.php"); 

for($i=0;$i<sizeof($cartcontents);$i++) {
	$product_id=$cartcontents[$i]['id'];
	$product_name=$cartcontents[$i]['name'];
	$product_qty=$cartcontents[$i]['qty'];
	$product_weight=$cartcontents[$i]['weight'];
	$product_subtotal=$cartcontents[$i]['subtotal'];
	$product_subtotalweight=$cartcontents[$i]['subtotalweight'];	
	$grand_total=$grand_total+$product_subtotal;
}

$grand_total=$grand_total+$shipping+$handling;
//echo $handling;
//exit();
?>
<div class="container section" id="shop_checkout">
	
	<div class="row pad20">
		
		
		<div class="twocol"></div>
		
		<div class="eightcol content">
				<div class="wrap">
				<div class="inner" style="position: relative;">
				  <div id="jcart"><?php $jcart->display_cart();?></div>
				</div>
				</div>				
		</div>
		
		<div class="twocol last"></div>		
	</div>
	<div class="row">	
<?

#echo $postcode;
if(is_numeric($postcode) && strlen($postcode)>3) {

?>
		<div class="twocol"></div>
		
		<div class="eightcol content">
		<div class="wrap">
		<div class="inner" style="position: relative;">
		<img src="resources/bookmark.png" class="bookmark" />
		
<form action="shop_process.php" method='post' name='form1' id='form1' class='spiritform'>

<fieldset>
	<legend>Delivery Details</legend>
	<label for="firstname">Your First Name</label>
	<input type="text" name="firstname" id="firstname" class="required">
	
	<label for="lastname">Your Last Name</label>
	<input type="text" name="lastname" id="lastname" class="required">
	
	<label for="address1">Your address</label>
	<input type="text" name="address1" id="address1">
	
	<label for="address2">Your town/suburb</label>
	<input type="text" name="address2" id="address2">
	
	<label for="state">Your state</label>
	<select name='state' type='text'>
	<option value='ACT'>Australian Capital Territory</option>
	<option selected=selected value='QLD'>Queensland</option>
	<option value='NSW'>New South Wales</option>
	<option value='NT'>Northern Territory</option>
	<option value='SA'>South Australia</option>
	<option value='TAS'>Tasmania</option>
	<option value='VIC'>Victoria</option>	
	<option value='WA'>Western Australia</option>	
	</select>

	<div class="clear"></div>
	<label for="state">Your postcode</label>
	<span class="clear"><?=$postcode;?></span>
		<hr />
</fieldset>

<fieldset>
	<legend>Contact Details</legend>
	
	<label for="emailcheck">Phone number</label>
	<input type="text" name="phone" id="phone" />
		
	<label for="email">Email</label>
	<input type="text" name="email" id="email" type='email' />

	<hr />
</fieldset>


<fieldset>
<legend>Payment Details</legend>
	
	<div id='totalsummary'>
		<p>Total amount payable
		<strong><big>$<?=number_format($grand_total,2);?></big></strong></p>
	</div>
	<input type='hidden' name='total_payable' value='<?=$grand_total;?>' />
	<input type='hidden' name='postcode' value='<?=$postcode;?>' />
	<input type='hidden' name='shipping' value='<?=$shipping;?>' />
	
	<hr />
	
	<label for='nameoncard1'>Name on Credit Card</label>
	<input type="text" name='nameoncard1' id='nameoncard1' value='<?=$_POST['nameoncard1'];?>' />
	
	<label for="cardnumber1">Credit Card Number</label>
	<input type="text" name='cardnumber1' id='cardnumber1' />
	
	<label for='expiry_m1' style="float: left;line-height: 40px; margin-right: 10px; ">Credit Card Expiry</label>
	<?=select_month("expiry_m1", "cc_droplist", "");?> <?=select_year("expiry_y1", "cc_droplist", "");?>
	
	<div class="clear"></div>
	
	<label for="ccv1" style="float: left; line-height: 40px; margin-right: 10px;">Card Security Number (CCV)</label>
	<input type="text" name='ccv1' id='ccv1' size=4 maxlength=4 style="width: 43px; float: left;" />
	<img src="resources/ccv.png" width="46" height="28" class="ccv" alt="Back of Credit card showing CCV" title="The CCV can be found on the back of your Credit Card." />
	
	<div class="clear"></div>
	<hr />
	
	<div class='tac'>
		<label for='chk_tc2'><input type='checkbox' id='chk_tc2' name='chk_tc2' />
		Yes &mdash; I have read and agreed to the 
			<a href='#nogo'onclick="javascript:tandc_window();" rel="1" class="terms">terms and conditions</a> of this purchase.
		</label>
		<hr />
	</div>	
	
<?	
	$ordered="";
	for($i=0;$i<sizeof($cartcontents);$i++) {
		$ordered.=$cartcontents[$i]['qty']. " x ".$cartcontents[$i]['name']." ($".$cartcontents[$i]['subtotal'].") || ";
	}
	?>
	<input type='hidden' name='ordered' value='<?=$ordered;?>' />
	
	<button type='submit' class='submit' id='submit_credit'><span>Pay Now</span></button>
	
</fieldset>
				<div class="tandc specs">
								<h4>Terms and Conditions:</h4>
									<p>We don't have any real terms and conditions except:</p>
									<UL>
									<li class="wide"><strong>Items not in stock:</strong>If an item is not in stock, we will call or email you and let you know. 
									Your card won't be charged for any items that we can't send straight away. 
									<li class="wide"><strong>Breakages:</strong> Generally we don't ship anything that can be broken. But if something doesn't arrive in good condition, please call us - 07 5446 8977.
									<li class="wide"><strong>Delays:</strong>
									We try to ship your order in the next business day's mail. Once again, contact us if you think your item has gone missing. 
									<li class="wide"><strong>Cook up a storm:</strong>We hope you enjoy our books and kitchen gadgets - and we hope you put them to good use.
									
									</UL>
									<p>&nbsp;</p>
									
				</div>

</form>
			
			<?
			}
			?>
		
		</div></div> <!-- end inner & wrap -->
		</div>
		<!-- end.content -->
		
		
	<div class="twocol last"></div>	
			
					
					
		<!-- end.content -->
			
	</div>
	<!-- end.row -->	
</div>
<!-- end#.container -->


<? include("includes/footer.inc.php");  ?>
<script >

(function() {
var tandc = $('div.tandc');
tandc.hide();
  $('a.terms').click(function(event){
    event.preventDefault();
    tandc.slideToggle(200);
  }             
);

})();

</script>
<script type="text/javascript" src="jcart/jcart.php"></script>