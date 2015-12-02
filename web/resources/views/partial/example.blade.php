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
            <pre id="data-json-{{ $counter }}"><code>{{ $requests['data']['json'] or '' }}</code></pre>
            <pre id="data-csv-{{ $counter }}"><code>{{ $requests['data']['csv'] or '' }}</code></pre>
            <pre id="data-xml-{{ $counter }}"><code>{{ $requests['data']['xml'] or '' }}</code></pre>
            <pre id="full-json-{{ $counter }}"><code>{{ $requests['full']['xml'] or '' }}</code></pre>
            <pre id="full-csv-{{ $counter }}"><code>{{ $requests['full']['xml'] or '' }}</code></pre>
            <pre id="full-xml-{{ $counter }}"><code>{{ $requests['full']['xml'] or '' }}</code></pre>
            <div class="btn-toolbar">
                <div class="btn-group pull-right" data-counter="{{ $counter }}">
                    <button type="button" class="btn btn-default active" class="request-type" data-requestType="full">Full request</button>
                    <button type="button" class="btn btn-default" class="request-type" data-requestType="data">Only data</button>
                </div> <div class="btn-group" data-counter="{{ $counter }}">
                    <button type="button" class="btn btn-default active" class="data-type" data-dataType="json">JSON</button>
                    <button type="button" class="btn btn-default" class="data-type" data-dataType="csv">CSV</button>
                    <button type="button" class="btn btn-default" class="data-type" data-dataType="xml">XML</button>
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
