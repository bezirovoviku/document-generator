@extends('layout.master')
@section('title', trans('template.TemplateHeader'). $template->name)

@section('content')

<div class="container">

	<h1 class="page-header">{{ $template->name }} <small>({{ trans('template.template') }})</small></h1>

	<div class="row">
		<div class="col-xs-12 col-sm-8">
			{{-- make request panel --}}
			{!! Form::open(['action' => ['TemplateController@createRequest', $template->id], 'class' => 'form', 'files' => true]) !!}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ trans('template.TestIt') }}</h3>
				</div>

				<div class="panel-body">

					<div class="form-group">
						<div class="btn-toolbar">
							{{-- TODO create HTML macro --}}
							<div class="btn-group pull-right" data-toggle="buttons">
								<label class="btn btn-default {{ Input::old('input_type') != 'file' ? 'active' : '' }}" data-toggle="tab" data-target="#direct">
									<input type="radio" name="input_type" value="direct" {{ Input::old('input_type') != 'file' ? 'checked' : '' }}> {{ trans('template.DirectInput') }}
								</label>
								<label class="btn btn-default {{ Input::old('input_type') == 'file' ? 'active' : '' }}" data-toggle="tab" data-target="#file">
									<input type="radio" name="input_type" value="file" {{ Input::old('input_type') == 'file' ? 'checked' : '' }}> {{ trans('template.FileUpload') }}
								</label>
							</div>
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-default {{ in_array(Input::old('data_type'), ['json', '']) ? 'active' : '' }}">
									<input type="radio" name="data_type" value="json" {{ in_array(Input::old('data_type'), ['json', '']) ? 'checked' : '' }}> JSON
								</label>
								<label class="btn btn-default {{ Input::old('data_type') == 'csv' ? 'active' : '' }}">
									<input type="radio" name="data_type" value="csv" {{ Input::old('data_type') == 'csv' ? 'checked' : '' }}> CSV
								</label>
								<label class="btn btn-default {{ Input::old('data_type') == 'xml' ? 'active' : '' }}">
									<input type="radio" name="data_type" value="xml" {{ Input::old('data_type') == 'xml' ? 'checked' : '' }}> XML
								</label>
							</div>
						</div>
					</div>

					<div class="tab-content">

						{{-- direct input --}}
						<div class="tab-pane {{ Input::old('input_type') != 'file' ? 'active' : '' }}" id="direct">
							<div class="form-group">
								<label for="request_data">{{ trans('template.RequestData') }}</label>
								<textarea id="request_data" name="data" class="form-control" rows="10">{{ Input::old('data') }}</textarea>
								<p class="help-block">{{ trans('template.Description1-1') }}<code>data</code>{{ trans('template.Description1-2') }}</p>
							</div>
						</div>

						{{-- file --}}
						<div class="tab-pane {{ Input::old('input_type') == 'file' ? 'active' : '' }}" id="file">
							<div class="form-group form-group-file">
								<label for="data_file" class="control-label">{{ trans('template.RequestDataFile') }}</label>
								<div class="input-group">
									<span class="input-group-btn">
										<span class="btn btn-primary btn-file">
											{{ trans('template.SelectFile') }}&hellip; {!! Form::file('data_file', null, ['id' => 'data_file']) !!}
										</span>
									</span>
									<input type="text" class="form-control" readonly>
								</div>
								<p class="help-block">Only the contents of request's <code>data</code> field saved in a file (please use UTF-8 encoding).</p>
							</div>
						</div>

					</div>

					<div class="form-group">
						<label for="callback_url">{{ trans('template.CallbackURL') }}</label>
						{!! Form::text('callback_url', null, ['id' => 'callback_url', 'class' => 'form-control', 'placeholder' => trans('template.optional')]) !!}
					</div>

					<button type="submit" class="btn btn-primary pull-right">{{ trans('template.SubmitARequest') }}</button>
				</div>

			</div>
			{!! Form::close() !!}
		</div>

		<div class="col-xs-12 col-sm-4">
			{{-- requests --}}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ trans('template.Request') }}</h3>
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
								<td><a href="{{ action('RequestController@show', $request->id) }}" class="btn btn-xs btn-link">{{ trans('template.details') }}</a></td>
							</tr>
							@empty
							<tr>
								<td colspan="3" class="text-center text-muted">{{ trans('template.NoRequest') }}</td>
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
