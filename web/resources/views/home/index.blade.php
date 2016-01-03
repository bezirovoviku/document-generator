@extends('layout.master')
@section('title', 'How does it work')

@section('content')

<div class="jumbotron">
	<div class="container">

		<h1>Document generator</h1>
		<p>Generate batches of DOCX documents based on single template using simple API.</p>

		@if (!Auth::check())

		<ul class="nav nav-pills">
			<li class="{{ in_array(Request::input('action'), ['login', '']) ? 'active' : '' }}"><a href="#login" data-toggle="tab">Login</a></li>
			<li class="{{ Request::input('action') == 'register' ? 'active' : '' }}"><a href="#register" data-toggle="tab">Register</a></li>
		</ul>


		<div class="tab-content">
			<div id="login" class="tab-pane {{ in_array(Request::input('action'), ['login', '']) ? 'active' : '' }}">
				{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}
				<div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
				<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'password', 'required']) !!}</div>
				<div class="row">
					<div class="col-sm-8">
						<div class="form-group">{!! Form::button('Login', ['name' => 'action', 'value' => 'login', 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
					</div>
					<div class="col-sm-4">
						<a href="{{ action('PasswordController@getEmail') }}" class="btn btn-block btn-default" role="button">Forgot password</a>
					</div>
				</div>
				{!! Form::close() !!}
			</div>

			<div id="register" class="tab-pane {{ Request::input('action') == 'register' ? 'active' : '' }}">
				{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}
				<div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
				<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'password', 'required']) !!}</div>
				<div class="form-group">{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'confirm password', 'required']) !!}</div>
				<div class="form-group">{!! Form::button('Register', ['name' => 'action', 'value' => 'register', 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
				{!! Form::close() !!}
			</div>
		</div>

		@else
		<a href="{{ action('DashboardController@index') }}" class="btn btn-block btn-primary">Go to your dashboard</a>
		@endif
	</div>
</div>

<div class="container">
	<h1 class="page-header">How does it work</h1>

	<div class="columns">
		<p>
			After registering, you will need to create your template. Template must be in DOCX format,
			so you will probably need to use Microsft Word (2007 or newer). How to create your own tempale,
			refer to our <a href='{{ action('HomeController@docs') }}'>template documentation</a>. Once you have your template, upload it using our dashboard.
		</p>
		<p>
			When you have your template uploaded and ready, you can start using API to send us data for documents you need to be generated.
			On how to use API, refer to our <a href="http://docs.docgen.apiary.io">Apiary docs</a>. Each batch of documents needs to be
			transfered as single request containing all required data.
		</p>
		<p>
			Once we get your data, we'll start generating documents you requested. This can take a while, so you won't receive your download link immediately. You will need to either wait and periodically check
			request status, if it's completed. Once it will be completed, you're free to download your generated documents as zip archive
		</p>
	</div>
</div>

@endsection
