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
	      		<div class="small-1 columns">
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
				</div>
				<div class="medium-6 columns">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<?php 
							if(isset($_POST["query"]))
							{
								$query = $_POST["query"];
							}
							else
							{
								$query = "";
							}
						?>
						<textarea name="query" rows="19" required><?php echo $query; ?></textarea>

						<input type ="submit" class="button small radius">						
					</form>
				</div>
			</fieldset>
		</div>
		<div class="row">
			<div class="small-7 columns">
				<?php
					if(isset($_POST["query"]))
					{
						ini_set('display_errors',0);
						error_reporting(E_ALL);

						$table = mysqli_connect("127.0.0.1","root","star2001!","flights");

						if (mysqli_connect_errno()) // Check connection
						{
							echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						else if($result = mysqli_query($table, $query))
						{
							$fieldInfo = mysqli_fetch_fields($result);
							echo "<div class='row'> <div class='small-12 small-centered columns'><table> <thead> <tr>";

							foreach($fieldInfo as $column)
							{
								echo "<th>" . $column->name . "</th>";
							}

							echo "</tr> </thead>";

							while($row = mysqli_fetch_array($result))
							{
								echo "<tr>";

								for($i = 0; $i < mysqli_field_count($table); $i++)
								{
									echo "<td>" . $row[$i] . "</td>";
								}

								echo "</tr>";
							}
							echo "</table> </div> </div>";
						}
						else
						{
							//echo "boop";
							//print_r(mysqli_error_list($table));
							printf(mysqli_error($table));
						}
					}
					//mysqli_free_result($result);
					//mysqli_close($table);
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