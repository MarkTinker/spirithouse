<? 
include("databaseconnect.php");
$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Cooking School";
$metaContent = "Best Thai food Cooking School on the Sunshine Coast. Voted one of Australia's best food destinations";
$sectionsList = "['#school_bookings', '#school_questions']";

$bigText_main = "Cooking School";
$bigText_sub = "Learn tips, tricks from our <em>great chefs.</em>";

include("includes/header.inc.php"); 

include("includes/class_functions.inc.php");

####testing the facebook cookie for discounts etc.#########

if(isset($_COOKIE['likedfacebookcompages/Spirit-House/7325766843'])){
	$isfan=1;
	$fantext = "<span class='teal'>Because you're a local or a facebook fan, we have some great discounts on certain classes which we've marked for you below. Only you can see these discounts but you can book for friends and family.</span.";
	}else{
	$discounttext = "&nbsp;";
	$isfan = 0;
	}


?>

<div class="container section">
	<div class="row">
	
		<div class="sixcol">
		<img src="resources/spirit-school.jpg" alt="spirit house cooking school"/>
		
		</div>
		<div class="sixcol last title">
		
			<p class="info">Whether you're a competent cook or simply want a basic understanding of Asian ingredients and cooking methods, a hands-on Spirit House     
			cooking class is a great way to meet new people, learn new skills and have an enjoyable day out which includes lunch/dinner and wine.</p>
			<div class="sixcol"><img class="shadow"  src="resources/newsletter-jul-dec13.jpg" alt="newsletter-jan-jun-14" /></div>
			<div class="sixcol last title"><p class="info"> <strong>Sign Up for great deals, cool news and more: </strong></p>
				
				<div class="createsend-button" style="height:27px;display:inline-block;" data-listid="y/BE/873/17D/E397E736E2F01549">
</div><script type="text/javascript">(function () { var e = document.createElement('script'); e.type = 'text/javascript'; e.async = true; e.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://btn.createsend1.com/js/sb.min.js?v=3'; e.className = 'createsend-script'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(e, s); })();</script>

</div>
			
			
			
		

		</div>
	</div>
	<!-- end.row -->
</div>	

<div class="container section" id="school_bookings">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a class='current' href="#school_bookings">Book a class</a>
			</h3>
			<p class="info">Find a class that you like and click the <em>book now</em> button &mdash; it's that easy. 
			<? echo $fantext; ?>
			</p>
						
		</div>
	</div>
	<!-- end.row -->
	


	<? 
	
	include("school-calendar.php"); ?>






</div>
<!-- end#school-bookings.container -->



<? include("includes/footer.inc.php");  ?>

