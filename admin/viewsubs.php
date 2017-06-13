<?
include("../databaseconnect.php");
include("includes/auth.inc.php");

if(isset($add) or isset($delete)) {

	if(isset($add)) {
		$email1=explode("\n",$add);
		for($i=0;$i<count($email1);$i++)
		{
		    $email2=trim($email1[$i]);
		    if($email2!="") 
		    {
				echo "adding: $email2<BR>";
				$sql="INSERT INTO `subscribers` ( `subscriberid` , `subscriberemail` ) VALUES ('', '$email2');" ;					
				$result=mysql_query($sql);				
			}
		}
	}
	
	if(isset($delete)) {
		$email1=explode("\n",$delete);
		for($i=0;$i<count($email1);$i++)
		{
		    $email2=trim($email1[$i]);
		    if($email2!="") 
		    {
				echo "removing: $email2<BR>";
				$sql="delete from subscribers where subscriberemail = '$email2'" ;
				$result=mysql_query($sql);
			}
		}
	}
	
	
	
} else {

	include("head.php");	
	head();
	
	?>
	<p><h3>Subscriber actions</h3></p>
	<form name='theForm' action='viewsubs.php' method="POST">
		
		Add subscribers (hit enter after each address)<BR>
		<textarea name='add' rows=5 cols=40></textarea><BR><BR>

		Delete subscribers (hit enter after each address)<BR>
		<textarea name='delete' rows=5 cols=40></textarea><BR><BR>

		<input type='submit' value=' ok '>
		
	</form>
	<?
	foot();
}
?>