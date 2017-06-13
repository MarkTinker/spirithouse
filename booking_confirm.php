<? 
include("databaseconnect.php");
// sql to generate the waitlist and send email
//include("includes/class_functions.inc.php");

$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Cooking School Wait List";
$sectionsList = "['#school_waitlist']";

$bigText_main = "Fingers crossed";
$bigText_sub = "If someone cancels you'll <em>hear from us soon.</em>";



include("includes/header.inc.php"); 

	$scheduleid=quote_smart($_GET['scheduleid']);
	
  $sql="SELECT classes.classid, classname, classdescription, classprice, scheduleid,
       DATE_FORMAT(scheduledate, '%d/%m/%y') as classdate, full, bookings
       FROM classes left join schedule
       on classes.classid = schedule.classid
       where scheduleid=".$scheduleid;
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no>0) {
    $bookings=mysql_result($rs,0,"bookings");
    $classname=mysql_result($rs,0,"classname");
    $classdescription=mysql_result($rs,0,"classdescription");
    $classprice=mysql_result($rs,0,"classprice");
    $classdate=mysql_result($rs,0,"classdate");
    $spotsleft=maxseats-$bookings;
  }
// end the scripts for the waitlist sql

?>
<div class="container section" id="school_questions">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a class='current' href="#school_waitlist">Thanks for Booking  <?=$classname; ?></a>
			</h3>			
				  <p><img src="../images/save_calendar_med.jpg" alt="save_calendar_med" style="float:left;margin:0 5px 0 0;" /> <a href="http://spirithouse.com.au/add_to_ical.php?scheduleid=<?= $scheduleid ?>">Save class 						to your calendar</a></p>

  					<br><br>
			<p>We received your payment and your cooking class booking is confirmed for the following:<br><?echo  $description;?>  
      
      <p> You may wish to print this page as part of your records, we've also sent a confirmation email to your inbox if you have provided us with an email address.

      <p>Some important information:
      <p>Your class starts at <?echo $starttime; ?> so please arrive 15 minutes early so we can start on time. If you have any queries regarding billing, refunds, changing classes etc. simply email us at office@spirithouse.com.au or call during business hours on (07) 5446 8977 .
      <p>All our classes are 'hands-on' so you need to wear comfortable shoes because you will be standing for a few hours while preparing recipes. Lunch, wine, all recipes and equipment are included in your class fee, so the only thing you need to bring is yourself (aprons are also provided).
      <p>Class duration, including lunch or dinner, is about 4.5 hours - handy to know if you're organising a pick-up.
      <p>We look forward to seeing you soon and to help you get here...

				
		
		</div>
	</div>
	<!-- end.row -->
	
      
	
	<div class="row">
		
		<div class="threecol sidebar">
		
		</div>
		<!-- end.sidebar -->
		
		<div class="ninecol last content">
			<h4 class="title">Directions:</h4>
				
			       <h1>Directions</h1>  
                 <img src="/images/map.gif" border="0" width="343" height="410" alt="map.gif (11,313 bytes)">   
                
                    <p>The Spirit House is actually quite easy to find - despite council not allowing us to have any signs - simply take the Yandina-Coolum exit on the Bruce Highway and head west.</p>
                    <p>This road will change names to Coulson Rd. which lasts for about 100m and then turns right and changes its name to School Rd. Drive to the end of School Rd. to the T-intersection. Turn right onto Ninderry Rd and we're about 100m in the right hand side.
                    <p>If you get lost, simply call the restaurant for directions.</p>
                    
                    
                    
<h2>Google Map Directions</h2>
 <p>Enter your postcode below and we'll take you to google maps where you not only get a map to print out but also street by street instructions to the Spirit House</p>
                               
                            
                             <?
                          //Google Maps directions opening in new window
                          include ("google-directions.php");

                    		?>
                    		<p>Or you can find us on the google street map below:</p>
                     
                     <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com.au/maps?f=q&amp;hl=en&amp;geocode=&amp;q=Spirit+House&amp;sll=-26.552639,152.966981&amp;sspn=0.027678,0.05476&amp;ie=UTF8&amp;cid=-26550700,152958891,7601547834026805340&amp;s=AARTsJorjAgndrDHLQxDqhHIUAMVB3RDUg&amp;ll=-26.548532,152.958956&amp;spn=0.026873,0.036478&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com.au/maps?f=q&amp;hl=en&amp;geocode=&amp;q=Spirit+House&amp;sll=-26.552639,152.966981&amp;sspn=0.027678,0.05476&amp;ie=UTF8&amp;cid=-26550700,152958891,7601547834026805340&amp;ll=-26.548532,152.958956&amp;spn=0.026873,0.036478&amp;z=14&amp;iwloc=A&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small></p>	
				
				
				
				
				
				
				
				
				
		</div>
		<!-- end.content -->	
	</div>
	<!-- end.row -->	
</div>
<!-- end#school-questions.container -->



<? include("includes/footer.inc.php");  ?>