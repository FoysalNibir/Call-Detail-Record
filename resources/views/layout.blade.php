<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Celltron Call Record Analyzer</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
   <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">

   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

   <link href="{{asset('visual/dist/vis-timeline-graph2d.min.css')}}" rel="stylesheet" type="text/css" />

   <script type="text/javascript" src="{{asset('visual/exampleUtil.js')}}"></script>
   <script type="text/javascript" src="{{asset('visual/dist/vis.js')}}"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js&callback=printPage"></script>


   <script src="https://code.highcharts.com/highcharts.js"></script>
   <script src="https://code.highcharts.com/modules/exporting.js"></script>
   <script src="https://code.highcharts.com/highcharts-3d.js"></script>


   <link href="{{asset('visual/dist/vis-network.min.css')}}" rel="stylesheet" type="text/css" />
  

   <script src="http://maps.google.com/maps/api/js?key=AIzaSyBhA_j7QSGT1CIt2No4Bx04HuBD7S312R4" type="text/javascript"></script>

   @yield('multiplemapscript')

   <style type="text/css">
   #map-canvas {
    height: 700px;
    margin: 0px;
    padding: 0px
  }
  #mynetwork {
    width: 100%;
    height:1500px;
  }
  table.legend_table {
    font-size: 11px;
    border-width:1px;
    border-color:#d3d3d3;
    border-style:solid;
  }
  table.legend_table,td {
    border-width:1px;
    border-color:#d3d3d3;
    border-style:solid;
    padding: 2px;
  }
  div.table_content {
    width:80px;
    text-align:center;
  }
  div.table_description {
    width:100px;
  }

  #operation {
    font-size:28px;
  }
  #node-popUp {
    display:none;
    position:absolute;
    top:350px;
    left:170px;
    z-index:299;
    width:250px;
    height:120px;
    background-color: #f9f9f9;
    border-style:solid;
    border-width:3px;
    border-color: #5394ed;
    padding:10px;
    text-align: center;
  }
  #edge-popUp {
    display:none;
    position:absolute;
    top:350px;
    left:170px;
    z-index:299;
    width:250px;
    height:90px;
    background-color: #f9f9f9;
    border-style:solid;
    border-width:3px;
    border-color: #5394ed;
    padding:10px;
    text-align: center;
  }
  #loadingBar {
    position:absolute;
    top:0px;
    left:0px;
    width: 100%;
    height: 902px;
    background-color:rgba(200,200,200,0.8);
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -ms-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
    opacity:1;
  }
  #text {
    position:absolute;
    top:8px;
    left:530px;
    width:30px;
    height:50px;
    margin:auto auto auto auto;
    font-size:22px;
    color: #000000;
  }


  div.outerBorder {
    position:relative;
    top:400px;
    width:600px;
    height:44px;
    margin:auto auto auto auto;
    border:8px solid rgba(0,0,0,0.1);
    background: rgb(252,252,252); /* Old browsers */
    background: -moz-linear-gradient(top,  rgba(252,252,252,1) 0%, rgba(237,237,237,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(252,252,252,1)), color-stop(100%,rgba(237,237,237,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* IE10+ */
    background: linear-gradient(to bottom,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfcfc', endColorstr='#ededed',GradientType=0 ); /* IE6-9 */
    border-radius:72px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
  }

  #border {
    position:absolute;
    top:10px;
    left:10px;
    width:500px;
    height:23px;
    margin:auto auto auto auto;
    box-shadow: 0px 0px 4px rgba(0,0,0,0.2);
    border-radius:10px;
  }

  #bar {
    position:absolute;
    top:0px;
    left:0px;
    width:20px;
    height:20px;
    margin:auto auto auto auto;
    border-radius:11px;
    border:2px solid rgba(30,30,30,0.05);
    background: rgb(0, 173, 246); /* Old browsers */
    box-shadow: 2px 0px 4px rgba(0,0,0,0.4);
  }
  #config {
    float:left;
    width: 400px;
    height: 600px;
  }
  .gm-style-iw {
    background-color: #FFF !important;
  }    

  .gm-style div div div div div div div div {
    background-color: #fff !important;

  }

</style>

<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<script>if (window.module) module = window.module;</script>
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background: #1F2739">
  <div class="wrapper">

    <header class="main-header">

      <!-- Logo -->
      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>C</b>T</span>
        <!-- logo for regular state and mobile devices -->
        <span ><b style="color: #4DC3FA;text-transform: uppercase;">CELLTRON </b> <span style="color: #FB667A">CDR</span></span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only"></span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            
            <!-- Notifications: style can be found in dropdown.less -->

            <!-- Tasks: style can be found in dropdown.less -->

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu" style="width: 50%">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                <span class="hidden-xs">Admin</span>
              </a>
              <ul class="dropdown-menu">
                <!-- Menu Footer-->
                <a href="{{route('logout')}}" class="btn bg-maroon margin">Sign out</a>

              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
          </ul>
        </div>

      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->

        <ul class="sidebar-menu" data-widget="tree" style="padding: 20px">
         <a type="button" style="color:#ffffff" class="btn btn-block btn-danger" href="{{route('postcdr',$id)}}" >Import cdr</a>
         <a type="button" style="color:#ffffff" class="btn btn-block btn-success">Import cell dump</a>
         <a type="button" style="color:#ffffff" class="btn btn-block btn-info" href="{{route('posttower',$id)}}" >Import Tower Info</a>
       </ul>

       <!-- search form -->


       <!-- /.search form -->
       <!-- sidebar menu: : style can be found in sidebar.less -->
       <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Analysis</li>



        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}"><a id="" href="{{route('dashboard', $id)}}"><i class="fa  fa-tachometer text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;cursor: pointer;">WorkSpace Dashboard</button></span></a></li>

        <li class="treeview {{ Request::is('targetanalysis*') ? 'active menu-open' : '' }}" >
          <a href="#">
            <i class="fa fa-group text-aqua"></i>
            <span>Target Analysis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('targetanalysis*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('targetanalysis/commoncallsfrequency/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-user-secret text-green"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commoncallsfrequency', $id)}}" form="filterform">Common Calls Frequency</button></span></a></li>


            <li class="{{ Request::is('targetanalysis/commoncallsfrequencyduration/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-stack-overflow text-blue"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commoncallsfrequencyduration', $id)}}" form="filterform">Frequency by Duration</button></span></a></li>


            <li class="{{ Request::is('targetanalysis/commoncalls/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-random text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commoncalls', $id)}}" form="filterform">Call Details</button></span></a></li>                                                    


            <li class="{{ Request::is('targetanalysis/behaviour/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-pie-chart"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('behaviour', $id)}}" form="filterform">Call Behavior</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/weekdaybehaviour/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-bar-chart text-yellow"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('weekdaybehaviour', $id)}}" form="filterform">Weekday Behavior</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/opsummary/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-hand-o-left"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('opsummary', $id)}}" form="filterform">OP Summary</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/imeisummary/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-phone text-aqua"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('imeisummary', $id)}}" form="filterform">Imei Summary</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/fclc/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-hourglass-end text-orange"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('fclc', $id)}}" form="filterform">FCLC</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/fclcop/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-user-plus text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('fclcop', $id)}}" form="filterform">FCLC-OP</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/datesop/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-calendar text-green"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('datesop', $id)}}" form="filterform">Dates-OP</button></span></a></li>


            <li class="{{ Request::is('targetanalysis/address/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-map-o"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('address', $id)}}" form="filterform">Route</button></span></a></li>


            <li class="{{ Request::is('targetanalysis/incoming/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-angle-double-down text-purple"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('incoming', $id)}}" form="filterform">Incoming Call</button></span></a></li>


            <li class="{{ Request::is('targetanalysis/outgoing/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-angle-double-up text-orange"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('outgoing', $id)}}" form="filterform">Outgoing Call</button></span></a></li>


            <li class="{{ Request::is('targetanalysis/incomingsms/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-arrow-circle-down"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('incomingsms', $id)}}" form="filterform">Incoming Sms</button></span></a></li>

            <li class="{{ Request::is('targetanalysis/outgoingsms/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-arrow-circle-up text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('outgoingsms', $id)}}" form="filterform">Outgoing Sms</button></span></a></li>

          </ul>
        </li>


        <li class="treeview {{ Request::is('toweranalysis*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-tree text-green"></i>
            <span>Tower Analysis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('toweranalysis*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('toweranalysis/towersummary/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-signal"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('towersummary', $id)}}" form="filterform">Tower Summary</button></span></a></li>

            <li class="{{ Request::is('toweranalysis/towerdetails/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-phone-square text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('towerdetails', $id)}}" form="filterform">Call Details</button></span></a></li>

            <li class="{{ Request::is('toweranalysis/commonnumbers/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-random text-yellow"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commonnumbers', $id)}}" form="filterform">Common Numbers</button></span></a></li>


            <li class="{{ Request::is('toweranalysis/commonimeis/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-sitemap text-purple"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commonimeis', $id)}}" form="filterform">Common IMEI</button></span></a></li>

            <li class="{{ Request::is('toweranalysis/commonop/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-venus-double text-orange"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commonop', $id)}}" form="filterform">Common OP</button></span></a></li>

            <li class="{{ Request::is('toweranalysis/internalcalls/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-refresh text-aqua"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('internalcalls', $id)}}" form="filterform">Internal Calls</button></span></a></li>



          </ul>
        </li>


        <li class="treeview {{ Request::is('imeianalysis/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-tty text-purple"></i>
            <span>IMEI Analysis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('imeianalysis/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('imeianalysis/imeinumbers/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-signal"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('imeinumbers', $id)}}" form="filterform">Common Numbers</button></span></a></li>

            <li class="{{ Request::is('imeianalysis/imeidetails/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-phone-square text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('imeidetails', $id)}}" form="filterform">Call Details</button></span></a></li>

            <li class="{{ Request::is('imeianalysis/imeiusage/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-random text-yellow"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('imeiusage', $id)}}" form="filterform">Imei Usage</button></span></a></li>


            <li class="{{ Request::is('imeianalysis/commoncallers/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-sitemap text-purple"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commoncallers', $id)}}" form="filterform">Common Callers</button></span></a></li>

          </ul>
        </li>


        <li class="treeview {{ Request::is('visualanalysis/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa  fa-object-ungroup"></i>
            <span>Visual Analysis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('visualanalysis/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('visualanalysis/completeanalysis/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-tags text-green"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('completeanalysis', $id)}}" form="filterform">Complete Analysis</button></span></a></li>

            <li class="{{ Request::is('visualanalysis/commonconnectionscluster/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-database"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commonconnectionscluster', $id)}}" form="filterform">Common Connections Cluster</button></span></a></li>

          </ul>
        </li>



        <li class="treeview {{ Request::is('glanceanalysis/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa  fa-anchor text-aqua"></i>
            <span>Calls at a glance</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('glanceanalysis/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('glanceanalysis/targettimeline/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-bar-chart text-purple"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('targettimeline', $id)}}" form="filterform">Call Timeline Frequency</button></span></a></li>

            <li class="{{ Request::is('glanceanalysis/targettimelinesms/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-line-chart text-yellow"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('targettimelinesms', $id)}}" form="filterform">SMS Timeline Frequency</button></span></a></li>

            <li class="{{ Request::is('glanceanalysis/allcalls/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-indent"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('allcalls', $id)}}" form="filterform">Target All Calls</button></span></a></li>

          </ul>
        </li>


        <li class="treeview {{ Request::is('aianalysis/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa  fa-android text-red"></i>
            <span>AI Analysis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('aianalysis/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('aianalysis/conferencecall/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-sitemap text-aqua"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('conferencecall', $id)}}" form="filterform">Conference Call Analysis</button></span></a></li>

          </ul>
        </li>

        <li class="treeview {{ Request::is('locationanalysis/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa  fa-map-marker text-green"></i>
            <span>Location Analysis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('locationanalysis/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('locationanalysis/targetlocation/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-map text-purple"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('targetlocation', $id)}}" form="filterform">Target Location</button></span></a></li>

            <li class="{{ Request::is('locationanalysis/singletarget/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-crosshairs text-purple"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('singletarget', $id)}}" form="filterform">Single Day Target</button></span></a></li>

            <li class="{{ Request::is('locationanalysis/multipletargetlocation/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-users text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('multipletargetlocation', $id)}}" form="filterform">Multiple Target</button></span></a></li>

            <li class="{{ Request::is('locationanalysis/singledaymultiple/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-dot-circle-o text-red"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('singledaymultiple', $id)}}" form="filterform">Single Day Multiple</button></span></a></li>



            <li class="{{ Request::is('locationanalysis/commonlocation/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-tag text-aqua"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('commonlocation', $id)}}" form="filterform">Common Location</button></span></a></li>

            <li class="{{ Request::is('locationanalysis/targetroute/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa fa-road"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('targetroute', $id)}}" form="filterform">Target Routes</button></span></a></li>

            <li class="{{ Request::is('locationanalysis/advtargetmovements/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa  fa-thumb-tack text-yellow"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('advtargetmovements', $id)}}" form="filterform">Adv Probable Location</button></span></a></li>



          </ul>
        </li>





        <li class="treeview {{ Request::is('othernumber/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa  fa-phone text-purple"></i>
            <span>Other Number Predictor</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('othernumber/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('othernumber/calculation/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa   fa-adjust"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('calculation', $id)}}" form="filterform">Other Number</button></span></a></li>

          </ul>

        </li>




        <li class="treeview {{ Request::is('deletion/*') ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa  fa-exclamation-triangle text-red"></i>
            <span>Deletion Panel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>


          <ul class="treeview-menu" style={{ Request::is('deletion/*') ? 'display: none;' : '' }}>


            <li class="{{ Request::is('deletion/deletebparty/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa   fa-adjust"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('deletebparty', $id)}}" form="filterform">Delete Bparty</button></span></a></li>

            <li class="{{ Request::is('deletion/deleteimei/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa   fa-adjust"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('deleteimei', $id)}}" form="filterform">Delete IMEI</button></span></a></li>

            <li class="{{ Request::is('deletion/deletecell/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa   fa-adjust"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('deletecell', $id)}}" form="filterform">Delete Cell ID</button></span></a></li>

            <li class="{{ Request::is('deletion/addservice/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa   fa-adjust"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('addservice', $id)}}" form="filterform">Add As Service</button></span></a></li>

            <li class="{{ Request::is('deletion/autoaddservice/*') ? 'active' : '' }}"><a id="" href="#"><i class="fa   fa-adjust"></i> <span><button style=" background:none!important;color:inherit;border:none;padding:0!important;font: inherit;
            cursor: pointer;" formaction="{{route('autoaddservice', $id)}}" form="filterform">Auto Service Add</button></span></a></li>
          </ul>

        </li>


        <li><a href="{{route('workspaces')}}"><i class="fa fa-book text-yellow"></i> <span>WorkSpace List</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content" id="printablecontent">
          @if(session('status'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i>Status</h4>
            <p>{{session('status')}}</p>
          </div>
          @endif
          @yield('content')
          <!-- /.content -->
        </section>
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; <a href="">Celltron</a>.</strong> All rights
        reserved.
      </footer>

      <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
   immediately after the control sidebar -->
 </div>
 <!-- ./wrapper -->
 <script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>

 @yield('extrastyle')

 <script src="{{asset('bower_components/chart.js/Chart.js')}}"></script>

 <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>

 <!-- Bootstrap 3.3.7 -->
 <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
 <!-- Select2 -->
 <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
 <!-- InputMask -->
 <script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
 <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
 <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
 <!-- date-range-picker -->
 <script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
 <script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
 <!-- bootstrap datepicker -->
 <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
 <!-- bootstrap color picker -->
 <script src="{{asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
 <!-- bootstrap time picker -->
 <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
 <!-- SlimScroll -->
 <script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
 <!-- iCheck 1.0.1 -->
 <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
 <!-- FastClick -->
 <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
 <!-- AdminLTE App -->
 <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
 <!-- AdminLTE for demo purposes -->
 <script src="{{asset('dist/js/demo.js')}}"></script>

 <!-- Page script -->
 <script>
  //Import CDR Overlay
  $(function(){
    $('[munimspinner]').each(function(i,el){
      var wrapper=$(el).addClass('box');
      var form=wrapper.find('form');
      var fileInput=wrapper.find('input[type=file]');
      var overlay=$('<div>').addClass('overlay hide').appendTo(wrapper);
      $('<i>').addClass('fa fa-refresh fa-spin').appendTo(overlay);
      form.submit(function(){
        if(fileInput.length&&fileInput[0].files.length==0){
          alert('No file selected!!!');
          return false;
        }
        overlay.removeClass('hide');
      });
    });
  });

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    $('.clockpicker').clockpicker()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
    {
      ranges   : {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
    },
    function (start, end) {
      $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })


  $(function () {
        // add multiple select / deselect functionality
        $("#selectall").click(function () {
          $('.target').attr('checked', this.checked);
        });

        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".target").click(function () {

          if ($(".target").length == $(".target:checked").length) {
            $("#selectall").attr("checked", "checked");
          } else {
            $("#selectall").removeAttr("checked");
          }

        });
      });

  $(function () {
        // add multiple select / deselect functionality
        $("#selectalltower").click(function () {
          $('.tower').attr('checked', this.checked);
        });

        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".tower").click(function () {

          if ($(".tower").length == $(".tower:checked").length) {
            $("#selectalltower").attr("checked", "checked");
          } else {
            $("#selectalltower").removeAttr("checked");
          }

        });
      });

  $(function () {
        // add multiple select / deselect functionality
        $("#selectallimei").click(function () {
          $('.imei').attr('checked', this.checked);
        });

        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".imei").click(function () {

          if ($(".imei").length == $(".imei:checked").length) {
            $("#selectallimei").attr("checked", "checked");
          } else {
            $("#selectallimei").removeAttr("checked");
          }

        });
      });

  $('.map-print').on('click',

    // printAnyMaps :: _ -> HTML
    function printAnyMaps() {
      var $body = $('body');
      var $mapContainer = $('.map-printer');
      var $mapContainerParent = $mapContainer.parent();
      var $printContainer = $('<div style="position:relative;">');

      $printContainer
      .height($mapContainer.height())
      .append($mapContainer)
      .prependTo($body);

      var $content = $body
      .children()
      .not($printContainer)
      .not('script')
      .detach();
      var $patchedStyle = $('<style media="print">')
      .text(
        'img { max-width: none !important; }' +
        'a[href]:after { content: ""; }'
        )
      .appendTo('head');

      window.print();

      $body.prepend($content);
      $mapContainerParent.append($mapContainer);

      $printContainer.remove();
      $patchedStyle.remove();
    });

  </script>


  @yield('chart')

  <script>if (window.module) module = window.module;</script>
</body>
</html>
