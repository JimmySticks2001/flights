<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOLARE</title>

    <!-- Bootstrap -->
	<link href="css/datepicker.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="css/personal.css">
	<!-- GOOGLE MAPS -->
	
	<!--End GOOGLE MAPS -->
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
        .result img{
            float: right;
            padding: 25px 20px 0px 0px; /*top right bottom left */
        }
        .resultButton{
            float: right;
            margin: 10px 10px 0px 0px;
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
		                              echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
		                          }
			                echo "</select>";
		                echo "</div>";
		            ?>
					
		            <div id="originCityDropdown"></div>
	            	<div id="originAirportDropdown"></div>

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

		    if(isset($_POST["originIATA"]) && isset($_POST["destIATA"]) && isset($_POST["date"]))  //if there is stuff to receive from the form...
		    {
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
		        WHERE f.departure = '" . $departureCode . "' AND arrival = '" . $arrivalCode . "' AND day_op LIKE '%".$dayofweek."%'
		        ORDER BY f.dep_time";

		        session_start(); 
			    $_SESSION['originIATA'] = $departureCode;
			    $_SESSION['destIATA'] = $arrivalCode;
			    header('Location: fuck.php'); // go to other

		        //WHERE f.departure = 'JFK' AND arrival = 'CDG' AND day_op LIKE '%1%'
		        //WHERE f.departure = '" . $departureCode . "' AND arrival = '" . $arrivalCode . "' AND day_op LIKE '%".$dayofweek."%'

		        if (mysqli_connect_errno()) //if there was an error connecting...
		        {
		            echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
		        }
		        else if($result = mysqli_query($table, $query)) //run the user entered query on the database
		        {
		        	$coordsResult = mysqli_query($table, $coordsQuery);
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


	        		echo "<div class='row col-md-12'>";
			            if(($rowcount = mysqli_num_rows($result)) > 1000)   //if there are more than 1000 rows returned
			            {
			                echo "<div class='alert alert-info' role='alert'>
			                        <h5> Search returned " . $rowcount . " flights. Displaying first 1000. </h5>
			                      </div>";  //inform the user that the page will not show all rows. Too slow to load.
			                $rowcount = 1000;   //set row count at max of 1000
			            }
			            else
			            {
			                echo "<div class='alert alert-info' role='alert'>
			                        <h5> Search returned " . $rowcount . " flights. </h5>
			                      </div>";  //inform the user how many rows are returned
			            }
			        echo "</div>";

			        echo "<div class='row'>";

			        	echo "<div class='col-md-7'>";
				            while($row = mysqli_fetch_array($result))   //return each row of the result as an associative array 
				            {
					            	echo "<div class='result'>";
				                    	echo "<button type='button' class='btn btn-primary resultButton'>Select</button>";
				                    	echo "<img src = 'AIRLINE_LOGOS/" . $row['airlineCode'] ."' alt= 'Airline logo'>";
					                    echo "Departs at <strong>" . date('h:ia', strtotime($row['departure'])) . "</strong>, " . date('H\h i\m', mktime(0,$row['duration'])) . "<br/>";
					                    echo $row['origin'] . " to " . $row['destination'] . "<br/>";
					                    echo "<strong>" . $row['airline'] . "</strong> flight number " . $row['flightNumber'] . "<br/>";
					                echo "</div>";
				            }
			            echo "</div>";

			        if($rowcount > 0)
			        {
			        	echo "<div class='col-md-5'>";
			            	echo "<iframe src='map.html' id='map' width='360' height='360'></iframe>";
			            echo "</div>";
			        }
			            

				echo "</div>"; //end row
		            
		            
		        }//end query
		        else    //if there was an error running the query...
		        {
		            echo "</div> </div>";
		            echo "<div class='row'> <div class='small-11 small-centered columns'>";
		            echo "<div data-alert class='alert-box alert radius'>"
		                    . mysqli_error($table) .
		                    "<a href='#' class='close'>&times;</a>
		                </div> </div> </div>";
		        }//end error

		        mysqli_free_result($result);
		        mysqli_close($table);   //close the connection to the database
		    }
		?>
   </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
	 $(function(){
	  $('.datepicker').datepicker();
	  });
	</script>
  </body>
</html>
