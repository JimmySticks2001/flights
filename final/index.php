<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOLARE</title>
	<link href="css/datepicker.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/personal.css">
	<script>
        function showCity(country, cityElement, airportElement) {
            if (country == "") {
                document.getElementById(cityElement).innerHTML = "";
                return;
            } else { 
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById(cityElement).innerHTML = xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET","getCity.php?country="+country+"&cityElement="+cityElement+"&airportElement="+airportElement,true);
                xmlhttp.send();
            }
        }

        function showAirport(city, airportElement) {
            if (city == "") {
                document.getElementById(airportElement).innerHTML = "";
                return;
            } else { 
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById(airportElement).innerHTML = xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET","getAirport.php?city="+city+"&airportElement="+airportElement,true);
                xmlhttp.send();
            }
        }
    </script>
    <style>
        .result{
            padding: 2px 0px 2px 5px; /*top right bottom left */
            margin: 0px 0px 10px 0px;
            border: 2px solid;
            border-color: #c7c7c7;
            border-radius: 5px;
            box-shadow: 0px 1px 0px #e7e7e7;
        }
        .resultButton{
            float: right;
            margin: 12px 10px 0px 0px;
        }
        .resultConButton{
            float: right;
            margin: 35px 10px 0px 0px;
        }
        #logo{
        	float: right;
            padding: 25px 20px 0px 0px; /*top right bottom left */
        }
        #conLogo{
        	display: block;
        	padding: 15px 0px 25px 0px; /*top right bottom left */
        }
        #logoDiv{
        	float: right;
        	width: 140px;
        	margin-right: 10px;
        }
    </style>
  </head>
  <body>
  	<?php
        ini_set('display_errors',0);
        $table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database

        if (mysqli_connect_errno()) //if there was an error connecting...
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
        }
 
    ?>
    <div class="container main-content">
	<?php
	    if(!(isset($_POST["originIATA"]) && isset($_POST["destIATA"]) && isset($_POST["date"])))  //if there is stuff to receive from the form...
	    {
	    	?>
				<div class="row col-md-12">
					<div class="col-md-12">
						<img class="head-image" src="img/head.png" alt="hd">
					</div>
				</div>
				<div class="row col-md-12" id="airplane">
					<form id="userInfos" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<div class="col-lg-5 form-padding"> <!-- Start first column -->
							<h5><span class="label label-info ">From:</span></h5>
							
							<?php
				                $countryQuery = "SELECT DISTINCT Country FROM airports ORDER BY Country";
				                $result = mysqli_query($table, $countryQuery);

				                echo "<div class='input-group form-padding'>";
					                echo "<select class='form-control' name='originCountry' onchange='showCity(this.value, \"originCityDropdown\",\"originAirportDropdown\")''>";
				                          while($row = mysqli_fetch_array($result))
				                          {
				                              echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
				                          }
					                echo "</select>";
				                echo "</div>";
				            ?>
							
				            <div id="originCityDropdown"></div>
			            	<div id="originAirportDropdown"></div>
			            	
			            	<div class="input-group form-padding">
			            		<input type="checkbox" name="nonStop" value="yes"> <div id="nonStop">Non-stop only</div>
			            	</div>

						</div> <!--Close First Column -->
						<div class="col-lg-5 form-padding">	<!-- Start Arrival column -->
							<h5><span class="label label-info">To:</span></h5>

							<?php
				                $result = mysqli_query($table, $countryQuery);

				                echo "<div class='input-group form-padding'>";
					                echo "<select class='form-control' name='destCountry' onchange='showCity(this.value, \"destCityDropdown\",\"destAirportDropdown\")''>";
					                      while($row = mysqli_fetch_array($result))
					                      {
					                          echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
					                      }
				                  	echo "</select>";
			                  	echo "</div>";
				            ?>

				            <div id="destCityDropdown"></div>
			            	<div id="destAirportDropdown"></div>

							<div class="input-group form-padding">
				                <input type="date" name="date">
				            </div>

				            <div class="form-padding2"> <!--Airport Form -->
								<button type="submit" class="btn btn-info form-pad">Submit</button>
							</div>

					   </div> <!--Close Arrival Column-->
					</form>
				</div>
			<?php
	    }
	    else //if the fields have been filled in and the submit button pressed...
	    {
	    	$originCountry  = $_SESSION['originCountry'];
	    	$originCity = $_SESSION['originCity'];

	    	$destCountry  = $_SESSION['destCountry'];
	    	$destCity = $_SESSION['destCity'];
	    	?>
	    	<div class="row col-md-12">
				<div class="col-md-12">
					<img class="head-image" src="img/head.png" alt="hd">
				</div>
			</div>
			<div class="row col-md-12" id="airplane">
				<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="col-lg-5 form-padding"> <!-- Start first column -->
						<h5><span class="label label-info ">From:</span></h5>
						
						<?php
			                $countryQuery = "SELECT DISTINCT Country FROM airports ORDER BY Country";
			                $result = mysqli_query($table, $countryQuery);

			                echo "<div class='input-group form-padding'>";
				                echo "<select class='form-control' name='originCountry' onchange='showCity(this.value, \"originCityDropdown\",\"originAirportDropdown\")''>";
			                          while($row = mysqli_fetch_array($result))
			                          {
			                          	  if($row['Country'] == $originCountry)
			                          	  {
			                          	  	echo "<option value='" . $row['Country'] . "' selected='selected'>" . $row['Country'] . "</option>";
			                          	  }
			                          	  else
			                          	  {
			                          	  	echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
			                          	  }
			                          }
				                echo "</select>";
			                echo "</div>";
			            ?>
						
			            <div id="originCityDropdown">
			            	<?php
				            	$sql = "SELECT DISTINCT City FROM Airports WHERE Country = '".$originCountry."' ORDER BY City";
								$result = mysqli_query($table,$sql);

								echo "<div class='input-group form-padding'>";
									echo "<select class='form-control' name='cities' onchange='showAirport(this.value,\"originAirportDropdown\")''>";
								        while($row = mysqli_fetch_array($result))
								        {
								        	if($row['City'] == $originCity)
				                          	{
				                          	  echo "<option value='" . $row['City'] . "' selected='selected'>" . $row['City'] . "</option>";
				                          	}
				                          	else
				                          	{
				                          	  echo "<option value='" . $row['City'] . "'>" . $row['City'] . "</option>";
				                          	}
								        }
									echo "</select>";
								echo "</div>";
							?>
			            </div>
		            	<div id="originAirportDropdown">
		            		<?php
		            			$sql = "SELECT Name, IATA_FAA FROM Airports WHERE City = '".$originCity."' AND IATA_FAA != ''";
								$result = mysqli_query($table,$sql);

			            		echo "<div class='input-group form-padding'>";
								    echo "<select class='form-control' name='originIATA'>";
								        while($row = mysqli_fetch_array($result))
								        {
								        	if($row['IATA_FAA'] == $_POST["originIATA"])
				                          	{
				                          	  echo "<option value='" . $row['IATA_FAA'] . "' selected='selected'>" . $row['Name'] . "</option>";
				                          	}
				                          	else
				                          	{
				                          	  echo "<option value='" . $row['IATA_FAA'] . "'>" . $row['Name'] . "</option>";
				                          	}
								        }
								    echo "</select>";
								echo "</div>";
							?>
		            	</div>

		            	<div class="input-group form-padding">
		            		<input type="checkbox" name="nonStop" value="yes" <?php if ($_POST['nonStop'] == 'yes') echo 'checked="checked"' ?> > <div id="nonStop">Non-stop only</div>
		            	</div>

					</div> <!--Close First Column -->
					<div class="col-lg-5 form-padding">	<!-- Start Arrival column -->
						<h5><span class="label label-info">To:</span></h5>

						<?php
			                $result = mysqli_query($table, $countryQuery);

			                echo "<div class='input-group form-padding'>";
				                echo "<select class='form-control' name='destCountry' onchange='showCity(this.value, \"destCityDropdown\",\"destAirportDropdown\")''>";
				                      while($row = mysqli_fetch_array($result))
				                      {
				                      	  if($row['Country'] == $destCountry)
			                          	  {
			                          	  	echo "<option value='" . $row['Country'] . "' selected='selected'>" . $row['Country'] . "</option>";
			                          	  }
			                          	  else
			                          	  {
			                          	  	echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
			                          	  }
				                      }
			                  	echo "</select>";
		                  	echo "</div>";
			            ?>

			            <div id="destCityDropdown">
			            	<?php
				            	$sql = "SELECT DISTINCT City FROM Airports WHERE Country = '".$destCountry."' ORDER BY City";
								$result = mysqli_query($table,$sql);

								echo "<div class='input-group form-padding'>";
									echo "<select class='form-control' name='cities' onchange='showAirport(this.value,\"destAirportDropdown\")''>";
								        while($row = mysqli_fetch_array($result))
								        {
								        	if($row['City'] == $destCity)
				                          	{
				                          	  echo "<option value='" . $row['City'] . "' selected='selected'>" . $row['City'] . "</option>";
				                          	}
				                          	else
				                          	{
				                          	  echo "<option value='" . $row['City'] . "'>" . $row['City'] . "</option>";
				                          	}
								        }
									echo "</select>";
								echo "</div>";
							?>
			            </div>
		            	<div id="destAirportDropdown">
		            		<?php
		            			$sql = "SELECT Name, IATA_FAA FROM Airports WHERE City = '".$destCity."' AND IATA_FAA != ''";
								$result = mysqli_query($table,$sql);

			            		echo "<div class='input-group form-padding'>";
								    echo "<select class='form-control' name='destIATA'>";
								        while($row = mysqli_fetch_array($result))
								        {
								        	if($row['IATA_FAA'] == $_POST["destIATA"])
				                          	{
				                          	  echo "<option value='" . $row['IATA_FAA'] . "' selected='selected'>" . $row['Name'] . "</option>";
				                          	}
				                          	else
				                          	{
				                          	  echo "<option value='" . $row['IATA_FAA'] . "'>" . $row['Name'] . "</option>";
				                          	}
								        }
								    echo "</select>";
								echo "</div>";
							?>
		            	</div>

						<div class="input-group form-padding">
			                <input type="date" name="date" <?php echo "value='".$_POST['date']."'" ?>>
			            </div>

			            <div class="form-padding2"> <!--Airport Form -->
							<button type="submit" class="btn btn-info form-pad">Submit</button>
						</div>

				   </div> <!--Close Arrival Column-->
				</form>
			</div>
			<?php

			

	    	$departureCode = $_POST["originIATA"];   //get the received departure code
	        $arrivalCode = $_POST["destIATA"];   //get the received arrival code
	        //$departureCode = "JFK";
	        //$arrivalCode = "CDG";
	        $date = $_POST["date"];   //get the received departure date
	        $dayofweek = date('w', strtotime($date)) + 1;   //get the day of the week. Sunday = 1, monday = 2, etc.

	        $query = "SELECT f.dep_time AS departure, a.Name AS origin, a2.Name AS destination, al.Name AS airline, f.airline AS airlineCode, f.flightnum AS flightNumber, f.duration AS duration
	        FROM Flights AS f
	        STRAIGHT_JOIN Airports AS a ON f.departure = a.IATA_FAA
	        STRAIGHT_JOIN Airports AS a2 ON f.arrival = a2.IATA_FAA
	        STRAIGHT_JOIN Airlines AS al ON f.airline = al.IATA
	        WHERE f.departure = '".$departureCode."' AND arrival = '".$arrivalCode."' AND day_op LIKE '%".$dayofweek."%'
	        ORDER BY f.dep_time";

	        if($_POST['nonStop'] != 'yes')
	        {
	        	$query .= "; SELECT * FROM 
				(
					SELECT f.dep_time AS departureTime, a.Name AS origin, a2.Name AS destination, al.Name AS airline, f.airline AS airlineCode, f.flightnum AS flightNumber, f.duration AS duration, f.arrival
					FROM Flights AS f
					STRAIGHT_JOIN Airports AS a ON f.departure = a.IATA_FAA
					STRAIGHT_JOIN Airports AS a2 ON f.arrival = a2.IATA_FAA
					STRAIGHT_JOIN Airlines AS al ON airline = al.IATA
					WHERE departure = '".$departureCode."' AND day_op LIKE '%".$dayofweek."%'
				) f1
				JOIN
				(
					SELECT f.dep_time AS conDepartureTime, a.Name AS conOrigin, a2.Name AS conDestination, al.Name AS conAirline, f.airline AS conAirlineCode, f.flightnum AS conFlightNumber, f.duration AS conDuration, f.departure AS conDeparture
					FROM Flights AS f
					STRAIGHT_JOIN Airports AS a ON f.departure = a.IATA_FAA
					STRAIGHT_JOIN Airports AS a2 ON f.arrival = a2.IATA_FAA
					STRAIGHT_JOIN Airlines AS al ON airline = al.IATA
					WHERE arrival = '".$arrivalCode."' AND day_op LIKE '%".$dayofweek."%'
				) f2
				ON f1.arrival = f2.conDeparture
				WHERE STR_TO_DATE(f2.conDepartureTime, '%H:%i') >= date_add(STR_TO_DATE(f1.departureTime, '%H:%i'), INTERVAL (f1.duration + 60) minute) AND (f1.duration + f2.conDuration) < 800 
				ORDER BY f1.departureTime";
	        }
	         
		    $_SESSION['originIATA'] = $departureCode;
		    $_SESSION['destIATA'] = $arrivalCode;

	        if (mysqli_connect_errno()) //if there was an error connecting...
	        {
	            echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
	        }
	        else if (mysqli_multi_query($table ,$query))
			{
				$queryCounter = 1;
				do
				{
				    // Store first result set
				    if($result = mysqli_store_result($table))
				    {
				    	$rowcount = mysqli_num_rows($result);

				        if($queryCounter == 1)	//if direct flights
				        {
				        	echo "<div class='row col-md-12'>";
					            if($rowcount > 500)   //if there are more than 500 rows returned
					            {
					                echo "<div class='alert alert-info'>
					                        <h5> Search returned " . $rowcount . " direct flights. Displaying first 500. </h5>
					                      </div>";  //inform the user that the page will not show all rows. Too slow to load.
					                $rowcount = 500;   //set row count at max of 500
					            }
					            elseif($rowcount == 0)
					            {
									echo "<div class='alert alert-error'>
					                        <h5> Search returned 0 direct flights. </h5>
					                      </div>";  //inform the user how many rows are returned
					            }
					            else
					            {
					                echo "<div class='alert alert-info'>
					                        <h5> Search returned " . $rowcount . " direct flights. </h5>
					                      </div>";  //inform the user how many rows are returned
					            }
					        echo "</div>"; //end row

							echo "<div class='row'>";
					        	echo "<div class='col-md-7'>";
					        		$rowCounter = 0;
						            while($row = mysqli_fetch_array($result))   //return each row of the result as an associative array 
						            {
						            	if($rowCounter >= $rowcount)
						            	{
						            		return;
						            	}
						            	else
						            	{
						            		echo "<div class='result'>";
						                    	echo "<button type='button' class='btn btn-primary resultButton'>Select</button>";
						                    	echo "<img id='logo' src = 'AIRLINE_LOGOS/" . $row['airlineCode'] ."' alt= 'Airline logo'>";
							                    echo "Departs at <strong>" . date('h:ia', strtotime($row['departure'])) . "</strong>, " . date('H\h i\m', mktime(0,$row['duration'])) . "<br/>";
							                    echo $row['origin'] . " to " . $row['destination'] . "<br/>";
							                    echo "<strong>" . $row['airline'] . "</strong> flight number " . $row['flightNumber'] . "<br/>";
							                echo "</div>";
							                $rowCounter++;
						            	}
						            }
					            echo "</div>";

						        if($rowcount > 0)
						        {
						        	echo "<div class='col-md-5'>";
						            	echo "<iframe src='map.html' id='map' width='360' height='360'></iframe>";
						            echo "</div>";
						        }
							echo "</div>"; //end row
				        }
				        else
				        {
				        	echo "<div class='row'>";
					        	echo "<div class='col-md-12'>";
						            if($rowcount > 500)   //if there are more than 500 rows returned
						            {
						                echo "<div class='alert alert-info'>";
						                    echo "<h5> Search returned " . $rowcount . " flights with connections. Displaying first 500. </h5>";
						                echo "</div>";  //inform the user that the page will not show all rows. Too slow to load.
						                $rowcount = 500;   //set row count at max of 500
						            }
						            elseif($rowcount == 0)
						            {
										echo "<div class='alert alert-error'>";
						                    echo "<h5> Search returned 0 flights with connections. </h5>";
						                echo "</div>";
						            }
						            else
						            {
						                echo "<div class='alert alert-info'>";
						                    echo "<h5> Search returned " . $rowcount . " flights with connections. </h5>";
						                echo "</div>";
						            }
					            echo "</div>"; //end row
					        echo "</div>"; //end row

							echo "<div class='row col-md-9'>";
								$rowCounter = 0;
					            while($row = mysqli_fetch_array($result))   //return each row of the result as an associative array 
					            {
					            	if($rowCounter >= $rowcount)
					            	{
					            		return;
					            	}
					            	else
					            	{
					            		echo "<div class='result'>";
					                    	echo "<button type='button' class='btn btn-primary resultConButton'>Select</button>";
					                    	echo "<div id='logoDiv'>";
						                    	echo "<img id='conLogo' src = 'AIRLINE_LOGOS/" . $row['airlineCode'] ."' alt= 'Airline logo'>";	// id='logo'
						                    	echo "<img id='conLogo' src = 'AIRLINE_LOGOS/" . $row['conAirlineCode'] ."' alt= 'Airline logo'>"; // id='conLogo' 
						                    echo "</div>";

						                    echo "Departs ".$row['origin']." at <strong>".date('h:ia', strtotime($row['departureTime']))."</strong>, ".date('H\h i\m', mktime(0,$row['duration']))."<br/>";
						                    echo "<strong>".$row['airline']."</strong> flight number ".$row['flightNumber']."<br/>";
						                    echo "<br/>";
						                    echo "Departs ".$row['conOrigin']." at <strong>".date('h:ia', strtotime($row['conDepartureTime']))."</strong>, ".date('H\h i\m', mktime(0,$row['conDuration']))."<br/>";
						                    echo "<strong>".$row['conAirline']."</strong> flight number ".$row['conFlightNumber']."<br/>";
						                echo "</div>";
						                $rowCounter++;
					            	}
					            }
							echo "</div>"; //end row
				        }

				      	mysqli_free_result($table);
				    }

				    $queryCounter++;
			    }
			  	while(mysqli_next_result($table));
	        }//end query
	        else    //if there was an error running the query...
	        {
	            echo "</div> </div>";
	            echo "<div class='row'> <div class='small-11 small-centered columns'>";
	            echo "<div class='alert alert-error' role='alert'>"
	                    . mysqli_error($table) .
	                    "<a href='#' class='close'>&times;</a>
	                </div> </div> </div>";
	        }//end error

	        mysqli_free_result($result);
	        mysqli_close($table);   //close the connection to the database
	    }//end else set
	?>
   </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
		$(function()
		{
			$('.datepicker').datepicker();
		});
	</script>
  </body>
</html>
