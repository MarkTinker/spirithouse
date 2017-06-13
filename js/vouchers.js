// ===================================================================  
// vouchers.js
// =================================================================== 
// Handles all the JavaScript interaction, forms, etc. on the vouchers
// page. Sends the data via AJAX to vouchers_process.php
//
// =================================================================== 
// @author: Hamish Macpherson
// @date: September 2011

var errorTimeout = null;
var TIMEOUT_MSECONDS = 45000;

function show_total() 
{
    var vouchers = $("#qty").val();
    var value = $("#amount").val();
	var voucher_total = value * vouchers;
	
	//console.log(vouchers);
	//console.log(value);
	//console.log(voucher_total);
		
	if (isNaN(voucher_total) || voucher_total <= 0)
	{
		voucher_total = "Invalid Amount";
		$('#totalsummary').html("<p>Total amount payable for " + vouchers + " voucher(s): <strong><big>Invalid Amount</big></strong></p>");
	}
	else
	{
		$('#totalsummary').html("<p>Total amount payable for " + vouchers + " voucher(s) at <b>$" + value + ".00</b> each: <strong><big>$"+voucher_total+".00</big></strong></p>");
	} 

	$("#totalsummary big").stop().css("background-color", "#FFFF9C");
	$("#totalsummary big").animate({ backgroundColor: "#fff"}, 500);
}

function recipients_change()
{
	show_total();
	// Hide All
	$(".recipient_wrap").hide();
	
	$("#recipients").show();
	
	var num = $("#qty").val();
	for (i = 1; i <= num; ++i)
	{
		className = ".recipient" + i;
		res = $(className);
		res.show();
	}
}

function processJSONResult(data)
{
	// possible types
	// - 'cc_error'
	// - 'error'
	// - 'success_email'
	// - 'success_post'

	switch (data[0])
	{
		// ------------------------------------------------------------	
    	case 'cc_error':
    		clearTimeout(errorTimeout);
    		$("label.error").remove();

    		$("img.ccv").after("<label id='cc_errors' generated='true' class='error'></label>");
   			$("#cc_errors").html("<ul><li>Sorry, we weren't able to process your credit card. <br>Please ensure the information is correct and try again.</li></ul>");
   			$("#cc_errors").css({backgroundColor: "#c00"});
			
			$("label.error").show().animate({ backgroundColor: "#777"}, 1500);
			
			$(".tac").hide();
			$(".submit").attr('disabled', false);
			$(".submit").html('<span>Pay Now</span>');
    		break;

		// ------------------------------------------------------------	
    	case 'error':
    		clearTimeout(errorTimeout);
			alert("We're sorry. There was an error while processing your voucher(s). If this error persists, please give us a call during business hours on (07) 5446 8977");
			$(".tac").hide();
			$(".submit").attr('disabled', false);
			$(".submit").html('<span>Pay Now</span>');
    		break;
		
		// ------------------------------------------------------------	
		case 'success_email':
			clearTimeout(errorTimeout);
			//console.log(data[1]);
			
			// Hide the form / show the message
			$("#theNewForm").slideUp();
			$("#voucher_complete").slideDown();
			
			// Add the HTML
			$("p.thanks").html("Thanks " + $("#firstname").attr("value") + "! Well done! Your voucher/s can be downloaded by clicking the button below. We've also emailed you copies, in case you want to print them out later. PLEASE check your SPAM folder if the voucher is not in your inbox.");
			$("p.thanks").after("<div class='download_wrap'><a href='#' class='voucher_download'>Download Vouchers</a></div>");
			$("a.voucher_download").after("<p class='adobe'><a title='Adobe Reader is required to open your vouchers. Click here to download it.' target='_blank' href='http://www.adobe.com/go/getreader'>Download it here.</a></p>");
			$("a.voucher_download").attr("href", data[1]);
			
			// Scroll to the message
			$('html, body').stop().animate({scrollTop: $("#headersub").offset().top + 200 }, 500, false);
			break;
		
		// ------------------------------------------------------------	
		case 'success_post':
			clearTimeout(errorTimeout);
			$("#theNewForm").slideUp();
			$("#voucher_complete").slideDown();
			$("p.thanks").html("Thanks " + $("#firstname").attr("value") + "! Your gift vouchers are now registered and will be mailed out on the next business day.");
			
			// Scroll to the message
			$('html, body').stop().animate({scrollTop: $("#headersub").offset().top + 200 }, 500, false);
			
			break;
			
	}
}

var voucher_process_options = 
{ 
	success: processJSONResult,
	url: 'vouchers_process.php',
	dataType: 'json'
};

$(document).ready(function()
{		
	$("#hideaddress").hide();
	$("#hideemail").show();
	//$("#recipients").hide();
	
    $(".recipient_wrap").each(function () 
    { 
		$(this).hide();
    });

	recipients_change();

	$("#qty").change(recipients_change);	
	$("#qty").change(show_total);
	
	// Validating Amount
	$("#amount").keyup(show_total);
	$("#amount").keydown(function(e) 
	{
		if(e.keyCode == 190)	// Disallow '.' char
	    	return false;

	});

	$("#deliverymode_post").click(function(event)
	{
		$("#hideaddress").slideDown();
		$("#recipientBlock").slideUp();
	});

	$("#deliverymode_email").click(function(event)
	{			
		$("#hideaddress").slideUp();	
		$("#recipientBlock").slideDown();
	});

	$("#theNewForm").validate({		
		submitHandler: function(form) 
		{
			$(".submit").attr('disabled', true);
			$(".submit").html('<span>Please wait…</span>');
			
			//console.log("The form was submitted!");

			// Submit the form			
			$(form).ajaxSubmit(voucher_process_options);

			// Start a timeout counter in the event that the script doesn't return
			// (Server seems to hang sometimes) — @by Hamish, July 19, 2013
			errorTimeout = setTimeout(function(){
				$("#theNewForm").slideUp();
				$("#voucher_timeout").slideDown();
			}, TIMEOUT_MSECONDS);
		},
		rules: 
		{
			firstname: "required",// simple rule, converted to {required:true}
					
			deliverymode: {
				required: true	
			},
			phone: {
     			required: "#deliverymode_post:checked"		
			},
			address: {// compound rule
     			required: "#deliverymode_post:checked"	
			},		
			email: {// compound rule
				required: true,
				email: true
			},					
			emailcheck: {
				equalTo: "#email"
			},
			amount: {
				required: true,
				number: true				
			},
			nameoncard1:{
				required: true
			},
			cardnumber1: {
				required: true,
				creditcard: true
			},
			ccv1: {
				required: true,
				number: true,
				rangelength: [3,4]				
			},
			chk_tc2: {
				required: true,				
			}			
        },
        
		messages: 
		{
			firstname: "Please specify your name.",
			deliverymode: "Please select a delivery method.",
			address: "Please enter your address.",
			phone: "Please enter a phone number.",
			email: "Please enter a valid email address.",
			emailcheck: "Your email addresses don't match. Try again.",
			// amount: "You must enter a numeric amount",
			nameoncard1: "Please enter the name on your credit card.",
			cardnumber1: "Please enter a valid credit card number.",
			ccv1: "Check the back of your credit card for the 3-4 digit CCV.",
			chk_tc2: "Please read and agree to our Terms and Conditions."
		},
		errorPlacement: function(error, element)
		{
			if (element.attr("name") == "ccv1")
			{
				error.insertAfter("img.ccv");
			}
			else if (element.attr("name") == "chk_tc2")
			{
				error.insertAfter("label[for='chk_tc2']");
			}
			else if (element.attr("name") == "amount")
			{
				error.insertBefore("#infoAmount");
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