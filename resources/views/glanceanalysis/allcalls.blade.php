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
<div class="row map-printer">
  <div class="col-xs-12">

    <div class="box box-danger color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title" style="color:#dd4b39"></i>All Calls Timeline</h3>
      </div>
      <div class="box-body">
        <div id="visualization"></div>
      </div>
      <!-- /.box-body -->
    </div>
    
    
  </div>
</div>



@stop


@section('chart')

<script type="text/javascript">

// end $(function(){});

var now = moment().minutes(0).seconds(0).milliseconds(0);
  var groupCount = <?php echo $groupcount; ?>;
  var itemCount = <?php echo $itemcount; ?>;

  // create a data set with groups
  var names = new Array();
    <?php foreach($bparties as $key => $val){ ?>
        names.push('<?php echo $val; ?>');
    <?php } ?>

  var groups = new vis.DataSet();
  for (var g = 0; g < groupCount; g++) {
    groups.add({id: g, content: names[g]});
  }

  // create a dataset with items
  var items = new vis.DataSet();
  var allitems= <?php echo json_encode($alldata); ?>;
  for (var i = 0; i < itemCount; i++) {
    var start = allitems[i]['start'];
    var group = allitems[i]['group'];
    var color="color:"+"white;"+"background-color:"+ allitems[i]['color']+";"
    items.add({
      id: i,
      group: group,
      content: '' + allitems[i]['time'] +
          ' <span style="color:#ffffff;">(' + allitems[i]['content'] + ')</span>',
      start: start,
      type: 'box',
      style: color
    });
  }


  // create visualization
  var container = document.getElementById('visualization');
  var options = {
    groupOrder: 'content',  // groupOrder can be a property name or a sorting function
    orientation: {axis: 'top', item: 'top'},
    stack: true,
    verticalScroll: true,
    horizontalScroll:true,
    zoomKey: 'ctrlKey',
    maxHeight: 700,
    selectable:true,
    editable: true,
  };

  var timeline = new vis.Timeline(container);
  timeline.setOptions(options);
  timeline.setGroups(groups);
  timeline.setItems(items);


</script>

@stop
