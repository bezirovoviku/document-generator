@extends('layout.master')
@section('title', 'How does it work')

@section('content')

<div class="jumbotron">
<div class="container">

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ Config::get('languages')[App::getLocale()] }}
    </a>
    <ul class="dropdown-menu">
        @foreach (Config::get('languages') as $lang => $language)
            @if ($lang != App::getLocale())
                <li>
                    {!! link_to_route('lang.switch', $language, $lang) !!}
                </li>
            @endif
        @endforeach
    </ul>
</li>

<h1>{{ trans('home.header0') }}</h1>
<p>{{ trans('home.description0') }}</p>

@if (!Auth::check())
	{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}

<ul class="nav nav-tabs2">
	<li role="presentation" class="active"><a href="{{ action('HomeController@index') }}">{{ trans('home.Login') }}</a></li>
 	<li role="presentation"><a href="{{ action('HomeController@register') }}">{{ trans('home.Register') }}</a></li>
</ul>

<div class="tab-content">
	<div id="Login">
		  	<p>
			<div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
			<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('home.password'), 'required']) !!}</div>
			</p>
			<div class="row">
				<div class="col-sm-4 col-sm-push-8">
					<a href="{{ action('PasswordController@getEmail') }}" class="btn btn-block btn-default" role="button">{{ trans('home.ForgotPassword') }}</a>
				</div>
				<div class="col-sm-8 col-sm-pull-4">
					<div class="form-group">{!! Form::button( trans('home.Login'), ['name' => 'login', 'value' => 1, 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
				</div>
			</div>
			
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
