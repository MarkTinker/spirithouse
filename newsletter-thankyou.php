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
				<a href="#news_deals" class='current'>Great Deals</a>
			</h3>			
		
               <p class="info">As a reward for subscribing to our newsletter, you will see the occasional discounted <a href="school2.php">cooking class</a>  and other offers, as you browse our website. 
               Only subscribers to our newsletter and facebook fans are able to see these deals, but you can also book for other friends and family at your special discount rate.</p>
               
               <h4>Did you miss out on downloading our newsletters when you signed up?</h4>
               <p class="info">Here are the back issues for you to download</p>

				
		
		</div>
		<!-- end.twelvecol -->
		
		<div class="fourcol content">
				<h4>::Latest Issue::</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=latestnews'> <img class="shadow"  src="resources/latestnews.jpg" alt="newsletter-jan-jun12" />
				</a>
		</div>
			<div class="fourcol content">
		
				<h4>Jan-Jun 2012</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=janjun12'> <img class="shadow"  src="resources/newsletter-jan-jun12.jpg" alt="newsletter-jan-jun12" />
				</a>
		
			</div>
		
			<div class="fourcol content last">
			
			<h4>Jul - Dec 2011</h4>
			<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=juldec11'><img class="shadow"  src="resources/newsletter-jul-dec11.jpg" alt="newsletter-jul-dec11" />	</a>
		
			</div>
			<div class="twelvecol last pad20"> <br><br><p class="info"><em>Want more issues? You know you do ... </em></p></div>
			<div class="fourcol content">
				<h4>Jan - Jun 2011</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=janjul11'><img class="shadow" src="resources/newsletter-jan-jun11.jpg" alt="newsletter-jan-jun11" /></a>		
			</div>
		
			<div class="fourcol content">
		
				<h4>Jul - Dec 2010</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=juldec10'> <img class="shadow"  src="resources/newsletter-jul-dec10.jpg" alt="newsletter-jul-dec10" />
				</a>
		
			</div>
			
			<div class="fourcol content last">
				<h4>Jan - Jun 2010</h4>
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=janjun10'>
				<img class="shadow"  src="resources/newsletter-jan-jun10.jpg" alt="newsletter-jan-jun10" /></a>
					
			</div>
		
		
		
		<div class="twelvecol last pad20"></div>
			<div class="fourcol content">
		
				<h4>Jul - Dec 2009</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=juldec09'>
				<img class="shadow" src="resources/newsletter-jul-dec09.jpg" alt="newsletter-jul-dec09.jpg" /></a>
		
			</div>
			<div class="fourcol content"></div>
			<div class="fourcol content last">
			
			
 
    <!--
    			
    			<div class="wrap">
            	<div class="inner" style="position: relative;">
            		<img class="bookmark" src="resources/bookmark.png">
            		<br>
            		<form action="http://spirithouse.createsend.com/t/y/s/agldd/" method="post" id="subForm" class="spiritform">

          			<fieldset>
          			
          			<legend>I Want To Save Trees</legend>
          			<p>Please put me on your email list &#8230;  <p>
					<div>
					<label for="fieldEmail">Email</label><br />
					 <input id="fieldEmail" name="cm-agldd-agldd" type="email" required />
					<label for="fieldmlhktj">Your Id</label><br />
       				 <input id="fieldmlhktj" name="cm-f-mlhktj" type="text" required />
					<button  class="bookingsubmit"  type="submit"><span>Subscribe</span></button>
					</div>
					</fieldset>
					</form>
					
				</div>	
				</div> 	
    
    	
  
    -->
    
    
    
    
    
</form>
			
			
			
			</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</div>
	<!-- end.row -->
</div>
<!-- end#news_intro.container -->







<? include("includes/footer.inc.php");  ?>