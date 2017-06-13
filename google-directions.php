
<?  

?>

<div class="wrap">
	<div style="position: relative;" class="inner">
            		<img src="resources/bookmark.png" class="bookmark">
            		<br>
          		
					

				<form action="http://maps.google.com.au/maps" method="get" target="_blank" class="spiritform"> 
				<fieldset>
				<legend>Get Directions!</legend>
				<label for="saddr">Enter an address:</label>
				<input id="saddr" class="custom-input" name="saddr" type="text" placeholder="eg: landmark or an address"/>
				
				<button class="custom-submit" type="submit" value="Get Directions">
				<span>Get Directions!</span>
				</button>
				<input name="daddr" type="hidden" value="20 Ninderry Rd, Yandina QLD 4561" /> 
				<input name="hl" type="hidden" value="en" /> 
				
				</fieldset>
				</form>
				<small>You can enter a complete address to get exact directions but you must include state and postcode Eg: <em>20 Smith St, Smithville Qld 4556</em> 
				Or you can enter a landmark like: <em>Novotel Twin Waters</em>.</small>



	</div>
</div>