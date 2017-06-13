<?php

// THIS FILE GETS THE DATA FROM A CSV AND UPDATES THE MAIL LIST
include("databaseconnect.php");
$csv = array_map('str_getcsv', file('data.csv'));

$result = count($csv);
echo "this many rows: $result <br><br>";

for ($row = 0; $row < $result; $row++) {

	$id = $csv[$row][1] - 30000;
	$email = $csv[$row][0];
 	
 	if($id > 1){// the client entered an id so we can move ahead
 	
 	 echo "<br>".$id."&nbsp;";
 	 echo $csv[$row][0];
 	 
 	 $sql="select * from `mail_list` where `mailid`=$id";
    	$rs=mysql_query($sql);
    	$no=mysql_num_rows($rs);
    	if($no>0) {
       	$surname =trim(mysql_result($rs,0,'mailSurname'));
    	echo " - $surname ";
    	
    	// ready to update - unescape line 25 to make the update work.
    		$sql2="update `mail_list` set  `mailEmail`='$email' where `mailid`='$id'";  
    		mysql_query($sql2);
    	
    	
    	echo $sql2;
    	}else{
    	//the voucher id doesn't match a record because they entered it incorrectly.
    	echo "NO MATCH";
    	}
    	//echo $sql;
 	 
 	 
 	}else{
 	
 	// the client didn't enter an ID so do nothing
 	}
   
  }


//print("<pre>".print_r($csv,true)."</pre>");


//print_r ($csv);

?>