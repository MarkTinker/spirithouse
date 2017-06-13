<?php




?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="search.css"> 
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans+Std+Light' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="1140.css">
<!--  <link rel="stylesheet" type="text/css" href="styles.css"> -->
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

</head>
<body>

<div class="container">
<div class="row">
<div class="fivecol"> &nbsp;</div>
	<div class="sevencol content last">

				<div><p>This is demo for the <a class='example5' href="search.php">Spirit House Search Form</a> on </p></div>
				
				
				
				
				
				
				<h1>Spirit House Search Form</h1>
				
				 
						
						
				<h2><span class="question">Q</span>How Do I Find Recipes or Hot Tips</h2>
				<p>
						
				
				<p>
					See that big search box thingy below, that's where you can search our database of tips, techniques and - of course- recipes. 
					You can be as specific or as vague as you like: search for recipes with chicken or a red curry paste. Give it a try >>>
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

				

		
			</div>


	</div><!-- end eightcol -->
	<div class="fivecol">
	<ul id="results" class="update"></ul>
	
	</div>	
		
	<div class="sevencol last">
		<div id="randomdiv">&nbsp;</div>
	</div>


</div><!-- end row -->
</div> <!-- end container -->
</body>
</html>


  
