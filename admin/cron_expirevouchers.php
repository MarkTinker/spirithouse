<?
include("../databaseconnect.php");

  $sql="update vouchers set `voucherstatus`=4, `vouchernotes`=CONCAT(`vouchernotes`, 'Expired by system on ".date("d/m/y")."') where voucherstatus=1 and voucherexpiryyear=".date("Y")." and voucherexpirymonth<".date("m");
  mysql_query($sql);

  mail("abrierty@gmail.com", "expire vouchers cron", $sql);

?>