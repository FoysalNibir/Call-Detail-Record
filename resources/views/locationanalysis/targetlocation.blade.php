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

<button style="font-family: Arial, Helvetica, sans-serif;"  class="map-print btn btn-primary" >&nbsp;Print&nbsp;Report&nbsp;</button>
<br>
<br>
<div note="do not delete">
  <div class="row map-printer">
    <div class="col-xs-12">

      <div class="box box-success color-palette-box">
        <div class="box-body">
         <div id="map" style="height: 700px; width: 700px;">

           <script type="text/javascript">
            var locations = <?php echo json_encode($newlocations) ?>;

            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 12,
              center: new google.maps.LatLng(locations[0][1], locations[0][2]),
              mapTypeId: google.maps.MapTypeId.ROADMAP,
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
                  infowindow.setContent(locations[i][3]);
                  infowindow.open(map, marker);
                }
              })(marker, i));

            }

          </script>

        </div>
        <!-- /.box-body -->
        
      </div>


    </div>

    <div class="col-xs-12">

      <div class="box box-success color-palette-box">
        <div class="box-header with-border">

        </div>
        <div class="box-body">
          <table class="table table-bordered">
            <tbody>
              <tr>


                <th>Marker</th>
                <th>Count</th>
                <th>Date</th>

              </tr>
              @foreach($newlocations as $key=>$locations)
              <tr>

                <td>{{$key}}</td>
                <td>{{count($locations[0])}}</td>
                <td>
                  @foreach($locations[0] as $date)
                  {{$date}},&nbsp;&nbsp;&nbsp;&nbsp;
                  @endforeach
                </td>

              </tr>
              @endforeach

            </tbody></table>

          </div>
          <!-- /.box-body -->
        </div>


      </div>
    </div>
  </div>
</div>



@stop