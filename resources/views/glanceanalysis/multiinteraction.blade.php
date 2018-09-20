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

    <div class="box box-danger color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title" style="color:#dd4b39"><i class="fa fa-slack"></i> Cluster Analysis</h3>
      </div>
      <div class="box-body">
        <p>
          <label for="style"> Style:
            <select id="style">
              <option value="bar">bar</option>
              <option value="bar-color">bar-color</option>
              <option value="bar-size">bar-size</option>

              <option value="dot">dot</option>
              <option value="dot-line">dot-line</option>
              <option value="dot-color">dot-color</option>
              <option value="dot-size">dot-size</option>

              <option value="grid">grid</option>
              <option value="line">line</option>
              <option value="surface">surface</option>
            </select>
          </label>
        </p>

        <div id="mygraph" style="width: 100%"></div>

        <div id="info"></div>
      </div>
      <!-- /.box-body -->
    </div>
    
    
  </div>
</div>



@stop


@section('chart')

<script type="text/javascript">

// end $(function(){});
   
   window.onload = function () {
        drawVisualization();
    }
   var data = null;
    var graph = null;

    function custom(x, y) {
      return (-Math.sin(x/Math.PI) * Math.cos(y/Math.PI) * 10 + 10) * 1000;
    }

    // Called when the Visualization API is loaded.
    function drawVisualization() {
      var style = document.getElementById('style').value;
      var withValue = ['bar-color', 'bar-size', 'dot-size', 'dot-color'].indexOf(style) != -1;

      // Create and populate a data table.
      data = new vis.DataSet();

      var aparties=<?php echo json_encode($aparties); ?>;
      var bparties=<?php echo json_encode($bparties); ?>;

      var allitems= <?php echo json_encode($alldata); ?>;

      // create some nice looking data with sin/cos
     
      for (var i= 0; i < allitems.length; i+=1) {
          if (withValue) {
            data.add({x:allitems[i]['x'], y:allitems[i]['y'], z: allitems[i]['z'], style:value});
          }
          else {
            data.add({x:allitems[i]['x'], y:allitems[i]['y'], z: allitems[i]['z']});
          }
       
      }

      console.log(data);

      // specify options
      var options = {
        width:  '1000px',
        height: '600px',
        style: style,
        showPerspective: true,
        showGrid: true,
        showShadow: false,

        // Option tooltip can be true, false, or a function returning a string with HTML contents
        //tooltip: true,
        tooltip: function (point) {
          // parameter point contains properties x, y, z
          return 'value: <b>' + point.z + '</b>';
        },
        
        xValueLabel: function(value) {
            return aparties[value];
        },
        
        yValueLabel: function(value) {
            return bparties[value];
        },

        zValueLabel: function(value) {
            return value;
        },

        keepAspectRatio: true,
        verticalRatio: 0.5
      };

      var camera = graph ? graph.getCameraPosition() : null;

      // create our graph
      var container = document.getElementById('mygraph');
      graph = new vis.Graph3d(container, data, options);

      if (camera) graph.setCameraPosition(camera); // restore camera position

      document.getElementById('style').onchange = drawVisualization;
    }

</script>

@stop
