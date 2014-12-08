<?php
$city = $_GET['city'];
$airportElement = $_GET['airportElement'];

$table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database
if (!$table) {
  die('Could not connect: ' . mysqli_error($table));
}

$sql = "SELECT Name, IATA_FAA FROM Airports WHERE City = '".$city."' AND IATA_FAA != ''";
$result = mysqli_query($table,$sql);

echo "<div class='input-group form-padding'>";
    	if($airportElement == "destAirportDropdown"){
        	echo "<select class='form-control' name='destIATA'>";}
        else{
        	echo "<select class='form-control' name='originIATA'>";}
        while($row = mysqli_fetch_array($result))
        {
    		echo "<option value='" . $row['IATA_FAA'] . "'>" . $row['Name'] . "</option>";
        }
    echo "</select>";
echo "</div>";

mysqli_close($table);
?>