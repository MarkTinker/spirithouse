<? 

$pageTitle = "Spirit House: Restaurant &amp; Cooking School";
$sectionsList = "['#restaurant', '#cookingschool', '#vouchers', '#shops', '#foodtours', '#mapsandinfo']";

$bigText_main = "Prepare to be amazed";
$bigText_sub = "Spirit House &mdash; one of <em>Australia's Best Food Experiences</em> &mdash; come find out why";

include("includes/header.inc.php"); 

?>

			<!--<div style="position: absolute; right: 30%; top: 0px;  z-index: 10; border: none;">
			
				<a href="http://www.spirithouse.com.au/locals"><img src="images/yourid-locals.png" alt="yourid-locals" /></a> 
			</div>-->

<div class="container section" id="restaurant">


	<div class="row videocontainer">
	
	<!--############  RESTAURANT ########-->
		<div class="fivecol frontpage">
			<h3><span>Restaurant</span></h3>
					
				
				
				
					<div class="video">
					<iframe src="http://player.vimeo.com/video/42187291?title=0&amp;byline=0&amp;portrait=0" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>			
											
					</div>
					<div class="buttons">
				<a href="restaurant.php" class='navbutton'>Visit the Restaurant Page</a>
				
			</div>
		
			
			<p class="info">Set in lush tropical gardens nestled around a tranquil pond, bubbling waterfalls, tinkling wind chimes, the sounds of nature &mdash; the Spirit House is a delight for 
			all senses. <em>Award winning</em> contemporary Asian food, friendly staff and enthusiastic chefs have made the Spirit House a destination for lovers of all things Asian.</p>
	
			<p class="info"><a href="restaurant.php">Visit the restaurant page</a> for information on weddings, menus and wine lists or 
			<a href="http://spirithouse.com.au/restaurant.php#restaurant_bookings">book a table online</a> now.</p>	
			
			<a href="https://plus.google.com/106244637589196337299" rel="publisher" style="display:none;">Google+</a>		

		</div> <!-- end fivecol -->
		
		<div class="twocol"></div>

<!--############  COOKING SCHOOL ########-->

		<div class="fivecol frontpage last">
			<h3><span>Cooking School</span></h3>
			
			
					<div class="video">
						<iframe src="https://player.vimeo.com/video/128646491" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				
						
					</div>
					
			<div class="buttons">
			<a class="navbutton" href="school2.php">Click to View &amp; Book Classes</a>
			</div>
		
			<p class="info">Learn the chef's secrets as you slice, dice and cook up a storm along with new-found friends. With daily classes as well as Friday and Saturday nights, we offer a 
			huge range of recipes for you to choose from.</p>
			 <p class="info">Master chef's techniques to make perfect stir-fries, balanced curries and dinner party dishes that will wow your friends.<a href="school2.php">Visit the cooking school page</a> to view classes and make bookings.</p>

		
		
			
		</div> <!-- end fivecol last bit -->		
	</div> <!-- end row -->	
</div> <!-- end container -->


<!-- ############ break between next section ########-->
<div id="headersub" class="container">


	<div class="row">
		<div id="bigtext" class="twelvecol last">
			<p class="main">Gift Vouchers, Food Tours &hellip; </p>
			<p class="sub">	Scroll down for	<em>great food adventures</em>
			</p>
		</div>
	</div>


</div>
<!-- END CONTAINER -->




<!--############  Second Listing of things we do ########-->
<div class="container section" id="shop">
	<div class="row videocontainer">
	
	
	<!--############  VOUCHERS ########-->
		<div class="fivecol frontpage">
			<h3><span>Gift Vouchers</span></h3>
			<div class="video">
				<iframe src="http://player.vimeo.com/video/42524896?title=0&amp;byline=0&amp;portrait=0" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>			</div>

			
			<div class="buttons">
				<a href="https://spirithouse.com.au/vouchers" class='navbutton'>Click to buy vouchers NOW</a>
				
			</div>
		
			
			<p class="info">You're just a click away from making someone very happy. Spirit House Gift Vouchers are the perfect gift for any food lover. They can be purchased for any amount.</p>
			
			<P class="info">Here's the best part &mdash; you can <a href="https://spirithouse.com.au/vouchers">order your vouchers online</a> and have them emailed to you instantly. Or, 
			<span class="teal">if you believe good things come 	to those who wait</span>, the voucher will be mailed to you. Either way, a voucher is bound to make the food lover in your life very happy.</P>
		

		</div> <!-- end fivecol -->
		
		<div class="twocol">&nbsp;</div>
		
			<!--############  TOURS ########-->
		<div class="fivecol frontpage  last">
		<h3><span>Food Tours</span></h3>
			
			<div class="video">
				<iframe src="http://player.vimeo.com/video/42960055?title=0&amp;byline=0&amp;portrait=0" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			</div>
			
			<div class="buttons">
				<a class="navbutton" href="foodtours.php">Itineraries and bookings</a>
			</div>
		
			<p class="info">As shown in the video above, tag-along with Spirit House chefs for four days to Thailand as we take you off the beaten path and uncover a world of amazing sights, sounds and tastes.</p>
			<p class="info">We will take you back 700 years exploring ruins of an ancient city, learning from market chefs, exploring river life, as well as visiting stunning temples and palaces.</p>
			
			<p class="info">Limited to just 8 people, <a href="foodtours.php">visit our tours page</a> for itineraries, dates and booking forms. </p>
		</div>

				

		</div> <!-- end row -->
	</div> <!-- end container -->



<script src="js/fitvids.js"></script>
<script>
  $(document).ready(function(){
    // Target your .container, .wrapper, .post, etc.
    $(".videocontainer").fitVids();
  });
  

  
</script>


<?php include("includes/footer.inc.php"); ?>