<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{!! @env('APP_NAME') !!}</title>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link href="/css/all.css" rel="stylesheet" />
		<link href="favicon.png" rel="shortcut icon" />
	</head>
	<body>
		@section('header')
			@include('layout.navbar')
		@show
		<div class="container">
			<div class="content">
				@yield('content')
			</div>
		</div>
		@section('javascript')
			<script src="/js/all.js"></script>
			<script>$(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });</script>
			<script>$(document).ready(function(){ $('[data-toggle="popover"]').popover(); });</script>
		@show
		<div style="font-size:70%; position:fixed; bottom:1px; right:1px; z-index:-23;" >
			<a href="https://evewho.com/pilot/kiu+Nakamura">kiu Nakamura</a> &bull; <a href="https://github.com/kiu/bravecollective-intel">github.com</a>
			&nbsp;|
			<a href="https://evewho.com/pilot/Memelo+Melo">Memelo Melo</a> &bull; <a href="https://github.com/msims04/bravecollective-intel">github.com</a>
		</div>
	</body>
</html>
