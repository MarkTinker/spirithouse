<?

// Fetch the Class Information

include("databaseconnect.php");
include("includes/class_functions.inc.php");

$sql = "SELECT classes.classid, classname, classdescription, classprice, classseats, scheduleid, full, bookings, starttime, discountclassprice, discount_price, discount, scheduleseats,
		DATE_FORMAT(scheduledate, '%a') as classday,
		DATE_FORMAT(scheduledate, '%d/%m/%y') as classdate, 
		DATE_FORMAT(scheduledate, '%W %D %M %Y') as classemaildate, 
		DATE_FORMAT(scheduledate, '%Y-%m-%d') as classfulldate
		FROM classes LEFT JOIN schedule
		ON classes.classid = schedule.classid
		WHERE scheduleid=" . quote_smart($_GET['scheduleid']);

$result = mysql_query($sql);
$num_rows = mysql_num_rows($result);
if ($num_rows > 0) 
{
	$bookings = 		mysql_result($result,0,"bookings");
	$classname =		mysql_result($result,0,"classname");
	$classdescription =	mysql_result($result,0,"classdescription");
	$classprice =		mysql_result($result,0,"classprice");
	$classseats = 		mysql_result($result,0,"classseats");
	$scheduleseats =	mysql_result($result,0,"scheduleseats");
	$classdate =		mysql_result($result,0,"classdate");
	$classemaildate =	mysql_result($result,0,"classemaildate");
	$classfulldate = 	mysql_result($result,0,"classfulldate");
	$starttime = 		mysql_result($result,0,"starttime");	
	$classday = 		mysql_result($result,0,"classday");
	$discount = 		mysql_result($result,0,"discount");
    $discountclassprice= mysql_result($result,0,"discountclassprice");
    $discount_price = 	mysql_result($result,0,"discount_price");
    
    
     # checking to see if class seats is being over-ridden by the scheduled amount of seats
  	 # we can override the default classseats in the schedule now - this checks to see
  	 # if a class has more or less seats than default for today 
  	if($scheduleseats>0){
  	$maxseats =		$scheduleseats;
  		}else{
  	$maxseats = 	$classseats;
  		}
	
	$spotsleft = 		$maxseats - $bookings;	
	
	$classname=strip_classname($classname);	
	$printdescription = split_classdescription($classdescription);
	
	$description= $printdescription[0];
	$recipes= $printdescription[1];
	$printrecipes= str_replace("*", "<li class='wide'>", $recipes); // replace asterisk with LI
	
	//new code from murray for dealing with discounts
	/*if($discount==1 && isset($_GET['l']) && $_GET['l']==1) {
	    if(!is_numeric($discountclassprice)) $discountclassprice=0;
	    if(!is_numeric($discount_price)) $discount_price=0;
	    
	    if($discountclassprice>0 && $discountclassprice>$discount_price) $classprice=$discountclassprice;
	    if($discount_price>0 && $discount_price>$discountclassprice) $classprice=$discount_price;    
	   }
	*/
	if ($discount_price > 1){
		
		$classprice=$discount_price;
		
	}
	
	
}
else
{
	// TODO: Invalid schedule ID. Show error page.
}

// Setup and Load the header file

$pageTitle = "Spirit House: Class Booking Form for $classname";
$sectionsList = "['#booking']";
$javascript = array("jquery.validate.min.js", "jquery.form.js", "booking.js");
$javascript_vars = array('classprice' => $classprice);

$hideBigText = true; // This shrinks the big text section
$bigText_main = "Book it Dano!";
$bigText_sub = "Just a few details and <em> you're done.</em>";

include("includes/header.inc.php"); 

// Promo Setup
  
$marketpromo='';
if ($classday == 'Tue')
{
	$marketpromo = <<<MP
	
				<p><strong>Eumundi Market Getaway Packages</strong></p>
				<p>Because your class is on a Tuesday, why not stay Tuesday night at Gridley Homestead or Yandina Station 
				and visit the famous Eumundi Markets on Wednesday! Just tell them Spirit House sent you when you book
				and they will give you a very good deal. A great idea for a mid-week getaway.</p>
MP;
}
if ($classday=='Thu')
{
	$marketpromo = <<<MP
	
				<p><strong>Eumundi Market Getaway Packages</strong></p>
				<p>Because your class is on a Thursday, why not visit the famous Eumundi Markets on Wendesday, say
				Wednesday night at Gridley Homestead or Yandina Station and attend your class with us on Thursday. 
				Just tell them Spirit House sent you when you book and they will give you a very good deal. A great 
				idea for a mid-week getaway.</p>			
MP;
}

// Email Setup

