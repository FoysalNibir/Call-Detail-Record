@extends('layout')
@section('multiplemapscript')

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

@stop
@section('content')

<div class="box box-default" style="background: #222d32">
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row"> 

      <form  method="GET" id="filterform">

        <!-- /.col -->
        <div class="col-md-6">
          <div class="form-group">
            <select name='aparty[]' class="form-control select2" multiple="multiple" data-placeholder="Aparty"
            style="width: 100%;">
            @foreach($aparties as $aparty)
            <option>{{$aparty}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <!-- /.col -->

      <div class="col-md-6">
        <div class="form-group">
          <select name='bparty[]' class="form-control select2" multiple="multiple" data-placeholder="Bparty"
          style="width: 100%;">
          @foreach($bparties as $bparty)
          <option>{{$bparty}}</option>
          @endforeach
        </select>
      </div>
    </div>

  </div>

  <div class="row"> 
   <div class="col-md-6">
    <div class="form-group">
      <select name='provider[]' class="form-control select2" multiple="multiple" data-placeholder="Provider"
      style="width: 100%;">
      @foreach($providers as $provider)
      <option>{{$provider}}</option>
      @endforeach
    </select>
  </div>
</div>


<div class="col-md-6">
  <div class="form-group">
    <select name='imei[]' class="form-control select2" multiple="multiple" data-placeholder="IMEI"
    style="width: 100%;">
    @foreach($imeis as $imei)
    <option>{{$imei}}</option>
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
        <input name="timefrom" type="time"  class="form-control pull-right">
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
        <input name="timeto" type="time" class="form-control pull-right">
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
        <input name="datefrom" type="date" class="form-control pull-right">
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
        <input name="dateto" type="date" class="form-control pull-right">
      </div>
      <!-- /.input group -->
    </div>
  </div>

</div>

<!-- /.row -->

</div>
</div>

</form>


<div class="row">




 <form  target="_blank"  method="POST" id="reportform" >


  <div class="box-body" >

    <input type="hidden" name="_token" value="{{csrf_token() }}">

    <div class="col-xs-12">








      <div class="row"> 

        <div class="col-md-3">
          <div class="form-group">
            <div class="input-group">
              <button style="width: 200px;" class="btn btn-block btn-primary" formaction="{{route('createreport', $id)}}" form="reportform">Print Report</button> <br>
            </div>
            <!-- /.input group -->
          </div>
        </div>
        
      </div>


      <div class="box color-palette-box box-primary">

        <div class="box-body">



          <table class="table table-bordered">
            <tbody>
             <tr>
              <td colspan="2">Date From :</td>

              <td colspan="2">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="datefrom" type="date" class="form-control pull-right">
                    </div>
                    <!-- /.input group -->
                  </div>
                </div>
              </td>
              <td colspan="2">Date To :</td>
              <td colspan="2">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="dateto" type="date" class="form-control pull-right">
                    </div>
                    <!-- /.input group -->
                  </div>
                </div>
              </td>

            </tr>
          </tbody>
        </table>


      </div>
      <!-- /.box-body -->
    </div>


    <!-- /.box-body -->


    <div class="box color-palette-box box-primary">

      <div class="box-body">

       <div class="box-header with-border">
        <h3 class="box-title">Target Analysis</h3>
        <label style="float: right">
          <input type="checkbox" id="selectall"/>
          Select All (Not Recommended)
        </label>

      </div>

      <table class="table table-bordered">
        <tbody>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="ccf">
                  Common call frequency
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Sorted list of most contacted Bparty</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="fbd">
                  Frequency by duration
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Sorted list of Bparty by most call duration</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="cd">
                  Call details
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Detail call between target & bparty</td>
          </tr>

          <tr>
            <td>
              <div class="checkbox" >
                <label>
                  <input type="checkbox" name="name[]" class="target" value="ops">
                  OP summary
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Sorted list of every bparty's contacted aparty</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="is">
                  IMEI summary
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">IMEI(s) used by target number and usage</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="fclc">
                  First Call Last Call
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">List of first and last call of every date for determining possible night stay place</td>
          </tr>

          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="fclcop">
                  First Call Last Call Other Party
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">List of first and last call of every B party</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="dop">
                  Dates Other Party
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">List of dates contacted with other party</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="target" value="r">
                  Routes
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Places the target have been with dates</td>
          </tr>

          <tr>
            <td>
             <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="target" value="inc">
                Incoming Call
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">List of first and last call of every B party</td>
          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="target" value="ins">
                Incoming sms
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">List of dates contacted with other party</td>
          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="target" value="outc">
                Outgoing call
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">Places the target have been with dates</td>
        </tr>

        <tr>
          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="target" value="outs">
                Outgoing sms
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">All outgoing sms of target</td>
          <td  colspan="2">
            <div class="form-group">
              <select name='targetaparty[]' class="form-control select2" multiple="multiple" data-placeholder="Aparty"
              style="width: 100%;">
              @foreach($aparties as $aparty)
              <option>{{$aparty}}</option>
              @endforeach
            </select>
          </div></td>
          <td  colspan="2">
            <div class="form-group">
              <select name='targetbparty[]' class="form-control select2" multiple="multiple" data-placeholder="Bparty"
              style="width: 100%;">
              @foreach($bparties as $bparty)
              <option>{{$bparty}}</option>
              @endforeach
            </select>
          </div></td>
        </tr>


      </tbody></table>


    </div>
    <!-- /.box-body -->
  </div>



  <div class="box color-palette-box box-info">

    <div class="box-body">

      <div class="box-header with-border">
        <h3 class="box-title">Tower Analysis</h3>
        <label style="float: right">
          <input type="checkbox" id="selectalltower"/>
          Select All
        </label>
      </div>


      <table class="table table-bordered">
        <tbody>


         <tr>
           <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="tower" value="ts">
                Tower Summary
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">Summary of call from different Places</td>
          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="tower" value="tcd">
                Tower call details
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">All calls from certains place</td>
          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="tower" value="tcn">
                Tower Common Numbers
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">Tower places target numbers have been</td>

        </tr>


        <tr>

          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="tower" value="tcop">
                Tower Common OP
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">Tower places target numbers called certain other party</td>

          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="tower" value="tcimei">
                Tower Common IMEI
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">Tower places target number's used handset</td>

          <td>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="name[]" class="tower" value="tic">
                Tower Internal calls
              </label>
            </div>
          </td>
          <td style="vertical-align:middle">Determines if target and other party was in same location during call</td>
        </tr>

        <tr>

          <td  colspan="3">
            <div class="form-group">
              <select name='toweraparty[]' class="form-control select2" multiple="multiple" data-placeholder="Aparty"
              style="width: 100%;">
              @foreach($aparties as $aparty)
              <option>{{$aparty}}</option>
              @endforeach
            </select>
          </div></td>
          <td  colspan="3">
            <div class="form-group">
              <select name='towerbparty[]' class="form-control select2" multiple="multiple" data-placeholder="Bparty"
              style="width: 100%;">
              @foreach($bparties as $bparty)
              <option>{{$bparty}}</option>
              @endforeach
            </select>
          </div></td>
        </tr>

      </tbody></table>


    </div>
    <!-- /.box-body -->
  </div>


  <div class="box color-palette-box">

    <div class="box-body">


      <div class="box-header with-border">
        <h3 class="box-title">IMEI Analysis</h3>
        <label style="float: right">
          <input type="checkbox" id="selectallimei"/>
          Select All
        </label>

      </div>


      <table class="table table-bordered">
        <tbody>


          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="imei" value="icn">
                  IMEI common numbers
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">List of imei used by numbers</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="imei" value="icd">
                  IMEI Call details
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Frequency of call from IMEI</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="imei" value="iu">
                  IMEI Usage
                </label>
              </div>
            </td>
            <td style="vertical-align:middle">Date & and total call/sms from IMEI</td>
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="name[]" class="imei" value="icc">
                  IMEI Common Callers
                </label>
              </div>
            </td>

          </tr>

          <tr>

            <td  colspan="5">
              <div class="form-group">
                <select name='imeiaparty[]' class="form-control select2" multiple="multiple" data-placeholder="Aparty"
                style="width: 100%;">
                @foreach($aparties as $aparty)
                <option>{{$aparty}}</option>
                @endforeach
              </select>
            </div></td>
            <td  colspan="5">
              <div class="form-group">
                <select name='imeibparty[]' class="form-control select2" multiple="multiple" data-placeholder="Bparty"
                style="width: 100%;">
                @foreach($bparties as $bparty)
                <option>{{$bparty}}</option>
                @endforeach
              </select>
            </div></td>
          </tr>



        </tbody></table>


      </div>
      <!-- /.box-body -->
    </div>

    <div class="box color-palette-box">

      <div class="box-body">

        <div class="box-header with-border">
          <h3 class="box-title">AI Analysis</h3>
        </div>


        <table class="table table-bordered">
          <tbody>

            <tr>
              <td>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="name[]" value="cca">
                    Conferrence call analysis
                  </label>
                </div>
              </td>
              <td style="vertical-align:middle">Possible conference calls</td>

            </tr>

          </tbody></table>


        </div>
        <!-- /.box-body -->
      </div>
    </form>

    <div class="box color-palette-box">

      <div class="box-body">

        <div class="box-header with-border">
          <h3 class="box-title">Location Analysis</h3>
        </div>


        <table class="table table-bordered">
          <tbody>

            <form role="form" target="_blank" action="{{route('printlocation',$id)}}">
              {{csrf_field()}}
  
              <tr>
                <td>

                  <label>
                    <p>Latitude</p>
                    <input type="text" name="latitude" id="latitude" style="height:  32px">

                  </label>
                  <label>
                    <p>Longitude</p>
                    <input type="text" name="longitude" id="longitude" style="height:  32px">

                  </label>

                  <label>
                   <p>Zoom</p>
                   <input type="text" name="zoom" id="zoom" style="height:  32px">
                 </label>

                 <p><b>Targets</b></p>

                 <div class="form-group">
                  <select name='locationaparty[]' class="form-control select2" multiple="multiple" data-placeholder="Aparty"
                  style="width: 100%;">
                  @foreach($aparties as $aparty)
                  <option>{{$aparty}}</option>
                  @endforeach
                </select>
              </div>
              
              
            </td>

            <td>
              <label>
                   <p>Report Format</p>
                 </label>
              <div style="border: 1px solid gray; ">
                <label  style="margin-left: 10px; margin-top: 5px;">
                  <input type="checkbox" name="single_day_single_target" id="multiple" style="margin-top: 10px; margin-bottom: 10px;">
                  Single Day Single
                </label>
            
                <label  style="margin-left: 10px; margin-top: 5px; border-left: 1px solid gray;">
                  <input type="checkbox" name="multiple_day_single_target" id="movement" style="margin-left: 5px; margin-top: 10px; margin-bottom: 10px;">
                  Multiple Day Single
                </label>

                 <label  style="margin-left: 10px; margin-top: 5px; border-left: 1px solid gray;">
                  <input type="checkbox" name="single_day_multiple_target" id="movement" style="margin-left: 5px; margin-top: 10px; margin-bottom: 10px;">
                  Single Day Multiple
                </label>
                 <label  style="margin-left: 10px; margin-top: 5px; border-left: 1px solid gray;">
                  <input type="checkbox" name="multiple_day_multiple_target" id="movement" style="margin-left: 5px; margin-top: 10px; margin-bottom: 10px;">
                  Multiple Day Multiple
                </label>
                </div>

                <label  style="margin-left: 10px;">
                <input type="checkbox" name="movement" id="movement">
                Movement
              </label>
            </td>


            <td>


            
              <p>Date From: </p>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="datefrom" type="date" class="form-control pull-right">
                </div>
                <!-- /.input group -->
              </div>


              <p>Date To: </p>
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="dateto" type="date" class="form-control pull-right">
                </div>
                <!-- /.input group -->
              </div>



            </td>

            <td>

              

              <button type="submit" class="btn btn-primary" style="float: right;">View Report</button>


            </td>

          </tr>

            <!-- /.input group -->


            <tr>
              <td>
                <div class="map-container" >
                  <div id="map" style="height: 700px; width:600px">
                  </div>
                  <script type="text/javascript">
                    var zoom = 8;

                    var map = new google.maps.Map(document.getElementById('map'), {
                      zoom: zoom,
                      center: new google.maps.LatLng(23.6850, 90.3563),
                      mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    google.maps.event.addListener(map, "click", function(event) {
                // get lat/lon of click
                var clickLat = event.latLng.lat();
                var clickLon = event.latLng.lng();

                // show in input box
                document.getElementById("latitude").value = clickLat.toFixed(10);
                document.getElementById("longitude").value = clickLon.toFixed(10);

              });

                    document.getElementById("zoom").value = zoom;
                    google.maps.event.addListener(map, 'zoom_changed', function() {
                      var z = map.getZoom();

                      document.getElementById("zoom").value = z;
                    });




                  </script>
                </div>

              </div>
            </div>


            <td><tr>

            </tr>
          </form>

        </tbody></table>


      </div>
      <!-- /.box-body -->
    </div>



  </div>




</div>




@stop
