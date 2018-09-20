<!DOCTYPE html>

<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://maps.google.com/maps/api/js?key=AIzaSyBhA_j7QSGT1CIt2No4Bx04HuBD7S312R4" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js&callback=printPage"></script>

	<style type="text/css">
	.header,
	.footer {
		width: 100%;
		text-align: center;
		position: fixed;
	}
	.header {
		top: 0px;
	}
	.footer {
		bottom: 0px;
	}
	.pagenum:before {
		content: counter(page);
	}
	p{
		color: #000000 !important;
	}

/*.map-container {
	width:  70%;
	height: 50vh;
	margin: .4rem;;
	}*/


</style>


<title></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body >
	<button class="map-print" style="margin-left: 65%;">print</button>
	<br>
	<div class="map-container">
		<div class="container map-container" style="page-break-after: always;">
			@for($i=0;$i<2;$i++)
			<br>
			@endfor
			<center><p style="font-size: 40px;font-family: Tahoma;">Call Detail Record Analysis Report</p></center>
			<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
			@for($i=0;$i<10;$i++)
			<br>
			@endfor

			<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
				<p style="font-size: 15px;font-family: Tahoma;"></p>
			</div>

			@for($i=0;$i<5;$i++)
			<br><br>
			@endfor

		</div>
		

		@if (isset($databases['ccf']))


		<div class="container" style="page-break-after: always;">
			@for($i=0;$i<2;$i++)
			<br>
			@endfor
			<center><p style="font-size: 40px;font-family: Tahoma; color: #000000">Call Frequency Analysis</p></center>
			<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
			@for($i=0;$i<10;$i++)
			<br>
			@endfor

			<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
				<p style="font-size: 15px;font-family: Tahoma;"></p>
			</div>

			@for($i=0;$i<5;$i++)
			<br><br>
			@endfor


		</div>

		@foreach($databases['ccf'] as $keyindex => $infos)
		<div class="box box-success color-palette-box">

			<div class="box-header with-border">
				<h3 class="box-title">Target Number: {{$infos['aparty']}}</h3>
			</div>

			<div class="box-body">    


				<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

					<thead>
						<tr>
							<th>#</th>
							<th>Aparty</th>
							<th>Bparty</th>
							<th>Total Call</th>
							<th>Total Duration</th>
							<th>Incoming Call</th>
							<th>Incoming Duration</th>
							<th>Outoging Call</th>
							<th>Outgoing Duration</th>
							<th>Incoming SMS</th>
							<th>Outgoing SMS</th>
						</tr>
					</thead>
					<tbody>


						@foreach($infos['data'] as $key => $info)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$info['aparty']}}</td>
							<td>{{$info['bparty']}}</td>
							<td>{{$info['total']}}</td>
							<td>{{$info['totalduration']}}</td>
							<td>{{$info['totalin']}}</td>
							<td>{{$info['totalinduration']}}</td>
							<td>{{$info['totalout']}}</td>
							<td>{{$info['totaloutduration']}}</td>
							<td>{{$info['totalinsms']}}</td>
							<td>{{$info['totaloutsms']}}</td>
						</tr>
						@endforeach


					</tbody>

				</table>
				@endforeach
			</div></div></div>
			@endif


			@if (isset($databases['fbd']))


			<div class="container" style="page-break-after: always;">
				@for($i=0;$i<2;$i++)
				<br>
				@endfor
				<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Call Duration Analysis</p></center>
				<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
				@for($i=0;$i<10;$i++)
				<br>
				@endfor

				<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
					<p style="font-size: 15px;font-family: Tahoma;"></p>
				</div>



				@for($i=0;$i<5;$i++)
				<br><br>
				@endfor


			</div>


			@foreach($databases['fbd'] as $keyindex => $infos)
			<div class="box box-success color-palette-box">

				<div class="box-header with-border">
					<h3 class="box-title">Target Number: {{$infos['aparty']}}</h3>
				</div>

				<div class="box-body">    


					<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

						<thead>
							<tr>
								<th>#</th>
								<th>Aparty</th>
								<th>Bparty</th>
								<th>Total Call</th>
								<th>Total Duration</th>
								<th>Incoming Call</th>
								<th>Incoming Duration</th>
								<th>Outoging Call</th>
								<th>Outgoing Duration</th>
								<th>Incoming SMS</th>
								<th>Outgoing SMS</th>
							</tr>
						</thead>
						<tbody>


							@foreach($infos['data'] as $key => $info)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$info['aparty']}}</td>
								<td>{{$info['bparty']}}</td>
								<td>{{$info['total']}}</td>
								<td>{{$info['totalduration']}}</td>
								<td>{{$info['totalin']}}</td>
								<td>{{$info['totalinduration']}}</td>
								<td>{{$info['totalout']}}</td>
								<td>{{$info['totaloutduration']}}</td>
								<td>{{$info['totalinsms']}}</td>
								<td>{{$info['totaloutsms']}}</td>
							</tr>
							@endforeach


						</tbody>

					</table>
					@endforeach
				</div></div></div>
				@endif
				
				@if (isset($databases['cd']))


				<div class="container" style="page-break-after: always;">
					@for($i=0;$i<2;$i++)
					<br>
					@endfor
					<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Call Details</p></center>
					<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
					@for($i=0;$i<10;$i++)
					<br>
					@endfor

					<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
						<p style="font-size: 15px;font-family: Tahoma;"></p>
					</div>



					@for($i=0;$i<5;$i++)
					<br><br>
					@endfor


				</div>


				@foreach($databases['cd'] as $keyindex => $infos)
				<div class="box box-success color-palette-box">

					<div class="box-header with-border">
						<h3 class="box-title">Target Number: {{$infos['aparty']}}</h3>
					</div>

					<div class="box-body">    


						<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

							<thead>
								<tr>
									<th>#</th>
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
							</thead>
							<tbody>


								@foreach($infos['data'] as $key => $cdr)
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

						</table>
						@endforeach
					</div></div>
					@endif



					@if (isset($databases['ops']))

					<div class="container" style="page-break-after: always;">
						@for($i=0;$i<2;$i++)
						<br>
						@endfor
						<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Other Party Summary</p></center>
						<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
						@for($i=0;$i<10;$i++)
						<br>
						@endfor

						<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
							<p style="font-size: 15px;font-family: Tahoma;"></p>
						</div>



						@for($i=0;$i<5;$i++)
						<br><br>
						@endfor


					</div>

					<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Bparty</th>
								<th>Aparties</th>
							</tr>
						</thead>
						<tbody>

							@foreach($databases['ops'] as $keyindex=> $info)
							<tr>
								<td>{{$keyindex}}</td>
								<td>{{$info['bparty']}}</td>
								<td>{{$info['aparties']}}</td>
							</tr>
							@endforeach

						</tbody>

					</table>
					@endif


					@if (isset($databases['is']))

					<div class="container" style="page-break-after: always;">
						@for($i=0;$i<2;$i++)
						<br>
						@endfor
						<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">IMEI Summary</p></center>
						<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
						@for($i=0;$i<10;$i++)
						<br>
						@endfor

						<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
							<p style="font-size: 15px;font-family: Tahoma;"></p>
						</div>



						@for($i=0;$i<5;$i++)
						<br><br>
						@endfor


					</div>

					<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Imei</th>
								<th>Aparty</th>
								<th>Total Call</th>
								<th>Incoming Call</th>
								<th>Outgoing Call</th>
								<th>Incoming SMS</th>
								<th>Outgoing SMS</th>
							</tr>
						</thead>
						<tbody>
							@foreach($databases['is'] as $key => $frequency)
							@foreach($frequency['data'] as $keyindex=> $info)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$frequency['aparty']}}</td>
								<td>{{$info['imei']}}</td>
								<td>{{$info['total']}}</td>
								<td>{{$info['in']}}</td>
								<td>{{$info['out']}}</td>
								<td>{{$info['insms']}}</td>
								<td>{{$info['outsms']}}</td>
							</tr>
							@endforeach
							@endforeach

						</tbody>

					</table>
					@endif


					@if (isset($databases['fclc']))


					<div class="container" style="page-break-after: always;">
						@for($i=0;$i<2;$i++)
						<br>
						@endfor
						<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">First & Last Call Analysis</p></center>
						<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
						@for($i=0;$i<10;$i++)
						<br>
						@endfor

						<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
							<p style="font-size: 15px;font-family: Tahoma;"></p>
						</div>



						@for($i=0;$i<5;$i++)
						<br><br>
						@endfor


					</div>

					@foreach($databases['fclc'] as $keyindex=> $infos)

					<div class="box box-success color-palette-box">

						<div class="box-header with-border">
							<h3 class="box-title">Target Number: {{$infos['aparty']}}</h3>
						</div>

						<div class="box-body">  

							<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

								<thead>
									<tr>
										<th style="width: 10px">#</th>
										<th>Date</th>
										<th>Time</th>
										<th>Aparty</th>
										<th>Bparty</th>
										<th>Description</th>
										<th>Call Type</th>
										<th>Duration</th>
										<th>Cell ID</th>
										<th>Provider</th>
										<th>Address</th>
									</tr>
								</thead>
								<tbody>
									@foreach($infos['info'] as $keyindex=> $info)
									<tr>
										<td>{{$keyindex}}</td>
										<td>{{$info['date']}}</td>
										<td>{{$info['time']}}</td>
										<td>{{$info['aparty']}}</td>
										<td>{{$info['bparty']}}</td>
										<td>{{$info['type']}}</td>
										<td>{{$info['usage_type']}}</td>
										<td>{{$info['call_duration']}}</td>
										<td>{{$info['cid']}}</td>
										<td>{{$info['provider']}}</td>
										<td>{{$info['address']}}</td>
									</tr>
									@endforeach

								</tbody>

							</table>
						</div></div>
						@endforeach
						@endif


						@if (isset($databases['fclcop']))

						<div class="container" style="page-break-after: always;">
							@for($i=0;$i<2;$i++)
							<br>
							@endfor
							<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">First & Last call of Other party</p></center>
							<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
							@for($i=0;$i<10;$i++)
							<br>
							@endfor

							<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
								<p style="font-size: 15px;font-family: Tahoma;"></p>
							</div>



							@for($i=0;$i<5;$i++)
							<br><br>
							@endfor


						</div>

						@foreach($databases['fclcop'] as $data)

						<div class="box box-success color-palette-box">

							<div class="box-header with-border">
								<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
							</div>

							<div class="box-body">  

								<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

									<thead>
										<tr>
											<th style="width: 10px">#</th>
											<th>Bparty</th>
											<th>Aparty</th>
											<th>Total call</th>
											<th>First Call Information</th>
											<th>Last Call Information</th>
										</tr>
									</thead>
									<tbody>
										
										@foreach($data['data'] as $keyindex=> $info)
										<tr>
											<td>{{$keyindex}}</td>
											<td>{{$info['bparty']}}</td>
											<td>{{$info['aparty']}}</td>
											<td>{{$info['total']}}</td>
											<td>{{$info['firstcall']}}</td>
											<td>{{$info['lastcall']}}</td>
										</tr>
										@endforeach
										

									</tbody>

								</table>
							</div></div>
							@endforeach
							@endif


							@if (isset($databases['dop']))

							<div class="container" style="page-break-after: always;">
								@for($i=0;$i<2;$i++)
								<br>
								@endfor
								<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Dates of Other Party</p></center>
								<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
								@for($i=0;$i<10;$i++)
								<br>
								@endfor

								<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
									<p style="font-size: 15px;font-family: Tahoma;"></p>
								</div>



								@for($i=0;$i<5;$i++)
								<br><br>
								@endfor


							</div>
							@foreach($databases['dop'] as $data)

							<div class="box box-success color-palette-box">

								<div class="box-header with-border">
									<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
								</div>

								<div class="box-body"> 



									<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

										<thead>
											<tr>
												<th style="width: 10px">#</th>
												<th>Aparty</th>
												<th>Bparty</th>
												<th>Dates</th>
											</tr>
										</thead>
										<tbody>
											

											@foreach($data['data'] as $keyindex=> $info)
											<tr>
												<td>{{$keyindex}}</td>
												<td>{{$info['aparty']}}</td>
												<td>{{$info['bparty']}}</td>
												<td>{{$info['alldate']}}</td>
											</tr>
											@endforeach
											

										</tbody>

									</table>
								</div></div>
								@endforeach
								@endif

								@if (isset($databases['r']))

								<div class="container" style="page-break-after: always;">
									@for($i=0;$i<2;$i++)
									<br>
									@endfor
									<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Address(s) of Target</p></center>
									<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
									@for($i=0;$i<10;$i++)
									<br>
									@endfor

									<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
										<p style="font-size: 15px;font-family: Tahoma;"></p>
									</div>



									@for($i=0;$i<5;$i++)
									<br><br>
									@endfor


								</div>

								@foreach($databases['r'] as $data)

								<div class="box box-success color-palette-box">

									<div class="box-header with-border">
										<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
									</div>

									<div class="box-body"> 


										<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

											<thead>
												<tr>
													<th style="width: 10px">#</th>
													<th>Aparty</th>
													<th>Address</th>
													<th>Total Days</th>
													<th>Dates</th>
												</tr>
											</thead>
											<tbody>
												@foreach($data['info'] as $keyindex=> $info)
												<tr>
													<td>{{$keyindex}}</td>
													<td>{{$info['aparty']}}</td>
													<td>{{$info['address']}}</td>
													<td>{{$info['total']}}</td>
													<td>{{$info['alldate']}}</td>
												</tr>
												@endforeach
												

											</tbody>

										</table>
									</div></div>
									@endforeach
									@endif


									@if (isset($databases['inc']))

									<div class="container" style="page-break-after: always;">
										@for($i=0;$i<2;$i++)
										<br>
										@endfor
										<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">All Incoming Call</p></center>
										<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
										@for($i=0;$i<10;$i++)
										<br>
										@endfor

										<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
											<p style="font-size: 15px;font-family: Tahoma;"></p>
										</div>



										@for($i=0;$i<5;$i++)
										<br><br>
										@endfor


									</div>

									@foreach($databases['inc'] as $data)

									<div class="box box-success color-palette-box">

										<div class="box-header with-border">
											<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
										</div>

										<div class="box-body"> 

											<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

												<thead>
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
														<th>CID</th>
														<th>Imsi</th>
														<th>Lac</th>
													</tr>
												</thead>
												<tbody>
													
													@foreach($data['cdr'] as $keyindex=> $cdr)
													<tr>
														<td>{{$keyindex}}</td>
														<td>{{$cdr->aparty}}</td>
														<td>{{$cdr->bparty}}</td>
														<td>{{$cdr->provider}}</td>
														@if(strtolower($cdr->usage_type)=='moc')
														<td><span class="badge bg-aqua">{{$cdr->usage_type}}</span></td>
														@elseif(strtolower($cdr->usage_type)=='mtc')
														<td><span class="badge bg-green">{{$cdr->usage_type}}</span></td>
														@elseif(strtolower($cdr->usage_type)=='smsmt')
														<td><span class="badge bg-yellow">{{$cdr->usage_type}}</span></td>
														@elseif(strtolower($cdr->usage_type)=='smsmo')
														<td><span class="badge bg-red">{{$cdr->usage_type}}</span></td>
														@else
														<td><span class="badge">N/A</span></td>
														@endif
														<td>{{$cdr->call_duration}}</td>
														<td>{{$cdr->imei}}</td>
														<td>{{$cdr->address}}</td>
														<td>{{$cdr->date}}</td>
														<td>{{$cdr->time}}</td>
														<td>{{$cdr->cid}}</td>
														<td>{{$cdr->imsi}}</td>
														<td>{{$cdr->lac}}</td>
													</tr>
													@endforeach
													

												</tbody>

											</table>
										</div></div>
										@endforeach
										@endif


										@if (isset($databases['ins']))

										<div class="container" style="page-break-after: always;">
											@for($i=0;$i<2;$i++)
											<br>
											@endfor
											<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">All Incoming SMS</p></center>
											<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
											@for($i=0;$i<10;$i++)
											<br>
											@endfor

											<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
												<p style="font-size: 15px;font-family: Tahoma;"></p>
											</div>



											@for($i=0;$i<5;$i++)
											<br><br>
											@endfor


										</div>

										@foreach($databases['ins'] as $data)

										<div class="box box-success color-palette-box">

											<div class="box-header with-border">
												<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
											</div>

											<div class="box-body"> 

												<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

													<thead>
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
															<th>CID</th>
															<th>Imsi</th>
															<th>Lac</th>
														</tr>
													</thead>
													<tbody>
														
														@foreach($data['cdr'] as $keyindex=> $cdr)
														<tr>
															<td>{{$keyindex}}</td>
															<td>{{$cdr->aparty}}</td>
															<td>{{$cdr->bparty}}</td>
															<td>{{$cdr->provider}}</td>
															@if(strtolower($cdr->usage_type)=='moc')
															<td><span class="badge bg-aqua">{{$cdr->usage_type}}</span></td>
															@elseif(strtolower($cdr->usage_type)=='mtc')
															<td><span class="badge bg-green">{{$cdr->usage_type}}</span></td>
															@elseif(strtolower($cdr->usage_type)=='smsmt')
															<td><span class="badge bg-yellow">{{$cdr->usage_type}}</span></td>
															@elseif(strtolower($cdr->usage_type)=='smsmo')
															<td><span class="badge bg-red">{{$cdr->usage_type}}</span></td>
															@else
															<td><span class="badge">N/A</span></td>
															@endif
															<td>{{$cdr->call_duration}}</td>
															<td>{{$cdr->imei}}</td>
															<td>{{$cdr->address}}</td>
															<td>{{$cdr->date}}</td>
															<td>{{$cdr->time}}</td>
															<td>{{$cdr->cid}}</td>
															<td>{{$cdr->imsi}}</td>
															<td>{{$cdr->lac}}</td>
														</tr>
														@endforeach
														

													</tbody>

												</table>
											</div></div>
											@endforeach
											@endif


											@if (isset($databases['outc']))


											<div class="container" style="page-break-after: always;">
												@for($i=0;$i<2;$i++)
												<br>
												@endfor
												<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">All Outgoing Call</p></center>
												<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
												@for($i=0;$i<10;$i++)
												<br>
												@endfor

												<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
													<p style="font-size: 15px;font-family: Tahoma;"></p>
												</div>



												@for($i=0;$i<5;$i++)
												<br><br>
												@endfor


											</div>
											@foreach($databases['outc'] as $data)

											<div class="box box-success color-palette-box">

												<div class="box-header with-border">
													<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
												</div>

												<div class="box-body"> 

													<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

														<thead>
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
																<th>CID</th>
																<th>Imsi</th>
																<th>Lac</th>
															</tr>
														</thead>
														<tbody>
															@foreach($data['cdr'] as $keyindex=> $cdr)
															<tr>
																<td>{{$keyindex}}</td>
																<td>{{$cdr->aparty}}</td>
																<td>{{$cdr->bparty}}</td>
																<td>{{$cdr->provider}}</td>
																@if(strtolower($cdr->usage_type)=='moc')
																<td><span class="badge bg-aqua">{{$cdr->usage_type}}</span></td>
																@elseif(strtolower($cdr->usage_type)=='mtc')
																<td><span class="badge bg-green">{{$cdr->usage_type}}</span></td>
																@elseif(strtolower($cdr->usage_type)=='smsmt')
																<td><span class="badge bg-yellow">{{$cdr->usage_type}}</span></td>
																@elseif(strtolower($cdr->usage_type)=='smsmo')
																<td><span class="badge bg-red">{{$cdr->usage_type}}</span></td>
																@else
																<td><span class="badge">N/A</span></td>
																@endif
																<td>{{$cdr->call_duration}}</td>
																<td>{{$cdr->imei}}</td>
																<td>{{$cdr->address}}</td>
																<td>{{$cdr->date}}</td>
																<td>{{$cdr->time}}</td>
																<td>{{$cdr->cid}}</td>
																<td>{{$cdr->imsi}}</td>
																<td>{{$cdr->lac}}</td>
															</tr>
															@endforeach
															

														</tbody>

													</table>
												</div></div>
												@endforeach
												@endif


												@if (isset($databases['outs']))


												<div class="container" style="page-break-after: always;">
													@for($i=0;$i<2;$i++)
													<br>
													@endfor
													<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">All Outgoing SMS</p></center>
													<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
													@for($i=0;$i<10;$i++)
													<br>
													@endfor

													<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
														<p style="font-size: 15px;font-family: Tahoma;"></p>
													</div>



													@for($i=0;$i<5;$i++)
													<br><br>
													@endfor


												</div>
												@foreach($databases['outs'] as $data)
												<div class="box box-success color-palette-box">

													<div class="box-header with-border">
														<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
													</div>

													<div class="box-body"> 

														<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

															<thead>
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
																	<th>CID</th>
																	<th>Imsi</th>
																	<th>Lac</th>
																</tr>
															</thead>
															<tbody>
																
																@foreach($data['cdr'] as $keyindex=> $cdr)
																<tr>
																	<td>{{$keyindex}}</td>
																	<td>{{$cdr->aparty}}</td>
																	<td>{{$cdr->bparty}}</td>
																	<td>{{$cdr->provider}}</td>
																	@if(strtolower($cdr->usage_type)=='moc')
																	<td><span class="badge bg-aqua">{{$cdr->usage_type}}</span></td>
																	@elseif(strtolower($cdr->usage_type)=='mtc')
																	<td><span class="badge bg-green">{{$cdr->usage_type}}</span></td>
																	@elseif(strtolower($cdr->usage_type)=='smsmt')
																	<td><span class="badge bg-yellow">{{$cdr->usage_type}}</span></td>
																	@elseif(strtolower($cdr->usage_type)=='smsmo')
																	<td><span class="badge bg-red">{{$cdr->usage_type}}</span></td>
																	@else
																	<td><span class="badge">N/A</span></td>
																	@endif
																	<td>{{$cdr->call_duration}}</td>
																	<td>{{$cdr->imei}}</td>
																	<td>{{$cdr->address}}</td>
																	<td>{{$cdr->date}}</td>
																	<td>{{$cdr->time}}</td>
																	<td>{{$cdr->cid}}</td>
																	<td>{{$cdr->imsi}}</td>
																	<td>{{$cdr->lac}}</td>
																</tr>
																@endforeach

															</tbody>

														</table>
													</div></div>
													@endforeach
													@endif


													@if (isset($databases['ts']))


													<div class="container" style="page-break-after: always;">
														@for($i=0;$i<2;$i++)
														<br>
														@endfor
														<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Tower Summary</p></center>
														<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
														@for($i=0;$i<10;$i++)
														<br>
														@endfor

														<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
															<p style="font-size: 15px;font-family: Tahoma;"></p>
														</div>



														@for($i=0;$i<5;$i++)
														<br><br>
														@endfor


													</div>

													@foreach($databases['ts'] as $frequency)

													<div class="box box-success color-palette-box">

														<div class="box-header with-border">
															<h3 class="box-title">Target Number: {{$frequency['aparty']}}</h3>
														</div>

														<div class="box-body"> 



															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>Address</th>
																		<th>Aparty</th>
																		<th>Total Call</th>
																		<th>Total Duration</th>
																		<th>Incoming Call</th>
																		<th>Incoming Duration</th>
																		<th>Outoging Call</th>
																		<th>Outgoing Duration</th>
																		<th>Incoming SMS</th>
																		<th>Outgoing SMS</th>
																	</tr>
																</thead>
																<tbody>
																	
																	@foreach($frequency['data'] as $keyindex=> $info)
																	<tr>
																		<td>{{$keyindex}}</td>
																		<td>{{$info['address']}}</td>
																		<td>{{$info['aparty']}}</td>
																		<td>{{$info['total']}}</td>
																		<td>{{$info['totalduration']}}</td>
																		<td>{{$info['totalin']}}</td>
																		<td>{{$info['totalinduration']}}</td>
																		<td>{{$info['totalout']}}</td>
																		<td>{{$info['totaloutduration']}}</td>
																		<td>{{$info['totalinsms']}}</td>
																		<td>{{$info['totaloutsms']}}</td>
																	</tr>
																	@endforeach
																	

																</tbody>

															</table>
														</div></div>
														@endforeach
														@endif


														@if (isset($databases['tcd']))


														<div class="container" style="page-break-after: always;">
															@for($i=0;$i<2;$i++)
															<br>
															@endfor
															<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Tower Details</p></center>
															<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
															@for($i=0;$i<10;$i++)
															<br>
															@endfor

															<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																<p style="font-size: 15px;font-family: Tahoma;"></p>
															</div>



															@for($i=0;$i<5;$i++)
															<br><br>
															@endfor


														</div>

														<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

															<thead>
																<tr>
																	<th style="width: 10px">#</th>
																	<th>Address</th>
																	<th>Aparty</th>
																	<th>Bparty</th>
																	<th>Provider</th>
																	<th>Usage Type</th>
																	<th>Duration</th>						
																	<th>Imei</th>                
																	<th>Date</th>
																	<th>Time</th>
																	<th>LAC</th>
																	<th>CID</th>
																	<th>Imsi</th>
																</tr>
															</thead>
															<tbody>
																@foreach($databases['tcd'] as $keyindex=> $cdr)
																<tr>
																	<td>{{$keyindex}}</td>
																	<td>{{$cdr->address}}</td>
																	<td>{{$cdr->aparty}}</td>
																	<td>{{$cdr->bparty}}</td>
																	<td>{{$cdr->provider}}</td>
																	@if(strtolower($cdr->usage_type)=='moc')
																	<td><span class="badge bg-aqua">{{$cdr->usage_type}}</span></td>
																	@elseif(strtolower($cdr->usage_type)=='mtc')
																	<td><span class="badge bg-green">{{$cdr->usage_type}}</span></td>
																	@elseif(strtolower($cdr->usage_type)=='smsmt')
																	<td><span class="badge bg-yellow">{{$cdr->usage_type}}</span></td>
																	@elseif(strtolower($cdr->usage_type)=='smsmo')
																	<td><span class="badge bg-red">{{$cdr->usage_type}}</span></td>
																	@else
																	<td><span class="badge">N/A</span></td>
																	@endif
																	<td>{{$cdr->call_duration}}</td>
																	<td>{{$cdr->imei}}</td>
																	<td>{{$cdr->date}}</td>
																	<td>{{$cdr->time}}</td>
																	<td>{{$cdr->lac}}</td>
																	<td>{{$cdr->cid}}</td>
																	<td>{{$cdr->imsi}}</td>            
																</tr>
																@endforeach

															</tbody>

														</table>
														@endif


														@if (isset($databases['tcn']))


														<div class="container" style="page-break-after: always;">
															@for($i=0;$i<2;$i++)
															<br>
															@endfor
															<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Tower Common Numbers</p></center>
															<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
															@for($i=0;$i<10;$i++)
															<br>
															@endfor

															<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																<p style="font-size: 15px;font-family: Tahoma;"></p>
															</div>



															@for($i=0;$i<5;$i++)
															<br><br>
															@endfor


														</div>

														<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

															<thead>
																<tr>
																	<th style="width: 10px">#</th>
																	<th>Aparty</th>
																	<th>Address</th>
																</tr>
															</thead>
															<tbody>
																@foreach($databases['tcn'] as $keyindex=> $cdr)
																<tr>
																	<td>{{$keyindex}}</td>
																	<td>{{$cdr['aparty']}}</td>
																	<td>{{$cdr['cids']}}</td>          
																</tr>
																@endforeach

															</tbody>

														</table>
														@endif


														@if (isset($databases['tcop']))


														<div class="container" style="page-break-after: always;">
															@for($i=0;$i<2;$i++)
															<br>
															@endfor
															<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Tower Common Other Parties</p></center>
															<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
															@for($i=0;$i<10;$i++)
															<br>
															@endfor

															<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																<p style="font-size: 15px;font-family: Tahoma;"></p>
															</div>



															@for($i=0;$i<5;$i++)
															<br><br>
															@endfor


														</div>

														@foreach($databases['tcop'] as $key=> $data)
														<div class="box box-success color-palette-box">

															<div class="box-header with-border">
																<h3 class="box-title">Target Number: {{$data['aparty']}}</h3>
															</div>

															<div class="box-body"> 

																<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																	<thead>
																		<tr>
																			<th style="width: 10px">#</th>
																			<th>Aparty</th>
																			<th>Bparty</th>
																			<th>Cid</th>
																		</tr>
																	</thead>
																	<tbody>
																		
																		@foreach($data['data'] as $keyindex=> $cdr)
																		<tr>
																			<td>{{$keyindex+1}}</td>
																			<td>{{$cdr['aparty']}}</td>
																			<td>{{$cdr['bparty']}}</td>
																			<td>{{$cdr['cids']}}</td>         
																		</tr>
																		@endforeach
																		

																	</tbody>

																</table>
															</div></div>
															@endforeach
															@endif


															@if (isset($databases['tcimei']))


															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Tower Common IMEI</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>

															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>Aparty</th>
																		<th>IMEI</th>
																		<th>CID</th>
																	</tr>
																</thead>
																<tbody>
																	@foreach($databases['tcimei'] as $key=> $data)
																	@foreach($data as $keyindex=> $cdr)
																	<tr>
																		<td>{{$keyindex+1}}</td>
																		<td>{{$cdr['aparty']}}</td>
																		<td>{{$cdr['imei']}}</td>
																		<td>{{$cdr['cids']}}</td>      
																	</tr>
																	@endforeach
																	@endforeach

																</tbody>

															</table>
															@endif


															@if (isset($databases['tic']))


															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Tower Internal Calls</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>

															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>Aparty</th>
																		<th>Bparty</th>
																		<th>Address</th>
																		<th>Date</th>
																		<th>Time</th>
																	</tr>
																</thead>
																<tbody>
																	@foreach($databases['tic'] as $keyindex=> $cdr)
																	<tr>
																		<td>{{$keyindex}}</td>
																		<td>{{$cdr->aparty}}</td>
																		<td>{{$cdr->bparty}}</td>
																		<td>{{$cdr->address}}</td>
																		<td>{{$cdr->date}}</td>
																		<td>{{$cdr->time}}</td>   
																	</tr>
																	@endforeach

																</tbody>

															</table>
															@endif


															@if (isset($databases['icn']))


															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">IMEI common numbers</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>

															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>IMEI</th>
																		<th>Numbers</th>
																	</tr>
																</thead>
																<tbody>
																	@foreach($databases['icn'] as $keyindex=> $cdr)
																	<tr>
																		<td>{{$keyindex}}</td>
																		<td>{{$cdr['imei']}}</td>
																		<td>@foreach($cdr['number'] as $key=> $number)
																			{{$number}} |
																			@endforeach
																		</td>
																	</tr>
																	@endforeach

																</tbody>

															</table>
															@endif

															@if (isset($databases['icd']))

															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">IMEI Call details</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>

															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>IMEI</th>
																		<th>Aparty</th>
																		<th>Total Call</th>
																		<th>Total Duration</th>
																		<th>Incoming Call</th>
																		<th>Incoming Duration</th>
																		<th>Outoging Call</th>
																		<th>Outgoing Duration</th>
																		<th>Incoming SMS</th>
																		<th>Outgoing SMS</th>
																	</tr>
																</thead>
																<tbody>
																	@foreach($databases['icd'] as $keyindex=> $infos)
																	@foreach($infos as $info)
																	<tr>
																		<td>{{$keyindex}}</td>
																		<td>{{$info['imei']}}</td>
																		<td>{{$info['aparty']}}</td>
																		<td>{{$info['totalcall']}}</td>
																		<td>{{$info['totalduration']}}</td>
																		<td>{{$info['totalincall']}}</td>
																		<td>{{$info['totalinduration']}}</td>
																		<td>{{$info['totaloutcall']}}</td>
																		<td>{{$info['totaloutduration']}}</td>
																		<td>{{$info['totalinsms']}}</td>
																		<td>{{$info['totaloutsms']}}</td>
																	</tr>
																	@endforeach
																	@endforeach

																</tbody>

															</table>
															@endif

															@if (isset($databases['iu']))

															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">IMEI Usage</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>

															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>IMEI</th>
																		<th>Aparty</th>
																		<th>Start Date</th>
																		<th>End Date</th>
																		<th>Total Call/SMS</th>
																	</tr>
																</thead>
																<tbody>
																	@foreach($databases['iu'] as $keyindex=> $infos)
																	@foreach($infos as $info)
																	<tr>
																		<td>{{$keyindex}}</td>
																		<td>{{$info['imei']}}</td>
																		<td>{{$info['aparty']}}</td>
																		<td>{{$info['start']['date']}}</td>
																		<td>{{$info['end']['date']}}</td>
																		<td>{{$info['count']}}</td>
																	</tr>
																	@endforeach
																	@endforeach

																</tbody>

															</table>
															@endif

															@if (isset($databases['icc']))

															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">IMEI Common Callers</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>

															<table style="page-break-after: always;" id="example2" class="table table-bordered table-hover">

																<thead>
																	<tr>
																		<th style="width: 10px">#</th>
																		<th>IMEI</th>
																		<th>Aparty</th>
																		<th>Bparty</th>
																	</tr>
																</thead>
																<tbody>
																	@foreach($databases['icc'] as $keyindex=> $info)

																	<tr>
																		<td>{{$keyindex}}</td>
																		<td>{{$info['imei']}}</td>
																		<td>{{$info['aparties']}}</td>
																		<td>{{$info['bparties']}}</td>
																	</tr>

																	@endforeach

																</tbody>

															</table>
															@endif


															@if (isset($databases['cca']))

															<div class="container" style="page-break-after: always;">
																@for($i=0;$i<2;$i++)
																<br>
																@endfor
																<center><p style="font-size: 40px;font-family: Tahoma; color: #ff0000">Conferrence call analysis</p></center>
																<center><p style="font-size: 20px;font-family: Tahoma;">Date: {{$databases['dates']}}</p></center>
																@for($i=0;$i<10;$i++)
																<br>
																@endfor

																<div  style="width: 700px;height:700px; padding: 20px;border: 1px solid gray;margin: 0 auto;">
																	<p style="font-size: 15px;font-family: Tahoma;"></p>
																</div>



																@for($i=0;$i<5;$i++)
																<br><br>
																@endfor


															</div>



															@foreach($databases['cca'] as $frequency)

															<div class="row" >
																<div class="col-xs-12">

																	<div class="box box-success color-palette-box">
																		<div class="box-header with-border">
																			<h3 class="box-title" style="color: #00a65a"><i class="fa fa-bars"></i> Date: {{$frequency['date']['date']}}</h3>
																		</div>
																		<div class="box-body">
																			<table class="table table-bordered">
																				<tbody>
																					<tr>
																						<th style="width: 10px">#</th>
																						<th>Originating Call</th>
																						<th>Joining Call</th>

																					</tr>
																					@foreach($frequency['data'] as $keyindex=> $infos)
																					<tr>
																						<td>{{$keyindex}}</td>
																						<td><strong style="color:#FB667A">{{$infos['maincall']['aparty']}}</strong> to <strong style="color:#FB667A">{{$infos['maincall']['bparty']}}</strong> from {{$infos['maincall']['time']}} to {{$infos['maincall']['endtime']}}</td>
																						<td>@foreach($infos['conferencecall'] as $conf)

																							<strong style="color:#FB667A">{{$conf['aparty']}}</strong> to <strong style="color:#FB667A">{{$conf['bparty']}}</strong> from {{$conf['time']}} to {{$conf['endtime']}} <br \>

																							@endforeach
																						</td>
																					</tr>
																					@endforeach
																				</tbody></table>

																			</div>
																			<!-- /.box-body -->
																		</div>


																	</div>
																</div>

																@endforeach

																@endif
															</div>


														</body>

														<script>
															$('.map-print').on('click',

    // printAnyMaps :: _ -> HTML
    function printAnyMaps() {
    	alert("Do you want to print?");
    	var $body = $('body');
    	var $mapContainer = $('.map-container');
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

    	$body.append($content);
    	$mapContainerParent.append($mapContainer);

    	$printContainer.remove();
    	$patchedStyle.remove();
    });
</script>
</html>

