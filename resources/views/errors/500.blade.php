@extends('layout.default')

@section('header')
@endsection

@section('content')

	<div class="container vertical-center">
		<div class="col-md-offset-3 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">{!! trans('app.error') !!}</div>
				<div class="panel-body">{!! trans('app.500', ['url' => route('home')]) !!}</div>
			</div>
		</div>
	</div>

@endsection
