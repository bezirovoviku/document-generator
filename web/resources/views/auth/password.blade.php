@extends('layout.master')
@section('title', 'Reset password')

@section('content')


<div class="container">


<div class="container-fluid">
	<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('auth.ResetPassword') }</div>
				<div class="panel-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('auth.E-MailAddress') }}</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									{{ trans('auth.SendPasswordResetLink') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
	</div>
</div>
</div>
@endsection
