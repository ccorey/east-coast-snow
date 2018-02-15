<?php

	/**
	* A procedural php script that parses the snowfall data for the given region. It uses a text file from the national weather
	* service that is real time.
	* Json data is returned.
	*/

	//error_reporting(E_ALL);
	date_default_timezone_set('Europe/London');

	//Sheets from the NWS are broken into 6 hour increments
	function getFormatedTime($time){
		$returnValue = '';

		if($time < 6)
			$returnValue = '00';
		if($time < 12)
			$returnValue = '06';
		elseif($time < 18)
			$returnValue = '12';
		else
			$returnValue = '18';

		return $returnValue;
	}

	//Set some of the local vars
	$date = date('Ymd');
	$time = getFormatedTime(date('H'));
	$region = $_GET['region'];
	$featureCollection = [];

	//Get the csv
	$csv = file_get_contents('http://www.nohrsc.noaa.gov/nsa/discussions_text/' . $region . '/snowfall/' . date('Ym') . '/snowfall_' . $date . $time . '_e.txt');
	$rows = explode("\n", $csv);
	$disclaimer = array_shift($rows);
	$headers = explode("|", array_shift($rows));

	//Go through each row and parse out the data
	foreach($rows as $row){
		
		$station = [];

		//Properties
		$station['properties'] = array_combine($headers, explode("|", $row));
		if(!empty($station['properties']['Longitude'])){

			//Geometry
			$station['type'] = 'Feature';
			$station['geometry'] = [
				'type'=>'Point',
				'coordinates'=> [$station['properties']['Longitude'], $station['properties']['Latitude']]
			];

			//Add to feature collection
			$featureCollection[] = $station;
		}
	}

	//Return json data
	echo json_encode(
		[
			'type'=>'FeatureCollection',
			'features'=>$featureCollection 
		], 
		JSON_NUMERIC_CHECK
	);
?>


