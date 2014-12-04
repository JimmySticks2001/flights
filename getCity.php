<?php
$country = $_GET['country'];
$cityElement = $_GET['cityElement'];
$airportElement = $_GET['airportElement'];

$table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database
if (!$table) {
  die('Could not connect: ' . mysqli_error($table));
}

$sql = "SELECT DISTINCT City FROM Airports WHERE Country = '".$country."' ORDER BY City";
$result = mysqli_query($table,$sql);

echo "<label>City 
        <select name='cities' onchange='showAirport(this.value,\"".$airportElement."\")''>";
        while($row = mysqli_fetch_array($result))
        {
			echo "<option value='" . $row['City'] . "'>" . $row['City'] . "</option>";
        }
echo "</select>
    </label>";

mysqli_close($table);
?>