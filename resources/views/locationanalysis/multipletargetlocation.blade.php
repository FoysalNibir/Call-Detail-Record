@extends('layout')


@section('multiplemapscript')

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="{{asset('js/chart-markerclusterer.js')}}"></script>
<script>
  function initialize() {

   var data = <?php echo json_encode($newdata) ?>;

   var person_latlngini = data.features[0].geometry["coordinates"];
   var center = new google.maps.LatLng(Number(person_latlngini[0]), Number(person_latlngini[1]));

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
}

google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(initialize);
</script>

@stop
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
         <div id="map" style="height: 700px; width: 100%;">

         </div>
         <!-- /.box-body -->
       </div>


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

              <th>Caller</th>
              <th>Address</th>
              <th>Date</th>
            </tr>
            @foreach($tabledata as $data)
            <tr>
              <td rowspan="{{count($data['info'])+1}}">{{$data['aparty']}}</td>
              @foreach($data['info'] as $newdata)
              <tr>
                <td>{{$newdata['address']}}</td>
                <td>{{$newdata['datetime']}}</td>
              </tr>
              @endforeach
            </tr>
            @endforeach

          </tbody></table>

        </div>
        <!-- /.box-body -->
      </div>


    </div>

  </div>
</div>

@stop

