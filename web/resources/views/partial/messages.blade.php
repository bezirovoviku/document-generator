{{-- flash messages --}}
@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if(isset($$msg))
	<div class="alert alert-{{ $msg }}">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		{{ $$msg }}
	</div>
@endif
@endforeach

{{-- form erros --}}
@if($errors->any())
	@foreach($errors->all() as $error)
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			{{ $error }}
		</div>
	@endforeach
@endif
