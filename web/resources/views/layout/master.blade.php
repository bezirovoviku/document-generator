<!doctype html>
<html lang="cs">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>@yield('title') | {{ Config::get('app.name') }}</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
	@yield('header')
</head>
<body class="@yield('body_class')">

	@include('partial.navigation')
	@include('partial.messages')

	<div id="content">
		@yield('content')
	</div>

	<div id="footer">
		<div class="container">
			© {{ Config::get('app.name') }} {{ date('Y') }}
		</div>
	</div>

	@section('scripts')
	<script type="text/javascript" src="https://code.jquery.com/jquery.js"></script>
	<script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
	@show

</body>
</html>
