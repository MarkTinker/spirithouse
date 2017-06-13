<? 

include("includes/shipping.inc.php");

include("includes/voucher_functions.inc.php");

include("databaseconnect.php"); 

$pageTitle = "Thanks for shopping at Spirit House";
//$sectionsList = "['#news_intro', '#news_letter', '#news_blog', '#news_fb']";
$bigText_main = "Thanks for your Order";
$bigText_sub = " We're busy wrapping your items now";

include("includes/header.inc.php"); 

//Chooses a random number 
 $num = Rand (1,6); 
 //Based on the random number, gives a quote 
 switch ($num)
 {
 case 1:
 $recipe="Citrus Caramel Pork";
 $recipeid=1;
 break;
 case 2:
 $recipe="Whole Crispy Fish";
 $recipeid=2;
 break;
 case 3:
 $recipe="Chilli Jam Chicken";
 $recipeid=3;
 break;
 case 4:
 $recipe="Tamarind Glazed Chicken";
 $recipeid=4;
 break;
 case 5:
 $recipe="Thai Fried Rice";
 $recipeid=5;
 break;
 case 6:
 $recipe="Spiced Pumpkin Soup";
 $recipeid=6;
 }


?>
<div class="container section" id="shop_checkout">
	
	<div class="row">
		
		
		<div class="twelvecol last content">
				<p class="info">We will be posting your order in the next mail. They say good things come to those who wait, so while you wait for your order to arrive, we've given you some <span class="teal"> cool free stuff!</span></p>
				
						
		</div>
		
		<div class="threecol content">
		<span class="smallheadline">Download our</span><br>
		<h4>Latest Newsletter</h4>
		<a href="http://www.spirithouse.com.au/download.php?filetype=1&amp;filename=latestnews"> <img alt="Latest newsletter" src="resources/latestnews.jpg" class="shadow">
				</a>
				
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>

		
		</div>
		<div class="threecol content">
		<span class="smallheadline">Free Recipe:</span><br>
				<H4><? echo $recipe; ?></H4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe<? echo $recipeid; ?>'> 
				<img class="shadow"  src="resources/freerecipe<? echo $recipeid; ?>.jpg" alt="<? echo $recipe; ?>recipe" /></a>

		
		</div>
		<div class="sixcol content last">
					<!--<span class="smallheadline">Like us for</span><br>
					<h4>Some Great Deals</h4>
					<p>Our Facebook fans get special deals on marked classes. All you have to do is click <em>like</em>:</p>
					<img src="resources/like-this-page.png" alt="like-this-page" />
					-->										
					
		
		</div>		
						
		<!-- end.content -->
			
	</div>
	<!-- end.row -->	
</div>
<!-- end#.container -->

<? include("includes/footer.inc.php");  ?>