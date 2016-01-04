@extends('layout.master')
@section('title', trans('docs.Documentation'))

@section('content')

<div class="container">
<div class="row">
<nav class="col-md-3 col-md-push-9 hidden-sm hidden-xs">
<div class="scrollspy">
	<strong>{{ trans('docs.Contents') }}:</strong>
	<ul class="nav nav-stacked">
		<li>
			<a href="#api">API</a>
		</li>
		<li>
			<a href="#templates">{{ trans('docs.Templates') }}</a>
			<ul class="nav nav-stacked">
				<li><a href="#templates-replacing">{{ trans('docs.WordReplacing') }}</a></li>
				<li><a href="#templates-replacing-nested">{{ trans('docs.NestedReplacing') }}</a></li>
				<li><a href="#templates-cycles">{{ trans('docs.MultiitemReplacing') }}</a></li>
				<li><a href="#templates-cycles-tables">{{ trans('docs.MultiitemReplacingInTables') }}</a></li>
				<li><a href="#templates-filters">{{ trans('docs.TemplateFilters') }}</a></li>
			</ul>
		</li>
		<li>
			<a href="#examples">{{ trans('docs.Examples') }}</a>
			<ul class="nav nav-stacked">
				<li><a href="#examples-01">{{ trans('docs.Example') }} #01</a></li>
			</ul>
		</li>
	</ul>
</div>
</nav>
<div class="col-md-9 col-md-pull-3 col-sm-12">

<h1 class="page-header">{{ trans('docs.Header1') }}</h1>

<h2 id="api">API</h2>
<p>{{ trans('docs.Description2') }}<a href="http://docs.docgen.apiary.io">Apiary docs</a>.</p>

<h2 id="templates">{{ trans('docs.Templates') }}</h2>
<p>{{ trans('docs.Description3') }}(<a href="http://docs.docgen.apiary.io">Apiary docs</a>){{ trans('docs.Description4') }}</p>

<h3 id="templates-replacing">{{ trans('docs.WordReplacing') }}</h3>
	<p>{{ trans('docs.Description5') }}<code>{KEYWORD}</code>.<p>

	@include('partial.example', ['request' => [
									'json' => '{"name":"Hildegard Testimen"}',
									'csv' => "name\r\nHildegard Testimen",
									'xml' => '<root><document><name>Hildegard Testimen</name></document></root>'
								],
								'template' => '{name}',
								 'result' => 'Hildegard Testimen'])

<h3 id="templates-replacing-nested">{{ trans('docs.NestedReplacing') }}</h3>
	<p>{{ trans('docs.Description6') }}<code>{OBJ1.OBJ2.KEYWORD}</code>.<p>

	@include('partial.example', ['request' => [
									'json' => '{"person":{"name":"Hildegard Testimen","age":25}}',
									'csv' => "person.name;person.age\r\nHildegard Testimen;25",
									'xml' => '<root><document><person><name>Hildegard Testimen</name><age>25</age></person></document></root>'
								],
								'template' => '{person.name} ({person.age})',
								'result' => 'Hildegard Testimen (25)'])

<h3 id="templates-cycles">{{ trans('docs.MultiitemReplacing') }}</h3>
	<p>{{ trans('docs.Description7') }}<code>{foreach items as item}</code>{{ trans('docs.Description8') }}<cpde>{item}</code>){{ trans('docs.Description9') }}<code>{/foreach}</code>.</p>
	<p>{{ trans('docs.Description10') }}<code>{foreach}</code> {{ trans('docs.and') }} <code>{/foreach}</code>{{ trans('docs.Description11') }}</p>
	<p>{{ trans('docs.Description12') }}:</p>

	@include('partial.example', ['request' => [
									'json' => '{"items":[{"name":"Item 1","cost":5},{"name":"Item 2","cost":6}]}',
									'xml' => '<root><document><items><name>Item 1</name><cost>5</cost></items><items><name>Item 2</name><cost>6</cost></items></document></root>'
								],
								'template' => "{foreach items as item}\r\n{item.name} ({item.cost})\r\n{/foreach}",
								'result' => "Item 1 (5)\r\nItem 2 (6)"])

