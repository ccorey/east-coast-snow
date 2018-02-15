
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
			.no-padding{
				padding-left:0;
				padding-right:0;
			}

			.my-icon {
				border:1px solid #333333;
				border-radius: 100%;
				width: 15px;
				height: 15px;
				text-align: center;
				line-height: 15px;
				color: white;
			}

			.dark-red{
				background-color: #900000;
			} 
			.red{
				background-color: #FF0000;
			}
			.orange{
				background-color: #ff9900;
			}
			.yellow{
				background-color: #FFFF00;
			}
			.green{
				background-color: #66ff00;
			}
			.grey{
				background-color: #888888;
			}
			.light-grey{
				background-color: #cccccc;
			}
			.transparent{
				background-color: #F5F5F5;
			}

			.legend-item{
				display:inline-block; 
				float:left;
				margin-right:5px;
			}

		</style>
	</head>
	<body>
		
		<div id = "legend">
			<ul>
				<li><strong>Snowfall Key (24h)</strong></li>
				<li><div class = "my-icon dark-red legend-item"></div> >= 24 inches</li>
				<li><div class = "my-icon red legend-item"></div> 18 - 24 inches</li>
				<li><div class = "my-icon orange legend-item"></div> 12 - 17 inches</li>
				<li><div class = "my-icon yellow legend-item"></div> 6 - 11 inches</li>
				<li><div class = "my-icon green legend-item"></div> 2 - 5 inches</li>
				<li><div class = "my-icon grey legend-item"></div> .5 - 2 inches</li>
				<li><div class = "my-icon light-grey legend-item"></div> 0 - .5</li>
				<li><div class = "my-icon transparent legend-item"></div> 0</li>
			</ul>			
		</div>
		<div id = "credits">
			<p>Sources: NWS, Mapbox</p>
		</div>

		<div class = "container-fluid no-padding">
			<div class = "row-fluid">
				<div class = "col-md-8 no-padding">
					<div id = "map"></div>
				</div>
				<div class = "col-md-4 no-padding">
					<div id = "results"></div>
				</div>
			</div>
		</div>
		
		<script>

			//Initialize map, centering on the Bozeman area
			L.mapbox.accessToken = 'pk.eyJ1IjoiY2NyaWZ0IiwiYSI6IlFoTklheHMifQ.FiWG45_BS8q6y2fX-OiKRQ';
			var map = L.mapbox.map('map').setView([42.8500, -71.5822], 7);

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

			var regions = [
				//['National', 'National']
				['Eastern_Coastal', 'Eastern Coastal'],
				['Midwest', 'Midwest'],
				['Northeast', 'Northeast'],
				['Southern_Great_Lakes', 'Southern Great Lakes'],
				['Southern_Appalachia', 'Southern Appalachia']
			];
			
			var labelMarkerOptions = {
			        opacity: 0,
			        fillOpacity: 0
			};

			var layerGroup = L.layerGroup();
			window.mapLayers = [];
			window.featureCount = 0;

			regions.forEach(function(item){
				var key = item[1].toString();
				mapLayers.push( 
					{ name : key,
					layer: L.mapbox.featureLayer().loadURL('./snowfall-csv.php?region=' + item[0]).on('ready', function(layer) {
						//Color code marker, create popup and build label for each station

						this.eachLayer(function(marker) {
							var station = marker.feature.properties;
							var colorClass = getColorClass(station.Amount);
							featureCount += 1;

							marker.setIcon(L.divIcon({
								className: 'my-icon ' + colorClass, // class name to style
								//html: station.Amount, // add content inside the marker, in this case a star
								iconSize: null // size of icon, use null to set the size in CSS
							}));
							
							marker.bindLabel(
								(String(station.Amount) + '" at Elevation: ' + String(station.Elevation)),
								{
									noHide:false,
									direction:'left'
								}
							);
						})
					}).addTo(map)
					}
				)
			});

			//Create menu to toggle base maps
	  		var baseMaps = {
	  			"Streets" : streetLayer,
	  			"Topo" : terrainLayer
	  			};
			
			//Create Menu to toggle layers on and off
			var features = {
				"Current Radar": nexrad
			};

			mapLayers.forEach(function(mapLayer){
				var key = mapLayer.name.toString();
				var layer = mapLayer.layer;
				features[key] = layer;
			});
			
			//Add Menu Items
			layerControl = new L.control.layers(baseMaps, features, {collapsed: false}).addTo(map);

		    //When user zooms check to see if table needs to be shown
			map.on('moveend', function(){ 
				checkZoom();
		    });

			//map.scrollZoom.disable();
			
		
		</script>

	</body>
<html>