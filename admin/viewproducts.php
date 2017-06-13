<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();

//$sql="select * from stockists order by stockiststate, stockistname" ;
//$result=mysql_query($sql);
//$no=mysql_num_rows($result);

?>


<!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script> -->

						
<p><h3>View products</h3></p>
<p>:: <a href="?action=add_item"><b>Add a NEW product</b></a> ::</p>
<?
  
  ##### NEW ITEM - Submit button has been pressed now we add the item with the relevant category ###########
  if(isset($_POST['submit33'])) {
    $sql="insert into `shop_items` (itemName, itemDesc, itemPrice, itemWeight, itemPic, itemMsmnt) values ('".$_POST['name']."', '".$_POST['desc']."', '".$_POST['price']."', '".$_POST['weight']."', '".$_POST['pic']."', '".$_POST['msmnt']."')";
 		$rs=mysql_query($sql);
    #echo $sql;
    //find the last inserted item
    $sql2="select `itemId` from `shop_items` where `itemId`=LAST_INSERT_ID()";
    $rs2=mysql_query($sql2);
    $no2=mysql_num_rows($rs2);
    if($no2>0) {
      $itemId=trim(mysql_result($rs2,0,'itemId'));
      
      // TO DO - make a FOR loop here and do the insert for the values in the category table - make it a function to share with the update?? //
      if($_POST['1']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (1, '$itemId')"; mysql_query($sql); }
      if($_POST['2']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (2, '$itemId')"; mysql_query($sql); }
      if($_POST['3']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (3, '$itemId')"; mysql_query($sql); }
      if($_POST['5']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (5, '$itemId')"; mysql_query($sql); }
      if($_POST['6']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (6, '$itemId')"; mysql_query($sql); }
      if($_POST['11']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (11, '$itemId')"; mysql_query($sql); }
      if($_POST['12']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (12, '$itemId')"; mysql_query($sql); }
      if($_POST['13']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (13, '$itemId')"; mysql_query($sql); }
      if($_POST['14']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (14, '$itemId')"; mysql_query($sql); }
      if($_POST['15']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (15, '$itemId')"; mysql_query($sql); }
    }

    $action="";
  }

  ### UPDATING AN ITEM - the same as making a NEW item except we're updating ###
  if(isset($_POST['submit11'])) {
    $sql="update `shop_items` set `itemName`='".$_POST['name']."', `itemDesc`='".$_POST['desc']."', `itemPrice`='".$_POST['price']."', `itemWeight`='".$_POST['weight']."', `itemPic`='".$_POST['pic']."', `itemMsmnt`='".$_POST['msmnt']."' where `itemid`='".$_POST['itemid']."'";
    mysql_query($sql);
    #echo $sql."<br>";
    #I think this next code deletes all the category checkboxes to allow the new values to be inserted if they've been updated
    $sql="delete from `shop_categoryxref` where `itemid`='".$_POST['itemid']."'";
    mysql_query($sql);
    #echo $sql."<br>";
    if($_POST['1']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (1, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['2']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (2, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['3']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (3, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['5']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (5, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['6']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (6, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['11']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (11, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['12']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (12, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['13']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (13, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['14']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (14, '".$_POST['itemid']."')"; mysql_query($sql); }
    if($_POST['15']=="on") { $sql="insert into `shop_categoryxref` (`categoryId`, `itemId`) VALUES (15, '".$_POST['itemid']."')"; mysql_query($sql); }
    #echo $sql."<br>";
    $action="";
  }

  switch($_GET['action'])
	{
  case "unfeature": toggle_feature(0,$_GET['itemid']); Showpage(); break;
  case "feature": toggle_feature(1,$_GET['itemid']); Showpage(); break;
  case "show": toggle_hide(1,$_GET['itemid']); Showpage(); break;
  case "unshow": toggle_hide(2,$_GET['itemid']); Showpage(); break;
	case "add_item":
	{
		additem($name, $desc, $price, $weight, $pic, $msmnt);
		break;
	}
  case "edit":
	{
		edititem($_GET['itemId']);
		break;
        $action='';
	}
  case "delete": deleteitem($_GET['itemId']); Showpage(); break;

	default:
	{
		Showpage();
	}

  }


  function toggle_hide($toggle,$itemId) {
    $sql="update `shop_items` set `show`=$toggle where `itemId`='$itemId'";
    mysql_query($sql);
  }
  function toggle_feature($toggle,$itemId) {
    $sql="update `shop_items` set `featured`=$toggle where `itemId`='$itemId'";
    mysql_query($sql);
  }

  
  ### DELETE THE ITEM ###
  function deleteitem($itemId) {
    $sql="delete from `shop_items` where `itemId`='$itemId'";
    mysql_query($sql);
    $sql="delete from `shop_categoryxref` where `itemId`='$itemId'";
    mysql_query($sql);
  }

	### Form to EDIT THE ITEM ###
  function edititem($itemid) {
    $sql="select * from `shop_items` where `itemid`='$itemid'";
    $rs=mysql_query($sql);
    $no=mysql_num_rows($rs);
    if($no>0) {
      $itemName=trim(mysql_result($rs,0,'itemName'));
      $itemDesc =trim(mysql_result($rs,0,'itemDesc'));
      $itemPic=trim(mysql_result($rs,0,'itemPic'));
      $itemPrice=trim(mysql_result($rs,0,'itemPrice'));
      $itemWeight=trim(mysql_result($rs,0,'itemWeight'));
      $itemMsmnt=trim(mysql_result($rs,0,'itemMsmnt'));
      $featured=trim(mysql_result($rs,0,'featured'));

      $form="<h2>Update product details</h2>";

      
      $form.="<form name='theForm987' action='' method='post'>";
      $form.="<table><tr><td>Name:</td>";
			$form.="<td><input type='text' name='name' value='".$itemName."' size='50'></td>";
			$form.="<tr><td>Desc:</td>";
			$form.="<td> <textarea name='desc' cols='80' rows='20'  class='widgEditor'>".$itemDesc."</textarea></td>";
			$form.="<tr><td>Price:</td><td> <input type='text' name='price' value='".$itemPrice."'size='6'></td>";
			$form.="<tr><td>Weight:</td><td> <input type='text' name='weight' value='".$itemWeight."'size='6'> </td></tr>";
      $form.="<tr><td>Image:</td><td> <input type='text' name='pic' value='".$itemPic."'size='80'> </td></tr>";
      $form.="<tr><td>Unit Net Weight:</td><td> <input type='text' name='msmnt' value='".$itemMsmnt."'size='5'> </td></tr>";
      
          $form.="<tr><td>Cats:</td><td>
      <table cellspacing=10>";
      // Need to get the SHOW categories from the category table and use that to generate the names for the fields etc of the following table. 
        $sql="SELECT * FROM `shop_categorytable`";
        $rs=mysql_query($sql);
        $no=mysql_num_rows($rs);
         for($t=0;$t<$no;$t++)    { 
                $categoryId=trim(mysql_result($rs,$t,'categoryId'));
                $categoryName =trim(mysql_result($rs,$t,'categoryName'));  

      // end of this crazy test  
   
      $form.="<tr><td>$categoryName</td><td><input type='checkbox' ".is_in_category($categoryId, $itemid)." name='$categoryId'></td></tr>";
      
       }
      
      $form.="</table></td></tr>";

      $form.="<input type='hidden' name='itemid' value='$itemid'>";
			$form.="<tr><td></td><td><input type='submit' name='submit11' value='update item'></td></tr>";
			$form.="</table></form>";
			echo($form);
    }

  }



	### ADD AN ITEM ###
 	function additem($name, $desc, $price, $weight, $msmnt)
	{
   		
   		// this if statement makes sure you enter at least a name or it refreshes the form
       if(!$name) {
      	$form="<h2> Add NEW item to database</h2>";
				$form.="<form action=''  method='post'>";
        $form.="<table><tr><td>Name:</td>";
  			$form.="<td><input type='text' name='name' size='50'></td>";
				$form.="<tr><td>Desc:</td>";
  			$form.="<td  style= \"background-color: #fff;\"> <textarea name='desc' cols='80' rows='15'></textarea></td>";
				$form.="<tr><td>Price:</td><td> <input type='text' name='price' size='6'></td>";
				$form.="<tr><td>Weight:</td><td> <input type='text' name='weight' size='6'> </td></tr>";
        $form.="<tr><td>Image:</td><td> <input type='text' name='pic' size='80'> </td></tr>";
        $form.="<tr><td>Unit Net Weight:</td><td> <input type='text' name='msmnt' size='5'> </td></tr>";
        $form.="<tr><td>Cats:</td><td>
        <table cellspacing=10>";
          // Need to get the SHOW categories from the category table and use that to generate the names for the fields etc of the following table. 
        $sql="SELECT * FROM `shop_categorytable`";
        $rs=mysql_query($sql);
        $no=mysql_num_rows($rs);
         for($t=0;$t<$no;$t++)    { 
                $categoryId=trim(mysql_result($rs,$t,'categoryId'));
                $categoryName =trim(mysql_result($rs,$t,'categoryName'));  

      // end of this crazy test  
   
      $form.="<tr><td>$categoryName</td><td><input type='checkbox' name='$categoryId'></td></tr>";
      
       }
        $form.="</table></td></tr>";

				$form.="<tr><td></td><td><input type='submit' name='submit33' value='add item'></td></tr>";
				$form.="</table></form>";
  			echo($form);
    	 }
  }
  
  
  ### SHOW THE PAGE  (this shows the category:used in teh first WHERE clause below -  T.show='1') AND ###
 function Showpage() {
	$sql="select * from shop_items as I, shop_categoryxref as C, shop_categorytable as T where  I.itemId = C.itemId and C.categoryId=T.categoryId order by T.categoryId, I.itemName";
	$result=mysql_query($sql);
	$no=mysql_num_rows($result);

  echo"<table>";

  for($t=0;$t<$no;$t++)	{
		$itemid=(mysql_result($result,$t,itemId));
		$itemname=(mysql_result($result,$t,itemName));
		$itemprice=(mysql_result($result,$t,itemPrice));
		$categoryname=(mysql_result($result,$t,categoryName));
    $featured=(mysql_result($result,$t,featured));
    $show=(mysql_result($result,$t,show));
    if($featured==1) { $bgcolor=' style="background-color:#efefef;" '; } else { $bgcolor=""; }
    if($show==0) { $bgcolor=' style="color:#999" '; } else { $bgcolor=""; }

		if($oldcategory<>$categoryname) {
			echo "<Tr valign='top'><Td colspan=5><h3><b>$categoryname</h3></td></tr>";
		}

		echo"<tr $bgcolor><td height='25'>$itemname</td>";

		echo" <td height='25'>$itemprice</td>";

		echo"<td height='25'><a href='?action=edit&itemId=$itemid'>Edit Item</a></td>";

		echo"<td height='25'><a href='?action=delete&itemId=$itemid'>Remove</a></td>";

    if($featured==1) {
      echo "<td><a href='?action=unfeature&itemid=$itemid'>Unfeature</a></td>";
    } else {
      echo "<td><a href='?action=feature&itemid=$itemid'>Feature!</a></td>";
    }
    if($show==1) {
      echo "<td><a href='?action=unshow&itemid=$itemid'>Hide</a></td>";
    } else {
      echo "<td><a href='?action=show&itemid=$itemid'>Show</a></td>";
    }
    echo "</tr>";


		$oldcategory = $categoryname;
  }
	echo "</table>";

  }

foot();


function is_in_category($catid, $itemid) {
	$sql="select `catxrefId` from `shop_categoryxref` where `categoryId`='$catid' and `itemId`='$itemid'";
	$result=mysql_query($sql);
	$no=mysql_num_rows($result);
  if($no>0) { return " checked " ; } else { return ""; }
}
?>