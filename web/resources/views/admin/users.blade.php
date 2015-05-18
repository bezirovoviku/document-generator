@extends('layout.master')
@section('title', 'All users')

@section('content')

<div class="container">

	<h1 class="page-header">All users</h1>

	<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="text-right">ID</th>
				<th>Email</th>
				<th>Role</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($users as $user)
			<tr>
				<td class="text-right">{{ $user->id }}</td>
				<td>{{ $user->email }}</td>
				<td>{{ $user->role }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="3" class="text-center text-muted">No users</td>
			</tr>
			@endforelse
		</tbody>
	</table>
	</div>

	<nav class="text-center">
		{!! $users->setPath(Request::url())->render() !!}
	</nav>

</div>

@endsection
