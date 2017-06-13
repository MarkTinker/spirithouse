<? 
include("databaseconnect.php");
include("includes/class_functions.inc.php");
// sql to generate the waitlist and send email

if(sizeof($_POST)>0) {
  $rand=date("His");
  $sql="insert into `waitlist` (`scheduleid`, `rand`, `email`, `phone`)
    VALUES (".quote_smart($_POST['scheduleid']).", ".quote_smart($rand).", ".quote_smart($_POST['email']).", '".stripslashes($_POST['phone'])."')";
  mysql_query($sql);
  header("location: school_waitlist_confirm.php?id=".quote_smart($_POST['scheduleid'])."");
  exit();

}
$pageTitle = "Spirit House: Restaurant &amp; Cooking School &mdash; Cooking School Wait List";
$sectionsList = "['#school_waitlist']";

$hideBigText = true;
$bigText_main = "Fingers crossed";
$bigText_sub = "If someone cancels you'll <em>hear from us soon.</em>";



include("includes/header.inc.php"); 


  $sql="SELECT classes.classid, classname, classdescription, classprice, scheduleid,
       DATE_FORMAT(scheduledate, '%d/%m/%y') as classdate, full, bookings
       FROM classes left join schedule
       on classes.classid = schedule.classid
       where scheduleid=".quote_smart($_GET['scheduleid']);
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
	$classname=strip_classname($classname);
	$printdescription = split_classdescription($classdescription);
	$description= $printdescription[0];
	$recipes= $printdescription[1];
	$printrecipes= str_replace("*", "<li class='wide'>", $recipes); // replace asterisk with LI

?>
<div class="container section" id="school_questions">
	
	<div class="row">
		
		<div class="fourcol sidebar specs">
			
			<h4><?=$classname;?></h4>
			<ul>
				<li class='wide'><strong>DATE:</strong><?=$classdate;?></li>
				
				<li class='wide'><strong>Description:</strong> <?=$description;?>.</li>
				<li class='wide'><strong>Recipes:</strong> <?=$printrecipes;?>.</li>
				
				
				</ul>
			<div class='clear'></div>

		</div>
		<!-- end.sidebar -->
		
		<div class="sixcol content">
				<div class="wrap">
				<div class="inner" style="position: relative;">
					
				<img src="resources/bookmark.png" class="bookmark" />

				<form method='post' class="spiritform">
				<fieldset>
     	 		<input type='hidden' name='scheduleid' value='<?=$_GET['scheduleid'];?>' />

      				<legend>Join the Waitlist for <? echo $classname; ?></legend>
      				<p>Simply enter your email address and we'll notify you if places become available for <? echo "$classname on the $classdate"; ?>.</p>
      				<br/>
      				<label for='email'>Your email</label>
      				<input name='email' type='text'/>
        			<!--<tr><td>Your mobile number (for SMS)</td><td><input name='phone' style='width:100px' /></td></tr>-->
        			<button  type='submit' class='bookingsubmit' value=' Submit your details ' /> <span>Join Waitlist!</span></button>     							
        			
        			</fieldset>
      			</form>
				</div>
				</div>		
		</div>
		<!-- end.content -->
		<div class="twocol last"> &nbsp;</div>
			
	</div>
	<!-- end.row -->	
</div>
<!-- end#school-questions.container -->



<? include("includes/footer.inc.php");  ?>