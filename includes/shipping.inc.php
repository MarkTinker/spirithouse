<?
function calc_shipping($weight,$destination_postcode) {

	$qs = 'Height=150&';
	$qs .= 'Length=150&';
	$qs .= 'Width=15&';
	$qs .= 'Weight='.$weight.'&';
	$qs .= 'Pickup_Postcode=4561&';
	$qs .= 'Destination_Postcode='.$destination_postcode.'&';
	$qs .= 'Country=AU&';
	$qs .= 'Service_Type=STANDARD&';
	$qs .= 'Quantity=1';

	$myfile=file('http://drc.edeliver.com.au/ratecalc.asp?'.$qs);

	$x = split('=',$myfile[0]);
	#echo $x[0]."1<BR>";
	return $x[1];

	#$x = split('=',$myfile[1]);
	#echo $x[0]."3<BR>";
	#echo $x[1]."4<BR>";

	#$x = split('=',$myfile[2]);
	#echo $x[0]."5<BR>";
	#echo $x[1]."6<BR>";

}