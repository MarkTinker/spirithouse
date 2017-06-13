<? 
$pageTitle = "Spirit House: Newsletter, Facebook etc.";
$sectionsList = "['#news_deals']";
$bigText_main = "Thanks for Signing Up";
$bigText_sub = "No Spam &mdash; just good news from <em>Spirit House</em>";

$value = "newsletter";

// send a cookie that expires in 10 years
setcookie("likedfacebookcompages/Spirit-House/7325766843",$value, time()+10*365*24*60*60);

// Include WordPress 
define('WP_USE_THEMES', false);
require('funstuff/wp-load.php');
query_posts('showposts=6');


include("includes/header.inc.php"); 

?>
<div class="container section" id="news_intro">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a href="#news_deals" class='current'>Rewards for you</a>
			</h3>			
		</div>
		<div class="fourcol food">
		
		<img src="images/class-disc.png" alt="class-disc" width="400" height="186" />
		<small>As you journey through our site, we will highlight special deals for you. </small>
		</div>
		<div class="eightcol last">
               <p class="info">As a reward for subscribing to our newsletter, you will see <span class="teal">highlighted</span> discounted <a href="/school">cooking class</a>  and other offers, as you browse our website. Only subscribers to our newsletter and facebook fans are able to see these deals, but you can also book for other friends and family at your special discount rate.</p>
               
              
             
	
		</div>
		<!-- end.eightcol -->
	</div> <!-- end.row -->
	<div class="row">
	<div class="twelvecol last pad20">&nbsp;</div>
		
		<div class="fourcol sidebar">
			<h4>From Facebook</h4> 
        
     
           <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FSpirit-House%2F7325766843&amp;width=292&amp;height=590&amp;colorscheme=light&amp;show_faces=true&amp;force_wall=true&amp;border_color&amp;stream=true&amp;header=true&amp;appId=122965114397233" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:590px;" allowTransparency="true"></iframe>
        
		</div>
		<!-- end.sidebar -->
		
		<div class="eightcol last">
		 <h4>Are you into Facebook?</h4>
		<p class="info">Become a fan of Spirit House via our facebook fan page - click LIKE in the facebook window on the left.</p>
		<!-- <img src="resources/like-this-page.png" alt="like-this-page" />
		<script src="https://s3.amazonaws.com/fansflood/d9802d1a" type="text/javascript"></script> -->
			  

			
				
		</div>
		<!-- end.eightcol -->
		
		
		
		
		
		
		
	</div>
	<!-- end.row -->
</div>
<!-- end#news_intro.container -->







<? include("includes/footer.inc.php");  ?>