@extends('layout')
 
 
@section('multiplemapscript')
 
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="{{asset('js/chart-markerclusterer.js')}}"></script>
<script>
  function initialize() {
 
   @if(isset($checkboxes['sdmt']))
   var data = <?php echo json_encode($databases['sdmt']) ?>;
   @endif
 
   @if(isset($checkboxes['mdmt']))
   var data2 = <?php echo json_encode($databases['mdmt']) ?>;
   @endif
   
 
   var lat = {{$lat}};
   var lon = {{$lon}};
   var zoom = {{$zoom}};
 
   @if(isset($checkboxes['sdmt']))
   var person_latlngini = data.features[0].geometry["coordinates"];
   @endif
   
   @if(isset($checkboxes['mdmt']))
   var person_latlngini = data2.features[0].geometry["coordinates"];
   @endif
   
   var center = new google.maps.LatLng(Number(lat), Number(lon));
 
   @if(isset($checkboxes['sdmt']))
   var map = new google.maps.Map(document.getElementById('map'), {
    zoom: zoom-1+1,
    center: center,
   /* mapTypeId: google.maps.MapTypeId.ROADMAP*/
   mapTypeId: google.maps.MapTypeId.HYBRID
  });
   @endif
   @if(isset($checkboxes['mdmt']))
   var map5 = new google.maps.Map(document.getElementById('map5'), {
    zoom: zoom,
    center: center,
    /*mapTypeId: google.maps.MapTypeId.ROADMAP*/
    mapTypeId: google.maps.MapTypeId.HYBRID
  });
   @endif
 
   
 
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
 
  @if(isset($checkboxes['sdmt']))
  for (var i = 0; i < data.features.length; i++) {
    var person_count = data.features[i].properties["person_serial"];
    var infowindowContent = data.features[i].properties["person_info"];
    var person_info = "";
 
    var person_latlng = data.features[i].geometry["coordinates"];
    person_info = data.features[i].properties["person_info"];
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
  @endif
  @if(isset($checkboxes['mdmt']))
  for (var i = 0; i < data2.features.length; i++) {
    var person_count = data2.features[i].properties["person_serial"];
    var infowindowContent = data2.features[i].properties["person_info"];
    var person_info = "";
 
    var person_latlng = data2.features[i].geometry["coordinates"];
    person_info = data2.features[i].properties["person_info"];
    var person_latlng = new google.maps.LatLng(Number(person_latlng[0]), Number(person_latlng[1]));
    var marker = new google.maps.Marker({
      position: person_latlng,
      title: person_info,
 
    });
    markers.push(marker);
 
 
    google.maps.event.addListener(marker, 'click', (function(marker, i, infowindowContent) {
      return function() {
        infowindow.setContent(infowindowContent);
        infowindow.open(map5, marker);
 
      }
    })(marker, i, infowindowContent));
  }
  var markerCluster = new MarkerClusterer(map5, markers, opt);
  @endif
 
 
 
}
 
google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(initialize);
</script>
 
@stop
@section('content')
 
<!-- Main content -->
 
 
<!-- SELECT2 EXAMPLE -->
 
<!-- /.box -->
<!-- /.row -->
<button style="font-family: Arial, Helvetica, sans-serif;"  class="map-print btn btn-primary" >&nbsp;Print&nbsp;Report&nbsp;</button>
<br>
<br>
 
<div note="do not delete">
  <div class="row map-printer">
    <div class="col-xs-12">
 
      <div class="box box-success color-palette-box">
 
        @if(isset($checkboxes['sdmt']))
        &nbsp;&nbsp;&nbsp;<h3 style=" margin: 0 auto;">Single Day Multiple Target ({{$checkboxes['datefrom']}}) </h3>
        <div class="box-body" style="page-break-after: always;">
         <div id="map" style="height: 650px; width: 600px;">
 
         </div>
         <!-- /.box-body -->
       </div>
       @endif
       @if(isset($checkboxes['mdmt']))
       &nbsp;&nbsp;&nbsp;<h3 style=" margin: 0 auto;">Multiple Day Multiple Target  ({{$checkboxes['datefrom']}} to {{$checkboxes['dateto']}})</h3>
       <div class="box-body" style="page-break-after: always;">
        <div id="map5" style="height: 650px; width: 600px;">
 
        </div>
        <!-- /.box-body -->
      </div>
      @endif
 
      @if(isset($checkboxes['sdst']))
       @foreach($databases['sdst'] as  $key => $newlocation)
        &nbsp;&nbsp;&nbsp;<h3 style=" margin: 0 auto;">Single Day Single Target : {{$targets[$key]}}  ({{$checkboxes['datefrom']}})</h3>
 
      <div class="box-body" style="page-break-after: always;">
       <div id="sdst{{$key}}" style="height: 650px; width: 600px;">
 
         <script type="text/javascript">
          var locations = <?php echo json_encode($newlocation) ?>;
          var lat={{$lat}};
          //console.log("latitude :"+lat);
          var lon={{$lon}};
          var zoom={{$zoom}};
 
          var map = new google.maps.Map(document.getElementById('sdst'+{{$key}}), {
            zoom: zoom,
            center: new google.maps.LatLng(lat ,lon ),
           /* mapTypeId: google.maps.MapTypeId.ROADMAP,*/
            mapTypeId: google.maps.MapTypeId.HYBRID,
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
 
          var marker, i;
 
 
 
          for (i = 0; i < locations.length; i++) {
 
           var infowindow = new google.maps.InfoWindow({minWidth: 100 });
           
           var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + i + "|FF0000|000000|",
            new google.maps.Size(21, 34),
            new google.maps.Point(0,0),
            new google.maps.Point(10, 34));
 
           marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: pinImage
          });
           google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
 
            }
          })(marker, i));
         
           infowindow.setContent(locations[i][0]);
           infowindow.open(map, marker);
         
         }
 
       </script>
 
     </div>
     <!-- /.box-body -->
 
   </div>
   @endforeach
 
   @endif
 
   @if(isset($checkboxes['mdst']))
       @foreach($databases['mdst'] as  $key => $newlocation)
       &nbsp;&nbsp;&nbsp;<h3 style="margin: 0 auto;">Multiple Day Single Target :{{$targets[$key]}} ({{$checkboxes['datefrom']}} to {{$checkboxes['dateto']}})</h3>
      <div class="box-body" style="page-break-after: always;">
       <div id="mdst{{$key+10}}" style="height: 650px; width: 600px;">
 
         <script type="text/javascript">
          var locations = <?php echo json_encode($newlocation) ?>;
          var lat={{$lat}};
          //console.log("latitude :"+lat);
          var lon={{$lon}};
          var zoom={{$zoom}};
 
          var map = new google.maps.Map(document.getElementById('mdst'+{{$key+10}}), {
            zoom: zoom,
            center: new google.maps.LatLng(lat ,lon ),
            /*mapTypeId: google.maps.MapTypeId.ROADMAP,*/
            mapTypeId: google.maps.MapTypeId.HYBRID,
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
 
          var marker, i;
 
 
 
          for (i = 0; i < locations.length; i++) {
 
           var infowindow = new google.maps.InfoWindow({minWidth: 100 });
           
           var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + i + "|FF0000|000000|",
            new google.maps.Size(21, 34),
            new google.maps.Point(0,0),
            new google.maps.Point(10, 34));
 
           marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: pinImage
          });
           google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
 
            }
          })(marker, i));
         
           //infowindow.setContent(locations[i][0]);
           //infowindow.open(mdst, marker);
       
 
         }
 
       </script>
 
     </div>
     <!-- /.box-body -->
 
   </div>
   @endforeach
 
   @endif
 
 
 </div>
</div>
 
</div>
</div>
 
@stop