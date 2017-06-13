<? 

include("../databaseconnect.php");
//include("includes/auth.inc.php");

$discount_price=0;
if(isset($_GET['discount_price']) && is_numeric($_GET['discount_price']) && $_GET['discount_price']>0 ) $discount_price=$_GET['discount_price'];

if(isset($_GET['scheduleid'])) {

  $scheduleid=$_GET['scheduleid'];
  
  	$sql="select discount from schedule where scheduleid=$scheduleid limit 1";
  	$result=mysql_query($sql);
	$discount=trim(mysql_result($result,0,"discount"));
	
	##### if discount is flagged let's remove it from the last minute rate program ######
	If($discount==1){
	 	$sql2="update schedule set discount=0 where scheduleid=$scheduleid";
 		//echo $sql2;
  		//exit();
  		$result2=mysql_query($sql2);
  		
	}else{
  		#### no discount flagged so let's add the class to the last minute rate program #####
  		if($discount_price>0) {  		
  			$sql2="update schedule set discount=1, discount_price=".quote_smart($discount_price)." where scheduleid=$scheduleid";
  		} else {
  			$sql2="update schedule set discount=1 where scheduleid=$scheduleid";
  		}
 		//echo $sql2;
  		//exit();
  		$result2=mysql_query($sql2);
  
  		}
  
  }
  
  ?>
