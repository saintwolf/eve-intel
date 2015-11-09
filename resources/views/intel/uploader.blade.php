@extends('layout.default')

@section('header')
@endsection

@section('content')
	<div class="container vertical-center">
		<div class="col-md-offset-2 col-md-8">

			<div class="panel panel-default">
				<div class="panel-heading">{!! trans('app.uploader_download') !!}</div>
				<div class="panel-body">
					<p>{!! trans('app.uploader_download_explain') !!}</p>

					<p>{!! trans('app.uploader_download_binary', ['url' => url('uploader.zip')]) !!}</p>

					<p>{!! trans('app.uploader_download_source', ['url' => url('uploader.py')]) !!}</p>
				</div>
			</div>

			<div class="panel panel-success">
				<div class="panel-heading">{!! trans('app.uploader_config') !!}</div>
				<div class="panel-body">
					<p>{!! trans('app.uploader_config_explain') !!}</p>
<pre>
cli    = False
filter = {!! env('UPLOADER_FILTER', '(.*)_\d+_\d+') !!}
logdir = ""
token  = {!! auth()->user()->uploader_token !!}
url    = {!! action('ReportController@store') !!}
</pre>
				</div>
				<div class="panel-footer"><small>{!! trans('app.uploader_config_token_explain') !!}</small></div>
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
