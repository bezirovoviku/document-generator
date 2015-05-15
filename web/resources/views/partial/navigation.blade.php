<nav class="navbar navbar-default" role="navigation">
    <div class="container">

        <ul class="nav navbar-nav">
            {{-- menu for all users --}}
            @foreach (['Home' => action('HomeController@index')] as $text => $url)
                <li class="{{ Request::url() == $url ? 'active' : '' }}">
                    <a href="{{ $url }}">{{ $text }}</a>
                </li>
            @endforeach

            {{-- menu for authenticated users --}}
            {{-- TODO condition --}}
            @if (!Auth::check())
                @foreach (['Dashboard' => action('DashboardController@index')] as $text => $url)
                    <li class="{{ Request::url() == $url ? 'active' : '' }}">
                        <a href="{{ $url }}">{{ $text }}</a>
                    </li>
                @endforeach
            @endif

            {{-- menu for admin --}}
            {{-- TODO condition --}}
            @if (!Auth::check())
                @foreach (['Admin' => action('AdminController@index')] as $text => $url)
                    <li class="{{ Request::url() == $url ? 'active' : '' }}">
                        <a href="{{ $url }}">{{ $text }}</a>
                    </li>
                @endforeach
            @endif
        </ul>

        {{-- right side for authenticated users --}}
        {{-- TODO condition --}}
        {{-- @if (Auth::check()) --}}
        <div class="navbar-right">
            <p class="navbar-text">Signed in as xxx@xx.cz{{-- Auth::user()->$email --}}, <a href="#" class="navbar-link">logout</a></p>
        </div>
        {{-- @endif --}}
    </div>
</nav>
