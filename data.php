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

		$json = [];

		while (mysqli_fetch_assoc())
		{
			extract($row);

			$json[] = [(int)$id];
		}

		echo json_encode($json);
	}
?>