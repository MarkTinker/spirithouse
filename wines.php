<? 

$pageTitle = "Restaurant Voucher Check.";
// php some code in here for the wine sections
$sectionsList = "['#restaurant_restaurant', '#restaurant_bookings', '#restaurant_menu', '#restaurant_functions', '#restaurant_awards']";;
$bigText_main = "Be A Wine Guru";
$bigText_sub = " <em>Wines we recommend you to try </em>";

include("includes/header.inc.php"); 
include("databaseconnect.php");	



?>



<div class="container section" id="searching">
	
	

<div class="row">
	
	
	
	<div class="sevencol content">

				<div id="menuandwinelist-winelist" class="tabsection wine";>
							<p>Discover our more eclectic wines and set out on a wine adventure</p>
          				<?                               
							$sql="select * from wines order by wineCat, wineBtl" ;
							$result=mysql_query($sql);
							$no=mysql_num_rows($result);
		
						?>
                              
                                
    					<table cellpadding="0" cellspacing="0" border="1" width="100%">

						<?
							$bgcolor="#CCCCCC";
							for($t=0;$t<$no;$t++)
							{			
							$wineId=trim(mysql_result($result,$t,"wineId"));
							$wineCat=trim(mysql_result($result,$t,"wineCat"));
							$wineYear=trim(mysql_result($result,$t,"wineYear"));
							$wineName=trim(mysql_result($result,$t,"wineName"));
							$wineRegion=trim(mysql_result($result,$t,"wineRegion"));
							$wineState=trim(mysql_result($result,$t,"wineState"));
							$wineGls=trim(mysql_result($result,$t,"wineGls"));
							$wineBtl=trim(mysql_result($result,$t,"wineBtl"));
							$wineDesc=trim(mysql_result($result,$t,"wineDesc"));
							if($oldwineCat<>$wineCat) { 
								echo "<tr> <td><b>Glass</b></td><td><b>Bottle</b></td><td colspan=3>";
								switch($wineCat) {
				
								case 1: echo "<h2>Sparkling</h2>";break;
								case 2: echo "<h2>Riesling</h2>";break;
								case 3: echo "<h2>Sauvignon Blanc</h2>";break;
								case 4: echo "<h2>Aromatics &amp; Varietals</h2>";break;
								case 5: echo "<h2>Pinot Gris</h2>";break;
								case 6: echo "<h2>Chardonnay</h2>";break;
								case 7: echo "<h2>Ros&eacute;</h2>";break;
								case 8: echo "<h2>Pinot Noir</h2>";break;
								case 9: echo "<h2>Merlot</h2>";break;
								case 10: echo "<h2>Red Varietals &amp; Blends</h2>";break;
								case 11: echo "<h2>Shiraz/Syrah</h2>";break;
								case 12: echo "<h2>Ciders</h2>";break;
								case 13: echo "<h2>Beers</h2>";break;
				
				
								}
								echo "</td></tr>";	
								
								}
			
							if($wineGls==0){$wineGls='&nbsp';}else{$wineGls='<sup>&#36;</sup>'.$wineGls;}
							if($wineBtl==0){$wineBtl='&nbsp;';}else{$wineBtl='<sup>&#36;</sup>'.$wineBtl;}
							if (!empty($wineDesc)){
								
								
							
			
							?>
		   						<tr class="row1">
		   								<td width="10%"> <? echo "$wineGls"; ?></td>
                                    	<td width="10%"> <? echo "$wineBtl"; ?></td>
                                  		<td width="2%"><sup><? echo "$wineYear"; ?></sup></td>
                                    	<td width="48%"><h3><span class='teal'><? echo "$wineName"; ?></span> </h3><br>
                                    	</td>
                                    	<td width="30%"> 
	                                    	<? echo "<small> This wine comes from $wineRegion - $wineState. $wineDesc</small>"; ?> 
	                                    </td>
                                    	
                                    		
	                                    
                                      
                                  	</tr>
                                
							<?
								}
								$oldwineCat = $wineCat;
								}
							?>
			
							</table>
			


	</div><!-- end sevencol -->
	
		<div class="fivecol content pad20 last"> 
			<ul id="results" class="update"></ul>
	
		</div>
	      
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
