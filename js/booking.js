// =========================================
// JavaScript for the booking form page
// Handles all the validation and submission
// via AJAX and practical magic.
// =========================================
// @author: Hamish Macpherson
// @date: August 2011

var errorTimeout = null;
var TIMEOUT_MSECONDS = 45000;

// Shows total cost of classe(s) whenever seats are selected
function show_total() 
{
    var seats = document.getElementById('seats').selectedIndex+1;
    var seattotal = seats*javascript_vars['classprice'] + ".00";
    document.getElementById('totalsummary').innerHTML="<p>Total amount payable for "+seats+" seat(s): <strong><big>$"+seattotal+"</big></strong></p>";

	$("#totalsummary big").stop().css("background-color", "#FFFF9C");
	$("#totalsummary big").animate({ backgroundColor: "#fff"}, 500);
}

// Shows and hides the voucher or Credit Card divs
function voucherRadio(evt)
{
	var val = $("#usevoucher_yes").attr("checked");
	if (val)
	{
		$("#paymenttype_voucher").slideDown('fast');
		$("#paymenttype_creditcard").slideUp('fast');
	}
	else
	{
		$("#paymenttype_voucher").slideUp('fast');
		$("#paymenttype_creditcard").slideDown('fast');
	}
}

// Code below handles the autofill of "name on credit card" with the person who is making the booking
// In most cases it will be the same name
var _name = {"firstname" : "", "lastname" : ""};
var _update_name = true;
function updateName()
{
	$("#nameoncard1").attr("value", _name["firstname"] + " " + _name["lastname"]);
}
function catchName(evt)
{
	var n_id = $(evt.target).attr("id");
	var val = $(evt.target).attr("value");
	_name[n_id] = val;
	if (_update_name == true)
	{
		updateName();
	}	
}



var result = false;

