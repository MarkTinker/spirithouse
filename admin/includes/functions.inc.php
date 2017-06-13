<?php
function DateSelectBox($inputname, $defaultd, $defaultm, $defaulty, $widthd, $widthm, $widthy) {

  if(strlen($defaulty)==0) { $defaulty=date("Y"); }
  if(strlen($defaultm)==0) { $defaulty=date("m"); }
  if(strlen($defaultd)==0) { $defaulty=date("d"); }

  echo "<select name='".$inputname."_d' style='width: ".$widthd."px'>";
  for($i=1;$i<32;$i++) {
     echo "<option ";
     if($i==$defaultd) { echo " SELECTED "; }
     echo "value='$i'>$i</OPTION>";
  }
  echo "</select> ";

  echo "<select name='".$inputname."_m' style='width: ".$widthm."px'>";
  for($i=1;$i<13;$i++) {
     echo "<option ";
     if($i==$defaultm) { echo " SELECTED "; }
     echo "value='$i'>".monthname($i)."</OPTION>";
  }
  echo "</select> ";

  echo "<select name='".$inputname."_y' style='width: ".$widthy."px'>";
  for($i=2013;$i<2018;$i++) {
     echo "<option ";
     if($i==$defaulty) { echo " SELECTED "; }
     echo "value='$i'>$i</OPTION>";
  }
  echo "</select>";
}

function monthname($monthnum) {

  switch($monthnum) {
  case 1: return "January";break;
  case 2: return "February";break;
  case 3: return "March";break;
  case 4: return "April";break;
  case 5: return "May";break;
  case 6: return "June";break;
  case 7: return "July";break;
  case 8: return "August";break;
  case 9: return "September";break;
  case 10: return "October";break;
  case 11: return "November";break;
  case 12: return "December";
  }
}

function get_class_booking_details($scheduleid) {
  $details=array();
  $sql="select * from bookings where `scheduleid`=".$scheduleid;
  $result=mysql_query($sql);
  $no=mysql_num_rows($result);
  if($no!=0) {
    for($i=0;$i<$no;$i++) {
      $details[$i]['nametags']=mysql_result($result,$i,"nametags");
      $details[$i]['bookingname']=mysql_result($result,$i,"firstname")." ".mysql_result($result,$i,"lastname");
      $details[$i]['email']=mysql_result($result,$i,"email");
      $details[$i]['phone']=mysql_result($result,$i,"phone");
      $details[$i]['total']=mysql_result($result,$i,"total");
      $details[$i]['total_by_cc']=mysql_result($result,$i,"total_by_cc");
      $details[$i]['total_by_gc']=mysql_result($result,$i,"total_by_gc");
      $details[$i]['giftcertificates']=mysql_result($result,$i,"giftcertificates");
      $details[$i]['giftcertificatevalue']=mysql_result($result,$i,"giftcertificatevalue");
      $details[$i]['notes']=mysql_result($result,$i,"notes");
    }
  }
  return $details;
}
?>