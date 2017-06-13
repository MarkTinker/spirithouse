<?
$body="==========<br>
ORDER DETAILS<br>
==========<br>
";
$body.=$_POST['ordered']."<br>

";
$body.="Total (inc shiping & handling): $".number_format($_POST['total_payable'],2)."<br>
	
==========<br>
CUSTOMER DETAILS<br>
==========<br>
";
$body.=$_POST['firstname']." ".$_POST['lastname']."<br>
";
$body.=$_POST['address1']."<br>
";
$body.=$_POST['address2']."<br>
";
$body.=$_POST['state']."<br>
";
$body.=$_POST['postcode']."<br>
";
$body.=$_POST['phone']."<br>
";
$body.=$_POST['email']."<br>

";
$body.="==========<br>
CREDIT CARD DETAILS<br>
==========<br>
";
$body.=$_POST['nameoncard1']."<br>
";
$body.=$_POST['cardnumber1']."<br>
";
$body.="Exp: ".$_POST['expiry_m1']." / ".$_POST['expiry_y1']."<br>
";




 $headers = "From: office@spirithouse.com.au\r\n" .'X-Mailer: PHP \r\n' ."MIME-Version: 1.0\r\n" ."Content-Type: text/html; charset=utf-8\r\n" ."Content-Transfer-Encoding: 8bit\r\n\r\n";


    
    
    

//mail("acland@spirithouse.com.au", "[Spirithouse Order - ".date("d M Y")."] ".$_POST['firstname']." ".$_POST['lastname'], $body, $headers);

mail("cookingschool@spirithouse.com.au", "[Spirithouse Order - ".date("d M Y")."] ".$_POST['firstname']." ".$_POST['lastname'], $body, $headers);


@session_destroy();



?><meta http-equiv="refresh" content="2;url=http://www.spirithouse.com.au/shop_thankyou.php">