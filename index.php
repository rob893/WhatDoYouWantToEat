<?php

require_once("header.php");

// if(isset($_POST['polyPoints'])){
// 	$latlngs = explode(",", $_POST['polyPoints']);
// 	$polyPoints = [];
// 	$point = [];
// 	$j = 0;
// 	$i = 0;
// 	foreach($latlngs as $latlng){
// 		if($i < 2){
// 			$point[$i] = $latlng;
// 			$i++;
// 		}
		
// 		if($i == 2){
// 			$polyPoints[$j] = $point;
// 			$j++;
// 			$i = 0;
// 		}
// 	}
	
// 	$guid = uniqid();
	
// 	$i = 1;
// 	echo "<div class='container-fluid'>";
// 	echo 'The following points have been inserted into the database: <br>';
// 	foreach($polyPoints as $polyPoint){
// 		$lat = $polyPoint[0];
// 		$long = $polyPoint[1];
		
// 		echo '
// 			Point number: '.$i.'<br>
// 			lat: '.$lat.'<br>
// 			long: '.$long.'<br>
// 			polyGuid: '.$guid.'<br><br>
// 		';
		
// 		$i++;
// 		$sqlInsert = $conn->prepare("INSERT INTO Polygons(poly_guid, lat, longitude) VALUES(?, ?, ?)");
// 		$sqlInsert->bind_param('sdd', $guid, $lat, $long);
		
// 		$sqlInsert->execute();
// 		$sqlInsert->close();
// 	}
	
// 	//echo '<pre>';
// 	//echo '<p>'.$guid.'</p>';
// 	//print_r($polyPoints);
// 	//echo '</pre>';
// 	echo '</div>';
// }

// $apiLink = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-33.8670522,151.1957362&radius=1500&type=restaurant&keyword=asian&key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM";
// $data = json_decode(file_get_contents($apiLink), true);
// echo "<pre>";
// //print_r($data);
// echo "</pre>";
// foreach($data['results'] as $name){
//     echo '<p>'.$name['name'].'</p>';
// }
// echo "yay";
?>

<div class='container-fluid'>
	<div style="display: none;" id="map"></div>
</div>

<script>
	var polyPoints = []; //array to hold latitude and longitude numbers, not the latlng objects.
	var polygonPath = []; //array to hold the polygon's path. This array holds latlng objects.
	var currentCoords = [];
	var markers = [];
	var polys = []; //array to hold the polygon objects.
	var map;
	
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 18,
			center: {lat: 33.252756903371775, lng: -85.81798553466797 } //Default location is Ashland AL
		});
		currentCoords[0] = 33.252756903371775;
		currentCoords[1] = -85.81798553466797;
		getLocation();  //Centers map on user's location. Requires the server to have an SSL certificate.
		document.getElementById("currentCoords").value = currentCoords;
		
		var poly = new google.maps.Polygon({
			paths: polygonPath,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35
		});
		poly.setMap(map);
		polys.push(poly);
		
		map.addListener('click', function(e) {
			placeMarker(e.latLng, map);
			polys[0].setPath(polygonPath);
		});
		
		polys[0].addListener('click', function(e) {
			placeMarker(e.latLng, map);
			polys[0].setPath(polygonPath);
		});
	}

	function placeMarker(latLng, map) {
		var marker = new google.maps.Marker({
			position: latLng,
			map: map
		});
		markers.push(marker);
		
		var point = [latLng.lat(), latLng.lng()];
		polyPoints.push(point);
		document.getElementById("polyPoints").value = polyPoints;
		
		polygonPath.push(latLng);
	}
	
	function deleteMarkers() {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
        }
		markers = [];
		
		for (var i = 0; i < polys.length; i++) {
			polys[i].setMap(null);
        }
		polys = [];
		polygonPath = [];
		polyPoints = [];
		
		var poly = new google.maps.Polygon({
			paths: polygonPath,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35
		});
		poly.setMap(map);
		polys.push(poly);
		
		polys[0].addListener('click', function(e) {
			placeMarker(e.latLng, map);
			polys[0].setPath(polygonPath);
		});
	}
	
	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
			
		} else {
			alert("Geolocation is not supported by this browser.");
		}
	}
	
	function showPosition(position) {
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;
		currentCoords[0] = lat;
		currentCoords[1] = lng;
		document.getElementById("currentCoords").value = currentCoords;
		map.setCenter(new google.maps.LatLng(lat, lng));
	}
	
</script>

<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM&callback=initMap">
</script>

<br>

<div class='container-fluid'>
	<form action='results.php' method='post' enctype='multipart/form-data'>
		<div class='row'>
			<div class='col-sm-4'>
				<div class='form-group'>
            		<input type='hidden' name='currentCoords' id='currentCoords'>
            		<label for='keyword'>Keyword (Not Required):</label><br>
            		<input type='text' name='keyword' id='keyword'><br><br>
            		<label for='distance'>Miles willing to travel (Not Required):</label><br>
            		<input type='number' name='distance' id='distance'><br><br>
            		<input name='submit' type='submit' value="I Don't FUCKING Know">
            	</div>
            </div>
    	</div>
	</form>
</div>

<?php
require_once("footer.php");
?>