// This function process the result of 
// our forms AJAX submission and updates
// the UI and form accordingly.
function processJSONResult(data)
{     
    // possible types
    // - 'voucher_error'	
	// - 'voucher_success'
	// - 'seat_error'
	// - 'need_cc'
	// - 'cc_error'
	// - 'cc_success'
	
	result = data;

	clearTimeout(errorTimeout);
	$("#booking_timeout").hide();

    switch (data[0])
    {
    	// ------------------------------------------------------------
    	case 'need_cc':
    		// Not enough on the voucher to cover the cost
	    	// We'll need the Credit Card info
    		//console.log("We need a credit card!");
    		
    		// Warn on leaving page -- may not work in all browsers
    		// Must unset this later to disable
    		window.onbeforeunload = function()
    		{
				  return "If you leave now you will lose your booking, do you want to leave?";
			  };
    		
    		// Update UI
    		$(".tac").hide();
    		$("fieldset.payment_details legend").html("Vouchers Approved!"); 
    		$("#paymenttype_creditcard legend").after("<p>Please pay the remaining balance with your Credit Card.</p>");
    		
    		// Update cost
    		var seats = document.getElementById('seats').selectedIndex + 1;
		    var seattotal = ((seats * javascript_vars['classprice']) - data[1]['total_voucher_amount']) + ".00";
		    $('#totalsummary').html("<p>With voucher(s) total amount payable for " + seats + " seat(s): <strong><big>$" + seattotal + "</big></strong></p>");
    		
    		// Update hidden form fields
        //var vouchers_to_use_JSON = JSON.stringify(data[1]['vouchers_to_use']).replace(/"/g, '\'');
        $("input[name='vouchers_to_use']").attr("value", (data[1]['vouchers_to_use']).join("||"));
    		$("input[name='total_voucher_amount']").attr("value", data[1]['total_voucher_amount']);
    		$("input[name='pay_balance']").attr("value", "true");
    		
    		 // Remove and reattach #vouchers_radios above total cost
			var vp = $('.vouchers_radios').detach().insertBefore("#totalsummary");
    		
    		// Show vouchers being used
    		voucherHTML = "";
    		for (v in data[1]['voucher_list'])
    		{
    			v = data[1]['voucher_list'][v];
    			voucherHTML += "<div class='voucher'>$" + v['amount'] + ".00 <span class='vid'>ID: " + v['id'] + "</span></div>";
    		}
    		$(".radios").html("<p>Using Vouchers: " + voucherHTML + "</p>");
    		
    		// Animate UI + Update
    		$("fieldset.personal_information").slideUp('fast');
    		$("#paymenttype_voucher").slideUp('fast');
    		$("#paymenttype_creditcard").slideDown('fast', function()
    		{
    			$('html, body').stop().animate({scrollTop: $("h3.nav").offset().top - 80 }, 500, function() 
	    		{ 
	    			$(".submit-disable").attr('disabled', false);
					$("#submit_credit").html('<span>Pay Now</span>'); 
				});
    		});
    		
    		break;
    	
    	// ------------------------------------------------------------	
    	case 'voucher_error':    	
    		var error_messages = new Array();
    		
    		for (error in data[1])
    		{
    			var error = data[1][error];
    			//console.log("We got a " + error[0] + " error with voucher ID '" + error[1] + "'");
    			
    			switch(error[0])
    			{
    				case 'notfound':
    					error_messages.push("Voucher <b>#" + error[1] + "</b> could not be found.");
    					break;
    					
    				case 'presented':
    					error_messages.push("Voucher <b>#" + error[1] + "</b> has already been presented.");
    					break;
    					
    				case 'holding':
    					error_messages.push("Voucher <b>#" + error[1] + "</b> is already being used to hold another class.");
    					break;
    					
    				case 'expired':
    					error_messages.push("Voucher <b>#" + error[1] + "</b> has expired.");
    					break;
    					
    				case 'unknown':
    				default:
    					error_messages.push("There was a problem processing Voucher #" + error[1] + ". Please try again.");
    			}    			
    		}
    		
    		//console.log(error_messages);
    		
    		if ($("#voucher_errors").length == 0)
    		{
    			$("#vouchers").after("<label id='voucher_errors' for='vouchers' generated='true' class='error'><ul></ul></label>");
    		}
    		else
    		{
    			$("#voucher_errors").html("<ul></ul>");
    			$("#voucher_errors").css({backgroundColor: "#c00"});
    		}
						
			for (msg in error_messages)
			{
				var msg = error_messages[msg];
				$("#voucher_errors").append("<li>" + msg + "</li>");
			}
			
			$("label.error").show().animate({ backgroundColor: "#777"}, 1500);
			
			$(".tac").hide();
			$(".submit-disable").attr('disabled', false);
			$("#submit_voucher").html('<span>Check Voucher(s)</span>');
    		
    		break;
    	
    	// ------------------------------------------------------------	
    	case 'cc_error':
    		$("label.error").remove();
    		$("img.ccv").after("<label id='cc_errors' generated='true' class='error'></label>");
   			$("#cc_errors").html("<ul><li>Sorry, we weren't able to process your credit card. Please ensure the information is correct and try again.</li></ul>");
   			$("#cc_errors").css({backgroundColor: "#c00"});
			
			$("label.error").show().animate({ backgroundColor: "#777"}, 1500);
			
			$(".tac").hide();
			$(".submit-disable").attr('disabled', false);
			$("#submit_credit").html('<span>Pay Now</span>');
    		break;
    	
    	// ------------------------------------------------------------	
    	case 'seat_error':
    		
    		break;
    	
    	// ------------------------------------------------------------	
    	case 'cc_success':    	
    	case 'voucher_success':
    		window.onbeforeunload = undefined;
    		$("#theForm").slideUp();
    		$("#seatsleft").slideUp();
    		$("#booking_complete").slideDown();
    		$("p.thanks").html("Thanks for booking a class, " + $("#firstname").attr("value") + ". We've sent all the details to your email address: " + $("#email").attr("value"));
    		$('html, body').stop().animate({scrollTop: $("h3.nav").offset().top - 80 }, 500, function() 
    		{ 
				
			});
    		break;
    		
    	// ------------------------------------------------------------	
    	default:
    		//console.log("Recieved response: " + data[0]);
    		//console.log(data[1]);
    }
}
// Options for form submission
// http://docs.jquery.com/Plugins/Validation/validate (see 'options' tab)
var booking_process_options = 
{ 
	success: processJSONResult,
	url: 'booking_process.php',
	dataType: 'json'
}; 

