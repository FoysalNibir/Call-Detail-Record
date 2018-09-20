@extends('layout')
@section('content')

<!-- Main content -->


<!-- SELECT2 EXAMPLE -->
<div class="box box-default" style="background: #222d32">
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row"> 

      <form action="#" method="GET" id="filterform">

        <!-- /.col -->
        <div class="col-md-6">
          <div class="form-group">
            <select name='aparty[]' class="form-control select2" multiple="multiple" data-placeholder="Aparty"
            style="width: 100%;">
            @foreach($allaparties as $aparty)
            <option @if(isset($inputs['aparty']) && in_array($aparty, $inputs['aparty'])) selected @endif>{{$aparty}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <!-- /.col -->

      <div class="col-md-6">
        <div class="form-group">
          <select name='bparty[]' class="form-control select2" multiple="multiple" data-placeholder="Bparty"
          style="width: 100%;" value="">
          @foreach($allbparties as $bparty)
          <option @if(isset($inputs['bparty']) && in_array($bparty, $inputs['bparty'])) selected @endif>{{$bparty}}</option>
          @endforeach
        </select>
      </div>
    </div>

  </div>

  <div class="row"> 
   <div class="col-md-6">
    <div class="form-group">
      <select name='provider[]' class="form-control select2" multiple="multiple" data-placeholder="Provider"
      style="width: 100%;" value="">
      @foreach($allproviders as $provider)
      <option @if(isset($inputs['provider']) && in_array($provider, $inputs['provider'])) selected @endif>{{$provider}}</option>
      @endforeach
    </select>
  </div>
</div>


<div class="col-md-6">
  <div class="form-group">
    <select name='imei[]' class="form-control select2" multiple="multiple" data-placeholder="IMEI"
    style="width: 100%;" value="">
    @foreach($allimeis as $imei)
    <option @if(isset($inputs['imei']) && in_array($imei, $inputs['imei'])) selected @endif>{{$imei}}</option>
    @endforeach
  </select>
</div>
</div>

</div>

<div class="row"> 

<div class="col-md-3">
  <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-clock-o"></i>
      </div>
      <input name="timefrom" type="time" class="form-control pull-right" value="{{$inputs['timefrom']}}">
    </div>
    <!-- /.input group -->
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-clock-o"></i>
      </div>
      <input name="timeto" type="time" class="form-control pull-right" value="{{$inputs['timeto']}}">
    </div>
    <!-- /.input group -->
  </div>
</div>


<div class="col-md-3">
  <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input name="datefrom" type="date" class="form-control pull-right" value="{{$inputs['datefrom']}}">
    </div>
    <!-- /.input group -->
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input name="dateto" type="date" class="form-control pull-right" value="{{$inputs['dateto']}}">
    </div>
    <!-- /.input group -->
  </div>
</div>

</div>

<!-- /.row -->
</div>
</div>
<!-- /.box -->
<!-- /.row -->



<div class="row">
  <div class="col-xs-12">

    <div class="box box-success color-palette-box">
   
      <div class="box-body">
      <div id="map" style="border: 2px solid #3872ac;width: 100%; height: 700px" ></div>
     


       <script src="https://maps.googleapis.com/maps/api/js"></script>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
       <!-- <div id="map" style="border: 2px solid #3872ac;"></div> -->

       <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBhA_j7QSGT1CIt2No4Bx04HuBD7S312R4"></script>
       <script type="text/javascript">

        var MapPoints = '<?php echo json_encode($locations) ?>';

        var MY_MAPTYPE_ID = 'custom_style';
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;
        var alpha = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];

        function initialize() {
          directionsDisplay = new google.maps.DirectionsRenderer();
          directionsDisplay.setOptions({ suppressMarkers: true });

          if (jQuery('#map').length > 0) {

            var locations = jQuery.parseJSON(MapPoints);

            map = new google.maps.Map(document.getElementById('map'), {
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              scrollwheel: true,
              styles: [
            {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#263c3f'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#6b9a76'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#38414e'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#212a37'}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#9ca5b3'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#746855'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#1f2835'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#f3d19c'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#2f3948'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#17263c'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#515c6d'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#17263c'}]
            }
          ]
            });
            directionsDisplay.setMap(map);
            var infowindow = new google.maps.InfoWindow();          
            var flightPlanCoordinates = [];
            var bounds = new google.maps.LatLngBounds();

            for (i = 0; i < locations.length; i++) {
               var lat = locations[i].address.lat;
               var lng = locations[i].address.lng;
               lat = lat*1 + 1*Math.random() * ( 0.00009 );
               lng = lng*1 + 1*Math.random() * ( 0.00009 );
              marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                label: alpha[i],
                labelClass: "labels",
                map: map
              });
              flightPlanCoordinates.push(marker.getPosition());
              bounds.extend(marker.position);
              google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                  infowindow.setContent(locations[i]['title']);
                  infowindow.open(map, marker);
                }
              })(marker, i));

            }


            map.fitBounds(bounds);
        /* polyline
            var flightPath = new google.maps.Polyline({
                map: map,
                path: flightPlanCoordinates,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
            */
        // directions service
        var start = flightPlanCoordinates[0];
        var end = flightPlanCoordinates[flightPlanCoordinates.length - 1];
        var waypts = [];
        for (var i = 1; i < flightPlanCoordinates.length - 1; i++) {
          waypts.push({
            location: flightPlanCoordinates[i],
            stopover: true
          });
        }
        calcRoute(start, end, waypts);
      }
    }

    function calcRoute(start, end, waypts) {
      var request = {
        origin: start,
        destination: end,
        waypoints: waypts,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING
      };
      directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
          directionsDisplay.setDirections(response);
          var route = response.routes[0];
          var summaryPanel = document.getElementById('directions_panel');
          summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }
          }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);

  </script> 


</div>
<!-- /.box-body -->
</div>
</div>


 <div class="col-xs-12">

    <div class="box box-success color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title" style="color: #00a65a"><i class="fa fa-bars"></i>Routes</h3>
      </div>
      <div class="box-body">

      <table class="table table-bordered">
          <tbody>
            <tr>
              <th style="width: 10px">#</th>
              <th>Place</th>
              <th>Time</th>
              <th>Date</th>            
              <th>Latitude</th>
              <th>Longitude</th>

            </tr>
            @foreach($routes as $keyindex => $route)
            <tr>
            <td>{{$keyindex}}</td>
            <td>{{$route['title']}}</td>
            <td><strong style="color: #ff6666; font-size:  20px">{{$route['time']}}<strong></td>
            <td>{{$route['date']}}</td>
            <td>{{$route['address']['lat']}}</td>
            <td>{{$route['address']['lng']}}</td>
            </tr>
            @endforeach
          </tbody>
      </table>


        
        </div>
        <!-- /.box-body -->
      </div>
      
      
    </div>
</div>


@stop