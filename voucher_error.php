<?
$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Voucher Payment Error";
$sectionsList = "['#voucher_thanks']";

$bigText_main = "Payment Error.";
$bigText_sub = "There was a problem with your <em>payment</em>";

include("includes/header.inc.php"); 

?>
  <div class="container section" id="voucher_thanks">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				Oh No!
				
			</h3>	

 

      <p class="info">We're sorry... we could not process this transaction. We received the following message back from the processing bank:</p>
      <p class="info"><strong><?=$_GET['error'];?></strong></p>
      <p class="info">If this is a problem you can fix, then please hit the browser's back button and try the transaction again. Otherwise please
      try another card.</p>
      <p class="info">Thanks - <br /><br />The Spirit House team.</p>

		</div>
	</div>
	<!-- end.row -->
</div>
<!-- end#rtours_booking.container --> 

<? include("includes/footer.inc.php");  ?>