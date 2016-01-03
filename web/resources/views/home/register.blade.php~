@extends('layout.master')
@section('title', 'How does it work')

@section('content')

<div class="jumbotron">
<div class="container">

<h1>{{ trans('home.header0') }}</h1>
<p>{{ trans('home.description0') }}</p>

@if (!Auth::check())
	{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}

<ul class="nav nav-tabs2">
	<li role="presentation"><a href="{{ action('HomeController@index') }}">{{ trans('home.Login') }}</a></li>
 	<li role="presentation" class="active"><a href="{{ action('HomeController@register') }}">{{ trans('home.Register') }}</a></li>
</ul>

<div class="tab-content">
  	<div id="Register">
		  	<p>
		    <div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
			<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('home.password'), 'required']) !!}</div>
			<div class="form-group">{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('home.confirmPassword'), 'required']) !!}</div>
			<div class="form-group">{!! Form::button(trans('home.Register'), ['name' => 'register', 'value' => 1, 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
			</p>
  	</div>
</div>

	{!! Form::close() !!}
@else
	<a href="{{ action('DashboardController@index') }}" class="btn btn-block btn-primary">{{ trans('home.Dashboard') }}</a>
@endif
</div>
</div>

<div class="container">
	<h1 class="page-header">{{ trans('home.header1') }}</h1>

	<div class="row">
		<div class="col-md-6">
			<p>{{ trans('home.description1') }} <a href='#'>{{ trans('home.templateDocumentation') }}</a>{{ trans('home.description1-2') }}</p>
		</div>
		<div class="col-md-6">
			<p>{{ trans('home.description1-3') }} <a href="http://docs.docgen.apiary.io">Apiary docs</a>{{ trans('home.description1-4') }}</p>
			<p>{{ trans('home.description1-5') }}</p>
		</div>
	</div>
</div>

@endsection
