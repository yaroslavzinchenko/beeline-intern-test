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

		$query = "INSERT INTO data(status, USER_EMAIL, BEELINE_VALUE, MF_VALUE, MTS_VALUE, TIME_KEY, TEST) values('".$status."', '".$USER_EMAIL."', '".$BEELINE_VALUE."', '".$MF_VALUE."', '".$MTS_VALUE."', '".$TIME_KEY."', '".$TEST."');";

		mysqli_query($connection, $query);



		// 3) Visualizing data.

		$queryTimeKey = 'SELECT TIME_KEY FROM data;';

		$timeKeyAll = mysqli_fetch_all(mysqli_query($connection, $queryTimeKey));

		$timeStampArray = [];

		foreach ($timeKeyAll as $timeEntity)
		{
			$timeEntity = strtotime($timeEntity[0]);
			$timeEntity += 10800;
			$timeEntity *= 1000;
			array_push($timeStampArray, $timeEntity);
		}

		$queryRowCount = 'SELECT COUNT(*) FROM data;';

		$rowCount = mysqli_fetch_all(mysqli_query($connection, $queryRowCount));



        // Retrieving all data for all operators in one query.

        $queryAll = 'SELECT BEELINE_VALUE, MF_VALUE, MTS_VALUE FROM data;';

        $VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $queryAll));



        // Retrieving daily data for all operators in one query.

        $queryDays = 'SELECT DATE(TIME_KEY) AS date,
                            SUM(BEELINE_VALUE) AS beeline_value,
                            SUM(MF_VALUE) AS mf_value,
                            SUM(MTS_VALUE) AS mts_value,
                            COUNT(*) AS count
                            FROM data
                            GROUP BY date;';

        $allDays = mysqli_fetch_all(mysqli_query($connection, $queryDays));




        // Retrieving minute data for all operators in one query.

        $queryMinutes = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, MINUTE(TIME_KEY) AS minute, SUM(BEELINE_VALUE) AS beeline_value, SUM(MF_VALUE) AS mf_value, SUM(MTS_VALUE) AS mts_value, COUNT(*) AS count FROM data GROUP BY date, hour, minute;';

        $allMinutes = mysqli_fetch_all(mysqli_query($connection, $queryMinutes));



        // Retrieving hourly data for all operators in one query.

        $queryHours = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour,
        SUM(BEELINE_VALUE) AS beeline_value,
        SUM(MF_VALUE) AS mf_value,
        SUM(MTS_VALUE) AS mts_value,
        COUNT(*) AS count FROM data GROUP BY date, hour;';

        $allHours = mysqli_fetch_all(mysqli_query($connection, $queryHours));
        echo $allHours[0][0];
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
	<select id="graphSelector">
		<option value="containerAll">Все данные</option>
		<option value="containerMinutes">По минутам</option>
		<option value="containerHours">По часам</option>
		<option value="containerDays">По дням</option>
	</select>

	<div id="containerAll" class="graphs" style="width:100%; height:400px;"></div>
	<div id="containerMinutes" class="graphs" style="display: none; width:100%; height:400px;"></div>
	<div id="containerHours" class="graphs" style="display: none; width:100%; height:400px;"></div>
	<div id="containerDays" class="graphs" style="display: none; width:100%; height:400px;"></div>
	
	<script>
		document.addEventListener('DOMContentLoaded', function () {
        	var myChart = Highcharts.chart('containerAll', {
            	chart: {
                	type: 'line'
            	},

            	title: {
                	text: 'Средние значения (все данные)'
            	},
            	
            	xAxis: {
                	type: 'datetime',
                	labels: {
                		format: '{value:%Y-%b-%e %H:%M:%S}'
                	}
            	},

            	yAxis: {
                	title: {
                    	text: 'Значение'
                	}
            	},

            	series: [{
                	name: 'BEELINE_VALUE',
                	color: 'yellow',
                	data: [
                		<?php
                			$sum = 0;
                			foreach ($VALUE_ALL as $value)
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
            	}, {
            		name: 'MF_VALUE',
            		color: 'green',
            		data: [
            			<?php
                			$sum = 0;
                			foreach ($VALUE_ALL as $value)
                			{
                				if ($sum == $rowCount[0][0] - 1)
                				{
                					echo '[' . $timeStampArray[$sum] . ',' . $value[1] . ']';
                				}
                				else
                				{
                					echo '[' . $timeStampArray[$sum] . ',' . $value[1] . '],';
                					$sum++;
                				}
                			};
                		?>
            		]
            	}, {
            		name: 'MTS_VALUE',
            		color: 'red',
            		data: [
            			<?php
                			$sum = 0;
                			foreach ($VALUE_ALL as $value)
                			{
                				if ($sum == $rowCount[0][0] - 1)
                				{
                					echo '[' . $timeStampArray[$sum] . ',' . $value[2] . ']';
                				}
                				else
                				{
                					echo '[' . $timeStampArray[$sum] . ',' . $value[2] . '],';
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
	<script>
		document.addEventListener('DOMContentLoaded', function () {
        var myChart = Highcharts.chart('containerMinutes', {
            chart: {
                type: 'line'
            },

            title: {
                text: 'Средние значения (по минутам)'
            },

            xAxis: {
                	type: 'datetime',
                	labels: {
                		format: '{value:%Y-%b-%e %H:%M}'
                	}
            	},

            yAxis: {
                title: {
                    text: 'Значение'
                }
            },

            series: [{
            	name: 'BEELINE_VALUE',
            	color: 'yellow',
            	data: [
            		<?php
                		foreach ($allMinutes as $allMinute)
						{
							$totalValue = $allMinute[3];
							$requestCount = $allMinute[6];
							$average = $totalValue / $requestCount;

							$time = $allMinute[0] . ' ' . $allMinute[1] . ':' . $allMinute[2];

							$timeEntity = strtotime($time);
							$timeEntity += 10800;
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }, {
            	name: 'MF_VALUE',
            	color: 'green',
            	data: [
            		<?php
                		foreach ($allMinutes as $allMinute)
						{
							$totalValue = $allMinute[4];
							$requestCount = $allMinute[6];
							$average = $totalValue / $requestCount;

							$time = $allMinute[0] . ' ' . $allMinute[1] . ':' . $allMinute[2];

							$timeEntity = strtotime($time);
							$timeEntity += 10800;
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }, {
            	name: 'MTS_VALUE',
            	color: 'red',
            	data: [
            		<?php
                		foreach ($allMinutes as $allMinute)
						{
							$totalValue = $allMinute[5];
							$requestCount = $allMinute[6];
							$average = $totalValue / $requestCount;

							$time = $allMinute[0] . ' ' . $allMinute[1] . ':' . $allMinute[2];

							$timeEntity = strtotime($time);
							$timeEntity += 10800;
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }]
        });
    });
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
        var myChart = Highcharts.chart('containerHours', {
            chart: {
                type: 'line'
            },

            title: {
                text: 'Средние значения (по часам)'
            },

            xAxis: {
                	type: 'datetime',
                	labels: {
                		format: '{value:%Y-%b-%e %H}'
                	}
            	},

            yAxis: {
                title: {
                    text: 'Значение'
                }
            },

            series: [{
            	name: 'BEELINE_VALUE',
            	color: 'yellow',
            	data: [
            		<?php
                		foreach ($allHours as $allHour)
						{
							$totalValue = $allHour[2];
							$requestCount = $allHour[5];
							$average = $totalValue / $requestCount;

							$time = $allHour[0] . ' ' . $allHour[1] . ':00';

							$timeEntity = strtotime($time);
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }, {
            	name: 'MF_VALUE',
            	color: 'green',
            	data: [
            		<?php
                		foreach ($allHours as $allHour)
						{
							$totalValue = $allHour[3];
							$requestCount = $allHour[5];
							$average = $totalValue / $requestCount;

							$time = $allHour[0] . ' ' . $allHour[1] . ':00';

							$timeEntity = strtotime($time);
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }, {
            	name: 'MTS_VALUE',
            	color: 'red',
            	data: [
            		<?php
                		foreach ($allHours as $allHour)
						{
							$totalValue = $allHour[4];
							$requestCount = $allHour[5];
							$average = $totalValue / $requestCount;

							$time = $allHour[0] . ' ' . $allHour[1] . ':00';

							$timeEntity = strtotime($time);
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }]
        });
    });
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
        var myChart = Highcharts.chart('containerDays', {
            chart: {
                type: 'line'
            },

            title: {
                text: 'Средние значения (по дням)'
            },

            xAxis: {
                	type: 'datetime',
                	labels: {
                		format: '{value:%Y-%b-%e}'
                	}
            	},

            yAxis: {
                title: {
                    text: 'Значение'
                }
            },

            series: [{
            	name: 'BEELINE_VALUE',
            	color: 'yellow',
            	data: [
            		<?php
                		foreach ($allDays as $allDay)
						{
							$totalValue = $allDay[1];
							$requestCount = $allDay[4];
							$average = $totalValue / $requestCount;

							$timeEntity = strtotime($allDay[0]);
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }, {
            	name: 'MF_VALUE',
            	color: 'green',
            	data: [
            		<?php
                		foreach ($allDays as $allDay)
						{
							$totalValue = $allDay[2];
							$requestCount = $allDay[4];
							$average = $totalValue / $requestCount;

							$timeEntity = strtotime($allDay[0]);
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }, {
            	name: 'MTS_VALUE',
            	color: 'red',
            	data: [
            		<?php
                		foreach ($allDays as $allDay)
						{
							$totalValue = $allDay[3];
							$requestCount = $allDay[4];
							$average = $totalValue / $requestCount;

							$timeEntity = strtotime($allDay[0]);
							$timeEntity *= 1000;

							echo '[' . $timeEntity . ',' . $average . '],';
						};
                	?>
            	]
            }]
        });
    });
	</script>
	<script>
		$(function()
		{
    		$('#graphSelector').change(function ()
    		{
        		$('.graphs').hide();
        		$('#' + $(this).val()).show();
    		});
		});
	</script>
</body>
</html>