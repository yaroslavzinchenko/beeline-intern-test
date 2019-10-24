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

		$queryRowCount = 'SELECT COUNT(*) FROM data;';

		$rowCount = mysqli_fetch_all(mysqli_query($connection, $queryRowCount));

		// Beeline.

		$queryBeelineAll = 'SELECT BEELINE_VALUE FROM data;';

		$BEELINE_VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $queryBeelineAll));

		// MegaFon.

		$queryMegafonAll = 'SELECT MF_VALUE FROM data;';

		$MF_VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $queryMegafonAll));

		// MTS.

		$queryMtsAll = 'SELECT MTS_VALUE FROM data;';

		$MTS_VALUE_ALL = mysqli_fetch_all(mysqli_query($connection, $queryMtsAll));



		// Beeline.
		$queryBeelineDays = 'SELECT DATE(TIME_KEY) AS date,
							SUM(BEELINE_VALUE) AS total_value,
							COUNT(*) AS count
							FROM data
							GROUP BY date;';

		$beelineDays = mysqli_fetch_all(mysqli_query($connection, $queryBeelineDays));

		// MegaFon.
		$queryMegafonDays = 'SELECT DATE(TIME_KEY) AS date,
							SUM(MF_VALUE) AS total_value,
							COUNT(*) AS count
							FROM data
							GROUP BY date;';

		$megafonDays = mysqli_fetch_all(mysqli_query($connection, $queryMegafonDays));

		// MTS.
		$queryMtsDays = 'SELECT DATE(TIME_KEY) AS date,
							SUM(MTS_VALUE) AS total_value,
							COUNT(*) AS count
							FROM data
							GROUP BY date;';

		$mtsDays = mysqli_fetch_all(mysqli_query($connection, $queryMtsDays));



		// Beeline.
		$queryBeelineMinutes = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, MINUTE(TIME_KEY) AS minute, SUM(BEELINE_VALUE) AS total_value, COUNT(*) AS count FROM data GROUP BY date, hour, minute;';

		$beelineMinutes = mysqli_fetch_all(mysqli_query($connection, $queryBeelineMinutes));

		// Megafon.
		$queryMegafonMinutes = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, MINUTE(TIME_KEY) AS minute, SUM(MF_VALUE) AS total_value, COUNT(*) AS count FROM data GROUP BY date, hour, minute;';

		$megafonMinutes = mysqli_fetch_all(mysqli_query($connection, $queryMegafonMinutes));

		// MTS.
		$queryMtsMinutes = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, MINUTE(TIME_KEY) AS minute, SUM(MTS_VALUE) AS total_value, COUNT(*) AS count FROM data GROUP BY date, hour, minute;';

		$mtsMinutes = mysqli_fetch_all(mysqli_query($connection, $queryMtsMinutes));



		// Beeline.
		$queryBeelineHours = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, SUM(BEELINE_VALUE) AS total_value, COUNT(*) AS count FROM data GROUP BY date, hour;';

		$beelineHours = mysqli_fetch_all(mysqli_query($connection, $queryBeelineHours));

		// MegaFon.
		$queryMegafonHours = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, SUM(MF_VALUE) AS total_value, COUNT(*) AS count FROM data GROUP BY date, hour;';

		$megafonHours = mysqli_fetch_all(mysqli_query($connection, $queryMegafonHours));

		// MTS.
		$queryMtsHours = 'SELECT DATE(TIME_KEY) AS date, HOUR(TIME_KEY) AS hour, SUM(MTS_VALUE) AS total_value, COUNT(*) AS count FROM data GROUP BY date, hour;';

		$mtsHours = mysqli_fetch_all(mysqli_query($connection, $queryMtsHours));
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
            	}, {
            		name: 'MF_VALUE',
            		color: 'green',
            		data: [
            			<?php
                			$sum = 0;
                			foreach ($MF_VALUE_ALL as $value)
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
            		name: 'MTS_VALUE',
            		color: 'red',
            		data: [
            			<?php
                			$sum = 0;
                			foreach ($MTS_VALUE_ALL as $value)
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
                		foreach ($beelineMinutes as $beelineMinute)
						{
							$totalValue = $beelineMinute[3];
							$requestCount = $beelineMinute[4];
							$average = $totalValue / $requestCount;

							$time = $beelineMinute[0] . ' ' . $beelineMinute[1] . ':' . $beelineMinute[2];

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
                		foreach ($megafonMinutes as $megafonMinute)
						{
							$totalValue = $megafonMinute[3];
							$requestCount = $megafonMinute[4];
							$average = $totalValue / $requestCount;

							$time = $megafonMinute[0] . ' ' . $megafonMinute[1] . ':' . $megafonMinute[2];

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
                		foreach ($mtsMinutes as $mtsMinute)
						{
							$totalValue = $mtsMinute[3];
							$requestCount = $mtsMinute[4];
							$average = $totalValue / $requestCount;

							$time = $mtsMinute[0] . ' ' . $mtsMinute[1] . ':' . $mtsMinute[2];

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
                		foreach ($beelineHours as $beelineHour)
						{
							$totalValue = $beelineHour[2];
							$requestCount = $beelineHour[3];
							$average = $totalValue / $requestCount;

							$time = $beelineHour[0] . ' ' . $beelineHour[1] . ':00';

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
                		foreach ($megafonHours as $megafonHour)
						{
							$totalValue = $megafonHour[2];
							$requestCount = $megafonHour[3];
							$average = $totalValue / $requestCount;

							$time = $megafonHour[0] . ' ' . $megafonHour[1] . ':00';

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
                		foreach ($mtsHours as $mtsHour)
						{
							$totalValue = $mtsHour[2];
							$requestCount = $mtsHour[3];
							$average = $totalValue / $requestCount;

							$time = $mtsHour[0] . ' ' . $mtsHour[1] . ':00';

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
                		foreach ($beelineDays as $beelineDay)
						{
							$totalValue = $beelineDay[1];
							$requestCount = $beelineDay[2];
							$average = $totalValue / $requestCount;

							$timeEntity = strtotime($beelineDay[0]);
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
                		foreach ($megafonDays as $megafonDay)
						{
							$totalValue = $megafonDay[1];
							$requestCount = $megafonDay[2];
							$average = $totalValue / $requestCount;

							$timeEntity = strtotime($megafonDay[0]);
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
                		foreach ($mtsDays as $mtsDay)
						{
							$totalValue = $mtsDay[1];
							$requestCount = $mtsDay[2];
							$average = $totalValue / $requestCount;

							$timeEntity = strtotime($mtsDay[0]);
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