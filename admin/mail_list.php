<?
include("head.php");
include("../databaseconnect.php");
include("includes/auth.inc.php");

head();

$sql="select * from mail_list order by mailSurname" ;
$result=mysql_query($sql);
$no=mysql_num_rows($result);

?>
<h3>New Sign Up for eMailing List</h3>
<p>Use this form from now on to add people to the new list:</p>

<form action="http://spirithouse.createsend.com/t/y/s/aihkdk/" method="post">
    <p>
        <label for="fieldmtktyl">First Name</label><br />
        <input id="fieldmtktyl" name="cm-f-mtktyl" type="text" />
    </p>
    <p>
        <label for="fieldEmail">Email</label><br />
        <input id="fieldEmail" name="cm-aihkdk-aihkdk" type="email" required />
    </p>
    <p>
        <label for="fieldmtktyj">Post Code</label><br />
        <input id="fieldmtktyj" name="cm-f-mtktyj" type="text" />
    </p>
    <p>
        <button type="submit">Subscribe</button>
    </p>
</form>


<p><h3>View Mailing List</h3></p>
<p>:: <a href="?action=add_person"><b>Add NEW person to Snail Mail List</b></a> ::</p>
<?
  // ADDING NEW PEOPLE WORKS
  if(isset($_POST['submit33'])) {
    $sql="insert into `mail_list` (mailTitle, mailFirstname, mailSurname, mailAddress1, mailAddress2, mailCity, mailPostcode, mailState, mailEmail) values ('".$_POST['title']."', '".$_POST['name']."', '".$_POST['surname']."', '".$_POST['add1']."', '".$_POST['add2']."', '".$_POST['city']."', '".$_POST['postcode']."', '".$_POST['state']."', '".$_POST['email']."')";
 		$rs=mysql_query($sql);
    
    echo $_POST['surname']." added to the database. >";

    $action="";
  }

  #UPDATING MAIL LIST WORKS
  if(isset($_POST['submit11'])) {
    $sql="update `mail_list` set `mailTitle`='".$_POST['title']."', `mailFirstname`='".$_POST['name']."', `mailSurname`='".$_POST['surname']."', `mailAddress1`='".$_POST['add1']."', `mailAddress2`='".$_POST['add2']."', `mailCity`='".$_POST['city']."', `mailPostcode`='".$_POST['postcode']."', `mailState`='".$_POST['state']."', `mailEmail`='".$_POST['email']."' where `mailid`='".$_POST['itemid']."'";  
    mysql_query($sql);
    echo $sql."<br>";
   
    $action="";
  }

  switch($_GET['action'])
	{
	case "add_person":
	{
		addperson(title, $name, $surname, $add1, $add2, $citt, $state, $code);
		break;
	}
	
	case "delete":
	{
		deleteitem($_GET['itemId']);
		showpage(); 
		break;
	}
	
  case "edit": edititem($_GET['itemId']);showpage(); break;


	default:
	{
		Showpage();
	}

  }


  function deleteitem($itemId) {
    $sql="delete from `mail_list` where `mailid`='$itemId'";
    //echo "this is the action: $sql";
    mysql_query($sql);

  }

  // this function actually edits the people inthe mailing list database
  function edititem($itemid) {
    $sql="select * from `mail_list` where `mailid`='$itemid'";
    $rs=mysql_query($sql);
    $no=mysql_num_rows($rs);
    if($no>0) {
        $mailid=trim(mysql_result($rs,0,'mailid')); 
      $title=trim(mysql_result($rs,0,'mailTitle'));
      $firstname =trim(mysql_result($rs,0,'mailFirstname')); 
      $surname =trim(mysql_result($rs,0,'mailSurname'));
      $add1=trim(mysql_result($rs,0,'mailAddress1'));
       $add2=trim(mysql_result($rs,0,'mailAddress2'));
       $city=trim(mysql_result($rs,0,'mailCity'));
       $code=trim(mysql_result($rs,0,'mailPostcode'));
       $state=trim(mysql_result($rs,0,'mailState'));
       $email=trim(mysql_result($rs,0,'mailEmail'));    
    
      echo $add1;
      $form="<h2>Update CONTACT details</h2>";
      $form.="<form name='theForm987' action='' method='post'>";
      
      $form.="<table><tr><td>Title</td><td><input type='text' name='title' value='".$title."' size='50'></td>";
                $form.="<tr><td>First Name :</td><td> <input type='text' name='name' value='".$firstname."' size='15'></td>";  
                $form.="<tr><td>Surname :</td><td> <input type='text' name='surname'  value='".$surname."' size size='20'></td>";
                $form.="<tr><td>Address 1</td><td> <textarea name='add1' cols='30' rows='2'>".$add1."</textarea></td>";
                $form.="<tr><td>Address 2</td><td> <textarea name='add2' cols='30' rows='2'>".$add2."</textarea></td>";  
                $form.="<tr><td>Town/City:</td><td> <input type='text' name='city'  value='".$city."' size='25'> </td></tr>";
                $form.="<tr><td>Postcode:</td><td> <input type='text' name='postcode' value='".$code."' size='10'> </td></tr>";
                $form.="<tr><td>State:</td><td> <input type='text' name='state'  value='".$state."' size' size='10'> </td></tr>";
                $form.="<tr><td>Email:</td><td> <input type='email' name='email'  value='".$email."' size' size='50'> </td></tr>";
                $form.="<input type='hidden' name='itemid' value='$mailid'>";
			$form.="<tr><td></td><td><input type='submit' name='submit11' value='update item'></td></tr>";
			$form.="</table></form>";
			echo($form);
    }

  }

 	function addperson($title, $name, $surname, $add1, $add2, $city, $state, $postcode )
	{
   		// this if statement makes sure you enter at least a name or it refreshes the form
       if(!$name) {
      	$form="<h2> Add NEW person to database</h2>";
		$form.="<form action=''  method='post'>";
        $form.="<table><tr><td>Title:</td>";
  			$form.="<td><input type='text' name='title' size='50'></td>";
			    $form.="<tr><td>First Name :</td><td> <input type='text' name='name' size='50'></td>";  
				$form.="<tr><td>Surname :</td><td> <input type='text' name='surname' size='50'></td>";
                $form.="<tr><td>Address 1:</td><td> <textarea name='add1' cols='50' rows='2'></textarea></td>";
                $form.="<tr><td>Address 2:</td><td> <textarea name='add2' cols='50' rows='2'></textarea></td>";  
				$form.="<tr><td>Town/City:</td><td> <input type='text' name='city' size='25'> </td></tr>";
                $form.="<tr><td>Postcode:</td><td> <input type='text' name='postcode' size='10'> </td></tr>";
                $form.="<tr><td>State:</td><td> <input type='text' name='state' size='10'> </td></tr>";
                $form.="<tr><td>Email:</td><td> <input type='email' name='email' size='50'> </td></tr>";
				$form.="<tr><td></td><td><input type='submit' name='submit33' value='add person'></td></tr>";
				$form.="</table></form>";
  			echo($form);
    	 }
  }
  //SHOWS THE MAIN PAGE
 function Showpage() {
$this_link = htmlentities( $_SERVER['PHP_SELF'] );
$letters = range('A', 'Z');
$menu = '<ul id="navlist">'."\n";
foreach ( $letters as $letter ) {
$menu .= '<li><a href="'. $this_link .'?var='. $letter .'">'. $letter .'</a></li>'."\n";
}
  
$menu .= '</ul>'."\n";

       
if ( isset( $_GET['var'] ) AND in_array( $_GET['var'], $letters ) ) {
 
          // CONNECT TO DATABASE AND QUERY
          //include("../databaseconnect.php");
          //include("includes/auth.inc.php");
          $letter=$_GET['var'];
          $sql = "SELECT * FROM mail_list WHERE mailSurname LIKE  \"".$letter."%\" ORDER BY mailSurname, mailFirstname"; 
           echo " this is the sql: $sql<br> letter: $letter";
            $result = mysql_query($sql);
            $no = mysql_num_rows($result);
   echo $menu; 
  echo"<table>";
          // DO QUERY AND CHURN OUT RESULTS
  for($t=0;$t<$no;$t++)    {
      $mailid=(mysql_result($result,$t,mailid)); 
      $title=(mysql_result($result,$t,mailTitle));
      $name=(mysql_result($result,$t,mailFirstname));
      $surname=(mysql_result($result,$t,mailSurname));
      $add1=(mysql_result($result,$t,mailAddress1));
      $add2=(mysql_result($result,$t,mailAddress2)); 
      $city=(mysql_result($result,$t,mailCity));  
      $code=(mysql_result($result,$t,mailPostcode));
      $state=(mysql_result($result,$t,mailState));
      $email=(mysql_result($result,$t,mailEmail));

    echo"<tr class='bg'><td height='25'>$title</td>";

        echo" <td height='25'>$name</td>";
        echo" <td height='25'>$surname</td>";
        echo" <td height='25'>$add1</td>";
        echo" <td height='25'>$add2</td>";
        echo" <td height='25'>$city</td>";
        echo" <td height='25'>$code</td>";
        echo" <td height='25'>$state</td>";
        echo" <td height='25'>$email</td>";

        echo"<td height='25'><a href='mail_list.php?action=edit&itemId=$mailid'>Edit Item</a></td>";
        echo"<td height='25'><a href='javascript:if(confirm(\"Are you sure?\")) { document.location.href=\"?action=delete&itemId=$mailid\"; }'>Remove</a></td>";

    echo "</tr>";

  }
    echo "</table>"; 
         
 
          } else {
 
         

          echo '<p>Select first letter of SURNAME</p>';

         

          }
 
       // DISPLAY LINKS   
 
      echo $menu;
 
       

  }

foot();

?>