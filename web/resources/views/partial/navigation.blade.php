<a href="#content" class="sr-only sr-only-focusable">skip to content</a>
<nav class="navbar navbar-inverse" role="navigation">
	<div class="container">

		<ul class="nav navbar-nav">
			{{-- menu for all users --}}
			@foreach (['Home' => action('HomeController@index')] as $text => $url)
				<li class="{{ Request::url() == $url ? 'active' : '' }}">
					<a href="{{ $url }}">{{ $text }}</a>
				</li>
			@endforeach

			{{-- menu for all users --}}
			@foreach (['Documentation' => action('HomeController@docs')] as $text => $url)
				<li class="{{ Request::url() == $url ? 'active' : '' }}">
					<a href="{{ $url }}">{{ $text }}</a>
				</li>
			@endforeach

			{{-- menu for authenticated users --}}
			@if (Auth::check())
				@foreach (['Dashboard' => action('DashboardController@index')] as $text => $url)
					<li class="{{ Request::url() == $url ? 'active' : '' }}">
						<a href="{{ $url }}">{{ $text }}</a>
					</li>
				@endforeach
			@endif

			{{-- menu for admin --}}
			@foreach (['list-users' => ['All users', action('AdminController@users')], 'list-requests' => ['All requests', action('AdminController@requests')]] as $action => $data)
				@can ($action)
					<li class="{{ Request::url() == $data[1] ? 'active' : '' }}">
						<a href="{{ $data[1] }}">{{ $data[0] }}</a>
					</li>
				@endcan
			@endforeach
		</ul>

		{{-- right side for authenticated users --}}
		@if (Auth::check())
			<div class="navbar-right">
				<p class="navbar-text">Logged in as {{ Auth::user()->email }}, <a href="{{ action('HomeController@logout') }}" class="navbar-link">logout</a></p>
			</div>
		@endif
	</div>
</nav>
