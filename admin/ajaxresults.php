<?
include("../databaseconnect.php");
include("../includes/class_functions.inc.php");

$vouchers = htmlspecialchars($_GET['vchr-find']);

 
  $vouchers=str_replace("  ", " ", $vouchers);
  $vouchers=str_replace(" ", "|", $vouchers);
  $vouchers=str_replace(",", "|", $vouchers);
  $vouchers=str_replace("/n", "|", $vouchers);
  $vouchers=str_replace("\n", "|", $vouchers);
  $vouchers=str_replace("/r", "|", $vouchers);
  $vouchers=str_replace("\r", "|", $vouchers);
  $vouchers=str_replace("/n/r", "|", $vouchers);
  $vouchers=str_replace("\n\r", "|", $vouchers);

  $vouchers_used=explode("|",$vouchers);
  $totalvoucheramount=0;

	$vouchercount=sizeof($vouchers_used);
  echo "this is voucher count:".$vouchercount."<br>";
  $vouchertext="";
  if(sizeof($vouchers_used)>0) {
  
    for($i=0;$i<$vouchercount;$i++) {
   
   
      
      //if(strlen($vouchers_used[$i])>0) {
        $sql="select * from vouchers where vouchernumber=".quote_smart($vouchers_used[$i]);
        $rs=mysql_query($sql);
        $no=mysql_num_rows($rs);
        if($no>0) {
          $voucherstatus=mysql_result($rs,0,"voucherstatus");
          $voucheramount=mysql_result($rs,0,"voucheramount");
          $vouchernumber=mysql_result($rs,0,"vouchernumber");
           
           
       //echo "<br>this is the value of i: $vouchernumber";
            if($voucherstatus==1) {
            $vouchertext.= "<span style='color:green'>Voucher #".$vouchernumber.": $".number_format($voucheramount)." hasn't been used yet</span><br>";
           
           //return $vouchernumber;
          } else {
           $vouchertext.="<span style='color:red'>Voucher #".$vouchernumber.": $".number_format($voucheramount)." has been used</span><br>";
            //return $vouchernumber;
          }

        } else {
        $vouchertext.= "<span style='color:blue'>Voucher #".$vouchers_used[$i].":Does Not Exist<br> </span>";
        }
          

      
    }
  
   echo "$vouchertext";
   //return $vouchertext;
  }
  
  
  
  
  
  
  
            

   
  

