<?php 
require_once("header.php");
?>
<div class='container-fluid'>

<?php
$coords = explode(",", $_POST['currentCoords']);

$keyword = $_POST['keyword'];
$meters = $_POST['distance'] * 1610;

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
    echo "<p>Sorry, no results found!</p>";
} else {
    echo "<p>Number of results: ".count($data['results'])."</p><p>How about this place?</p>";
    echo $data['results'][rand(0, count($data['results']) -1)][name]."<br>";
}


?>

    <form action='#' method='post' enctype='multipart/form-data'>
    	<input type='hidden' name='currentCoords' id='currentCoords' value="<?php echo $_POST['currentCoords'] ?>">
    	<input type='hidden' name='keyword' id='keyword' value="<?php echo $_POST['keyword'] ?>">
    	<input type='hidden' name='distance' id='distance' value="<?php echo $_POST['distance'] ?>">
    	<br>
        <input name='submit' type='submit' value="No, that place sucks ass!">
    </form>
</div>

<?php
require_once("footer.php");
?>