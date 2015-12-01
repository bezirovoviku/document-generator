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
            <pre id="json{{ $counter }}"><code>{{ $requests['json'] or '' }}</code></pre>
            <pre id="csv{{ $counter }}"><code>{{ $requests['csv'] or '' }}</code></pre>
            <pre id="xml{{ $counter }}"><code>{{ $requests['xml'] or '' }}</code></pre>
            <pre id="full-json{{ $counter }}"><code>{{ $requests['xml'] or '' }}</code></pre>
            <pre id="full-csv{{ $counter }}"><code>{{ $requests['xml'] or '' }}</code></pre>
            <pre id="full-xml{{ $counter }}"><code>{{ $requests['xml'] or '' }}</code></pre>
            <div class="btn-toolbar">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default active">Full request</button>
                    <button type="button" class="btn btn-default">Only data</button>
                </div> <div class="btn-group" data-counter="{{ $counter }}">
                    <button type="button" class="btn btn-default active" data-id="#data-json">JSON</button>
                    <button type="button" class="btn btn-default" data-id="#data-csv{{ $counter }}">CSV</button>
                    <button type="button" class="btn btn-default" data-id="#data-xml{{ $counter }}">XML</button>
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
