{{-- flash messages --}}
@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if(Session::has($msg))
	<div class="container">
		<div class="alert alert-{{ $msg }}">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{{ $error }}
			</div>
		</div>
	@endforeach
@endif
