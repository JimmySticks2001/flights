<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Flight finder</title>
        <meta name="author" content="Tim, Sunny, Pedro, Gilnei" />
        <meta name="description" content="Flights!" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/foundation.css" />
        
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
        <script src="js/vendor/modernizr.js"></script>
        <style>
            .result{
                width: 600px;
                padding: 2px 0px 2px 5px; /*top right bottom left */
                margin: 0px 0px 10px 0px;
                border: 2px solid;
                border-color: #c7c7c7;
                border-radius: 5px;
                box-shadow: 0px 1px 0px #e7e7e7;
            }
            .result img{
                float: right;
                padding: 5px 20px 0px 0px; /*top right bottom left */
            }
            .result #button{
                float: right;
                padding: 5px 0px 0px 15px; /*top right bottom left */
                margin: 0px 20px 0px 0px;
                width: 90px;
                height: 40px;
                border: 2px solid;
                border-color: #008cba;
                border-radius: 4px;
                background-color: #008cba;
                color: #ffffff;
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


        <h1>Flight Planner</h1>

        Application and user interface will go here. Run test queries <a href="query.php"> here </a>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php 
                //if(isset($_POST["originIATA"]) && isset($_POST["destIATA"]) && isset($_POST["date"]))  //if PHP received a http POST request
                //{
                //   $departureCode = $_POST["originIATA"];   //get the received departure code
                //    $arrivalCode = $_POST["destIATA"];   //get the received arrival code
                //    $date = $_POST["date"];   //get the received departure date
                //}
                //else
                //{
                //    $departureCode = "";    //if nothing was recieved, query is a 0 char string
                //    $arrivalCode = "";
                //    $date = "";
                //}
            ?>

            <h4>Origin</h4>
            <?php
                $countryQuery = "SELECT DISTINCT Country FROM airports ORDER BY Country";
                $result = mysqli_query($table, $countryQuery);

                echo "<label>Country 
                          <select name='originCountry' onchange='showCity(this.value, \"originCityDropdown\",\"originAirportDropdown\")''>";
                              while($row = mysqli_fetch_array($result))
                              {
                                  echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
                              }
                          echo "</select>
                      </label>";
            ?>

            <div id="originCityDropdown"></div>
            <div id="originAirportDropdown"></div>

            
            </br>
            <h4>Destination</h4>
            <?php
                $result = mysqli_query($table, $countryQuery);

                echo "<label>Country 
                          <select name='destCountry' onchange='showCity(this.value, \"destCityDropdown\",\"destAirportDropdown\")''>";
                              while($row = mysqli_fetch_array($result))
                              {
                                  echo "<option value='" . $row['Country'] . "'>" . $row['Country'] . "</option>";
                              }
                          echo "</select>
                      </label>";
            ?>

            <div id="destCityDropdown"></div>
            <div id="destAirportDropdown"></div>

            <label>Departure date
                <input type="date" name="date">
            </label>
            
            </br>
            <input type ="submit" class="button small radius">                      
        </form>
        

        <?php
            //ini_set('display_errors',0);
            //error_reporting(E_ALL);

            //if(isset($_POST["originIATA"]) && isset($_POST["destIATA"]) && isset($_POST["date"]))  //if there is stuff to receive from the form...
            //{
                $departureCode = $_POST["originIATA"];   //get the received departure code
                $arrivalCode = $_POST["destIATA"];   //get the received arrival code
                $date = $_POST["date"];   //get the received departure date
                $dayofweek = date('w', strtotime($date)) + 1;   //get the day of the week. Sunday = 1, monday = 2, etc.

                //$table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database

                $query = "SELECT f.dep_time AS departure, a.Name AS origin, a2.Name AS destination, al.Name AS airline, f.airline AS airlineCode, f.flightnum AS flightNumber, f.duration AS duration
                FROM Flights AS f 
                STRAIGHT_JOIN Airports AS a ON f.departure = a.IATA_FAA
                STRAIGHT_JOIN Airports AS a2 ON f.arrival = a2.IATA_FAA
                STRAIGHT_JOIN Airlines AS al ON f.airline = al.IATA
                WHERE f.departure = 'JFK' AND f.arrival = 'CDG' AND f.day_op LIKE '%1%'
                ORDER BY f.dep_time";

                
                if (mysqli_connect_errno()) //if there was an error connecting...
                {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
                }
                else if($result = mysqli_query($table, $query)) //run the user entered query on the database
                {
                    if(($rowcount = mysqli_num_rows($result)) > 1000)   //if there are more than 1000 rows returned
                    {
                        echo "<div class='panel callout radius'>
                                <h5> Search returned " . $rowcount . " flights. Displaying first 1000. </h5>
                              </div>";  //inform the user that the page will not show all rows. Too slow to load.
                        $rowcount = 1000;   //set row count at max of 1000
                    }
                    else
                    {
                        echo "<div class='panel callout radius'>
                                <h5> Search returned " . $rowcount . " flights. </h5>
                              </div>";  //inform the user how many rows are returned
                    }

                    while($row = mysqli_fetch_array($result))   //return each row of the result as an associative array 
                    {
                        echo "<div class='result'>";
                            echo "<img src = 'AIRLINE_LOGOS/" . $row['airlineCode'] ."' alt= 'Airline logo'>";
                            echo "Departs at " . date('h:ia', strtotime($row['departure'])) . ", " . date('H\h i\m', mktime(0,$row['duration'])) . "<br/>";
                            echo "<div id='button'> Book it! </div>";
                            echo $row['origin'] . " to " . $row['destination'] . "<br/>";
                            echo $row['airline'] . " flight number " . $row['flightNumber'] . "<br/>";
                        echo "</div>";
                    }
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

            /*
                    echo "<table> <thead> <tr>";

                    foreach(mysqli_fetch_fields($result) as $column)    //for every field that is returned
                    {
                        echo "<th>" . $column->name . "</th>";  //make a table header
                    }

                    echo "</tr> </thead> <tbody>";

                    $colCount = mysqli_field_count($table); //get the number of columns in the returned table

                    for($j = 0; $j < $rowcount; $j++)   //make an html table row for each returned mysql table row
                    {
                        $row = mysqli_fetch_array($result);
                        echo "<tr>";

                        for($i = 0; $i < $colCount; $i++)   //make an html table data element for each mysql column 
                        {
                            if($i == 0)
                            {
                                echo "<td>" . date('h:i a', strtotime($row[$i])) . "</td>";   //if its the first column, its a date. Covert it to 12 hour format.
                            }
                            elseif($i == 5)
                            {
                                echo "<td>" . date('H:i', mktime(0,$row[$i])) . "</td>";   //if its column 5, its the duration of the flight in minutes, convert to hours and minutes 
                            }
                            else
                            {
                                echo "<td>" . $row[$i] . "</td>";   //insert mysql table data into html table
                            }
                        }

                        echo "</tr>";
                    }
                    echo "</tbody> </table>";
                    
                }
                else    //if there was an error running the query...
                {
                    echo "</div> </div>";

                    echo "<div class='row'> <div class='small-11 small-centered columns'>";

                    echo "<div data-alert class='alert-box alert radius'>"
                      . mysqli_error($table) .
                      "<a href='#' class='close'>&times;</a>
                    </div> </div> </div>";  //inform the user and show the generated error
                }

                mysqli_free_result($result);
                mysqli_close($table);   //close the connection to the database
            //}//end if POSTed

            */
        ?>

        
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script>
            $(document).foundation();
        </script>
    </body>
</html>