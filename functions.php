<?php

// Include the source code
require 'cabi.php';

// Create instance of class
try {
	$api = new Cabi();
} catch (Exception $e) {
	echo 'Unable to create instance: ' .$e->getMessage();
	exit;
}

// Check for action getClosestStations, and return json-encoded array of closest ten stations
if ($_GET['action'] && ($_GET['action'] == 'getClosestStations')) {
	$lat = (float) $_GET['lat'];
	$long = (float) $_GET['long'];
	echo json_encode($api->get_closest_stations($lat, $long, 10));
}

?>