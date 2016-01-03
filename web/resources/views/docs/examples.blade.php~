@extends('layout.master')
@section('title', 'Documentation')

@section('content')

<div class="container">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="{{ action('DocsController@index') }}">API</a></li>
  <li role="presentation"><a href="{{ action('DocsController@templates') }}">{{ trans('docs.Templates') }}</a></li>
  <li role="presentation" class="active"><a href="{{ action('DocsController@examples') }}">{{ trans('docs.Examples') }}</a></li>
</ul>
<h1 class="page-header" id="api">{{ trans('docs.Examples') }}</h1>

<p>{{ trans('docs.Description1') }}:</p>
	<div style="margin:0;auto;width:100%;text-align:center;"><img src="{{ asset('examples/template.png') }}" width="70%" height="70%" /></div>
<p>{{ trans('docs.Description2') }}:</p>
<pre><code class="language-json" data-lang="json">{
	[
	{
		"nadpis": "Nadpis dokumentu 1",
		"items": [
			{ "name": "Item 1" },
			{ "name": "Item 2" },
			{ "name": "Item 3" }
		],
		"date": 1447085800,
		"format": "Y-m-d H:i:s",
		"number": 879872475
	},
	{
		"nadpis": "Nadpis dokumentu 2",
		"items": [
			{ "name": "Meti 1" },
			{ "name": "Meti 2" },
			{ "name": "Meti 3" },
			{ "name": "Meti 4" },
			{ "name": "Meti 5" },
			{ "name": "Meti 6" },
			{ "name": "Meti 7" }
		],
		"date": "2015-01-02 15:40:13",
		"format": "d.m.Y H:i:s",
		"number": 879872475
	}
]
}</code></pre>

{{ trans('docs.Downaload') }} <a href="/examples/template.docx">{{ trans('docs.template') }}</a> {{ trans('docs.and') }} <a href="/examples/data.json">data</a>.
</div>
@endsection
