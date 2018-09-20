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
        <div id="map-canvas"></div>
        <!-- <div id="map" style="border: 2px solid #3872ac;"></div> -->
      </div>
      <!-- /.box-body -->
    </div>


  </div>
</div>


@stop


@section('extrastyle')

<script type="text/javascript">

  var directionsDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
  var need;
  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var center = new google.maps.LatLng(23.8103, 90.4125);
    var mapOptions = {
      zoom: 12,
      center: center
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    directionsDisplay.setMap(map);
    return calcRoute();
  }
  function fract(){

    var start_time = '<?php echo $mintime?>';
    console.log(start_time);
    var stop_time = '<?php echo $maxtime?>';
    console.log(stop_time);
    var observation_time = '<?php echo $time?>:00';
    console.log(observation_time);
    var a = start_time.split(':');
    var start_seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);

    var b = stop_time.split(':');
    var stop_seconds = (+b[0]) * 60 * 60 + (+b[1]) * 60 + (+b[2]);

    var c = observation_time.split(':');
    var observation_seconds = (+c[0]) * 60 * 60 + (+c[1]) * 60 + (+c[2]);
    var dif = (stop_seconds - start_seconds);
    var find_dif = (observation_seconds - start_seconds);
    var div = (dif / find_dif);
    return div;
  }

  need = fract();
  function calcRoute() {

    var start = new google.maps.LatLng('<?php echo $minlat?>','<?php echo $minlong?>');
    var end = new google.maps.LatLng('<?php echo $maxlat?>','<?php echo $maxlong?>');
    var request = {
      origin: start,
      destination: end,
      travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
        var numberofWaypoints = response.routes[0].overview_path.length;

        var midPoint=response.routes[0].overview_path[parseInt( numberofWaypoints / need)];
        var marker = new google.maps.Marker({
          map: map,
          position:new google.maps.LatLng(midPoint.lat(),midPoint.lng()),
          title :'<?php echo $time?>'
        });


      }
    });
  }

  google.maps.event.addDomListener(window, 'load', initialize);

</script> 

@stop