<?php
global $counter;
@$counter++;
?>

<div class="example" data-counter="{{ $counter }}">

    <ul class="nav nav-tabs">
        <li class="active"><a href="#request{{ $counter }}">{{ trans('partial.Request') }}</a></li>
        <li><a href="#template{{ $counter }}">{{ trans('partial.Template') }}</a></li>
        <li><a href="#result{{ $counter }}">{{ trans('partial.Result') }}</a></li>
    </ul>

    <div class="btn-toolbar">
        <div class="btn-group pull-right" data-counter="{{ $counter }}">
            <button type="button" class="btn btn-default btn-sm active request-type" data-requesttype="full">{{ trans('partial.FullRequest') }}</button>
            <button type="button" class="btn btn-default btn-sm request-type" data-requesttype="data">{{ trans('partial.OnlyData') }}</button>
        </div> <div class="btn-group" data-counter="{{ $counter }}">
            <button type="button" class="btn btn-default btn-sm active data-type" data-datatype="json">JSON</button>
            @if (isset($request['csv']))
            <button type="button" class="btn btn-default btn-sm data-type" data-datatype="csv">CSV</button>
            @endif
            <button type="button" class="btn btn-default btn-sm data-type" data-datatype="xml">XML</button>
        </div>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="request{{ $counter }}">
            <?php $json = json_encode(json_decode($request['json']), JSON_PRETTY_PRINT); ?>
            <?php $dom = new \DOMDocument();
                  $dom->preserveWhiteSpace = FALSE;
                  $dom->loadXML($request['xml']);
                  $dom->formatOutput = TRUE;
                  $xml = "";
                  foreach ($dom->childNodes as $node) {
                    $xml .= $dom->saveXML($node);
                  } ?>
            <pre id="data-json-{{ $counter }}"><code class="language-json" data-lang="json">{{ $json or '' }}</code></pre>
            <pre id="data-csv-{{ $counter }}"><code class="language-csv" data-lang="csv">{{ $request['csv'] or '' }}</code></pre>
            <pre id="data-xml-{{ $counter }}"><code class="language-xml" data-lang="xml">{{ $xml or '' }}</code></pre>
            <pre id="full-json-{{ $counter }}"><code class="language-json" data-lang="json">{
    "template_id": 1,
    "data_type": "json",
    "data": '{{ $request['json'] or '' }}'
}</code></pre>
            <pre id="full-csv-{{ $counter }}"><code class="language-json" data-lang="json">{
    "template_id": 1,
    "data_type": "csv",
    "data": '{{ $request['csv'] or '' }}'
}</code></pre>
            <pre id="full-xml-{{ $counter }}"><code class="language-json" data-lang="json">{
    "template_id": 1,
    "data_type": "xml",
    "data": '{{ $request['xml'] or '' }}'
}</code></pre>
        </div>

        <div class="tab-pane" id="template{{ $counter }}">
            <pre><code>{{ $template or '' }}</code></pre>
        </div>

        <div class="tab-pane" id="result{{ $counter }}">
            <pre><code>{{ $result or '' }}</code></pre>
        </div>
    </div>

</div>
