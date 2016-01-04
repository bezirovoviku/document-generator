@extends('layout.master')

@section('content')

<div class="container">
	<div class="container-fluid">
		<div class="row">
				<div class="panel panel-default">
					<div class="panel-heading">{{ trans('auth.ResetPassword') }}</div>
					<div class="panel-body">

						<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="token" value="{{ $token }}">

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('auth.E-MailAddress') }}</label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="email" value="{{ old('email') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('auth.Password') }}</label>
								<div class="col-md-6">
									<input type="password" class="form-control" name="password">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">{{ trans('auth.ConfirmPassword') }}</label>
								<div class="col-md-6">
									<input type="password" class="form-control" name="password_confirmation">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										{{ trans('auth.ResetPassword') }}
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
