<!DOCTYPE HTML>
<html>
<head>
	<title></title>
	<link href="{{asset('loginstyle/css/style.css')}}" rel="stylesheet" type="text/css" media="all"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="Transparent Login Form Responsive Widget,Login form widgets, Sign up Web forms , Login signup Responsive web form,Flat Pricing table,Flat Drop downs,Registration Forms,News letter Forms,Elements" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css' />
</head>

<body>
	<div class="header-w3l">
		<h1> <span style="color:#4DC3FA">Call Detail</span><span style="color:#FB667A"> Record Analyzer</span></h1>
	</div>
	<div class="main-content-agile">
		<div class="sub-main-w3">	
			<form action="{{ route('login.post') }}" method="post">
				@if ($errors->has())
				<div class="alert alert-danger">
					<p style="color: #ffffff">@foreach ($errors->all() as $error)
						{{ $error }}<br>
						@endforeach</p>
					</div>
					@endif

					@if (session('status'))
					<div class="alert alert-danger" >
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <p style="color: #ffffff">{{ session('status') }}</p>
					</div>
					@endif
					{{ csrf_field() }}
					<input placeholder="User" name="email" class="user" type="text" required placeholder=""><br>
					<input  placeholder="Password" name="password" class="pass" type="password" required placeholder=""><br>
					<input type="submit" value="">
				</form>
			</div>
		</div>
	</body>
	</html>