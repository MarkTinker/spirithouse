<?php

// Make Vouchers a little easier to work with.
// @author: Hamish Macpherson

class Voucher
{
	private $id;
	
	public $firstName;
	public $lastName;
	public $fromName;
	public $recipientName;	
	public $email;
	
	public $number;
	public $eNumber;
	
	public $security;
	public $amount;
	public $expiryMonth;
	public $expiryYear;
	public $status;
	public $notes;
	
	public $issued;
	
	function __construct($firstName, $lastName, $fromName, $recipientName, $email, 
		$number, $security, $amount, $expiryMonth, $expiryYear, $status, $notes)
	{
		$this->firstName 		= $firstName;
		$this->lastName 		= $lastName;
		$this->fromName 		= $fromName;
		$this->recipientName 	= $recipientName;
		$this->email 			= $email;
		$this->number 			= $number;
		$this->security 		= $security;
		$this->amount 			= $amount;
		$this->expiryMonth 		= $expiryMonth;
		$this->expiryYear 		= $expiryYear;
		$this->status 			= $status;
		$this->notes 			= $notes;
		
		$this->issued 			= '';		// This will be set by MySQL (TODO: Do we need this?)
	}
	
	public function insert()
	{		
		if ($this->id)
		{
			// We've already inserted this voucher
			return false;
		}
		
		$sql = "
		INSERT INTO `vouchers` 
		(firstname, lastname, fromname, recipientname, email, security, voucheramount, voucherexpirymonth, 
			voucherexpiryyear, voucherstatus, vouchernumber, evouchernumber, vouchernotes)

		VALUES ('$this->firstName', '$this->lastName', '$this->fromName', '$this->recipientName', '$this->email', 
			$this->security, $this->amount, $this->expiryMonth, $this->expiryYear, $this->status, 
			$this->number, $this->number, '$this->notes')";
		
		$result = mysql_query($sql);
		if ($result) {
			$this->id = mysql_insert_id();
		}
		return $result;
	}
}

?>