<?php
/*  ===============================================================
	SpiritHouse - Page Header
	--------------------------------------------------------------- 

	This is the main header include for all primary pages.

	Certain variables must be defined before this is included, in 
	order to populate the page title and to set various menus,
	text items, and other page congifuration items.

	Example:

	$pageTitle = "Spirit House: Restaurant &amp; Restaurant";
	$sectionsList = "['#restaurant_restaurant', '#restaurant_menu', '#restaurant_functions', '#restaurant_awards']";
	$hideBigText = false; // or not defined at all
	$bigText_main = "The Restaurant";
	$bigText_sub = "Spirit House has the food to tingle every taste bud. <em>Hungry yet?</em>";
	$fb_meta = "the facebook meta tags needed for the page"
	
	$javascript = array("myjs.js", "myjs2.js"); // JavaScript files to include (must be in the 'js' folder)
	$javascript_vars = array('myvar'=>'value'); // variables that you want to pass to included JS, will be converted to JSON and accessible in JS via javascript_vars[keyname] -- used on the booking.php page.
*/

define("SCRIPT_ROOT", "https://www.spirithouse.com.au/js/");

function classIfCurrentPage($page)
{
	if (strrpos($_SERVER["SCRIPT_NAME"], $page))
	{
		echo "current";
	}
}



?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<meta charset="utf-8" />
	<title><? echo $pageTitle; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<? echo $fb_meta; ?>
	<meta name="description" content="<? echo $metaContent; ?>">

	<LINK REL="icon" HREF="favicon.ico" TYPE="image/x-icon">
	<LINK REL="shortcut icon" HREF="favicon.ico" TYPE="image/x-icon"> 
	<!-- Fonts -->
	<link href="LeagueGothic/font.css" rel="stylesheet" type="text/css">
	
	<!-- Sweet Stylin' -->
	<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
	<!--[if lte IE 9]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" /><![endif]-->
	<link rel="stylesheet" href="css/1140.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/acland.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
	
	<!-- JavaScript -->
	<?php
	// These are variables defined before the header include
	// This way you can pass information to included JS files
	if (isset($javascript_vars))
	{
		echo "<script>";
		echo "\n\t\tvar javascript_vars = " . json_encode($javascript_vars);
		echo "\n\t</script>";
	}
	?>

	<script>
	function process_postage(thePostcode,theWeight) {
		var scriptUrl = "ajax/getPostage.php?theWeight="+theWeight+"&thePostcode="+thePostcode;
		$.get(scriptUrl, function(data) {
		  $('#shippingcost').html(data);		
		});
	}		
	</script>
	
		
	
	<!-- <script src="https://www.spirithouse.com.au/js/jquery.js"></script>	-->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
	
	
	<!-- <script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script> -->
	
	
				
	<script type="text/javascript" src="https://www.spirithouse.com.au/js/jquery.color.js" async></script>
	<script type="text/javascript" src="https://www.spirithouse.com.au/js/spirithouse.js" async></script>
	<script type="text/javascript" src="https://www.spirithouse.com.au/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="https://www.spirithouse.com.au/js/css3-mediaqueries.js"></script>

	
	<!--MARK -  this snippet is ONLY used on the shop page for checking the form on checkout - we should dump it elsewhere.
		 -->
	<script type="text/javascript">
    $(document).ready(function() {
      $("#form1").validate({
        rules: {
          firstname: "required",// simple rule, converted to {required:true}          
          address1: "required",
          address2: "required",
          phone: "required",
          email: {// compound rule
          	required: true,
          	email: true
          },
          nameoncard1: "required",
          cardnumber1: {// compound rule
          	required: true,
          	creditcard: true
          },
          ccv1: "required",
          chk_tc2: {
          	required:true
          }
        }
      });
    });
  </script>
  
	
	<?php
	if (isset($javascript))
	{
		foreach ($javascript as $script_src) 
		{
		    echo "<script src='" . SCRIPT_ROOT . $script_src . "'></script>\n";
		}
	}
	?>
	
	<!-- Start Up -->
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function()
		{
			// Enable smooth scrolling
			setupScroll();
			
			// Initialize Navigation/Tabs/Menus
			var sections = <? echo $sectionsList; ?>;
			initNavigation(sections);
			
			// Setup our fixed navigation
			$("#fixedtop .container").fixedNavigation( sections );
			
			// Class Show/Hide Links
			$(".classblock").each(classInfoShowHide);
												
		});
	</script>
	
