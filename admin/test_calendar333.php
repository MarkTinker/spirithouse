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
                 
                 
                 
                 
                 
                 
                 echo" this is the month number $calyear";
                 
                 
                 
                 
                 }


                }
                #echo "<br>YEAR = $calyear";

           }

           foreach($arr as $value)
           { 
               
               #Grabs the data from the dbase for the MONTH and starts the LOOP for each month



$sql="SELECT classes.classid, classname, classdescription, classprice, MONTH(scheduledate) as monthnumber, scheduleid, date_format(scheduledate, '%W') as dayname, DATE_FORMAT(scheduledate, '%d') as classday, date_format(scheduledate, '%d %M %y') as classdate, DATE_FORMAT(scheduledate, '%Y-%m-%d') as classfulldate, DATE_FORMAT(scheduledate, '%Y') as classyear, full, bookings, daynight FROM classes, schedule WHERE classes.classid=schedule.classid and MONTH(scheduledate) = $value and scheduledate >= now() order by scheduledate, daynight" ;
$result=mysql_query($sql);
$noclasses=mysql_num_rows($result);
 
            
   
            #works out how many days are in this month
            $classcount=0; 
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
            #### have to reset the variables that make up the calendar output to blank before each month - otherwise youwill keep adding to the variables and only getting the first result
            $Cal_Weeks_Days='';
            $firsttablerow='';
            $tabledata='';
            $classdata='';
            //$getclass=0;
            ####
            
            # START THE CALENDAR OUTPUT
            for($t=0; $t<$Days_In_Month; $t++)
            {
            //echo'this is the month number:'. $month;
            //echo'$getclass variable:'.$getclass;   
           #this is the date of the month
           $caldateshow = date("d F y", mktime(0,0,0,$month,$calday,$year));
           #this is the DAY of the month
           $caldayshow=date('l', strtotime($caldateshow));  
           
           #$noclasses is going to be more than the number of DAYS so remember that - $getclasses needs to be able to handle that
           $getclass=$classcount;             
           $scheduleid=mysql_result($result,$getclass,"scheduleid");
           $classid=mysql_result($result,$getclass,"classid");
           $classdate=mysql_result($result,$getclass,"classdate");
           $classname=mysql_result($result,$getclass,"classname");
           $classday=mysql_result($result,$getclass,"classday");
           $classfulldate=mysql_result($result,$getclass,"classfulldate");
           $classprice=mysql_result($result,$getclass,"classprice");
           $full=mysql_result($result,$getclass,"full");
           $daynight=mysql_result($result,$getclass,"daynight"); 
           $bookings=mysql_result($result,$getclass,"bookings");
           $classdescription=mysql_result($result,$getclass,"classdescription");
           $ClassNameLink = "<a href='#nogo' onclick=\"javascript:openWindow($classid,'$classname');\">$classname</a>";
            
            #gives a name to the daynight variable - has to be changed if there's more than two classes a day
            if($daynight==0) { $daynight="Day"; } else { $daynight="Night"; }  
        
         //echo $classcount; 
         
        ####################################################################  
        #Here's were we MATCH a date with a CLASS no match then print a blank row
        #####################################################################
         $rowspan=1;
         
         $firsttablerow= '<tr align="left" class='.$rowtype.'><td rowspan=';// set up the rowspan NOW if one class then span will be one     
         if($classday==$calday){
               
            # we have a match, let's start with the first class 
            # to cope with the rowspan being more than one we're going to add it at the end of this IF loop-
             $classdata .= '>'.$caldayshow.$calday.$classday.' first td</td>
                      <td>'.$caldateshow.'</td>
                      <td width=150>'.$classname.'</td> 
                      <td width=10>'.$getclass.'getclass'.'</td>
                      <td width=100 align="center" width=250>'.$rowspan.'rowspan</td>
                      <td align="center" width=25>classcount'.$classcount.'</td>
                      <td width=50 align="center" width=250>and nada</td>
                        </tr>';
             $getclass++; 
             $classday2=mysql_result($result,$getclass,"classday"); // getting the next classday in the array to see if there's another class on that day
            
            #now let's see if there's other classes on that day using the WHILE loop 
            while ($classday2==$calday){
                       $rowspan++;                         
                      $classname=mysql_result($result,$getclass,"classname");  
                      #shortened the TDs to make a rowspan thingy
                      $classdata .= '<tr align="left" class='.$rowtype.'>
                      <td>'.$caldateshow.'</td>
                      <td width=150>'.$classname.'</td> 
                      <td width=10>'.$getclass.'getclass'.'</td>
                      <td width=100 align="center" width=250>'.$classcount.'classcount</td>
                      <td align="center" width=25>$nada</td>
                      <td width=50 align="center" width=250>and nada</td>
                        </tr>';
         
                    ##########remember that you've moved UP the record set here so when you go back to normal loop you must make sure that getclass is going to be equal to this getclass or you'll go backwards - getclass = classcount     
                     $getclass++;
                    $classday2=mysql_result($result,$getclass,"classday"); 
                   
                        }
            
          # THis is where we assemble all the class info for the IF loop above. We need the first two variables to set up the format for the table  
          $tabledata.=$firsttablerow.$rowspan.$classdata;
          $classcount=2;
         
         }else{  #ELSE No class on this day so output a blank row
      
      $tabledata .='<tr align="left" class='.$rowtype.'><td >'.$caldayshow.$calday.$classday.'</td>
      <td>'.$caldateshow.'</td>
      <td width=150>NO CLASS TODAY '.$Days_In_Month.' and $t'.$t.'</td> 
      <td width=10>'.$noclasses.'noclasses'.'</td>
      <td width=100 align="center" width=250>'.$classcount.'classcount</td>
      <td align="center" width=25>$nada</td>
      <td width=50 align="center" width=250>and nada</td>
        </tr>';
         
            }
         
         ##############################################################################################
         
         # add the variables that make up the calendar
         $Cal_Weeks_Days=$tabledata; 
           
          
          
        //increment the day so we can run through the records again
        $calday++;
            }//ends the FOR loop that goes through the days
                
                
                
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





?> 

