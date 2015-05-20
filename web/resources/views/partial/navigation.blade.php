<a href="#content" class="sr-only sr-only-focusable">skip to content</a>
<nav class="navbar navbar-default" role="navigation">
	<div class="container">

		<ul class="nav navbar-nav">
			{{-- menu for all users --}}
			@foreach (['Home' => action('HomeController@index')] as $text => $url)
				<li class="{{ Request::url() == $url ? 'active' : '' }}">
					<a href="{{ $url }}">{{ $text }}</a>
				</li>
			@endforeach

			{{-- menu for all users --}}
			@foreach (['Docs' => action('DocsController@index')] as $text => $url)
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
			@if (Auth::check() && Auth::user()->isAdmin())
				@foreach (['All users' => action('AdminController@users'), 'All requests' => action('AdminController@requests')] as $text => $url)
					<li class="{{ Request::url() == $url ? 'active' : '' }}">
						<a href="{{ $url }}">{{ $text }}</a>
					</li>
				@endforeach
			@endif
		</ul>

		{{-- right side for authenticated users --}}
		@if (Auth::check())
			<div class="navbar-right">
				<p class="navbar-text">Logged in as {{ Auth::user()->email }}, <a href="{{ action('HomeController@logout') }}" class="navbar-link">logout</a></p>
			</div>
		@endif
	</div>
</nav>
