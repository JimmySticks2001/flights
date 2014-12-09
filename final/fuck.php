<?php
	session_start();

	$table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database

    if (mysqli_connect_errno()) //if there was an error connecting...
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
    }

	
    $origin = $_SESSION['originIATA'];
    $destination = $_SESSION['destIATA'];

	$coordsQuery = "SELECT Airports.Latitude, Airports.Longitude
	FROM Airports
	WHERE Airports.IATA_FAA = '" . $origin . "' OR Airports.IATA_FAA = '" . $destination . "'";

    if (mysqli_connect_errno()) //if there was an error connecting...
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
    }
    else if($coordsResult = mysqli_query($table, $coordsQuery)) //run the user entered query on the database
    {
    	$coords = array();	//initialize an empty array

    	$i = 0;
    	while($row = mysqli_fetch_array($coordsResult))   //return each row of the result as an associative array 
	    {
			$coords[$i] = $row[0];
			$coords[$i+1] = $row[1];
			$i += 2;
		}

		$js_array = json_encode($coords);	//change the php variable into one that javascript will see
		echo $js_array;	//send it on its way
	}
?>