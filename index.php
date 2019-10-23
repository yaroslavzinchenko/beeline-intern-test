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
	$TIME_KEY = $arrayOfRetrievedData['data']['TIME_KEY'];
	$TEST = $arrayOfRetrievedData['data']['TEST'];


	// Debugging.
	// echo $status . '<br>';
	// echo $USER_EMAIL . '<br>';
	// echo $BEELINE_VALUE . '<br>';
	// echo $MF_VALUE . '<br>';
	// echo $MTS_VALUE . '<br>';
	// echo $TIME_KEY . '<br>';
	// echo $TEST . '<br>';


	
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

		// Beeline average.

		$query = 'SELECT AVG(BEELINE_VALUE) FROM data;';

		$BEELINE_VALUE_AVERAGE = mysqli_fetch_row(mysqli_query($connection, $query));

		// MegaFon average.

		$query = 'SELECT AVG(MF_VALUE) FROM data;';

		$MF_VALUE_AVERAGE = mysqli_fetch_row(mysqli_query($connection, $query));

		// MTS average.

		$query = 'SELECT AVG(MTS_VALUE) FROM data;';

		$MTS_VALUE_AVERAGE = mysqli_fetch_row(mysqli_query($connection, $query));


		$queryTimeKey = 'SELECT TIME_KEY FROM data';

		$timeKeyAll = mysqli_fetch_all(mysqli_query($connection, $queryTimeKey));

		$timeStampArray = [];

		foreach ($timeKeyAll as $timeEntity)
		{
			$timeEntity = strtotime($timeEntity[0]);
			$timeEntity += 21600;
			$timeEntity *= 1000;
			array_push($timeStampArray, $timeEntity);
		}

		$query = 'SELECT COUNT(*) FROM data;';

		$rowCount = mysqli_fetch_all(mysqli_query($connection, $query));
		// echo $rowCount[0][0];

		// Beeline.

		$query = 'SELECT BEELINE_VALUE FROM data;';

		$BEELINE_VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $query));

		// Megafon.

		// $query = 'SELECT MF_VALUE FROM data;';

		// $MF_VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $query));

		// MTS.

		// $query = 'SELECT MTS_VALUE FROM data;';

		// $MTS_VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $query));

		
		$sum = 0;
                			foreach ($BEELINE_VALUE_ALL as $value)
							{
								if ($sum == $rowCount[0][0] - 1)
								{
									echo '[' . $timeStampArray[$sum] . ', ' . $value[0] . ']';
								}
								else
								{
									echo '[' . $timeStampArray[$sum] . ', ' . $value[0] . '],';
									$sum++;
								}
							};


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
		document.addEventListener('DOMContentLoaded', function () {
        	var myChart = Highcharts.chart('container', {
            	chart: {
                	type: 'line'
            	},

            	title: {
                	text: 'Средние значения (все данные)'
            	},
            	
            	xAxis: {
            		tickInterval: 24 * 3600 * 1000, // One day.
            		tickWidth: 0,
            		gridLineWidth: 1
                	// type: 'datetime',
                	// labels: {
                	// 	format: '{value:%Y-%b-%e %H:%M:%S}'
                	// }
            	},
            	yAxis: {
                	title: {
                    	text: 'Значение'
                	}
            	},

            	series: [{
                	name: 'BEELINE_VALUE',
                	data: [
                		<?php
                			$sum = 0;
                			foreach ($BEELINE_VALUE_ALL as $value)
                			{
                				if ($sum == $rowCount[0][0] - 1)
                				{
                					echo '[' . $timeStampArray[$sum] . ',' . $value[0] . ']';
                				}
                				else
                				{
                					echo '[' . $timeStampArray[$sum] . ',' . $value[0] . '],';
                					$sum++;
                				}
                			};
                		?>
                	]
            	}
            	]

        	});
    	});
	</script>
</body>
</html>