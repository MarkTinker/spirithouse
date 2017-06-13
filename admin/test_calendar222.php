<?
include("head.php");
include("../globals.inc.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();


#Gets the months in the database and year
 $sql="SELECT classes.classid, scheduledate, MONTH(scheduledate) as monthnumber, DATE_FORMAT(scheduledate, '%d') as classdate, DATE_FORMAT(scheduledate, '%Y') as classyear, DATE_FORMAT(scheduledate, '%M') as classmonth, full FROM classes, schedule where classes.classid=schedule.classid and scheduledate >=now() order by scheduledate";

           $result=mysql_query($sql);
           $no=mysql_num_rows($result);
           $arr = array();
           if($no > 0) {

                for($i=0;$i<$no;$i++) {
                $monthcontrol=$monthtest;
                $monthtest=mysql_result($result,$i,"monthnumber");
                $monthyear=mysql_result($result,$i,"classyear");
                $classmonth=mysql_result($result,$i,"classmonth"); 
                $calyear=mysql_result($result,$i,"classyear"); 
                 if($monthcontrol!=$monthtest){
                 $arr[]=$monthtest;
                 
                 
                 
                 
                 
                 
                 echo" this is the month number $monthtest";
                 
                 
                 
                 
                 }


                }
                #echo "<br>YEAR = $calyear";

           }

           foreach($arr as $value)
           { 
               
               #Grabs the data from the dbase for the MONTH and starts the LOOP for each month



$sql="SELECT classes.classid, classname, classdescription, classprice, MONTH(scheduledate) as monthnumber, scheduleid, date_format(scheduledate, '%W') as dayname, DATE_FORMAT(scheduledate, '%d') as classday, date_format(scheduledate, '%d %M %y') as classdate, DATE_FORMAT(scheduledate, '%Y-%m-%d') as classfulldate, DATE_FORMAT(scheduledate, '%Y') as classyear, full, bookings, daynight FROM classes, schedule WHERE classes.classid=schedule.classid and MONTH(scheduledate) = $value and scheduledate>= now() order by scheduledate, daynight" ;
$result=mysql_query($sql);
$noclasses=mysql_num_rows($result);
 
            #works out how many days are in this month
            $getclass=0; 
            $month=mysql_result($result,$getclass,"monthnumber");
            $year=mysql_result($result,$getclass,"classyear");
            $Days_In_Month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
           
   
           
           
           
            
    
          
?>
<script>

</script>

<p><h3>MONTH: <? echo getmonth($value).$Days_In_Month.$month; ?></h3></p>


    <table>
        <tr>
        <th>Day</th>
        <th>Date</th>
        <th>Class name</th>
      <th>Day/night</th>
      <th>Seats left</th>
      <th>Cost</th>
      <th>Book Status</th>
        </tr>
        <? 
           
            $calday=1;
            #### have to reset the calendar output to blank before each month - otherwise youwill keep adding to the variable and only getting the first result
            $Cal_Weeks_Days='';
            //$getclass=0;
            ####
            
            # START THE CALENDAR OUTPUT
            for($t=0; $t<$Days_In_Month; $t++)
            {
            //echo'this is the month number:'. $month;
            //echo'$getclass variable:'.$getclass;   
           
           #this is the date of the month
           $caldate = date("d F y", mktime(0,0,0,$month,$calday,$year));
           #this is the DAY of the month
           $caldayshow=date('l', strtotime($caldate));  
           
           #$noclasses is going to be more than the number of DAYS so remember that - $getclasses needs to be able to handle that
           $getclass=$classcount;             
           $scheduleid=mysql_result($result,$getclass,"scheduleid");
           $classid=mysql_result($result,$getclass,"classid");
           $classdate=mysql_result($result,$getclass,"classdate");
           $classname=mysql_result($result,$getclass,"classname");
           $classday=mysql_result($result,$getclass,"classdate");
           $classfulldate=mysql_result($result,$getclass,"classfulldate");
           $classprice=mysql_result($result,$getclass,"classprice");
           $full=mysql_result($result,$getclass,"full");
           $daynight=mysql_result($result,$getclass,"daynight"); 
           $bookings=mysql_result($result,$getclass,"bookings");
           $classdescription=mysql_result($result,$getclass,"classdescription");
           $ClassNameLink = "<a href='#nogo' onclick=\"javascript:openWindow($classid,'$classname');\">$classname</a>";
            
            #gives a name to the daynight variable - has to be changed if there's more than two classes a day
            if($daynight==0) { $daynight="Day"; } else { $daynight="Night"; }  
        
         echo $classcount;  
        #Here's were we MATCH a date with a CLASS no match then print a blank row
      if ($classdate!=$caldate){
      $Cal_Weeks_Days .= '<tr align="left" class='.$rowtype.'>
      <td>'.$caldayshow.'</td>
      <td>'.$caldate.'</td>
      <td width=150>'.$Days_In_Month.' and $t'.$t.'</td> 
      <td width=10>'.$noclasses.'noclasses'.'</td>
      <td width=100 align="center" width=250>'.$classcount.'classcount</td>
      <td align="center" width=25>$nada</td>
      <td width=50 align="center" width=250>and nada</td>
        </tr>';
        //increment the day so we can start a new <tr> and check again
        $calday++;
              
           
           }else {
           
    ######### #DATES MATCH - SHOLD DO A NEW TR HERE AND CHECK FOR MULTIPLE CLASSES ON THIS DATE

            if($bookings<maxseats) {
              $bookinglink="<br /><a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid'><small>BOOK NOW</small></a>";
             
            } else {
              $Extra="bgcolor=#CCCC99 id='calendarfull'";
              $bookinglink=" <a style='text-decoration:none' href='waitlist.php?scheduleid=$scheduleid'><br><small>WAITLIST</small></a>";
            }

            if($classfulldate<date("Y-m-d")) $bookinglink="";
            #seats left in each class
            $seatsleft=(maxseats-$bookings); 
           #printing the data into the td cells
           $Cal_Weeks_Days .= 
           '<tr align="left" class='.$rowtype.'>
                  <td>'.$caldayshow.'</td>
                  <td>'.$caldate.'</td>
                  <td width=150>'.$classname.'</td> 
                  <td width=10>'.$daynight.'</td>
                  <td width=100 align="center" width=250>'.$seatsleft.'seat/s left</td>
                  <td align="center" width=25>$'.$classprice.'</td>
                  <td width=50 align="center" width=250>'.$bookinglink.'</td>
           </tr>';
           #make sure that classcount isn't more than the records in the database otherwise add to classcount
            if($classcount==$noclasses-1){$classcount=0;}else{
           $classcount++; }

            $calday++;   

           }
           
           
           

         
            }
                echo $Cal_Weeks_Days;  
        ?>
        <tr>
            <td colspan="5">
            <b>$noclasses: <? echo $noclasses; ?></b><br>
            <b>$classcount: <? echo $classcount; ?></b><br> 
            <b>$getclass: <? echo $getclass; ?></b><br>
            <b>$t: <? echo $t; ?></b><br>
            <b>$classdate: <? echo $classdate; ?></b><br>
            <b>$caldate: <? echo $caldate; ?></b><br>  
            </td>
        </tr>
    </table>

<? } ?>
<?

foot();
?>

<?php




function getmonth($m=0) {
return (($m==0 ) ? date(”F”) : date(”F”, mktime(0,0,0,$m)));
}


function checknextclass($getclass, $caldate){
    #We have the date and a class to match, so while we're on this date we need to check the next records until we get a date mismatch
          $daydata='';
          $rowspan=1;
          WHILE ($classdate==$caldate)
                        {           
                               
                            $scheduleid=mysql_result($result,$getclass,"scheduleid");
                               $classid=mysql_result($result,$getclass,"classid");
                               $classdate=mysql_result($result,$getclass,"classdate");
                               $classname=mysql_result($result,$getclass,"classname");
                               $classday=mysql_result($result,$getclass,"classdate");
                               $classfulldate=mysql_result($result,$getclass,"classfulldate");
                               $classprice=mysql_result($result,$getclass,"classprice");
                               $full=mysql_result($result,$getclass,"full");
                               $daynight=mysql_result($result,$getclass,"daynight"); 
                               $bookings=mysql_result($result,$getclass,"bookings");
                               $classdescription=mysql_result($result,$getclass,"classdescription");
                               $ClassNameLink = "<a href='#nogo' onclick=\"javascript:openWindow($classid,'$classname');\">$classname</a>";
                               
                               
                               
                               if($bookings<maxseats) {
              $bookinglink="<br /><a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid'><small>BOOK NOW</small></a>";
             
            } else {
              $Extra="bgcolor=#CCCC99 id='calendarfull'";
              $bookinglink=" <a style='text-decoration:none' href='waitlist.php?scheduleid=$scheduleid'><br><small>WAITLIST</small></a>";
            }

            if($classfulldate<date("Y-m-d")) $bookinglink="";
            #seats left in each class
            $seatsleft=(maxseats-$bookings);    
                              # we're going to need some sort of 
                              $daydata .= 
                                          '<td>'.$caldate.'</td>
                                          <td width=150>'.$classname.'</td> 
                                          <td width=10>'.$daynight.'</td>
                                          <td width=100 align="center" width=250>'.$seatsleft.'seat/s left</td>
                                          <td align="center" width=25>$'.$classprice.'</td>
                                          <td width=50 align="center" width=250>'.$bookinglink.'</td>
                                   </tr>';
                        
                        
                        $rowspan++;
                        
                        }
   
               $rowdata='<tr align="left" class='.$rowtype.'rowspan='.$rowspan.'>
                                          <td>'.$caldayshow.'</td>';
                        
   
           }







function makecal($t, $result, $classdate, $noclasses){
    if($t+1==$noclasses){
        $classname=mysql_result($result,$t,"classname"); 
        echo "$classname"; 
    }else{
    $next=$t+1;
    $classdate2=mysql_result($result,$next,"classdate");
    $classname=mysql_result($result,$t,"classname");
    $test= "1st: $classdate, $classname";
    
    WHILE ($classdate==$classdate2)
    {
     $classname2=mysql_result($result,$next,"classname"); 
     $test.="<br>next: $classdate2, $classname2";    
    $next++;
    $classdate2=mysql_result($result,$next,"classdate"); 
    
    }
    
     
    echo $test;          
   
                    }
           }


?> 

