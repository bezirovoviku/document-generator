@extends('layout.master')
@section('title', 'Documentation')

@section('content')

<div class="container">

<h1 class="page-header">Docs</h1>

<h2 id="api">API</h2>
<p>For API documentation, please refer to our <a href="http://docs.docgen.apiary.io">Apiary docs</a></p>

<h2 id="templates">Templates</h2>
<p>Our system works with DOCX templates, format used by Microsoft Office (2007 and newer).
Data should be provided (<a href="http://docs.docgen.apiary.io">Apiary docs</a>) as JSON array,
where each document is single object containing all the data.</p>

<h3 id="templates-replacing">Basic word replacing</h3>
	<p>Our system simply replaces specified keywords inside document text. Keywords are expected in format <code>{KEYWORD}</code>.<p>
	<p>When your data are, for example, following:</p>
	<pre><code class="language-json" data-lang="json">{
	    'name': 'Hildegard Testimen'
	}</code></pre>
	<p>you can then place data name inside document by writing: <code>{name}</code>.</p>

<h3 id="templates-replacing-nested">Nested data</h3>
	<p>We also support multilevel objects in data. Simple use <code>{OBJ1.OBJ2.KEYWORD}</code>.<p>
	<p>When your data are, for example, following:</p>
	<pre><code class="language-json" data-lang="json">{
	    'person: {
	        'name': 'Hildegarda Testimenova',
	        'age': 25
	    }
	}</code></pre>
	<p>you can then place name inside document by writing: <code>{person.name}</code> and age by: <code>{person.age}</code>.</p>

<h3 id="templates-cycles">Multiitem replacing</h3>
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
	@TODO

<h3 id="templates-cycles-tables">Multiitem replacing in tables</h3>
	<p>To repeat table rows you need to write <code>{foreach items as item}</code> to one row, then add rows that will contain items,
	then add one last row with <code>{/foreach}</code> that will mark end of the mutliitem replacing.</p>
	<p>This is how your document would look like:</p>
	@TODO
</div>

@endsection
