@extends('layout.master')
@section('title', 'How does it work')

@section('content')

<div class="jumbotron">
<div class="container">

<h1>Document generator</h1>
<p>Generate batches of DOCX documents based on single template using simple API.</p>

@if (!Auth::check())
	{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}

<ul class="nav nav-tabs2">
	<li role="presentation" class="active"><a href="{{ action('HomeController@index') }}">Login</a></li>
 	<li role="presentation"><a href="{{ action('HomeController@register') }}">Register</a></li>
</ul>

<div class="tab-content">
	<div id="Login">
		  	<p>
			<div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
			<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'password', 'required']) !!}</div>
			</p>
			<div class="row">
				<div class="col-sm-4 col-sm-push-8">
					<a href="{{ action('PasswordController@getEmail') }}" class="btn btn-block btn-default" role="button">Forgot password</a>
				</div>
				<div class="col-sm-8 col-sm-pull-4">
					<div class="form-group">{!! Form::button('Login', ['name' => 'login', 'value' => 1, 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
				</div>
			</div>
			
  	</div>
</div>

	{!! Form::close() !!}
@else
	<a href="{{ action('DashboardController@index') }}" class="btn btn-block btn-primary">Go to your dashboard</a>
@endif
</div>
</div>

<div class="container">
	<h1 class="page-header">How does it work</h1>

	<div class="row">
		<div class="col-md-6">
			<p>After registering, you will need to create your template. Template must be in DOCX format,
			so you will probably need to use Microsft Word (2007 or newer). How to create your own tempale,
			refer to our <a href='#'>template documentation</a>. Once you have your template, upload it using our dashboard.</p>
		</div>
		<div class="col-md-6">
			<p>When you have your template uploaded and ready, you can start using API to send us data for documents you need to be generated.
			On how to use API, refer to our <a href="http://docs.docgen.apiary.io">Apiary docs</a>. Each batch of documents needs to be
			transfered as single request containing all required data.</p>
			<p>Once we get your data, we'll start generating documents you requested.
			This can take a while, so you won't receive your download link immediately. You will need to either wait and periodically check
			request status, if it's completed. Once it will be completed, you're free to download your generated documents as zip archive</p>
		</div>
	</div>
</div>

@endsection
