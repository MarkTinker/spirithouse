<?php
// ===================================================================  
// Voucher.class.php
// =================================================================== 
//
// This class makes vouchers a lot easier to work with. It can be used
// to create new vouchers, insert them into the database, update them,
// and also generate a printable PDF document.
//
// Usage:
// Include this script. And use:
//
// 		$myVoucher = new Voucher(...);
//
// to instantiate a new Voucher object. See the __constructor below for
// the correct arguments. When you're ready to commit it to the 
// database you can call
//
//		$myVoucher->insert();
// 
// Which will return 'true' if the insert was successful. You can still
// change the object after (or later on) and then call update() to
// update the fields in the database.
//
//		$myVoucher->email = "newemail@mysite.com";
//		$myVoucher->update(); 
//
// To load an existing voucher from the database, call the static
// method ::loadVoucherByNumber
//
//		$voucherid = $_POST['vouchernumber'];
//		$myVoucher = Voucher::loadVoucherByNumber($vouchernumber);
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

// This is used for retreiving voucher PDFs
define ("MD5_SALT", "secretsalt-xjh665");

class Voucher
{
	// Private
	private $sanitized;
	
	// Public
	public $id;
	
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
		// Private
		$this->sanitized 		= array();
		
		// Public
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
	
	private function sanitize()
	{
		// Loops through 'public' vars
		foreach($this as $key => $value)
		{
			$this->sanitized[$key] = quote_smart($value);
		}
	}
	
	public function getUpdateSQL()
	{
		$this->sanitize();
		
		$sql = "UPDATE `vouchers` SET
		firstname 			= {$this->sanitized['firstName']},
		lastname 			= {$this->sanitized['lastName']},
		fromname			= {$this->sanitized['fromName']},
		recipientname		= {$this->sanitized['recipientName']},
		email				= {$this->sanitized['email']},
		security			= {$this->sanitized['security']},
		voucheramount		= {$this->sanitized['amount']},
		voucherexpirymonth	= {$this->sanitized['expiryMonth']},
		voucherexpiryyear	= {$this->sanitized['expiryYear']},
		voucherstatus		= {$this->sanitized['status']},
		vouchernumber		= {$this->sanitized['number']},
		evouchernumber		= {$this->sanitized['number']},
		vouchernotes		= {$this->sanitized['notes']}
		
		WHERE id = {$this->id}";
		
		return $sql;
	}
	
	public function update()
	{		
		// Must have been inserted or 
		// fetched before we can update
		if (!$this->id) { return false; }
		
		$sql = $this->getUpdateSQL();		
		$result = mysql_query($sql);
		return $result;
	}
	
	public function getInsertSQL()
	{
		$this->sanitize();
		
		$sql = "INSERT INTO `vouchers` 
		(firstname, lastname, fromname, recipientname, email, security, voucheramount, voucherexpirymonth, 
			voucherexpiryyear, voucherstatus, vouchernumber, evouchernumber, vouchernotes)
		
		VALUES ({$this->sanitized['firstName']}, 
				{$this->sanitized['lastName']}, 
				{$this->sanitized['fromName']}, 
				{$this->sanitized['recipientName']}, 
				{$this->sanitized['email']}, 
				{$this->sanitized['security']}, 
				{$this->sanitized['amount']}, 
				{$this->sanitized['expiryMonth']}, 
				{$this->sanitized['expiryYear']}, 
				{$this->sanitized['status']}, 
				{$this->sanitized['number']}, 
				{$this->sanitized['number']}, 
				{$this->sanitized['notes']})";
		
		return $sql;
	}
	
	public function insert()
	{		
		// Return if we've already inserted this voucher
		if ($this->id) { return true; }
		
		$sql = $this->getInsertSQL();		
		$result = mysql_query($sql);
		if ($result) {
			$this->id = mysql_insert_id();
		}
		return $result;
	}
	
	public function getAuthToken()
	{
		return md5($this->number . $this->security . MD5_SALT);
	}
	
	// STATIC METHODS
	
	public static function loadVoucherByNumber($id)
	{
		$sql = "SELECT * FROM 
				`vouchers` WHERE vouchernumber = $id 
				LIMIT 1";
				
		$result = mysql_query($sql);
		
		if ($result && (mysql_num_rows($result) > 0)) 
		{
			$v = mysql_fetch_assoc($result);
			$newVoucher = new Voucher(	$v['firstname'], 
										$v['lastname'], 
										$v['fromname'], 
										$v['recipientname'], 
										$v['email'], 
										$v['vouchernumber'], 
										$v['security'], 
										$v['voucheramount'], 
										$v['voucherexpirymonth'], 
										$v['voucherexpiryyear'], 
										$v['voucherstatus'], 
										$v['vouchernotes']);
			$newVoucher->id = $id;
			return $newVoucher;
		}
		else
		{
			return false;
		}		
	}
	
}

?>