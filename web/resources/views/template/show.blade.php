@extends('layout.master')
@section('title', 'Template: ' . $template->name)

@section('content')

<div class="container">

<h1 class="page-header">{{ $template->name }} <small>(template)</small></h1>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		{{-- make request textarea --}}
		{!! Form::open(['action' => ['TemplateController@createRequest', $template->id]]) !!}
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<button type="submit" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-ok"></span> Submit a request</button>
				</div>
				<h3 class="panel-title">Test it</h3>
			</div>

			<div class="panel-body">
				<label for="request_data">Request data</label>
				<textarea id="request_data" name="data" class="form-control" rows="10"></textarea>
				<p class="help-block">Request data as provided in API calls – in JSON format.</p>
			</div>

		</div>
		{!! Form::close() !!}
	</div>

	<div class="col-xs-12 col-sm-4">
		{{-- requests --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Requests</h3>
			</div>

			<div class="table-responsive">
			<table class="table table-hover" id="requests">
				<thead>
					<tr>
						<th class="text-right">ID</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($requests as $request)
					<tr>
						<td class="text-right">{{ $request->id }}</td>
						<td>@include('partial.request_status', ['request' => $request])</td>
					</tr>
					@empty
					<tr>
						<td colspan="3" class="text-center text-muted">No requests</td>
					</tr>
					@endforelse
				</tbody>
			</table>
			</div>

			<nav class="text-center">
				{!! $requests->setPath(Request::url())->fragment('requests')->render() !!}
			</nav>

		</div>
	</div>
</div>

</div>
</div>

@endsection
