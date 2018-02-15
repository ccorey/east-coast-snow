

//Get color used to symbolize the marker based on snow amount
function getColor(amount){
	var color;

	if(amount >= 18)
		color = '#FF0000'; //red
	else if(amount >= 12)
		color = '#ff9900'; //orange
	else if(amount >= 6)
		color = '#FFFF00'; //yellow
	else if(amount >= 2)
		color = '#66ff00'; //green
	else
		color = '#888888'; //grey 

	return color;
}

//Get color used to symbolize the marker based on snow amount
function getColorClass(amount){
	var color;

	if(amount >= 24)
		color = 'dark-red'; //'#900000'; //red
	else if(amount >= 18)
		color = 'red'; //'#FF0000'; //red
	else if(amount >= 12)
		color = 'orange'; //'#ff9900'; //orange
	else if(amount >= 6)
		color = 'yellow'; //'#FFFF00'; //yellow
	else if(amount >= 2)
		color = 'green'; //'#66ff00'; //green
	else if (amount >= .5)
		color = 'grey'; //'#888888'; //grey 
	else if (amount > 0)
		color = 'light-grey'; //'#888888'; //grey
	else
		color = 'transparent';
		//'#cccccc'; //grey 

	return color;
}

//Write table of station data to results div
function writeResults(){
	bounds = map.getBounds();

    var html = "<table class = 'table table-striped'><tr><td><b>Location</b></td><td><b>Inches of snow</b></td></tr>";

	mapLayers.forEach(function(item){
		layer = item.layer;
			layer.eachLayer(function(marker){ 
				if (bounds.contains(marker.getLatLng())) {
					html += "<tr><td>" + marker.feature.properties.Name + "</td><td>" + String(marker.feature.properties.Amount) + "</td></tr>";
					//northeastWeatherStationLayer.addTo(map);
				}

			});
		}
	);

	html += "</table>";
			
	document.getElementById('results').innerHTML = html;

	$("#results").fadeIn("900");
}

//When user zooms, see if they are zoomed in enough to render the results
function checkZoom(){
    if (map.getZoom() > 7) {      
    	//$("#results").fadeOut("200");
    	writeResults();
    } 
    else{
        document.getElementById('results').innerHTML = "<p style='text-align: center; margin-top:25px;'>Zoom in to view results</p>";
    }
}
