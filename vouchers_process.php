<?php
// ===================================================================  
// vouchers_process.php
// =================================================================== 
// (AJAX) Processes the vouchers form:
//
// - Validates Credit Card information and charges card
// - Either 1) Generates PDF Vouchers or 2) Emails information to admin
//	 so manual vouchers can be printed and posted by mail.
// - Returns JSON to the calling script
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

include("databaseconnect.php");
include("includes/eway_process.inc.php");
include("includes/voucher_functions.inc.php");

// Variables
// -------------------------------------------------------------------
$transactionid 		= date('YmdHis');
$total_to_process 	= $_POST['qty'] * $_POST['amount'];

// Utility Functions
// -------------------------------------------------------------------

// Simple function for returning a JSON result
function returnResult( $result , $type)
{
	// Possible $type values:
	// - 'cc_error'
	// - 'success'
	// - 'error'
	
	$result = array($type, $result);
	echo json_encode($result);
	die();
}

// Main Processing
// -------------------------------------------------------------------

// Let's check the Credit Card first
$authcode = process_payment(
			$total_to_process, 
			"Spirit House Gift Voucher", 
			$transactionid, 
			$_POST['firstname'], 
			$_POST['lastname'], 
			$_POST['email'], 
			$_POST['nameoncard1'], 
			$_POST['cardnumber1'], 
			$_POST['expiry_m1'], 
			$_POST['expiry_y1'], 
			$_POST['ccv1']);

if (!is_numeric($authcode)) 
{
	returnResult($authcode, "cc_error"); 
	// $authcode contains error msg 
}
else
{
	// Credit card is GO. Let's create the voucher(s).
	$result = generate_vouchers($total_to_process, $transactionid, $authcode);
	if ($result)
	{
		switch ($result['mode'])
		{
			case "post":
				returnResult(1, "success_post");
				break;
			
			case "email":				
				returnResult($result['href'], "success_email");
				break;
		}
	}
	else
	{
		returnResult(1, "error");
		// There was an error in generating the voucher(s)
	}
}

?>