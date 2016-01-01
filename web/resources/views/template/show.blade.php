@extends('layout.master')
@section('title', 'Template: ' . $template->name)

@section('content')

<div class="container">

	<h1 class="page-header">{{ $template->name }} <small>(template)</small></h1>

	<div class="row">
		<div class="col-xs-12 col-sm-8">
			{{-- make request panel --}}
			{!! Form::open(['action' => ['TemplateController@createRequest', $template->id], 'class' => 'form', 'files' => true]) !!}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Test it</h3>
				</div>

				<div class="panel-body">

					<div class="form-group">
						<div class="btn-toolbar">
							<div class="btn-group pull-right" data-toggle="buttons">
								<label class="btn btn-default active" data-toggle="tab" data-target="#direct">
									<input type="radio" name="input_type" value="direct"> Direct input
								</label>
								<label class="btn btn-default" data-toggle="tab" data-target="#file">
									<input type="radio" name="input_type" value="file"> File upload
								</label>
							</div>
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-default active">
									<input type="radio" name="data_type" value="json"> JSON
								</label>
								<label class="btn btn-default">
									<input type="radio" name="data_type" value="csv"> CSV
								</label>
								<label class="btn btn-default">
									<input type="radio" name="data_type" value="xml"> XML
								</label>
							</div>
						</div>
					</div>

					<div class="tab-content">

						{{-- direct input --}}
						<div class="tab-pane active" id="direct">
							<div class="form-group">
								<label for="request_data">Request data</label>
								<textarea id="request_data" name="data" class="form-control" rows="10"></textarea>
								<p class="help-block">Only the request's <code>data</code> filed as provided through API.</p>
							</div>
						</div>

						{{-- file --}}
						<div class="tab-pane" id="file">
							<div class="form-group form-group-file">
								<label for="template" class="control-label">Request data file</label>
								<div class="input-group">
									<span class="input-group-btn">
										<span class="btn btn-primary btn-file">
											Select file&hellip; {!! Form::file('template', null, ['id' => 'template']) !!}
										</span>
									</span>
									<input type="text" class="form-control" readonly>
								</div>
								<p class="help-block">Only the contents of request's <code>data</code> field saved in a file (please use UTF-8 encoding).</p>
							</div>
						</div>

					</div>

					<div class="form-group">
						<label for="callback_url">Callback URL</label>
						{!! Form::text('callback_url', null, ['id' => 'callback_url', 'class' => 'form-control', 'placeholder' => 'optional']) !!}
					</div>

					<button type="submit" class="btn btn-primary pull-right">Submit a request</button>
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
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse ($requests as $request)
							<tr>
								<td class="text-right">{{ $request->id }}</td>
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
	</div>

</div>

@endsection

{{-- add script for switching between direct and file input  --}}
@section('scripts')
@parent
	<script type="text/javascript">

	</script>
@stop
