<?
include("head.php");
include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();
$schedule=array();
$old_class_year=0;
$old_class_month=0;
$old_class_day=0;

$sql="select scheduleid, classname, classdescription, starttime, DATE_FORMAT(scheduledate, '%Y') as year,
  DATE_FORMAT(scheduledate, '%m') as month, DATE_FORMAT(scheduledate, '%d') as day,
  full, bookings, daynight from
  schedule left join classes
  on schedule.classid=classes.classid
  where scheduledate>'".date("Y-m-d")."'
  order by scheduledate, scheduleid
  ";

   $result=mysql_query($sql);
   $schedule=mysql_fetch_array($result);
   $no=mysql_num_rows($result);
   for($t=0;$t<$no;$t++) {

     $scheduleid=trim(mysql_result($result,$t,"scheduleid"));
     $classname=trim(mysql_result($result,$t,"classname"));
     $classdescription=trim(mysql_result($result,$t,"classdescription"));
     $bookings=trim(mysql_result($result,$t,"bookings"));
     $class_year=trim(mysql_result($result,$t,"year"));
     $class_month=trim(mysql_result($result,$t,"month"));
     $class_day=trim(mysql_result($result,$t,"day"));
     $full=trim(mysql_result($result,$t,"full"));
     $starttime=trim(mysql_result($result,$t,"starttime"));

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
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['bookings']=$bookings;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['full']=$full;
     $schedule[$class_year][$class_month][$class_day][$classes_for_today]['starttime']=$starttime;

     #update the vars for the next loop
     $old_class_year=$class_year;
     $old_class_month=$class_month;
     $old_class_day=$class_day;

    }



    ##################

    # locating the last date in the database
    $last_date_in_database                        =$old_class_year."-".$old_class_month."-".$old_class_day;

    # make the next day's date, and explode the date into year/month/date elements
    $next_date_after_last_date_in_database_array  =explode("-", date("Y-m-d", strtotime("+1 day", strtotime($last_date_in_database))));

    # grab the elements
    $finish_year   =$next_date_after_last_date_in_database_array[0];
    $finish_month  =$next_date_after_last_date_in_database_array[1];
    $finish_day    =$next_date_after_last_date_in_database_array[2];

    # put the FINISH marker into the schedule array
    $schedule[$finish_year][$finish_month][$finish_day][0]['classname']="FINISH";

    ##################



    echo "<table>";

     #this outputs the month if TODAY is not the first. - could make a problem on the first day of the month as you will get TWO month outputs. Put a control where it checks the DAY of the month and if its' the first then DON'T echo this.
     if(date("d", strtotime("today"))!=1){
     echo "<tr><td colspan=3 align='center'><h2>classes for  ".date("F", strtotime("today"))." </h2></td></tr>";
     }


    for($i=1;$i<9999;$i++) {

      $search_year=date("Y", strtotime("+".$i." days"));
      $search_month=date("m", strtotime("+".$i." days"));
      $search_day=date("d", strtotime("+".$i." days"));
      $display_month=date("F", strtotime("+".$i." days"));




      ## ######Do a check to see if the DAY is a SAT OR SUN and change the <TR Class> to WE so the weekends are in a different colour
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
                if($j==0) echo "<tr class='$rowtype'><td>$dayname:</td><td>  $search_day / $search_month / $search_year:</td><td>";

                $scheduleid       =$schedule[$search_year][$search_month][$search_day][$j]['scheduleid'];
                $bookings         =$schedule[$search_year][$search_month][$search_day][$j]['bookings'];
                $class            =$schedule[$search_year][$search_month][$search_day][$j]['classname'];
                $classdesription  =$schedule[$search_year][$search_month][$search_day][$j]['classdescription'];
                $starttime        =$schedule[$search_year][$search_month][$search_day][$j]['starttime'];
                $seatsleft        =18-$bookings;

                if($bookings==18) {
                  echo "<table class='calclasses' width='100%'><tr><td width='10%'>&nbsp;</td><td width='35%'><s>".$class."</s></td><td width='25%'><span style='color:red'>FULL!</span></td><td width=25%><a href='waitlist.php?scheduleid=$scheduleid'><small>join the WAITLIST</small></a></td></table>";
                } else {
                  echo "<table class='calclasses' width='100%'><tr><td width='10%'>";
                  if($j==0) echo "Day:</td> ";
                  if($j>0) echo "Night:</td> ";
                  echo "<td width='35%'><a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid' title='<b>Click to Book: $class</b><br />$classdesription'>$class</a>";
                  echo "</td><td width='25%'>";
                  echo " (".$seatsleft." seats left) ";
                  echo "</td><td width='25%'>";
                  echo "(starts: ".$starttime.")";
                  echo "</td></tr></table>";
                }

                # if it's the last class of the day, close the <tr>
                if(($j+1)==$classes_for_today) echo "</td></tr>";
              }
            }
          } else {
            echo "<tr class='$rowtype'><td>$dayname:</td><td>  $search_day / $search_month / $search_year:</td><td><strong>no class today";
            echo "</td></tr>";
          }
        } else {
          echo "<tr class='$rowtype'><td>$dayname:</td><td>  $search_day / $search_month / $search_year:</td><td><strong>no class today";
          echo "</td></tr>";
        }
      } else {
        echo "<tr class='$rowtype'><td>$dayname:</td><td>  $search_day / $search_month / $search_year:</td><td><strong>no class today</strong>";
        echo "</td></tr>";
      }

    }

    echo "</table>";
    echo "<br /><br />end.";