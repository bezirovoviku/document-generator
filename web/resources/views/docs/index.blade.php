@extends('layout.master')
@section('title', 'Documentation')

@section('content')

<div class="container">
<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="{{ action('DocsController@index') }}">API</a></li>
  <li role="presentation"><a href="{{ action('DocsController@templates') }}">Templates</a></li>
  <li role="presentation"><a href="{{ action('DocsController@examples') }}">Examles</a></li>
</ul>
<h1 class="page-header" id="api">Api</h1>

<p>For API documentation, please refer to our <a href="http://docs.docgen.apiary.io">Apiary docs</a></p>
</div>
@endsection
