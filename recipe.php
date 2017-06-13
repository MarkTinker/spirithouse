<? 
$pageTitle = "Spirit House: Recipes.";
$metaContent = "Free Thai Recipes from Spirit House Sunshine Coast";
$sectionsList = "['#free_recipes', '#searching']";
$bigText_main = "Recipes for free";
$bigText_sub = " <em>they're free, what's not to love about that?</em>";





include("includes/header.inc.php"); 



?>
<div class="container section" id="free_recipes">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a href="#free_recipes" class='current'>Free Recipes</a><span class='dot'>&middot;</span>
				<a href="#searching">Search Recipe Database</a><span class='dot'>&middot;</span>
			</h3>			
		

		<p class="info">Hooked on our citrus caramel pork? Can't get enough of our whole crispy fish - help yourself to some of our most requested recipes below.
		</p>
		</div>
		
		<!-- end.twelvecol -->
	</div>
	<!-- end.row -->
      
	
	<div class="row pad20" >
		
		<div class="threecol sidebar">

		</div>
		<!-- end.sidebar -->
			<div class="threecol content">
		
				<h4>Citrus Caramel Pork</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe1'> <img class="shadow"  src="resources/freerecipe1.jpg" alt="citrus caramel pork recipe" />
				</a>
		
		</div>
		
		<div class="threecol content">
		
				<h4>Whole Crispy Fish</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe2'> <img class="shadow"  src="resources/freerecipe2.jpg" alt="Whole Crispy Fish with Tamarind Sauce" />
				</a>
		
		</div>
		
	
		
		<div class="threecol content last">
				<h4>Chilli Jam Chicken</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe3'> <img class="shadow"  src="resources/freerecipe3.jpg" alt="Chilli Jam Chicken" />
				</a>
				
		</div>
		
	

		<!-- end.content -->	
	</div>
	<!-- end.row -->	
	
	
	<div class="row pad20">
		
		<div class="threecol sidebar">
	

		</div>
		<!-- end.sidebar -->
		
		<div class="threecol content">
				<!-- <span class="smallheadline">Our most requested:</span> -->
				<h4>Tamarind Glazed Chicken</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe4'> <img class="shadow"  src="resources/freerecipe4.jpg" alt="tamarind glazed chicken recipe" />
				</a>
		
		</div>
		
		<div class="threecol">
		
				<h4>Thai Fried Rice</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe5'> <img class="shadow"  src="resources/freerecipe5.jpg" alt="Thai Fried Rice recipe" />
				</a>
		
		</div>
		
	
		
		<div class="threecol last">
				<h4>Spiced Pumpkin Soup</h4>			
				<a href='http://www.spirithouse.com.au/download.php?filetype=1&filename=freerecipe6'> <img class="shadow"  src="resources/freerecipe6.jpg" alt="spiced pumpkin soup recipe" />
				</a>
				
		</div>		
	

		<!-- end.content -->	
	</div>
	<!-- end.row -->





</div>
<!-- end#newsletter.container -->


<div class="container section" id="searching">
	<div class="row">
		<div class="twelvecol last title">
			<h3 class='nav'>
				<a href="#free_recipes">Free Recipes</a><span class='dot'>&middot;</span>
				<a href="#searching" class='current'>Search Recipe Database</a><span class='dot'>&middot;</span>

			</h3>
			<p class="info">Six measely recipes we hear you say - au contraire we have a motherlode of recipes which we haven't had time to 
			photograph and make all pretty.</p> 

	</div>

</div><!-- end.row -->
	

<div class="row">
		<div class="fivecol">
						
			
		</div>
	
	
	<div class="sevencol content last">

				<h4><span class="gray">Q: &nbsp;</span> How Do I Find Recipes or Hot Tips</h4>
		
				
				<div class="food"><small>
					Search our database for recipes, tips and techniques. Need some ideas for <span class="teal">chicken</span>. 
					How about a nice <span class="teal">stir fry</span>? Want to make a <span class="teal">curry paste</span>? Just type in a key word &hellip; <em>et voil&agrave;</em>. 
				</small>
				</div>

				<div class="wrap">
            				<div class="inner" style="position: relative;">
            					<img class="bookmark" src="resources/bookmark.png">
            					<br>
							
								<form method="post" action="" id="subForm" class="spiritform">
				    			<fieldset>
          					
          						<legend>Search our Recipe Database</legend>
          						<p>Enter an ingredient eg: <span class="teal"><em>prawns</em></span>  &#8230;  <p>
							<div>
					 
				    	<input type="text" name="search" id="search_box" class='search_box' placeholder="pork, caramel, prawns, stir fry, red curry... go nuts" />
				
				     	<button type="submit" value="Search" class="search_button"><span>Show me the Money!</span></button>
				 
						</fieldset>
				     
				     <!-- <input type="submit" value="Search" class="search_button" /><br /> -->
					</form>
				</div> 
			

				
			</div>
			


	</div><!-- end sevencol -->
</row>
<row>
<div class="twelvecol last content"><div id="searchresults" class="pad20"><h4>Search results for <span class=" teal word"></span></h4></div></div>
</row>
<row>	
	<div class="fivecol content pad20">
	<ul id="results" class="update"></ul>
	
	</div>	
		
	<div class="sevencol last">
		<div id="randomdiv" class="recipes">&nbsp;</div>
	</div>










</div>
	<!-- end.row -->
      
</div>
<!-- end#newsletter.container -->





<? include("includes/footer.inc.php");  ?>
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
                url: "recipe-check.php",
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
