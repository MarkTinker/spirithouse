<?




?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Spirit House Search Testing Page</title>
<link rel="stylesheet" type="text/css" href="search.css">
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans+Std+Light' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<link type="text/css" media="screen" rel="stylesheet" href="example5/colorbox.css" />
<script type="text/javascript" src="/colorbox/jquery.colorbox.js"></script>



<script type="text/javascript">
$(function() {
		
	

    $(".search_button").click(function() {
        // Getting the value that user typed
        var searchString    = $("#search_box").val();
        // forming the queryString
        var data            = 'search='+ searchString;
        
        // if searchString is not empty
        if(searchString) {
            // ajax call
            $.ajax({
                type: "POST",
                url: "search-check.php",
                data: data,
                beforeSend: function(html) { // this happen before actual call
                    $("#results").html(''); 
                    $("#searchresults").show();
                    $(".word").html(searchString);
               },
               success: function(html){ // this happen after we get result
               		 
                    $("#results").show();
                    $("#results").append(html);
                    $(".example5").colorbox();
                   
              }
            });    
        }
        return false;
    });
    
  
});
</script>



</head>
<body>
<div id="container">


<div>

<p>This is demo for the <a class='example5' href="search.php">Spirit House Search Form</a> on </p>
</div>

<h1>Spirit House Search Form</h1>
<h2><span class="question">Q</span>How Do I Find Recipes or Hot Tips</h2>
		
		<p>
			See that big search box thingy below, that's where you can search our database of tips, techniques and - of course- recipes. You can be as specific or as vague as you like: search for recipes with chicken or a red curry paste. Give it a try >>>
		</p>


<div style="margin:20px auto; text-align: center;">
<form method="post" action="">
    <h2>Type <em>curry</em> to get results:</h2>
    <input type="text" name="search" id="search_box" class='search_box'/>

    <input type="submit" value="Search" class="search_button" /><br />
</form>
</div>      

<div>

<div id="searchresults">Search results for <span class="word"></span></div>

<div id="input-background">
					<input  name="i" id="i" maxlength="200" type="text" autocapitalize="off"/>
					<a id="iClear" style="display:none;" ></a>
					<label class="hidden" for="equal">Calculate</label>

					<input type="submit" id="equal" title="compute" value="Submit" />
					<div id="howTo"></div>
				</div>
				
				<div id="dropdown">
					<div class="left"></div>
					<div class="mid">
						<h1>Try your own.</h1>
						<img id="close-dropdown" src="images/homepage/try-close-btn.gif" title="close" alt="close" />

						<h2>Or try one of these:</h2>
						<ul id="dropdowninputs">
<li>
<span class="input">3/4 cup in tablespoons</span>
<span class="textdesc">Enter a unit conversion</span>
<span class="desc">(units)</span>
</li>
<li>
<span class="input">chancellor Germany</span>
<span class="textdesc">Ask about a political leader</span>

<span class="desc">(leader)</span>
</li>
<li>
<span class="input">Hurricane Isabel</span>
<span class="textdesc">Ask about a hurricane</span>
<span class="desc">(hurricane)</span>
</li>
<li>
<span class="input">actinium 227</span>
<span class="textdesc">Ask about a nuclear isotope</span>
<span class="desc">(isotope)</span>

</li>
<li>
<span class="input">d/dx Si(x)^2</span>
<span class="textdesc">Enter a derivative</span>
<span class="desc">(derivative)</span>
</li>
</ul>





<ul id="results" class="update">
</ul>

</div>
</div>


<script type="text/javascript">
//<![CDATA[
$("#input-label").css("display", "none");
$("#more-links").css("right", "80px");

var inputSize = 5;
var inputs = new Array(inputSize);
var animationStartTime = 200;
var fadeInTime = 1000;
var fadeOutTime = 600;
var waitTime = 2000;
var fadeInTimeDrop = 600;
var closeMenu = true;
var animation = true;
var focusLossOnPage = false;
var inputSeries = Math.floor(Math.random() * 10 + 1);
var seriesPage = 1; 
var visitThreshold = 4;
var closeDropdownLen = 3;

inputs[0] = { input: "june 23, 1988", bubbleDesc: "Try any date", eg: "(e.g. a birth date)", dropdownDesc: "date" }
inputs[1] = { input: "new york", bubbleDesc: "Enter any city", eg: "(e.g. a home town)", dropdownDesc: "city" }
inputs[2] = { input: "IBM Apple", bubbleDesc: "Compare two stocks", eg: "", dropdownDesc: "two stocks" }
inputs[3] = { input: "x^2 sin(x)", bubbleDesc: "Compute any math formula", eg: "", dropdownDesc: "math formula" }
inputs[4] = { input: "Andrew, Barbara", bubbleDesc: "Enter any two first names", eg: "", dropdownDesc: "two first names" }

