<?php
global $counter;
@$counter++;
?>

<div class="example">

    <ul class="nav nav-tabs">
        <li class="active"><a href="#request{{ $counter }}">Request</a></li>
        <li><a href="#template{{ $counter }}">Template</a></li>
        <li><a href="#result{{ $counter }}">Result</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="request{{ $counter }}">
            <pre><code>{{ $request or '' }}</code></pre>

            <div class="btn-toolbar">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default active">Full request</button>
                    <button type="button" class="btn btn-default">Only data</button>
                </div> <div class="btn-group">
                    <button type="button" class="btn btn-default active">JSON</button>
                    <button type="button" class="btn btn-default">CSV</button>
                    <button type="button" class="btn btn-default">XML</button>
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
