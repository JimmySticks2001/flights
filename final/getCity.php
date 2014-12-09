<?php
session_start();
$country = $_GET['country'];
$cityElement = $_GET['cityElement'];
$airportElement = $_GET['airportElement'];

if (strpos($airportElement, 'origin') !== FALSE)
{
	$_SESSION['originCountry'] = $country;
}
else
{
	$_SESSION['destCountry'] = $country;
}

$table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database
if (!$table) {
  die('Could not connect: ' . mysqli_error($table));
}

$sql = "SELECT DISTINCT City FROM Airports WHERE Country = '".$country."' ORDER BY City";
$result = mysqli_query($table,$sql);

echo "<div class='input-group form-padding'>";
	echo "<select class='form-control' name='cities' onchange='showAirport(this.value,\"".$airportElement."\")''>";
	        while($row = mysqli_fetch_array($result))
	        {
				echo "<option value='" . $row['City'] . "'>" . $row['City'] . "</option>";
	        }
	echo "</select>";
echo "</div>";

mysqli_close($table);
?>