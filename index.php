
<html lang="en" xml:lang="en" xmlns= "http://www.w3.org/1999/xhtml">

	<head>
		<title>Snowfall &amp; Weather | Clark Corey Design</title>
		<meta charset="utf-8" />
		<meta http-equiv="Content-Language" content="en" />
		<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
		<meta name="google" content="notranslate">
		<link rel="stylesheet" href="stylesheet/style.css" />
		<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
		<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />
		<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-label/v0.2.1/leaflet.label.js'></script>
		<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-label/v0.2.1/leaflet.label.css' rel='stylesheet' />

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

		<script src='js/functions.js'></script>

		<style type="text/css">
		</style>
	</head>
	<body>
		
		<div id = "legend">
			<ul>
				<li><strong>Snowfall Key (24h)</strong></li>
				<li><img src = "images/red.png" alt = "" height = "25px" /> >= 18 inches</li>
				<li><img src = "images/orange.png" alt = "" height = "25px" /> 12 - 17 inches</li>
				<li><img src = "images/yellow.png" alt = "" height = "25px" /> 6 - 11 inches</li>
				<li><img src = "images/green.png" alt = "" height = "25px" /> 2 - 5 inches</li>
				<li><img src = "images/grey.png" alt = "" height = "25px" /> 0.5 - 2 inches</li>
			</ul>			
		</div>

		<div id = "credits">
			<p>Sources: NWS, Mapbox</p>
		</div>

		<div id = "map"></div>
		<div id = "results"></div>
		
		<script>

			//Initialize map, centering on the Bozeman area
			L.mapbox.accessToken = 'pk.eyJ1IjoiY2NyaWZ0IiwiYSI6IlFoTklheHMifQ.FiWG45_BS8q6y2fX-OiKRQ';
			var map = L.mapbox.map('map').setView([42.8500, -72.5822], 7);

			// Topographic tile layer
			var terrainLayer = L.mapbox.tileLayer('ccrift.ko7g3fim');
			
			//Street Map		
			var streetLayer = L.mapbox.tileLayer('ccrift.k9diho6o').addTo(map);
			
			//Radar
			var nexrad = L.tileLayer.wms("http://mesonet.agron.iastate.edu/cgi-bin/wms/nexrad/n0r.cgi", {
				layers: 'nexrad-n0r-900913',
				format: 'image/png',
				transparent: true,
				attribution: "Weather data © 2012 IEM Nexrad"
			});
			
			var labelMarkerOptions = {
			        opacity: 0,
			        fillOpacity: 0
			};

			var layerGroup = L.layerGroup();

			//Would like to make this a little more dynamic instead of hard coding each region as a layer

			var northeastWeatherStationLayer = L.mapbox.featureLayer().loadURL('./snowfall-csv.php?region=Northeast').on('ready', function(layer) {
				//Color code marker, create popup and build label for each station

		        this.eachLayer(function(marker) {
		        	var station = marker.feature.properties;
		        	var color = getColor(station.Amount);
		            
		            marker.setIcon(L.mapbox.marker.icon({
		                'marker-color': color,
		                'marker-size': 'small'

		            }));

		        	marker.bindLabel(
		        		(String(station.Amount) + '" at Elevation: ' + String(station.Elevation)),
		        		{
		        			noHide:false,
		        			direction:'left'
		        		}
	        		);

	        	})

	        	//northeastWeatherStationLayer.addTo(layerGroup);

		    }).addTo(map);

			var southernApplatiaWeatherStationLayer = L.mapbox.featureLayer().loadURL('./snowfall-csv.php?region=Southern_Appalachia').on('ready', function(layer) {
				//Color code marker, create popup and build label for each station

		        this.eachLayer(function(marker) {
		        	var station = marker.feature.properties;
		        	var color = getColor(station.Amount);

		            marker.setIcon(L.mapbox.marker.icon({
		                'marker-color': color,
		                'marker-size': 'small'

		            }));

		        	marker.bindLabel(
		        		(String(station.Amount) + '" at Elevation: ' + String(station.Elevation)),
		        		{
		        			noHide:false,
		        			direction:'left'
		        		}
	        		);

	        	})

	        	//southernApplatiaWeatherStationLayer.addTo(layerGroup);

		    });

			var easternCoastalWeatherStationLayer = L.mapbox.featureLayer().loadURL('./snowfall-csv.php?region=Eastern_Coastal').on('ready', function(layer) {
				//Color code marker, create popup and build label for each station

		        this.eachLayer(function(marker) {
		        	var station = marker.feature.properties;
		        	var color = getColor(station.Amount);

		            marker.setIcon(L.mapbox.marker.icon({
		                'marker-color': color,
		                'marker-size': 'small'

		            }));

		        	marker.bindLabel(
		        		(String(station.Amount) + '" at Elevation: ' + String(station.Elevation)),
		        		{
		        			noHide:false,
		        			direction:'left'
		        		}
	        		);

	        	})

		       // easternCoastalWeatherStationLayer.addTo(layerGroup);

		    });
			
			var midwestWeatherStationLayer = L.mapbox.featureLayer().loadURL('./snowfall-csv.php?region=Midwest').on('ready', function(layer) {
				//Color code marker, create popup and build label for each station

		        this.eachLayer(function(marker) {
		        	var station = marker.feature.properties;
		        	var color = getColor(station.Amount);

		            marker.setIcon(L.mapbox.marker.icon({
		                'marker-color': color,
		                'marker-size': 'small'

		            }));

		        	marker.bindLabel(
		        		(String(station.Amount) + '" at Elevation: ' + String(station.Elevation)),
		        		{
		        			noHide:false,
		        			direction:'left'
		        		}
	        		);

	        	})

	        	//midwestWeatherStationLayer.addTo(layerGroup);
		    });

			var southernGreatLakesWeatherStationLayer = L.mapbox.featureLayer().loadURL('./snowfall-csv.php?region=Southern_Great_Lakes').on('ready', function(layer) {
				//Color code marker, create popup and build label for each station

		        this.eachLayer(function(marker) {
		        	var station = marker.feature.properties;
		        	var color = getColor(station.Amount);

		            marker.setIcon(L.mapbox.marker.icon({
		                'marker-color': color,
		                'marker-size': 'small'

		            }));

		        	marker.bindLabel(
		        		(String(station.Amount) + '" at Elevation: ' + String(station.Elevation)),
		        		{
		        			noHide:false,
		        			direction:'left'
		        		}
	        		);

	        	})

				//southernGreatLakesWeatherStationLayer.addTo(layerGroup);

		    });

			//Create menu to toggle base maps
	  		var baseMaps = {
	  			"Streets" : streetLayer,
	  			"Topo" : terrainLayer
	  			};
			
			//Create Menu to toggle layers on and off
			var features = {
				"Current Radar": nexrad,
				"Northeast": northeastWeatherStationLayer,
				"Southern Appalatia": southernApplatiaWeatherStationLayer,
				"Eastern Coastal": easternCoastalWeatherStationLayer,
				"Midwest": midwestWeatherStationLayer,
				"Southern Great Lakes": southernGreatLakesWeatherStationLayer
			};
			
			//Add Menu Items
			layerControl = new L.control.layers(baseMaps, features, {collapsed: false}).addTo(map);

		    //When user zooms check to see if table needs to be shown
			map.on('moveend', function(){ 
				checkZoom();

		    });
			
		
		</script>

	</body>
<html>