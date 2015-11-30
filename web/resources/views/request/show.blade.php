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
				<div id="document-tree">
				</div>
			</div>
		</div>		
	</div>
	
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">Info</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="pull-right">
							@if (!$request->template->deleted_at)
								<a href="{{ action('TemplateController@show', $request->template->id) }}" class="btn btn-xs btn-link">{{ $request->template->name }}</a>
							@else
								<span class="text-muted"><s>{{ $request->template->name }}</s></span>
							@endif
						</span>
						
						Template
					</li>
					
					<li class="list-group-item">
						<span class="pull-right">{{ $request->created_at }}</span>
						Created
					</li>
					
					<li class="list-group-item">
						<span class="pull-right">@include('partial.request_status', ['request' => $request])</span>
						Status
					</li>
					@if ($request->status == App\Request::STATUS_DONE)
					<li class="list-group-item">
						<span class="pull-right"><a href="{{action('RequestController@download', ['request' => $request->id ])}}"><i class="glyphicon glyphicon-floppy-save"></i> Package <small>{{ $archive_size }}</small></a></span>
						Download
					</li>
					@endif
				</ul>
			</div>
		</div>
	</div>
</div>

</div>
</div>

@endsection

		
@section('custom_scripts')
<script type="text/javascript">
	//@TODO: Convert this to interactive tree
	
	var data = {!! $request->data !!},
		$target = $("#document-tree");
			
	function make_tree(data, prefix) {
		var html = "<ul>";
		for(var i in data) {
			var item = data[i];
			if (prefix)
				i = prefix + i;
			
			html += "<li><span class='var'>" + i + "</span>";
			if (typeof item === typeof 0 || typeof item === typeof "") {
				html += "<span class='value pull-right'>" + item + "</span>";
			} else {
				html += make_tree(item);
			}
			html += "</li>";
		}
		html += "</ul>";
		return html;
	}
	
	$target.append(make_tree(data, 'document'));
</script>
@endsection