$emaildescription = <<<EML

	<table style='margin:5px; border-top:1px solid #ccc;border-bottom:1px solid #ccc;' cellspacing=5>
	<tr valign='top'><td width='15%'>Date:</td><td><strong>$classemaildate</strong></td></tr>
	<tr valign='top'><td>Class:</td><td><strong>$classname</strong></td></tr>
	<tr valign='top'><td>&nbsp;</td><td>$classdescription</td></tr>
	<tr valign='top'><td>Start Time:</td><td><strong>$starttime</strong></td></tr>
	</table>
	
EML;

$emaildescription = htmlspecialchars($emaildescription);

?>

<div class="container section" id="booking">

	<div class="row">
		
			<div id="warning" class="twelvecol last info">
			 <h4><span class="red">Just A Sec . . .</span></h4>
				<p class="info"><span class="red">You must have javascript enabled in your browser to make a booking. Please enable javascript and refresh this page or call the office on 07 5446 8977 so we can make the booking for you.</span></p><br><br>			
		</div>

<script>
document.getElementById('warning').style.display = "none";

</script>		
		<div class="twelvecol last title">
			<h3 class='nav'>
				Booking for <span class="teal">&#8220;<?=$classname;?>&#8221;</span> on <?=$classdate;?>
			</h3>			
		</div>
	</div>
	<!-- end.row -->
	
	
	<div class="row bookings">
		
		<div class="fourcol sidebar specs">
			<h4><?=$classname;?></h4>
			<ul>
				<li><strong>DATE:</strong><?=$classdate;?></li>
				<li><strong>START:</strong><?=$starttime;?></li>
				<li class='wide'><strong>Description:</strong> <?=$description;?>.</li>
				<li class='wide'><strong>Recipes:</strong> <?=$printrecipes;?>.</li>
				
				
				</ul>
			<div class='clear'></div>
		
		
		
			<!-- <div class='class_description'>
			<h4>Class Information</h4>
    		<table cellspacing=0>
      			<tr><td class="header">Name</td><td><strong><?=$classname;?></strong></td></tr>
      			<tr><td class="header">Price</td><td><strong>$<?=$classprice;?> per person</strong></td></tr>
      			<tr><td class="header">Date</td><td><strong><?=$classdate;?> at <?=$starttime;?></strong></td></tr>
      			<tr><td colspan="2" class="desc"><p><?=$classdescription;?></p></td></tr>
      			 <tr valign='top'><td>Promo Check:</td><td><?=$marketpromo;?></td></tr> 
    		</table>
			</div> -->
			
			
			<p><a href="school2.php">Go Back to Classes</a></p>
		</div>
		<!-- end fourcol -->		
		
		<div class="sixcol content" style="margin-top: 20px">
					
			<div class="wrap">
			
			<div class="inner" style="position: relative;">
			
			<?
		    if ($spotsleft > 0) {
			?>
					
			<img src="resources/bookmark.png" class="bookmark" />
			<div id="booking_complete" style="display:none;">			
				<h4>Booking Complete</h4>
				<p class='thanks'></p>
				<hr />
				<p>Print this page out for your records if you like, or you can click the following link to &mdash;</p>
				<p><img src="../images/save_calendar_med.jpg" alt="save_calendar_med" style="float:left;margin:0 5px 0 0;" /> 
				<a href="add_to_ical.php?scheduleid=<?=$_GET['scheduleid'];?>">Save this booking to your calendar</a></p>
				<p>If you have any questions please feel free to give us a call at the office: (07) 5446 8977 &mdash; we'll be happy to assist you however we can. See you soon!</p>
				
				<p><a href="school2.php">Back to Classes</a></p>
				<div class="clear"></div>
		    </div>
		    
		     <div id="booking_timeout" style="display:none;">			
				<h4>Oops!</h4>
				<p>It looks like we're experiencing some technical difficulties. To make sure your booking is complete please give us a call at the office (07) 5446 8977. We apologize for the inconvenience.</p>
				<p><strong>Error:</strong> The script response timed out.</p>
				<div class="clear"></div>
		    </div>
		    <form method='post' name='theForm' id='theForm' class='spiritform'>	
		    	<input type='hidden' name='scheduleid' value='<?=$_GET['scheduleid'];?>' />
			    <input type='hidden' name='spotsbooked' value="<?=$bookings;?>" />
			    <input type='hidden' name='emaildescription' value="<?=$emaildescription;?>" />
			    <input type='hidden' name='starttime' value='<?=$starttime;?>' />
			    <input type='hidden' name='classdate' value='<?=$classdate;?>' />
			    <input type='hidden' name='classname' value='<?=$classname;?>' />
			    <input type='hidden' name='maxseats' value='<?=$maxseats;?>' />
				<input type='hidden' name='classprice' value='<?=$classprice;?>' />
				<input type='hidden' name='classfulldate' value='<?=$classfulldate;?>' />
				<input type='hidden' name='vouchers_to_use' value='' />	
				<input type='hidden' name='total_voucher_amount' value='0' />		        
				<input type='hidden' name='pay_balance' value='false' />
				
				<fieldset class="personal_information">
				   	<legend>Personal Information</legend>
			
			        <label for="firstname">First Name</label>
			        <input type="text" id='firstname' name='firstname' class="required" />
			        
			       	<label for="lastname">Last Name</label>
			       	<input type="text" id='lastname' name='lastname' class="required" />
			       	
			       	<label for="phone">Phone Number</label>
			       	<input type="text" id='phone' name='phone' class="required phone" />
			       	
			       	<label for="email">Email</label>
			       	<input type="email" id='email' name='email' class="required email" />
			
			       	<label for="seats" class="seats">No. seats required</label>
					<select id="seats" name="seats" style="width:50px;">        	
						<?php
						for($i = 0; $i < $spotsleft; $i++) 
						{
							echo "<option ";
							if ($i == 0) echo "selected";
							echo " value=".($i+1).">".($i+1)."</option>";
				       	} 				       	
				       	?>
					</select>
					
					<div class="clear"></div>
					
					<label for="nametags">Names of those attending the class:*</label>
			       	<textarea rows=3 id='nametags' name='nametags' placeholder="Example: John, Neil, Terry"  class="required" /></textarea>
					<p><small>*Names will be used to make nametags.</small></p>					
					<hr />
				</fieldset>	
				
				<fieldset class="payment_details">
					<legend>Payment Details</legend>
					<div id='totalsummary'>
						<p>Total amount payable for 1 seat 
						<strong><big>$<?=$classprice;?></big></strong></p>
					</div>
				
					<div class="radios">
						<p>Will you be using a gift voucher?</p>
						<div class='sub'>
							<input type="radio" name="usevoucher" id="usevoucher_yes" value="yes" /> <label for="usevoucher_yes">Yes, please!</label>
							<input type="radio" name="usevoucher" id="usevoucher_no" value="no" checked="checked" /> <label for="usevoucher_no">No, thank you.</label>
						</div>
					</div>
					<div class="clear"></div>
					<hr />
				</fieldset>	
				
				<div id='paymenttype_voucher'>
					<fieldset>
						<legend>Gift Voucher</legend>
						
						<label for="vouchers">Enter your gift voucher number(s) here:*<sup>&dagger;</sup></label>
						<textarea id="vouchers" name='vouchers' rows='3' cols='30' ></textarea>
						
						<p><small>* Don't worry if your voucher doesn't cover the full amount, we will fix that in the next step.</small></p>
						<p><small>&dagger; Please separate multiple vouchers with spaces or commas.</small></p>
						<hr />
						
						<div class='tac'>
							<label for='chk_tc1'><input type='checkbox' id='chk_tc1' name='chk_tc1' />
							Yes &mdash; I have read and agreed to the 
							
								<a href="http://www.spirithouse.com.au/newwindow/tandc.htm" rel="1" class="terms" >terms and conditions</a> of this purchase
								<!-- <a href='#nogo' onclick="javascript:tandc_window();">terms and conditions</a> of this purchase. -->
							</label>
							<hr />
						</div>						
						
						<button type='submit' class='submit-disable' id='submit_voucher'><span>Check Voucher(s)</span></button>
							<div class="clear"></div>
					</fieldset>
					
					<div class="tandc specs">
							<h4>Terms and Conditions:</h4>
									<p>While our classes are fun, friendly and laid-back, we do have a few conditions that you should be aware of:
									<UL>
									<li class="wide"><strong>Cancellations &amp; No-shows</strong> Classes are non refundable unless 5 days notice is given. If you need to cancel or change
									a class, please call our friendly office on (07) 5446 8977.
									<li class="wide"><strong>Suitable Footwear</strong> 
									For safety reasons, please bring covered shoes to wear during class. This will protect you from any spills or dropped knives.
									<li class="wide"><strong>Allergies etc.</strong>
									Classes DO NOT cater for vegetarians or those with food allergies unless stated in the class description. All dishes may contain traces of peanuts. Because
									the focus is on a 'shared food experience', the class can not cook one-off special dishes. 
									<li class="wide"><strong>Vouchers</strong>If using a voucher you MUST present your voucher to us on the day of  the class - if the voucher has been forgotten or lost, 
									the full amount will be charged to the recepients credit card and will be refunded when the vouchers have been delivered to us.
									<li class="wide"><strong>Recipe Changes</strong>A recipe might be different to the description - this is rare but sometimes an ingredient might be unavailable on the day. 
									</UL>
									<p>&nbsp;</p>
									
							</div>

				</div> 
				<!-- ends the paymenttype_voucher div -->
				
				<div id='paymenttype_creditcard'>
			    <fieldset>
					<legend>Credit Card</legend>
			
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
					<br>
									
					<div class="clear"></div>
					<hr />
					
					<div class='tac'>
						<label for='chk_tc2'><input type='checkbox' id='chk_tc2' name='chk_tc2' />
						Yes &mdash; I have read and agreed to the 
							<a href="http://www.spirithouse.com.au/newwindow/tandc.htm" rel="1" class="terms" >terms and conditions</a> of this purchase
							<!-- <a href='#nogo' onclick="javascript:tandc_window();">terms and conditions</a> of this purchase. --></label>
							
						
							
							
						<hr />
					</div>	
					
					<button type='submit' class='submit-disable' id='submit_credit'><span>Pay Now</span></button>
					<!-- <button type="submit" class="submit-disable" id="submit_credit" onClick="ga('send', 'event', 'Button', 'click', 'Book Class', 150);"><span>Pay Now</span></button> -->
					<div class="clear"></div>
					<hr>
				
					<!-- Begin eWAY Linking Code -->
<div id="eWAYBlock">
    <div style="text-align:center;">
        <a href="http://www.eway.com.au/secure-site-seal?i=12&s=7&pid=7acd3e4d-daf1-41d0-af3a-d18d59323c84" title="eWAY Payment Gateway" target="_blank" rel="nofollow">
            <img alt="eWAY Payment Gateway" src="https://www.eway.com.au/developer/payment-code/verified-seal.ashx?img=12&size=7&pid=7acd3e4d-daf1-41d0-af3a-d18d59323c84" />
        </a>
    </div>
</div>
<!-- End eWAY Linking Code -->
					
			    </fieldset>
			    
			    		<div class="tandc specs">
							<h4>Terms and Conditions:</h4>
									<p>While our classes are fun, friendly and laid-back, we do have a few conditions that you should be aware of:
									<UL>
									<li class="wide"><strong>Cancellations & No-shows</strong> Classes are non refundable unless 5 days notice is given. If you need to cancel or change a class, please call our friendly office on (07) 5446 8977.
									<li class="wide"><strong>Suitable Footwear</strong> 
									For safety reasons, please bring covered shoes to wear during class. This will protect you from any spills or dropped knives.

									<li class="wide"><strong>Allergies etc.</strong>
									Classes DO NOT cater for vegetarians or those with food allergies unless stated in the class description. All dishes may contain traces of peanuts. Because
									the focus is on a 'shared food experience', the class can not cook one-off special dishes. 
									<li class="wide"><strong>Vouchers</strong>If using a voucher you MUST present your voucher to us on the day of  the class - if the voucher has been forgotten or lost, 
									the full amount will be charged to the recepients credit card and will be refunded when the vouchers have been delivered to us.
									<li class="wide"><strong>Recipe Changes</strong>A recipe might be different to the description - this is rare but sometimes an ingredient might be unavailable on the day. 
									</UL>
									<p>&nbsp;</p>
									
							</div>
			    </div>
				<!-- ends the paymenttype_creditcard div -->
		
			</form>  
  
			<? } else { // No spots left ?>
				<h4>Terribly Sorry</h4>
				<? if($_GET['from']=="waitlist") { ?>
					<p>Ah nuts! You weren't fast enough!</p>
					<p>It looks like you just missed out on the last few spots that were free.</p>
					<p>If any more places open up, we will notify you again. If you'd like off the list, click <a href='waitlist_remove.php?id=<?=$_GET['id'];?>&rand=<?=$_GET['rand'];?>'>here</a>.</p>
				<? } else { ?>
					<p>Sorry - there are no spots left in this class at the moment. Would you like to 
					<a href='http://www.spirithouse.com.au/school-waitlist.php?scheduleid=<?=$_GET['scheduleid'];?>'>join the waitlist?</a></p>
					<button><span><a href="school2.php">Back to Calendar</a></span></button>
					<div class="clear"></div>
				<? } ?>	
						
			<? } ?>

		</div></div>
		<!-- end wrap and inner-->
		
		</div>
		<!-- end.sixcol booking form -->
		
		<div class="twocol seatsleft sidebar last">
		
      		<div id="seatsleft">
      			<h4>Seats left</h4>
      			<div class='seatsleft_bottom'><?=$spotsleft;?></div>
				 
			</div>
			
		</div>
		<!-- end twocol -->	
	
	</div>
	<!-- end.row -->
</div>
<!-- end.container -->      

<?php include("includes/footer.inc.php"); ?>
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
