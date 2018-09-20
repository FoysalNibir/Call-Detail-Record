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
      <div class="box-body map-printer">
        <p>
          <label for="locale">Select a locale:</label>
          <select id="locale" onchange="draw();">
            <option value="en">en</option>
          </select>
        </p>

        <div id="node-popUp">
          <span id="node-operation">node</span> <br>
          <table style="margin:auto;">
            <tr>
              <td>id</td><td><input id="node-id" value="new value" /></td>
            </tr>
            <tr>
              <td>label</td><td><input id="node-label" value="new value" /></td>
            </tr>
          </table>
          <input type="button" value="save" id="node-saveButton" />
          <input type="button" value="cancel" id="node-cancelButton" />
        </div>

        <div id="edge-popUp">
          <span id="edge-operation">edge</span> <br>
          <table style="margin:auto;">
            <tr>
              <td>label</td><td><input id="edge-label" value="new label" /></td>
            </tr>
            <tr>
              <td>value</td><td><input id="edge-value" value="new value" /></td>
            </tr>
          </table>
          <input type="button" value="save" id="edge-saveButton" />
          <input type="button" value="cancel" id="edge-cancelButton" />
        </div>

        <br />
        <div id="mynetwork"></div>
        <div id="loadingBar">
          <div class="outerBorder">
            <div id="text">0%</div>
            <div id="border">
              <div id="bar"></div>
            </div>
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


window.onload = function () {
  init();
}
var nodes = <?php echo json_encode($data); ?>;
var edges = <?php echo json_encode($edge); ?>;
var network = null;
    // randomly create some nodes and edges
    var data = {
      nodes: nodes,
      edges: edges
    };
    var seed = 8;

    function setDefaultLocale() {
      var defaultLocal = navigator.language;
      var select = document.getElementById('locale');
      select.selectedIndex = 0; // set fallback value
      for (var i = 0, j = select.options.length; i < j; ++i) {
        if (select.options[i].getAttribute('value') === defaultLocal) {
          select.selectedIndex = i;
          break;
        }
      }
    }

    function destroy() {
      if (network !== null) {
        network.destroy();
        network = null;
      }
    }

    function draw() {
      destroy();
      nodes = [];
      edges = [];

      // create a network
      var container = document.getElementById('mynetwork');
      var options = {
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
          multiselect: true,
          navigationButtons: true,
          selectable: true,
          selectConnectedEdges: true,
          tooltipDelay: 300,
          zoomView: true,

        },
        layout: {randomSeed:seed}, // just to make sure the layout is the same when the locale is changed
        locale: document.getElementById('locale').value,
        manipulation: {
          addNode: function (data, callback) {
            // filling in the popup DOM elements
            document.getElementById('node-operation').innerHTML = "Add Node";
            editNode(data, callback);
          },
          editNode: function (data, callback) {
            // filling in the popup DOM elements
            document.getElementById('node-operation').innerHTML = "Edit Node";
            editNode(data, callback);
          },
          addEdge: function (data, callback) {
            if (data.from == data.to) {
              var r = confirm("Do you want to connect the node to itself?");
              if (r != true) {
                callback(null);
                return;
              }
            }
            document.getElementById('edge-operation').innerHTML = "Add Edge";
            editEdgeWithoutDrag(data, callback);
          },
          editEdge: {
            editWithoutDrag: function(data, callback) {
              document.getElementById('edge-operation').innerHTML = "Edit Edge";
              editEdgeWithoutDrag(data,callback);
            }
          }
        }
      };
      network = new vis.Network(container, data, options);
      network.on("stabilizationIterationsDone", function () {
        network.setOptions( 
          { physics: false } );
      });

      network.on("stabilizationProgress", function(params) {
                var maxWidth = 496;
                var minWidth = 20;
                var widthFactor = params.iterations/params.total;
                var width = Math.max(minWidth,maxWidth * widthFactor);

                document.getElementById('bar').style.width = width + 'px';
                document.getElementById('text').innerHTML = Math.round(widthFactor*100) + '%';
            });
            network.once("stabilizationIterationsDone", function() {
                document.getElementById('text').innerHTML = '100%';
                document.getElementById('bar').style.width = '496px';
                document.getElementById('loadingBar').style.opacity = 0;
                // really clean the dom element
                setTimeout(function () {document.getElementById('loadingBar').style.display = 'none';}, 500);
            });

    }
    function editNode(data, callback) {
      document.getElementById('node-label').value = data.label;
      document.getElementById('node-saveButton').onclick = saveNodeData.bind(this, data, callback);
      document.getElementById('node-cancelButton').onclick = clearNodePopUp.bind();
      document.getElementById('node-popUp').style.display = 'block';
    }

    function clearNodePopUp() {
      document.getElementById('node-saveButton').onclick = null;
      document.getElementById('node-cancelButton').onclick = null;
      document.getElementById('node-popUp').style.display = 'none';
    }

    function cancelNodeEdit(callback) {
      clearNodePopUp();
      callback(null);
    }

    function saveNodeData(data, callback) {
      data.label = document.getElementById('node-label').value;
      clearNodePopUp();
      callback(data);
    }

    function editEdgeWithoutDrag(data, callback) {
      // filling in the popup DOM elements
      document.getElementById('edge-label').value = data.label;
      document.getElementById('edge-label').value = data.value;
      document.getElementById('edge-saveButton').onclick = saveEdgeData.bind(this, data, callback);
      document.getElementById('edge-cancelButton').onclick = cancelEdgeEdit.bind(this,callback);
      document.getElementById('edge-popUp').style.display = 'block';
    }

    function clearEdgePopUp() {
      document.getElementById('edge-saveButton').onclick = null;
      document.getElementById('edge-cancelButton').onclick = null;
      document.getElementById('edge-popUp').style.display = 'none';
    }

    function cancelEdgeEdit(callback) {
      clearEdgePopUp();
      callback(null);
    }

    function saveEdgeData(data, callback) {
      if (typeof data.to === 'object')
        data.to = data.to.id
      if (typeof data.from === 'object')
        data.from = data.from.id
      data.label = document.getElementById('edge-label').value;
      data.value = document.getElementById('edge-value').value;
      clearEdgePopUp();
      callback(data);
    }

    function init() {
      setDefaultLocale();
      draw();
    }


  </script>

  @stop
