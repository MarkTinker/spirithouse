<?
include("databaseconnect.php");
//include("globals.inc.php");
include("includes/class_functions.inc.php");
include("includes/eway_process.inc.php");

$transactionid=date('YmdHis');

if(sizeof($_POST)>0) {

   
$pageTitle = "Spirit House: Cooking School &mdash; Booking in progress";
$sectionsList = "['#school_questions', '#school_bookings']";

$hideBigText = true; // This shrinks the big text section
$bigText_main = "We're Just About There.";
$bigText_sub = "We need a <em>few more details</em>.";
include("includes/header.inc.php"); 

  if(seats_still_avail($_POST['scheduleid'],$_POST['seats'], $_POST['maxseats'])) {

    $classprice=$_POST['classprice'];
    $total_to_process=$_POST['seats']*$classprice;
    
    // If there are seats still available then we Start the first container DIV here I guess
    echo "
    <div class='container section'>
	<div class='row'>
		<div class='twelvecol last title'>

    		<h3 class='nav'>Booking for &mdash; <span class='teal'>".$_POST['classname']."</span></h3>
   			 <p class='content'>Total to process now for ".$_POST['seats']." seat(s): $".number_format($total_to_process,0)." </p>";

    $vouchers=$_POST['vouchers'];
    $vouchers=str_replace("  ", " ", $vouchers);
    $vouchers=str_replace(" ", "|", $vouchers);
    $vouchers=str_replace(",", "|", $vouchers);
    $vouchers=str_replace("/n", "|", $vouchers);
    $vouchers=str_replace("\n", "|", $vouchers);
    $vouchers=str_replace("/r", "|", $vouchers);
    $vouchers=str_replace("\r", "|", $vouchers);
    $vouchers=str_replace("/n/r", "|", $vouchers);
    $vouchers=str_replace("\n\r", "|", $vouchers);

    $vouchers_used=explode("|",$vouchers);
    $totalvoucheramount=0;   $voucher_flag=false;  $voucher_results="";     $vouchers_to_use=array();     $stop_process=false;
	
	// Have vouchers been used?
    if(sizeof($vouchers_used)>0) {
      for($i=0;$i<sizeof($vouchers_used);$i++) {
        if(strlen($vouchers_used[$i])>0) {
          $sql="select * from vouchers where vouchernumber=".quote_smart($vouchers_used[$i]);
          //echo $sql."<br />";
          $rs=mysql_query($sql);
          $no=mysql_num_rows($rs);
          if($no>0) {
            $voucherstatus=mysql_result($rs,0,"voucherstatus");
            $voucheramount=mysql_result($rs,0,"voucheramount");

            if($voucherstatus==voucher_active) {
              $voucher_results.="<div style='color:green'><table><tr valign='middle'><td><img src='resources/greentick.gif' /></td><td>Voucher #".$vouchers_used[$i].": $".number_format($voucheramount)." confirmed OK.</									td></tr></table></div>";
              $totalvoucheramount=$totalvoucheramount+$voucheramount;
              $voucher_flag=false;
              $vouchers_to_use[]=$vouchers_used[$i];
     		##### vouchers are not ACTIVE so we display the messages ###########         
            } else {
              if($voucherstatus==voucher_presented) {
                $voucher_results.="<div style='color:red'><table><tr valign='middle'><td><img src='resources/redcross.gif' /></td><td>We're sorry... Voucher ID (".$vouchers_used[$i].") has already been presented.</td></									tr></table></div>";
                $stop_process=true;
              }
              if($voucherstatus==voucher_holding) {
                $voucher_results.="<div style='color:red'><table><tr valign='middle'><td><img src='resources/redcross.gif' /></td><td>We're sorry... Voucher ID (".$vouchers_used[$i].") is already being used to hold 										another class.</td></tr></table></div>";
                $stop_process=true;
              }
              if($voucherstatus==voucher_expired) {
                $voucher_results.="<div style='color:red'><table><tr valign='middle'><td><img src='resources/redcross.gif' /></td><td>We're sorry... Voucher ID (".$vouchers_used[$i].") has expired.</td></tr></table></										div>";
                $stop_process=true;
              }
              $voucher_flag=true;
            }
        ############# We don't have the voucher number ############
          } else {
            $voucher_results.="<div style='color:red'><table><tr valign='middle'><td><img src='resources/redcross.gif' /></td><td><p class='info'>Sorry - we can't find voucher ID number (".$vouchers_used[$i].") in our database.</td></									tr></table></div>";
            $voucher_flag=true;
            $stop_process=true;
          }
        }
      }
      if($voucher_flag) $voucher_results.="<div style='font-weight:bold; margin-bottom:20px; margin-top:20px; padding:15px; background-color:#efefef;'>If you feel this is wrong, please feel free to give us a call in 											the office on (07) 5446 8977. If you'd like to amend your voucher ID numbers, please click 'back' and make your changes.</div>";
    }
    
    ####### END VOUCHER PROCESSING ##############
    
    if(isset($_POST['stopped_already'])) { $stop_process=false; }
	
	//if the total is more than the vouchers we need a credit card
    if($total_to_process>$totalvoucheramount) {
      
      //if($voucher_flag) echo $voucher_results;
     //delete below if problem
      echo $voucher_results;  
      
      if(strlen($_POST['cardnumber1'])>0 && !$stop_process) {
        #cool - charge the card for the difference.
        #echo $total_to_process." processed. redirecting to thank you page";
        $totalcreditamount=$total_to_process-$totalvoucheramount;
        
        
        // PAYING ONLY WITH CREDIT CARD - no vouchers
        $authcode=process_cc($_POST, $totalcreditamount, $transactionid);
        if(is_numeric($authcode)) {
          show_overlay();
          do_booking($_POST, $total_to_process, $totalvoucheramount, $totalcreditamount, $vouchers_to_use, $authcode, $transactionid);
          $redir="http://www.spirithouse.com.au/booking_confirm.php?scheduleid=".$_POST['scheduleid'];
          
          ?><html><head><title>Processing Your Credit Card...</title><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='REFRESH' content='1; URL=<?=$redir;?>'></head><body>Your booking was processed. Please stand by, redirecting...</html> <html> <?
        } else {
          #if($voucher_flag) echo $voucher_results;
          echo "<div style='margin-bottom:20px; margin-top:10px; '>
            <table><tr valign='middle'><td><img src='resources/redcross.gif' /></td><td><p class='info'>Sorry - there was a problem with this credit card.
            <br><strong>".$authcode."</strong>.</p>
            <p class='info'>If this is a problem you can do something about, please feel free to try again. If not, please try another credit card.</p></td></tr></table></div>";
          if($total_to_process==$totalcreditamount) {
            output_cc_form("Try another credit card?", $totalcreditamount, $vouchers, $_POST, 1);
          } else {
            output_cc_form("Put the $".$totalcreditamount." balance on your credit card?", $totalcreditamount, $vouchers, $_POST, 1);
          }
        }


      } else {
        # need credit card to continue
        $amount_outstanding=$total_to_process-$totalvoucheramount;
        output_cc_form("Put the $".$amount_outstanding." balance on your credit card?", $amount_outstanding, $vouchers, $_POST, $stop_process);

      }
    } else {
    
	#### Here's where we DO THE BOOKING either vouchers are ok, or the credit card is ok or both.	    
      show_overlay();
      do_booking($_POST, $total_to_process, $totalvoucheramount, 0, $vouchers_to_use, "voucher", $transactionid);
      $redir="http://www.spirithouse.com.au/booking_confirm.php?scheduleid=".$_POST['scheduleid'];
      ?><html><head><title>Processing...</title><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='REFRESH' content='1; URL=<?=$redir;?>'></head><body>Your booking was 			processed. Please stand by, redirecting...</html> <html> <?
      #process_booking(3, $_POST, $totalamount, $vouchers_to_use, $totalcreditamount, $totalvoucheramount);

    }


  } else {

    ?><p>Ah nuts! You weren't fast enough!</p>
    <p>It looks like you just missed out on the last few spots that were free.</p>
    <p>If any more places open up, we will notify you again. If you'd like off the list, click <a href='waitlist_remove.php?id=<?=$_GET['id'];?>&rand=<?=$_GET['rand'];?>'>here</a>.</p>
    <?

  }
	
	echo "	</div>
		  	</div>
			<!-- end.row -->
	</div>
	<!-- end#maps_accom.container -->";	
		
  include("includes/footer.inc.php"); 
  exit();

}