<?php include_once("analyticstracking.php") ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '576866809154945');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=576866809154945&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
	
</head>
<body>

<div id="fixedtop">
	<div class="container">
		<div class="row">
			<div class="threecol" id="logo">
				<h1><a href="index.php">Spirit House</a></h1>
			</div>
			<div class="ninecol last" id="nav">
				<a href="http://www.spirithouse.com.au/restaurant" class=" <?php classIfCurrentPage('restaurant.php'); ?>">Restaurant</a> 
				<a href="http://www.spirithouse.com.au/school2.php" class="<?php classIfCurrentPage('school2.php'); ?>">Cooking School</a>
				<a href="https://www.spirithouse.com.au/shop" class="<?php classIfCurrentPage('shop2.php'); ?>">Shop</a>
				<a href="https://www.spirithouse.com.au/vouchers" class="<?php classIfCurrentPage('vouchers2.php'); ?>">Vouchers</a>
				<a href="http://www.spirithouse.com.au/foodtours" class="<?php classIfCurrentPage('foodtours.php'); ?>">Food Tours</a>
				<a href="http://www.spirithouse.com.au/mapsandinfo" class="l <?php classIfCurrentPage('mapsandinfo.php'); ?>">Maps &amp; Info</a>
			</div>
		</div>
	</div>
</div>

<div class="container" id="headertop">
	<div class="row">
		<div class="twelvecol last" id="logocontainer">
			<div id="logo"><h1><a href="index.php">Spirit House</a></h1></div>
		</div>
	</div>
	<!-- end.row -->
</div>
<!-- end#headertop.container -->

<div id="headersub" 
	<?php 
	if (isset($hideBigText) && $hideBigText == true) 
	{ 
		echo "class='container hideBigText'"; 
	}
	else
	{
		echo "class='container'";
	} 
	?>>	
	<div class="row">
		<div class="twelvecol last">
			<div id="navigation">
			<ul id="mainnav">
				<li><a href="http://www.spirithouse.com.au/restaurant" class="<?php classIfCurrentPage('restaurant.php'); ?>">Restaurant</a></li> 
				<li><a href="http://www.spirithouse.com.au/school2.php" class="<?php classIfCurrentPage('school2.php'); ?>">Cooking School</a></li>
				<li><a href="https://www.spirithouse.com.au/shop" class="<?php classIfCurrentPage('shop2.php'); ?>">Shop</a></li>
				<li><a href="https://www.spirithouse.com.au/vouchers" class="<?php classIfCurrentPage('vouchers2.php'); ?>">Vouchers</a></li>
				<li><a href="http://www.spirithouse.com.au/foodtours" class="<?php classIfCurrentPage('foodtours.php'); ?>">Food Tours</a></li>
				<li><a href="http://www.spirithouse.com.au/mapsandinfo" class="l <?php classIfCurrentPage('mapsandinfo.php'); ?>">Maps &amp; Info</a></li>
			</ul>
			</div>
		</div>
	</div>
	<!-- end.row -->
	
	
	<div class="row">
		<div class="twelvecol last" id="bigtext">
			<p class='main'><? echo $bigText_main; ?></p>
			<p class='sub'><? echo $bigText_sub; ?></p>
		</div>
	</div>
	<!-- end.row -->
	
</div>
<!-- end.#headersub.container -->