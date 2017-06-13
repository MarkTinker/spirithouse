<?

#### Unescape below if you're not using the sign-in thingy @@@@@@
#### include("../databaseconnect.php");



//include("includes/is_Murray.inc.php");
//include("globals.inc.php");

###### FAQ used in the calendar ######
$faq= faq_section();




$digest_date = "2008-11-01";
$date_diff = abs(strtotime(date('y-m-d'))-strtotime($digest_date)) / 86400;
$yesterday=date("Y-m-d", strtotime("-1 days"));

$schedule=array();
$old_class_year=0;
$old_class_month=0;
$old_class_day=0;

 //$yesterday = mktime(0, 0, 0, date("m"), date("d")-3, date("y"));
 //$dateyesterday = date("Y-m-d", $yesterday); 


	


$sql="select scheduleid, classname, classdescription, classseats, classprice, starttime, discount, discount_price, DATE_FORMAT(scheduledate, '%Y') as year,
  DATE_FORMAT(scheduledate, '%m') as month, DATE_FORMAT(scheduledate, '%d') as day,
  full, bookings,scheduleseats, daynight from
  schedule left join classes
  on schedule.classid=classes.classid
  where scheduledate >'$yesterday'
  order by scheduledate, daynight 
  ";
  
  //echo $sql;
  //exit();

   $result=mysql_query($sql);
   $schedule=mysql_fetch_array($result);
   $no=mysql_num_rows($result);
   for($t=0;$t<$no;$t++) {

     $scheduleid=trim(mysql_result($result,$t,"scheduleid"));
     $classname=trim(mysql_result($result,$t,"classname"));
     $classdescription=trim(mysql_result($result,$t,"classdescription"));
     $classseats=trim(mysql_result($result,$t,"classseats"));
     $scheduleseats=trim(mysql_result($result,$t,"scheduleseats"));
     $classprice=trim(mysql_result($result,$t,"classprice"));
     $discount=trim(mysql_result($result,$t,"discount"));
     $discountprice=trim(mysql_result($result,$t,"discount_price"));    
     $bookings=trim(mysql_result($result,$t,"bookings"));
     $class_year=trim(mysql_result($result,$t,"year"));
     $class_month=trim(mysql_result($result,$t,"month"));
     $class_day=trim(mysql_result($result,$t,"day"));
     $full=trim(mysql_result($result,$t,"full"));
     $starttime=trim(mysql_result($result,$t,"starttime"));
     $daynight=trim(mysql_result($result,$t,"daynight"));
     
     $classname=strip_classname($classname);
     	# checking to see if class seats is being over-ridden by the scheduled amount of seats
  		# we can override the default classseats in the schedule now - this checks to see
  		# if a class has more or less seats than default for today 
  		if($scheduleseats>0){
  		$classseats=$scheduleseats;
  		}else{
  		
  		$classseats=$classseats;
  		} 
     # are we looping on a class on the same day as yesterday??  if so, increment $classes_for_today variable
     if(($class_year==$old_class_year) && ($class_month==$old_class_month) && ($class_day==$old_class_day)) {
       $classes_for_today++;
     } else {
       $classes_for_today=0;
     }

     #add values to the associative array
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['scheduleid']=$scheduleid;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['classname']=$classname;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['classdescription']=$classdescription;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['classseats']=$classseats;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['classprice']=$classprice; 
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['discount']=$discount;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['discountprice']=$discountprice;       
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['bookings']=$bookings;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['full']=$full;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['starttime']=$starttime;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['daynight']=$daynight;
       
     #update the vars for the next loop
     $old_class_year=$class_year;
     $old_class_month=$class_month;
     $old_class_day=$class_day;
     
     //echo "class day is $class_day";
     //print_r($schedule);
     //exit();

    }



    ##################

    # locating the last date in the database
    $last_date_in_database =$old_class_year."-".$old_class_month."-".$old_class_day;
	
    # make the next day's date, and explode the date into year/month/date elements
    $next_date_after_last_date_in_database_array  =explode("-", date("Y-m-d", strtotime("+1 day", strtotime($last_date_in_database))));

	
	
    # grab the elements
    $finish_year   =$next_date_after_last_date_in_database_array[0];
    $finish_month  =$next_date_after_last_date_in_database_array[1];
    $finish_day    =$next_date_after_last_date_in_database_array[2];

    # put the FINISH marker into the schedule array
    $schedule[$finish_year][$finish_month][$finish_day][0]['classname']="FINISH";

    ##################

     
    $testmonth = date('F'); 
     
     
     // $calendarstarted is used as a control to close the divs for the code that generates the calendar on the FIRST of the month. .
	$calendarstarted=0;
     #Starts the ROW AND HEADER for the school calendar if today's date is NOT the first day of the month.
     if(date('j')>=1){
     $calendarstarted=1;
     //This section outputs the first Month according to the calendar
     echo "
		<div class='row'>
		<div class='threecol specs'>
			<div class='faq'>
					<h4>Frequently Asked Questions</h4>
					$faq
				</div>		

		</div>
		<!-- end.three -->
		
		<div class='ninecol last content'>
			
			<div class='wrap'>				
				
				<!-- // SUB-SECTION: UPCOMING CLASSES -->
				<div id='school_classes' class='subsection'>
					<div class='inner'>
						
						<table class='school'>
						        
						        <caption>Classes for $testmonth</caption>
						        <thead>
						        <tr class='header'>
						                <th class='date_col'>Date</th>						                
						                <th>Class Information</th>
										<th class='nightday_col'>Day / Night</th>
										<th class='avail_col'>Availability</th>
						        </tr>
						        </thead>						
						        <tbody>";
        
     }

     ####################
     //CHANGE $i below to 0 if you want to show the first date in the array    
     ###################
    for($i=0;$i<9999;$i++) {
      $search_year=date("Y", strtotime("+".$i." days"));
      $search_month=date("m", strtotime("+".$i." days"));
      $show_month=date("M", strtotime("+".$i." days"));
     
      ####### Grabs the DAY for today 
      ####### if you want a date in the past, you need use $yesterday for the $searchday and then get it to increment 
      ####### something like this: $search_day=date("d", (strtotime("+".$i."days", strtotime($yesterday)))); ---BUT you need to enter this for every $search_ variable.
      $search_day=date("d", strtotime("+".$i." days"));
      
      
      $display_month=date("F", strtotime("+".$i." days"));
      //echo "this is $search_day and number is $i and yesterday is $yesterday";
      //exit();

      ## ######Do a check to see if the DAY is a SAT OR SUN and change the <TR Class> to WE so the weekends are in a different colour
      //CSSGRID - we can use this to colour
        $dayshow=date('D', strtotime("+".$i." days")); 
        $dayname=date('l', strtotime("+".$i." days"));
        if($dayname=="Saturday" || $dayname=="Sunday") {
        $rowtype="we";
      } else {
        $rowtype="bg";
      }
      #############################
         #If it's the first of the month then we print the MONTH before we start going through the days.
      
      if(number_format($search_day)==1) {
      
      		if($calendarstarted==1){
      			 echo "	</table>
    
    					</div>
						<!-- end.inner -->
						</div>
						<!-- // END SUB-SECTION: UPCOMING CLASSES -->
				
			
						</div>
						<!-- end.wrap -->
			
						</div>
						<!-- end.content -->		
						</div>
						<!-- end.row -->
    
    				";
      
      			}
      
      
      
      
        echo "
        	<div class='row'>
        	<div class='threecol specs'>
				<div class='faq'>
					<h4>Frequently Asked Questions</h4>
					$faq
				</div>
			</div>		
		<div class='ninecol last content'>
			
			<div class='wrap'>				
				
				<!-- // SUB-SECTION: UPCOMING CLASSES -->
				<div id='school_classes' class='subsection'>
					<div class='inner'>
						
						<table class='school'>
						        <caption>Classes for $display_month</caption>
						        <thead>
						       
						        <tr class='header'>
						                <th class='date_col'>Date</th>						                
						                <th>Class Information</th>
										<th class='nightday_col'>Day / Night</th>
										<th class='avail_col'>Availability</th>
						        </tr>
						        </thead>						
						        <tbody>";
      }

      #is there an index in the array on this year/month/day?
      if(array_key_exists($search_year,$schedule)) {
        if(array_key_exists($search_month,$schedule[$search_year])) {
          if(array_key_exists($search_day,$schedule[$search_year][$search_month])) {

            $days_since_last_class=0;
            #1st: check how many classes today
            $classes_for_today=sizeof($schedule[$search_year][$search_month][$search_day]);
			
			
			#####################################
            ### HAMISH - THIS CODE CREATES THE PAD CLASS##
            #####################################  
			if($classes_for_today>1){
			$pad="pad";
				} else {
				$pad=" ";
				}
			
			
            for($j=0;$j<$classes_for_today;$j++) {

              #####################################
              # see if we've hit the finish marker yet?
              $class=$schedule[$search_year][$search_month][$search_day][$j]['classname'];
              if($class=="FINISH") {
                $i=9999;
              } else {

                #if it's the first class of the day, open the ROW
                if($j==0) echo "
             						<tr>
						                <td class='date_col' rowspan='".$classes_for_today."'>
											<div class='date ".$pad."'>
												<span class='dw'>$dayshow</span>
												<span class='d'>$search_day</span>
												<span class='m'>$show_month</span>
											</div>
										</td>
										";

                $scheduleid       =$schedule[$search_year][$search_month][$search_day][$j]['scheduleid'];
                $bookings         =$schedule[$search_year][$search_month][$search_day][$j]['bookings'];
                $class            =$schedule[$search_year][$search_month][$search_day][$j]['classname'];
                $classseats       =$schedule[$search_year][$search_month][$search_day][$j]['classseats'];
                $classprice       =$schedule[$search_year][$search_month][$search_day][$j]['classprice']; 
                $discount		  =$schedule[$search_year][$search_month][$search_day][$j]['discount'];
                $discountprice		  =$schedule[$search_year][$search_month][$search_day][$j]['discountprice'];  	
                $classdescription  =$schedule[$search_year][$search_month][$search_day][$j]['classdescription'];
                $starttime        =$schedule[$search_year][$search_month][$search_day][$j]['starttime'];
                $daynight        =$schedule[$search_year][$search_month][$search_day][$j]['daynight'];
                $seatsleft        =$classseats-$bookings;
                
     
                 ###################### WORK OUT IF CLIENT IS ELLIGBLE FOR DISCOUNT  ###################
                 $discount_total = $classprice-$discountprice;
                
                  if ($discount == 1)
                 //if (($isfan==1) AND ($discount == 1))//- the old code that checked for facebook fans and discount
                 {
                 $classprice = "<del>$classprice</del> <span class='teal'>for you: &#36; $discountprice</span>";
                 $discounttext= "<span class='teal'>WOW! &#36;$discount_total discount for you</span>";
                 $discountlink="l=1&";
                 }ELSE{
                 //$classprice=$classprice;
                 $discounttext=' &nbsp;';
                 $discountlink=''; 
                 }
                 
                
                ###################### SPLITTING THE CLASS DESCRIPTION  ###################
                $printdescription = split_classdescription($classdescription);
				$description= "$printdescription[0]. <b>Recipes include:</b>";
				$recipes= $printdescription[1];
				$printrecipes= str_replace("*", "<hr> <p>", $recipes); // replace asterisk with <hr>and <p> for styling
				#################
                if($seatsleft>1)
                    {$seattext='seats';
                    }else{
                        $seattext='seat';}
                ############works out if it's a day or night class to be used when the class is FULL
                if($daynight==0){$classtype='Day';}
                if($daynight==1) {$classtype='Night';}
                ############ 
                if($bookings >= $classseats) {
                  echo "
                  		 				<td>
											<div class='classblock'>
												<p class='classname'>$class</p>
												<p class='info'>No Seats Available 
													<span class='mobile_links'>
														<a href='school-waitlist.php?scheduleid=$scheduleid'><small>Join Waitlist</small></a> / 
														<a href='#' class='toggle'>Details &darr;</a>
													</span>
												</p>
												<a href='#' class='showdescription toggle'>Details &darr;</a>
													<div class='details' style='display:none;'>
														<b>Starts:</b> $starttime &mdash;
														<b>Cost:$</b> $classprice<br>
														$description
														$printrecipes
													</div>
											</div>
										</td>
										<td class='nightday'>$classtype</td>
										<td class='button'>
											<a class='waitlist' href='school-waitlist.php?scheduleid=$scheduleid'>Join Waitlist</a>
											<div class='classblock'> <p class='info'>Class Full </div>
										</td>
                  		</tr>	
						
						";
						
	
                
                } else {
 
                echo"
                
                					<td>
											<div class='classblock'>
												<p class='classname'>$class</p>
												<p class='info'>$discounttext</p>
													<span class='mobile_links'>
														<a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid'>
														Book Now</a> /
														<a href='#' class='toggle'>Details &darr;</a>
													</span>
												</p>
												<a href='#' class='showdescription toggle'>Details &darr;</a>
													<div class='details' style='display:none;'>
														<b>Starts:</b> $starttime &mdash;
														<b>Cost:$</b> $classprice<br>
														$description
														$printrecipes
													</div>
											</div>
										</td>
										<td class='nightday'>$classtype</td>
										<td class='button'>
											<a class='book' href='https://www.spirithouse.com.au/booking.php?$discountlink scheduleid=$scheduleid'>Book Now</a>
											<div class='classblock'><p class='info'>$seatsleft $seattext left </div>
										</td>
						
								</tr>		
                
						";
   
                }

                # if it's the last class of the day, DO NOTHING - POSSIBLY DELETE THIS
                if(($j+1)==$classes_for_today) 
                
                echo "	
                		
                
						
						";
              }
            }
          } else {
            echo " 
            
            		
						                <td class='date_col'>
											<div class='date'>
												<span class='dw'>$dayshow</span>
												<span class='d'>$search_day</span>
												<span class='m'>$show_month</span>
											</div>
										</td>
            
            							<td>
											<div class='classblock'>
												<p class='classname'>No Class Today</p>
												<p class='info'>no class today
													
												</p>
											</div>
										</td>
										<td class='nightday'>&nbsp;</td>
										<td>
											&nbsp;
										</td>
					</tr>";
          }
        
        
        } else {
         echo " 	
         			<tr>
						                <td class='date_col'>
											<div class='date'>
												<span class='dw'>$dayshow</span>
												<span class='d'>$search_day</span>
												<span class='m'>$show_month</span>
											</div>
										</td>
            
            							<td>
											<div class='classblock'>
												<p class='classname'>No Class Today</p>
												<p class='info'>no class today
													
												</p>
											</div>
										</td>
										<td class='nightday'>&nbsp;</td>
										<td>
											&nbsp;
										</td>
					</tr>";
        }
      
      
      } else {
       echo " <tr>
						                <td class='date_col'>
											<div class='date'>
												<span class='dw'>$dayshow</span>
												<span class='d'>$search_day</span>
												<span class='m'>$show_month</span>
											</div>
										</td>
            
            							<td>
											<div class='classblock'>
												<p class='classname'>No Class Today</p>
												<p class='info'>no class today
													
												</p>
											</div>
										</td>
										<td class='nightday'>&nbsp;</td>
										<td>
											&nbsp;
										</td>
					</tr>";      }

    }

    echo "			</table>
    
    		</div>
					<!-- end.inner -->
				</div>
				<!-- // END SUB-SECTION: UPCOMING CLASSES -->
				
			
			</div>
			<!-- end.wrap -->
			
		</div>
		<!-- end.content -->		
	</div>
	<!-- end.row -->
    
    
    
    ";
 
    

    
    

