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
    <div class="box color-palette-box">
      <div class="box-body" style="overflow-x: scroll;">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th style="width: 10px">#</th>
              <th>Aparty</th>
              <th>Bparty</th>
              <th>Provider</th>
              <th>Usage Type</th>
              <th>Duration</th>
              <th>Imei</th>
              <th>Address</th>
              <th>Date</th>
              <th>Time</th>
              <th>MCC/MNC/CID</th>
              <th>Imsi</th>
              <th>Cid</th>
              <th>Lac</th>
            </tr>
            @foreach($alldata as $key=> $cdr)
            <tr>
              <td>{{$key+1}}</td>
              <td>{{$cdr->aparty}}</td>
              <td>{{$cdr->bparty}}</td>
              <td>{{$cdr->provider}}</td>
              @if(strtolower($cdr->usage_type)=='moc')
              <td><span class="badge bg-aqua">incoming</span></td>
              @elseif(strtolower($cdr->usage_type)=='mtc')
              <td><span class="badge bg-green">outgoing</span></td>
              @elseif(strtolower($cdr->usage_type)=='smsmt')
              <td><span class="badge bg-yellow">incoming sms</span></td>
              @elseif(strtolower($cdr->usage_type)=='smsmo')
              <td><span class="badge bg-red">outgoing sms</span></td>
              @endif
              <td>{{$cdr->call_duration}}</td>
              <td>{{$cdr->imei}}</td>
              <td>{{$cdr->address}}</td>
              <td>{{$cdr->date}}</td>
              <td>{{$cdr->time}}</td>
              <td>{{$cdr->mcc}}/{{$cdr->mnc}}/{{$cdr->cid}}</td>
              <td>{{$cdr->imsi}}</td>
              <td>{{$cdr->cid}}</td>
              <td>{{$cdr->lac}}</td>
            </tr>
            @endforeach
          </tbody>
          {{$alldata->appends(Input::except('page'))->links()}}
        </table>
           
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>


@stop
