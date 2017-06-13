<?php
include("head.php");
//include("../globals.inc.php");
include("../databaseconnect.php");
//include("includes/auth.inc.php");
include("../includes/class_functions.inc.php");
include("../includes/mail_pear.php");

//$scheduleid =  $_GET['scheduleid'];
//echo "this is the schedule id - $scheduleid";
//exit();

$yesterday=date("Y-m-d", strtotime("-1 days"));

if(!empty($yesterday)){
  $sql="select firstname, email, seats, classname, scheduledate,  date_format(`scheduledate`, '%W') as scheduledate2
        from bookings
        left join schedule on bookings.scheduleid=schedule.scheduleid
        left join classes on schedule.classid=classes.classid
        where scheduledate = '$yesterday' AND seats > 0"; 

  $rs=mysql_query($sql);
  $no=mysql_num_rows($rs);
  //echo $sql;
  //$content = "outside of the loop";
  //echo "<p> number of rows $no";
   //exit();
  
   for($t=0;$t<$no;$t++)
      {
    $firstname=mysql_result($rs,$t,"firstname");
    $classname=mysql_result($rs,$t,"classname");
    $scheduledate=mysql_result($rs,$t,"scheduledate2");
    $searchdate=mysql_result($rs,$t,"scheduledate");
    $email=mysql_result($rs,$t,"email");
    

    $content="
   
   
    <p>Hi ".$firstname."</p>
    <p>We just wanted to say 'Thank You' for attending the <strong>".$classname."</strong> cooking class on $scheduledate.</p>
    <p>We do hope you enjoyed your class. We welcome any feedback about your class experience, your ideas to improve the format, or suggestions for new class topics which would appeal to you and your friends.</p>

    <p>In the meantime, why not stay in touch with us and get some great last minute cooking school deals, interesting news and cool pics from the Spirit House world with these handy links:
     <p><a href='https://www.facebook.com/spirithouse.restaurant'>Facebook</a>: Like us on facebook for latest news and cool deals for friends of Spirit House
    <p><a href='http://instagram.com/the_spirithouse'>Instagram</a>: please tag us in any photos you've taken or check out cool photos from other customers. @the_spirithouse or #spirithouserestaurant 
    <p><a href='http://www.spirithouse.com.au/funstuff/'>Our Blog</a>: the kitchen sink of fun articles, pics and videos that inspire, motivate or amuse us
    <p><a href='http://www.spirithouse.com.au/recipe.php#searching'>Recipes</a>: search the Spirit House recipe database or download our most requested recipes as well as chef's hot tips.</p>
    
    <p>Hope to see you again soon,</p>
    <p>The Spirit House Team</p>
    <p>P.S. If you want to share the experience with other food lovers in your life, a Spirit House <a href='https://www.spirithouse.com.au/vouchers'>gift voucher</a> makes a great gift and can be <a href='https://www.spirithouse.com.au/vouchers'>bought online</a> and sent instantly via email or posted out to you. 
    ";
    
    
 ######### below is the new mail script using smtp and PEAR ########
    $content=email_template($content);
    $subject="Thanks for attending the $classname class on $scheduledate"; 
    $recipient="$firstname <$email>";
    //escape BCC below if you don't want to get a copy of the customer's email 
 	//$bcc= "Bookings <abrierty@gmail.com>";   
	send_email($recipient,$bcc, $subject, $content);  
    ######################################################################
    
    
  //echo "Thanks for attending the $searchdate class on $yesterday - $scheduleid<br><br> $content";
    
    }
    }ELSE{
	//echo "Nothing to check for";
    exit();
	}



    
   



?>
