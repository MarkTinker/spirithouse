<? 

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Playground for styles etc</title>

<style type="text/css">
.question:before {
    background-color: #EEEEEE;
    border-radius: 2em 2em 2em 2em;
    color: #222222;
    content: "Q";
    display: block;
    float: left;
    font-size: 28px;
    line-height: 32px;
    margin-right: 0.5em;
    padding: 0.2em;
    text-align: center;
    text-shadow: 0 1px 0 #FFFFFF;
    width: 1.2em;
}

.question {
    font-size: 22px;
    line-height: 40px;
    margin-bottom: 0.3em;
    margin-left: 10px;
    margin-top: 0.6em;
    padding-top: 0.2em;
}


.answer:before {
    background-color: #E2F5FF;
    border-radius: 2em 2em 2em 2em;
    color: #111144;
    content: "A";
    display: block;
    float: left;
    font-size: 28px;
    line-height: 32px;
    margin-left: -2.1em;
    margin-right: 0.5em;
    padding: 0.2em;
    text-align: center;
    text-shadow: 0 1px 1px #FFFFFF;
    width: 1.2em;
}

.answer {
    color: #333333;
    line-height: 24px;
    margin-bottom: 1.5em;
    margin-left: 10px;
    padding-left: 58px;
}


</style>

</head>

<body>

<p><a href="search/search.php"> GO TO SEARCH PAGE</a></p>

<h1>Fansflood test</h1>
<script src="https://s3.amazonaws.com/fansflood/93f8a21a496c1b81" type="text/javascript"></script>
<div class="freerecipes" id="freerecipes">

Here are the recipes

</div>
				
				
<h1>Test question and answer style from photojojo</h1>
<p class="question">How the heck do I use this?</p>
<p class="answer">First, download the free ioShutter app on your iPhone, iPad, or iPod Touch and fire it up. Plug one end of the ioShutter Camera Remote into your iPhone, iPad, or iPod Touch's headphone jack and the other end into the cable release port of your camera. That’s it!</p>

<p class="question">Will it work with my camera? </p>
<p class="answer">The ioShutter works with Canon cameras and some Samsung, Hasselblad, and Contax cameras. There are two kinds to choose from: E3 and N3. Pick depending on your camera model.<br><br>N3 is compatible with Canon cameras 5D Mark III, 5D Mark II, 5D, 7D, EOS 1D X, EOS-1D Mark IV, EOS-1Ds Mark III, EOS-1Ds MARK II, EOS-1Ds, EOS-1D Mark III, EOS-1D MARK II, EOS-1D MARK IIn, EOS-1D, EOS 10D, 20D, 30D, 40D, 50D, EOS D60 (2002) EOS D30 (2000), EOS 3 and EOS 1V. </br></br>E3 is compatible with Canon G1X, G10, G11, G12, 60D, 1000D, 1100D, 600D, 100D, 550D, 500D, 450D, 400D, 350D, 300D, EOS Digital Rebel series, Kiss F, X5, X4, X3, X50, XS, Elan II/ IIE, Elan 7/7E, Rebel Ti, T1i, T2i, Pentax K5, K7, Super, K100D, D110D, *ist Ds2, +ist D, +ist Ds, *ist, *ist DL, Samsung GX-20, GX-10, GX-1L, GX-1S, Hasselblad H1, H2, H3, H4, Contax 645. </br></br>Nikon shutterbugs, stay tuned!</p>

<p class="question">Can I call or text while it's going?</p>
<p class="answer">No, it’ll stop your exposure. You should put your phone on Airplane Mode or finish that text before you start your shoot. You don’t want to be bugged while you’re perfecting those star trails, anyway.</p>

<p class="question">Will my exposure stop if my iPhone, iPad, or iPod Touch falls asleep?</p>
<p class="answer">The app keeps your device awake, so it won't fall asleep. w00t.</p>

<p class="question">How fast will it shoot photos between sounds when in the sound trigger mode?</p>
<p class="answer">You get 3 seconds between triggers.</p>

<p class="question">Can I combine modes?</p>
<p class="answer">Why, yes, you can! Almost all of the modes can be combined with each other. For example, you can trigger a time-lapse with sound, or you can set the timer to set off a bulb exposure. Convenient!</p>

<p class="question">Does it let you trigger videos?</p>
<p class="answer">Only if your camera's video recording  is triggered by the shutter. Some do, some don't. Check it out! We'll wait.</p>

<p class="question">So what does the app look like?</p>
<p class="answer">Just like this:</p>



		
		<div class="threecol sidebar">
			<h4>Have you booked before?</h4>
				<p>Quickly search for classes you've booked in the past and future"</p>
				<form method="post" action="">
	    		<h2>Type in your email or phone number</h2>
	    		<input type="text" name="search" id="search_box" class='search_box' />
	    		<input type="submit" value="Search" class="search_button" /><br />
				</form>
	 
				<div id="searchresults"> <span class="word"></span></div>	
				<div id="results" class="update"></div>
		</div>
		<!-- end.sidebar -->
		<!-- end#school-questions.container -->


<img src="https://www.paypal.com/en_AU/i/logo/PayPal_mark_37x23.gif" align="left" style="margin-right:7px;"><span style="font-size:11px; font-family: Arial, Verdana;">The safer, easier way to pay.</span>



<script>
$(function() {

    $(".search_button").click(function() {
        // getting the value that user typed
        var searchString    = $("#search_box").val();
        // forming the queryString
        var data            = 'email='+ searchString;

        // if searchString is not empty
        if(searchString) {
            // ajax call
            $.ajax({
                type: "POST",
                url: "school_email.php",
                data: data,
                beforeSend: function(html) { // this happen before actual call
                    $("#results").html('');
                    $("#searchresults").show();
                    $(".word").html('Here are the classes you have booked with us:');
               },
               success: function(html){ // this happen after we get result
                    $("#results").show();
                    $("#results").append(html);
              }
            });
        }
        return false;
    });
});



</body>
</html>