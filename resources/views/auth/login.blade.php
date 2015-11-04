@extends('layout.default')

@section('title', trans('app.login'))

@section('header')
@endsection

@section('content')
	<div class="container vertical-center">
		<div class="container col-md-6">
			@include('layout.flash')

			{!! Form::open(['route' => 'postLogin']) !!}

				<div class="form-group">
					{!! Form::label('username', trans('app.username'), ['class' => 'label label-default']) !!}
					{!! Form::text('username', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('password', trans('app.password'), ['class' => 'label label-default']) !!}
					{!! Form::password('password', ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default">
							{!! Form::checkbox('remember_me') !!} {!! trans('app.remember_me') !!}
						</label>
					</div>
				</div>

				<div class="form-group">
					{!! Form::submit(trans('app.login'), ['class' => 'btn btn-default btn-block']) !!}
				</div>

			{!! Form::close() !!}
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