{{-- flash messages --}}
@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if(Session::has($msg))
	<div class="container">
		<div class="alert alert-{{ $msg }}">
			{{ Session::get($msg) }}
		</div>
	</div>
@endif
@endforeach

{{-- form erros --}}
@if($errors->any())
	@foreach($errors->all() as $error)
		<div class="container">
			<div class="alert alert-danger">
				{{ $error }}
			</div>
		</div>
	@endforeach
@endif
