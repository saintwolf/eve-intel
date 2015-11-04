@extends('layout.default')

@section('header')
@endsection

@section('content')

	<div class="container vertical-center">
		<div class="col-md-offset-3 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">{!! trans('app.error') !!}</div>
				<div class="panel-body">{!! trans('app.404', ['url' => route('home')]) !!}</div>
			</div>
		</div>
	</div>

@endsection

@section('javascript')
	@parent
	<script>
		$('body').css('background', '#000000 url("img/bg' + Math.floor((Math.random() * 8) + 1) + '.jpg") center center fixed no-repeat');
		$('body').css('-webkit-background-size', 'cover');
		$('body').css('-moz-background-size', 'cover');
		$('body').css('-o-background-size', 'cover');
		$('body').css('background-size', 'cover');
	</script>
@show