<h3 id="templates-cycles-tables">{{ trans('docs.MultiitemReplacingInTables') }}</h3>
	<p>{{ trans('docs.Description13') }}<code>{foreach items as item}</code>{{ trans('docs.Description14') }}<code>{/foreach}</code>{{ trans('docs.Description15') }}</p>
	<p>{{ trans('docs.Description16') }}:</p>
	<img src="{{ asset('img/example_02.png') }}" alt="Example of foreach in table" />

<h3 id="templates-filters">{{ trans('docs.TemplateFilters') }}</h3>
	<p>{{ trans('docs.Description17') }}<code>{value|filter parameter1 parameter2 ...}</code>.
	{{ trans('docs.Description18') }}<code>*value*</code> ({{ trans('docs.Description19') }}: <code>{value|date *Y-m-d H:i:s*}</code>).</p>
	<p>{{ trans('docs.Description20') }}<code>format</code>: <code>{value|date format}</code>.</p>

	<h4>{{ trans('docs.DateFilter') }}</h4>
	<p>{{ trans('docs.Description21') }}<a target="_blank" href="http://php.net/manual/en/function.date.php">{{ trans('docs.Description22') }}</a>.
	{{ trans('docs.Description23') }}<a target="_blank" href="http://php.net/manual/en/function.strtotime.php">strtotime</a>.</p>

		@include('partial.example', ['request' => [
									'json' => '{"date1":1447137857,"format1":"Y-m-d:H-i-s","date2":"2015-11-10","format2":"d/m/Y"}',
									'csv' => "date1;format1;date2;format2\r\n1447137857;Y-m-d:H-i-s;2015-11-10;d/m/Y",
									'xml' => '<root><document><date1>1447137857</date1><format1>Y-m-d:H-i-s</format1><date2>2015-11-10</date2><format2>d/m/Y</format2></document></root>'
								],
								'template' => "{date1|format1}\r\n{date2|format2}",
								'result' => "2015-11-10:06-44-17\r\n10/11/2015"])

	<h4>{{ trans('docs.StringFilters') }}</h4>
	<p>{{ trans('docs.Description24') }}<code>{string|upper}</code>.{{ trans('docs.Description25') }}<code>{string|lower}</code>.</p>

		@include('partial.example', ['request' => [
									'json' => '{"string":"Hello World"}',
									'csv' => "string\r\nHello Wordl",
									'xml' => '<root><document><string>Hello World</string></document></root>'
								],
								'template' => "{string|upper}\r\n{string|lower}",
								'result' => "HELLO WORLD\r\nhello world"])

	<h4>{{ trans('docs.NumberFilter') }}</h4>
	<p>{{ trans('docs.Description26') }}<code>{value|number standard [number of digits after decimal point]} // => 1 111</code><p>

	@include('partial.example', ['request' => [
									'json' => '{"num":1111}',
									'csv' => "num\r\n1111",
									'xml' => '<root><document><num>1111</num></document></root>'
								],
								'template' => "{num|number eu 2}\r\n{num|number us 2}\r\n{num|number eu}\r\n{num|number us}\r\n{num|number}",
								'result' => "1 111,00\r\n1,111.00\r\n1 111\r\n1,111\r\n1,111"])


<h2 id="examples">{{ trans('docs.Examples') }}</h2>
<h3 id="examples-01">{{ trans('docs.Example') }} #01</h3>
<h4 id="examples-document">{{ trans('docs.Document') }}</h4>
	<img src="{{ asset('examples/template.png') }}" alt="template screenshot" class="img img-responsive" />

<h4 id="examples-data">Data</h4>
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

	<h4 id="examples-download">{{ trans('docs.Download') }}</h4>
	<ul>
		<li><a href="/examples/template.docx"><i class="glyphicon glyphicon-floppy-save"></i> template.docx - {{ trans('docs.exampleTemplate') }}</a></li>
		<li><a href="/examples/data.json"><i class="glyphicon glyphicon-floppy-save"></i> data.json - {{ trans('docs.exampleData') }}</a></li>
	</ul>

</div> {{-- end right col --}}
</div> {{-- end .row --}}
</div> {{-- end .container --}}

@endsection

@section('custom_scripts')
<script type="text/javascript">

	$('body').scrollspy({
		target: '.scrollspy',
		offset: 0
	});

	$('.scrollspy').affix({
		offset: {
			top: 0,
			bottom: function () {
				return (this.bottom = $('#footer').outerHeight(true))
			}
		}
	})

</script>
@endsection
