<?php
	$connection = mysql_connect('127.0.0.1','root','star2001!') or die(mysql_error());
    $database = mysql_select_db('flights') or die(mysql_error());

	if($_POST)
	{
	    $q = mysql_real_escape_string($_POST['search']);
	    $strSQL_Result = mysql_query("SELECT DISTINCT Country FROM Airports WHERE Country LIKE '%".$q."%' ORDER BY Country");

	    while($row=mysql_fetch_array($strSQL_Result))
	    {
	        $Country   = $row['Country'];
			//echo $Country;

			?>
			<div class="show" align="left">
            	<span class="name"><?php echo $Country; ?></span>
            </div>
            <?php
	    }
	}
?>