<?php
require_once( 'EwayPayment.php' );
$ip_add = $_SERVER['REMOTE_ADDR'];

/* Live Beagle Gateway */
//$eway = new EwayPayment( '13885527', 'https://www.eway.com.au/gateway_cvn/xmlbeagle.asp' ); // live url and sh id
//$eway = new EwayPayment( '91431322', 'https://www.eway.com.au/gateway_cvn/xmlbeagle.asp' ); // sandbox id and live url
//https://www.eway.com.au/eway-partner-portal/resources G0ndr0ng
//https://sandbox.myeway.com.au/gbc/BusinessCentre.aspx - to check payments in the gateway etc

/* Test Beagle Gateway*/

$eway = new EwayPayment( '91431322', 'https://www.eway.com.au/gateway_cvn/xmltest/BeagleTest.aspx' );

/* Live Beagle Gateway */
//$eway = new EwayPayment( '87654321', 'https://www.eway.com.au/gateway_cvn/xmlbeagle.asp' );

//Substitute 'FirstName', 'Lastname' etc for $_POST["FieldName"] where FieldName is the name of your INPUT field on your webpage
$eway->setCustomerFirstname( 'Firstname' ); 
$eway->setCustomerLastname( 'Lastname' );
$eway->setCustomerEmail( 'name@xyz.com.au' );
//$eway->setCustomerAddress( '123 Someplace Street, Somewhere ACT' );
//$eway->setCustomerPostcode( '2609' );
$eway->setCustomerInvoiceDescription( 'Acland Testing' );
$eway->setCustomerInvoiceRef( 'INV120394' );
$eway->setCardHoldersName( 'Acland Brierty' );
$eway->setCardNumber( '4444333322221111' );
$eway->setCardExpiryMonth( '08' );
$eway->setCardExpiryYear( '18' );
$eway->setTrxnNumber( '4230' );
$eway->setTotalAmount( 100 );
$eway->setCVN( 123 );
$eway->setOption1("option1");
$eway->setOption2("option2");
$eway->setOption3("option3");
$eway->setCustomerIPAddress("$ip_add");
$eway->setCustomerBillingCountry("AUS");


if( $eway->doPayment() == EWAY_TRANSACTION_OK ) {
    echo "Transaction Successful: ". $eway->getTrxnStatus()."</br>";
    echo "Transaction Number: " . $eway->getTrxnNumber()."</br>";
    echo "Transaction Reference: " . $eway->getTrxnReference()."</br>";
    echo "Return Amount: " . $eway->getReturnAmount()."</br>";
    echo "Auth Code: " . $eway->getAuthCode()."</br>";
    echo "Option1: " . $eway->getTrxnOption1()."</br>";
    echo "Option2: " . $eway->getTrxnOption2()."</br>";
    echo "Option3: " . $eway->getTrxnOption3()."</br>";
} else {
    echo "Error occurred (".$eway->getError()."): " . $eway->getErrorMessage();
}
?>