// Conditional Check for Form Validation
// Returns true if element is 'checked', false otherwise
function isChecked(el)
{
  if ($(el).attr("checked"))
  {
    return true;
  }
  return false;
}


// Setup page onReady
$(document).ready(function()
{
	// Scroll and focus first input
	$('html, body').stop().animate({scrollTop: $("h3.nav").offset().top - 80 }, 500, function() 
	{ 
		$("input[type='text']").first().focus();		
	});
	
	// Hide vouchers
	$("#paymenttype_voucher").hide();
	
	// Update total when seats are selected
	show_total();
	$("#seats").change(function()
	{
		show_total();
	});
	
	// Hook the change event for the voucher radio choices
	$("#usevoucher_yes").change(voucherRadio);
	$("#usevoucher_no").change(voucherRadio);
	
	// Hook the change event for the first/last-name -> CC Name field autofill
	$("#firstname").keyup(catchName);
	$("#lastname").keyup(catchName);
		
		// Disbale this once the user has touched the CC Name field
		$("#nameoncard1").keyup(function(){
			_update_name = false;
		});
	
	// Setup jQuery form validation
	// http://docs.jquery.com/Plugins/Validation/
	
	$("#theForm").validate({
		submitHandler: function(form) 
		{
      console.log("SUBMIT!");
      $(".submit-disable").attr('disabled', true);
			$("#submit_credit").html('<span>Please wait…</span>');
			$("#submit_voucher").html('<span>Please wait…</span>');
			
			$(form).ajaxSubmit(booking_process_options);
			//form.submit();

			// Start a timeout counter in the event that the script doesn't return
			// (Server seems to hang sometimes) — @by Hamish, July 19, 2013
			errorTimeout = setTimeout(function(){
				window.onbeforeunload = undefined;
	    		$("#theForm").slideUp();
	    		$("#seatsleft").slideUp();
	    		$("#booking_timeout").slideDown();
			}, TIMEOUT_MSECONDS);
		},
		rules:
		{
			nameoncard1:
			{
				required: function(element)
				{
					return isChecked("#usevoucher_no");
				}
			},
			cardnumber1: 
			{
				required: function(element)
				{
					return isChecked("#usevoucher_no");
				}
			},
			ccv1:
			{
				required: function(element)
				{
					return isChecked("#usevoucher_no");
				}
			},
			vouchers:
			{
				required: function(element)
				{
          return isChecked("#usevoucher_yes");
				}
			},
			chk_tc1:
			{
				required: function(element)
				{
					return isChecked("#usevoucher_yes");
				}
			},
			chk_tc2:
			{
				required: function(element)
				{
					return isChecked("#usevoucher_no");
				}
			}
		},
		messages: 
		{
			firstname: "Please specify your first name.",
			lastname: "Please specify your last name.",
			phone: "Please enter a phone number.",
			email: 
			{
				required: "We need your email address to send you the booking information.",
				email: "Your email address must be in the format of name@domain.com"
			},
			nameoncard1: "Please enter the name on your Credit Card.",
			cardnumber1: "Please enter your Credit Card Number.",
			ccv1: "Please enter your Credit Card's security number.",
			vouchers: "Please enter your voucher code(s).",
			chk_tc1: "You must agree to the terms and conditions.",
			chk_tc2: "You must agree to the terms and conditions."
		},
		errorPlacement: function(error, element)
		{
			if (element.attr("name") == "ccv1")
			{
				error.insertAfter("img.ccv");
			}
			else if (element.attr("name") == "chk_tc1")
			{
				error.insertAfter("label[for='chk_tc1']");
			}
			else if (element.attr("name") == "chk_tc2")
			{
				error.insertAfter("label[for='chk_tc2']");
			}
			else
			{
				error.insertAfter(element);
			}
		},
		invalidHandler: function(form, validator)
		{
			// Makes the errors flash red for a moment
			setTimeout(function(){
				$("label.error").stop().css("background-color", "#c00");
				$("label.error").animate({ backgroundColor: "#777"}, 1500);
			}, 200);
		}
	});
});