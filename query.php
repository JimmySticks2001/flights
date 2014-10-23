<!DOCTYPE html>

<html>
	<head>
    	<title>Stats</title>
		<meta name="author" content="Tim Lightfoot" />
		<meta name="description" content="My place on the internets" />
		<meta charset="utf-8">
		<link rel="stylesheet" href="../mainStyle.css" type="text/css" />
		<link rel="icon" type="image/png" href="../favi.png">
		<script type="text/javascript">
  			var _gaq = _gaq || [];
  			_gaq.push(['_setAccount', 'UA-37326147-1']);
  			_gaq.push(['_trackPageview']);
	
  			(function() 
  			{
    			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  			})();
		</script>
	</head>
	<body>
		<div id="page">
			<div id="content">
				<h1>flights</h1>

				<table border="1">
					<tbody>
						<tr>
							<th>Field</th>
							<th>Type</th>
							<th>Example</th>
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

				</br>
			
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					Query: <textarea name="query" rows="5" cols="80" required></textarea><br>
					<input type ="submit">
				</form>
				
				<?php
					if(isset($_POST["query"]))
					{
						$query = $_POST["query"];
						echo $query . "<br>";

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
							echo "<table border='1'> <tr>";

							foreach($fieldInfo as $column)
							{
								echo "<th>" . $column->name . "</th>";
							}

							echo "</tr>";

							while($row = mysqli_fetch_array($result))
							{
								echo "<tr>";

								for($i = 0; $i < mysqli_field_count($table); $i++)
								{
									echo "<td>" . $row[$i] . "</td>";
								}

								echo "</tr>";
							}
							echo "</table>";
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
	</body>
</html>