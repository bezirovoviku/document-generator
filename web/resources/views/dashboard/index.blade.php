@extends('layout.master')
@section('title', 'Dashboard')

@section('content')

<div class="container">

<h1 class="page-header sr-only">Dashboard</h1>

<div class="row">

{{-- right col --}}
<div class="col-md-7 col-md-push-5">

	{{-- admin --}}
	@can('update-limits')
		{!! Form::model($user, ['action' => 'DashboardController@updateLimits']) !!}
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<button type="submit" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-ok"></span> Apply</button>
				</div>
				<h3 class="panel-title">Admin tools</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="request_limit">Request limit</label>
					{!! Form::number('request_limit', null, ['id' => 'request_limit', 'class' => 'form-control', 'step' => 1, 'min' => 0]) !!}
					<p class="help-block">Maximum requests per month. <code>0</code> means no limit.</p>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	@endcan

	{{-- templates --}}
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-right">
				<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#addNewTemplate">
					<span class="glyphicon glyphicon-plus"></span> Add new template
				</button>
			</div>
			<h3 class="panel-title">Templates</h3>
		</div>


		<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th class="text-right">ID</th>
					<th class="text-right">Used</th>
					<th>Name</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse ($templates as $template)
				<tr>
					<td class="text-right">{{ $template->id }}</td>
					<td class="text-right">{{ $template->getUsageCount() }} &times;</td>
					<td>{{ $template->name }}</td>
					<td class="text-right">
						{!! Form::open(['action' => ['TemplateController@delete', $template->id]]) !!}
							<a href="{{ action('TemplateController@show', $template->id) }}" class="btn btn-xs btn-link">details</a>
							<button type="submit" class="btn btn-xs btn-link">delete</button>
						{!! Form::close() !!}
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="4" class="text-center text-muted">No templates</td>
				</tr>
				@endforelse
			</tbody>
		</table>
		</div>
	</div>

	{{-- add new template modal --}}
	{!! Form::open(['action' => 'TemplateController@upload', 'class' => 'form-horizontal', 'files' => true]) !!}
	<div class="modal fade" id="addNewTemplate" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><h4 class="modal-title"><span class="glyphicon glyphicon-plus"></span> Add new template</h4></div>
				<div class="modal-body">

					<div class="form-group">
						<label for="templateName" class="col-md-4 col-sm-3 control-label">Template name</label>
						<div class="col-md-8 col-sm-9">{!! Form::text('name', null, ['id' => 'templateName', 'class' => 'form-control']) !!}</div>
					</div>
					<div class="form-group form-group-file">
						<label for="template" class="col-md-4 col-sm-3 control-label">DOCX template file</label>
						<div class="col-md-8 col-sm-9">
							<div class="input-group">
								<span class="input-group-btn">
									<span class="btn btn-primary btn-file">
										Select file&hellip; {!! Form::file('template', null, ['id' => 'template']) !!}
									</span>
								</span>
								<input type="text" class="form-control" readonly>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Upload template</button>
				</div>
			</div>
		</div>
	</div>
	{!! Form::close() !!}

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
					<th>Template</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse ($requests as $request)
				<tr>
					<td class="text-right">{{ $request->id }}</td>
					@if (!$request->template->deleted_at)
						<td>{{ $request->template->name }}</td>
					@else
						<td class="text-muted"><s>{{ $request->template->name }}</s></td>
					@endif
					<td>@include('partial.request_status', ['request' => $request])</td>
					<td><a href="{{ action('RequestController@show', $request->id) }}" class="btn btn-xs btn-link">details</a></td>
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

{{-- left col --}}
<div class="col-md-5 col-md-pull-7">

	{{-- API key --}}
	{!! Form::open(['action' => 'DashboardController@regenerateApiKey']) !!}
	<div class="panel panel-default panel-api-key">
		<div class="panel-heading">
			<div class="pull-right">
				<button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span> Regenerate</button>
			</div>
			<h3 class="panel-title">Your API key</h3>
		</div>
		<div class="panel-body">
			<div class="form-group form-group-lg">
				<input type="text" class="form-control text-center" disabled value="{{ $user->api_key }}">
			</div>
			<p>Use this key in your application to make requests. For more informations, read <a href="http://docs.docgen.apiary.io">the docs</a> please.</p>
		</div>
	</div>
	{!! Form::close() !!}

	{{-- usage history --}}
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Usage history</h3>
		</div>
		<div class="panel-body">
			<canvas id="usageChart" style="width:100%;"></canvas>
			<hr>
			{{-- usage progress bar --}}
			@if ($user->request_limit)
				<p>You have used <strong>{{ $requestsUsed }} of {{ $user->request_limit }}</strong> allowed requests this month.</p>
				<div class="progress">
					<div class="progress-bar progress-bar-{{ $requestsPercentage < 66 ? 'success' : ($requestsPercentage < 80 ? 'warning' : 'danger') }}"
						style="width: {{ min($requestsPercentage, 100) }}%; min-width: 2em" role="progressbar" >
						{{ $requestsPercentage }}%
					</div>
				</div>
			@else
				<p>You have <strong>unlimited</strong> number of requests. Yeah!</p>
			@endif
		</div>
	</div>

</div>

</div>
</div>

@endsection

{{-- add Chart.JS and setup chart --}}
@section('custom_scripts')
@parent
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
	<script type="text/javascript">
		var ctx = document.getElementById("usageChart").getContext("2d");
		var data = {
			labels: {!! json_encode(array_keys($usageHistory)) !!},
			datasets: [{
				data: {!! json_encode(array_values($usageHistory)) !!},
				fillColor: "rgba(121, 151, 181, 0.4)",
				strokeColor: "rgba(44, 62, 80, 0.4)",
				highlightFill: "rgba(121, 151, 181, 0.75)",
			}]
		};
		var usageChart = new Chart(ctx).Bar(data);
	</script>
@stop
