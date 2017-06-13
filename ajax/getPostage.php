<?
if(strlen($_GET['thePostcode'])==4 && $_GET['theWeight']>0) {

	$qs = 'Height=150&';
	$qs .= 'Length=150&';
	$qs .= 'Width=15&';
	$qs .= 'Weight='.$_GET['theWeight'].'&';
	$qs .= 'Pickup_Postcode=4561&';
	$qs .= 'Destination_Postcode='.$_GET['thePostcode'].'&';
	$qs .= 'Country=AU&';
	$qs .= 'Service_Type=STANDARD&';
	$qs .= 'Quantity=1';

	$myfile=file('http://drc.edeliver.com.au/ratecalc.asp?'.$qs);

	$x = split('=',$myfile[0]);
	#echo $x[0]."1<BR>";
	echo $x[1];

	#$x = split('=',$myfile[1]);
	#echo $x[0]."3<BR>";
	#echo $x[1]."4<BR>";

	#$x = split('=',$myfile[2]);
	#echo $x[0]."5<BR>";
	#echo $x[1]."6<BR>";

}