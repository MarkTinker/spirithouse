<?php
// ===================================================================  
// voucherPDF.php
// =================================================================== 
// Script for generating a printable PDF file of voucher(s)
// 
// Example request:
// 		voucherPDF.php?vkeys=123456|1235,654321|2222&auth=13b99776b58f1e97577fc95f44eaa32b
//
// vkeys:
// 		This should be a string containing vouchernumber and voucher
//		security code pairs like so: "123456|1235,654321|2222" etc.
//
// auth:
//		This is a secret key. It is made by calling getAuthToken() on
//		the first voucher in the previous variable. This makes it almost
//		impossible for an attacker to steal other vouchers. Unless he
//		learns the algorithm for this token's generation.
//		
//		The voucher PDF is NOT generated unless the auth token is 
//		correct. The security codes are checked too.
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

require("databaseconnect.php");
require("includes/Voucher.class.php");
require("includes/PDFVoucherGenerator.class.php");

// Utility Functions
// -------------------------------------------------------------------
function dieMessage($msg)
{
	die("<pre>Sorry, we couldn't load your voucher! If this error persists and you need assistance, please call our office  (07) 5446 8977 &mdash; we'll be happy to assist you however we can.<br><b>Error: </b>$msg</pre>");
}

// Main Processing
// -------------------------------------------------------------------

if ($_GET['vkeys'] && $_GET['auth'])
{
	$vkeys 		= explode(",", $_GET['vkeys']);
	$auth 		= $_GET['auth'];	
	$vouchers	= array();
	
	foreach ($vkeys as $v)
	{
		$parts = explode("|", $v);
		$vnumber = $parts[0];
		$security = $parts[1];
		
		$myVoucher = Voucher::loadVoucherByNumber($vnumber);
		
		// Existing voucher?
		if (!$myVoucher)
		{
			dieMessage("Voucher not found.");
		}
		
		// Check security code
		if (!($myVoucher->security == $security))
		{
			dieMessage("Access denied.");
		}
		
		$vouchers[] = $myVoucher;
	}
	
	// Check auth token
	$myAuth = $vouchers[0]->getAuthToken();

	if ($myAuth == $auth)
	{
		$pdf = new PDFVoucherGenerator();
		
		foreach ($vouchers as $v)
		{
			$pdf->addVoucher($v);
		}

		$pdf->Output("Vouchers.pdf", "D");
		//"Vouchers.pdf", "D"
	}
	else
	{
		dieMessage("Access denied.");
	}
	
}
else
{
	dieMessage("Voucher not found.");
	// TODO: More helpful error, for the user
}

//good
//http://www.spirithouse.com.au/voucherPDF.php?vkeys=63617|2489,63618|2191,63619|5595,63620|2905,63621|2788,63622|8844&auth=dbbcc65b2423a32df7db4f6cffd7f3f2

// bad security code
//http://www.spirithouse.com.au/voucherPDF.php?vkeys=63617|3333,63618|2191,63619|5595,63620|2905,63621|2788,63622|8844&auth=dbbcc65b2423a32df7db4f6cffd7f3f2


?>