function show_overlay() {
  ?><div id="light" class="white_content"><h1>Processing your booking, please stand by...</h1><br /><br /><img src='resources/ajax-loader.gif' /></div>
  <div id="fade" class="black_overlay"></div>
  <script>
  document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'
  </script><?
}

function do_booking($_POST, $total_to_process, $totalvoucheramount, $totalcreditamount, $vouchers_to_use, $authcode='', $transactionid) {
  insert_booking($_POST, $authcode, $transactionid, $total_to_process, $totalvoucheramount, $totalcreditamount, $vouchers_to_use);
  process_voucher($_POST, $totalvoucheramount, $vouchers_to_use);
  generate_email_for_student($_POST);
  update_seats_for_schedule($_POST['scheduleid']);
}



function output_cc_form($head_cc, $amount_outstanding, $vouchers, $_POST, $stop_process=0) {

  ?>
 
  <form name='theForm' id='theForm' action='booking_check.php' method='post' onsubmit='return validate_form2(document.theForm); return false;'>
  <fieldset>
  <legend> <h2><?=$head_cc;?></h2></legend>
  

  <table class='class_description' id='paymenttype_creditcard'>
    <? if($amount_outstanding>0) { ?>
      <tr><td>Amount&nbsp;outstanding:</td><td>$<?=$amount_outstanding;?></td></tr>
    <? } ?>
    <tr><td>Name on credit card:</td><td><input name='nameoncard1' value='<?=$_POST['nameoncard1'];?>' style='width:300px' /></td></tr>
    <tr><td>Credit card number:</td><td><input name='cardnumber1' value='<?=$_POST['cardnumber1'];?>' style='width:300px' /></td></tr>
    <tr><td>Credit card expiry:</td><td><?=select_month("expiry_m1", "cc_droplist", $_POST['expiry_m1']);?> <?=select_year("expiry_y1", "cc_droplist", $_POST['expiry_y1']);?></td></tr>
    <tr><td>Card Security Number (CCV):</td><td><input name='ccv1' size=4 maxlength=4 /></td></tr>
    <tr><Td>Please agree:</td><Td><input type='checkbox' id='chk_tc1' name='chk_tc1' /> <label for='chk_tc1'>Yes - I have read and agreed to the <a href='#nogo' onclick="javascript:tandc_window();">terms and conditions</a> of this purchase</label></td></tr>
    <tr><td align='center' colspan=2><input type='submit' class='bookingsubmit' value=' Please bill my card for $<?=$amount_outstanding;?> ' /></td></tr>
    <tr><td align='center' colspan=2>Please click button ONCE only!</td></tr>
  </table>
  <input type='hidden' name='vouchers' value='<?=$vouchers;?>' />
  <input type='hidden' name='scheduleid' value='<?=$_POST['scheduleid'];?>' />
  <input type='hidden' name='spotsbooked' value="<?=$_POST['spotsbooked'];?>" />
  <input type='hidden' name='emaildescription' value="<?=$_POST['emaildescription'];?>" />
  <input type='hidden' name='starttime' value='<?=$_POST['starttime'];?>' />
  <input type='hidden' name='classdate' value='<?=$_POST['classdate'];?>' />
  <input type='hidden' name='classname' value='<?=$_POST['classname'];?>' />
  <input type='hidden' name='maxseats' value='<?=$_POST['maxseats'];?>' />

  <input type='hidden' name='firstname' value='<?=$_POST['firstname'];?>' />
  <input type='hidden' name='lastname' value='<?=$_POST['lastname'];?>' />
  <input type='hidden' name='phone' value='<?=$_POST['phone'];?>' />
  <input type='hidden' name='email' value='<?=$_POST['email'];?>' />

  <input type='hidden' name='classprice' value='<?=$_POST['classprice'];?>' />
  <input type='hidden' name='seats' value='<?=$_POST['seats'];?>' />
  <input type='hidden' name='nametags' value='<?=$_POST['nametags'];?>' />
  <?
  if($stop_process==1) {
    ?><input type='hidden' name='stopped_already' value='1' /><?
  }
  ?>  </fieldset></form><?


}