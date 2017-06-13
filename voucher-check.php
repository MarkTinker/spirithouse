<? 
$pageTitle = "Restaurant Voucher Check.";
$sectionsList = "['#searching']";
$bigText_main = "Search Vouchers";
$bigText_sub = " <em>Enter search terms to find vouchers</em>";





//include("includes/header.inc.php"); 




?>
<html>
	<head>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	</head>


<div class="container section" id="searching">
	
	

<div class="row">
	
	
	
	<div class="sevencol content">

				<h4><span class="gray">Q: &nbsp;</span> How Do I Find Voucher Details</h4>
		
				
				<div class="food"><small>
					Easy! You can enter their <span class="teal">name</span> 
					or <span class="teal">voucher number</span> or even the <span class="teal">surname</span> of the person who gave them the voucher</em>. 
				</small>
				</div>

				<div class="wrap">
            				<div class="inner" style="position: relative;">
            					
            					<br>
							
								<form method="post" action="" id="subForm" class="spiritform">
				    			<fieldset>
          					
          						<legend>Search For Vouchers</legend>
          						<p>Enter details eg: <span class="teal"><em>first name</em></span>  &#8230;  <p>
							<div>
					 
				    	<input type="text" name="search" id="search_box" class='search_box' placeholder="Voucher number or name etc." />
				
				     	<button type="submit" value="Search" class="search_button"><span>Find it Baby!</span></button>
				 
						</fieldset>
				     
				     <!-- <input type="submit" value="Search" class="search_button" /><br /> -->
					</form>
				</div> 
			

				
			</div>
			


	</div><!-- end sevencol -->
	
		<div class="fivecol content pad20 last"> 
			<ul id="results" class="update"></ul>
	
		</div>
	      
</div>
</div>
<!-- end#newsletter.container -->




</body>
</html>
<script>


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
                url: "voucher-search.php",
                data: data,
                beforeSend: function(html) { // this happen before actual call
                    $("#results").html(''); 
                    $("#searchresults").show();
                    $(".word").html(searchString);
               },
               success: function(html){ // this happen after we get result
               		 
                    $("#results").show();
                    $("#results").append(html);
                    //$(".example5").colorbox();
                   
              }
            });    
        }
        return false;
    });
    
  
});


</script>

    <script typ="text/javascript">
        $(document).ready(function() {
        	$(".recipe").live("click", function(evt) {
        	   evt.preventDefault();
             $("#randomdiv").load($(this).attr('href'));
          
          })
        })
    </script>
