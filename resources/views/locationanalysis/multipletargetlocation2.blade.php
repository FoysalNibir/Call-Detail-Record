<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
	body {
		margin: 0;
		padding: 10px 20px 20px;
		font-family: Arial;
		font-size: 16px;
	}

	#map-container {
		padding: 0px;
		border-width: 0px;
		border-style: solid;
		border-color: #ccc #ccc #999 #ccc;
		-webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
		-moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
		box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
		width: 100%;
	}

	#map {
		width: 100%;
		height: 80vh;
	}
</style>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyDny3BEShAxx3Ni9dsXidwaNKlLJNwxawU" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/chart-markerclusterer.js')}}"></script>
<script>
	function initialize() {

		 var data = <?php echo json_encode($newdata) ?>;
		var center = new google.maps.LatLng(23.6850, 90.3563);

		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 12,
			center: center,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		
		var opt = {
			"styles" : [
			{textColor: "black", textSize: 15, height: 60, width: 60},
			{textColor: "black", textSize: 15, height: 70, width: 70},                    
			{textColor: "black", textSize: 15, height: 80, width: 80},
			{textColor: "black", textSize: 15, height: 90, width: 90},                    
			{textColor: "black", textSize: 15, height: 100, width: 100}
			],
		};
		var infowindow = new google.maps.InfoWindow();
		var infowindowContent;
		
		var markers = [];
		for (var i = 0; i < data.features.length; i++) {
			var person_count = data.features[i].properties["person_serial"];
			var infowindowContent = data.features[i].properties["person_info"];
			var person_info = "";

			var person_latlng = data.features[i].geometry["coordinates"];
			if(Number(person_count))
			{
				person_info = "Person "+person_count;
			}
			var person_latlng = new google.maps.LatLng(Number(person_latlng[0]), Number(person_latlng[1]));
			var marker = new google.maps.Marker({
				position: person_latlng,
				title: person_info,

			});
			markers.push(marker);

			
			google.maps.event.addListener(marker, 'click', (function(marker, i, infowindowContent) {
				return function() {
					infowindow.setContent(infowindowContent);
					infowindow.open(map, marker);

				}
			})(marker, i, infowindowContent));
		}

		var markerCluster = new MarkerClusterer(map, markers, opt);
	}

	google.load("visualization", "1", {packages: ["corechart"]});
	google.setOnLoadCallback(initialize);
</script>

</head>
<body>
	<div id="map-container">
		<div id="map"></div>
	</div>
	
</body>
</html>