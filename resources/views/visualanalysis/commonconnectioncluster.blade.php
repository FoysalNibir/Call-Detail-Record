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
<div class="row">
  <div class="col-xs-12">

    <div class="box box-danger color-palette-box">
     <a id="dl" href="#" download="Graph.png" >Download</a>
      <div class="box-body map-printer">
       <strong>Serial: Outgoing, incoming, outgoing, incoming</strong>
       <div id="click"></div>
        <div id="mynetwork" style="height: 700px"></div>
      </div>
      <!-- /.box-body -->
    </div>
    
    
  </div>
</div>



@stop


@section('chart')

<script type="text/javascript">

// end $(function(){});


var nodes = new vis.DataSet(<?php echo json_encode($data); ?>);
var edges = new vis.DataSet(<?php echo json_encode($edge); ?>);


var container = document.getElementById('mynetwork');
var data = {
  nodes: nodes,
  edges: edges
};
var options = {
  manipulation: {
    enabled: true
  },
  clickToUse: true,
  interaction:{
    dragNodes:true,
    dragView: true,
    hideEdgesOnDrag: false,
    hideNodesOnDrag: false,
    hover: false,
    hoverConnectedEdges: true,
    keyboard: {
      enabled: false,
      speed: {x: 10, y: 10, zoom: 0.02},
      bindToWindow: true
    },
    multiselect: false,
    navigationButtons: true,
    selectable: true,
    selectConnectedEdges: true,
    tooltipDelay: 300,
    zoomView: true,

  },
  edges: {
    "smooth": {
      "enabled":true,
      "type": "cubicBezier",
      "forceDirection": "horizontal",
      "roundness": 1
    }
  },
  physics: {
    enabled:true,
    forceAtlas2Based: {
      "avoidOverlap": 1,
      "springLength": 300,
      "nodeDistance": 280
    },
    solver:"forceAtlas2Based"
  },
  layout:{randomSeed:0}
}
var network = new vis.Network(container, data, options);

network.on("stabilizationIterationsDone", function () {
  network.setOptions( 
    { physics: false } );
});

network.on("selectNode", function (params) {
        document.getElementById("click").innerHTML = '<button type="image">Change Image</button><input type="file" id="my_file" style="display: none;" />';
      $("button[type='image']").click(function() {
        $("input[id='my_file']").click();
        var currentElementId = network.getSelectedNodes();
            console.log(currentElementId);
        $(document).ready(function(){
          $('input[type="file"]').change(function(e){
            var ext = my_file.value.split('.')[1];
                    if (ext == 'jpg' || ext == 'JPG' || ext == 'JPEG' || ext == 'jpeg' || ext == 'png' || ext == 'PNG') {
                        var file = e.target.files[0];
                        var reader = new FileReader();
                        reader.onloadend = function() {
                            //console.log('RESULT', reader.result)
                            nodes.update({id: currentElementId, shape: 'image', image: reader.result});
                            document.getElementById("click").innerHTML = '';
                          }
                        reader.readAsDataURL(file);
                    }
                    else{
                        alert("Upload Only Image file ('.jpg' or '.png')");
                    }
            });
        });
      });
    });
    network.on("deselectNode", function (params) {
      document.getElementById("click").innerHTML = '';
    });

    var container = document.getElementById('mynetwork');
   network.on("afterDrawing", function (ctx) {
   var dataURL = ctx.canvas.toDataURL();
   function dlCanvas() {        

         this.href = dataURL;
         
       };
       document.getElementById("dl").addEventListener('click', dlCanvas, false);
 });
</script>

@stop
