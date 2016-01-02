@extends('layout.master')
@section('title', 'Request #' . $request->id)

@section('content')

<div class="container">

<h1 class="page-header">{{ $request->template->name }} #{{ $request->id }} <small>(request)</small></h1>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">Request data</div>
			<div class="panel-body">
				<pre><code>{{ json_encode($request->data, JSON_PRETTY_PRINT) }}</code></pre>
				{{-- <div id="document-tree"></div> --}}
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">Info</div>
			<table class="table">
				<tr>
					<th>Template</th>
					<td>
						@if (!$request->template->deleted_at)
							<a href="{{ action('TemplateController@show', $request->template->id) }}">{{ $request->template->name }}</a>
						@else
							<span class="text-muted"><s>{{ $request->template->name }}</s></span>
						@endif
					</td>
				</tr>

				<tr>
					<th>Created</th>
					<td>{{ $request->created_at }}</td>
				</tr>

				<tr>
					<th>Status</th>
					<td>@include('partial.request_status', ['request' => $request])</td>
				</tr>
				@if ($request->status == App\Request::STATUS_DONE)
				<tr>
					<th>Download</th>
					<td><a href="{{action('RequestController@download', ['request' => $request->id ])}}"><i class="glyphicon glyphicon-floppy-save"></i> Package <small>{{ $archive_size }}</small></a></td>
				</tr>
				@endif
			</table>
		</div>
	</div>
</div>

</div>
</div>

@endsection


@section('custom_scripts')
<script type="text/javascript">
	// TODO: Convert this to interactive tree
	// FIXME: values are not HTML safe! (in meantime use plain json display - see above)

	// var data = {!! json_encode($request->data) !!},
	// 	$target = $("#document-tree");
	//
	// function make_tree(data, prefix) {
	// 	var html = "<ul>";
	// 	for(var i in data) {
	// 		var item = data[i];
	// 		if (prefix)
	// 			i = prefix + i;
	//
	// 		html += "<li><span class='var'>" + i + "</span>";
	// 		if (typeof item === typeof 0 || typeof item === typeof "") {
	// 			html += "<span class='value pull-right'>" + item + "</span>";
	// 		} else {
	// 			html += make_tree(item);
	// 		}
	// 		html += "</li>";
	// 	}
	// 	html += "</ul>";
	// 	return html;
	// }
	//
	// $target.append(make_tree(data, 'document'));
</script>
@endsection
