@extends('layout.master')
@section('title', trans('home.header1'))

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

		<ul class="nav nav-pills">
			<li class="{{ in_array(Request::input('action'), ['login', '']) ? 'active' : '' }}"><a href="#login" data-toggle="tab">{{ trans('home.Login') }}</a></li>
			<li class="{{ Request::input('action') == 'register' ? 'active' : '' }}"><a href="#register" data-toggle="tab">{{ trans('home.Register') }}</a></li>
		</ul>


		<div class="tab-content">
			<div id="login" class="tab-pane {{ in_array(Request::input('action'), ['login', '']) ? 'active' : '' }}">
				{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}
				<div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
				<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('home.password'), 'required']) !!}</div>
				<div class="row">
					<div class="col-sm-8">
						<div class="form-group">{!! Form::button(trans('home.Login'), ['name' => 'action', 'value' => 'login', 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
					</div>
					<div class="col-sm-4">
						<a href="{{ action('PasswordController@getEmail') }}" class="btn btn-block btn-default" role="button">{{ trans('home.ForgotPassword') }}</a>
					</div>
				</div>
				{!! Form::close() !!}
			</div>

			<div id="register" class="tab-pane {{ Request::input('action') == 'register' ? 'active' : '' }}">
				{!! Form::open(['action' => 'HomeController@loginOrRegister']) !!}
				<div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
				<div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('home.password'), 'required']) !!}</div>
				<div class="form-group">{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('home.confirmPassword'), 'required']) !!}</div>
				<div class="form-group">{!! Form::button(trans('home.Register'), ['name' => 'action', 'value' => 'register', 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
				{!! Form::close() !!}
			</div>
		</div>

		@else
		<a href="{{ action('DashboardController@index') }}" class="btn btn-block btn-primary">{{ trans('home.Dashboard') }}</a>
		@endif
	</div>
</div>

<div class="container">
	<h1 class="page-header">{{ trans('home.header1') }}</h1>

	<div class="columns">
		<p>
			{{ trans('home.description1') }} <a href='{{ action('HomeController@docs') }}'>{{ trans('home.templateDocumentation') }}</a>{{ trans('home.description1-2') }}
		</p>
		<p>
			{{ trans('home.description1-3') }} <a href="http://docs.docgen.apiary.io">Apiary docs</a>{{ trans('home.description1-4') }}
		</p>
		<p>
			{{ trans('home.description1-5') }}
		</p>
	</div>
</div>

@endsection
