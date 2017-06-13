<?
include("databaseconnect.php");
include("includes/class_functions.inc.php");

$pageTitle = "Spirit House: Cooking School &mdash; Cooking School Wait List Confirmed";
$sectionsList = "['#waitlist_next,  #waitlist_same']";

$bigText_main = "We'll Be In Touch";
$bigText_sub = "If someone cancels we'll <em>email you.</em>";




include("includes/header.inc.php"); 

if(isset($_COOKIE['likedfacebookcompages/Spirit-House/7325766843'])){
	$isfan=1;
	$fantext = "<span class='teal'>Because you're a facebook fan, we have some great discounts on certain classes which we've marked for you below. Only you can see these discounts but you can book for friends and family.</span.";
	}else{
	$discounttext = "&nbsp;";
	$isfan = 0;
	}


  $sql="SELECT classes.classid, classname, classdescription, classprice, scheduleid,
       DATE_FORMAT(scheduledate, '%d/%m/%y') as classdate, full, bookings
       FROM classes left join schedule
       on classes.classid = schedule.classid
       where scheduleid=".quote_smart($_GET['id']);
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
  $classname=strip_classname($classname);
  $wait_classname=$classname;
// end the scripts for the waitlist sql


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
 $recipe="Spiced pumpkin Soup";
 $recipeid=6;
 }


?>
<div class="container section" id="waitlist_next">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a class='current' href="#waitlist_next">What Next</a><span class='dot'>&middot;</span>
				<a  href="#waitlist_same">Available Classes
				 </a><span class='dot'>&middot;</span>
			</h3>			
			
		</div>
	</div>
	<!-- end.row -->
	<div class="row">

		<div class="ninecol section">
		<p class="info">Should someone cancel from the <?=$classname; ?> class, you'll receive an email with a link to book. <em>Be quick</em> &mdash; because you probably won't be the 
							only person on the waitlist for this class.</p>
		
		<p class="info">They say <span class="teal">good things come to those who wait</span> which is why we have given you a <a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe<? echo $recipeid; ?>'>free recipe to download</a>. <span class="charfix"> &#10163;</span> </p>
		
		</div>
		<div class="threecol content last">
				<span class="smallheadline">Free Recipe:</span><br>
				<H4><? echo $recipe; ?></H4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe<? echo $recipeid; ?>'> <img class="shadow"  src="resources/freerecipe<? echo $recipeid; ?>.jpg" alt="<? echo $recipe; ?>recipe" />
				</a>
		
		</div>
		
	
	
	</div><!-- end.row -->
	

</div>
<!-- end#waitlist_next.container -->



<div class="container section" id="waitlist_same">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a href="#waitlist_next">What Next</a><span class='dot'>&middot;</span>
				<a  class='current'  href="#waitlist_same">Available Classes
				 </a><span class='dot'>&middot;</span>
			</h3>			
			<p class="info">For your convenience, here's a calendar showing all our available classes only. We've highlighted any available <em> <?=$classname; ?></em> classes for you. </p>
		</div>
	</div>
	<!-- end.row -->
	
		


	<? include("school-calendar-waitlist.php"); ?>






</div>












</div>
<!-- end#waitlist_same.container -->

     
     
     
    
    



<? include("includes/footer.inc.php");  ?> 