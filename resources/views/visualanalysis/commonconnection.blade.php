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
<div class="row " >
  <div class="col-xs-12">

    <div class="box box-danger color-palette-box">

      <div class="box-body">
        <p>
          <input type="button" id="btn-UD" value="Up-Down">
          <input type="button" id="btn-DU" value="Down-Up">
          <input type="button" id="btn-LR" value="Left-Right">
          <input type="button" id="btn-RL" value="Right-Left">
          <input type="hidden" id='direction' value="UD">
        </p>

        <div class="map-printer">
          <strong>Common Numbers (Hierarchical View)</strong><br \>
          <strong>Serial: Outgoing call, incoming call, outgoing sms, incoming sms</strong>
          <div id="mynetwork"  style="align-content:right">
            
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    
    
  </div>
</div>



@stop


@section('chart')

<script type="text/javascript">

// end $(function(){});


var nodes = null;
var edges = null;
var network = null;
var directionInput = document.getElementById("direction");

var nodes = <?php echo json_encode($data); ?>;
var edges = <?php echo json_encode($edge); ?>;

function destroy() {
  if (network !== null) {
    network.destroy();
    network = null;
  }
}

function draw() {
  destroy();
  var container = document.getElementById('mynetwork');
  var data = {
    nodes: nodes,
    edges: edges
  };

  var options = {
    edges: {
      smooth: {
        type: 'none',
        forceDirection: (directionInput.value == "UD" || directionInput.value == "DU") ? 'vertical' : 'horizontal',
        roundness: 0
      }
    },
    multiselect: true,
    layout: {
      hierarchical: {
        direction: directionInput.value,
        sortMethod: "directed"

      }
    },
    physics: {
      hierarchicalRepulsion: {
        avoidOverlap: 1,
        springLength: 220,
        nodeDistance: 220,
      },
      solver: 'hierarchicalRepulsion'
    }
  };
  network = new vis.Network(container, data, options);
  network.on('select', function (params) {
    document.getElementById('selection').innerHTML = 'Selection: ' + params.nodes;
  });
  network.on("stabilizationIterationsDone", function () {
    network.setOptions( 
      { physics: false } );
  });
}
var directionInput = document.getElementById("direction");
var btnUD = document.getElementById("btn-UD");
btnUD.onclick = function () {
  directionInput.value = "UD";
  draw();
};
var btnDU = document.getElementById("btn-DU");
btnDU.onclick = function () {
  directionInput.value = "DU";
  draw();
};
var btnLR = document.getElementById("btn-LR");
btnLR.onclick = function () {
  directionInput.value = "LR";
  draw();
};
var btnRL = document.getElementById("btn-RL");
btnRL.onclick = function () {
  directionInput.value = "RL";
  draw();
};

</script>

@stop
