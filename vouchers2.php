<?php
include("includes/voucher_functions.inc.php");

// Setup and Load the header file

$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Gift Vouchers";
$metaContent = "Gift vouchers for cooking school at Spirit House, Australia's best Thai cooking school";
$sectionsList = "['voucher_form']";
$javascript = array("jquery.validate.min.js", "jquery.form.js", "vouchers.js");
$javascript_vars = array('classprice' => $classprice);

//$hideBigText = true; // This shrinks the big text section
$bigText_main = "Make Someone Happy";
$bigText_sub = "Spirit House Gift Vouchers make the <em>Perfect Gift</em>.";

include("includes/header.inc.php"); 

?>

<div class="container section texture1" id="voucher_form">

<div class="row">




	<div class="twelvecol last info">

				<p class="info">If you're short on time, choose to download your voucher instantly and print them at your convenience. Otherwise we can send your voucher in the next business mail.</p>
               

	</div>
</div>

	
	<div class="row">
		<div class="fourcol specs">
			<h4>Voucher F.A.Q.</h4>
			<ul>
				<li class='wide'><strong>How much does a voucher cost?</strong>Whatever dollar amount you choose. The most common amount is $150 which covers a meal for two at the restaurant or one cooking class.</li>
				<li class='wide'><strong>How soon will I get it?</strong>Immediately, if you choose the <span class="teal">download and print</span> option, or two to three days via snail mail.</li>
				<li class='wide'><strong>Validity:</strong> Spirit House vouchers are valid for twelve months.</li>
				<li class='wide'><strong>Where can I use it?</strong>Your voucher can be used at the restaurant or the cooking school.</li>
				<li class='wide'><strong>What if I lose the voucher?</strong>Simply call the office on (07) 5446 8977 and we'll track the number for you.</li>
				<li class='wide'><strong>Can I book online with vouchers?</strong>You can book cooking classes online &mdash; just type in your voucher number.</li>
				
				</ul>
			<div class='clear'></div>
						
		</div>
		
		<div class="eightcol last content">
			<h4>Purchase your gift vouchers.</h4>
		<div class="wrap">
		<div class="inner" style="position: relative;">
		<img src="resources/bookmark.png" class="bookmark" />

		<div id="voucher_complete" style="display:none;">			
			<h4>Voucher Purchase Successful</h4>
			<p class='thanks'></p>
			<hr />
			<p>If you have any questions about your purchase please feel free to give us a call at the office: 
				(07) 5446 8977 &mdash; we'll be happy to assist you however we can.</p>
			<div class="clear"></div>
	    </div>

	    <div id="voucher_timeout" style="display:none;">			
			<h4>Oops!</h4>
			<p>It looks like we're experiencing some technical difficulties. Your Voucher purchase is probably complete, 
			but please give us a call at the office (07) 5446 8977, to confirm. We apologize for the inconvenience.</p>
			<p><strong>Error:</strong> The script response timed out.</p>
			<div class="clear"></div>
	    </div>
		
<form action="#" method='post' name='theForm' id='theNewForm' class='spiritform'>

<fieldset>
	<legend>Delivery Details</legend>
	<label for="firstname">Your Name</label>
	<input type="text" name="firstname" id="firstname">

	<label for="email">Email</label>
	<input type="text" name="email" id="email" type='email' />
	
	<label for="emailcheck">Confirm Email</label>
	<input type="text" name="emailcheck" id="emailcheck" type='email' />
	<hr />
</fieldset>

<fieldset>	
	<legend>Delivery Mode</legend>
	
	<div class="radios">
		<p>How would you like the vouchers delivered?</p>
		<div class='sub_flat'>
			
			<div class='radio_item'>
			<input type="radio" name="deliverymode" value="email" id="deliverymode_email" checked="checked"/>			
			<label for="deliverymode_email">Download &amp; Print (PDF) &mdash; <em>We'll email them to you, too.</em></label>
			</div>
			
			<div class='radio_item'>
			<input type="radio" name="deliverymode" value="address" id="deliverymode_post" />
			<label for="deliverymode_post">Post the vouchers to my street address</label>	
			</div>
			
		</div>
	</div>
	
	<div id="hideaddress">
		<label for="phone">Phone Number</label>
		<input type="text" name="phone" id="phone" type='tel'>
		
		<label for="address">Address</label>
		<textarea name="address" id="address"></textarea>
		
		<label for="notes">If you have any special requests please enter them here:</label>
		<textarea name='notes' id='notes' /></textarea>
	</div>
	<hr />
	
</fieldset>

