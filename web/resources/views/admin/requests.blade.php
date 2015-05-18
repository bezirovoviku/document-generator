@extends('layout.master')
@section('title', 'All requests')

@section('content')

<div class="container">

	<h1 class="page-header">All requests</h1>

	<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="text-right">ID</th>
				<th>User</th>
				<th>Template</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($requests as $request)
			<tr>
				<td class="text-right">{{ $request->id }}</td>
				<td>{{ $request->user->email }}</td>
				@if (!$request->template->deleted_at)
					<td>{{ $request->template->name }}</td>
				@else
					<td class="text-muted"><s>{{ $request->template->name }}</s></td>
				@endif
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
		{!! $requests->setPath(Request::url())->render() !!}
	</nav>

</div>

@endsection