$(document).ready(function() {
	$("#exbtop").after("<li id=\"settings-link\"><a href=\"/homesettings.html\">Settings</a><span class=\"pipe\">|</span></li>");
	var tips = $.cookie('WolframAlphaHomepageTips');
	var visits = $.cookie('WolframHomepageVisits');
	var returningFromSettings = ((window.location+ "").indexOf('animation=none') != -1);
	
	//Fade in hack for firefox on vista and windows 7
	if($.browser.mozilla && 
			(navigator.userAgent.indexOf("Windows NT 6.0") > -1 || navigator.userAgent.indexOf("Windows NT 6.1") > -1))  {
		$("#fadeinput").css({opacity : ".45", color : "#000"});
	}
	

	
	$("#i").val("").focus();

       
	
		setTimeout( function () {
		 	$("#input-label").fadeIn(fadeInTime , function () {
				if(displayTips && animation)
		 			animateBubbles(0);
		 	});
		}, animationStartTime); 

	if(displayTips) {
		$("#more-links").css("right", "35px");
		$("#input-links").prepend("<li><a id=\"show-hints\" href=\"#\">Show Hints</a><span class=\"pipe\">|</span></li>");

		$("#show-hints").click(function (e) {
			e.preventDefault();
			$("#fadeall").remove();
			$("#dropdown").show();
			animation = false;
			closeMenu = false;
		});

		// parse html into javascript json array
		var i = 0;
		$("#dropdowninputs li").each(function() {
			if (i < 5) {
				inputs[i].input = $(this).find(".input").html();
				inputs[i].bubbleDesc = $(this).find(".textdesc").html();
				inputs[i].eg = "";
				inputs[i].dropdownDesc = $(this).find(".desc").html();
				i++;
			} else {
				return false;
			}
		});

		$("body").click(function (e) {
			var id = e.target.id;
			if(id != "fadeinput" && id != "i") {
				focusLossOnPage = true;
				if(closeMenu) {
					$("#dropdown").hide();
				}
			}
		});

		$("#dropdown")
			.mouseover(function() { closeMenu = false; })
			.mouseout(function() { closeMenu = true; });
	
		$("#dropdown ul li").click(function (e) {
			$("#i").val($("span.input", this).html()).focus();
			$("#dropdown").hide();
			$("#howTo").show();
                        window.scrollTo(1200,0);
		});
		
		$("#i")
		.click (function () {
			if(animation) {
				$("#fadeall").remove();
				$("#dropdown").fadeIn(fadeInTime);
				animation = false;
				$("#i").val("")
			}
		})
		.focus(function () {
			if(focusLossOnPage) {
				if(animation) {
					$("#fadeall").remove();
					$("#dropdown").fadeIn(fadeInTime);
					animation = false;
					$("#i").val("");
				}
			}
		})
		.keyup(function (e) {
			if(animation && e.keyCode != 13 && e.keyCode != 17 && e.keyCode != 116) { 
				$("#fadeall").remove();
				$("#dropdown").fadeIn(fadeInTimeDrop);
				animation = false;
			}else {
				if(e.keyCode != 17 && e.keyCode != 116 && e.keyCode != 13) {
					var inputLen = $("#i").val().length;
					if(inputLen >= closeDropdownLen) {
						$("#dropdown").hide();
					}
					if(inputLen < closeDropdownLen) {
						$("#dropdown").show();
					}
				}
			}
		});
		
		$("#fadeinput").click( function () {
			$("#fadeall").remove();
			$("#dropdown").fadeIn(fadeInTimeDrop);
			animation = false;
			$("#i").focus();
		});
		
		$("#close-dropdown").click (function () {
			$("#dropdown").hide();
		});
	}


  
function animateBubbles(index) {
	if(index < inputSize) {
		$("#bubble-desc").html(inputs[index].bubbleDesc + "");
		$(".eg").html(inputs[index].eg + "");
		$("#fadeinput").html(inputs[index].input + "");
		$("#fadeall").fadeIn(fadeInTime, 
			function () {
				setTimeout( function () {
					$("#fadeall").fadeOut(fadeOutTime, function () {
						animateBubbles(++index);
					})
				}, waitTime);
			});
	} else {
		$("#fadeall").remove();
		$("#dropdown").fadeIn(fadeInTime);
		animation = false;
		$("#i").val("");
	}
}
//]]>
</script>


  
</body>
</html>











