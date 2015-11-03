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
