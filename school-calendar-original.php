<?
include("databaseconnect.php");
//include("includes/is_Murray.inc.php");
//include("globals.inc.php");




$digest_date = "2008-11-01";
$date_diff = abs(strtotime(date('y-m-d'))-strtotime($digest_date)) / 86400;
$yesterday=date("Y-m-d", strtotime("-1 days"));

$schedule=array();
$old_class_year=0;
$old_class_month=0;
$old_class_day=0;

 //$yesterday = mktime(0, 0, 0, date("m"), date("d")-3, date("y"));
 //$dateyesterday = date("Y-m-d", $yesterday); 


$sql="select scheduleid, classname, classdescription, classseats, classprice, starttime, DATE_FORMAT(scheduledate, '%Y') as year,
  DATE_FORMAT(scheduledate, '%m') as month, DATE_FORMAT(scheduledate, '%d') as day,
  full, bookings, daynight from
  schedule left join classes
  on schedule.classid=classes.classid
  where scheduledate >'$yesterday'
  order by scheduledate, scheduleid
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
     $classprice=trim(mysql_result($result,$t,"classprice"));
     $bookings=trim(mysql_result($result,$t,"bookings"));
     $class_year=trim(mysql_result($result,$t,"year"));
     $class_month=trim(mysql_result($result,$t,"month"));
     $class_day=trim(mysql_result($result,$t,"day"));
     $full=trim(mysql_result($result,$t,"full"));
     $starttime=trim(mysql_result($result,$t,"starttime"));
     $daynight=trim(mysql_result($result,$t,"daynight"));
     
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
    echo "<table width='100%'>"; // CSSGRID: This starts the calendar table - we should dump this.

     #this outputs the month if TODAY is not the first. - could make a problem on the first day of the month as you will get TWO month outputs. Put a control where it checks the DAY of the month and if its' the first then DON'T echo this.
     if(date('j')>=1){
     echo "<tr><td colspan=3 align='center'><h2>classes for  ".date("F", strtotime("today"))."</h2></td></tr>";// CSSGRID This is the first ROW and holds the MONTH NAME only STYLE this row with heaps of padding.
     }

     ####################
     //CHANGE $i below to 0 if you want to show the first date in the array    
     ###################
    for($i=0;$i<9999;$i++) {
      $search_year=date("Y", strtotime("+".$i." days"));
      $search_month=date("m", strtotime("+".$i." days"));
     
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
        echo "<tr><td colspan=3 align='center'><h2>classes for ".$display_month."</h2></td></tr>";
      }

      #is there an index in the array on this year/month/day?
      if(array_key_exists($search_year,$schedule)) {
        if(array_key_exists($search_month,$schedule[$search_year])) {
          if(array_key_exists($search_day,$schedule[$search_year][$search_month])) {

            $days_since_last_class=0;
            #1st: check how many classes today
            $classes_for_today=sizeof($schedule[$search_year][$search_month][$search_day]);

            for($j=0;$j<$classes_for_today;$j++) {

              #####################################
              # see if we've hit the finish marker yet?
              $class            =$schedule[$search_year][$search_month][$search_day][$j]['classname'];
              if($class=="FINISH") {
                $i=9999;
              } else {

                #if it's the first class of the day, open the <tr>
                if($j==0) echo "<tr class='$rowtype'><td>$dayshow:</td><td width='40px'>$search_day/$search_month:</td><td>";

                $scheduleid       =$schedule[$search_year][$search_month][$search_day][$j]['scheduleid'];
                $bookings         =$schedule[$search_year][$search_month][$search_day][$j]['bookings'];
                $class            =$schedule[$search_year][$search_month][$search_day][$j]['classname'];
                $classseats       =$schedule[$search_year][$search_month][$search_day][$j]['classseats'];
                $classprice       =$schedule[$search_year][$search_month][$search_day][$j]['classprice']; 
                $classdesription  =$schedule[$search_year][$search_month][$search_day][$j]['classdescription'];
                $starttime        =$schedule[$search_year][$search_month][$search_day][$j]['starttime'];
                $daynight        =$schedule[$search_year][$search_month][$search_day][$j]['daynight'];
                $seatsleft        =$classseats-$bookings;
                if($seatsleft>1)
                    {$seattext='seats';
                    }else{
                        $seattext='seat';}
                ############works out if it's a day or night class to be used when the class is FULL
                if($daynight==0){$classtype='Day';}
                if($daynight==1) {$classtype='Night';}
                ############ 
                if($bookings >= $classseats) {
                  echo "<table class='calclasses' width='100%'><tr><td width='10%'>".$classtype ."</td><td width='50%'><s>".$class."</s></td><td width='10%'><span style='color:red'>FULL!</span></td><td width=25%><a href='waitlist.php?scheduleid=$scheduleid'><small>join waitlist</small></a></td></table>";
                } else {
                  echo "<table class='calclasses' width='100%'><tr><td width='10%'> $classtype <td width='50%'><a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid' title='Click to Book:<br><b>$class:</b><br><b>Starts:</b> $starttime <br /><b>Price:  $$classprice</b><br />$classdesription'>$class</a>";
                  echo "</td><td width='10%'>";
                  echo " &nbsp; ";
                  echo "</td><td width='25%'>";
                  echo "$seatsleft $seattext left";
                  echo "</td></tr></table>";
                }

                # if it's the last class of the day, close the <tr>
                if(($j+1)==$classes_for_today) echo "</td></tr>";
              }
            }
          } else {
            echo "<tr class='$rowtype'><td>$dayshow:</td><td>  $search_day/$search_month:</td><td><table class='calclasses' width='100%'><tr><td width='10%'>&nbsp;</td><td width='50%'>No Classes Today</td><td width='10%'>&nbsp;</td><td width=25%>&nbsp;</td></table>";
            echo "</td></tr>";
          }
        } else {
          echo "<tr class='$rowtype'><td>$dayshow:</td><td>  $search_day/$search_month:</td><td><table class='calclasses' width='100%'><tr><td width='10%'>&nbsp;</td><td width='50%'>No Classes Today</td><td width='10%'>&nbsp;</td><td width=25%>&nbsp;</td></table>";
          echo "</td></tr>";
        }
      } else {
        echo "<tr class='$rowtype'><td>$dayshow:</td><td>  $search_day/$search_month:</td><td><table class='calclasses' width='100%'><tr><td width='10%'>&nbsp;</td><td width='50%'>No Classes Today</td><td width='10%'>&nbsp;</td><td width=25%>&nbsp;</td></table>";
        echo "</td></tr>";
      }

    }

    echo "</table>";
    echo "<br /><br />";
    

    
    

