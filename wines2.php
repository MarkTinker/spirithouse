<? 

$pageTitle = "Spirit House Wine Guru.";
// php some code in here for the wine sections
$sectionsList = "['#restaurant_restaurant', '#restaurant_bookings', '#restaurant_menu', '#restaurant_functions', '#restaurant_awards']";;
$bigText_main = "Be A Wine Guru";
$bigText_sub = " <em>Wines we recommend you to try </em>";

include("includes/header.inc.php"); 
include("databaseconnect.php");	



?>



<div class="container section" id="searching">
	
	

	<div class="row">
	
		
          				<?                               
							$sql="select * from wines order by wineCat, wineBtl" ;
							$result=mysql_query($sql);
							$no=mysql_num_rows($result);
		
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
								$colcount=1;
								echo "<div class='row pad20'></div><div class='twelvecol last title '>";
								
								switch($wineCat) {
								case 1: echo "<h3 class='nav red'>Sparkling</h3>";break;
								case 2: echo "<h3 class='nav red'>Riesling</h3>";break;
								case 3: echo "<h3 class='nav red'>Sauvignon Blanc</h3>";break;
								case 4: echo "<h3 class='nav red'>Aromatics &amp; Varietals</h3>";break;
								case 5: echo "<h3 class='nav red'>Pinot Gris</h3>";break;
								case 6: echo "<h3 class='nav red'>Chardonnay</h3>";break;
								case 7: echo "<h3 class='nav red'>Ros&eacute;</h3>";break;
								case 8: echo "<h3 class='nav red'>Pinot Noir</h3>";break;
								case 9: echo "<h3 class='nav red'>Merlot</h3>";break;
								case 10: echo "<h3 class='nav red'>Red Varietals &amp; Blends</h3>";break;
								case 11: echo "<h3 class='nav red'>Shiraz/Syrah</h3>";break;
								case 12: echo "<h3 class='nav red'>Ciders</h3>";break;
								case 13: echo "<h3 class='nav red'>Beers</h3>";break;
				
								}
								echo "</div>";	
								
								}
			
							if($wineGls==0){$wineGls='&nbsp';}else{$wineGls='<sup>&#36;</sup>'.$wineGls;}
							if($wineBtl==0){$wineBtl='&nbsp;';}else{$wineBtl='<sup>&#36;</sup>'.$wineBtl;}
							
							if (!empty($wineDesc)){
								if ($colcount % 2 == 1){
							
							?>
		   						<div class="fivecol specs sidebar">
			   						<h4><span class='teal'><? echo "$wineName"; ?></span> </h4>
			   						<ul>
				   						<li class='wide'><strong>What the Guru Says:</strong><? echo " $wineDesc"; ?> </li>  
		   								<li> <strong>Glass</strong> <? echo "$wineGls"; ?></li>
                                    	<li> <strong>Bottle</strong>  <? echo "$wineBtl"; ?></li>
                                    	<li> <strong>Vintage</strong> <? echo "&nbsp; $wineYear"; ?></li>
                                    	<li> <strong>Region</strong>  <? echo "$wineRegion - $wineState"; ?></li>
										                     
			   						</ul>
			   						<div class='clear'></div>		
		   						</div>
		   						<div class="twocol specs"> </div>
		   							   						
		   					<?
			   					} ELSE {
				   					if (!empty($wineDesc)){
					   		?>
					   					<div class="fivecol specs sidebar last">
					   					<h4><span class='teal'><? echo "$wineName"; ?></span> </h4>
					   					<ul>
				   						<li class='wide'><strong>What the Guru Says:</strong><? echo " $wineDesc"; ?> </li>  
		   								<li> <strong>Glass</strong> <? echo "$wineGls"; ?></li>
                                    	<li> <strong>Bottle</strong>  <? echo "$wineBtl"; ?></li>
                                    	<li> <strong>Vintage</strong> <? echo "&nbsp; $wineYear"; ?></li>
                                    	<li> <strong>Region</strong>  <? echo "$wineRegion - $wineState"; ?></li>
										                     
			   							</ul>
			   							<div class='clear'></div>	
		   								</div>
		   								<div class="row"></div>

				   			<?	
				   					
			   						} ELSE	{
				   					 			echo "<div class='fourcol specs last> &nbsp; </div><div class='row pad20'></div>";
				   					 		}
			   						   					
								}
								$colcount=$colcount+1; }
								
								$oldwineCat = $wineCat;
								}
							?>
			
							
			


					</div><!-- end sevencol -->
	
					<div class="fivecol content pad20 last"> 
						
	
					</div>
	      
</div>
<!-- end#newsletter.container -->





<? include("includes/footer.inc.php");  ?>

