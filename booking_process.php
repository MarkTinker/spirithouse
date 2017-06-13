<?php

// ===================================================================  
// booking_process.php
// =================================================================== 
// (AJAX) Processes the class booking form:
//
// - Validates any voucher(s) submitted
// - Validates Credit Card information and charges card
// - Makes bookings (sends emails, updates DB)
// - Returns JSON to the calling script
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: August 2011

require_once("databaseconnect.php");
require_once("includes/class_functions.inc.php");
require_once("includes/eway_process.inc.php");

// Variables
// -------------------------------------------------------------------

$transactionid 		= date('YmdHis'); // For the eway API

$classprice 		= $_POST['classprice'];
$total_cost			= $_POST['seats'] * $classprice;

$total_voucher_amount 	= $_POST['total_voucher_amount'];
$voucher_errors 		= array();
$voucher_list 			= array();
$vouchers_to_use		= array();

// Utility Functions
// -------------------------------------------------------------------

// Simple function for returning a JSON result
function returnResult( $result , $type = 'success')
{
	// Possible $type values:
	// - 'voucher_error'	
	// - 'voucher_success'
	// - 'seat_error'
	// - 'need_cc'
	// - 'cc_error'
	// - 'cc_success'
	
	$result = array($type, $result);
	echo json_encode($result);
	die();
}

// Makes the class booking - tasks delegated to functions in class.functions.inc.php
function makeBooking( $_POST, $total_cost, $totalvoucheramount, $totalcreditamount, $vouchers_to_use, $authcode='', $transactionid ) 
{
	insert_booking($_POST, $authcode, $transactionid, $total_cost, $totalvoucheramount, $totalcreditamount, $vouchers_to_use);
	process_voucher($_POST, $totalvoucheramount, $vouchers_to_use);
	generate_email_for_student($_POST);
	update_seats_for_schedule($_POST['scheduleid']);
}

// Voucher Functions
// -------------------------------------------------------------------

function parseVouchers( $vouchers )
{
	preg_match_all ("(\d+)", $vouchers, $matches);
	return $matches[0];
}

// Grabs a voucher from the database
function getVoucher( $voucher_id )
{	
	$sql = "SELECT * FROM vouchers WHERE vouchernumber = " . quote_smart($voucher_id);
    $result = mysql_query($sql);
    $rows = mysql_num_rows($result);
    if($rows > 0)
    {
    	$voucherstatus = mysql_result($result, 0, "voucherstatus");
        $voucheramount = mysql_result($result, 0, "voucheramount");
        return array('status' => $voucherstatus, 'amount' => $voucheramount);
    }
    else
    {
    	return false;
    }
}

// Main Processing
// -------------------------------------------------------------------

// # CASE 0: Check if seats are still available

if (!seats_still_avail($_POST['scheduleid'], $_POST['seats'], $_POST['maxseats'])) 
{
	returnResult(0, "seat_error");
	// TODO: Handle seat_error in HTML
}

// # CASE 1: Voucher(s)
//
// - The user selected the voucher option. We will
//   validate the voucher and return the result.
//   if there is still an outstanding balance to be
//   paid the Credit Card form will appear and we
//   will hit CASE 2 below when the form is submitted.

if ($_POST['usevoucher'] == 'yes')
{
	$vouchers = parseVouchers($_POST['vouchers']);	
	
	if (count($vouchers) > 0)
	{
		foreach ($vouchers as $voucher_id)
		{
			$voucher = getVoucher($voucher_id);
			if ($voucher)
			{
				switch ($voucher['status'])
				{				
					case voucher_active:
						$total_voucher_amount = $total_voucher_amount + $voucher['amount'];
						$voucher_list[] = array("id" => $voucher_id, "amount" => $voucher['amount']);
						$vouchers_to_use[] = $voucher_id;
						break;
						
					case voucher_presented:
						$voucher_errors[] = array("presented", $voucher_id);
						break;
						
					case voucher_holding:
						$voucher_errors[] = array("holding", $voucher_id);
						break;
						
					case voucher_expired:
						$voucher_errors[] = array("expired", $voucher_id);
						break;
					
					default:
						// unkown voucher state
						// this really shouldn't happen
						$voucher_errors[] = array("unknown", $voucher_id);
				}
			}
			else
			{
				// Couldn't find the voucher
				$voucher_errors[] = array("notfound", $voucher_id);
			}
		}
		
		// -------------------------------
		
		if (count($voucher_errors))
		{
			// Sputter and die() if we have any errors...
			returnResult($voucher_errors, "voucher_error");
		}
		else
		{
			if ($total_cost > $total_voucher_amount)
			{
				// We need a Credit Card for the rest				
				$result = array("voucher_list" => 			$voucher_list,
								"vouchers_to_use" => $vouchers_to_use,
								"total_voucher_amount" => 	$total_voucher_amount,
								"final_cost" =>				($total_cost - $total_voucher_amount));
								
				returnResult($result, "need_cc");
			}
			elseif ($total_cost <= $total_voucher_amount)
			{
				// The voucher covers the cost, make the booking!
				makeBooking($_POST, $total_cost, $total_voucher_amount, 0, $vouchers_to_use, "voucher", $transactionid);
				returnResult(1, "voucher_success");
			}			
		}
		
		// -------------------------------
					
	}
	else
	{
		// No vouchers submitted (e.g, RegEx failed to find)
		$voucher_errors[] = array("badform", "");
		returnResult($voucher_errors, "voucher_error");
	}
}

// # CASE 2: Credit Card
//
// - Either (1) the user has validated their vouchers
//   and needs to pay a remaining balance on a credit
//   card, or (2) they are paying only by credit card.

//				(1)								  (2)
if ($_POST['pay_balance'] == 'true' || $_POST['usevoucher'] == 'no')
{
	$total_credit_amount = $total_cost - $total_voucher_amount;
	$authcode = process_cc($_POST, $total_credit_amount, $transactionid);
  
  if ($_POST['pay_balance'])
  {
  	$vouchers_to_use = explode("||", $_POST['vouchers_to_use']);
  } 
	
	if (is_numeric($authcode)) 
	{
    	makeBooking($_POST, $total_cost, $total_voucher_amount, $total_credit_amount, $vouchers_to_use, $authcode, $transactionid);
    	returnResult(1, "cc_success");
	}
	else
	{
		returnResult($authcode, "cc_error"); // $authcode contains error msg 
	}
}

?>