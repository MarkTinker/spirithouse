<?php
// ===================================================================  
// PDFVoucherGenerator.class.php
// =================================================================== 
// Generates a printable PDF file from Voucher(s)
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

// Includes
// -------------------------------------------------------------------
require('includes/pdf/fpdf.php');
require('includes/pdf/transform.php');

// Constants
// -------------------------------------------------------------------
define("DEFAULT_ORIENTATION", "L");
define("DEFAULT_UNIT", "mm");
define("DEFAULT_SIZE", "Letter");

define("VOUCHER_BASE", "voucherbase3.jpg");
define("BASE_HEIGHT", 200);
define("BASE_ORIGIN_X", 0);
define("BASE_ORIGIN_Y", -3.2);

define("NAME_FONT_SIZE", 23);

define("REGULAR_FONT_SIZE", 15);
define("REGULAR_FONT_NAME", "Helvetica");
define("REGULAR_FONT_STYLE", "BI");

define("ID_FONT_SIZE", 10);
define("ID_FONT_NAME", "Helvetica");
define("ID_FONT_STYLE", "");

define("VN_FONT_SIZE", 10);
define("VN_FONT_NAME", "Helvetica");
define("VN_FONT_STYLE", "");


// Class Definition
// -------------------------------------------------------------------
class PDFVoucherGenerator extends PDF_Rotate
{
	private $vouchers;
	private $fontsize;
	
	public function __construct($orientation = DEFAULT_ORIENTATION, 
								$unit = DEFAULT_UNIT, 
								$size = DEFAULT_SIZE)
	{
		parent::__construct($orientation, $unit, $size);
		
		$vouchers = array();
		$fontsize = REGULAR_FONT_SIZE;
	}
	
	public function SetFont($name, $style, $size)
	{
		parent::SetFont($name, $style, $size);
		$this->fontsize = $size;
	}
	
	public function SetFontSize($size)
	{
		parent::SetFontSize($size);
		$this->fontsize = $size;
	}
	
	public function Text($x, $y, $txt, $scaleToMaxWidth = 0, $flip = false)
	{		
		// Fit to width
		if ($scaleToMaxWidth)
		{
			$curSize = $origSize = $this->fontsize;
			while ($this->GetStringWidth($txt) > $scaleToMaxWidth)
			{
				$curSize--;
				if ($curSize <= 2) {break;}
				$this->SetFontSize($curSize);
			}
		}
		
		// Draw Text
		if ($flip)
		{
			$this->Rotate(180, $x + ($this->GetStringWidth($txt)/2), $y - 2);
			parent::Text(BASE_ORIGIN_X + $x, $y - BASE_ORIGIN_Y, $txt);
		}
		else
		{
			parent::Text(BASE_ORIGIN_X + $x, BASE_ORIGIN_Y + $y, $txt);
		}		
		
		// Reset
		$this->Rotate(0);
		$this->SetFontSize($origSize);
	}
	
	public function addVoucher($v)
	{
		$this->AddPage();
		$this->Image(VOUCHER_BASE, BASE_ORIGIN_X, BASE_ORIGIN_Y, false, BASE_HEIGHT);
		
		// Set Text Color
		$this->SetTextColor(0, 0, 0);
		
		// Set Font
		$this->SetFont(REGULAR_FONT_NAME, REGULAR_FONT_STYLE, REGULAR_FONT_SIZE);
		
		// To:
		$this->SetFontSize(NAME_FONT_SIZE);
		$this->Text(57, 144.25, $v->recipientName, 95);
		
		// From:
		$this->SetFontSize(REGULAR_FONT_SIZE);
		$this->Text(57, 154.25, $v->fromName, 95);
		
		// Value:
		$this->Text(57, 165, "$" . $v->amount, 28);
		
		// Expiry:
		$this->Text(118, 165, $v->expiryMonth . "/" . $v->expiryYear, 28);
		
		// ID:
		$this->SetFont(ID_FONT_NAME, ID_FONT_STYLE, ID_FONT_SIZE);
		$this->SetTextColor(150);
		$this->Text(17, 189.5, $v->id, 28);
		
		// Voucher #:
		$this->SetFont(VN_FONT_NAME, VN_FONT_STYLE, VN_FONT_SIZE);
		$this->SetTextColor(220, 0, 0);
		$this->Text(174.2, 15.5, $v->number, 0, true);
		
		//$this->Line(90, 152, 90 + 95, 152);
	}
}
?>