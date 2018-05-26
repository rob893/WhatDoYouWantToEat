<?php 
require_once("header.php");
?>
<div class='container-fluid'>

<?php
$coords = explode(",", $_POST['currentCoords']);

$keyword = $_POST['keyword'];
$meters = (int)$_POST['distance'] * 1610;

if($meters == 0){
    $meters = 15000;
}

$apiLink = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$coords[0].",".$coords[1]."&radius=".$meters."&type=restaurant&keyword=".$keyword."&key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM";
$data = json_decode(file_get_contents($apiLink), true);
echo "<pre>";
//print_r($data);
echo "</pre>";



// foreach($data['results'] as $name){
//    // echo '<p>'.$name['name'].'</p>';
// }
if(count($data['results']) == 0){
	?>
	
    <p>Sorry, no results found!</p>
	<form action='index.php' method='post' enctype='multipart/form-data'>
    	<br>
        <input name='submit' type='submit' value="Try another search">
    </form>
	
	<?php
} else {
    $selection = rand(0, count($data['results']) -1);
	if($data['results'][$selection]['opening_hours']['open_now'] == 1){
		$open = "Yes";
	} else if($data['results'][$selection]['opening_hours']['open_now'] == 0){
		$open = "No";
	} else {
		$open = "No posted hours";
	}
	
    echo "<p>Number of results: ".count($data['results'])."</p><p>How about this place?</p>";
    echo $data['results'][$selection]['name']."<br>";
    echo $data['results'][$selection]['vicinity']."<br>";
	echo "Open Now: ".$open."<br>";
	
    ?>
    <div class='container-fluid'>
		<div id="map"></div>
    </div>
    
    <script>
    	function initMap() {
    		var myLatLng = {lat: <?php echo $data['results'][$selection]['geometry']['location']['lat']; ?>, lng: <?php echo $data['results'][$selection]['geometry']['location']['lng']; ?>};
        	
    		map = new google.maps.Map(document.getElementById('map'), {
    			zoom: 18,
    			center: myLatLng
    		});

    		var marker = new google.maps.Marker({
    	          position: myLatLng,
    	          map: map,
    	          title: 'EAT HERE DAMNIT!'
    	    });
    	}
    </script>
    
    <script async defer
    	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL2PSZaOLv96XKHrWuENmPT8kwnn8vLRM&callback=initMap">
    </script>
	
	<form action='#' method='post' enctype='multipart/form-data'>
    	<input type='hidden' name='currentCoords' id='currentCoords' value="<?php echo $_POST['currentCoords'] ?>">
    	<input type='hidden' name='keyword' id='keyword' value="<?php echo $_POST['keyword'] ?>">
    	<input type='hidden' name='distance' id='distance' value="<?php echo $_POST['distance'] ?>">
    	<br>
        <input name='submit' type='submit' value="No, that place sucks ass!">
    </form>
<?php
}
?>


    
</div>

<?php
require_once("footer.php");
?>