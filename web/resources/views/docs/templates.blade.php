@extends('layout.master')
@section('title', 'Documentation')

@section('content')

<div class="container">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="/docs">API</a></li>
  <li role="presentation" class="active"><a href="/docs/templates">Templates</a></li>
</ul>
<h1 class="page-header" id="templates">Templates</h1>

<p>Our system works with DOCX templates, format used by Microsoft Office (2007 and newer).
Data should be provided (<a href="http://docs.docgen.apiary.io">Apiary docs</a>) as JSON array,
where each document is single object containing all the data.</p>

<h2 id="templates-replacing">Basic word replacing</h2>
	<p>Our system simply replaces specified keywords inside document text. Keywords are expected in format <code>{KEYWORD}</code>.<p>
	<p>When your data are, for example, following:</p>
	<pre><code class="language-json" data-lang="json">{
	'name': 'Hildegard Testimen'
}</code></pre>
	<p>you can then place data name inside document by writing: <code>{name}</code>.</p>

<h2 id="templates-replacing-nested">Nested data</h2>
	<p>We also support multilevel objects in data. Simple use <code>{OBJ1.OBJ2.KEYWORD}</code>.<p>
	<p>When your data are, for example, following:</p>
	<pre><code class="language-json" data-lang="json">{
	'person: {
		'name': 'Hildegarda Testimenova',
		'age': 25
	}
}</code></pre>
	<p>you can then place name inside document by writing: <code>{person.name}</code> and age by: <code>{person.age}</code>.</p>

<h2 id="templates-cycles">Multiitem replacing</h2>
	<p>You can also have repeated items inside your data. To insert them,
	you will need to write <code>{foreach items as item}</code> on one line,
	where items is data keyword which contains multiple subitems, item is keyword that can be used inside foreach tag.
	Then place content that will be repeated for each item (that will be masked as <cpde>{item}</code>) and then create ending line with <code>{/foreach}</code>.</p>
	<p>It's vitaly important, that both <code>{foreach}</code> and <code>{/foreach}</code> tags must be on own line.
	Anything that will be on the same line will be deleted.</p>
	<p>Example data:</p>
	<pre><code class="language-json" data-lang="json">{
	items: [
		{ name: "Item 1", cost: 5 },
		{ name: "Item 2", cost: 6 }
	]
}</code></pre>
	<p>This is how your document would look like:</p>
	<img src="{{ asset('img/example_01.png') }}" alt="Example of foreach" />

<h2 id="templates-cycles-tables">Multiitem replacing in tables</h2>
	<p>To repeat table rows you need to write <code>{foreach items as item}</code> to one row, then add rows that will contain items,
	then add one last row with <code>{/foreach}</code> that will mark end of the mutliitem replacing.</p>
	<p>This is how your document would look like:</p>
	<img src="{{ asset('img/example_02.png') }}" alt="Example of foreach in table" />
<h2 id="templates-filtes">Filters in templates</h2>
	<p>Our generator supports some basic filters you can use on values.</p>
	<p>The syntax is <code>{value|filter parameter1 parameter2 ...}</code>.
	<p>If you use as a parameter string value, you have to enclose it in *value*. For example <code>{value|date *Y-m-d H:i:s*}</code>.
	<p>You can also use variables from your temlate data. For example if you have variable named format <code>{value|date format}</code>.
	<h3>Date filter</h3>
	<p>Use this filter to format a date value to a specific format. For supported formats see <a target="_blank" href="http://php.net/manual/en/function.date.php">available formats</a>.
	As a date value you can use a unix timestamp or dates supported by PHP function <a target="_blank" href="http://php.net/manual/en/function.strtotime.php">strtotime</a>.</p>
	<p>Examples:</p>
	<code><p>date = 1447137857; format = "Y-m-d:H-i-s"; {date|format} // => 2015-11-10:06-44-17</p>
	      <p>date = "2015-11-10"; format = "d/m/Y"; {date|format} // => 10/11/2015</p></code>
	<h3>String filters</h3>
	<p>To convert strings to uppercase letters use <code>{string|upper}</code> For lowercase letters use <code>{string|lower}</code>.</p>
	<p>Examples:</p>
	<code><p>string = "Hello World"; {string|upper} // => "HELLO WORLD"</p>
	      <p>string = "Hello World"; {string|lower} // => "hello world"</p></code>
	<h3>Number filter</h3>
	With this filter you can use EU format or US format by default. Usage id <code>{value|number standard [number of digits after decimal point]} // => 1 111<p></code>
	<p>Examples:</p>
	<code>
	<p>num = 1111; {num|number eu 2} // => 1 111,00</p>
	<p>num = 1111; {num|number us 2} // => 1,111.00</p>
	<p>num = 1111; {num|number eu} // => 1 111</p>
	<p>num = 1111; {num|number us} // => 1,111</p>
	<p>num = 1111; {num|number} // => 1,111</p>
	</code>
</div>
@endsection
