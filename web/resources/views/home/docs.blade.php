@extends('layout.master')
@section('title', 'Documentation')

@section('content')

<div class="container">
<div class="row">
<nav class="col-md-3 col-md-push-9 hidden-sm hidden-xs">
<div class="scrollspy">
	<strong>Contents:</strong>
	<ul class="nav nav-stacked">
		<li>
			<a href="#api">API</a>
		</li>
		<li>
			<a href="#templates">Templates</a>
			<ul class="nav nav-stacked">
				<li><a href="#templates-replacing">Word Replacing</a></li>
				<li><a href="#templates-replacing-nested">Nested Replacing</a></li>
				<li><a href="#templates-cycles">Multiitem Replacing</a></li>
				<li><a href="#templates-cycles-tables">Multiitem Replacing In Tables</a></li>
				<li><a href="#templates-filters">Template Filters</a></li>
			</ul>
		</li>
		<li>
			<a href="#examples">Examples</a>
			<ul class="nav nav-stacked">
				<li><a href="#examples-01">Example #01</a></li>
			</ul>
		</li>
	</ul>
</div>
</nav>
<div class="col-md-9 col-md-pull-3 col-sm-12">

<h1 class="page-header">Documentation</h1>

<h2 id="api">API</h2>
<p>For API documentation, please refer to our <a href="http://docs.docgen.apiary.io">Apiary docs</a></p>

<h2 id="templates">Templates</h2>
<p>Our system works with DOCX templates, format used by Microsoft Office (2007 and newer).
Data should be provided (<a href="http://docs.docgen.apiary.io">Apiary docs</a>) as JSON array,
where each document is single object containing all the data.</p>

<h3 id="templates-replacing">Word replacing</h3>
	<p>Our system simply replaces specified keywords inside document text. Keywords are expected in format <code>{KEYWORD}</code>.<p>

	@include('partial.example', ['request' => [
									'json' => '{name: "Hildegard Testimen"}',
									'csv' => "name\r\nHildegard Testimen",
									'xml' => '<name>Hildegard Testimen</name>'
								],
								'template' => '{name}',
								 'result' => 'Hildegard Testimen'])

<h3 id="templates-replacing-nested">Nested replacing</h3>
	<p>We also support multilevel objects in data. Simple use <code>{OBJ1.OBJ2.KEYWORD}</code>.<p>

	@include('partial.example', ['request' => [
									'json' => '{ person: { name: "Hildegard Testimen", age: 25 } }',
									'csv' => "person.name;person.age\r\nHildegard Testimen;25",
									'xml' => '<person><name>Hildegard Testimen</name><age>25</age></person>'
								],
								'template' => '{person.name} ({person.age})',
								'result' => 'Hildegard Testimen (25)'])

<h3 id="templates-cycles">Multiitem replacing</h3>
	<p>You can also have repeated items inside your data. To insert them,
	you will need to write <code>{foreach items as item}</code> on one line,
	where items is data keyword which contains multiple subitems, item is keyword that can be used inside foreach tag.
	Then place content that will be repeated for each item (that will be masked as <code>{item}</code>) and then create ending line with <code>{/foreach}</code>.</p>
	<p>It's vitaly important, that both <code>{foreach}</code> and <code>{/foreach}</code> tags must be on own line.
	Anything that will be on the same line will be deleted.</p>
	<p>Example data:</p>

	@include('partial.example', ['request' => [
									'json' => '{ items: [ { name: "Item 1", cost: 5 }, { name: "Item 2", cost: 6 } ]',
									'xml' => '<items><name>Item 1</name><cost>5</cost></items><items><name>Item 2</name><cost>6</cost></items>'
								],
								'template' => "{foreach items as item}\r\n{item.name} ({item.cost})\r\n{/foreach}",
								'result' => "Item 1 (5)\r\nItem 2 (6)"])

<h3 id="templates-cycles-tables">Multiitem replacing in tables</h3>
	<p>To repeat table rows you need to write <code>{foreach items as item}</code> to one row, then add rows that will contain items,
	then add one last row with <code>{/foreach}</code> that will mark end of the mutliitem replacing.</p>
	<p>This is how your document would look like:</p>
	<img src="{{ asset('img/example_02.png') }}" alt="Example of foreach in table" />

<h3 id="templates-filters">Template filters</h3>
	<p>Our generator supports some basic filters you can use on values. The syntax is <code>{value|filter parameter1 parameter2 ...}</code>.
	If you use as a parameter string value, you have to enclose it in <code>*value*</code> (for example: <code>{value|date *Y-m-d H:i:s*}</code>).</p>
	<p>You can also use variables from your plate data. For example if you have variable named <code>format</code>: <code>{value|date format}</code>.</p>

	<h4>Date filter</h4>
	<p>Use this filter to format a date value to a specific format. For supported formats see <a target="_blank" href="http://php.net/manual/en/function.date.php">available formats</a>.
	As a date value you can use a unix timestamp or dates supported by PHP function <a target="_blank" href="http://php.net/manual/en/function.strtotime.php">strtotime</a>.</p>

		@include('partial.example', ['request' => [
									'json' => '{ date1: 1447137857, format1: "Y-m-d:H-i-s", date2: "2015-11-10", format2 = "d/m/Y" }',
									'csv' => "date1;format1;date2;format2\r\n1447137857;Y-m-d:H-i-s;2015-11-10;d/m/Y",
									'xml' => '<date1>1447137857</date1><format1>Y-m-d:H-i-s</format1><date2>2015-11-10</date2><format2>d/m/Y</format2>'
								],
								'template' => "{date1|format1}\r\n{date2|format2}",
								'result' => "2015-11-10:06-44-17\r\n10/11/2015"])

	<h4>String filters</h4>
	<p>To convert strings to uppercase letters use <code>{string|upper}</code> For lowercase letters use <code>{string|lower}</code>.</p>

		@include('partial.example', ['request' => [
									'json' => '{ string: "Hello World" }',
									'csv' => "string\r\nHello Wordl",
									'xml' => '<string>Hello World</string>'
								],
								'template' => "{string|upper}\r\n{string|lower}",
								'result' => "HELLO WORLD\r\nhello world"])

	<h4>Number filter</h4>
	<p>With this filter you can use EU format or US format by default. Usage id <code>{value|number standard [number of digits after decimal point]} // => 1 111</code><p>

	@include('partial.example', ['request' => [
									'json' => '{ num: "1111" }',
									'csv' => "num\r\n1111",
									'xml' => '<num>1111</num>'
								],
								'template' => "{num|number eu 2}\r\n{num|number us 2}\r\n{num|number eu}\r\n{num|number us}\r\n{num|number}",
								'result' => "1 111,00\r\n1,111.00\r\n1 111\r\n1,111\r\n1,111"])


<h2 id="examples">Examples</h2>
<h3 id="examples-01">Example #01</h3>
<h4 id="examples-document">Document</h4>
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

	<h4 id="examples-download">Download</h4>
	<ul>
		<li><a href="/examples/template.docx"><i class="glyphicon glyphicon-floppy-save"></i> template.docx - example template</a></li>
		<li><a href="/examples/data.json"><i class="glyphicon glyphicon-floppy-save"></i> data.json - example data</a></li>
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