<fieldset>
<legend>Purchase Details</legend>

	<label for="qty">I would like </label>
	<select class='qty' name='qty' id='qty'>
      <option value="1" selected="selected" >1 Voucher</option>
		<?php
		$maxvouchers = 6;
		for ($i=2; $i<=$maxvouchers; $i++) {
			echo "<option value=\"$i\" >$i Vouchers</option>";
		}
		?>
	</select>
	
	<label for="amount">at </label>
	<!--<select name='amount' id='amount'>
		<option value="50">$50.00</option>
		<option value="75">$75.00</option>
		<option value="125" selected="selected">$125.00</option>
		<option value="250">$250.00</option>
		<option value="375">$375.00</option>
		<option value="500">$500.00</option>
	</select>-->
	$<input type="text" name='amount' id='amount' />.00 each.
	
	<div id="infoAmount" class="information"><p>The cost of one cooking class is <em>$150.00</em>, just so you know!</p></div>
	<hr />
	
	
	<div id="recipientBlock">
		<label for="fromname">These voucher/s are from:</label>
		<input type="text" name="fromname" id="fromname" placeholder="Eg: Mum and Dad or your name etc.">
	
		<label>To whom are you giving the voucher/s?</label>
		<ol id="recipients">
			<?php
			for ($i=1; $i<=$maxvouchers; $i++) 
			{
				echo "<li class='recipient_wrap recipient$i'>";
				//echo "<label for='recipient$i'>Voucher $i is for </label>";
				echo "<input type='text' name='recipient$i' id='recipient$i' placeholder='Voucher $i &mdash; Name of recipient.' />";
				echo "</li>";
			}
			?>
		</ol>
		<hr />
	</div>
	
</fieldset>

<fieldset>
<legend>Payment Details</legend>
	
	<div id='totalsummary'>
		<p>Total amount payable for 1 seat 
		<strong><big>$<?=$classprice;?></big></strong></p>
	</div>
	
	<hr />
	
	<label for='nameoncard1'>Name on Credit Card</label>
	<input type="text" name='nameoncard1' id='nameoncard1' value='<?=$_POST['nameoncard1'];?>' />
	
	<label for="cardnumber1">Credit Card Number</label>
	<input type="text" name='cardnumber1' id='cardnumber1' />
	
	<label for='expiry_m1' style="float: left; margin-right: 10px; ">Credit Card Expiry</label>
	<?=select_month("expiry_m1", "cc_droplist", "");?> <?=select_year("expiry_y1", "cc_droplist", "");?>
	
	<div class="clear"></div>
	
	<label for="ccv1" style="float: left; line-height: 40px;margin-right: 10px;">Card Security Number (CCV)</label>
	<input type="text" name='ccv1' id='ccv1' size=4 maxlength=4 style="width: 43px; float: left;" />
	<img src="resources/ccv.png" width="46" height="28" class="ccv" alt="Back of Credit card showing CCV" title="The CCV can be found on the back of your Credit Card." />
	
	<!-- Begin eWAY Linking Code -->
	<div class="clear"></div>
<div id="eWAYBlock">
    <div style="text-align:center; float: left;">
        <a href="http://www.eway.com.au/secure-site-seal?i=12&s=7&pid=7acd3e4d-daf1-41d0-af3a-d18d59323c84" title="eWAY Payment Gateway" target="_blank" rel="nofollow">
            <img alt="eWAY Payment Gateway" src="https://www.eway.com.au/developer/payment-code/verified-seal.ashx?img=12&size=7&pid=7acd3e4d-daf1-41d0-af3a-d18d59323c84" />
        </a>
    </div>
</div>
<!-- End eWAY Linking Code -->
	
	<div class="clear"></div>
	<hr />
	
	<div class='tac'>
		<label for='chk_tc2'><input type='checkbox' id='chk_tc2' name='chk_tc2' />
		Yes &mdash; I have read and agreed to the 
			<a href='#nogo' onclick="javascript:tandc_window();" rel="1" class="terms" >terms and conditions</a> of this purchase.
		</label>
		<hr />
	</div>	
	
	<button type='submit' class='submit' id='submit_credit'><span>Pay Now</span></button>
	
</fieldset>
<div class="tandc specs">
							<h4>Terms and Conditions:</h4>
									<p>Vouchers can be used in the restaurant or the cooking school. 
									We do have one condition on vouchers though:</p>
									
									<UL>
									
									
									<li class="wide"><strong>Don't Lose your Voucher</strong>If using a voucher you MUST present your voucher to 
									us on the day of the class or your restaurant reservation- if the voucher has been forgotten or lost, 
									the full amount will be charged to the recepients credit card and will be refunded when the vouchers
									 have been delivered to us.
									
								 
									</UL>
									<p>&nbsp;</p>
									
							</div>
</form>
			
			
	
		
		</div></div> <!-- end inner & wrap -->
		</div>
		<!-- end.content -->
	
	</div>
	<!-- end.row -->
	
</div>
<!-- end#voucher_form.container -->

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
