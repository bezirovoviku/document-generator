<?php
global $counter;
@$counter++;
?>

<div class="example" data-counter="{{ $counter }}">

    <ul class="nav nav-tabs">
        <li class="active"><a href="#request{{ $counter }}">Request</a></li>
        <li><a href="#template{{ $counter }}">Template</a></li>
        <li><a href="#result{{ $counter }}">Result</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="request{{ $counter }}">
            <pre id="data-json-{{ $counter }}"><code class="language-json" data-lang="json">{{ $request['json'] or '' }}</code></pre>
            <pre id="data-csv-{{ $counter }}"><code class="language-csv" data-lang="csv">{{ $request['csv'] or '' }}</code></pre>
            <pre id="data-xml-{{ $counter }}"><code class="language-xml" data-lang="xml">{{ $request['xml'] or '' }}</code></pre>
            <pre id="full-json-{{ $counter }}"><code class="language-json" data-lang="json">{
    template_id: 1,
    data_type: 'json',
    data: '{{ $request['json'] or '' }}'
}</code></pre>
            <pre id="full-csv-{{ $counter }}"><code class="language-json" data-lang="json">{
    template_id: 1,
    data_type: 'csv',
    data: '{{ $request['csv'] or '' }}'
}</code></pre>
            <pre id="full-xml-{{ $counter }}"><code class="language-json" data-lang="json">{
    template_id: 1,
    data_type: 'xml',
    data: '&lt;root&gt;&lt;document&gt;{{ $request['xml'] or '' }}&lt;/document&gt;&lt;/root&gt;'
}</code></pre>
            <div class="btn-toolbar">
                <div class="btn-group pull-right" data-counter="{{ $counter }}">
                    <button type="button" class="btn btn-default active request-type" data-requesttype="full">Full request</button>
                    <button type="button" class="btn btn-default request-type" data-requesttype="data">Only data</button>
                </div> <div class="btn-group" data-counter="{{ $counter }}">
                    <button type="button" class="btn btn-default active data-type" data-datatype="json">JSON</button>
                    @if (isset($request['csv']))
                    <button type="button" class="btn btn-default data-type" data-datatype="csv">CSV</button>
                    @endif
                    <button type="button" class="btn btn-default data-type" data-datatype="xml">XML</button>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="template{{ $counter }}">
            <pre><code>{{ $template or '' }}</code></pre>
        </div>

        <div class="tab-pane" id="result{{ $counter }}">
            <pre><code>{{ $result or '' }}</code></pre>
        </div>
    </div>

</div>
