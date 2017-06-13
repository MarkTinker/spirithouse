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
                $calyear=DATE(Y);
                 if($monthcontrol!=$monthtest){
                 $arr[]=$monthtest;
                 
                 
                 
                 
                 
                 
                 echo" this is the month number $monthtest";
                 
                 
                 
                 
                 }


                }
                #echo "<br>YEAR = $calyear";

           }

           foreach($arr as $value)
           { 
               
               #Grabs the data from the dbase for the MONTH



$sql="SELECT classes.classid, classname, classdescription, classprice, MONTH(scheduledate) as monthnumber, scheduleid,
     date_format(scheduledate, '%W') as dayname, DATE_FORMAT(scheduledate, '%d') as classday, date_format(scheduledate, '%d %M %y') as classdate, DATE_FORMAT(scheduledate, '%Y') as classyear, full, bookings, daynight FROM classes, schedule WHERE classes.classid=schedule.classid and MONTH(scheduledate) = $value and scheduledate>= now() order by scheduledate, daynight" ;
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

<p><h3>MONTH: <? echo getmonth($value).$Days_In_Month; ?></h3></p>


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

            for($t=0;$t<$noclasses;$t++)
            {
           $getclass=$classcount;             
           $scheduleid=mysql_result($result,$t,"scheduleid");
           $classid=mysql_result($result,$t,"classid");
           $classname=mysql_result($result,$t,"classname");
           $classday=mysql_result($result,$t,"classday");
           $dayname=mysql_result($result,$t,"dayname");
           $classdate=mysql_result($result,$t,"classdate");
           $classprice=mysql_result($result,$t,"classprice");
           $full=mysql_result($result,$t,"full");
           $bookings=mysql_result($result,$t,"bookings");
           $daynight=mysql_result($result,$t,"daynight");
           $classdescription=mysql_result($result,$t,"classdescription");
           $ClassNameLink = "<a href='#nogo' onclick=\"javascript:openWindow($classid,'$classname');\">$classname</a>";

         if($bookings<maxseats) {
             $seatsleft= (maxseats-$bookings);
              $bookinglink="<a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid'>BOOK NOW</a>";
            } else {
              $Extra="bgcolor=#CCCC99 id='calendarfull'";
              $bookinglink=" <a style='text-decoration:none' href='waitlist.php?scheduleid=$scheduleid'><br><small>WAITLIST</small></a>";
            }
      ############this will have to change if ever you do more than 2 classes a day      
      if($daynight==0) { $classtype="Day"; } else { $classtype="Night"; }
      ############
      ############ sets the color for the css to make weekends a different shade
      if($dayname=="Saturday" || $dayname=="Sunday") {
        $rowtype="we";
      } else {
        $rowtype="bg";
      }
      
        if($daynight==0) { $daynight="Day"; } else { $daynight="Night"; } 
      #############
        ?>
      <tr align="left" class='<?=$rowtype;?>'>
      <td><?=$dayname;?></td>
      <td><?=$classdate;?></td>
      
       <!-- this is the function I want to check to see if there's two classes on the same day -->
      <td width=300><? makecal($t, $result, $classdate, $noclasses);?></td>
      
      <td width=10><?=$daynight;?></td>
      <td width=100 align='center' width=250><?=$seatsleft;?> seat/s left</td>
      <td align='center' width=25>$<?=$classprice;?> </td>
      <td width=50 align='center' width=250><?=$bookinglink;?></td>
        </tr>
        <?
            }
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
  $t++;
}


?> 

