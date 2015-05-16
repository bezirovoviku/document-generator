@extends('layout.master')
@section('title', 'Dashboard')

@section('content')

<div class="container">
<div class="row">

{{-- right col --}}
<div class="col-md-7 col-md-push-5">

    {{-- admin --}}
    {!! Form::open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-ok"></span> apply</button>
            </div>
            <h3 class="panel-title">Admin tools</h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="limit">Maximum requsts per month</label>
                {!! Form::number('limit', null, ['id' => 'limit', 'class' => 'form-control', 'step' => 1]) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    {{-- templates --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Templates</h3>
        </div>

        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-right">ID</th>
                    <th class="text-right">Used</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                <tr>
                    <td class="text-right">{{ $template->id }}</td>
                    <td class="text-right">TODO &times;</td>
                    <td>{{ $template->name }}</td>
                    <td><a href="#">details</a> | <a href="#">delete</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No templates</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {!! Form::open(['class' => 'form-horizontal']) !!}
        <div class="panel-body">
            <fieldset>
                <legend>
                    <button type="submit" class="btn btn-xs btn-primary pull-right"><span class="glyphicon glyphicon-upload"></span> Upload template</button>
                    Add new template
                </legend>
                <div class="form-group">
                    <label for="templateName" class="col-md-4 col-sm-3 control-label">Template name</label>
                    <div class="col-md-8 col-sm-9">{!! Form::text('name', null, ['id' => 'templateName', 'class' => 'form-control']) !!}</div>
                </div>
                <div class="form-group">
                    <label for="templateFile" class="col-md-4 col-sm-3 control-label">DOCX template file</label>
                    <div class="col-md-8 col-sm-9">{!! Form::file('file', null, ['id' => 'templateFile']) !!}</div>
                </div>
            </fieldset>
        </div>
        {!! Form::close() !!}
    </div>

    {{-- requests --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Requests</h3>
        </div>

        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-right">ID</th>
                    <th>Template</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                <tr>
                    <td class="text-right">{{ $request->id }}</td>
                    <td>{{ $request->template->name }}</td>
                    {{-- TODO: text colored by status --}}
                    <td class="text-{{ $request->status }}">@include('partial.request_status', ['request' => $request])</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No requests</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- TODO: pagging --}}
        {{-- <nav class="panel-footer">
            <ul class="pager">
                <li class="previous disabled"><a href="#">newer items</a></li>
                <li class="next"><a href="#">older items</a></li>
            </ul>
        </nav> --}}

    </div>

</div>

{{-- left col --}}
<div class="col-md-5 col-md-pull-7">

    {{-- API key --}}
    {!! Form::open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right">
                <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span> regenerate</button>
            </div>
            <h3 class="panel-title">Your API key</h3>
        </div>
        <div class="panel-body">
            <div class="form-group form-group-lg">
                <input type="text" class="form-control text-center" disabled value="{{ $user->apiKey }}">
            </div>
            <p>Use this key in your application to make requests. For more informations, read <a href="#">the docs</a> please.</p>
        </div>
    </div>
    {!! Form::close() !!}
    
    {{-- usage history --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Usage history</h3>
        </div>

        <img src="http://placehold.it/800x300" class="img-responsive">

        <div class="panel-body">
            <p>You have used <strong>60 of 100</strong> free requests this month.</p>
            <div class="progress">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="button" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-circle-arrow-up"></span> Raise the limit</button>
        </div>
    </div>

</div>

</div>
</div>

@endsection
