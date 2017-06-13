<?php
# config file for the fansflood cookie.
# this file allows you to configure discounts and other 
# triggers for various pages.

function is_fan(){
	if(isset($_COOKIE['likedfacebookcompages/Spirit-House/7325766843'])){
			return true;
						}else{
			return false;;
	}
}



function shop_discount($itemPrice) {
		$itemPrice = $itemPrice*0.8;
		return($itemPrice);

		}


?>