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
  schedule2 left join classes
  on schedule2.classid=classes.classid
  where scheduledate>'2008-05-08'
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

    $days_to_display=200;

    echo "<table>";
    echo "<tr><td><h2>Welcome to month ".date("m", strtotime("today"))."!!</h2><br /><br /></td><td></td></tr>";

    for($i=1;$i<$days_to_display;$i++) {

      $search_year=date("Y", strtotime("+".$i." days"));
      $search_month=date("m", strtotime("+".$i." days"));
      $search_day=date("d", strtotime("+".$i." days"));

      echo "<tr><td>searching on $search_day / $search_month / $search_year:</td><td>";

      #is there an index in the array on this year/month/day?
      if(array_key_exists($search_year,$schedule)) {
        if(array_key_exists($search_month,$schedule[$search_year])) {
          if(array_key_exists($search_day,$schedule[$search_year][$search_month])) {

            #1st: check how many classes today
            $classes_for_today=sizeof($schedule[$search_year][$search_month][$search_day]);

            for($j=0;$j<$classes_for_today;$j++) {
              if($j>0) echo "<br />".($j+1).": ";
              $scheduleid       =$schedule[$search_year][$search_month][$search_day][$j]['scheduleid'];
              $bookings         =$schedule[$search_year][$search_month][$search_day][$j]['bookings'];
              $class            =$schedule[$search_year][$search_month][$search_day][$j]['classname'];
              $classdesription  =$schedule[$search_year][$search_month][$search_day][$j]['classdescription'];
              $starttime        =$schedule[$search_year][$search_month][$search_day][$j]['starttime'];
              $seatsleft        =18-$bookings;

              if(number_format($search_day)==1) {
                echo "<h2>Welcome to month ".$search_month."!!</h2><br /><br />";
              }
              if($bookings==18) {
                echo "<s>".$class."</s> - <span style='color:red'>SOLD OUT!</span>";
              } else {
                echo "<a href='' title='$classdesription'>$class</a>";
                echo " (".$seatsleft." seats left) ";
                echo "(starts: ".$starttime.")";
              }
            }
          } else {
            echo "<strong>no class today";
          }
        } else {
          echo "<strong>no class today";
        }
      } else {
        echo "<strong>no class today";
      }


      echo "</td></tr>";
    }

    echo "</table>";