<?php

	error_reporting(0);

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


	date_default_timezone_set('Europe/London');

	$date = date('Ymd');
	$time = getFormatedTime(date('H'));
	$region = $_GET['region'];

	$csv = file_get_contents('http://www.nohrsc.noaa.gov/nsa/discussions_text/' . $region . '/snowfall/' . date('Ym') . '/snowfall_' . $date . $time . '_e.txt');
	$rows = explode("\n", $csv);
	
	$featureCollection = [];

	$disclaimer = array_shift($rows);
	$headers = explode("|", array_shift($rows));

	foreach($rows as $row){
		
		$station = [];

		//Properties
		$station['properties'] = array_combine($headers, explode("|", $row));
		if(!empty($station['properties']['Longitude']) && $station['properties']['Amount'] >= 0.5 ){

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

	echo json_encode(
		[
			'type'=>'FeatureCollection',
			'features'=>$featureCollection 
		], 
		JSON_NUMERIC_CHECK
	);
?>


