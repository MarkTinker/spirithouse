<?php

include("databaseconnect.php");

//if we got something through $_POST
	$end_result='';
	
	if (isset($_POST['search'])) {
	 
	    // never trust what user wrote! We must ALWAYS sanitize user input
	    $word = mysql_real_escape_string($_POST['search']);
	    
	      
	      // Let's check the classes out to see if they have a recipe with the word in it.  
	    $sql = "select classid, classname, classdescription from `classes` where classdescription LIKE '%$word%' order by classname";
	    //echo "<li class ='title'>$sql</li>";
	    $data=mysql_query($sql);
		$no=mysql_num_rows($data);
		

		
			    // search the RECIPES and SHOP ITEMS ETC
	    $sql2 = "select * from shop_items, shop_categoryxref as C, shop_categorytable as T where itemName LIKE '%$word%' and shop_items.itemId = C.itemId and C.categoryId=T.categoryId order by T.categoryId, itemName";
	    // get results
	    //$row = $db->select_list($sql);
   
	    //$showrow=count($row);    
	    $data2=mysql_query($sql2);
		$no2=mysql_num_rows($data2);
	    
	    if ($no2>0){
	    for($t=0;$t<$no2;$t++)	{
		$itemid=(mysql_result($data2,$t,itemId));
		$result=(mysql_result($data2,$t,itemName));
		$categoryname=(mysql_result($data2,$t,categoryName));
		
		if($oldcategory<>$categoryname) {
			$end_result.="<li class ='title'><h3>$categoryname</h3></li>";
		}
		
		
		// we will use this to bold the search word in result
	            $bold= '<span class="teal">' . $word . '</span>';
	            $end_result.= "<P><a class='example5 recipe' href='recipe-results.php?itemid=$itemid'>".str_ireplace($word, $bold, $result)."</a></P>";
	    		$oldcategory = $categoryname;
	    
	    
	    	}
	    	//echo  $end_result;
	    		
	    		}else{ 
	    		
	    $end_result.= "<li>No results found in recipes or tips.</li>";
	    
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
	 }   
	    

	?>
	

