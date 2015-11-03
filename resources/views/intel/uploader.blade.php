@extends('layout.default')

@section('header')
@endsection

@section('content')

	<div class="container vertical-center">
		<div class="col-md-offset-3 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">{!! trans('app.uploader') !!}</div>
				<div class="panel-body">
					<p>{!! trans('app.uploader_token', ['token' => auth()->user()->uploader_token]) !!}</p>

					<p>{!! trans('app.uploader_download', ['url' => secure_asset('uploader.py')]) !!}</p>

					<p>{!! trans('app.uploader_explain', ['url' => action('ReportController@store'), 'token' => auth()->user()->uploader_token]) !!}</p>
				</div>
			</div>
		</div>
	</div>

@endsection
