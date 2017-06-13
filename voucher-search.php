<?php

include("databaseconnect.php");

//if we got something through $_POST
	$end_result='';
	
	if (isset($_POST['search'])) {
	 
	    // never trust what user wrote! We must ALWAYS sanitize user input
	    $find = mysql_real_escape_string($_POST['search']);
	    
	      
	      


$voucherstatusflag=0;
//// SEARCHING FOR VOUCHER NUMBERS OR NAMES ETC.


  $sql = "select * from vouchers WHERE vouchers.vouchernumber LIKE \"".$find."%\" OR vouchers.firstname LIKE \"".$find."%\" OR vouchers.lastname LIKE \"".$find."%\" OR vouchers.fromname LIKE \"%".$find."%\" OR vouchers.email LIKE \"".$find."%\" OR vouchers.recipientname LIKE \"".$find."%\" ORDER BY voucherstatus ASC"; 
	      
	      
	  //$result = mysql_query($sql);
  	//$no = mysql_num_rows($result);
	      

   
	    //using some old search code hence the 2s at the end of the data etc    
	    $result=mysql_query($sql);
		$no=mysql_num_rows($result);
	    
	    if ($no>0){
	    for($t=0;$t<$no;$t++)	{
		//$itemid=(mysql_result($result,$t,itemId));
		//$result=(mysql_result($result,$t,itemName));
		//$categoryname=(mysql_result($result,$t,categoryName));
		
		$voucherid=trim(mysql_result($result,$t,voucherid));

    $firstname=trim(mysql_result($result,$t,firstname));
    $lastname=trim(mysql_result($result,$t,lastname));
    $from=trim(mysql_result($result,$t,fromname));
    $recipient=trim(mysql_result($result,$t,recipientname));
    $voucheramount=trim(mysql_result($result,$t,voucheramount));
    $voucherstatus=trim(mysql_result($result,$t,voucherstatus));
    $vouchernumber=trim(mysql_result($result,$t,vouchernumber));
    $vouchernotes=trim(mysql_result($result,$t,vouchernotes));
    $voucherexpirymonth=trim(mysql_result($result,$t,voucherexpirymonth));
    $voucherexpiryyear=trim(mysql_result($result,$t,voucherexpiryyear));
			
			
		if($voucherstatus <> $voucherstatusflag AND $voucherstatus < 2){
		$end_result.="<li class ='title'><h4>ACTIVE VOUCHERS</h4></li>";
		$voucherstatusflag=$voucherstatus;
		} 
		
		if($voucherstatusflag==1 AND $voucherstatus > 1){
		$end_result.="<li class ='title'><h4>EXPIRED or USED etc.</h4></li>";
		$voucherstatusflag=3;
		} 
		
		if($voucherstatusflag==0 AND $voucherstatus > 1){
		$end_result.="<li class ='title'><h4>EXPIRED or USED etc.</h4></li>";
		$voucherstatusflag=3;
		} 
		
		switch($voucherstatus) {
				case 1: $voucherstat = 'Active';break;
				case 2: $voucherstat = 'Used';break;
				case 3: $voucherstat = 'Class Booking';break;
				case 4:	$voucherstat = 'Expired';break;
				case 5:	$voucherstat = 'Cancelled';
				}
	
		$end_result.= "<li class ='title'><h3>$vouchernumber - &#36;$voucheramount - <span class='teal'>$voucherstat</span></h3></li>
		<ul><li> To: $recipient</li><li>From: $from</li> <li>Purchased By: $lastname, $firstname</li><li class='teal'>Exp:  $voucherexpirymonth/$voucherexpiryyear</li>
		<li><p><small>$vouchernotes</small></p></li></ul>";

	    
	    
	    	}
	    	//echo  $end_result;
	    		
	    		}else{ 
	    		
	    $end_result.= "<li>No voucher found. $find</li>";
	    
	    				}
		
		 
		 //*********HIDE THE COOKING CLASSES FOR THE MOMENT **********//
		 /* Find classes which match.
		 if ($no){
	    	
	    		$end_result.="<li class ='title'><h3>Cooking Classes with <span class='found'>$word</span> in the recipes</h3></li>";
	    		for($t=0;$t<$no;$t++)	{
				$classid=(mysql_result($data,$t,classid));
				$classname=(mysql_result($data,$t,classname));
				$classdescription=(mysql_result($data,$t,classdescription));
				
				
				// we will use this to bold the search word in result
	            $bold= '<span class="found">' . $word . '</span>';
	            $end_result.= "<li><a class='example5 recipe' href='search-results.php?classid=$classid'>".str_ireplace($word, $bold, $classname)."</a></li>";

	    	}
	    	//echo  $end_result;
	    		
	    		}
		
		*/
	    
	    
	    
	    
	    
	    

	    
	 
	 echo $end_result;
	 $voucherstatusflag=0;
	 }   
	    

	?>
	

