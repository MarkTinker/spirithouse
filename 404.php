<? 

 
$pageTitle = "You found a broken link at Spirit House";
//$sectionsList = "['#news_intro', '#news_letter', '#news_blog', '#news_fb']";
$bigText_main = "WOOOPS! <br>You've found a missing link";
$bigText_sub = " The page you were after, no longer exists ";

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
if (isset($_COOKIE['admin'] ))
{$cookie=". . .";} else {$cookie="-";}


?>
<div class="container section" id="shop_checkout">
	
	<div class="row">
		
		
		<div class="twelvecol last content">
		<h4>This is Embarrasing <? echo $cookie; ?> </h4>
				<p class="info">Chances are that our new site no longer honours some of the links leading in to our old site. Please don't be alarmed. You can find all you 
				favourite Spirit House food experiences by using the links above.</p>
				
				
				<p class="info">As a way of apologising for this hiccup in your browsing experience, we offer you some <span class="teal"> cool free stuff!</span></p>
				
						
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
										
		
		</div>		
						
		<!-- end.content -->
			
	</div>
	<!-- end.row -->	
</div>
<!-- end#.container -->

<? include("includes/footer.inc.php");  ?>