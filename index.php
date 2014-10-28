<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Flight finder</title>
        <meta name="author" content="Tim, Sunny, Pedro, Gilnei" />
        <meta name="description" content="Flights!" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/foundation.css" />
        <script src="js/vendor/modernizr.js"></script>
    </head>
    <body>
        
        <h1>Flight Planner</h1>

        Application and user interface will go here. Run test queries <a href="query.php"> here </a>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php 
                if(isset($_POST["departure"]) && isset($_POST["arrival"]))  //if PHP received a http POST request
                {
                    $departureCode = $_POST["departure"];   //get the received departure code
                    $arrivalCode = $_POST["arrival"];   //get the received arrival code
                }
                else
                {
                    $departureCode = "";    //if nothing was recieved, query is a 0 char string
                    $arrivalCode = "";
                }
            ?>
            <!--<textarea name="query" rows="19" required><?php echo $query;//place the query into the textarea?></textarea>
            -->

            <label>Departure code
                <input type="departure" name="departure">
            </label>
            <label>Arrival code
                <input type="arrival" name="arrival">
            </label>

            </br>
            <input type ="submit" class="button small radius">                      
        </form>
        

        <?php
            ini_set('display_errors',0);
            //error_reporting(E_ALL);

            if(isset($_POST["departure"]) && isset($_POST["arrival"]))  //if there is stuff to receive from the form...
            {
                $table = mysqli_connect("127.0.0.1","root","star2001!","flights");  //connect to the database

                $query = "SELECT * FROM flights WHERE departure = '" . $departureCode . "' AND arrival = '" . $arrivalCode . "'";

                //echo $query;
                
                if (mysqli_connect_errno()) //if there was an error connecting...
                {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
                }
                else if($result = mysqli_query($table, $query)) //run the user entered query on the database
                {
                    
                    if(($rowcount = mysqli_num_rows($result)) > 1000)   //if there are more than 1000 rows returned
                    {
                        echo "<div class='panel callout radius'>
                                <h5> Query returned " . $rowcount . " rows. Displaying first 1000 results. </h5>
                              </div>";  //inform the user that the page will not show all rows. Too slow to load.
                        $rowcount = 1000;   //set row count at max of 1000
                    }
                    else
                    {
                        echo "<div class='panel callout radius'>
                                <h5> Query returned " . $rowcount . " rows. </h5>
                              </div>";  //inform the user how many rows are returned
                    }
                    
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
                            echo "<td>" . $row[$i] . "</td>";   //insert mysql table data into html table
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
            }//end if POSTed
        ?>














        
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script>
            $(document).foundation();
        </script>
    </body>
</html>