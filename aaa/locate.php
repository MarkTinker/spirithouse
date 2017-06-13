<?

?>
<!DOCTYPE html>  
<html>
<head>
<title>Geolocation API Example</title>
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<script type="text/javascript">
var id = "data";

function setText(val, e) {
    document.getElementById(e).value = val;
}

function insertText(val, e) {
    document.getElementById(e).value += val;
}

var nav = null; 

function requestPosition() {
  if (nav == null) {
      nav = window.navigator;
  }
  if (nav != null) {
      var geoloc = nav.geolocation;
      if (geoloc != null) {
          geoloc.getCurrentPosition(successCallback);
      }
      else {
          alert("geolocation not supported");
      }
  }
  else {
      alert("Navigator not found");
  }
}



function successCallback(position)
{
   setText(position.coords.latitude, "latitude");
   setText(position.coords.longitude, "longitude");
   setText(position.coords.accuracy, "accuracy");
   setText(position.timestamp, "timestamp");
   
}



</script>


</head>
<body>
	

	<!-- spirithouse is in a box between -26.550 and -26.552, 152.958 and 152.960 -->
<label for="latitude">Latitude: </label><input id="latitude" /> <br />
<label for="longitude">Longitude: </label><input id="longitude" /> <br />
<label for="accuracy">accuracy: </label><input id="accuracy" /> <br />
<label for="timestamp">timestamp: </label><input id="timestamp" /> <br />
<input type="button" onclick="requestPosition()" value="Get Latitude and Longitude"  /> 

<?
   //$getthevalueofid = var id;
  / echo "this is the id: $getthevalueofid";
 ?>

</body>
</html>