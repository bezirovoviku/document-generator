@extends('layout.master')
@section('title', 'How does it work')

@section('content')

<div class="jumbotron">
<div class="container">

<h1>Document generator</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam commodi, distinctio sequi beatae inventore vitae possimus libero numquam error ut, ex odio repellat sint et fugit, maiores laborum cumque suscipit!</p>

{!! Form::open() !!}
    <div class="form-group">{!! Form::email('email', Request::old('email'), ['class' => 'form-control', 'placeholder' => 'email', 'required']) !!}</div>
    <div class="form-group">{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'password', 'required']) !!}</div>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">{!! Form::button('Register', ['name' => 'register', 'type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}</div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">{!! Form::button('Login', ['name' => 'login', 'type' => 'submit', 'class' => 'btn btn-block btn-default']) !!}</div>
        </div>
    </div>
{!! Form::close() !!}
</div>
</div>

<div class="container">
    <h1 class="page-header">How does it work</h1>

    <div class="row">
        <div class="col-md-6">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
        <div class="col-md-6">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>

</div>

@endsection
