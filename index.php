<?php
	// 1) Getting data from URL.

	$retrievedData = file_get_contents("https://mixtech.dev/neiro-bit/beeline/?LOGIN=zin.yar@mail.ru&PASSWORD=vjz2Adh");

	// JSON string to array.
	$arrayOfRetrievedData = json_decode($retrievedData, true);

	$status = $arrayOfRetrievedData['status'];
	$USER_EMAIL = $arrayOfRetrievedData['data']['USER_EMAIL'];
	$BEELINE_VALUE = $arrayOfRetrievedData['data']['BEELINE_VALUE'];
	$MF_VALUE = $arrayOfRetrievedData['data']['MF_VALUE'];
	$MTS_VALUE = $arrayOfRetrievedData['data']['MTS_VALUE'];
	$TIME_KEY = $arrayOfRetrievedData['data']['MF_VALUE'];
	$TEST = $arrayOfRetrievedData['data']['TEST'];


	// Debugging.
	/*echo $status . '<br>';
	echo $USER_EMAIL . '<br>';
	echo $BEELINE_VALUE . '<br>';
	echo $MF_VALUE . '<br>';
	echo $MTS_VALUE . '<br>';
	echo $TIME_KEY . '<br>';
	echo $TEST . '<br>';*/


	
	// 2) Writing data to db.

	$host = 'localhost';
	$user = 'root';
	$password = 'password';
	$database = 'beeline-intern-test';

	$connection = mysqli_connect($host, $user, $password, $database);

	// Check connection.
	if (mysqli_connect_errno())
	{
		// Connection failed.
		echo 'Failed to connect to MySQL '. mysqli_connect_errno();
	}
	else
	{
		// No errors.

		// Create Query.
		$query = "INSERT INTO data(status, USER_EMAIL, BEELINE_VALUE, MF_VALUE, MTS_VALUE, TIME_KEY, TEST) values('".$status."', '".$USER_EMAIL."', '".$BEELINE_VALUE."', '".$MF_VALUE."', '".$MTS_VALUE."', '".$TIME_KEY."', '".$TEST."');";

		// Debugging.
		// echo $query;

		mysqli_query($connection, $query);

		// mysqli_close($connection);



		// 3) Visualizing data.

		$array = array(1, 2, 3);

		/*while ($row = mysql_fetch_array($array[1, 2, 3]))
		{
			$data[] = $row['value'];
		}*/
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Beeline Intern Test</title>
	<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
	<div id="container" style="width:100%; height:400px;"></div>
	<script>
		$(document).read(function(){
			var options = {
				charts: {
					renderTo: 'container'
				},
				series: [{
					data: [<?php echo 1 ?>],
					pointStart: 0,
         			pointInterval
				}]
			};
		});
	</script>
</body>
</html>