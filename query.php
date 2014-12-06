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
    	<!-- fix the rows and columns for the query checker -->
    	<div class="row">
    		<div class="medium-5 small-centered columns">
    			<h2>query checker</h2>
    		</div>
    	</div>
    	<div class="row">
    		<fieldset>
    			<!--<div class="small-1 columns">
	      			<table>
						<thead>
							<tr>
								<th>Field</th>
								<th>Type</th>
								<th>Example</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>FlightID</td>
								<td>int(11)</td>
								<td>55</td>
							</tr>
							<tr>
								<td>departure</td>
								<td>varchar(255)</td>
								<td>ACC</td>
							</tr>
							<tr>
								<td>arrival</td>
								<td>varchar(255)</td>
								<td>AMS</td>
							</tr>
							<tr>
								<td>day_op</td>
								<td>int(11)</td>
								<td>1234567</td>
							</tr>
							<tr>
								<td>dep_time</td>
								<td>varchar(255)</td>
								<td>21:50</td>
							</tr>
							<tr>
								<td>carrier</td>
								<td>varchar(255)</td>
								<td>KL</td>
							</tr>
							<tr>
								<td>airline</td>
								<td>varchar(255)</td>
								<td>KL</td>
							</tr>
							<tr>
								<td>flightnum</td>
								<td>int(11)</td>
								<td>590</td>
							</tr>
							<tr>
								<td>duration</td>
								<td>int(11)</td>
								<td>420</td>
							</tr>
							<tr>
								<td>aircraft</td>
								<td>varchar(255)</td>
								<td>330</td>
							</tr>
						</tbody>
					</table>
				</div> -->
				<div class="medium-12 medium-centered columns">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<?php 
							if(isset($_POST["query"]))	//if PHP received a http POST request
							{
								$query = $_POST["query"];	//get the received query
							}
							else
							{
								$query = "";	//if nothing was recieved, query is a 0 char string
							}
						?>
						<textarea name="query" rows="10" required><?php echo $query;//place the query into the textarea?></textarea>

						<input type ="submit" class="button small radius">						
					</form>
				</div>
			</fieldset>
		</div>
		<div class="row">
			<div class="small-9 small-centered columns">
				<?php
					//ini_set('display_errors',0);
					//error_reporting(E_ALL);

					if(isset($_POST["query"]))	//if there is stuff to receive from the form...
					{
						$table = mysqli_connect("127.0.0.1","root","star2001!","flights");	//connect to the database
						
						if (mysqli_connect_errno()) //if there was an error connecting...
						{
							echo "Failed to connect to MySQL: " . mysqli_connect_error(); //print the error
						}
						else if($result = mysqli_query($table, $query))	//run the user entered query on the database
						{
							if(($rowcount = mysqli_num_rows($result)) > 1000)	//if there are more than 1000 rows returned
							{
								echo "<div class='panel callout radius'>
								        <h5> Query returned " . $rowcount . " rows. Displaying first 1000 results. </h5>
								      </div>";	//inform the user that the page will not show all rows. Too slow to load.
								$rowcount = 1000;	//set row count at max of 1000
							}
							else
							{
								echo "<div class='panel callout radius'>
								        <h5> Query returned " . $rowcount . " rows. </h5>
								      </div>";	//inform the user how many rows are returned
							}
							
							echo "<table> <thead> <tr>";

							foreach(mysqli_fetch_fields($result) as $column)	//for every field that is returned
							{
								echo "<th>" . $column->name . "</th>";	//make a table header
							}

							echo "</tr> </thead> <tbody>";

							$colCount = mysqli_field_count($table);	//get the number of columns in the returned table

							for($j = 0; $j < $rowcount; $j++)	//make an html table row for each returned mysql table row
							{
								$row = mysqli_fetch_array($result);
								echo "<tr>";

								for($i = 0; $i < $colCount; $i++)	//make an html table data element for each mysql column 
								{
									echo "<td>" . $row[$i] . "</td>";	//insert mysql table data into html table
								}

								echo "</tr>";
							}
							echo "</tbody> </table>";
						}
						else 	//if there was an error running the query...
						{
							echo "</div> </div>";

							echo "<div class='row'> <div class='small-11 small-centered columns'>";

							echo "<div data-alert class='alert-box alert radius'>"
							  . mysqli_error($table) .
							  "<a href='#' class='close'>&times;</a>
							</div> </div> </div>";	//inform the user and show the generated error
						}

						mysqli_free_result($result);
						mysqli_close($table);	//close the connection to the database
					}//end if POSTed
				?>
			</div>
      	</div>
        
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script>
            $(document).foundation();
        </script>
    </body>
</html>