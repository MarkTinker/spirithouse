<?
/*  ===============================================================
	SITE MAP COOKING SCHOOL PAGE
	--------------------------------------------------------------- 

	This page outputs the classes as listed from the site map

*/

// Get the name of the class from the site map and strip the dashes etc //

  $full_url = $_SERVER["REQUEST_URI"];
  $full_url_len = strlen($full_url);
  $classname = str_replace("-", " ", substr($full_url, 1, -14));
  
  
$pageTitle = "Spirit House: Rosters";
$sectionsList = "['#news_deals']";
$bigText_main = "Sunshine Coast Cooking Classes";
$bigText_sub = "A list of <em>$classname</em> cooking classes";

$value = "newsletter";
include("databaseconnect.php");

include("includes/header.inc.php");

  $sql="select * from classes where LOWER(classname)=".quote_smart($classname);
  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  if($no>0) {
    $classid=mysql_result($rs,0,"classid");
    $classname=mysql_result($rs,0,"classname");
    $classdescription=mysql_result($rs,0,"classdescription");
	$classseats=mysql_result($rs,0,"classseats");
	
	
    $scheduled=array();
    $sql2="select *, date_format(scheduledate, '%d %M %Y') as nicedate from schedule where scheduledate>NOW() and classid=".quote_smart($classid)." order by scheduledate ";
    $rs2=mysql_query($sql2);
    $no2=mysql_num_rows($rs2);
    if($no2>0) {
      for($i=0;$i<$no2;$i++) {
        $scheduled[$i]['scheduledid']=mysql_result($rs2,$i,"scheduleid");
        $scheduled[$i]['scheduleddate']=mysql_result($rs2,$i,"nicedate");
        $scheduled[$i]['seatsleft']=$classseats-mysql_result($rs2,$i,"bookings");
      }
    } else {

    }


  } else {

    //header("location: http://www.spirithouse.com.au/school");
    //exit();

  }
  
   ###################### SPLITTING THE CLASS DESCRIPTION  ###################
                $printdescription = split_classdescription($classdescription);
				$description= "$printdescription[0]. <b>Recipes include:</b>";
				$recipes= $printdescription[1];
				$printrecipes= str_replace("*", "<li class='wide'>", $recipes); // replace asterisk with <hr>and <p> for styling


?>

<div class="container section" id="class_desc">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class="nav">Upcoming <?=$classname;?> Classes</h3>
       		<p class="info"><? echo "$description"; ?></p>
		
		</div>	
		
		<div class="threecol sidebar food">
		  <div class="tandc specs">
							<h4>Recipes Include:</h4>
									
									<UL>
									<?=$printrecipes; ?>
									</UL>
									<p>&nbsp;</p>
									
			  
		  	<p>  
         		For a full list of all our classes please visit 
         		<a href='http://www.spirithouse.com.au/school'>our complete cooking school program</a></p>
         		
         </div>	
		</div>
		
		<div class="sixcol content">
	
	<?
       $classes_scheduled=sizeof($scheduled);
       if($classes_scheduled>0) {
         echo "
           <h4>Book $classname Below:</h4>
           <ul>";
         
         
         for($i=0;$i<$classes_scheduled;$i++) {
           if($scheduled[$i]['seatsleft']==0) {
             echo "<li><p class='info'><strike>".$scheduled[$i]['scheduleddate']." (sorry - fully booked!)</strike></li>";
           } else {
             echo "<li><p class='info'><a href='https://www.spirithouse.com.au/booking.php?scheduleid=".$scheduled[$i]['scheduledid']."'>".$scheduled[$i]['scheduleddate']."</a> (".$scheduled[$i]['seatsleft']." seat(s) available)</li>";
           }
         }
         echo "</ul class='test'>";
       
       
       } ELSE {
         # Class is not currently in our schedule so we output two weeks of upcoming classes instead.
          echo "We aren't running this class at the moment but here are the next few weeks classes on offer.  <ul>";
            
         $sql="select scheduleid, classname, classdescription, classseats, classprice, starttime, DATE_FORMAT(scheduledate, '%Y') as year,
          DATE_FORMAT(scheduledate, '%m') as month, DATE_FORMAT(scheduledate, '%d') as day,
          full, bookings, daynight from
          schedule left join classes
          on schedule.classid=classes.classid
          where scheduledate >'".date("Y-m-d")."'
          order by scheduledate, scheduleid
          ";

           $result=mysql_query($sql);
           $schedule=mysql_fetch_array($result);
           $no=mysql_num_rows($result);
           for($t=0;$t<14;$t++) {

             $scheduleid    =trim(mysql_result($result,$t,"scheduleid"));
             $classname     =trim(mysql_result($result,$t,"classname"));
             $classdescription=trim(mysql_result($result,$t,"classdescription"));
             $classseats    =trim(mysql_result($result,$t,"classseats"));
             $classprice    =trim(mysql_result($result,$t,"classprice"));
             $bookings      =trim(mysql_result($result,$t,"bookings"));
             $class_year    =trim(mysql_result($result,$t,"year"));
             $class_month   =trim(mysql_result($result,$t,"month"));
             $class_day     =trim(mysql_result($result,$t,"day"));
             $full          =trim(mysql_result($result,$t,"full"));
             $starttime     =trim(mysql_result($result,$t,"starttime"));
             $daynight      =trim(mysql_result($result,$t,"daynight"));
             $seatsleft     =$classseats-$bookings;
           
           if($seatsleft==0) {
             echo "<li><p class='info'>$class_day / $class_month <strike> $classname </strike> &nbsp;(sorry - fully booked!)</li>";
           } else {
             echo "<li><p class='info'>$class_day / $class_month &nbsp;<a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid'>$classname</a>&nbsp; $seatsleft seat(s) available</li>";

           
                   }
                        }
       
       echo "</ul><p>Visit our <a href='http://www.spirithouse.com.au/school'>cooking school page</a> to see our complete calendar of cooking classes and availability."; }
       ?>
		
		</div><!-- end sixcol -->	
		
		<div class="threecol last"></div>

     
	</div>
	<!-- end.row -->
</div>



<? include("includes/footer.inc.php");  
function split_classdescription($classdescription) {

	$offset= strpos($classdescription,":");
	$descriptionlength = strlen($classdescription);
	$description = substr($classdescription,0, $offset);// the first part of the description
	$recipes = substr($classdescription,(($descriptionlength-($offset+1))*-1));// gets the recipe part AFTER the colon, hence the +1
	$printrecipes= str_replace("*", "<li class='wide'>", $recipes); // replace asterisk with LI
	
	$result = array($description, $recipes);
	return $result;
	
	
	}
	?>