<?
 include("databaseconnect.php");
 
 $itemid = mysql_real_escape_string($_GET['itemid']);
 $classid = mysql_real_escape_string($_GET['classid']);

//echo "This is the id: $itemid";

#### THIS SEARCHES ON RECIPES ETC USING ITEMID #####
if ($itemid){
$sql="select * from `shop_items` where itemId = $itemid";
$rs=mysql_query($sql);
$num=mysql_num_rows($rs);

for($i=0;$i<$num;$i++) {
            $itemId=mysql_result($rs,$i,"itemId");
            $itemName=mysql_result($rs,$i,"itemName");
            $itemDesc=mysql_result($rs,$i,"itemDesc");
            $itemPrice=mysql_result($rs,$i,"itemPrice");
            $itemPic="http://www.spirithouse.com.au/images/products/".mysql_result($rs,$i,"itemPic");
         
           
            $print= utf8_encode($itemDesc); // encode any pesky apostrophes to display properly
           $result= "$print";
             }
  } 
 #### END OF RECIPE SEARCH #### 
  
  #### This grabs any classes with the classid passed from the previous page ####
  if ($classid) {         
          
  $sql2="select scheduleid, classname, classdescription, classseats, classprice, starttime, DATE_FORMAT(scheduledate, '%Y') as year,
          DATE_FORMAT(scheduledate, '%m') as month, DATE_FORMAT(scheduledate, '%d') as day,
          full, bookings, daynight from
          schedule left join classes
          on schedule.classid=classes.classid
          where classes.classid=$classid and scheduledate >'".date("Y-m-d")."'
          order by scheduledate";
           $result2=mysql_query($sql2);
         
           
           //$schedule=mysql_fetch_array($result2);
           $no2=mysql_num_rows($result2);
             //// TO DO - CATCH THE ERROR HERE IF THERE"S NO RESULT - like no class etc. ////
           
           if($no2>0){
           $classdescription=trim(mysql_result($result2,0,"classdescription"));
         
           $result.="<hr><br><ul><lh>$classdescription</lh><br><hr>";
           
           for($t2=0;$t2<$no2;$t2++) {

             $scheduleid    =trim(mysql_result($result2,$t2,"scheduleid"));
             $classname     =trim(mysql_result($result2,$t2,"classname"));
             $classdescription=trim(mysql_result($result2,$t2,"classdescription"));
             $classseats    =trim(mysql_result($result2,$t2,"classseats"));
             $classprice    =trim(mysql_result($result2,$t2,"classprice"));
             $bookings      =trim(mysql_result($result2,$t2,"bookings"));
             $class_year    =trim(mysql_result($result2,$t2,"year"));
             $class_month   =trim(mysql_result($result2,$t2,"month"));
             $class_day     =trim(mysql_result($result2,$t2,"day"));
             $full          =trim(mysql_result($result2,$t2,"full"));
             $starttime     =trim(mysql_result($result2,$t2,"starttime"));
             $daynight      =trim(mysql_result($result2,$t2,"daynight"));
             $seatsleft     =$classseats-$bookings;
           
           if($seatsleft==0) {
             $result.= "<li>$class_day / $class_month <strike> $classname </strike> &nbsp;(sorry - fully booked!)</li>";
           } else {
             $result.= "<li>$class_day / $class_month &nbsp;<a href='https://www.spirithouse.com.au/booking.php?scheduleid=$scheduleid'>$classname</a>&nbsp; $seatsleft seat(s) available</li>";
           
                   }
                        }
                        
             }ELSE{
             $result.="We're not running that class at this time,sorry. But you can click here to see our calendar of <a href='http://www.spirithouse.com.au/school>cookingschool classes</a>.";}           
          
          }
          
#### END OF THE CLASS SEARCH ####        





?>



<h4> <? echo"$itemName $classname"; ?> </h4>

<? echo $result; ?>
